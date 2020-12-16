<?
/*=========================================================
This is the main configuration file of the Twitter Clone.
Please BE VERY CAREFUL while making any changes in it.

How to change configuration:
Any config option consists of a name and a value.
If a value is a string, it's wrapped in quotation marks ("").
DO NOT REMOVE THE QUOTATION MARKS!!!
Example:
define(name, "value");
define("title", "ReVou | What are you doing??");

To set a particular configuration, change ONLY the VALUES
=========================================================*/
error_reporting(E_ALL ^ E_NOTICE);

//=========================================================
//DATABSE DETAILS
//=========================================================
define(db_host, "db2218.perfora.net");
define(db_name, "db314053431");
define(db_user, "dbo314053431");
define(db_pass, "ReVou99834");
define(db_type, "MySQL");;
//=========================================================

//admin_panel login data
//=========================================================
define(admin_user, "admin");
define("admin_pass", "juno1092");
//=========================================================

define("CONTACT_MAIL", "gre.bris@gmail.com");
define("title", "The Telephone Telepathy Experiment");

//Meta tags
define("keywords", "twitter, miniblog, clone, what are you doing,micro blogging, blog, jaiku, blogging");
define("description", "telepathyexperiment.com | What are you doing? | Yet Another Twitter Clone");

//rpx account details
define('RPX_API_KEY','a864dc05bb32b908ac99a8441bfe4a44dd477bf7');
define('RPX_API_URL','https://revodemo.rpxnow.com/');
define('RPX_TOKEN_URL','http://www.telepathyexperiment.com/openIDLogin/&keepThis=true&TB_iframe=true&height=250&width=400');

//the root adress of the site. No slash "/" at the end!
define(root_domain, "http://www.telepathyexperiment.com");
//how many characters has the link in a message have to be treated as long
define(link_lenght_limit, 20);

define(spp, 20); //stuff per page - pagination
define(mpp, 20); //messages per page

//picture sizes
define("thsize", 60);

//images in messages:
define("post_img_size", 1048576);
define("post_img_max_width", 100);
define("post_img_max_height", 60);

define(max_length, 140); //max message length

//encryption password used to encrypt user data sent in a confirmation link
define(encryption_key, "tweet_tweet");

define(treshold, 2);

//rss feed data
define(rss_link, "http://www.telepathyexperiment.com/"); //link to a page (placed in the feed)
define(rss_limit, 30); //max messages to be put in the feed
define(rss_guid_prefix, "twittr_clne-"); //rss guid prefix

//Instant Messenger accounts
define(im_list, "MSN,ICQ,GTalk/Jabber,AIM,Yahoo Messenger"); //protocol name list
define("im_account_msn", "revou.im@hotmail.com");
define("im_account_icq", "354848349");
define("im_account_jabber", "revou.im@gmail.com");
define("im_account_yahoo", "yourim@yahoo.com");
define("im_account_aim", "yourim@aim.com");
define("gateway_phone", "+44700666997");

//paypal
define("paypal_business", "your@paypaladdress.com");
define(paypal_addr, "https://www.paypal.com/cgi-bin/webscr"); //paypal address - payment form is sent to it
define(paypal_success, "buy_ok"); //name of the page the user is sent to after a successful payment
define(paypal_failure, "buy_fail"); //name of the page the user is sent to when the payment goes wrong or is cancelled

//sms gateway
define("sms_user", "clickatelluser");
define("sms_pass", "pass");
define("sms_api_id", "12345678");

// Languages
define("lang_default", "ENG");

// sms callback: put it in the Clickatell configuration
// Clickatell will send sms messages to this address
// http://www.revou.com/demo/get_sms.php
?>