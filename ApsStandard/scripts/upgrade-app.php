<?php
require_once('app-util.php');
require_once('file-util.php');

function upgrade_app($from_ver, $from_rel, $config_files, $db_ids, $psa_modify_hash, $db_modify_hash, $settings_modify_hash, $crypt_settings_modify_hash, $settings_enum_modify_hash, $additional_modify_hash){

    $upgrade_schema_files = array( ); // array('upgrade-1.0-1.sql' => 'main')

	// SQL queries are found in typo3 upgrade wizard, 'COMPARE Database' section
	// upgrade wizard url: $psa_modify_hash["@@ROOT_URL@@"].'/typo3/install/index.php?TYPO3_INSTALL[type]=update'

	$typo_config = $psa_modify_hash['@@ROOT_DIR@@'].'/typo3conf/localconf.php';
	if (file_exists($typo_config)) {
		require_once($typo_config);
	}
	$compat_version = $TYPO3_CONF_VARS['SYS']['compat_version'];
	$update_schema = 'upgrade-'.$compat_version.'.sql';
	if (file_exists($update_schema)) {
		$upgrade_schema_files = array($update_schema => 'main');
	} else if ($compat_version != '4.4') {
		if (preg_match("/^4\.0\./", $from_ver))
		{
			$upgrade_schema_files = array('upgrade-4.0.sql' => 'main');
		} else if (preg_match("/^4\.2\./", $from_ver)) {
			$upgrade_schema_files = array('upgrade-4.2.sql' => 'main');
		} else if (preg_match("/^4\.3\./", $from_ver)) {
			$upgrade_schema_files = array('upgrade-4.3.sql' => 'main');
		}
	}

    configure($config_files, $upgrade_schema_files, $db_ids, $psa_modify_hash, $db_modify_hash, $settings_modify_hash, $crypt_settings_modify_hash, $settings_enum_modify_hash, $additional_modify_hash);

	clean_cache($psa_modify_hash['@@ROOT_DIR@@']);

	return 0;

}

function clean_cache($root_dir) {
	$conf_dir = $root_dir.'/typo3conf';
	if ($handle = opendir($conf_dir)) {
		while ($file = readdir($handle)) {
			if (preg_match('/^temp_CACHED_/', $file)) {
				unlink($conf_dir.'/'.$file);
			}
		}
	}
}
?>
