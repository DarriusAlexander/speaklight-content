<?php


namespace Tenweb_Manager {


    class MigrationContent extends Migration
    {

        private $zip = null;
        private $archive_path = null;
        private $files = null;
        private $files_count = null;
        private $method;


        public function __construct()
        {

            parent::__construct();
            // if everything ok, create zip or tar archive
            $this->archive_path = self::get_tmp_dir() . "/" . self::getMigrationArchive();

            //tar.gz is set by default as prefered way to archive

            //to use gzencode function we need zlib
            $this->method = Helper::get_migration_archive_type();
        }

        /**
         * @param string $run_type
         *
         * @throws \Exception
         */
        public static function set_up($run_type = 'run')
        {
            if ($run_type == 'run') {
                Helper::store_migration_log('run_type_' . current_time('timestamp'), 'Run type is run.');
                $migration_content = new self();
                $migration_content->run($run_type);
            }
            if ($run_type == 'restart') {
                Helper::store_migration_log('run_type_' . current_time('timestamp'), 'Run type is restart.');
                $migration_content = self::get_object_file_content();
                $migration_content->run($run_type);
            }
        }


        /**
         * @param $run_type
         *
         * @throws \Exception
         */
        public function run($run_type)
        {
            if ($run_type == "run") {
                // check if zip or zlib extension exists
                if ($this->check_if_zip_extension_exists() === false && $this->check_if_zlib_extension_exists() === false) {
                    throw new \Exception("PHP zip or zlib extension is missing");
                }

                // check if config json exists
                if (!file_exists(self::get_tmp_dir() . "/" . self::MIGRATION_CONFIG_FILE_NAME)) {
                    throw new \Exception("Config json file is missing");
                }

                // check if db sql exists
                if (!file_exists(self::get_tmp_dir() . "/" . self::MIGRATION_DB_FILE_NAME)) {
                    throw new \Exception("Database file is missing");
                }
                $this->files = $this->get_content_files();

                $tar = new Archive_Tar($this->archive_path, true);
                Helper::store_migration_log('add_meta_data_to_tar', "Adding meta data..");
                $tar->addModify(array(self::get_tmp_dir() . "/" . self::MIGRATION_CONFIG_FILE_NAME, self::get_tmp_dir() . "/" . self::MIGRATION_DB_FILE_NAME), '10web_meta', self::get_tmp_dir());
                Helper::store_migration_log('added_meta_data_to_tar', "Meta data added..");
                $tar->_close();
            }

            // if everything ok, create zip or tar archive
            if ($this->method == 'zip') {
                // initialize archive object
                $this->zip = new \ZipArchive();
                $this->create_zip_archive();
            } else {
                //create tar.gz file for import
                //$this->zip = fopen($this->archive_path, 'ab');
                Helper::store_migration_log('start_create_tar_archive', $this->archive_path);
                $this->create_tar_archive_new($run_type);
            }
        }

        /**
         * @return bool
         */
        private function check_if_zip_extension_exists()
        {
            if (!extension_loaded('zip')) {
                return false;
            }

            Helper::store_migration_log('zip_extension_exists', 'Zip extension exists.');

            return true;
        }

        /**
         * @return bool
         */
        private function check_if_zlib_extension_exists()
        {
            if (!extension_loaded('zlib')) {
                return false;
            }

            Helper::store_migration_log('zlib_extension_exists', 'Zlib extension exists.');

            return true;
        }

        /**
         * @return array
         */
        private function get_content_files()
        {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            Helper::store_migration_log('start_get_content_files', 'Starting get_content_files function.');
            $all_files = array();

            // get wp-content files
            $all_files['wp-content'] = $this->method == 'zip' ? $this->get_files(WP_CONTENT_DIR) : $this->get_files_for_tar(WP_CONTENT_DIR, 'wpcontent');

            // get media files
            $uploads_dir = Migration::get_uploads_dir();
            if (strpos($uploads_dir, WP_CONTENT_DIR) === false) {
                $uploads_dir_basename = str_replace(ABSPATH, '', $uploads_dir);
                $all_files[$uploads_dir_basename] = $this->method == 'zip' ? $this->get_files($uploads_dir) : $this->get_files_for_tar($uploads_dir, 'uploads');
            }

            Helper::store_migration_log('end_get_content_files', 'End get_content_files function.');

            return $all_files;
        }

        /**
         * @param $dir
         *
         * @return array
         */
        private function get_files($dir)
        {
            $files = array();

            $innerIterator = new \RecursiveDirectoryIterator($dir, \RecursiveIteratorIterator::LEAVES_ONLY);
            $filter = $this->get_filter();
            $iterator = new \RecursiveIteratorIterator(new \RecursiveCallbackFilterIterator($innerIterator, $filter));

            foreach ($iterator as $file) {
                $file_path = $file->getRealPath();

                if (!is_dir($file_path) || $this->dir_is_empty($file_path)) {
                  $files[] = $file_path;
                }
            }

            return array_unique($files);
        }

        /**
         * @param $dir
         *
         * @return array
         */
        private function get_files_for_tar($dir, $type)
        {
            $unique_files = $this->get_files($dir);

            $files = array();
            $i = 0;
            $total_files_count = 0;
            $bulk_files = array();

            if (!empty($unique_files)) {
              foreach ($unique_files as $key => $file_path) {
                $bulk_files[] = $file_path;
                $i++;

                if ($i == TENWEB_MIGRATION_BULK_FILES_COUNT) {
                  $files[] = $bulk_files;
                  $total_files_count += $i;
                  $i = 0;
                  $bulk_files = array();
                }
              }
            }

            if ($i > 0) {
              $files[] = $bulk_files;
              $total_files_count += $i;
            }

            Helper::store_migration_log('total_files_count_in_tar_in_' . $type, $total_files_count . ' files in ' . $dir);

            return $files;
        }

        private function get_exclude_regex () {
          $excluded_files = array(
            'imagecache',
            'wp\-content\/w3tc',
            'wp\-content\/w3\-',
            'wp\-content\/wflogs',
            'wp\-content\/mu\-plugins\/wpmudev\-hosting\.php',
            'wp\-content\/mu\-plugins\/wpmudev\-hosting',
            'wp\-content\/mu\-plugins\/wpengine\-security\-auditor\.php',
            'wp\-content\/mu\-plugins\/slt\-force\-strong\-passwords\.php',
            'wp\-content\/mu\-plugins\/stop\-long\-comments\.php',
            'wp\-content\/mu\-plugins\/force\-strong\-passwords',
            'wp\-content\/object\-cache\.php$',
            'wp\-content\/envato\-backups',
            "wp\-content\/.*\-wprbackups$",
            "wp\-content\/.*\-backups$",
            'wp\-content\/updraft$',
            '\.htaccess',
            '\._htaccess',
            'wp\-config\-sample\.php',
            'mu\-plugins\/wpengine\-common',
            'mu\-plugins\/mu\-plugin\.php',
            'mu\-plugins\/kinsta\-mu\-plugins\.php',
            '\.svn$',
            '\.git$',
            '\.log$',
            '\.tmp$',
            '\.listing$',
            '\.cache$',
            '\.bak$',
            '\.swp$',
            '\~',
            '_wpeprivate',
            'wp\-content\/cache',
            'wp\-content\/cache_old',
            'ics\-importer\-cache',
            'gt\-cache',
            'plugins\/wpengine\-snapshot\/snapshots',
            'wp\-content\/backups',
            'wp\-content\/managewp',
            'wp\-content\/upgrade',
            'kinsta\-mu\-plugins',
            'wp\-content\/advanced\-cache\.php',
            'wp\-content\/wp\-cache\-config\.php',
            'wp\-content\/advanced\-cache\.php',
            'wp\-content\/wp\-cache\-config\.php',
            'ai1wm\-backups$',
            'uploads\/snapshots',
            'uploads\/backup',
            'uploads\/backups',
            'uploads\/ShortpixelBackups',
            'uploads\/backupbuddy_backups',
            'uploads\/backupbuddy_temp',
            'uploads\/webarx\-backup',
            'uploads\/iw\-backup',
            'uploads\/fw\-backup',
            'uploads\/10web_tmp',
            'uploads\/wp\-clone',
            'wp\-content\/bps\-backup',
            'wp\-content\/wptouch\-data',
            'aiowps_backups$',
            'aiowps\-backups$');

          $excluded_files[] = preg_quote(self::get_tmp_dir(), '/');

          return "/(" . implode("|", $excluded_files) . ")/i";
        }

        private function get_filter()
        {
            $path_regex = $this->get_exclude_regex();

            $filter = function ($file, $key, $iterator) use ($path_regex) {
                return !preg_match($path_regex, $file->getRealPath(), $matches);
            };

            return $filter;
        }

        /**
         * function for creating zip archive from wp-content dir
         *
         * @throws \Exception
         */
        private function create_zip_archive()
        {
            Helper::store_migration_log('start_create_zip_archive', 'Starting create_zip_archive function.');

            if ($this->zip->open($this->archive_path, \ZipArchive::CREATE) !== true) {
                throw new \Exception("Unable to open zip");
            }

            $all_files = $this->files;
            foreach ($all_files as $type => &$files) {
                if (!empty($files)) {
                    $t = current_time('timestamp');
                    Helper::store_migration_log('start_files_iteration_' . $t, 'Starting files iteration.');
                    foreach ($files as $key => $file_path) {
                        unset($files[$key]);
                        $this->files = $all_files;

                        if ($type == "wp-content") {
                            $relative_path = $type . "/" . substr($file_path, strlen(WP_CONTENT_DIR) + 1);
                        } else {
                            $relative_path = $type . "/" . substr($file_path, strlen(ABSPATH . $type) + 1);
                        }
                        if (!is_dir($file_path)) {
                            // add current file to archive
                            $this->add_file_to_archive($file_path, $relative_path);
                        } else {
                            $this->zip->addEmptyDir($relative_path);
                        }
                        $this->files_count++;
                        if ($this->files_count % 600 == 0) {
                            $this->archive_reload();
                        }
                        $this->check_for_restart();
                    }

                    Helper::store_migration_log('end_files_iteration_' . $t, 'End files iteration.');
                }
            }

            // add other necessary files to zip
            $this->add_external_files_to_archive();

            // after all close
            @$this->zip->close();

            Helper::store_migration_log('end_create_zip_archive', 'End create_zip_archive function.');
        }

        /**
         * @return bool
         */
        private function check_for_restart()
        {
            $max_exec_time_server = ini_get('max_execution_time');
            $start = get_site_transient(TENWEB_PREFIX . "_migration_start_time");
            $script_exec_time = microtime(true) - $start;

            if ($script_exec_time >= ((int)$max_exec_time_server - TENWEB_MIGRATION_EXEC_TIME_OFFSET) || ($this->files_count != 0 && $this->files_count % TENWEB_MIGRATION_MAX_FILES_RESTART == 0)) {
                $this->restart();

                return false;
            }
        }

        /**
         *
         */
        private function restart()
        {
            Helper::store_migration_log('start_restart_' . current_time('timestamp'), 'Starting restart.');
            $this->write_object_file();

            // close archives
            if ($this->method == 'zip') {
                @$this->zip->close();
            } else if ($this->method == 'gzip') {
                //@$this->tar->_close();
            }

            Helper::store_migration_log('end_restart_' . current_time('timestamp'), 'End restart.');
            self::restart_request();
            die();
        }

        /**
         *
         */
        private function write_object_file()
        {
            $content = serialize($this);
            file_put_contents(self::get_tmp_dir() . '/content_object.txt', $content);
            //$handle = fopen(self::get_tmp_dir() . '/content_object.txt', 'w');

            //$content = serialize($this);
            //fwrite($handle, $content);
            //fclose($handle);
        }

        private static function restart_request()
        {
            $url = add_query_arg(array('rest_route' => '/' . TENWEB_REST_NAMESPACE . '/restart_migration_file'), get_home_url() . "/");

            wp_remote_post($url, array('method' => 'POST', 'timeout' => 0.1, 'body' => array('tenweb_nonce' => wp_create_nonce('wp_rest'))));
        }

        /**
         * @param      $file_path
         * @param      $file_relative_path
         *
         * @return bool
         * @throws \Exception
         */
        private function add_file_to_archive($file_path, $file_relative_path)
        {

            if ($this->method == 'zip') {
                if ($this->zip->addFile($file_path, $file_relative_path) !== true) {
                    throw new \Exception("Unable to add " . $file_relative_path . " to " . MigrationContent::getMigrationArchive() . " archive");
                }
            } else {
                //tar
                // Convert chars for archives file names
                if (function_exists('iconv') && stripos(PHP_OS, 'win') === 0) {
                    $test = @iconv('ISO-8859-1', 'UTF-8', $file_relative_path);
                    if ($test) {
                        $file_relative_path = $test;
                    }
                }
                if (!$this->add_file_to_tar($file_path, $file_relative_path)) {
                    //error
                    return false;
                }


            }

            return true;

        }

        private function add_file_to_tar($file_name, $name_in_archive)
        {
            $chunk_size = 1024 * 1024 * 4;
            $filename = $name_in_archive;
            $filename_prefix = "";

            // Split filename larger than 100 chars
            if (100 < strlen($name_in_archive)) {
                $filename_offset = strlen($name_in_archive) - 100;
                $split_pos = strpos($name_in_archive, '/', $filename_offset);

                if ($split_pos === false) {
                    $split_pos = strrpos($name_in_archive, '/');
                }

                $filename = substr($name_in_archive, $split_pos + 1);
                $filename_prefix = substr($name_in_archive, 0, $split_pos);

                if (strlen($filename) > 100) {
                    $filename = substr($filename, -100);
                    trigger_error(
                        sprintf(
                        /* translators: $1 is the file name. */
                            esc_html__('File name "%1$s" is too long to be saved correctly in %2$s archive!', 'backwpup'),
                            $name_in_archive,
                            $this->method
                        ),
                        E_USER_WARNING
                    );
                }

                if (155 < strlen($filename_prefix)) {
                    trigger_error(
                        sprintf(
                        /* translators: $1 is the file name to use in the archive. */
                            esc_html__('File path "%1$s" is too long to be saved correctly in %2$s archive!', 'backwpup'),
                            $name_in_archive,
                            $this->method
                        ),
                        E_USER_WARNING
                    );
                }
            }

            //get file stat
            $file_stat = stat($file_name);
            if (!$file_stat) {
                return true;
            }
            $file_stat['size'] = abs((int)$file_stat['size']);
            //open file
            if ($file_stat['size'] > 0) {
                if (!($fd = fopen($file_name, 'rb'))) {
                    trigger_error(sprintf(__('Cannot open source file %s for archiving', 'buwd'), $file_name), E_USER_WARNING);

                    return true;
                }
            }
            //Set file user/group name if linux
            $fileowner = "Unknown";
            $filegroup = "Unknown";
            if (function_exists('posix_getpwuid')) {
                $info = posix_getpwuid($file_stat['uid']);
                $fileowner = $info['name'];
                $info = posix_getgrgid($file_stat['gid']);
                $filegroup = $info['name'];
            }

            // Generate the TAR header for this file
            $chunk = $this->make_tar_headers(
                $filename,
                $file_stat['mode'],
                $file_stat['uid'],
                $file_stat['gid'],
                $file_stat['size'],
                $file_stat['mtime'],
                0,
                $fileowner,
                $filegroup,
                $filename_prefix
            );


            if (isset($fd) && is_resource($fd)) {
                // read/write files in 512 bite Blocks
                while (($content = fread($fd, 512)) != '') {
                    $chunk .= pack("a512", $content);
                    if (strlen($chunk) >= $chunk_size) {
                        $chunk = gzencode($chunk);
                        fwrite($this->zip, $chunk);
                        $chunk = '';
                    }

                }
                fclose($fd);
            }

            if (!empty($chunk)) {
                $chunk = gzencode($chunk);
                fwrite($this->zip, $chunk);
            }

            return true;
        }

        /**
         * Make Tar Headers
         *
         * @param string  $name     The name of the file or directory. Known as Item.
         * @param string  $mode     The permissions for the item.
         * @param integer $uid      The owner ID.
         * @param integer $gid      The group ID.
         * @param integer $size     The size of the item.
         * @param integer $mtime    The time of the last modification.
         * @param integer $typeflag The type of the item. 0 for File and 5 for Directory.
         * @param string  $owner    The owner Name.
         * @param string  $group    The group Name.
         * @param string  $prefix   The item prefix.
         *
         * @return mixed|string
         */
        private function make_tar_headers($name, $mode, $uid, $gid, $size, $mtime, $typeflag, $owner, $group, $prefix)
        {

            // Generate the TAR header for this file
            $chunk = pack("a100a8a8a8a12a12a8a1a100a6a2a32a32a8a8a155a12", $name, //name of file  100
                sprintf("%07o", $mode), //file mode  8
                sprintf("%07o", $uid), //owner user ID  8
                sprintf("%07o", $gid), //owner group ID  8
                sprintf("%011o", $size), //length of file in bytes  12
                sprintf("%011o", $mtime), //modify time of file  12
                "        ", //checksum for header  8
                $typeflag, //type of file  0 or null = File, 5=Dir
                "", //name of linked file  100
                "ustar", //USTAR indicator  6
                "00", //USTAR version  2
                $owner, //owner user name 32
                $group, //owner group name 32
                "", //device major number 8
                "", //device minor number 8
                $prefix, //prefix for file name 155
                ""); //fill block 12

            // Computes the unsigned Checksum of a file's header
            $checksum = 0;
            for ($i = 0; $i < 512; $i++) {
                $checksum += ord(substr($chunk, $i, 1));
            }

            $checksum = pack("a8", sprintf("%07o", $checksum));
            $chunk = substr_replace($chunk, $checksum, 148, 8);

            return $chunk;
        }

        /**
         * function for reloading zip archive
         */
        private function archive_reload()
        {
            if (!is_resource($this->zip) && get_class($this->zip) == "ZipArchive") {
                @$this->zip->close();
                $this->zip = new \ZipArchive();
                $this->zip->open($this->archive_path, \ZipArchive::CREATE);
            }
        }

        /**
         *
         * @throws \Exception
         */
        private function add_external_files_to_archive()
        {
            // add config json to archive
            $this->add_file_to_archive(self::get_tmp_dir() . "/" . self::MIGRATION_CONFIG_FILE_NAME, '10web_meta/' . self::MIGRATION_CONFIG_FILE_NAME);

            // add db sql file to archive
            $this->add_file_to_archive(self::get_tmp_dir() . "/" . self::MIGRATION_DB_FILE_NAME, '10web_meta/' . self::MIGRATION_DB_FILE_NAME);

            // add wp-config.php to archive
            $this->add_file_to_archive(ABSPATH . "/wp-config.php", "wp-config.php");
        }

        /**
         * function for crating tar archive from wp-content dir
         *
         * @throws \Exception
         */
        private function create_tar_archive()
        {
          Helper::store_migration_log('start_create_tar_archive', 'Starting create_tar_archive function.');

          $all_files = $this->files;
          foreach ($all_files as $type => &$files) {
              if (!empty($files)) {
                  $t = current_time('timestamp');
                  Helper::store_migration_log('start_files_iteration_' . $t, 'Starting files iteration.');

                  foreach ($files as $key => $file_path) {
                      unset($files[$key]);
                      $this->files = $all_files;

                      if ($type == "wp-content") {
                          $relative_path = $type . "/" . substr($file_path, strlen(WP_CONTENT_DIR) + 1);
                      } else {
                          $relative_path = $type . "/" . substr($file_path, strlen(ABSPATH . $type) + 1);
                      }
                      // add current file to archive
                      if (!is_dir($file_path)) {
                          $this->add_file_to_archive($file_path, $relative_path);
                      } else if ($this->dir_is_empty($file_path)) {
                          $this->add_empty_folder_to_tar($file_path, $relative_path);
                      }
                      $this->files_count++;
                      $this->check_for_restart();
                  }

                  Helper::store_migration_log('end_files_iteration_' . $t, 'End files iteration.');
              }
          }

          // add other necessary files to zip
          $this->add_external_files_to_archive();

          // after all close
          $this->close_tar_archive();

          Helper::store_migration_log('end_create_tar_archive', 'End create_tar_archive function.');
        }

        private function create_tar_archive_new($run_type)
        {
          $tar = new Archive_Tar($this->archive_path, true);
          $tar->setIgnoreRegexp($this->get_exclude_regex());

          foreach ($this->files as $type => &$files) {
            if (!empty($files)) {
              foreach ($files as $key => $files_chunk) {
                unset($files[$key]);
                $tar->addModify($files_chunk, '', ABSPATH);

                $this->files_count += count($files_chunk);
                Helper::store_migration_log('tar_files_count', $this->files_count);
                $this->check_for_restart();
              }
            }
          }
        }

      /**
       * Check whether exec has been disabled.
       *
       * @access public
       * @static
       * @return bool
       */
      public static function is_exec() {

        // Is function avail
        if ( ! function_exists( 'exec' ) ) {
          return false;
        }

        // Is shell_exec disabled?
        if ( in_array( 'exec', array_map( 'trim', explode( ',', @ini_get( 'disable_functions' ) ) ), true ) ) {
          return false;
        }

        // Can we issue a simple echo command?
        $output = exec( 'echo 10web-manager' );
        if ( $output != '10web-manager' ) {
          return false;
        }

        return true;

      }

        /**
         * @param $dir
         *
         * @return bool
         */
        public function dir_is_empty($dir)
        {
            $handle = opendir($dir);
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    return false;
                }
            }

            return true;
        }

        /**
         * Tar an empty Folder to archive
         *
         * @param $folder_name
         * @param $name_in_archive
         *
         * @return bool True on success, false on failure.
         */
        private function add_empty_folder_to_tar($folder_name, $name_in_archive)
        {

            if (!is_resource($this->zip)) {
                return false;
            }

            $name_in_archive = trailingslashit($name_in_archive);

            $tar_filename = $name_in_archive;
            $tar_filename_prefix = '';

            // Split filename larger than 100 chars.
            if (100 < strlen($name_in_archive)) {
                $filename_offset = strlen($name_in_archive) - 100;
                $split_pos = strpos($name_in_archive, '/', $filename_offset);

                if ($split_pos === false) {
                    $split_pos = strrpos(untrailingslashit($name_in_archive), '/');
                }

                $tar_filename = substr($name_in_archive, $split_pos + 1);
                $tar_filename_prefix = substr($name_in_archive, 0, $split_pos);

                if (strlen($tar_filename) > 100) {
                    $tar_filename = substr($tar_filename, -100);
                    trigger_error(sprintf(/* translators: $1 is the name of the folder. $2 is the archive name.*/
                        esc_html__('Folder name "%1$s" is too long to be saved correctly in %2$s archive!', 'backwpup'), $name_in_archive, $this->method), E_USER_WARNING);
                }

                if (strlen($tar_filename_prefix) > 155) {
                    trigger_error(sprintf(/* translators: $1 is the name of the folder. $2 is the archive name.*/
                        esc_html__('Folder path "%1$s" is too long to be saved correctly in %2$s archive!', 'backwpup'), $name_in_archive, $this->method), E_USER_WARNING);
                }
            }

            $file_stat = @stat($folder_name);
            // Retrieve owner and group for the file.
            list($owner, $group) = $this->posix_getpwuid($file_stat['uid'], $file_stat['gid']);
            // Generate the TAR header for this file
            $header = $this->make_tar_headers($tar_filename, $file_stat['mode'], $file_stat['uid'], $file_stat['gid'], $file_stat['size'], $file_stat['mtime'], 5, $owner, $group, $tar_filename_prefix);

            $content = gzencode($header);

            fwrite($this->zip, $content);

            return true;
        }

        /**
         * @return mixed
         */
        private static function get_object_file_content()
        {
            $content = file_get_contents(self::get_tmp_dir() . '/content_object.txt');

            return unserialize($content);
        }

        /**
         * Close
         *
         * Closing the archive
         *
         * @return void
         */
        public function close_tar_archive()
        {


            if (!is_resource($this->zip)) {
                return;
            }

            // Write tar file end.
            if ($this->method = 'gzip') {
                fwrite($this->zip, pack('a1024', ''));
            }

            fclose($this->zip);
        }

        /**
         * Posix Get PW ID
         *
         * @param integer $uid The user ID.
         * @param integer $gid The group ID.
         *
         * @return array The owner and group in posix format
         */
        private function posix_getpwuid($uid, $gid)
        {
            // Set file user/group name if linux.
            $owner = esc_html__('Unknown', 'tenwebmanager');
            $group = esc_html__('Unknown', 'tenwebmanager');
            if (function_exists('posix_getpwuid')) {
                $info = posix_getpwuid($uid);
                $owner = $info['name'];
                $info = posix_getgrgid($gid);
                $group = $info['name'];
            }

            return array(
                $owner,
                $group,
            );
        }


    }
}