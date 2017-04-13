CREATE TABLE IF NOT EXISTS `report_log_issued` (
  `id_report_log_issued` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `book_code` varchar(50) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL COMMENT '1 => transfer BCA, 2 => Mega CC, 3 => CC, 4 => Mega Priority',
  `note` text,
  `create_by_users` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_by_users` int(11) DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_report_log_issued`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;