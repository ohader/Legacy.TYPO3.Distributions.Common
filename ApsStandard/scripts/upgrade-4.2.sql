ALTER TABLE be_groups ADD fileoper_perms tinyint(4) NOT NULL default '0';
ALTER TABLE sys_lockedrecords ADD feuserid int(11) unsigned NOT NULL default '0';
ALTER TABLE pages_language_overlay ADD doktype tinyint(3) unsigned NOT NULL default '0';
ALTER TABLE pages_language_overlay ADD url varchar(255) NOT NULL default '';
ALTER TABLE pages_language_overlay ADD urltype tinyint(4) unsigned NOT NULL default '0';
ALTER TABLE pages_language_overlay ADD shortcut int(10) unsigned NOT NULL default '0';
ALTER TABLE pages_language_overlay ADD shortcut_mode int(10) unsigned NOT NULL default '0';
ALTER TABLE sys_domain ADD redirectHttpStatusCode int(4) unsigned NOT NULL default '301';
ALTER TABLE sys_domain ADD forced tinyint(3) unsigned NOT NULL default '0';
ALTER TABLE tx_rtehtmlarea_acronym ADD static_lang_isocode int(11) unsigned NOT NULL default '0';
ALTER TABLE pages CHANGE t3ver_label t3ver_label varchar(255) default '';

ALTER TABLE pages CHANGE target target varchar(80) default '';

ALTER TABLE sys_history CHANGE tablename tablename varchar(255) default '';

ALTER TABLE sys_lockedrecords CHANGE record_table record_table varchar(255) default '';

ALTER TABLE sys_refindex CHANGE tablename tablename varchar(255) default '';

ALTER TABLE sys_refindex CHANGE ref_table ref_table varchar(255) default '';

ALTER TABLE sys_refindex DROP KEY lookup_string;
ALTER TABLE sys_refindex ADD KEY lookup_string (ref_string);
ALTER TABLE sys_refindex_res CHANGE tablename tablename varchar(255) default '';

ALTER TABLE sys_log CHANGE tablename tablename varchar(255) default '';

ALTER TABLE cache_pages CHANGE HTML HTML mediumblob;

ALTER TABLE pages_language_overlay CHANGE t3ver_label t3ver_label varchar(255) default '';

ALTER TABLE sys_template CHANGE t3ver_label t3ver_label varchar(255) default '';

ALTER TABLE tt_content CHANGE t3ver_label t3ver_label varchar(255) default '';

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
);


TRUNCATE TABLE be_sessions;
TRUNCATE TABLE cache_hash;
ALTER TABLE cache_hash CHANGE ident ident varchar(32) default '';
ALTER TABLE cache_hash DROP PRIMARY KEY;
ALTER TABLE cache_hash ADD id int(11) unsigned NOT NULL;
ALTER TABLE cache_hash ADD KEY hash (hash);
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
