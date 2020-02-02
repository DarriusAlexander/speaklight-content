<?php

namespace Tenweb_Manager {

    class MigrationRun
    {

        private $error = null;
        private $archive_path;

        public function __construct()
        {
            ini_set("memory_limit", "-1");
            ini_set("max_execution_time", "500");
        }

        /**
         * @param MigrationConfig $migration_config
         *
         * @return bool
         */
        public function run_config(MigrationConfig $migration_config)
        {
            try {
                Helper::store_migration_log('start_run_config', 'Starting run_config function.');
                // run config migration
                $config_json_path = $migration_config->run();

                if (!file_exists($config_json_path)) {
                    throw new \Exception("Config file not found");
                }

                Helper::store_migration_log('end_run_config', 'End run_config function.');

                return true;

            } catch (\Exception $e) {
                // response errors
                $this->set_error($e->getMessage());
                // log errors
                Helper::store_migration_log('run_config_exception', $e->getMessage());
                Helper::set_error_log('migration_error', $e->getMessage());

                // if something is wrong we execute role back
                MigrationConfig::role_back();
            }

            return false;
        }


        /**
         * @param MigrationDB $migration_db
         *
         * @return bool
         */
        public function run_db(MigrationDB $migration_db)
        {
            try {
                Helper::store_migration_log('start_run_db', 'Starting run_db function.');
                // run config migration
                $db_path = $migration_db->run();
                if (!file_exists($db_path)) {
                    throw new \Exception("Db file not found");
                }

                Helper::store_migration_log('end_run_db', 'End run_db function.');

                return true;

            } catch (\Exception $e) {
                // response errors
                $this->set_error($e->getMessage());
                // log errors
                Helper::store_migration_log('run_db_exception', $e->getMessage());
                Helper::set_error_log('migration_error', $e->getMessage());

                // if something is wrong we execute role back
                MigrationDB::role_back();
            }

            return false;
        }

        /**
         * @param $run_type
         *
         * @return bool|string
         */
        public function run_content($run_type)
        {
            try {
                Helper::store_migration_log('start_run_content_' . current_time('timestamp'), 'Starting run_content function.');

                // run content migration
                MigrationContent::set_up($run_type);

                // remove files
                $this->remove_file(MigrationContent::get_tmp_dir() . "/" . MigrationContent::MIGRATION_CONFIG_FILE_NAME);

                $this->remove_file(MigrationContent::get_tmp_dir() . "/" . MigrationContent::MIGRATION_DB_FILE_NAME);

                $this->remove_file(MigrationContent::get_tmp_dir() . "/content_object.txt");

                // tell manager service, that migration file was created
                $this->migration_file_created();

                Helper::store_migration_log('end_run_content_' . current_time('timestamp'), 'End run_content function.');

                return MigrationContent::get_tmp_dir() . "/" . MigrationContent::getMigrationArchive();

            } catch (\Exception $e) {
                // response errors
                $this->set_error($e->getMessage());

                // log errors
                Helper::store_migration_log('run_content_exception', $e->getMessage());
                Helper::set_error_log('migration_error', $e->getMessage());
            }

            return false;
        }

        /**
         * @param $migration_file
         */
        public function migrate($migration_file, $start_byte, $chunk_size)
        {
            try {
                if (!file_exists($migration_file)) {
                    throw new \Exception("Migration archive not found");
                }
                $this->download_archive($migration_file, $start_byte, $chunk_size);

            } catch (\Exception $e) {
                $this->set_error($e->getMessage());
            }

        }


        /**
         * function for loading migration classes
         */
        public function load_classes()
        {
            include_once TENWEB_INCLUDES_DIR . '/class-migration.php';
            include_once TENWEB_INCLUDES_DIR . '/class-migration-db.php';
            include_once TENWEB_INCLUDES_DIR . '/class-migration-config.php';
            include_once TENWEB_INCLUDES_DIR . '/class-migration-content.php';
            include_once TENWEB_INCLUDES_DIR . "/pear/PEAR.php";
            include_once TENWEB_INCLUDES_DIR . "/pear/Tar.php";
        }


        /**
         * @param $error
         */
        public function set_error($error)
        {
            $this->error = $error;
        }

        /**
         * @return array
         */
        public function get_error()
        {
            return $this->error;
        }

        /**
         * @return bool|string
         *
         * @throws \Exception
         */
        private function migration_file_created()
        {
            include_once(TENWEB_INCLUDES_DIR . '/class-tenweb-services.php');

            $migrate_domain_id = get_site_transient('tenweb_migrate_domain_id');
            if (!$migrate_domain_id) {
                $url = TENWEB_API_URL . '/migrate-files';
            } else {
                $url = TENWEB_API_URL . '/domains/' . $migrate_domain_id . '/migrate-files';
            }
            $subdomain = get_site_transient('tenweb_subdomain');
            $region = get_site_transient('tenweb_migrate_region');
            $live = get_site_transient('tenweb_migrate_live');
            $tp_domain_name = get_site_transient('tenweb_tp_domain_name');

            if (!$subdomain) {
                $subdomain = null;
            }

            if (!$region) {
                $region = null;
            }

            $file_extension_array = explode('.', MigrationContent::getMigrationArchive());
            $file_extension = $file_extension_array[1];


            $args = array(
                "method"  => "POST",
                "timeout" => 50000,
                "body"    => array(
                    "domain_id"      => \TenwebServices::get_domain_id(),
                    "domain_name"    => site_url(),
                    "subdomain"      => $subdomain,
                    "region"         => $region,
                    "live"           => $live,
                    "file_extension" => $file_extension,
                    "file_size"      => (integer)filesize(MigrationContent::get_tmp_dir() . "/" . MigrationContent::getMigrationArchive()),
                    "encrypted"      => 0
                )
            );

            if (!empty($tp_domain_name)) {
                $args['body']['tp_domain_name'] = $tp_domain_name;
            }

            if (!TENWEB_MIGRATION_DEBUG && function_exists('openssl_encrypt')) {
                $args['body']['encrypted'] = 1;
            }

            $result = \TenwebServices::do_request($url, $args);

            if (is_wp_error($result) || wp_remote_retrieve_response_code($result) !== 200) {
                $this->set_error($result["response"]["message"]);
                throw new \Exception("Error while sending status to manager service: " . $result["response"]["message"]);
            }

            delete_site_transient('tenweb_subdomain');
            delete_site_transient('tenweb_migrate_live');
            delete_site_transient('tenweb_migrate_domain_id');
            delete_site_transient('tenweb_migrate_region');
            delete_site_transient('tenweb_tp_domain_name');

            return true;
        }

        /**
         * @param     $file
         *
         * @param     $start_byte
         * @param     $chunk_size
         *
         * @throws \Exception
         */
        private function download_archive($file, $start_byte, $chunk_size)
        {
            @error_reporting(0);
            $handle = fopen($file, 'rb');
            if ($handle === false) {
                throw new \Exception("Can not open " . $file);
            }

            while (ob_get_level() > 0) {
                @ob_end_clean();
            }

            // set headers

            header("Content-Description: File Transfer");
            header("Content-Type:  application/octet-stream");
            header("Content-disposition: attachment; filename=\"" . basename($file) . "\"");
            header("Content-Transfer-Encoding: Binary");
            header("Connection: Keep-Alive");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Pragma: public");
            header("Content-Length: " . filesize($file));
            header("Access-Control-Allow-Headers: Authorization, Content-Type, Accept-Ranges");
            header("Accept-Ranges: bytes");

            //OLD WAY
            //readfile($file);

            fseek($handle, $start_byte);
            #readfile($file);
            echo fread($handle, $chunk_size);

            fclose($handle);

            //fclose($handle);

            // after all remove file
            //$this->remove_file($file);

            exit();
        }


        /**
         * function for removing file
         *
         * @param bool $path
         */
        private function remove_file($path)
        {
            if (file_exists($path)) {
                unlink($path);
            }
        }

    }
}