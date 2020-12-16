-- 
-- Table structure for table `experiments`
-- 

CREATE TABLE `experiments` (
  `experiment_id` int(11) NOT NULL auto_increment,
  `experimenter` varchar(12) collate latin1_german2_ci default NULL,
  `status` tinyint(1) default NULL,
  `start_date` date default NULL,
  `start_time` time default NULL,
  `end_date` date default NULL,
  `end_time` time default NULL,
  `trial_count` int(11) default NULL,
  `num_hits` int(11) default NULL,
  `group_name` varchar(20) collate latin1_german2_ci NOT NULL,
  PRIMARY KEY  (`experiment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=60 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `friends`
-- 

CREATE TABLE `friends` (
  `id` int(10) NOT NULL auto_increment,
  `username` varchar(12) collate latin1_german2_ci NOT NULL,
  `friend_name` varchar(40) collate latin1_german2_ci default NULL,
  `phone` varchar(20) collate latin1_german2_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=191 DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci COMMENT='friends of telephone telepathy users' AUTO_INCREMENT=191 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `login_attempts`
-- 

CREATE TABLE `login_attempts` (
  `ip` varchar(30) collate latin1_german2_ci NOT NULL,
  `attempts` int(10) default NULL,
  PRIMARY KEY  (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci COMMENT='login attempts per ip address';

-- --------------------------------------------------------

-- 
-- Table structure for table `results`
-- 

-- CREATE ALGORITHM=UNDEFINED DEFINER=`dbo314053431`@`%` SQL SECURITY DEFINER VIEW `db314053431`.`results` AS select `db314053431`.`trials`.`experiment_id` AS `experiment_id`,`db314053431`.`trials`.`trial_num` AS `trial_num`,`db314053431`.`trials`.`start_date` AS `start_date`,`db314053431`.`trials`.`start_time` AS `start_time`,`db314053431`.`trials`.`end_date` AS `end_date`,`db314053431`.`trials`.`end_time` AS `end_time`,`db314053431`.`trials`.`sms_date` AS `sms_date`,`db314053431`.`trials`.`sms_time` AS `sms_time`,`db314053431`.`trials`.`guess_date` AS `guess_date`,`db314053431`.`trials`.`guess_time` AS `guess_time`,`db314053431`.`trials`.`call_date` AS `call_date`,`db314053431`.`trials`.`call_time` AS `call_time`,`db314053431`.`trials`.`participant_1` AS `participant_1`,`db314053431`.`trials`.`participant_2` AS `participant_2`,`db314053431`.`trials`.`caller_guess` AS `caller_guess`,`db314053431`.`trials`.`caller_actual` AS `caller_actual`,`db314053431`.`trials`.`caller_phone` AS `caller_phone`,`db314053431`.`trials`.`extension` AS `extension`,`db314053431`.`trials`.`hit` AS `hit`,`db314053431`.`trials`.`status` AS `status`,`db314053431`.`experiments`.`experimenter` AS `experimenter` from (`db314053431`.`trials` join `db314053431`.`experiments` on((`db314053431`.`trials`.`experiment_id` = `db314053431`.`experiments`.`experiment_id`)));
CREATE ALGORITHM=UNDEFINED VIEW `psiresea`.`results` AS select `psiresea`.`trials`.`experiment_id` AS `experiment_id`,`psiresea`.`trials`.`trial_num` AS `trial_num`,`psiresea`.`trials`.`start_date` AS `start_date`,`psiresea`.`trials`.`start_time` AS `start_time`,`psiresea`.`trials`.`end_date` AS `end_date`,`psiresea`.`trials`.`end_time` AS `end_time`,`psiresea`.`trials`.`sms_date` AS `sms_date`,`psiresea`.`trials`.`sms_time` AS `sms_time`,`psiresea`.`trials`.`guess_date` AS `guess_date`,`psiresea`.`trials`.`guess_time` AS `guess_time`,`psiresea`.`trials`.`call_date` AS `call_date`,`psiresea`.`trials`.`call_time` AS `call_time`,`psiresea`.`trials`.`participant_1` AS `participant_1`,`psiresea`.`trials`.`participant_2` AS `participant_2`,`psiresea`.`trials`.`caller_guess` AS `caller_guess`,`psiresea`.`trials`.`caller_actual` AS `caller_actual`,`psiresea`.`trials`.`caller_phone` AS `caller_phone`,`psiresea`.`trials`.`extension` AS `extension`,`psiresea`.`trials`.`hit` AS `hit`,`psiresea`.`trials`.`status` AS `status`,`psiresea`.`experiments`.`experimenter` AS `experimenter` from (`psiresea`.`trials` join `psiresea`.`experiments` on((`psiresea`.`trials`.`experiment_id` = `psiresea`.`experiments`.`experiment_id`)));

-- --------------------------------------------------------

-- 
-- Table structure for table `sms_queue`
-- 

CREATE TABLE `sms_queue` (
  `id` int(11) NOT NULL auto_increment,
  `callsid` varchar(50) collate latin1_german2_ci default NULL,
  `receiver` varchar(20) collate latin1_german2_ci default NULL,
  `sender` varchar(20) collate latin1_german2_ci default NULL,
  `message` varchar(165) collate latin1_german2_ci default NULL,
  `delay` int(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=222 DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci COMMENT='pending sms messages per call' AUTO_INCREMENT=222 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `trials`
-- 

CREATE TABLE `trials` (
  `id` int(11) NOT NULL auto_increment,
  `experiment_id` int(11) NOT NULL,
  `trial_num` int(11) default NULL,
  `start_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_date` date default NULL,
  `end_time` time default NULL,
  `sms_date` date default NULL,
  `sms_time` time default NULL,
  `guess_date` date default NULL,
  `guess_time` time default NULL,
  `call_date` date default NULL,
  `call_time` time default NULL,
  `participant_1` varchar(40) collate latin1_german2_ci default NULL,
  `participant_2` varchar(40) collate latin1_german2_ci default NULL,
  `caller_guess` varchar(40) collate latin1_german2_ci default NULL,
  `caller_actual` varchar(40) collate latin1_german2_ci default NULL,
  `caller_phone` varchar(20) collate latin1_german2_ci default NULL,
  `extension` int(4) default NULL,
  `hit` char(1) collate latin1_german2_ci default NULL,
  `status` tinyint(1) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=217 DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=217 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

CREATE TABLE `users` (
  `username` varchar(12) collate latin1_german2_ci NOT NULL,
  `phone` varchar(20) collate latin1_german2_ci NOT NULL,
  `email` varchar(50) collate latin1_german2_ci NOT NULL,
  `first_name` varchar(20) collate latin1_german2_ci NOT NULL,
  `last_name` varchar(20) collate latin1_german2_ci NOT NULL,
  `password` varchar(40) collate latin1_german2_ci NOT NULL,
  `locked` char(1) collate latin1_german2_ci default NULL,
  `age` int(3) unsigned default NULL,
  `code` varchar(20) collate latin1_german2_ci default NULL,
  `pin` int(10) NOT NULL,
  `gender` varchar(6) character set utf8 default NULL,
  `admin` char(1) character set utf8 default NULL,
  `group_name` varchar(20) collate latin1_german2_ci NOT NULL,
  PRIMARY KEY  (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci COMMENT='telephone telepathy users';

-- 
-- Dumping data for table `users`
-- 

