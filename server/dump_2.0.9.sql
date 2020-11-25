DROP TABLE IF EXISTS `lsv_recordings`;
CREATE TABLE IF NOT EXISTS `lsv_recordings` (
  `recording_id` int(255) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `room_id` varchar(255) NOT NULL,
  `agent_id`  varchar(255) NULL,
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`recording_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;