# phpMyAdmin MySQL-Dump
# version 2.4.0
# http://www.phpmyadmin.net/ (download page)
#
# Host: localhost
# Generation Time: Apr 30, 2003 at 01:17 PM
# Server version: 3.23.54
# PHP Version: 4.2.4-dev
# Database : `xoops2`
# --------------------------------------------------------

#
# Table structure for table `yomi_key`
#

CREATE TABLE `yomi_key` (
    `word` VARCHAR(50)      DEFAULT NULL,
    `time` INT(10) UNSIGNED DEFAULT NULL,
    `ip`   VARCHAR(15)      DEFAULT NULL
)
    ENGINE = ISAM;
# --------------------------------------------------------

#
# Table structure for table `yomi_log`
#

CREATE TABLE `yomi_log` (
    `id`         INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`      VARCHAR(255)              DEFAULT NULL,
    `url`        VARCHAR(255)              DEFAULT NULL,
    `mark`       CHAR(3)                   DEFAULT NULL,
    `last_time`  VARCHAR(21)               DEFAULT NULL,
    `passwd`     VARCHAR(255)              DEFAULT NULL,
    `message`    TEXT,
    `comment`    TEXT,
    `name`       VARCHAR(255)              DEFAULT NULL,
    `mail`       VARCHAR(255)              DEFAULT NULL,
    `category`   VARCHAR(255)              DEFAULT NULL,
    `stamp`      INT(10) UNSIGNED          DEFAULT NULL,
    `banner`     VARCHAR(255)              DEFAULT NULL,
    `renew`      TINYINT(3) UNSIGNED       DEFAULT NULL,
    `ip`         VARCHAR(15)               DEFAULT NULL,
    `keywd`      VARCHAR(255)              DEFAULT NULL,
    `build_time` INT(10) UNSIGNED          DEFAULT NULL,
    `uid`        INT(5) UNSIGNED  NOT NULL DEFAULT '0',
    `rating`     DOUBLE(6, 4)     NOT NULL DEFAULT '0.0000',
    `votes`      INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `comments`   INT(11) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `uid` (`uid`)
)
    ENGINE = ISAM;
# --------------------------------------------------------

#
# Table structure for table `yomi_rank`
#

CREATE TABLE `yomi_rank` (
    `id`   INT(10) UNSIGNED DEFAULT NULL,
    `time` INT(10) UNSIGNED DEFAULT NULL,
    `ip`   VARCHAR(15)      DEFAULT NULL
)
    ENGINE = ISAM;
# --------------------------------------------------------

#
# Table structure for table `yomi_rev`
#

CREATE TABLE `yomi_rev` (
    `id`   INT(10) UNSIGNED DEFAULT NULL,
    `time` INT(10) UNSIGNED DEFAULT NULL,
    `ip`   VARCHAR(15)      DEFAULT NULL
)
    ENGINE = ISAM;

# --------------------------------------------------------

#
# Table structure for table `yomi_votedata`
#

CREATE TABLE `yomi_votedata` (
    ratingid        INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
    lid             INT(11) UNSIGNED    NOT NULL DEFAULT '0',
    ratinguser      INT(11) UNSIGNED    NOT NULL DEFAULT '0',
    rating          TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
    ratinghostname  VARCHAR(60)         NOT NULL DEFAULT '',
    ratingtimestamp INT(10)             NOT NULL DEFAULT '0',
    PRIMARY KEY (ratingid),
    KEY ratinguser (ratinguser),
    KEY ratinghostname (ratinghostname)
)
    ENGINE = ISAM;

#
# Table structure for table `yomi_comments`
#

CREATE TABLE `yomi_comments` (
    comment_id INT(8) UNSIGNED     NOT NULL AUTO_INCREMENT,
    pid        INT(8) UNSIGNED     NOT NULL DEFAULT '0',
    item_id    INT(8) UNSIGNED     NOT NULL DEFAULT '0',
    date       INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    user_id    INT(5) UNSIGNED     NOT NULL DEFAULT '0',
    ip         VARCHAR(15)         NOT NULL DEFAULT '',
    subject    VARCHAR(255)        NOT NULL DEFAULT '',
    comment    TEXT                NOT NULL,
    nohtml     TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    nosmiley   TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    noxcode    TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    icon       VARCHAR(25)         NOT NULL DEFAULT '',
    PRIMARY KEY (comment_id),
    KEY pid (pid),
    KEY item_id (item_id),
    KEY user_id (user_id),
    KEY subject (subject(40))
)
    ENGINE = ISAM;
