<?php

ini_set('include_path', '.');

require_once('env-parser.php');
require_once('file-util.php');
require_once('db-util.php');

function configure($config_files, $schema_files, $db_ids, $psa_modify_hash, $db_modify_hash, $settings_modify_hash, $crypt_settings_modify_hash, $settings_enum_modify_hash, $additional_modify_hash)
{
    // -- Creating inital DB --

    import_sql_scripts_to_databases($schema_files, $db_ids, $psa_modify_hash, $db_modify_hash, $settings_modify_hash, $crypt_settings_modify_hash, $settings_enum_modify_hash, $additional_modify_hash);

    // -- Writing config file --

    write_config_files($config_files, $psa_modify_hash, $db_modify_hash, $settings_modify_hash, $crypt_settings_modify_hash, $settings_enum_modify_hash, $additional_modify_hash);
}

function remove_app($schema_files, $db_ids, $psa_modify_hash, $db_modify_hash, $settings_modify_hash, $crypt_settings_modify_hash, $settings_enum_modify_hash, $additional_modify_hash)
{
    import_sql_scripts_to_databases($schema_files, $db_ids, $psa_modify_hash, $db_modify_hash, $settings_modify_hash, $crypt_settings_modify_hash, $settings_enum_modify_hash, $additional_modify_hash);
}

function import_sql_scripts_to_databases($schema_files, $db_ids, $psa_modify_hash, $db_modify_hash, $settings_modify_hash, $crypt_settings_modify_hash, $settings_enum_modify_hash, $additional_modify_hash)
{
    foreach($db_ids as $db_id){
        if(get_db_type($db_id) != "mysql")
        {
            print "FIXME: database type " . get_db_type($db_id) . " is not supported.\n";
            exit(1);
        }
        foreach($schema_files as $schema_filename => $schema_db_id){
            if($schema_db_id == $db_id){
                mysql_db_connect(get_db_address($db_id),
                                 get_db_login($db_id),
                                 get_db_password($db_id),
                                 get_db_name($db_id));

                $sql = modify_content($schema_filename,
                                      array_merge($psa_modify_hash,
                                            $db_modify_hash,
                                            $settings_modify_hash,
                                            $settings_enum_modify_hash,
					    $crypt_settings_modify_hash,
					    $additional_modify_hash));

                populate_mysql_db($sql);
            }
        }
    }
}

function write_config_files($config_files, $psa_modify_hash, $db_modify_hash, $settings_modify_hash, $crypt_settings_modify_hash, $settings_enum_modify_hash, $additional_modify_hash)
{
    foreach($config_files as $web_id => $arr2){
        foreach($arr2 as $arr){
            $template_file = $arr[0];
            $dest_path = get_web_dir($web_id).'/'.$arr[1];
            modify_file($template_file,
                    $dest_path,
                    array_merge($psa_modify_hash,
                          $db_modify_hash,
                          $settings_modify_hash,
                          $settings_enum_modify_hash,
			  $crypt_settings_modify_hash,
			  $additional_modify_hash));
			set_writable ($dest_path);
	}
    }
}

function set_writable ($file) {
    if(substr(PHP_OS, 0, 3) === 'win') {
        chmod($file, 666);
    } else {
        chmod($file, 0777);
    }
}

?>
