ALTER TABLE be_groups ADD fileoper_perms tinyint(4) NOT NULL default '0';
ALTER TABLE pages ADD t3ver_move_id int(11) NOT NULL default '0';
ALTER TABLE sys_workspace ADD review_stage_edit tinyint(3) NOT NULL default '0';
ALTER TABLE sys_lockedrecords ADD feuserid int(11) unsigned NOT NULL default '0';
ALTER TABLE sys_log ADD KEY recuidIdx (recuid,uid);
ALTER TABLE fe_groups ADD crdate int(11) unsigned NOT NULL default '0';
ALTER TABLE fe_groups ADD cruser_id int(11) unsigned NOT NULL default '0';
ALTER TABLE fe_sessions ADD ses_permanent tinyint(1) unsigned NOT NULL default '0';
ALTER TABLE pages_language_overlay ADD doktype tinyint(3) unsigned NOT NULL default '0';
ALTER TABLE pages_language_overlay ADD url varchar(255) NOT NULL default '';
ALTER TABLE pages_language_overlay ADD urltype tinyint(4) unsigned NOT NULL default '0';
ALTER TABLE pages_language_overlay ADD shortcut int(10) unsigned NOT NULL default '0';
ALTER TABLE pages_language_overlay ADD shortcut_mode int(10) unsigned NOT NULL default '0';
ALTER TABLE sys_domain ADD crdate int(11) unsigned NOT NULL default '0';
ALTER TABLE sys_domain ADD cruser_id int(11) unsigned NOT NULL default '0';
ALTER TABLE sys_domain ADD redirectHttpStatusCode int(4) unsigned NOT NULL default '301';
ALTER TABLE sys_domain ADD forced tinyint(3) unsigned NOT NULL default '0';
ALTER TABLE tt_content ADD t3ver_move_id int(11) NOT NULL default '0';
ALTER TABLE tt_content ADD crdate int(11) unsigned NOT NULL default '0';
ALTER TABLE tt_content ADD cruser_id int(11) unsigned NOT NULL default '0';
ALTER TABLE tx_rtehtmlarea_acronym ADD static_lang_isocode int(11) unsigned NOT NULL default '0';
ALTER TABLE be_groups CHANGE title title varchar(50) default '';
ALTER TABLE be_groups CHANGE non_exclude_fields non_exclude_fields text;
ALTER TABLE be_groups CHANGE explicit_allowdeny explicit_allowdeny text;
ALTER TABLE be_groups CHANGE allowed_languages allowed_languages varchar(255) default '';
ALTER TABLE be_groups CHANGE custom_options custom_options text;
ALTER TABLE be_groups CHANGE db_mountpoints db_mountpoints varchar(255) default '';
ALTER TABLE be_groups CHANGE pagetypes_select pagetypes_select varchar(255) default '';
ALTER TABLE be_groups CHANGE tables_select tables_select text;
ALTER TABLE be_groups CHANGE tables_modify tables_modify text;
ALTER TABLE be_groups CHANGE groupMods groupMods text;
ALTER TABLE be_groups CHANGE file_mountpoints file_mountpoints varchar(255) default '';
ALTER TABLE be_groups CHANGE hidden hidden tinyint(1) unsigned default '0';
ALTER TABLE be_groups CHANGE deleted deleted tinyint(1) unsigned default '0';
ALTER TABLE be_groups CHANGE TSconfig TSconfig text;
ALTER TABLE be_groups CHANGE subgroup subgroup varchar(255) default '';
ALTER TABLE be_sessions CHANGE ses_data ses_data longtext;
ALTER TABLE be_users CHANGE username username varchar(50) default '';
ALTER TABLE be_users CHANGE usergroup usergroup varchar(255) default '';
ALTER TABLE be_users CHANGE disable disable tinyint(1) unsigned default '0';
ALTER TABLE be_users CHANGE db_mountpoints db_mountpoints varchar(255) default '';
ALTER TABLE be_users CHANGE userMods userMods varchar(255) default '';
ALTER TABLE be_users CHANGE allowed_languages allowed_languages varchar(255) default '';
ALTER TABLE be_users CHANGE uc uc text;
ALTER TABLE be_users CHANGE file_mountpoints file_mountpoints varchar(255) default '';
ALTER TABLE be_users CHANGE disableIPlock disableIPlock tinyint(1) unsigned default '0';
ALTER TABLE be_users CHANGE deleted deleted tinyint(1) unsigned default '0';
ALTER TABLE be_users CHANGE TSconfig TSconfig text;
ALTER TABLE be_users CHANGE usergroup_cached_list usergroup_cached_list varchar(255) default '';
ALTER TABLE cache_imagesizes CHANGE filename filename varchar(255) default '';
ALTER TABLE cache_imagesizes ENGINE=InnoDB;
ALTER TABLE pages CHANGE t3ver_label t3ver_label varchar(255) default '';
ALTER TABLE pages CHANGE deleted deleted tinyint(1) unsigned default '0';
ALTER TABLE pages CHANGE title title varchar(255) default '';
ALTER TABLE pages CHANGE TSconfig TSconfig text;
ALTER TABLE pages CHANGE url url varchar(255) default '';
ALTER TABLE pages CHANGE subtitle subtitle varchar(255) default '';
ALTER TABLE pages CHANGE target target varchar(80) default '';
ALTER TABLE pages CHANGE media media text;
ALTER TABLE pages CHANGE author author varchar(255) default '';
ALTER TABLE pages CHANGE nav_title nav_title varchar(255) default '';
ALTER TABLE pages DROP KEY parent;
ALTER TABLE pages ADD KEY parent (pid,sorting,deleted,hidden);
ALTER TABLE sys_be_shortcuts CHANGE module_name module_name varchar(255) default '';
ALTER TABLE sys_be_shortcuts CHANGE description description varchar(255) default '';
ALTER TABLE sys_filemounts CHANGE deleted deleted tinyint(1) unsigned default '0';
ALTER TABLE sys_workspace CHANGE deleted deleted tinyint(1) default '0';
ALTER TABLE sys_workspace CHANGE description description varchar(255) default '';
ALTER TABLE sys_workspace CHANGE adminusers adminusers varchar(255) default '';
ALTER TABLE sys_workspace CHANGE db_mountpoints db_mountpoints varchar(255) default '';
ALTER TABLE sys_workspace CHANGE file_mountpoints file_mountpoints varchar(255) default '';
ALTER TABLE sys_workspace CHANGE disable_autocreate disable_autocreate tinyint(1) default '0';
ALTER TABLE sys_history CHANGE history_data history_data mediumtext;
ALTER TABLE sys_history CHANGE fieldlist fieldlist text;
ALTER TABLE sys_history CHANGE tablename tablename varchar(255) default '';
ALTER TABLE sys_history CHANGE history_files history_files mediumtext;
ALTER TABLE sys_history DROP KEY recordident;
ALTER TABLE sys_history ADD KEY recordident (tablename,recuid,tstamp);
ALTER TABLE sys_lockedrecords CHANGE record_table record_table varchar(255) default '';
ALTER TABLE sys_refindex CHANGE tablename tablename varchar(255) default '';
ALTER TABLE sys_refindex CHANGE flexpointer flexpointer varchar(255) default '';
ALTER TABLE sys_refindex CHANGE deleted deleted tinyint(1) default '0';
ALTER TABLE sys_refindex CHANGE ref_table ref_table varchar(255) default '';
ALTER TABLE sys_refindex DROP KEY lookup_string;
ALTER TABLE sys_refindex ADD KEY lookup_string (ref_string);
ALTER TABLE sys_log CHANGE tablename tablename varchar(255) default '';
ALTER TABLE sys_log CHANGE details details varchar(255) default '';
ALTER TABLE sys_log CHANGE log_data log_data varchar(255) default '';
ALTER TABLE sys_log ENGINE=InnoDB;
ALTER TABLE cache_pages ENGINE=InnoDB;
ALTER TABLE cache_pagesection ENGINE=InnoDB;
ALTER TABLE cache_typo3temp_log CHANGE filename filename varchar(255) default '';
ALTER TABLE cache_typo3temp_log CHANGE orig_filename orig_filename varchar(255) default '';
ALTER TABLE cache_typo3temp_log ENGINE=InnoDB;
ALTER TABLE cache_md5params ENGINE=InnoDB;
ALTER TABLE fe_groups CHANGE title title varchar(50) default '';
ALTER TABLE fe_groups CHANGE subgroup subgroup tinytext;
ALTER TABLE fe_groups CHANGE TSconfig TSconfig text;
ALTER TABLE fe_session_data ENGINE=InnoDB;
ALTER TABLE fe_sessions ENGINE=InnoDB;
ALTER TABLE fe_users CHANGE usergroup usergroup tinytext;
ALTER TABLE fe_users CHANGE address address varchar(255) default '';
ALTER TABLE fe_users CHANGE image image tinytext;
ALTER TABLE fe_users CHANGE TSconfig TSconfig text;
ALTER TABLE fe_users DROP KEY parent;
ALTER TABLE fe_users ADD KEY parent (pid,username);
ALTER TABLE pages_language_overlay CHANGE t3ver_label t3ver_label varchar(255) default '';
ALTER TABLE pages_language_overlay CHANGE title title varchar(255) default '';
ALTER TABLE pages_language_overlay CHANGE subtitle subtitle varchar(255) default '';
ALTER TABLE pages_language_overlay CHANGE nav_title nav_title varchar(255) default '';
ALTER TABLE pages_language_overlay CHANGE media media tinytext;
ALTER TABLE pages_language_overlay CHANGE author author varchar(255) default '';
ALTER TABLE pages_language_overlay DROP KEY parent;
ALTER TABLE pages_language_overlay ADD KEY parent (pid,sys_language_uid);
ALTER TABLE static_template CHANGE title title varchar(255) default '';
ALTER TABLE static_template CHANGE include_static include_static tinytext;
ALTER TABLE static_template CHANGE constants constants text;
ALTER TABLE static_template CHANGE config config text;
ALTER TABLE static_template CHANGE editorcfg editorcfg text;
ALTER TABLE sys_template CHANGE t3ver_label t3ver_label varchar(255) default '';
ALTER TABLE sys_template CHANGE title title varchar(255) default '';
ALTER TABLE sys_template CHANGE sitetitle sitetitle varchar(255) default '';
ALTER TABLE sys_template CHANGE include_static include_static tinytext;
ALTER TABLE sys_template CHANGE include_static_file include_static_file text;
ALTER TABLE sys_template CHANGE constants constants text;
ALTER TABLE sys_template CHANGE config config text;
ALTER TABLE sys_template CHANGE editorcfg editorcfg text;
ALTER TABLE sys_template CHANGE resources resources text;
ALTER TABLE sys_template CHANGE basedOn basedOn tinytext;
ALTER TABLE sys_template DROP KEY parent;
ALTER TABLE sys_template ADD KEY parent (pid,sorting,deleted,hidden);
ALTER TABLE tt_content CHANGE t3ver_label t3ver_label varchar(255) default '';
ALTER TABLE tt_content CHANGE header header varchar(255) default '';
ALTER TABLE tt_content CHANGE image image text;
ALTER TABLE tt_content CHANGE media media text;
ALTER TABLE tt_content CHANGE records records text;
ALTER TABLE tt_content CHANGE pages pages tinytext;
ALTER TABLE tt_content CHANGE subheader subheader varchar(255) default '';
ALTER TABLE tt_content CHANGE header_link header_link varchar(255) default '';
ALTER TABLE tt_content CHANGE image_link image_link varchar(255) default '';
ALTER TABLE tt_content CHANGE multimedia multimedia tinytext;
ALTER TABLE tt_content DROP KEY parent;
ALTER TABLE tt_content ADD KEY parent (pid,sorting);
ALTER TABLE static_tsconfig_help CHANGE obj_string obj_string varchar(255) default '';
ALTER TABLE static_tsconfig_help CHANGE title title varchar(255) default '';
ALTER TABLE tx_impexp_presets CHANGE title title varchar(255) default '';
ALTER TABLE sys_note CHANGE subject subject varchar(255) default '';
ALTER TABLE tx_rtehtmlarea_acronym CHANGE term term varchar(255) default '';
ALTER TABLE tx_rtehtmlarea_acronym CHANGE acronym acronym varchar(255) default '';
ALTER TABLE fe_users DROP KEY pid;

CREATE TABLE IF NOT EXISTS cache_extensions (
	extkey varchar(60) NOT NULL default '',
	version varchar(10) NOT NULL default '',
	alldownloadcounter int(11) unsigned NOT NULL default '0',
	downloadcounter int(11) unsigned NOT NULL default '0',
	title varchar(150) NOT NULL default '',
	description mediumtext,
	state int(4) NOT NULL default '0',
	reviewstate int(4) NOT NULL default '0',
	category int(4) NOT NULL default '0',
	lastuploaddate int(11) unsigned NOT NULL default '0',
	dependencies mediumtext,
	authorname varchar(100) NOT NULL default '',
	authoremail varchar(100) NOT NULL default '',
	ownerusername varchar(50) NOT NULL default '',
	t3xfilemd5 varchar(35) NOT NULL default '',
	uploadcomment mediumtext,
	authorcompany varchar(100) NOT NULL default '',
	intversion int(11) NOT NULL default '0',
	lastversion int(3) NOT NULL default '0',
	lastreviewedversion int(3) NOT NULL default '0',
	PRIMARY KEY (extkey,version)
);

CREATE TABLE IF NOT EXISTS cachingframework_cache_hash (
	id int(11) unsigned NOT NULL auto_increment,
	identifier varchar(128) NOT NULL default '',
	crdate int(11) unsigned NOT NULL default '0',
	content mediumtext,
	lifetime int(11) unsigned NOT NULL default '0',
	PRIMARY KEY (id),
	KEY cache_id (identifier)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cachingframework_cache_hash_tags (
	id int(11) unsigned NOT NULL auto_increment,
	identifier varchar(128) NOT NULL default '',
	tag varchar(128) NOT NULL default '',
	PRIMARY KEY (id),
	KEY cache_id (identifier),
	KEY cache_tag (tag)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sys_registry (
	uid int(11) unsigned NOT NULL auto_increment,
	entry_namespace varchar(128) NOT NULL default '',
	entry_key varchar(128) NOT NULL default '',
	entry_value blob,
	PRIMARY KEY (uid),
	UNIQUE entry_identifier (entry_namespace,entry_key)
);

CREATE TABLE IF NOT EXISTS sys_refindex_words (
	wid int(11) NOT NULL default '0',
	baseword varchar(60) NOT NULL default '',
	PRIMARY KEY (wid)
);

CREATE TABLE IF NOT EXISTS sys_refindex_rel (
	rid int(11) NOT NULL default '0',
	wid int(11) NOT NULL default '0',
	PRIMARY KEY (rid,wid)
);

CREATE TABLE IF NOT EXISTS sys_refindex_res (
	rid int(11) NOT NULL default '0',
	tablename varchar(255) NOT NULL default '',
	recuid int(11) NOT NULL default '0',
	PRIMARY KEY (rid)
);

CREATE TABLE IF NOT EXISTS cachingframework_cache_pages (
	id int(11) unsigned NOT NULL auto_increment,
	identifier varchar(128) NOT NULL default '',
	crdate int(11) unsigned NOT NULL default '0',
	content mediumtext,
	lifetime int(11) unsigned NOT NULL default '0',
	PRIMARY KEY (id),
	KEY cache_id (identifier)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cachingframework_cache_pages_tags (
	id int(11) unsigned NOT NULL auto_increment,
	identifier varchar(128) NOT NULL default '',
	tag varchar(128) NOT NULL default '',
	PRIMARY KEY (id),
	KEY cache_id (identifier),
	KEY cache_tag (tag)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cachingframework_cache_pagesection (
	id int(11) unsigned NOT NULL auto_increment,
	identifier varchar(128) NOT NULL default '',
	crdate int(11) unsigned NOT NULL default '0',
	content mediumtext,
	lifetime int(11) unsigned NOT NULL default '0',
	PRIMARY KEY (id),
	KEY cache_id (identifier)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cachingframework_cache_pagesection_tags (
	id int(11) unsigned NOT NULL auto_increment,
	identifier varchar(128) NOT NULL default '',
	tag varchar(128) NOT NULL default '',
	PRIMARY KEY (id),
	KEY cache_id (identifier),
	KEY cache_tag (tag)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cache_treelist (
	md5hash char(32) NOT NULL default '',
	pid int(11) NOT NULL default '0',
	treelist text,
	tstamp int(11) NOT NULL default '0',
	expires int(11) unsigned NOT NULL default '0',
	PRIMARY KEY (md5hash)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS tx_scheduler_task (
    uid int(11) unsigned NOT NULL auto_increment,
    crdate int(11) unsigned NOT NULL default '0',
    disable tinyint(4) unsigned NOT NULL default '0',
    classname varchar(255) NOT NULL default '',
    nextexecution int(11) unsigned NOT NULL default '0',
    lastexecution_time int(11) unsigned NOT NULL default '0',
    lastexecution_failure text NOT NULL,
    lastexecution_context char(3) NOT NULL default '',
    serialized_task_object blob,
    serialized_executions blob,
    PRIMARY KEY (uid),
    KEY index_nextexecution (nextexecution)
) ENGINE=InnoDB;

TRUNCATE TABLE be_sessions;
TRUNCATE TABLE cache_hash;
ALTER TABLE cache_hash ADD KEY hash (hash);
ALTER TABLE cache_hash CHANGE ident ident varchar(32) default '';
ALTER TABLE cache_hash DROP PRIMARY KEY;
ALTER TABLE cache_hash ENGINE=InnoDB;
ALTER TABLE cache_hash ADD id int(11) unsigned NOT NULL;
ALTER TABLE cache_hash ADD PRIMARY KEY (id);
ALTER TABLE cache_hash CHANGE id id int(11) unsigned auto_increment;
ALTER TABLE sys_filemounts ADD sorting int(11) unsigned NOT NULL default '0';
ALTER TABLE fe_session_data ADD KEY tstamp (tstamp);
ALTER TABLE fe_users ADD first_name varchar(50) NOT NULL default '';
ALTER TABLE fe_users ADD middle_name varchar(50) NOT NULL default '';
ALTER TABLE fe_users ADD last_name varchar(50) NOT NULL default '';
ALTER TABLE tt_content ADD KEY language (l18n_parent,sys_language_uid);
ALTER TABLE sys_log CHANGE details details text;
ALTER TABLE sys_template CHANGE include_static zzz_deleted_include_static tinytext;
ALTER TABLE static_template RENAME zzz_deleted_static_template;
ALTER TABLE sys_template DROP zzz_deleted_include_static;
DROP TABLE zzz_deleted_static_template;
