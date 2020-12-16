
CREATE TABLE `groups_links` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


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


ALTER TABLE `messages` ADD `group_id` INT DEFAULT '0' NOT NULL AFTER `time` ;

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
