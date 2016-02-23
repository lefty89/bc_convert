
#
# Table structure for table 'tx_bcconvert_domain_model_file'
#
CREATE TABLE tx_bcconvert_domain_model_file (

    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    name varchar(255) NOT NULL,
    hash varchar(40) NOT NULL,
    mime varchar(50) NOT NULL,
    size int(11) NOT NULL,
    complete tinyint(1) DEFAULT '0' NOT NULL,
    path varchar(255) NOT NULL,
    mirror int(11) DEFAULT '0' NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)
);

#
# Table structure for table 'tx_bcconvert_domain_model_queue'
#
CREATE TABLE tx_bcconvert_domain_model_queue (

    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    file int(11) DEFAULT '0' NOT NULL,
    video_bitrate int(11) DEFAULT '0' NOT NULL
    audio_bitrate int(11) DEFAULT '0' NOT NULL,
    format int(11) DEFAULT '0' NOT NULL,
    complete tinyint(1) DEFAULT '0' NOT NULL,

    time datetime DEFAULT '0000-00-00 00:00:00',

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)
);