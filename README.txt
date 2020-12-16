In addition to the new IONS folder, these are the files that were modified

Files modified compare to Rupert version
----------------------------------------
- twilio/main_menu.php (edited menus, changed default menu)
- twilio/menu_response.php (edited menus, execute different files)
- twilio/do_experiment.php (changed voice)
- twilio/do_exit.php (changed voice)
- twilio/do_cancel.php (changed voice)
- register.php (add disclaimer, change button format)
- about.php (change information)
- scripts/do_register.php (remove forcing to set the group)
- inc/config.php (change config information)
- graphics/telephone_telepathy.jpg
- graphics/telephone_telepathy_banner_beta.jpg

New databases created
---------------------
CREATE TABLE `tele_trials` (
  `id` int(11) NOT NULL auto_increment,
  `experiment_id` int(11) default NULL,
  `trial_num` int(11) default NULL,
  `start_date` char(20) default NULL,
  `end_date` char(20) default NULL,
  `participant_1` varchar(40) collate latin1_german2_ci default NULL,
  `participant_2` varchar(40) collate latin1_german2_ci default NULL,
  `participant_3` varchar(40) collate latin1_german2_ci default NULL,
  `phone_1` varchar(20) collate latin1_german2_ci default NULL,
  `phone_2` varchar(20) collate latin1_german2_ci default NULL,
  `phone_3` varchar(20) collate latin1_german2_ci default NULL,
  `guess_1` varchar(40) collate latin1_german2_ci default NULL,
  `guess_2` varchar(40) collate latin1_german2_ci default NULL,
  `guess_3` varchar(40) collate latin1_german2_ci default NULL,
  `actual_1` varchar(40) collate latin1_german2_ci default NULL,
  `actual_2` varchar(40) collate latin1_german2_ci default NULL,
  `actual_3` varchar(40) collate latin1_german2_ci default NULL,
  `status_1` int(1) default 0,
  `status_2` int(1) default 0,
  `status_3` int(1) default 0,
  `hit` char(1) collate latin1_german2_ci default NULL,
  `status` tinyint(1) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=217 DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=217 ;
CREATE TABLE tele_informedconsent (phone CHAR(20), status CHAR(20), number_of_calls INT);

Database note: After creating a user, run "update users set admin='x' where username ='apierce';" to make them admin
