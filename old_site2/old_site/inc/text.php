<?
//IMPORTANT NOTICE:
// "\n" is a new line symbol, strings starting witch a hash (eg. #user) will be automatically replaced, so please to not change them

//error messages
define(err_fill_all, "Please fill all fields of this form.");
define(err_email, "An error occured while trying to send your email. Please try again.");
define(err_email_incorrect, "Given email address is incorrect.");
define(err_email_exists, "An account with this email already exists. Choose a different one.");
define(err_login_incorrect, "Login incorrect.");
define(err_invitations, "An error occured while trying to send your invitations.");
define(err_email_not_found, "Given email does not exist in our database.");
define(err_reminder_error, "An error occured while trying to send you the password reminder.");
define(err_accept_terms, "You have to accept the terms of use.");
define(err_choose_name, "Choose a user name.");
define(err_account_exists, "An account with this name already exists.");
define(err_password_mismatch, "The password and its confirmation don't match.");
define(err_invalid_code, "Given code is incorrect.");
define(err_create_account, "Could not create your account. Try again later.");
define(err_msg_too_long, "The message is too long. It can't exceed 140 characters.");
define(err_layout_change, "An error occured while trying to change your layout.");
define(err_about_me_too_long, "The 'about me' text shouldn't be longer than 200 characters.");
define(err_interests_too_long, "The 'interests' text shouldn't be longer than 200 characters.");
define(err_life_change, "An error occured while trying to change information about your life");
define(err_set_im, "Could not activate your IM");
define(err_photo_upload, "Could not upload your photo. Make sure the format of the file is correct.");
define(err_email_needed, "Please specify your email.");
define(err_file_too_large, "Image file cannot be bigger than 500 Kb");
define(err_email_confirmation_needed, "If you want to log in, you have to activate your account using the ling you got in the confirmation email. If you didn't receive the email, please check your spam folder.");
define(err_account_inactive, "Account is inactive. To activate your account using the ling you got in the confirmation email. If you didn't receive the email, please check your spam folder.");
define(err_sms_limit, "Could not set the SMS limit");
define(err_sms_limit_nan, "SMS limit value must be a number");
define(err_mobile, "Error while trying to save your mobile number");

//success messages
define(ok_email_sent, "Your email has been sent. Thanks.");
define(ok_invitations_sent, "Invitations were sent to all or selected contacts, those already registered, were added in your Friends list");
define(ok_invitations_sent_nolist, "Invitations were sent to all selected contacts, and those already registered, were added in your Friends list");
define(ok_reminder_sent, "Password reminder has been sent");
define(ok_back_to_defaults, "Your settings have been restored to the defaults");
define(ok_layout_changed, "Your layout has been changed");
define(ok_msg_sent, "Your message has been sent");
define(ok_reply_sent, "Your reply has been sent");
define(ok_profile_saved, "Your profle details have been saved.");
define(ok_life_changed, "Your life details have been changed.");
define(ok_life_saved, "Your life details have been saved.");
define(ok_im_deactivated, "Your IM has been deactivated");
define(ok_im_set, "Your IM has been activated. Now add <span id='im_contact'>#contact</span> to your contacts list in your IM.");
define(ok_photo_uploaded, "Your photo has been uploaded");
define(ok_photo_changed, "Your photo has been changed.");
define(ok_settings_saved, "Your settings have been saved.");
define(ok_added_as_friend, "The chosen user has been added to your friends list");
define(ok_sms_limit_set, "SMS limit has been set");
define(ok_phone_saved, "Your mobile number has been saved. Now you can post messages sending them via SMS to this number: ");

//invitation to ReVou. if you modify it, leave #name and #sender_name as it is,
//because it will be automatically replaced by the user's name
define(invitation_mail, "Hello!\nOur site is a service that asks \"What are you doing now?\".\nThis is my last message:\n\n#last_update\n#message_link\n\nAnd what are you doing now? Register with us and tell others!\n\nRegards\n");
define(invitation_subject, "Invitation to ReVou");

//password reminder
define(reminder_subject, "Password reminder from ReVou");
define(reminder_mail, "Hello #user!\nYour password on ReVou is: #pass\n\nRegards\nReVou team");

//account confirmation mail
define(confirmation_mail, "Hello #username!\nYou've just joined ReVou. Click on the link below to complete your registration:\n#link\n\nFollow the instructions to activate your MSN or Gtalk. It's that simple! Then invite your friends to join also and ask them to add you as a friend.\n\n In case you has any doubt, go to the HELP section or send us an email from the Contact Us section.\nRegards\nReVou team");
define(confirmation_subject, "Welcome to ReVou");

//message notification
define(notification_mail, "Hello #recipient_name!\n#author_name (#author_link) \nhas just posted a message on ReVou.com:\n\n#message\n\nRegards\nReVou team");
define(notification_subject, "New message at ReVou.com from #username!");

//message notification sms
define(notification_sms, "Hello #recipient_name! #author_name has just posted a message: #message");

define(nudge_subject, "Nudge from ReVou.com");
define(nudge, "Hello, Why don't you write something at ReVou.com? I can't wait!");

define(direct_notification_mail, "Hello #recipient_name!\n#author_name (#author_link) \nhas just sent you a direct message:\n\n#message\n\nRegards\nReVou team");
define(direct_notification_subject, "#username sent you a direct message!");

define(direct_notification_sms, "Hello #recipient_name! #author_name has just sent you a direct message on ReVou");

define(added_as_friend_mail, "Hello #recipient_name!\n#username (http://ReVou.com/profile/#username)\nhas just added you to his friends list.\n\nRegards\nReVou team");
define(added_as_friend_subject, "#username has added you as a friend");

define(added_as_friend_sms, "Hello #recipient_name! #username has just added you to his friends list at ReVou");

define(nudge_ok, "#username has been nudged");
define(follow_ok, "You're following #username now");
define(remove_ok, "#username has been removed");
define(leave_ok, "You have left #username");
define(block_ok, "#username is blocked");
define(unblock_ok, "#username is unblocked");

//time function
define(yesterday, "yesterday");
define(days_ago, "days ago");
define(hour_ago, "hour ago");
define(hours_ago, "hours ago");
define(mins_and, "minutes and");
define(min_and, "minute and");
define(secs_ago, "secs ago");
define(minutes_ago, "minutes ago");

//predefined messages
define(default_message_from_admin, "Welcome to ReVou, we hope that you'll enjoy the site and if you have any questions, go to the HELP section or send us an e-mail by visiting the Contact Us section.");
define(user_first_message, "Joined ReVou :)");

//contact.tpl
define(contact_us, "Contact us");
define(label_subject, "Subject:");
define(label_message, "Message:");
define(label_your_name, "Your name:");
define(label_your_email, "Your email:");
define(label_send, "Send");

//direct_message.tpl
define(direct_msg, "Direct message");
define(send_msg_to, "Send the message to");
define(chars_left, "characters left");

//direct_messages.tpl
define(direct_msgs_to, "Direct messages to");
define(inbox, "Inbox");
define(outbox, "Outbox");
define(from, "from");
define(label_delete, "Remove");
define(title_delete, "Remove this message");
define(title_reply, "Reply to this message");
define(label_reply, "Reply");
define(no_msg, "No messages");
define(to, "to");
define(in_reply_to, "@");

//favorites.tpl
define(label_reply, "reply");
define(favorites_header, "Favorites");
define(title_fav_del, "Remove from favorites");
define(title_fav_add, "Add to favorites");

//followers
define(followers_of1, "Followers of");
define(followers_of2, "");
define(label_follow, "Follow");

//forgot_password.tpl
define(forgot_header, "Forgot your password?");
define(forgot_paragraph, "If you don't remember your password, please write your email address and your password will be sent to it immediately");
define(label_remind, "Remind now");

//friends
define(friends_of1, "Friends of");
define(friends_of2, "");
define(label_stop_following, "Stop following");

//home.tpl
define(pagination_page, "Page");
define(pagination_prev, "Previous");
define(pagination_next, "Next");
define(main_header, "Tell the world what you're doing at this moment!");

//invite.tpl
define(invite_header, "Invite your friends!");
define(invite_paragraph, "Provide your details and see if your friends are already in ReVou");
define(import_contacts_from, "Import your contacts from");
define(label_user_name, "User name:");
define(label_password, "Password:");
define(pass_not_stored, "Your username and password will not be stored on our server");
define(label_check_now, "Check now");
define(we_found1, "We found");
define(we_found2, "of your contacts");
define(check_all, "Select all");
define(uncheck_all, "Deselect all");
define(name_and_mail, "Name and email");
define(member_of, "Member of ReVou");
define(yes, "YES");
define(no, "NO");
define(invitation_preview1, "Hello!\nReVou is a service that asks \"What are you doing now?\".\nThis is my last message:");
define(invitation_preview2, "And what are you doing now? Register in ReVou and tell others!\n\nRegards");
define(label_invite, "Invite");
define(no_contacts_found, "No contacts were found");
define(big_or, "OR");
define(enter_some_emails, "Write your friends' emails below and we will send invitations to them.");
define(label_emails, "Email addresses:");
define(separate_commas, "Comma separated");

//message.tpl
define(updates_protected, "Only my friends can see my updates.");

//my_mobile
define(my_mobile, "My mobile");
define(my_mobile_header, "My mobile number");
define(my_mobile_text, "Specify your mobile number (including the +xx country code) to send and receive messages and notifications to and from ReVou! Be always up to date with what's gong on!");
define(my_mobile_label, "Your mobile number is:");
define(save, "Save");
define(gateway_info, "Our SMS gateway number is: ");

//my_api_key.tpl
define(api_key_header, "My API key");
define(api_key_explanation, "The key API is needed for applications that use the ReVou API. You must use it every time you call any of the API methods.");
define(api_key_is, "Your API key is:");

//my_delete.tpl
define(profile_del_header, "Remove my account");
define(profile_del_explanation, "If you want to completely remove your profile ReVou, click the button below. It will not be possible to restore your account, so think well before doing so!");
define(label_remove_account, "Remove my account");
define(profile_del_u_sure, "Are you sure you want to completely remove your ReVou account?");
define(label_delete_account, "Remove my account now!");

//my_im.tpl
define(my_im_header, "My Instant Messenger");
define(my_im_explanation, "Activating your IM will enable you to post messages by it and to receive other people's messages and notifications.");
define(label_im_type, "Instant Messenger:");
define(label_im_id, "E-mail or your IM ID:");
define(label_save_changes, "Save changes");
define(label_deactivate, "Deactivate my IM");

//my_layout.tpl
define(my_lay_header, "My layout");
define(my_lay_background, "Background");
define(my_lay_bubble, "Message background");
define(my_lay_boxes, "Boxes on the right");
define(my_lay_other, "Other");
define(label_back_default, "Back to default settings");
define(label_back_color, "Background color:");
define(label_back_image, "Background image:");
define(label_use_image, "Use image");
define(label_tile_image, "Tile image:");
define(label_back_fixed, "Fixed background:");
define(label_bubble_text, "Your update text color:");
define(label_bubble_fill, "Your update background color:");
define(label_border_color, "Border color:");
define(label_side_fill, "Side box background color:");
define(label_text_color, "Text color:");
define(label_link_color, "Link color:");
define(label_msg_area, "Message area background color:");

//my_life.tpl
define(life_header, "About me");
define(label_location, "Location:");
define(label_lang, "Language:");
define(label_dob, "Date of birth:");
define(option_day, "Day");
define(option_month, "Month");
define(option_year, "Year");
define(label_about_me, "About me:");
define(limit_200chr, "Limit: 200 characters");
define(label_more_info, "More info URL:");
define(label_url, "Do you have a blog? Put its address here");
define(label_interests, "Interests:");
define(comma_separated_200chr, "Comma separated, max 200 characters");

//my_photo.tpl
define(photo_header, "My photo");
define(tab_upload, "Upload");
define(tab_picture_set, "Image library");
define(upload_your_photo, "Upload your photo");
define(upload_comment, "Show others your smile!");
define(upload_hint, "Max. file size is 1 MB, allowed formats: jpeg/png/gif");
define(label_upload_now, "Upload now");
define(choose_your_photo, "Choose your photo from the library");
define(label_choose_this_photo, "Choose this photo");

//my_profile.tpl
define(label_repeat_pass, "Repeat the password:");
define(label_full_name, "Full name:");
define(my_profile_header, "My profile");
define(visibility, "Let my messages are visible to all users, not just to my friends");

//my_sticker.tpl
define(sticker_header, "My stickers");
define(sticker_flash, "Flash sticker");
define(sticker_js, "JavaScript stickers");
define(paste_code_flash, "Paste this code into your website/Blog:");
define(sticker_choose_color, "Choose the color for your sticker:");
define(label_sticker_color, "Sticker color:");
define(sticker_js_comment, "Do you want something simpler that you'll be able to customize using CSS? Paste this code into your website/blog:");
define(sticker_friends_comment, "The code below will create a HTML sticker which will display a list of users that you follow:");

//notification.tpl
define(notification_header, "Email notification");
define(notification_label, "Notify me about new followers:");
define(notification_comment, "Notify me when somebody starts following me");

define(setting_ok, "OK, settings changed");
define(setting_err, "An error occured while trying to change your settings");

define(notify_way_ok, "OK, notification mode changed to ");
define(notify_way_err, "Error while changing notification mode to ");

//profile.tpl
define(what_are_you_doing, "What are you doing?");
define(profile_tab_mine, "My messages");
define(profile_tab_with_friends, "Your friends' messages");
define(profile_tab_replys, "Public replies");
define(profile_tab_customize, "Customize ReVou");
define(upload_picture, "Add a picture (jpeg/gif/png):");

//reply.tpl
define(reply_to_msg, "Reply to message:");
define(your_reply, "Your reply:");

//search.tpl
define(results_for, "Results for");

//settings
define(settings, "Settings");
define(my_account, "My account");
define(my_sms_credits, "My SMS credits");

//steps for new user
define(step1, "Step 1: Add your photo");
define(step2, "Step 2: Activate your Instant Messenger");
define(step3, "Step 3: Your details");
define(step4, "Step 4: Invite your friends");
define(link_continue, "Continue");
define(skip_setup, "Skip setup");
define(link_finish, "FINISH");

//tag.tpl
define(people_interested_in, "People interested in");

//welcome.tpl
define(link_start, "Start!");
define(follow_4_steps, "Follow these 4 easy steps to setup your account:");
define(welcome, "Welcome to ReVou");

//welcom_info.tpl
define(welcome_info1, "Your Account in ReVou was created but it needs activation. To activate it click on the link in the confirmation e-mail that was sent to you.");
define(welcome_info2, "Do not forget to check whether this email did not arrive in your SPAM folder");

//index.tpl
define(text_on_black, "");
define(bm_about, "About us");
define(bm_contact, "Contact us");
define(bm_api, "API");
define(bm_help, "Help");
define(bm_terms, "Terms of use");
define(bm_privacy, "Privacy policy");

//right.tpl
define(friend_search, "Search for friends");
define(label_search, "Go!");
define(i_am_in, "Location");
define(qty_direct_msg, "direct messages");
define(qty_followers, "followers");
define(qty_friends, "friends");
define(qty_favorites, "favorites");
define(qty_sms_credits, "sms credits");
define(qty_updates, "messages");
define(new_msg_notify, "Notify me about new messages:");
define(notify_any_type, " of any type from people I follow");
define(notify_direct_only, " only about direct messages from people I follow");
define(notify_by, "Notify me by:");
define(n_web, "web only");
define(n_email, "e-mail");
define(n_im, "IM");
define(n_sms, "SMS");
define(activate_im, "Activate my IM (MSN, Gtalk etc.)");
define(u_about, "About");
define(u_fullname, "Name:");
define(u_location, "Location:");
define(u_interests, "Interesses:");
define(u_bio, "About me:");
define(u_age, "Age:");
define(u_www, "WWW:");
define(actions, "Actions");
define(a_send_msg, "Send a message to ");
define(a_nudge, "Nudge");
define(a_leave, "Stop following");
define(a_remove, "Remove");
define(a_block, "Block");
define(a_follow, "Follow");
define(unblock, "Unblock");
define(a_add, "Add");
define(a_as_friend, "as a friend");

define(link_register, "Register");
define(link_login, "Login");
define(label_uname_email, "Your user name or email:");
define(label_remember, "Remember me");
define(label_login, "Login");
define(label_your_uname, "Your Name (without space between letters and words):");
define(label_email, "e-mail:");
define(i_accept, "I accept");
define(label_catcha1, "Security code:");
define(label_catcha2, "Retype the code:");
define(label_create_account, "Create account");
define(tab_popular, "Most popular");
define(tab_recent, "Most recent");

//other
define(add_me, "Add me");
define(refresh_warning, "If the picture didn't change refresh the page using Ctrl+F5");
define(im_msg_too_long, "Your message was too long and was trimmed to 140 characters: ");
define(all_friends, "Show all my friends");

//new
define(i_accept2, "");
define(label_captcha2, "Code:");
define(friends1, "Friends of ");
define(friends2, "");
define(you_have, "You have");
define(max500, "Max. picture size is 500 kb.");

//main menu
define(menu_home, "Home");
define(menu_my_panel, "My panel");
define(menu_invite, "Invite friends");
define(menu_vision, "Vision Map");
define(menu_settings, "Settings");
define(menu_logout, "Logout");

//background tile
define(tile_v, "Tile vertically");
define(tile_h, " Tile horizontally");
define(tile_both, "Tile horizontally and vertically");
define(tile_no, "Don't tile");

define(choose_bglib, "Choose from the background library:");
define(hello, "Hello");
define(users_message, "'s message");
define(users_profile, "'s profile");

//sms credits
define(sms_credits_title, "Your SMS credits");
define(you_have, "Currently you have");
define(buying_instruction, "To buy more choose how many sms credits you'd like to buy and press the BUY button.");
define(credit_amount, "Credit anount");
define(choose_sms_plan, "Choose sms plan");
define(credits_for, "credits for");
define(buy, "BUY");
define(credit_price, "1 SMS credit costs");
define(total_price, "so the total price is");
define(your_sms_transactions, "SMS plans you bought");
define(trans_date, "Transaction date");
define(trans_time, "Transaction time");
define(trans_credits, "Credits bought");
define(trans_value, "Value");

define(sms_limit_header, "SMS limit");
define(sms_limit_text, "To save on SMS credits you can set a limit of SMSs that you can send/receive. This limit can be changed or reset anytime.");
define(used_credits_below_limit, "Used credits");
define(your_limit_is, "Your limit is");
define(reset_sms_limit, "Reset");
define(set_sms_limit, "Set SMS limit");
define(set_limit_to, "Set your SMS limit to");
define(set_limit_button, "Set now");


//buy success
define(buy_success_title, "New SMS credits added");
define(buy_success1, "The transaction ended successfully,");
define(buy_success2, "sms credits have been added to your account");

define(buy_success_error_title, "Transaction error");
define(buy_success_error, "This transaction is already finished or does not exist. Please try once more.");

define(buy_failure_title, "New SMS credits added");
define(buy_failure, "Unfortunately, some error occured while processing your transaction.");
?>