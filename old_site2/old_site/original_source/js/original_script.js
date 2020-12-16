function messages(){
    var html = $.ajax({
    url: "ajax/messages.php",
    async: false
    }).responseText;
    $("div#messages_all").empty().append(html);
}

function set_notify_mode(stuff){
    var result = $.ajax({
        type: "GET",
        url: "ajax/set_notify_way.php",
        data: "stuff=" + stuff,
        async: false
    }).responseText;
    $("#ncmr").empty().append(result);
}

function set_msg_notification(stuff){
    var result = $.ajax({
        type: "GET",
        url: "ajax/message_notification.php",
        data: "stuff=" + stuff,
        async: false
    }).responseText;
    $("#mnr").empty().append(result);
}



function load_group_members(group_id)
    {
        var group_members = $.ajax({
        url: "ajax/group_members.php",
        data: 'group_id=' + group_id,
        async: false
        }).responseText;
        $("#group_members").html(group_members);
    }
    
$(document).ready(function(){
    $("#note").keyup(function(){
        $("#additional_note").empty().append($(this).val());
    });

    $("#choose_photo").hide();
    $("#upload").addClass("current");

    $("#upload").click(function(){
        $("#choose_photo").hide();
        $("#upload_photo").fadeIn("slow");
        $(this).addClass("current");
        $("#photo_set").removeClass("current");
        return false;
    });

    $("#photo_set").click(function(){
        $("#upload_photo").hide();
        $("#choose_photo").fadeIn("slow");
        $(this).addClass("current");
        $("#upload").removeClass("current");
        return false;
    });

    $("img.avatar.choose").click(function(){
        $("img.avatar.choose").removeClass("chosen");
        $(this).addClass("chosen");
        $("#chosen_photo").val($(this).attr("alt"));
        return false;
    });

    $("#message").keyup(function(){
        var chars;
        if(typeof message_max_length == "undefined") message_max_length = 140;
//        message_max_length = 140;
        chars = message_max_length - $(this).val().length;
        if (chars == 0 || chars < 0) {
        	$(this).val($(this).val().substr(0, message_max_length-1));
        	$("#chars_left").html(0);
        } else {
        	$("#chars_left").html(chars);
        }
    });
    $("#bio, #interests").keyup(function(){
        var chars;
        chars = 200 - $(this).val().length;
        if(chars == 0 || chars < 0) $(this).val($(this).val().substr(0, 199));
    });
    $("#country").change(function(){
      // alert($("#location").options);
    }); 


//login/register tabs switching
    if($("#show_register_form").val() == 1){
        $("#login_stuff").hide();
        $("#link_register").addClass("current");
    } else {
        $("#register_stuff").hide();
        $("#link_login").addClass("current");
    }

    $("#link_register").click(function(){
        $("#login_stuff").hide();
        $("#register_stuff").fadeIn("slow");
        $(this).addClass("current");
        $("#link_login").removeClass("current");
        return false;
    });
    $("#link_login").click(function(){
        $("#register_stuff").hide();
        $("#login_stuff").fadeIn("slow");
        $(this).addClass("current");
        $("#link_register").removeClass("current");
        return false;
    });

//direct messages
    $("#dm_outbox").hide();
    $("#link_inbox").addClass("current");
    $("#link_inbox").click(function(){
        $("#dm_outbox").hide();
        $("#dm_inbox").fadeIn("slow");
        $(this).addClass("current");
        $("#link_outbox").removeClass("current");
        return false;
    });
    $("#link_outbox").click(function(){
        $("#dm_inbox").hide();
        $("#dm_outbox").fadeIn("slow");
        $(this).addClass("current");
        $("#link_inbox").removeClass("current");
        return false;
    });

//most popular and most recent users
    $("#most_popular_stuff").hide();
    $("#link_recent").addClass("current");
    var recent = $.ajax({
        url: "ajax/most_recent.php",
        async: false
    }).responseText;
    $("#most_recent_stuff").html(recent);

    $("#link_popular").click(function(){
        $("#most_recent_stuff").hide();
        $("#most_popular_stuff").fadeIn("slow");
        $(this).addClass("current");
        $("#link_recent").removeClass("current");
        var popular = $.ajax({
            url: "ajax/most_popular.php",
            async: false
        }).responseText;
        $("#most_popular_stuff").html(popular);
        recent_popular_enable();
        return false;
    });

    $("#link_recent").click(function(){
        $("#most_popular_stuff").hide();
        $("#most_recent_stuff").fadeIn("slow");
        $(this).addClass("current");
        $("#link_popular").removeClass("current");
        var recent = $.ajax({
            url: "ajax/most_recent.php",
            async: false
        }).responseText;
        $("#most_recent_stuff").html(recent);
        recent_popular_enable();
        return false;
    });

// most popular groups
    $("#link_popular_groups").addClass("current");
    var popular_g = $.ajax({
        url: "ajax/most_popular_groups.php",
        async: false
    }).responseText;
    $("#most_popular_groups_stuff").html(popular_g);
    
    /*
    function load_group_members()
    {
        var group_members = $.ajax({
        url: "ajax/group_members.php",
            async: false
        }).responseText;
        $("#group_members").html(group_members);    
    }
    */

    $("#link_popular_groups").click(function(){
        $("#link_popular_groups").addClass("current");
        $("#most_new_groups_stuff").hide();
        $("#most_popular_groups_stuff").fadeIn("slow");
        $(this).addClass("current");
        $("#link_new_groups").removeClass("current");
        var popular_g = $.ajax({
            url: "ajax/most_popular_groups.php",
            async: false
        }).responseText;
        $("#most_popular_groups_stuff").html(popular_g);
//      recent_popular_enable();
        return false;
    });

// most new groups
    $("#link_new_groups").click(function(){
        $("#link_new_groups").addClass("current");
        $("#most_popular_groups_stuff").hide();
        $("#most_new_groups_stuff").fadeIn("slow");
        $(this).addClass("current");
        $("#link_popular_groups").removeClass("current");
        var new_g = $.ajax({
            url: "ajax/most_popular_groups.php?act=new",
            async: false
        }).responseText;
        $("#most_new_groups_stuff").html(new_g);
//      recent_popular_enable();
        return false;
    });


//all group users
        $("#all_group_members").addClass("current");
    var all_g_members = $.ajax({
            url: "ajax/group_members.php?group_id=" + $('#current_group').val(),
            async: false
    }).responseText;
    $("#all_group_members_stuff").html(all_g_members);


        $("#all_group_members").click(function(){
            $("#all_group_members").addClass("current");
        $("#new_group_members_stuff").hide();
        $("#all_group_members_stuff").fadeIn("slow");
        $(this).addClass("current");
        $("#new_group_members").removeClass("current");
        var popular_g = $.ajax({
            url: "ajax/group_members.php?group_id=" + $('#current_group').val(),
            async: false
        }).responseText;
        $("#all_group_members_stuff").html(popular_g);
        return false;
    });

        $("#new_group_members").click(function(){
                $("#new_group_members").addClass("current");
        $("#all_group_members_stuff").hide();
                $("#new_group_members_stuff").fadeIn("slow");
                $(this).addClass("current");
                $("#all_group_members").removeClass("current");
                var new_g = $.ajax({
                        url: "ajax/group_members.php?act=new&group_id=" + $('#current_group').val(),
                        async: false
                }).responseText;
                $("#new_group_members_stuff").html(new_g);
                return false;
    });

    

//latest message tooltip
    var winH = $("body").height();
    if(winH < window.innerHeight) winH = window.innerHeight;
    var winW = window.innerWidth;
    var tooltip_w, tooltip_h;
    function eventMouseX(e, w) {
        if (e.pageX) xval = e.pageX;
        //if(xval + w > winW) xval = winW - w - 30;
        return xval;
    }
    function eventMouseY(e, h) {
        if (e.pageY) yval = e.pageY;
        //if(yval + h > winH) yval = winH - h - 30;
        return yval;
    }
    $("body").mousemove(function(e){
        var mouse_x = eventMouseX(e, 200)-100;
        var mouse_y = eventMouseY(e, tooltip_h)+10;
        $("#last_msg_tooltip").css({
            left: mouse_x + 'px',
            top: mouse_y + 'px'
        });
        tooltip_h = $("#last_msg_tooltip").height;
    });
    $(".show_msg_tooltip").hover(function(){
        var markup = $.ajax({
            type: "GET",
            url: "ajax/user_last_message.php",
            data: "user=" + $(this).attr("alt"),
            async: false
        }).responseText;
        $("body").append("<div id='last_msg_tooltip'><strong>" + $(this).attr("alt") + "</strong>: " + markup + "</div>");
    }, function(){
        $("#last_msg_tooltip").remove();
    });
    $(".show_msg_tooltip").mouseout(function(){
        $("#last_msg_tooltip").remove();
    });

    function recent_popular_enable(){
        $(".show_msg_tooltip").hover(function(){
            var markup = $.ajax({
                type: "GET",
                url: "ajax/user_last_message.php",
                data: "user=" + $(this).attr("alt"),
                async: false
            }).responseText;
            $("body").append("<div id='last_msg_tooltip'><strong>" + $(this).attr("alt") + "</strong>: " + markup + "</div>");
        }, function(){
            $("#last_msg_tooltip").remove();
        });
    }
//favorites ajax stuff

    function enable_add(){
        $(".fav_add").click(function(){
            var result = $.ajax({
                type: "GET",
                url: "ajax/add_favorite.php",
                data: "stuff=" + $(this).attr("alt"),
                async: false
            }).responseText;
            if(result != "OK") alert("Error adding this message to favorites");
            if(result == "OK") {
                $(this).parent().append("<img src='grafika/heart_delete.png' class='fav_del' width='16' height='16' alt='"+ $(this).attr("alt") +"' title='Remove from favorites' />");
                enable_del();
                $(this).remove();
            }
        });
    }

    function enable_del(){
        $(".fav_del").click(function(){
            var result = $.ajax({
                type: "GET",
                url: "ajax/del_favorite.php",
                data: "stuff=" + $(this).attr("alt"),
                async: false
            }).responseText;
            if(result != "OK") alert("Error removing this message from favorites");
            if(result == "OK") {
                $(this).parent().append("<img src='grafika/heart_add.png' class='fav_add' width='16' height='16' alt='"+ $(this).attr("alt") +"' title='Add to favorites' />");
                enable_add();
                $(this).remove();
            }
        });
    }

    $(".fav_add").click(function(){
        var result = $.ajax({
            type: "GET",
            url: "ajax/add_favorite.php",
            data: "stuff=" + $(this).attr("alt"),
            async: false
        }).responseText;
        if(result != "OK") alert("Error adding this message to favorites");
        if(result == "OK") {
            $(this).parent().append("<img src='grafika/heart_delete.png' class='fav_del' width='16' height='16' alt='"+ $(this).attr("alt") +"' title='Remove from favorites' />");
            enable_del();
            $(this).remove();
        }
    });

    $(".fav_del").click(function(){
        var result = $.ajax({
            type: "GET",
            url: "ajax/del_favorite.php",
            data: "stuff=" + $(this).attr("alt"),
            async: false
        }).responseText;
        if(result != "OK") alert("Error removing this message from favorites");
        if(result == "OK") {
            $(this).parent().append("<img src='grafika/heart_add.png' class='fav_add' width='16' height='16' alt='"+ $(this).attr("alt") +"' title='Add to favorites' />");
            enable_add();
            $(this).remove();
        }
    });

//scripts to copy and paste
    $("#paste_code").click(function(){
        this.select();
    });
    $("#js_my_status").click(function(){
        this.select();
    });
    $("#js_my_friends").click(function(){
        this.select();
    });

//notification mode setting
    $("#notify_mode1").click(function(){
        set_notify_mode($(this).val());
    });
    $("#notify_mode2").click(function(){
        set_notify_mode($(this).val());
    });
    $("#notify_mode3").click(function(){
        set_notify_mode($(this).val());
    });
    $("#notify_mode4").click(function(){
        set_notify_mode($(this).val());
    });

//message notification
    $("#notify_direct1").click(function(){
        set_msg_notification($(this).val());
    });
    $("#notify_direct2").click(function(){
        set_msg_notification($(this).val());
    });

//autoselect of api key
    $("#api_key").click(function(){
        this.select();
    });

//autofill of the mail address in the invite page
    $("#check_user").val("@hotmail.com");
    $("#check_type").change(function(){
        $("#check_user").val("@"+ $(this).val() +".com");
    });

//password for account deletion
    $("#del_pass_form").hide();
    $("#del_pass").click(function(){
        $(this).hide();
        $("#del_pass_form").show();
    });


// ==========================
// tabs in user's profile
// ==========================

// opening the right tab
    switch($("#current_tab").val()){
        case "group_messages":
            $("#profile_my_msg").addClass("current");
            $("#profile_my_msg").siblings("a").removeClass("current");
            $("#tab_content").html("<p>Loading...</p>");
            var stuff = $.ajax({
                    type: "GET",
                url: "ajax/messages_group.php",
                data: 'group_id=' + $("#current_group").val(),
                async: false
            }).responseText;
            $("#tab_content").html(stuff).fadeIn("slow");
            //enable_mine();
            //enable_add();
            //enable_del();
            break;
        case "mine":
            $("#profile_my_msg").addClass("current");
            $("#profile_my_msg").siblings("a").removeClass("current");
            $("#tab_content").html("<p>Loading...</p>");
            var stuff = $.ajax({
                type: "GET",
                url: "ajax/messages_mine.php",
                data: "stuff=" + $("#profile_my_msg").attr("rel"),
                async: false
            }).responseText;
            $("#tab_content").html(stuff).fadeIn("slow");
            enable_mine();
            enable_add();
            enable_del();
            break;
        case "with_friends":
            $("#profile_friends").addClass("current");
            $("#profile_friends").siblings("a").removeClass("current");
            $("#tab_content").html("<p>Loading...</p>");
            var stuff = $.ajax({
                type: "GET",
                url: "ajax/messages_with_friends.php",
                data: "stuff=" + $("#profile_friends").attr("rel"),
                async: false
            }).responseText;
            $("#tab_content").html(stuff).fadeIn("slow");
            enable_add();
            enable_del();
            enable_friends();
            break;
        case "replys":
            $("#profile_replys").addClass("current");
            $("#profile_replys").siblings("a").removeClass("current");
            $("#tab_content").html("<p>Loading...</p>");
            var stuff = $.ajax({
                type: "GET",
                url: "ajax/messages_replys.php",
                data: "stuff=" + $("#profile_replys").attr("rel"),
                async: false
            }).responseText;
            $("#tab_content").html(stuff).fadeIn("slow");
            enable_add();
            enable_del();
            enable_replys();
            break;
        case "customize":
            $("#profile_customize").addClass("current");
            $("#profile_customize").siblings("a").removeClass("current");
            var stuff = $.ajax({
                type: "GET",
                url: "ajax/profile_customize.php",
                data: "user=" + $("#current_user").val(),
                async: false
            }).responseText;
            $("#tab_content").html(stuff).fadeIn("slow");
            enable_customize();
            break;
        default:
            $("#profile_my_msg").addClass("current");
            $("#profile_my_msg").siblings("a").removeClass("current");
            $("#tab_content").html("<p>Loading...</p>");
            var stuff = $.ajax({
                type: "GET",
                url: "ajax/messages_mine.php",
                data: "stuff=" + $("#profile_my_msg").attr("rel"),
                async: false
            }).responseText;
            $("#tab_content").html(stuff).fadeIn("slow");
            enable_mine();
            enable_add();
            enable_del();
            break;
    }
// when user clicks on a tab...
    $("#profile_my_msg").click(function(){
        $(this).addClass("current");
        $(this).siblings("a").removeClass("current");
        $("#tab_content").html("<p>Loading...</p>");
        var stuff = $.ajax({
            type: "GET",
            url: "ajax/messages_mine.php",
            data: "stuff=" + $(this).attr("rel"),
            async: false
        }).responseText;
        $("#tab_content").html(stuff).fadeIn("slow");
        enable_mine();
        return false;
    });

    $("#profile_friends").click(function(){
        $(this).addClass("current");
        $(this).siblings("a").removeClass("current");
        $("#tab_content").html("<p>Loading...</p>");
        var stuff = $.ajax({
            type: "GET",
            url: "ajax/messages_with_friends.php",
            data: "stuff=" + $(this).attr("rel"),
            async: false
        }).responseText;
        $("#tab_content").html(stuff).fadeIn("slow");
        enable_add();
        enable_del();
        enable_friends();
        return false;
    });
    $("#profile_replys").click(function(){
        $(this).addClass("current");
        $(this).siblings("a").removeClass("current");
        $("#tab_content").html("<p>Loading...</p>");
        var stuff = $.ajax({
            type: "GET",
            url: "ajax/messages_replys.php",
            data: "stuff=" + $(this).attr("rel"),
            async: false
        }).responseText;
        $("#tab_content").html(stuff).fadeIn("slow");
        enable_add();
        enable_del();
        enable_replys();
        return false;
    });
    $("#profile_customize").click(function(){
        $(this).addClass("current");
        $(this).siblings("a").removeClass("current");
        var stuff = $.ajax({
            type: "GET",
            url: "ajax/profile_customize.php",
            data: "user=" + $(this).attr("rel"),
            async: false
        }).responseText;
        $("#tab_content").html(stuff).fadeIn("slow");
        enable_customize();
        return false;
    });

// pagination enable
    function enable_mine(){
        $("a.pagination.mine").click(function(){
            $("#tab_content").empty().html("<p>Loading...</p>");
            var stuff = $.ajax({
                type: "GET",
                url: "ajax/messages_mine.php",
                data: "stuff=" + $(this).attr("rel"),
                async: false
            }).responseText;
            $("#tab_content").hide().empty().html(stuff).fadeIn("slow");
            enable_mine();
            return false;
        });
    }

    function enable_friends(){
        $("a.pagination.friends").click(function(){
            $("#tab_content").empty().html("<p>Loading...</p>");
            var stuff = $.ajax({
                type: "GET",
                url: "ajax/messages_with_friends.php",
                data: "stuff=" + $(this).attr("rel"),
                async: false
            }).responseText;
            $("#tab_content").html(stuff).fadeIn("slow");
            enable_add();
            enable_del();
            enable_friends();
            return false;
        });
    }

    function enable_replys(){
        $("a.pagination.replys").click(function(){
            $("#tab_content").empty().html("<p>Loading...</p>");
            var stuff = $.ajax({
                type: "GET",
                url: "ajax/messages_replys.php",
                data: "stuff=" + $(this).attr("rel"),
                async: false
            }).responseText;
            $("#tab_content").hide().empty().html(stuff).fadeIn("slow");
            enable_add();
            enable_del();
            enable_replys();
            return false;
        });
    }

    //color picker stuff

    function add_picker(current_element){
        var move_picker = true;
        current_element.parent().prepend("<div id='colorpicker'><div id='wheel'></div><a id='cmClose' href='#'>Apply color</a></div>");
        $("#wheel").farbtastic(current_element);
        $("#colorpicker").show();
        current_element.parent().click(function(e){
            var mouse_x = eventMouseX(e, 200)-100;
            var mouse_y = eventMouseY(e, $("#colorpicker").height())+10;
            if(move_picker == true){
                $("#colorpicker").css({
                    left: mouse_x + 'px',
                    top: mouse_y + 'px'
                });
                move_picker = false;
            }
        });
        $("#cmClose").click(function(){
            if(current_element.val().substr(0, 1) == "#") current_element.val(current_element.val().substr(1));
            current_element.css("background", "#"+current_element.val());
            switch(current_element.attr("id")){
                case "back_color":
                    $("html").css("background-color", "#" + current_element.val());
                    break;
                case "side_fill_color":
                    $(".side_stuff").css("background", "#" + current_element.val());
                    break;
                case "side_border_color":
                    $(".decorative_bar").css("background", "#"+current_element.val());
                    break;
                case "text_color":
                    $("*, h1, h2, h3, h4, #footer, #copyright").not("a").css("color", "#"+current_element.val());
                    break;
                case "link_color":
                    $("a, a:visited, #footer a, #footer a:visited, .side_middle a, .side_middle a:visited, .username, .username:visited").css("color", "#"+current_element.val());
                    $("input.submit").css("background-color", "#"+current_element.val());
                    break;
                case "top_area_color":
                    $("#profile_header").css("background", "#" + current_element.val());
                    break;
            }
            $(this).parent().remove();
            return false;
        });
        return false;
    }

    var current_element;
    $("#colorpicker").hide();
    if($("#current_tab").val() != "customize") enable_customize();

    function bglib_enable(){
        $("#backs").hide();
        $(".back_lib").click(function(){
            var x = false;
            if(x==false){
                $("#backs").show("slow");
                x = true;
            } else {
                $("#backs").fadeOut("slow");
                x = false;
            }
            return false;
        });
        $("a.bglib_link").click(function(){
            $("a.bglib_link").not($(this)).children("img").removeClass("bglib_pic_current").addClass("bglib_pic");
            $(this).children("img").removeClass("bglib_pic").addClass("bglib_pic_current");
            $("#background_name").val($(this).attr("rel"));
            $("#use_image").attr("checked", "checked");
            return false;
        });
    }

    function enable_customize(){
        bglib_enable();
        $("#sticker_color").css("background", "#"+$("#sticker_color").val());
        $("#sticker_color").click(function(){add_picker($(this));});

        $("#back_color").css("background", "#"+$("#back_color").val());
        $("#back_color").click(function(){add_picker($(this));});

        $("#text_color").css("background", "#"+$("#text_color").val());
        $("#text_color").click(function(){add_picker($(this));});

        $("#link_color").css("background", "#"+$("#link_color").val());
        $("#link_color").click(function(){add_picker($(this));});

        $("#bubble_fill_color").css("background", "#"+$("#bubble_fill_color").val());
        $("#bubble_fill_color").click(function(){add_picker($(this));});

        $("#bubble_text_color").css("background", "#"+$("#bubble_text_color").val());
        $("#bubble_text_color").click(function(){add_picker($(this));});

        $("#side_border_color").css("background", "#"+$("#side_border_color").val());
        $("#side_border_color").click(function(){add_picker($(this));});

        $("#side_fill_color").css("background", "#"+$("#side_fill_color").val());
        $("#side_fill_color").click(function(){add_picker($(this));});

        $("#top_area_color").css("background", "#"+$("#top_area_color").val());
        $("#top_area_color").click(function(){add_picker($(this));});
    }

//invitations check all and uncheck all
    $("#check_all").click(function(){
        $("table input").attr("checked", "checked");
        return false;
    });
    $("#uncheck_all").click(function(){
        $("table input").removeAttr("checked");
        return false;
    });

//blocking confirmation
    $(".block_user").click(function(){
        return confirm("Are you sure you want to block this user? He will not be able to read your updates or add you as a friend.");
    });

    $("#search_words").click(function(){
        $(this).val("");
    });
    $("#search_words").blur(function(){
        if($(this).val() == "") $(this).val("Search for friends");
    });

//sms credit paypal form
    if($("#item_name").val() != "") $("#item_name").parent().submit();
});
