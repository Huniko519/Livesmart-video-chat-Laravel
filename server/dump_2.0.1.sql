DROP TABLE IF EXISTS `lsv_users`;
CREATE TABLE `lsv_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `name`  varchar(255) NULL,
  `roomId` VARCHAR(255) NULL, 
  `first_name` VARCHAR(255) NULL, 
  `last_name` VARCHAR(255) NULL, 
  `token` VARCHAR(255) NULL, 
  `is_blocked` tinyint(4) DEFAULT 0 NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `lsv_rooms`;
CREATE TABLE `lsv_rooms` (
`room_id`  int(11) NOT NULL AUTO_INCREMENT ,
`agent`  varchar(255) NULL ,
`visitor`  varchar(255) NULL ,
`agenturl`  varchar(2048) NULL ,
`visitorurl`  varchar(2048) NULL ,
`password`  varchar(255) NULL ,
`roomId`  varchar(255) NULL ,
`datetime`  varchar(255) NULL ,
`duration`  varchar(255) NULL ,
`shortagenturl`  varchar(255) NULL,
`shortvisitorurl`  varchar(255) NULL,
`agent_id`  varchar(255) NULL,
`is_active` TINYINT NOT NULL DEFAULT '1',
`agenturl_broadcast`  varchar(2048) NULL,
`visitorurl_broadcast`  varchar(2048) NULL ,
`shortagenturl_broadcast`  varchar(2048) NULL,
`shortvisitorurl_broadcast`  varchar(2048) NULL,
`title` VARCHAR(2048) NULL,
PRIMARY KEY (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `chats`
-- ----------------------------
DROP TABLE IF EXISTS `lsv_chats`;
CREATE TABLE `lsv_chats` (
  `chat_id` int(255) NOT NULL AUTO_INCREMENT,
  `message` varchar(4000) DEFAULT NULL,
  `system` varchar(255) DEFAULT '',
  `participants` varchar(255) DEFAULT NULL,
  `from` varchar(255) DEFAULT NULL,
  `agent_id` varchar(255) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `room_id` varchar(255) DEFAULT NULL,
  `agent` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`chat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `lsv_agents`;
CREATE TABLE `lsv_agents` (
  `agent_id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `is_master` tinyint(4) NOT NULL DEFAULT '0',
  `roomId` VARCHAR(255) NULL, 
  `token` VARCHAR(255) NULL,
  PRIMARY KEY (`agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of agents
-- ----------------------------
INSERT INTO `lsv_agents` VALUES ('1', 'admin', 'first', 'last', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@admin.com', '1', '', '');

DROP TABLE IF EXISTS `lsv_feedbacks`;
CREATE TABLE IF NOT EXISTS `lsv_feedbacks` (
  `feedback_id` int(255) NOT NULL AUTO_INCREMENT,
  `rate` tinyint(4) NOT NULL DEFAULT '0',
  `text` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `room_id` varchar(255) NOT NULL,
  `date_added`  datetime NULL,
  PRIMARY KEY (`feedback_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `lsv_drawings`;
CREATE TABLE IF NOT EXISTS `lsv_drawings` (
  `drawing_id` int(255) NOT NULL AUTO_INCREMENT,
  `drawing` text DEFAULT NULL,
  `room_id` varchar(255) NOT NULL,
  PRIMARY KEY (`drawing_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

