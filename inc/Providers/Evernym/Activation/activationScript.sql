CREATE TABLE `wordpressi_webhook` (
  `request_id` varchar(1000) NOT NULL,
  `thread_id` varchar(1000) NOT NULL,
  `message_type` varchar(1000) NOT NULL,
  `body` varchar(10000) NOT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
