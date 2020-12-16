/*Table structure for table `blocked_users` */

DROP TABLE IF EXISTS `blocked_users`;

CREATE TABLE `blocked_users` (
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL default '0',
  `blocked_user` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `favorites` */

DROP TABLE IF EXISTS `favorites`;

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL default '0',
  `message` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Table structure for table `followed` */

DROP TABLE IF EXISTS `followed`;

CREATE TABLE `followed` (
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL default '0',
  `followed` int(11) NOT NULL default '0',
  `friend_only` tinyint(1) NOT NULL default '0',
  `sms_flag` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `followed` (`followed`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Table structure for table `groups_links` */

DROP TABLE IF EXISTS `groups_links`;

CREATE TABLE `groups_links` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `groups_mes` */

DROP TABLE IF EXISTS `groups_mes`;

CREATE TABLE `groups_mes` (
  `group_id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `group_title` varchar(255) NOT NULL,
  `group_furl` varchar(100) NOT NULL,
  `group_furl_hash` varchar(32) NOT NULL,
  `group_descr` text NOT NULL,
  `group_tags` text NOT NULL,
  `group_image` varchar(250) NOT NULL,
  `created` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`group_id`),
  KEY `parent_id` (`parent_id`),
  KEY `level_id` (`level_id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Table structure for table `lang_list` */

DROP TABLE IF EXISTS `lang_list`;

CREATE TABLE `lang_list` (
  `lang_id` int(11) NOT NULL auto_increment,
  `lang_short_name` varchar(3) default NULL,
  `lang_full_name` varchar(100) NOT NULL,
  `lang_charset` varchar(50) NOT NULL,
  PRIMARY KEY  (`lang_id`),
  KEY `lang_short_name` (`lang_short_name`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Table structure for table `lang_trans` */

DROP TABLE IF EXISTS `lang_trans`;

CREATE TABLE `lang_trans` (
  `id` int(11) NOT NULL auto_increment,
  `lang_id` int(11) NOT NULL,
  `var_id` int(11) NOT NULL,
  `value` tinytext,
  PRIMARY KEY  (`id`),
  KEY `lang_id` (`lang_id`),
  KEY `var_id` (`var_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2837 DEFAULT CHARSET=utf8;

/*Table structure for table `lang_vars` */

DROP TABLE IF EXISTS `lang_vars`;

CREATE TABLE `lang_vars` (
  `var_id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`var_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=376 DEFAULT CHARSET=utf8;

/*Table structure for table `layouts` */

DROP TABLE IF EXISTS `layouts`;

CREATE TABLE `layouts` (
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL default '0',
  `back_color` varchar(6) NOT NULL default 'ffffff',
  `back_image` tinyint(1) NOT NULL default '1',
  `back_tile` tinyint(1) NOT NULL default '1',
  `back_fixed` tinyint(1) NOT NULL default '0',
  `use_image` tinyint(1) NOT NULL default '1',
  `sticker_color` varchar(6) NOT NULL default 'cccccc',
  `text_color` varchar(6) NOT NULL default '000000',
  `link_color` varchar(6) NOT NULL default '007eff',
  `name_color` varchar(6) NOT NULL default '007eff',
  `bubble_text_color` varchar(6) NOT NULL default '000000',
  `bubble_fill_color` varchar(6) NOT NULL default 'ffffff',
  `side_border_color` varchar(6) NOT NULL default '39ba00',
  `side_fill_color` varchar(6) NOT NULL default 'ebebeb',
  `top_area_color` varchar(6) NOT NULL default '39ba00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

insert into layouts values (1, 1, 'ffffff', 1, 1, 0, 1, 'cccccc', '000000', '007eff', '007eff', '000000', 'ffffff', '39ba00', 'ebebeb', '39ba00');



/*Table structure for table `messages` */

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `id` int(11) NOT NULL auto_increment,
  `user` varchar(50) NOT NULL default '',
  `time` int(11) NOT NULL default '0',
  `msg` varchar(512) NOT NULL default '',
  `from` varchar(10) NOT NULL default 'web',
  `direct` int(11) NOT NULL default '0',
  `reply` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;

/*Table structure for table `nudges` */

DROP TABLE IF EXISTS `nudges`;

CREATE TABLE `nudges` (
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL default '0',
  `txt` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `sms_plans` */

DROP TABLE IF EXISTS `sms_plans`;

CREATE TABLE `sms_plans` (
  `id` int(11) NOT NULL auto_increment,
  `credits` int(11) NOT NULL default '0',
  `price` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

insert into sms_plans values (1, 5, '5.00');
insert into sms_plans values (2, 10, '8.00');
insert into sms_plans values (3, 15, '10.00');
insert into sms_plans values (5, 20, '12.00');
insert into sms_plans values (6, 30, '20.00');



/*Table structure for table `static_pages` */

DROP TABLE IF EXISTS `static_pages`;

CREATE TABLE `static_pages` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `tiny_url` */

DROP TABLE IF EXISTS `tiny_url`;

CREATE TABLE `tiny_url` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(200) NOT NULL default '',
  `tiny` varchar(9) NOT NULL default '',
  `author_id` int(11) NOT NULL default '0',
  `expires` time NOT NULL default '00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `tiny` (`tiny`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `transactions` */

DROP TABLE IF EXISTS `transactions`;

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL auto_increment,
  `time` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `credits` int(11) NOT NULL,
  `value` decimal(10,2) NOT NULL default '0.00',
  `status` int(11) NOT NULL default '0' COMMENT '0=new, 1=ok, 2=cancelled',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `time` int(11) NOT NULL default '0',
  `username` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL default '',
  `pass` varchar(50) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `visible` tinyint(1) NOT NULL default '0',
  `phone` varchar(20) NOT NULL default '',
  `im_type` varchar(30) NOT NULL default '',
  `im_id` varchar(50) NOT NULL default '',
  `age` int(11) NOT NULL default '0',
  `interests` text NOT NULL,
  `bio` text NOT NULL,
  `location` varchar(200) NOT NULL default '',
  `www` varchar(200) NOT NULL default '',
  `notify_direct` tinyint(1) NOT NULL default '0',
  `notify_friend` tinyint(1) NOT NULL default '1',
  `notify_way` varchar(5) NOT NULL default 'email',
  `new` tinyint(1) NOT NULL default '1',
  `avatar` varchar(20) NOT NULL default '',
  `api_key` varchar(32) NOT NULL default '',
  `x` float NOT NULL,
  `y` float NOT NULL,
  `sms_credits` int(11) NOT NULL default '0',
  `sms_limit` int(11) NOT NULL default '1000',
  `used_sms` int(11) NOT NULL default '0',
  `open_id_identifier` varchar(225) NOT NULL,
  `open_id_displayname` varchar(225) NOT NULL,
  `open_id_provider` varchar(225) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

insert into users values (1, 0, 'admin', 'Admin Adminsky', 'admin', 'admin@company.com', 1, '0700 997 666', '', '', 124326000, 'administration', 'I+am+the+admin%2C+you+must+obey+my+orders', 'London', 'http://google.com', 0, 0, 'email', 0, '942.jpg', '21232f297a57a5a743894a0e4a801fc3', '-0.126236', '51.5002', 0, 1000, 0, '', '', '');



/* Language Upgrade Data */

ALTER TABLE users ADD twitter_com_username varchar(50) default NULL;
ALTER TABLE users ADD twitter_com_pass varchar(50) default NULL;
ALTER TABLE users ADD twitter_com_send_message tinyint(1) NOT NULL default '0';
ALTER TABLE `users` ADD `lang_id` VARCHAR( 3 ) NOT NULL ;

INSERT INTO `lang_list` VALUES (1, 'ENG', 'English (US)', 'UTF-8');
INSERT INTO `lang_list` VALUES (2, 'RUS', 'Russian', 'UTF-8');


INSERT INTO `lang_vars` VALUES (1, 'err_fill_all');
INSERT INTO `lang_vars` VALUES (2, 'err_email');
INSERT INTO `lang_vars` VALUES (3, 'err_email_incorrect');
INSERT INTO `lang_vars` VALUES (4, 'err_email_exists');
INSERT INTO `lang_vars` VALUES (5, 'err_login_incorrect');
INSERT INTO `lang_vars` VALUES (6, 'err_invitations');
INSERT INTO `lang_vars` VALUES (7, 'err_email_not_found');
INSERT INTO `lang_vars` VALUES (8, 'err_reminder_error');
INSERT INTO `lang_vars` VALUES (9, 'err_accept_terms');
INSERT INTO `lang_vars` VALUES (10, 'err_choose_name');
INSERT INTO `lang_vars` VALUES (11, 'err_account_exists');
INSERT INTO `lang_vars` VALUES (12, 'err_password_mismatch');
INSERT INTO `lang_vars` VALUES (13, 'err_invalid_code');
INSERT INTO `lang_vars` VALUES (14, 'err_create_account');
INSERT INTO `lang_vars` VALUES (15, 'err_msg_too_long');
INSERT INTO `lang_vars` VALUES (16, 'err_layout_change');
INSERT INTO `lang_vars` VALUES (17, 'err_about_me_too_long');
INSERT INTO `lang_vars` VALUES (18, 'err_interests_too_long');
INSERT INTO `lang_vars` VALUES (19, 'err_life_change');
INSERT INTO `lang_vars` VALUES (20, 'err_set_im');
INSERT INTO `lang_vars` VALUES (21, 'err_photo_upload');
INSERT INTO `lang_vars` VALUES (22, 'err_email_needed');
INSERT INTO `lang_vars` VALUES (23, 'err_file_too_large');
INSERT INTO `lang_vars` VALUES (24, 'err_email_confirmation_needed');
INSERT INTO `lang_vars` VALUES (25, 'err_account_inactive');
INSERT INTO `lang_vars` VALUES (26, 'err_sms_limit');
INSERT INTO `lang_vars` VALUES (27, 'err_sms_limit_nan');
INSERT INTO `lang_vars` VALUES (28, 'err_mobile');
INSERT INTO `lang_vars` VALUES (29, 'ok_email_sent');
INSERT INTO `lang_vars` VALUES (30, 'ok_invitations_sent');
INSERT INTO `lang_vars` VALUES (31, 'ok_invitations_sent_nolist');
INSERT INTO `lang_vars` VALUES (32, 'ok_reminder_sent');
INSERT INTO `lang_vars` VALUES (33, 'ok_back_to_defaults');
INSERT INTO `lang_vars` VALUES (34, 'ok_layout_changed');
INSERT INTO `lang_vars` VALUES (35, 'ok_msg_sent');
INSERT INTO `lang_vars` VALUES (36, 'ok_reply_sent');
INSERT INTO `lang_vars` VALUES (37, 'ok_profile_saved');
INSERT INTO `lang_vars` VALUES (38, 'ok_life_changed');
INSERT INTO `lang_vars` VALUES (39, 'ok_life_saved');
INSERT INTO `lang_vars` VALUES (40, 'ok_im_deactivated');
INSERT INTO `lang_vars` VALUES (41, 'ok_im_set');
INSERT INTO `lang_vars` VALUES (42, 'ok_photo_uploaded');
INSERT INTO `lang_vars` VALUES (43, 'ok_photo_changed');
INSERT INTO `lang_vars` VALUES (44, 'ok_settings_saved');
INSERT INTO `lang_vars` VALUES (45, 'ok_added_as_friend');
INSERT INTO `lang_vars` VALUES (46, 'ok_sms_limit_set');
INSERT INTO `lang_vars` VALUES (47, 'ok_phone_saved');
INSERT INTO `lang_vars` VALUES (48, 'invitation_mail');
INSERT INTO `lang_vars` VALUES (49, 'invitation_subject');
INSERT INTO `lang_vars` VALUES (50, 'reminder_subject');
INSERT INTO `lang_vars` VALUES (51, 'reminder_mail');
INSERT INTO `lang_vars` VALUES (52, 'confirmation_mail');
INSERT INTO `lang_vars` VALUES (53, 'confirmation_subject');
INSERT INTO `lang_vars` VALUES (54, 'notification_mail');
INSERT INTO `lang_vars` VALUES (55, 'notification_subject');
INSERT INTO `lang_vars` VALUES (56, 'notification_sms');
INSERT INTO `lang_vars` VALUES (57, 'nudge_subject');
INSERT INTO `lang_vars` VALUES (58, 'nudge');
INSERT INTO `lang_vars` VALUES (59, 'direct_notification_mail');
INSERT INTO `lang_vars` VALUES (60, 'direct_notification_subject');
INSERT INTO `lang_vars` VALUES (61, 'direct_notification_sms');
INSERT INTO `lang_vars` VALUES (62, 'added_as_friend_mail');
INSERT INTO `lang_vars` VALUES (63, 'added_as_friend_subject');
INSERT INTO `lang_vars` VALUES (64, 'added_as_friend_sms');
INSERT INTO `lang_vars` VALUES (65, 'nudge_ok');
INSERT INTO `lang_vars` VALUES (66, 'follow_ok');
INSERT INTO `lang_vars` VALUES (67, 'remove_ok');
INSERT INTO `lang_vars` VALUES (68, 'leave_ok');
INSERT INTO `lang_vars` VALUES (69, 'block_ok');
INSERT INTO `lang_vars` VALUES (70, 'unblock_ok');
INSERT INTO `lang_vars` VALUES (71, 'yesterday');
INSERT INTO `lang_vars` VALUES (72, 'days_ago');
INSERT INTO `lang_vars` VALUES (73, 'hour_ago');
INSERT INTO `lang_vars` VALUES (74, 'hours_ago');
INSERT INTO `lang_vars` VALUES (75, 'mins_and');
INSERT INTO `lang_vars` VALUES (76, 'min_and');
INSERT INTO `lang_vars` VALUES (77, 'secs_ago');
INSERT INTO `lang_vars` VALUES (78, 'minutes_ago');
INSERT INTO `lang_vars` VALUES (352, 'label_lang');
INSERT INTO `lang_vars` VALUES (80, 'default_message_from_admin');
INSERT INTO `lang_vars` VALUES (81, 'user_first_message');
INSERT INTO `lang_vars` VALUES (82, 'contact_us');
INSERT INTO `lang_vars` VALUES (83, 'label_subject');
INSERT INTO `lang_vars` VALUES (84, 'label_message');
INSERT INTO `lang_vars` VALUES (85, 'label_your_name');
INSERT INTO `lang_vars` VALUES (86, 'label_your_email');
INSERT INTO `lang_vars` VALUES (87, 'label_send');
INSERT INTO `lang_vars` VALUES (88, 'direct_msg');
INSERT INTO `lang_vars` VALUES (89, 'send_msg_to');
INSERT INTO `lang_vars` VALUES (90, 'chars_left');
INSERT INTO `lang_vars` VALUES (91, 'direct_msgs_to');
INSERT INTO `lang_vars` VALUES (92, 'inbox');
INSERT INTO `lang_vars` VALUES (93, 'outbox');
INSERT INTO `lang_vars` VALUES (94, 'from');
INSERT INTO `lang_vars` VALUES (95, 'label_delete');
INSERT INTO `lang_vars` VALUES (96, 'title_delete');
INSERT INTO `lang_vars` VALUES (97, 'title_reply');
INSERT INTO `lang_vars` VALUES (98, 'label_reply');
INSERT INTO `lang_vars` VALUES (99, 'no_msg');
INSERT INTO `lang_vars` VALUES (100, 'to');
INSERT INTO `lang_vars` VALUES (101, 'in_reply_to');
INSERT INTO `lang_vars` VALUES (102, 'favorites_header');
INSERT INTO `lang_vars` VALUES (103, 'title_fav_del');
INSERT INTO `lang_vars` VALUES (104, 'title_fav_add');
INSERT INTO `lang_vars` VALUES (105, 'followers_of1');
INSERT INTO `lang_vars` VALUES (106, 'followers_of2');
INSERT INTO `lang_vars` VALUES (107, 'label_follow');
INSERT INTO `lang_vars` VALUES (108, 'forgot_header');
INSERT INTO `lang_vars` VALUES (109, 'forgot_paragraph');
INSERT INTO `lang_vars` VALUES (110, 'label_remind');
INSERT INTO `lang_vars` VALUES (111, 'friends_of1');
INSERT INTO `lang_vars` VALUES (112, 'friends_of2');
INSERT INTO `lang_vars` VALUES (113, 'label_stop_following');
INSERT INTO `lang_vars` VALUES (114, 'pagination_page');
INSERT INTO `lang_vars` VALUES (115, 'pagination_prev');
INSERT INTO `lang_vars` VALUES (116, 'pagination_next');
INSERT INTO `lang_vars` VALUES (117, 'main_header');
INSERT INTO `lang_vars` VALUES (118, 'invite_header');
INSERT INTO `lang_vars` VALUES (119, 'invite_paragraph');
INSERT INTO `lang_vars` VALUES (120, 'import_contacts_from');
INSERT INTO `lang_vars` VALUES (121, 'label_user_name');
INSERT INTO `lang_vars` VALUES (122, 'label_password');
INSERT INTO `lang_vars` VALUES (123, 'pass_not_stored');
INSERT INTO `lang_vars` VALUES (124, 'label_check_now');
INSERT INTO `lang_vars` VALUES (125, 'we_found1');
INSERT INTO `lang_vars` VALUES (126, 'we_found2');
INSERT INTO `lang_vars` VALUES (127, 'check_all');
INSERT INTO `lang_vars` VALUES (128, 'uncheck_all');
INSERT INTO `lang_vars` VALUES (129, 'name_and_mail');
INSERT INTO `lang_vars` VALUES (130, 'member_of');
INSERT INTO `lang_vars` VALUES (131, 'yes');
INSERT INTO `lang_vars` VALUES (132, 'no');
INSERT INTO `lang_vars` VALUES (133, 'invitation_preview1');
INSERT INTO `lang_vars` VALUES (134, 'invitation_preview2');
INSERT INTO `lang_vars` VALUES (135, 'label_invite');
INSERT INTO `lang_vars` VALUES (136, 'no_contacts_found');
INSERT INTO `lang_vars` VALUES (137, 'big_or');
INSERT INTO `lang_vars` VALUES (138, 'enter_some_emails');
INSERT INTO `lang_vars` VALUES (139, 'label_emails');
INSERT INTO `lang_vars` VALUES (140, 'separate_commas');
INSERT INTO `lang_vars` VALUES (141, 'updates_protected');
INSERT INTO `lang_vars` VALUES (142, 'my_mobile');
INSERT INTO `lang_vars` VALUES (143, 'my_mobile_header');
INSERT INTO `lang_vars` VALUES (144, 'my_mobile_text');
INSERT INTO `lang_vars` VALUES (145, 'my_mobile_label');
INSERT INTO `lang_vars` VALUES (146, 'save');
INSERT INTO `lang_vars` VALUES (147, 'gateway_info');
INSERT INTO `lang_vars` VALUES (148, 'api_key_header');
INSERT INTO `lang_vars` VALUES (149, 'api_key_explanation');
INSERT INTO `lang_vars` VALUES (150, 'api_key_is');
INSERT INTO `lang_vars` VALUES (151, 'profile_del_header');
INSERT INTO `lang_vars` VALUES (152, 'profile_del_explanation');
INSERT INTO `lang_vars` VALUES (153, 'label_remove_account');
INSERT INTO `lang_vars` VALUES (154, 'profile_del_u_sure');
INSERT INTO `lang_vars` VALUES (155, 'label_delete_account');
INSERT INTO `lang_vars` VALUES (156, 'my_im_header');
INSERT INTO `lang_vars` VALUES (157, 'my_im_explanation');
INSERT INTO `lang_vars` VALUES (158, 'label_im_type');
INSERT INTO `lang_vars` VALUES (159, 'label_im_id');
INSERT INTO `lang_vars` VALUES (160, 'label_save_changes');
INSERT INTO `lang_vars` VALUES (161, 'label_deactivate');
INSERT INTO `lang_vars` VALUES (162, 'my_lay_header');
INSERT INTO `lang_vars` VALUES (163, 'my_lay_background');
INSERT INTO `lang_vars` VALUES (164, 'my_lay_bubble');
INSERT INTO `lang_vars` VALUES (165, 'my_lay_boxes');
INSERT INTO `lang_vars` VALUES (166, 'my_lay_other');
INSERT INTO `lang_vars` VALUES (167, 'label_back_default');
INSERT INTO `lang_vars` VALUES (168, 'label_back_color');
INSERT INTO `lang_vars` VALUES (169, 'label_back_image');
INSERT INTO `lang_vars` VALUES (170, 'label_use_image');
INSERT INTO `lang_vars` VALUES (171, 'label_tile_image');
INSERT INTO `lang_vars` VALUES (172, 'label_back_fixed');
INSERT INTO `lang_vars` VALUES (173, 'label_bubble_text');
INSERT INTO `lang_vars` VALUES (174, 'label_bubble_fill');
INSERT INTO `lang_vars` VALUES (175, 'label_border_color');
INSERT INTO `lang_vars` VALUES (176, 'label_side_fill');
INSERT INTO `lang_vars` VALUES (177, 'label_text_color');
INSERT INTO `lang_vars` VALUES (178, 'label_link_color');
INSERT INTO `lang_vars` VALUES (179, 'label_msg_area');
INSERT INTO `lang_vars` VALUES (180, 'life_header');
INSERT INTO `lang_vars` VALUES (181, 'label_location');
INSERT INTO `lang_vars` VALUES (182, 'label_dob');
INSERT INTO `lang_vars` VALUES (183, 'option_day');
INSERT INTO `lang_vars` VALUES (184, 'option_month');
INSERT INTO `lang_vars` VALUES (185, 'option_year');
INSERT INTO `lang_vars` VALUES (186, 'label_about_me');
INSERT INTO `lang_vars` VALUES (187, 'limit_200chr');
INSERT INTO `lang_vars` VALUES (188, 'label_more_info');
INSERT INTO `lang_vars` VALUES (189, 'label_url');
INSERT INTO `lang_vars` VALUES (190, 'label_interests');
INSERT INTO `lang_vars` VALUES (191, 'comma_separated_200chr');
INSERT INTO `lang_vars` VALUES (192, 'photo_header');
INSERT INTO `lang_vars` VALUES (193, 'tab_upload');
INSERT INTO `lang_vars` VALUES (194, 'tab_picture_set');
INSERT INTO `lang_vars` VALUES (195, 'upload_your_photo');
INSERT INTO `lang_vars` VALUES (196, 'upload_comment');
INSERT INTO `lang_vars` VALUES (197, 'upload_hint');
INSERT INTO `lang_vars` VALUES (198, 'label_upload_now');
INSERT INTO `lang_vars` VALUES (199, 'choose_your_photo');
INSERT INTO `lang_vars` VALUES (200, 'label_choose_this_photo');
INSERT INTO `lang_vars` VALUES (201, 'label_repeat_pass');
INSERT INTO `lang_vars` VALUES (202, 'label_full_name');
INSERT INTO `lang_vars` VALUES (203, 'my_profile_header');
INSERT INTO `lang_vars` VALUES (204, 'visibility');
INSERT INTO `lang_vars` VALUES (205, 'sticker_header');
INSERT INTO `lang_vars` VALUES (206, 'sticker_flash');
INSERT INTO `lang_vars` VALUES (207, 'sticker_js');
INSERT INTO `lang_vars` VALUES (208, 'paste_code_flash');
INSERT INTO `lang_vars` VALUES (209, 'sticker_choose_color');
INSERT INTO `lang_vars` VALUES (210, 'label_sticker_color');
INSERT INTO `lang_vars` VALUES (211, 'sticker_js_comment');
INSERT INTO `lang_vars` VALUES (212, 'sticker_friends_comment');
INSERT INTO `lang_vars` VALUES (213, 'notification_header');
INSERT INTO `lang_vars` VALUES (214, 'notification_label');
INSERT INTO `lang_vars` VALUES (215, 'notification_comment');
INSERT INTO `lang_vars` VALUES (216, 'setting_ok');
INSERT INTO `lang_vars` VALUES (217, 'setting_err');
INSERT INTO `lang_vars` VALUES (218, 'notify_way_ok');
INSERT INTO `lang_vars` VALUES (219, 'notify_way_err');
INSERT INTO `lang_vars` VALUES (220, 'what_are_you_doing');
INSERT INTO `lang_vars` VALUES (221, 'profile_tab_mine');
INSERT INTO `lang_vars` VALUES (222, 'profile_tab_with_friends');
INSERT INTO `lang_vars` VALUES (223, 'profile_tab_replys');
INSERT INTO `lang_vars` VALUES (224, 'profile_tab_customize');
INSERT INTO `lang_vars` VALUES (225, 'upload_picture');
INSERT INTO `lang_vars` VALUES (226, 'reply_to_msg');
INSERT INTO `lang_vars` VALUES (227, 'your_reply');
INSERT INTO `lang_vars` VALUES (228, 'results_for');
INSERT INTO `lang_vars` VALUES (229, 'settings');
INSERT INTO `lang_vars` VALUES (230, 'my_account');
INSERT INTO `lang_vars` VALUES (231, 'my_sms_credits');
INSERT INTO `lang_vars` VALUES (232, 'step1');
INSERT INTO `lang_vars` VALUES (233, 'step2');
INSERT INTO `lang_vars` VALUES (234, 'step3');
INSERT INTO `lang_vars` VALUES (235, 'step4');
INSERT INTO `lang_vars` VALUES (236, 'link_continue');
INSERT INTO `lang_vars` VALUES (237, 'skip_setup');
INSERT INTO `lang_vars` VALUES (238, 'link_finish');
INSERT INTO `lang_vars` VALUES (239, 'people_interested_in');
INSERT INTO `lang_vars` VALUES (240, 'link_start');
INSERT INTO `lang_vars` VALUES (241, 'follow_4_steps');
INSERT INTO `lang_vars` VALUES (242, 'welcome');
INSERT INTO `lang_vars` VALUES (243, 'welcome_info1');
INSERT INTO `lang_vars` VALUES (244, 'welcome_info2');
INSERT INTO `lang_vars` VALUES (245, 'text_on_black');
INSERT INTO `lang_vars` VALUES (246, 'bm_about');
INSERT INTO `lang_vars` VALUES (247, 'bm_contact');
INSERT INTO `lang_vars` VALUES (248, 'bm_api');
INSERT INTO `lang_vars` VALUES (249, 'bm_help');
INSERT INTO `lang_vars` VALUES (250, 'bm_terms');
INSERT INTO `lang_vars` VALUES (251, 'bm_privacy');
INSERT INTO `lang_vars` VALUES (252, 'friend_search');
INSERT INTO `lang_vars` VALUES (253, 'label_search');
INSERT INTO `lang_vars` VALUES (254, 'i_am_in');
INSERT INTO `lang_vars` VALUES (255, 'qty_direct_msg');
INSERT INTO `lang_vars` VALUES (256, 'qty_followers');
INSERT INTO `lang_vars` VALUES (257, 'qty_friends');
INSERT INTO `lang_vars` VALUES (258, 'qty_favorites');
INSERT INTO `lang_vars` VALUES (259, 'qty_sms_credits');
INSERT INTO `lang_vars` VALUES (260, 'qty_updates');
INSERT INTO `lang_vars` VALUES (261, 'new_msg_notify');
INSERT INTO `lang_vars` VALUES (262, 'notify_any_type');
INSERT INTO `lang_vars` VALUES (263, 'notify_direct_only');
INSERT INTO `lang_vars` VALUES (264, 'notify_by');
INSERT INTO `lang_vars` VALUES (265, 'n_web');
INSERT INTO `lang_vars` VALUES (266, 'n_email');
INSERT INTO `lang_vars` VALUES (267, 'n_im');
INSERT INTO `lang_vars` VALUES (268, 'n_sms');
INSERT INTO `lang_vars` VALUES (269, 'activate_im');
INSERT INTO `lang_vars` VALUES (270, 'u_about');
INSERT INTO `lang_vars` VALUES (271, 'u_fullname');
INSERT INTO `lang_vars` VALUES (272, 'u_location');
INSERT INTO `lang_vars` VALUES (273, 'u_interests');
INSERT INTO `lang_vars` VALUES (274, 'u_bio');
INSERT INTO `lang_vars` VALUES (275, 'u_age');
INSERT INTO `lang_vars` VALUES (276, 'u_www');
INSERT INTO `lang_vars` VALUES (277, 'actions');
INSERT INTO `lang_vars` VALUES (278, 'a_send_msg');
INSERT INTO `lang_vars` VALUES (279, 'a_nudge');
INSERT INTO `lang_vars` VALUES (280, 'a_leave');
INSERT INTO `lang_vars` VALUES (281, 'a_remove');
INSERT INTO `lang_vars` VALUES (282, 'a_block');
INSERT INTO `lang_vars` VALUES (283, 'a_follow');
INSERT INTO `lang_vars` VALUES (284, 'unblock');
INSERT INTO `lang_vars` VALUES (285, 'a_add');
INSERT INTO `lang_vars` VALUES (286, 'a_as_friend');
INSERT INTO `lang_vars` VALUES (287, 'link_register');
INSERT INTO `lang_vars` VALUES (288, 'link_login');
INSERT INTO `lang_vars` VALUES (289, 'label_uname_email');
INSERT INTO `lang_vars` VALUES (290, 'label_remember');
INSERT INTO `lang_vars` VALUES (291, 'label_login');
INSERT INTO `lang_vars` VALUES (292, 'label_your_uname');
INSERT INTO `lang_vars` VALUES (293, 'label_email');
INSERT INTO `lang_vars` VALUES (294, 'i_accept');
INSERT INTO `lang_vars` VALUES (295, 'label_catcha1');
INSERT INTO `lang_vars` VALUES (296, 'label_catcha2');
INSERT INTO `lang_vars` VALUES (297, 'label_create_account');
INSERT INTO `lang_vars` VALUES (298, 'tab_popular');
INSERT INTO `lang_vars` VALUES (299, 'tab_recent');
INSERT INTO `lang_vars` VALUES (300, 'add_me');
INSERT INTO `lang_vars` VALUES (301, 'refresh_warning');
INSERT INTO `lang_vars` VALUES (302, 'im_msg_too_long');
INSERT INTO `lang_vars` VALUES (303, 'all_friends');
INSERT INTO `lang_vars` VALUES (304, 'i_accept2');
INSERT INTO `lang_vars` VALUES (305, 'label_captcha2');
INSERT INTO `lang_vars` VALUES (306, 'friends1');
INSERT INTO `lang_vars` VALUES (307, 'friends2');
INSERT INTO `lang_vars` VALUES (308, 'you_have');
INSERT INTO `lang_vars` VALUES (309, 'max500');
INSERT INTO `lang_vars` VALUES (310, 'menu_home');
INSERT INTO `lang_vars` VALUES (311, 'menu_my_panel');
INSERT INTO `lang_vars` VALUES (312, 'menu_invite');
INSERT INTO `lang_vars` VALUES (313, 'menu_vision');
INSERT INTO `lang_vars` VALUES (314, 'menu_settings');
INSERT INTO `lang_vars` VALUES (315, 'menu_logout');
INSERT INTO `lang_vars` VALUES (316, 'tile_v');
INSERT INTO `lang_vars` VALUES (317, 'tile_h');
INSERT INTO `lang_vars` VALUES (318, 'tile_both');
INSERT INTO `lang_vars` VALUES (319, 'tile_no');
INSERT INTO `lang_vars` VALUES (320, 'choose_bglib');
INSERT INTO `lang_vars` VALUES (321, 'hello');
INSERT INTO `lang_vars` VALUES (322, 'users_message');
INSERT INTO `lang_vars` VALUES (323, 'users_profile');
INSERT INTO `lang_vars` VALUES (324, 'sms_credits_title');
INSERT INTO `lang_vars` VALUES (325, 'buying_instruction');
INSERT INTO `lang_vars` VALUES (326, 'credit_amount');
INSERT INTO `lang_vars` VALUES (327, 'choose_sms_plan');
INSERT INTO `lang_vars` VALUES (328, 'credits_for');
INSERT INTO `lang_vars` VALUES (329, 'buy');
INSERT INTO `lang_vars` VALUES (330, 'credit_price');
INSERT INTO `lang_vars` VALUES (331, 'total_price');
INSERT INTO `lang_vars` VALUES (332, 'your_sms_transactions');
INSERT INTO `lang_vars` VALUES (333, 'trans_date');
INSERT INTO `lang_vars` VALUES (334, 'trans_time');
INSERT INTO `lang_vars` VALUES (335, 'trans_credits');
INSERT INTO `lang_vars` VALUES (336, 'trans_value');
INSERT INTO `lang_vars` VALUES (337, 'sms_limit_header');
INSERT INTO `lang_vars` VALUES (338, 'sms_limit_text');
INSERT INTO `lang_vars` VALUES (339, 'used_credits_below_limit');
INSERT INTO `lang_vars` VALUES (340, 'your_limit_is');
INSERT INTO `lang_vars` VALUES (341, 'reset_sms_limit');
INSERT INTO `lang_vars` VALUES (342, 'set_sms_limit');
INSERT INTO `lang_vars` VALUES (343, 'set_limit_to');
INSERT INTO `lang_vars` VALUES (344, 'set_limit_button');
INSERT INTO `lang_vars` VALUES (345, 'buy_success_title');
INSERT INTO `lang_vars` VALUES (346, 'buy_success1');
INSERT INTO `lang_vars` VALUES (347, 'buy_success2');
INSERT INTO `lang_vars` VALUES (348, 'buy_success_error_title');
INSERT INTO `lang_vars` VALUES (349, 'buy_success_error');
INSERT INTO `lang_vars` VALUES (350, 'buy_failure_title');
INSERT INTO `lang_vars` VALUES (351, 'buy_failure');
INSERT INTO `lang_vars` (`var_id`, `name`) VALUES 
  (353,'twitter_com_account_header'),
  (354,'twitter_com_account_username'),
  (355,'twitter_com_account_password'),
  (356,'label_flag_send_message');



INSERT INTO `lang_trans` VALUES (1, 1, 1, 'Please fill all fields of this form.');
INSERT INTO `lang_trans` VALUES (2, 1, 2, 'An error occured while trying to send your email. Please try again.');
INSERT INTO `lang_trans` VALUES (3, 1, 3, 'Given email address is incorrect.');
INSERT INTO `lang_trans` VALUES (4, 1, 4, 'An account with this email already exists. Choose a different one.');
INSERT INTO `lang_trans` VALUES (5, 1, 5, 'Login incorrect.');
INSERT INTO `lang_trans` VALUES (6, 1, 6, 'An error occured while trying to send your invitations.');
INSERT INTO `lang_trans` VALUES (7, 1, 7, 'Given email does not exist in our database.');
INSERT INTO `lang_trans` VALUES (8, 1, 8, 'An error occured while trying to send you the password reminder.');
INSERT INTO `lang_trans` VALUES (9, 1, 9, 'You have to accept the terms of use.');
INSERT INTO `lang_trans` VALUES (10, 1, 10, 'Choose a user name.');
INSERT INTO `lang_trans` VALUES (11, 1, 11, 'An account with this name already exists.');
INSERT INTO `lang_trans` VALUES (12, 1, 13, 'Given code is incorrect.');
INSERT INTO `lang_trans` VALUES (13, 1, 14, 'Could not create your account. Try again later.');
INSERT INTO `lang_trans` VALUES (14, 1, 16, 'An error occured while trying to change your layout.');
INSERT INTO `lang_trans` VALUES (15, 1, 19, 'An error occured while trying to change information about your life');
INSERT INTO `lang_trans` VALUES (16, 1, 20, 'Could not activate your IM');
INSERT INTO `lang_trans` VALUES (17, 1, 21, 'Could not upload your photo. Make sure the format of the file is correct.');
INSERT INTO `lang_trans` VALUES (18, 1, 22, 'Please specify your email.');
INSERT INTO `lang_trans` VALUES (19, 1, 23, 'Image file cannot be bigger than 500 Kb');
INSERT INTO `lang_trans` VALUES (20, 1, 26, 'Could not set the SMS limit');
INSERT INTO `lang_trans` VALUES (21, 1, 27, 'SMS limit value must be a number');
INSERT INTO `lang_trans` VALUES (22, 1, 28, 'Error while trying to save your mobile number');
INSERT INTO `lang_trans` VALUES (23, 1, 29, 'Your email has been sent. Thanks.');
INSERT INTO `lang_trans` VALUES (24, 1, 30, 'Invitations were sent to all or selected contacts, those already registered, were added in your Friends list');
INSERT INTO `lang_trans` VALUES (25, 1, 31, 'Invitations were sent to all selected contacts, and those already registered, were added in your Friends list');
INSERT INTO `lang_trans` VALUES (26, 1, 32, 'Password reminder has been sent');
INSERT INTO `lang_trans` VALUES (27, 1, 33, 'Your settings have been restored to the defaults');
INSERT INTO `lang_trans` VALUES (28, 1, 34, 'Your layout has been changed');
INSERT INTO `lang_trans` VALUES (29, 1, 35, 'Your message has been sent');
INSERT INTO `lang_trans` VALUES (30, 1, 36, 'Your reply has been sent');
INSERT INTO `lang_trans` VALUES (31, 1, 37, 'Your profle details have been saved.');
INSERT INTO `lang_trans` VALUES (32, 1, 38, 'Your life details have been changed.');
INSERT INTO `lang_trans` VALUES (33, 1, 39, 'Your life details have been saved.');
INSERT INTO `lang_trans` VALUES (34, 1, 40, 'Your IM has been deactivated');
INSERT INTO `lang_trans` VALUES (35, 1, 42, 'Your photo has been uploaded');
INSERT INTO `lang_trans` VALUES (36, 1, 43, 'Your photo has been changed.');
INSERT INTO `lang_trans` VALUES (37, 1, 44, 'Your settings have been saved.');
INSERT INTO `lang_trans` VALUES (38, 1, 45, 'The chosen user has been added to your friends list');
INSERT INTO `lang_trans` VALUES (39, 1, 46, 'SMS limit has been set');
INSERT INTO `lang_trans` VALUES (40, 1, 47, 'Your mobile number has been saved. Now you can post messages sending them via SMS to this number: ');
INSERT INTO `lang_trans` VALUES (41, 1, 48, 'Hello!\nOur site is a service that asks What are you doing now?.\nThis is my last message:\n\n#last_update\n#message_link\n\nAnd what are you doing now? Register with us and tell others!\n\nRegards\n');
INSERT INTO `lang_trans` VALUES (42, 1, 49, 'Invitation to ReVou');
INSERT INTO `lang_trans` VALUES (43, 1, 50, 'Password reminder from ReVou');
INSERT INTO `lang_trans` VALUES (44, 1, 51, 'Hello #user!\nYour password on ReVou is: #pass\n\nRegards\nReVou team');
INSERT INTO `lang_trans` VALUES (45, 1, 53, 'Welcome to ReVou');
INSERT INTO `lang_trans` VALUES (46, 1, 54, 'Hello #recipient_name!\n#author_name (#author_link) \nhas just posted a message on ReVou.com:\n\n#message\n\nRegards\nReVou team');
INSERT INTO `lang_trans` VALUES (47, 1, 55, 'New message at ReVou.com from #username!');
INSERT INTO `lang_trans` VALUES (48, 1, 56, 'Hello #recipient_name! #author_name has just posted a message: #message');
INSERT INTO `lang_trans` VALUES (49, 1, 57, 'Nudge from ReVou.com');
INSERT INTO `lang_trans` VALUES (50, 1, 59, 'Hello #recipient_name!\n#author_name (#author_link) \nhas just sent you a direct message:\n\n#message\n\nRegards\nReVou team');
INSERT INTO `lang_trans` VALUES (51, 1, 60, '#username sent you a direct message!');
INSERT INTO `lang_trans` VALUES (52, 1, 61, 'Hello #recipient_name! #author_name has just sent you a direct message on ReVou');
INSERT INTO `lang_trans` VALUES (53, 1, 62, 'Hello #recipient_name!\n#username (http://ReVou.com/profile/#username)\nhas just added you to his friends list.\n\nRegards\nReVou team');
INSERT INTO `lang_trans` VALUES (54, 1, 63, '#username has added you as a friend');
INSERT INTO `lang_trans` VALUES (55, 1, 64, 'Hello #recipient_name! #username has just added you to his friends list at ReVou');
INSERT INTO `lang_trans` VALUES (56, 1, 65, '#username has been nudged');
INSERT INTO `lang_trans` VALUES (57, 1, 67, '#username has been removed');
INSERT INTO `lang_trans` VALUES (58, 1, 68, 'You have left #username');
INSERT INTO `lang_trans` VALUES (59, 1, 69, '#username is blocked');
INSERT INTO `lang_trans` VALUES (60, 1, 70, '#username is unblocked');
INSERT INTO `lang_trans` VALUES (61, 1, 71, 'yesterday');
INSERT INTO `lang_trans` VALUES (62, 1, 72, 'days ago');
INSERT INTO `lang_trans` VALUES (63, 1, 73, 'hour ago');
INSERT INTO `lang_trans` VALUES (64, 1, 74, 'hours ago');
INSERT INTO `lang_trans` VALUES (65, 1, 75, 'minutes and');
INSERT INTO `lang_trans` VALUES (66, 1, 76, 'minute and');
INSERT INTO `lang_trans` VALUES (67, 1, 77, 'secs ago');
INSERT INTO `lang_trans` VALUES (68, 1, 78, 'minutes ago');
INSERT INTO `lang_trans` VALUES (69, 1, 81, 'Joined ReVou :)');
INSERT INTO `lang_trans` VALUES (70, 1, 82, 'Contact us');
INSERT INTO `lang_trans` VALUES (71, 1, 83, 'Subject:');
INSERT INTO `lang_trans` VALUES (72, 1, 84, 'Message:');
INSERT INTO `lang_trans` VALUES (73, 1, 85, 'Your name:');
INSERT INTO `lang_trans` VALUES (74, 1, 86, 'Your email:');
INSERT INTO `lang_trans` VALUES (75, 1, 87, 'Send');
INSERT INTO `lang_trans` VALUES (76, 1, 88, 'Direct message');
INSERT INTO `lang_trans` VALUES (77, 1, 89, 'Send the message to');
INSERT INTO `lang_trans` VALUES (78, 1, 90, 'characters left');
INSERT INTO `lang_trans` VALUES (79, 1, 91, 'Direct messages to');
INSERT INTO `lang_trans` VALUES (80, 1, 92, 'Inbox');
INSERT INTO `lang_trans` VALUES (81, 1, 93, 'Outbox');
INSERT INTO `lang_trans` VALUES (82, 1, 94, 'from');
INSERT INTO `lang_trans` VALUES (83, 1, 95, 'Remove');
INSERT INTO `lang_trans` VALUES (84, 1, 96, 'Remove this message');
INSERT INTO `lang_trans` VALUES (85, 1, 97, 'Reply to this message');
INSERT INTO `lang_trans` VALUES (86, 1, 98, 'Reply');
INSERT INTO `lang_trans` VALUES (87, 1, 99, 'No messages');
INSERT INTO `lang_trans` VALUES (88, 1, 100, 'to');
INSERT INTO `lang_trans` VALUES (89, 1, 101, '@');
INSERT INTO `lang_trans` VALUES (90, 1, 98, 'reply');
INSERT INTO `lang_trans` VALUES (91, 1, 102, 'Favorites');
INSERT INTO `lang_trans` VALUES (92, 1, 103, 'Remove from favorites');
INSERT INTO `lang_trans` VALUES (93, 1, 104, 'Add to favorites');
INSERT INTO `lang_trans` VALUES (94, 1, 105, 'Followers of');
INSERT INTO `lang_trans` VALUES (95, 1, 106, '');
INSERT INTO `lang_trans` VALUES (96, 1, 107, 'Follow');
INSERT INTO `lang_trans` VALUES (97, 1, 108, 'Forgot your password?');
INSERT INTO `lang_trans` VALUES (98, 1, 110, 'Remind now');
INSERT INTO `lang_trans` VALUES (99, 1, 111, 'Friends of');
INSERT INTO `lang_trans` VALUES (100, 1, 112, '');
INSERT INTO `lang_trans` VALUES (101, 1, 113, 'Stop following');
INSERT INTO `lang_trans` VALUES (102, 1, 114, 'Page');
INSERT INTO `lang_trans` VALUES (103, 1, 115, 'Previous');
INSERT INTO `lang_trans` VALUES (104, 1, 116, 'Next');
INSERT INTO `lang_trans` VALUES (105, 1, 118, 'Invite your friends!');
INSERT INTO `lang_trans` VALUES (106, 1, 119, 'Provide your details and see if your friends are already in ReVou');
INSERT INTO `lang_trans` VALUES (107, 1, 120, 'Import your contacts from');
INSERT INTO `lang_trans` VALUES (108, 1, 121, 'User name:');
INSERT INTO `lang_trans` VALUES (109, 1, 122, 'Password:');
INSERT INTO `lang_trans` VALUES (110, 1, 123, 'Your username and password will not be stored on our server');
INSERT INTO `lang_trans` VALUES (111, 1, 124, 'Check now');
INSERT INTO `lang_trans` VALUES (112, 1, 125, 'We found');
INSERT INTO `lang_trans` VALUES (113, 1, 126, 'of your contacts');
INSERT INTO `lang_trans` VALUES (114, 1, 127, 'Select all');
INSERT INTO `lang_trans` VALUES (115, 1, 128, 'Deselect all');
INSERT INTO `lang_trans` VALUES (116, 1, 129, 'Name and email');
INSERT INTO `lang_trans` VALUES (117, 1, 130, 'Member of ReVou');
INSERT INTO `lang_trans` VALUES (118, 1, 131, 'YES');
INSERT INTO `lang_trans` VALUES (119, 1, 132, 'NO');
INSERT INTO `lang_trans` VALUES (120, 1, 133, 'Hello!\nReVou is a service that asks What are you doing now?.\nThis is my last message:');
INSERT INTO `lang_trans` VALUES (121, 1, 134, 'And what are you doing now? Register in ReVou and tell others!\n\nRegards');
INSERT INTO `lang_trans` VALUES (122, 1, 135, 'Invite');
INSERT INTO `lang_trans` VALUES (123, 1, 136, 'No contacts were found');
INSERT INTO `lang_trans` VALUES (124, 1, 137, 'OR');
INSERT INTO `lang_trans` VALUES (125, 1, 139, 'Email addresses:');
INSERT INTO `lang_trans` VALUES (126, 1, 140, 'Comma separated');
INSERT INTO `lang_trans` VALUES (127, 1, 141, 'Only my friends can see my updates.');
INSERT INTO `lang_trans` VALUES (128, 1, 142, 'My mobile');
INSERT INTO `lang_trans` VALUES (129, 1, 143, 'My mobile number');
INSERT INTO `lang_trans` VALUES (130, 1, 145, 'Your mobile number is:');
INSERT INTO `lang_trans` VALUES (131, 1, 146, 'Save');
INSERT INTO `lang_trans` VALUES (132, 1, 147, 'Our SMS gateway number is: ');
INSERT INTO `lang_trans` VALUES (133, 1, 148, 'My API key');
INSERT INTO `lang_trans` VALUES (134, 1, 149, 'The key API is needed for applications that use the ReVou API. You must use it every time you call any of the API methods.');
INSERT INTO `lang_trans` VALUES (135, 1, 150, 'Your API key is:');
INSERT INTO `lang_trans` VALUES (136, 1, 151, 'Remove my account');
INSERT INTO `lang_trans` VALUES (137, 1, 152, 'If you want to completely remove your profile ReVou, click the button below. It will not be possible to restore your account, so think well before doing so!');
INSERT INTO `lang_trans` VALUES (138, 1, 153, 'Remove my account');
INSERT INTO `lang_trans` VALUES (139, 1, 154, 'Are you sure you want to completely remove your ReVou account?');
INSERT INTO `lang_trans` VALUES (140, 1, 155, 'Remove my account now!');
INSERT INTO `lang_trans` VALUES (141, 1, 156, 'My Instant Messenger');
INSERT INTO `lang_trans` VALUES (142, 1, 158, 'Instant Messenger:');
INSERT INTO `lang_trans` VALUES (143, 1, 159, 'E-mail or your IM ID:');
INSERT INTO `lang_trans` VALUES (144, 1, 160, 'Save changes');
INSERT INTO `lang_trans` VALUES (145, 1, 161, 'Deactivate my IM');
INSERT INTO `lang_trans` VALUES (146, 1, 162, 'My layout');
INSERT INTO `lang_trans` VALUES (147, 1, 163, 'Background');
INSERT INTO `lang_trans` VALUES (148, 1, 164, 'Message background');
INSERT INTO `lang_trans` VALUES (149, 1, 165, 'Boxes on the right');
INSERT INTO `lang_trans` VALUES (150, 1, 166, 'Other');
INSERT INTO `lang_trans` VALUES (151, 1, 167, 'Back to default settings');
INSERT INTO `lang_trans` VALUES (152, 1, 168, 'Background color:');
INSERT INTO `lang_trans` VALUES (153, 1, 169, 'Background image:');
INSERT INTO `lang_trans` VALUES (154, 1, 170, 'Use image');
INSERT INTO `lang_trans` VALUES (155, 1, 171, 'Tile image:');
INSERT INTO `lang_trans` VALUES (156, 1, 172, 'Fixed background:');
INSERT INTO `lang_trans` VALUES (157, 1, 173, 'Your update text color:');
INSERT INTO `lang_trans` VALUES (158, 1, 174, 'Your update background color:');
INSERT INTO `lang_trans` VALUES (159, 1, 175, 'Border color:');
INSERT INTO `lang_trans` VALUES (160, 1, 176, 'Side box background color:');
INSERT INTO `lang_trans` VALUES (161, 1, 177, 'Text color:');
INSERT INTO `lang_trans` VALUES (162, 1, 178, 'Link color:');
INSERT INTO `lang_trans` VALUES (163, 1, 179, 'Message area background color:');
INSERT INTO `lang_trans` VALUES (164, 1, 180, 'About me');
INSERT INTO `lang_trans` VALUES (165, 1, 181, 'Location:');
INSERT INTO `lang_trans` VALUES (166, 1, 182, 'Date of birth:');
INSERT INTO `lang_trans` VALUES (167, 1, 183, 'Day');
INSERT INTO `lang_trans` VALUES (168, 1, 184, 'Month');
INSERT INTO `lang_trans` VALUES (169, 1, 185, 'Year');
INSERT INTO `lang_trans` VALUES (170, 1, 186, 'About me:');
INSERT INTO `lang_trans` VALUES (171, 1, 187, 'Limit: 200 characters');
INSERT INTO `lang_trans` VALUES (172, 1, 188, 'More info URL:');
INSERT INTO `lang_trans` VALUES (173, 1, 189, 'Do you have a blog? Put its address here');
INSERT INTO `lang_trans` VALUES (174, 1, 190, 'Interests:');
INSERT INTO `lang_trans` VALUES (175, 1, 191, 'Comma separated, max 200 characters');
INSERT INTO `lang_trans` VALUES (176, 1, 192, 'My photo');
INSERT INTO `lang_trans` VALUES (177, 1, 193, 'Upload');
INSERT INTO `lang_trans` VALUES (178, 1, 194, 'Image library');
INSERT INTO `lang_trans` VALUES (179, 1, 195, 'Upload your photo');
INSERT INTO `lang_trans` VALUES (180, 1, 196, 'Show others your smile!');
INSERT INTO `lang_trans` VALUES (181, 1, 197, 'Max. file size is 1 MB, allowed formats: jpeg/png/gif');
INSERT INTO `lang_trans` VALUES (182, 1, 198, 'Upload now');
INSERT INTO `lang_trans` VALUES (183, 1, 199, 'Choose your photo from the library');
INSERT INTO `lang_trans` VALUES (184, 1, 200, 'Choose this photo');
INSERT INTO `lang_trans` VALUES (185, 1, 201, 'Repeat the password:');
INSERT INTO `lang_trans` VALUES (186, 1, 202, 'Full name:');
INSERT INTO `lang_trans` VALUES (187, 1, 203, 'My profile');
INSERT INTO `lang_trans` VALUES (188, 1, 204, 'Let my messages are visible to all users, not just to my friends');
INSERT INTO `lang_trans` VALUES (189, 1, 205, 'My stickers');
INSERT INTO `lang_trans` VALUES (190, 1, 206, 'Flash sticker');
INSERT INTO `lang_trans` VALUES (191, 1, 207, 'JavaScript stickers');
INSERT INTO `lang_trans` VALUES (192, 1, 208, 'Paste this code into your website/Blog:');
INSERT INTO `lang_trans` VALUES (193, 1, 209, 'Choose the color for your sticker:');
INSERT INTO `lang_trans` VALUES (194, 1, 210, 'Sticker color:');
INSERT INTO `lang_trans` VALUES (195, 1, 212, 'The code below will create a HTML sticker which will display a list of users that you follow:');
INSERT INTO `lang_trans` VALUES (196, 1, 213, 'Email notification');
INSERT INTO `lang_trans` VALUES (197, 1, 214, 'Notify me about new followers:');
INSERT INTO `lang_trans` VALUES (198, 1, 215, 'Notify me when somebody starts following me');
INSERT INTO `lang_trans` VALUES (199, 1, 216, 'OK, settings changed');
INSERT INTO `lang_trans` VALUES (200, 1, 217, 'An error occured while trying to change your settings');
INSERT INTO `lang_trans` VALUES (201, 1, 218, 'OK, notification mode changed to ');
INSERT INTO `lang_trans` VALUES (202, 1, 219, 'Error while changing notification mode to ');
INSERT INTO `lang_trans` VALUES (203, 1, 220, 'What are you doing?');
INSERT INTO `lang_trans` VALUES (204, 1, 221, 'My messages');
INSERT INTO `lang_trans` VALUES (205, 1, 223, 'Public replies');
INSERT INTO `lang_trans` VALUES (206, 1, 224, 'Customize ReVou');
INSERT INTO `lang_trans` VALUES (207, 1, 225, 'Add a picture (jpeg/gif/png):');
INSERT INTO `lang_trans` VALUES (208, 1, 226, 'Reply to message:');
INSERT INTO `lang_trans` VALUES (209, 1, 227, 'Your reply:');
INSERT INTO `lang_trans` VALUES (210, 1, 228, 'Results for');
INSERT INTO `lang_trans` VALUES (211, 1, 229, 'Settings');
INSERT INTO `lang_trans` VALUES (212, 1, 230, 'My account');
INSERT INTO `lang_trans` VALUES (213, 1, 231, 'My SMS credits');
INSERT INTO `lang_trans` VALUES (214, 1, 232, 'Step 1: Add your photo');
INSERT INTO `lang_trans` VALUES (215, 1, 233, 'Step 2: Activate your Instant Messenger');
INSERT INTO `lang_trans` VALUES (216, 1, 234, 'Step 3: Your details');
INSERT INTO `lang_trans` VALUES (217, 1, 235, 'Step 4: Invite your friends');
INSERT INTO `lang_trans` VALUES (218, 1, 236, 'Continue');
INSERT INTO `lang_trans` VALUES (219, 1, 237, 'Skip setup');
INSERT INTO `lang_trans` VALUES (220, 1, 238, 'FINISH');
INSERT INTO `lang_trans` VALUES (221, 1, 239, 'People interested in');
INSERT INTO `lang_trans` VALUES (222, 1, 240, 'Start!');
INSERT INTO `lang_trans` VALUES (223, 1, 241, 'Follow these 4 easy steps to setup your account:');
INSERT INTO `lang_trans` VALUES (224, 1, 242, 'Welcome to ReVou');
INSERT INTO `lang_trans` VALUES (225, 1, 243, 'Your Account in ReVou was created but it needs activation. To activate it click on the link in the confirmation e-mail that was sent to you.');
INSERT INTO `lang_trans` VALUES (226, 1, 244, 'Do not forget to check whether this email did not arrive in your SPAM folder');
INSERT INTO `lang_trans` VALUES (227, 1, 245, '');
INSERT INTO `lang_trans` VALUES (228, 1, 246, 'About us');
INSERT INTO `lang_trans` VALUES (229, 1, 247, 'Contact us');
INSERT INTO `lang_trans` VALUES (230, 1, 248, 'API');
INSERT INTO `lang_trans` VALUES (231, 1, 249, 'Help');
INSERT INTO `lang_trans` VALUES (232, 1, 250, 'Terms of use');
INSERT INTO `lang_trans` VALUES (233, 1, 251, 'Privacy policy');
INSERT INTO `lang_trans` VALUES (234, 1, 252, 'Search for friends');
INSERT INTO `lang_trans` VALUES (235, 1, 253, 'Go!');
INSERT INTO `lang_trans` VALUES (236, 1, 254, 'Location');
INSERT INTO `lang_trans` VALUES (237, 1, 255, 'direct messages');
INSERT INTO `lang_trans` VALUES (238, 1, 256, 'followers');
INSERT INTO `lang_trans` VALUES (239, 1, 257, 'friends');
INSERT INTO `lang_trans` VALUES (240, 1, 258, 'favorites');
INSERT INTO `lang_trans` VALUES (241, 1, 259, 'sms credits');
INSERT INTO `lang_trans` VALUES (242, 1, 260, 'messages');
INSERT INTO `lang_trans` VALUES (243, 1, 261, 'Notify me about new messages:');
INSERT INTO `lang_trans` VALUES (244, 1, 262, ' of any type from people I follow');
INSERT INTO `lang_trans` VALUES (245, 1, 263, ' only about direct messages from people I follow');
INSERT INTO `lang_trans` VALUES (246, 1, 264, 'Notify me by:');
INSERT INTO `lang_trans` VALUES (247, 1, 265, 'web only');
INSERT INTO `lang_trans` VALUES (248, 1, 266, 'e-mail');
INSERT INTO `lang_trans` VALUES (249, 1, 267, 'IM');
INSERT INTO `lang_trans` VALUES (250, 1, 268, 'SMS');
INSERT INTO `lang_trans` VALUES (251, 1, 269, 'Activate my IM (MSN, Gtalk etc.)');
INSERT INTO `lang_trans` VALUES (252, 1, 270, 'About');
INSERT INTO `lang_trans` VALUES (253, 1, 271, 'Name:');
INSERT INTO `lang_trans` VALUES (254, 1, 272, 'Location:');
INSERT INTO `lang_trans` VALUES (255, 1, 273, 'Interesses:');
INSERT INTO `lang_trans` VALUES (256, 1, 274, 'About me:');
INSERT INTO `lang_trans` VALUES (257, 1, 275, 'Age:');
INSERT INTO `lang_trans` VALUES (258, 1, 276, 'WWW:');
INSERT INTO `lang_trans` VALUES (259, 1, 277, 'Actions');
INSERT INTO `lang_trans` VALUES (260, 1, 278, 'Send a message to ');
INSERT INTO `lang_trans` VALUES (261, 1, 279, 'Nudge');
INSERT INTO `lang_trans` VALUES (262, 1, 280, 'Stop following');
INSERT INTO `lang_trans` VALUES (263, 1, 281, 'Remove');
INSERT INTO `lang_trans` VALUES (264, 1, 282, 'Block');
INSERT INTO `lang_trans` VALUES (265, 1, 283, 'Follow');
INSERT INTO `lang_trans` VALUES (266, 1, 284, 'Unblock');
INSERT INTO `lang_trans` VALUES (267, 1, 285, 'Add');
INSERT INTO `lang_trans` VALUES (268, 1, 286, 'as a friend');
INSERT INTO `lang_trans` VALUES (269, 1, 287, 'Register');
INSERT INTO `lang_trans` VALUES (270, 1, 288, 'Login');
INSERT INTO `lang_trans` VALUES (271, 1, 289, 'Your user name or email:');
INSERT INTO `lang_trans` VALUES (272, 1, 290, 'Remember me');
INSERT INTO `lang_trans` VALUES (273, 1, 291, 'Login');
INSERT INTO `lang_trans` VALUES (274, 1, 292, 'Your Name (without space between letters and words):');
INSERT INTO `lang_trans` VALUES (275, 1, 293, 'e-mail:');
INSERT INTO `lang_trans` VALUES (276, 1, 294, 'I accept');
INSERT INTO `lang_trans` VALUES (277, 1, 295, 'Security code:');
INSERT INTO `lang_trans` VALUES (278, 1, 296, 'Retype the code:');
INSERT INTO `lang_trans` VALUES (279, 1, 297, 'Create account');
INSERT INTO `lang_trans` VALUES (280, 1, 298, 'Most popular');
INSERT INTO `lang_trans` VALUES (281, 1, 299, 'Most recent');
INSERT INTO `lang_trans` VALUES (282, 1, 300, 'Add me');
INSERT INTO `lang_trans` VALUES (283, 1, 302, 'Your message was too long and was trimmed to 140 characters: ');
INSERT INTO `lang_trans` VALUES (284, 1, 303, 'Show all my friends');
INSERT INTO `lang_trans` VALUES (285, 1, 304, '');
INSERT INTO `lang_trans` VALUES (286, 1, 305, 'Code:');
INSERT INTO `lang_trans` VALUES (287, 1, 306, 'Friends of ');
INSERT INTO `lang_trans` VALUES (288, 1, 307, '');
INSERT INTO `lang_trans` VALUES (289, 1, 308, 'You have');
INSERT INTO `lang_trans` VALUES (290, 1, 309, 'Max. picture size is 500 kb.');
INSERT INTO `lang_trans` VALUES (291, 1, 310, 'Home');
INSERT INTO `lang_trans` VALUES (292, 1, 311, 'My panel');
INSERT INTO `lang_trans` VALUES (293, 1, 312, 'Invite friends');
INSERT INTO `lang_trans` VALUES (294, 1, 313, 'Vision Map');
INSERT INTO `lang_trans` VALUES (295, 1, 314, 'Settings');
INSERT INTO `lang_trans` VALUES (296, 1, 315, 'Logout');
INSERT INTO `lang_trans` VALUES (297, 1, 316, 'Tile vertically');
INSERT INTO `lang_trans` VALUES (298, 1, 317, ' Tile horizontally');
INSERT INTO `lang_trans` VALUES (299, 1, 318, 'Tile horizontally and vertically');
INSERT INTO `lang_trans` VALUES (300, 1, 320, 'Choose from the background library:');
INSERT INTO `lang_trans` VALUES (301, 1, 321, 'Hello');
INSERT INTO `lang_trans` VALUES (302, 1, 324, 'Your SMS credits');
INSERT INTO `lang_trans` VALUES (303, 1, 308, 'Currently you have');
INSERT INTO `lang_trans` VALUES (304, 1, 326, 'Credit anount');
INSERT INTO `lang_trans` VALUES (305, 1, 327, 'Choose sms plan');
INSERT INTO `lang_trans` VALUES (306, 1, 328, 'credits for');
INSERT INTO `lang_trans` VALUES (307, 1, 329, 'BUY');
INSERT INTO `lang_trans` VALUES (308, 1, 330, '1 SMS credit costs');
INSERT INTO `lang_trans` VALUES (309, 1, 331, 'so the total price is');
INSERT INTO `lang_trans` VALUES (310, 1, 332, 'SMS plans you bought');
INSERT INTO `lang_trans` VALUES (311, 1, 333, 'Transaction date');
INSERT INTO `lang_trans` VALUES (312, 1, 334, 'Transaction time');
INSERT INTO `lang_trans` VALUES (313, 1, 335, 'Credits bought');
INSERT INTO `lang_trans` VALUES (314, 1, 336, 'Value');
INSERT INTO `lang_trans` VALUES (315, 1, 337, 'SMS limit');
INSERT INTO `lang_trans` VALUES (316, 1, 338, 'To save on SMS credits you can set a limit of SMSs that you can send/receive. This limit can be changed or reset anytime.');
INSERT INTO `lang_trans` VALUES (317, 1, 339, 'Used credits');
INSERT INTO `lang_trans` VALUES (318, 1, 340, 'Your limit is');
INSERT INTO `lang_trans` VALUES (319, 1, 341, 'Reset');
INSERT INTO `lang_trans` VALUES (320, 1, 342, 'Set SMS limit');
INSERT INTO `lang_trans` VALUES (321, 1, 343, 'Set your SMS limit to');
INSERT INTO `lang_trans` VALUES (322, 1, 344, 'Set now');
INSERT INTO `lang_trans` VALUES (323, 1, 345, 'New SMS credits added');
INSERT INTO `lang_trans` VALUES (324, 1, 346, 'The transaction ended successfully,');
INSERT INTO `lang_trans` VALUES (325, 1, 347, 'sms credits have been added to your account');
INSERT INTO `lang_trans` VALUES (326, 1, 348, 'Transaction error');
INSERT INTO `lang_trans` VALUES (327, 1, 349, 'This transaction is already finished or does not exist. Please try once more.');
INSERT INTO `lang_trans` VALUES (328, 1, 350, 'New SMS credits added');
INSERT INTO `lang_trans` VALUES (329, 1, 351, 'Unfortunately, some error occured while processing your transaction.');
INSERT INTO `lang_trans` VALUES (330, 1, 352, 'Language');
INSERT INTO `lang_trans` (`id`, `lang_id`, `var_id`, `value`) VALUES
  (2807,1,353,'Your Twitter.com account'),
  (2808,1,354,'User name'),
  (2809,1,355,'Password'),
  (2810,1,356,'Send messages to my twitter.com account');
INSERT INTO `lang_trans` VALUES (331, 1, 52, 'Hello #username!\nYou've just joined ReVou. Click on the link below to complete your registration:\n#link\n\nFollow the instructions to activate your MSN or Gtalk. It's that simple! Then invite your friends to join also and ask them to add you as a friend');



/* Groups Upgrade Data */


ALTER TABLE `messages` ADD `group_id` INT DEFAULT '0' NOT NULL AFTER `time` ;
UPDATE `lang_trans` SET `value` = 'Search friends or group' WHERE `id` = '234' LIMIT 1 ;

INSERT INTO `lang_vars` VALUES (357, 'my_groups_header');
INSERT INTO `lang_vars` VALUES (358, 'label_groups');
INSERT INTO `lang_vars` VALUES (359, 'select_group');
INSERT INTO `lang_vars` VALUES (360, 'select_group_default');
INSERT INTO `lang_vars` VALUES (361, 'alert_message_group');
INSERT INTO `lang_vars` VALUES (362, 'label_search_group');
INSERT INTO `lang_vars` VALUES (363, 'tab_group_top10');
INSERT INTO `lang_vars` VALUES (364, 'group_label');
INSERT INTO `lang_vars` VALUES (365, 'group_members');
INSERT INTO `lang_vars` VALUES (366, 'groups_label');
INSERT INTO `lang_vars` VALUES (367, 'groups_all_label');
INSERT INTO `lang_vars` VALUES (368, 'you_are_sure');
INSERT INTO `lang_vars` VALUES (369, 'group_join_label');
INSERT INTO `lang_vars` VALUES (370, 'group_leave_label');


INSERT INTO `lang_trans` VALUES (2813, 1, 357, 'My groups');
INSERT INTO `lang_trans` VALUES (2819, 1, 358, 'Your groups:');
INSERT INTO `lang_trans` VALUES (2820, 1, 359, 'Select group');
INSERT INTO `lang_trans` VALUES (2821, 1, 360, 'Without group');
INSERT INTO `lang_trans` VALUES (2822, 1, 361, 'Please select SubGroup!');
INSERT INTO `lang_trans` VALUES (2823, 1, 362, 'Search group');
INSERT INTO `lang_trans` VALUES (2824, 1, 363, 'TOP10 Groups');
INSERT INTO `lang_trans` VALUES (2825, 1, 364, 'Group');
INSERT INTO `lang_trans` VALUES (2826, 1, 365, 'Group members');
INSERT INTO `lang_trans` VALUES (2827, 1, 366, 'Groups');
INSERT INTO `lang_trans` VALUES (2828, 1, 367, 'All Groups');
INSERT INTO `lang_trans` VALUES (2829, 1, 368, 'You are sure?');
INSERT INTO `lang_trans` VALUES (2830, 1, 369, 'Join to group');
INSERT INTO `lang_trans` VALUES (2831, 1, 370, 'Leave group');

UPDATE `lang_trans` SET `value` = 'Search friends or group' WHERE `id` = '234' LIMIT 1 ;