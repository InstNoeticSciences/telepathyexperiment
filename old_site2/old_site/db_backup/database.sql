


CREATE TABLE `blocked_users` (
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL default '0',
  `blocked_user` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);



CREATE TABLE `favorites` (
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL default '0',
  `message` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);



CREATE TABLE `followed` (
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL default '0',
  `followed` int(11) NOT NULL default '0',
  `friend_only` tinyint(1) NOT NULL default '0',
  `sms_flag` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `followed` (`followed`)
);



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
);





insert into layouts values (1, 1, 'ffffff', 1, 1, 0, 1, 'cccccc', '000000', '007eff', '007eff', '000000', 'ffffff', '39ba00', 'ebebeb', '39ba00');




CREATE TABLE `messages` (
  `id` int(11) NOT NULL auto_increment,
  `user` varchar(50) NOT NULL default '',
  `time` int(11) NOT NULL default '0',
  `msg` varchar(140) NOT NULL default '',
  `from` varchar(10) NOT NULL default 'web',
  `direct` int(11) NOT NULL default '0',
  `reply` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);


CREATE TABLE `nudges` (
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL default '0',
  `txt` text NOT NULL,
  PRIMARY KEY  (`id`)
);



CREATE TABLE `sms_plans` (
  `id` int(11) NOT NULL auto_increment,
  `credits` int(11) NOT NULL default '0',
  `price` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`id`)
);



insert into sms_plans values (1, 5, '5.00');
insert into sms_plans values (2, 10, '8.00');
insert into sms_plans values (3, 15, '10.00');
insert into sms_plans values (5, 20, '12.00');
insert into sms_plans values (6, 30, '20.00');




CREATE TABLE `static_pages` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
);



CREATE TABLE `tiny_url` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(200) NOT NULL default '',
  `tiny` varchar(9) NOT NULL default '',
  `author_id` int(11) NOT NULL default '0',
  `expires` time NOT NULL default '00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `tiny` (`tiny`)
);




CREATE TABLE `transactions` (
  `id` int(11) NOT NULL auto_increment,
  `time` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `credits` int(11) NOT NULL,
  `value` decimal(10,2) NOT NULL default '0.00',
  `status` int(11) NOT NULL default '0' COMMENT '0=new, 1=ok, 2=cancelled',
  PRIMARY KEY  (`id`)
);



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
  PRIMARY KEY  (`id`),
  KEY `username` (`username`)
);



insert into users values (1, 0, 'admin', 'Admin', 'admin', 'admin@company.com', 1, '0700 997 666', '', '', 124326000, 'administration', 'I+am+the+admin%2C+you+must+obey+my+orders', 'London', 'http://google.com', 0, 0, 'email', 0, '942.jpg', '21232f297a57a5a743894a0e4a801fc3', '-0.126236', '51.5002', 0, 1000, 0);
