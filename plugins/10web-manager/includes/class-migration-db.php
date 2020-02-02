<?php

namespace Tenweb_Manager {

    class MigrationDB extends Migration
    {

        private $password;
        private $iv;


        public function __construct($password, $iv)
        {
            parent::__construct();

            $this->password = $password;
            $this->iv = $iv;

        }

        /**
         * @return bool
         * @throws \Exception
         */
        public function run()
        {
            // get tables
            $tables = $this->get_tables();

            $file_to_save_database_data = self::get_tmp_dir() . "/" . self::MIGRATION_DB_FILE_NAME;
            //open sql file
            if (($db_file = fopen($file_to_save_database_data, "w+")) !== false) {
                // add sql headers
                $sql_header_string = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";" . PHP_EOL . "SET time_zone = \"+00:00\";" . PHP_EOL . "SET foreign_key_checks = 0;" . PHP_EOL;

                $this->add_to_file($db_file, $sql_header_string . PHP_EOL);
                foreach ($tables as $table) {
                    // get create table syntax
                    if (($create_table = $this->get_create_table_str($table)) !== false) {
                        // run drop table if table exists
                        $drop_string = "DROP TABLE IF EXISTS `{$table}`";
                        $this->add_to_file($db_file, $drop_string . ';' . PHP_EOL);
                        // run create table
                        $this->add_to_file($db_file, $create_table . ';' . PHP_EOL . PHP_EOL);
                        // if table has data, run insert data

                        $this->insert_data($table, $db_file);

                        /*if ($insert_data) {
                          $this->add_to_file($db_file, $insert_data . ';' . PHP_EOL . PHP_EOL);
                        }*/
                    }
                }

                if (!TENWEB_MIGRATION_DEBUG && function_exists('openssl_encrypt')) {
                    $this->encrypt_db_file($db_file, $file_to_save_database_data);
                } else {
                    // after all close file
                    fclose($db_file);
                }

                return $file_to_save_database_data;
            } else {
                throw new \Exception("Unable open database sql file");
            }

        }


        /**
         * @param $table_name
         *
         * @return bool|string
         * @throws \Exception
         */
        private function insert_data($table_name, $db_file)
        {
            Helper::store_migration_log('start_insert_data', 'Start insert_data function.');
            // get table rows
            $table_data = $this->wpdb->get_results("SELECT * FROM `{$table_name}`", ARRAY_A);
            $columns = $this->get_table_structure($table_name);
            if (!empty($table_data)) {
                // generate insertion string
                $insert_rows = array();

                $insert_data = "INSERT INTO `{$table_name}` (" . implode(",", $this->get_table_columns($columns)) . ") VALUES ";

                $i = 0;
                $j = 1;
                $current_insert_data = $insert_data;

                foreach ($table_data as $table_row) {

                    $insert_values = array();
                    foreach ($table_row as $field_name => $field_value) {
                        if (empty($field_value)) {
                            if ($columns[$field_name]["Null"] != "NO") {
                                $insert_values[$field_name] = 'NULL';
                            } else {
                                if (strpos(strtolower($columns[$field_name]["Type"]), "int") !== false || $field_value === '0') {
                                    $insert_values[$field_name] = '0';
                                } else {
                                    $insert_values[$field_name] = "''";
                                }
                            }
                        } else {
                            $insert_values[$field_name] = "'" . addslashes($field_value) . "'";
                        }
                    }

                    $current_insert_data .= "(" . implode(",", array_values($insert_values)) . "),";

                    $i++;

                    if ($i == TENWEB_MIGRATION_BULK_DB_ROWS_COUNT) {
                        Helper::store_migration_log('row_limit_' . $j, 'Rows limit reached, adding to file.');
                        $current_insert_data = rtrim($current_insert_data, ",");
                        $this->add_to_file($db_file, $current_insert_data . ';' . PHP_EOL . PHP_EOL);
                        $i = 0;
                        $j++;
                        $current_insert_data = $insert_data;
                    }

                    //array_push($insert_rows, $insert_values);
                }

                if ($i > 0) {
                    Helper::store_migration_log('rows_remain', 'Rows reamain, adding to file.');
                    $current_insert_data = rtrim($current_insert_data, ",");
                    $this->add_to_file($db_file, $current_insert_data . ';' . PHP_EOL . PHP_EOL);
                }

                /*if (count($table_data) > 0) {
                    $insert_data = rtrim($insert_data, ",");
                    return $insert_data;
                }*/

                //return false;
            }

            Helper::store_migration_log('end_insert_data', 'End insert_data function.');
        }

        /**
         * @param $table_structure
         *
         * @return array
         */
        private function get_table_columns($table_structure)
        {
            $columns = array_keys($table_structure);
            $columns = array_map(function ($value) {
                return "`" . $value . "`";
            }, $columns);

            return $columns;
        }

        /**
         * @param $table_name
         *
         * @return array
         */
        private function get_table_structure($table_name)
        {
            $table_columns = array();
            $table_structure = $this->wpdb->get_results("DESCRIBE `{$table_name}`", ARRAY_A);

            foreach ($table_structure as $column) {
                $table_columns[$column["Field"]] = $column;
            }

            return $table_columns;
        }

        /**
         * @param $table_name
         *
         * @return mixed
         */
        private function get_create_table_str($table_name)
        {
            // get create table syntax
            $create_table = $this->wpdb->get_row("SHOW CREATE TABLE `{$table_name}`", ARRAY_N);

            return !empty($create_table[1]) ? $create_table[1] : false;
        }

        /**
         * @return array
         */
        private function get_tables()
        {
            Helper::store_migration_log('start_get_tables', 'Start get_tables function.');
            $tables = array();
            $data = $this->wpdb->get_results("SHOW TABLES FROM `{$this->wpdb->dbname}`");
            if (!empty($data)) {
                $key = 'Tables_in_' . $this->wpdb->dbname;
                foreach ($data as $table) {
                    if (strpos($table->$key, $this->wpdb->prefix) === 0) {
                        array_push($tables, $table->$key);
                    }
                }
            }

            Helper::store_migration_log('end_get_tables', 'End get_tables function.');

            return $tables;
        }

        /**
         * @param $file
         * @param $data
         *
         * @throws \Exception
         */
        private function add_to_file($file, $data)
        {
            if (fwrite($file, $data) === false) {
                throw new \Exception("Unable write data to db file");
            }
        }


        private function encrypt_db_file($file, $db_file_path)
        {
            $dest = tempnam(self::get_tmp_dir(), 'db');

            $key = $this->password;
            //initialization vector
            $iv = $this->iv;
            fseek($file, 0);
            $error = false;
            if ($fpOut = fopen($dest, 'w')) {
                while (!feof($file)) {
                    $plaintext = fread($file, 16 * 1000);
                    $ciphertext = openssl_encrypt($plaintext, 'AES-256-CBC', $key, null, $iv);
                    //$iv = substr($ciphertext, 0, 16);
                    fwrite($fpOut, $ciphertext);
                }
                fclose($fpOut);
            } else {
                $error = true;
            }

            unlink($db_file_path);
            rename($dest, $db_file_path);

            return $error ? false : true;
        }
    }
}