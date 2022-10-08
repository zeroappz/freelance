/*
 Copyright (c) 2015 Bylancer
 Developed by Dev Katriya
 Date : 10/1/2015
 */

var windowFocus = true;
var chatHeartbeatCount = 0;
var minChatHeartbeat = 5000;
var maxChatHeartbeat = 33000;
var chatHeartbeatTime = minChatHeartbeat;
var originalTitle;
var audioogg = new Audio(siteurl+'plugins/quickchat-ajax/assets/audio/chat.ogg');
var audiomp3 = new Audio(siteurl+'plugins/quickchat-ajax/assets/audio/chat.mp3');

var chatboxFocus = new Array();
var newMessages = new Array();
var newMessagesWin = new Array();
var chatBoxes = new Array();
var unseenMessage = new Array();
if(rtl){
    var chat_margin_from = "left";
}else{
    var chat_margin_from = "right";
}
// default is PNG but you may also use SVG
emojione.imageType = 'png';
emojione.sprites = false;
// default is ignore ASCII smileys like :) but you can easily turn them on
emojione.ascii = true;
// if you want to host the images somewhere else
// you can easily change the default paths
emojione.imagePathPNG = siteurl+'plugins/quickchat-ajax/plugins/smiley/assets/png/';
emojione.imagePathSVG = siteurl+'plugins/quickchat-ajax/plugins/smiley/assets/svg/';

function msg_eventpl(chatid,message_content,msgtype,time,position){

    var zechat_eventpl = '<div class="zechat-message '+msgtype+'">'+message_content+'</div>';

    if(position == "append"){
        $("#chatbox_"+chatid+" .chatboxcontent").append(zechat_eventpl);
        $("#chatbox_"+chatid+" .chatboxcontent").scrollTop($("#chatbox_"+chatid+" .chatboxcontent")[0].scrollHeight);
    }
    else{
        $("#chatbox_"+chatid+" .chatboxcontent").prepend(zechat_eventpl);
    }

}
function msg_oddtpl(chatid,message_content,msgtype,time,position,icon){

    var zechat_oddtpl = '<div class="zechat-message odd '+msgtype+'">'+message_content+'</div>';

    if(position == "append"){
        $("#chatbox_"+chatid+" .chatboxcontent").append(zechat_oddtpl);
        $("#chatbox_"+chatid+" .chatboxcontent").scrollTop($("#chatbox_"+chatid+" .chatboxcontent")[0].scrollHeight);
    }
    else{
        $("#chatbox_"+chatid+" .chatboxcontent").prepend(zechat_oddtpl);
    }
}

$(document).ready(function(){
    originalTitle = document.title;
    startChatSession();

    $([window, document]).blur(function(){
        windowFocus = false;
    }).focus(function(){
        windowFocus = true;
        document.title = originalTitle;
    });

    // Create Contact List
    chatfrindList(0);
});

$(document).on('click', ".start_zechat" ,function(){
    var chatid = $(this).data('chatid');
    var postid = $(this).data('postid');
    var userid = $(this).data('userid');
    var fullname = $(this).data('fullname');
    var userimage = $(this).data('userimage');
    var userstatus = $(this).data('userstatus');

    if(session_uid == userid){
        alert(LANG_ENABLE_CHAT_YOURSELF);
        return;
    }
    createChatBox({
        chatid: chatid,
        postid: postid,
        userid: userid,
        fullname: fullname,
        userimage: userimage,
        userstatus: userstatus
    });
});

function restructureChatBoxes() {
    align = 0;
    for (x in chatBoxes) {
        chatid = chatBoxes[x];

        if ($("#chatbox_"+chatid).css('display') != 'none') {

            if (align == 0) {
                $("#chatbox_"+chatid).css(chat_margin_from, '95px');
            } else {
                width = (align)*(273+7)+90;
                $("#chatbox_"+chatid).css(chat_margin_from, width+'px');
            }
            align++;
        }
    }
}

function createChatBox($options) {

    var $defaults = {
        chatid: null,
        postid: null,
        userid: null,
        fullname: null,
        userimage: null,
        userstatus: 'offline',
        minimizeChatBox: 0
    };
    var $settings = $.extend({}, $defaults, $options);

    var chatid = $settings.chatid;
    var postid = $settings.postid;
    var userid = $settings.userid;
    var fullname = $settings.fullname;
    var userimage = $settings.userimage;
    var userstatus = $settings.userstatus;
    var minimizeChatBox = $settings.minimizeChatBox;

    if ($("#chatbox_"+chatid).length > 0) {
        if ($("#chatbox_"+chatid).css('display') == 'none') {
            $("#chatbox_"+chatid).css('display','block');
            restructureChatBoxes();
        }
        $("#chatbox_"+chatid+" .chatboxtextarea").focus();
        return;
    }

    $("<div />" ).attr("id","chatbox_"+chatid)
        .addClass("chatbox active-chat zechat-hide-under-768px")
        .data('userid',userid)
        .data('chatid',chatid)
        .data('postid',postid)
        .data('fullname',fullname)
        .data('userstatus',userstatus)
        .data('userimage',userimage)
        .html('<div class="chatbox-icon" onclick="javascript:toggleChatBoxGrowth(\''+chatid+'\')" href="#"><div class="contact-floating red"><img class="chat-image img-circle pull-left" src="'+siteurl+'storage/profile/small_'+userimage+'"><small class="unread-msg">2</small><small class="status"></small></div></div>' +
        '<div class="panel personal-chat"> ' +
        '<div class="panel-heading chatboxhead"> ' +
        '<div class="panel-title">' +
        '<img class="chat-image img-circle pull-left" height="36" width="36" src="'+siteurl+'storage/profile/small_'+userimage+'" alt="avatar-image"> ' +
        '<div class="header-elements">'+fullname+'<br> ' +
        '<small class="status '+status+'"><b>'+status+'</b></small> ' +
        '<div class="pull-right options"> ' +
        '<div class="btn-group uploadFile" id="uploadFile"><span><i class="ti-clip attachment"></i></span></div> ' +
        '<div class="btn-group"  onclick="javascript:toggleChatBoxGrowth(\''+chatid+'\')" href="#">' +
        '<span>' +
        '<i class="fa fa-minus-circle"></i>' +
        '</span>' +
        '</div> ' +
        '<div class="btn-group" onclick="javascript:closeChatBox(\''+chatid+'\')" href="#">' +
        '<span><i class="fa fa-times-circle"></i></span>' +
        '</div> ' +
        '</div> ' +
        '</div> ' +
        '</div> ' +
        '</div> ' +
        '<div class="panel-body"><div id="uploader_'+chatid+'" style="display: none;height: 342px;"><p>Your browser does not have Flash, Silverlight or HTML5 support.</p></div>' +
        '<div class="zechat-chat chat-conversation"> ' +
        '<div class="chat_post_title"></div> ' +
        '<div class="conversation-list chatboxcontent zechat-messages" id="resultchat_'+chatid+'"> </div> ' +
        '<footer class="wchat-footer wchat-chat-footer chatboxinput"> ' +
        '<div id="chatFrom"> ' +
        '<div class="block-wchat"> ' +
        '<button class="icon ti-face-smile font-24 btn-emoji" id="toggle-emoji"></button>' +
        '<div class="input-container"> ' +
        '<div class="input-emoji"> ' +
        '<div class="input-placeholder" style="visibility: visible;">'+LANG_TYPE_A_MESSAGE+'</div> ' +
        '<div class="input chatboxtextarea" id="chatboxtextarea" name="chattxt" contenteditable="true" spellcheck="true" onkeydown=\'javascript:return checkChatBoxInputKey(event,this,"'+chatid+'",'+postid+',"'+userid+'");\'></div>' +
        '</div> ' +
        '</div> ' +
        '</div> ' +
        '</div> ' +
        '<div class="wchat-box-items-positioning-container"><div class="wchat-box-items-overlay-container"><div class="target-emoji" style="display: none"><div id="include-smiley-panel"></div></div></div></div>'+
        '</footer> ' +
        '</div> ' +
        '</div>' +
        '</div>')
        .appendTo($( "body" ));
    postdata = get_postdata(chatid,postid);



    get_all_msg(siteurl+plugin_directory+"?page=1&action=get_all_msg&client="+userid+"&postid="+postid);
    lastseen(chatid,userid);
    smiley_tpl(chatid);

    $("#chatbox_"+chatid).css('bottom', '0px');

    chatBoxeslength = 0;
    for (x in chatBoxes) {
        if ($("#chatbox_"+chatBoxes[x]).css('display') != 'none') {
            chatBoxeslength++;
        }
    }

    if (chatBoxeslength == 0) {
        $("#chatbox_"+chatid).css(chat_margin_from, '95px');
    } else {
        width = (chatBoxeslength)*(273+7)+90;
        $("#chatbox_"+chatid).css(chat_margin_from, width+'px');
    }

    chatBoxes.push(chatid);

    if (minimizeChatBox == 1) {
        minimizedChatBoxes = new Array();

        if ($.cookie('chatbox_minimized')) {
            minimizedChatBoxes = $.cookie('chatbox_minimized').split(/\|/);
        }
        minimize = 0;
        for (j=0;j<minimizedChatBoxes.length;j++) {
            if (minimizedChatBoxes[j] == chatid) {
                minimize = 1;
            }
        }

        if (minimize == 1) {
            $('#chatbox_'+chatid+' .chatboxcontent').css('display','none');
            $('#chatbox_'+chatid+' .chatboxinput').css('display','none');
        }
    }

    chatboxFocus[chatid] = false;

    $("#chatbox_"+chatid+" .chatboxtextarea").blur(function(){
        chatboxFocus[chatid] = false;
        $("#chatbox_"+chatid+" .chatboxtextarea").removeClass('chatboxtextareaselected');
    }).focus(function(){
        chatboxFocus[chatid] = true;
        newMessages[chatid] = false;
        $('#chatbox_'+chatid+' .chatboxhead').removeClass('chatboxblink');
        $("#chatbox_"+chatid+" .chatboxtextarea").addClass('chatboxtextareaselected');
    });


    $("#chatbox_"+chatid).show();

    $("#resultchat_"+chatid).scroll( function(){
        if ($("#resultchat_"+chatid).scrollTop() == 0){

            if($("#chatbox_"+chatid+" .pagenum:first").val() != $("#chatbox_"+chatid+" .total-page").val()) {

                var pagenum = parseInt($("#chatbox_"+chatid+" .pagenum:first").val()) + 1;

                var URL = siteurl+plugin_directory+"?page="+pagenum+"&action=get_all_msg&client="+userid+"&postid="+postid;

                get_all_msg(URL);

                if(pagenum != $("#chatbox_"+chatid+" .total-page").val()) {
                    setTimeout(function () {										//Simulate server delay;
                        $("#resultchat_"+chatid).scrollTop(100);							// Reset scroll
                    }, 458);
                }
            }
        }
    });
}

function chatHeartbeat(){

    var itemsfound = 0;

    $.ajax({
        url: siteurl+plugin_directory+"?action=chatheartbeat",
        cache: false,
        dataType: "json",
        success: function(data) {

            $.each(data, function(i,item){
                if (item)	{ // fix strange ie bug
                    itemsfound += 1;

                    var chatid = item.chatid,
                        postid = item.postid,
                        name = item.from_name,
                        from_id = item.from_id,
                        senderimg = item.picname,
                        status = item.status,
                        msgtype = item.message_type,
                        time = item.time,
                        message_content = item.message;

                    if (item.s != 2) {
                        if (windowFocus == false) {
                            document.title = LANG_GOT_MESSAGE;
                        }
                        $('#chatbox_'+chatid+' .chatboxhead').toggleClass('chatboxblink');

                        newMessages[chatid] = true;
                        newMessagesWin[chatid] = true;

                        if (eval(localStorage.sound)) {
                            audiomp3.play();
                            audioogg.play();
                        }
                        unseenMessage[chatid] = chatid;
                    }

                    if ($("#chatbox_"+chatid).length <= 0) {
                        createChatBox({
                            chatid: chatid,
                            postid: postid,
                            userid: from_id,
                            fullname: name,
                            userimage: senderimg,
                            userstatus: status
                        });

                        return;
                    }
                    if ($("#chatbox_"+chatid).css('display') == 'none') {
                        $("#chatbox_"+chatid).css('display','block');
                        restructureChatBoxes();
                    }

                    if (msgtype=="text")
                        message_content = emojione.shortnameToImage(message_content);  // Set imotions
                    else if (msgtype == "file") {

                        var str = message_content;
                        str = str.replace(/&quot;/g, '"');
                        var file_content = JSON.parse(str);
                        message_content = "";

                        if (file_content.file_type == "image") {
                            message_content = "<a url='" + file_content.file_path + "' onclick='trigq(this)'><img src='" + siteurl + "storage/user_files/small" + file_content.file_name + "' class='userfiles'/></a>";
                        }
                        else if (file_content.file_type == "video") {
                            message_content = '<video class="userfiles" controls>' +
                            '<source src="' + siteurl + "storage/user_files/" + file_content.file_name + '" type="video/mp4">' +
                            'Your browser does not support HTML5 video.' +
                            '</video>';
                        }
                        else {
                            message_content = "<a href='" + file_content.file_path + "' class='download-link' download></a>";
                        }

                    }

                    if (item.s == 2) {
                        $("#chatbox_"+chatid+" .chatboxcontent").append('<div class="zechat-time">'+message_content+'</div>');
                    }
                    else {
                        msg_eventpl(chatid,message_content,msgtype,time,"append");
                    }


                    itemsfound += 1;
                    if (itemsfound > 0) {
                        $("#chatbox_"+chatid+" .chatboxcontent").scrollTop($("#chatbox_"+chatid+" .chatboxcontent")[0].scrollHeight);
                    }
                }
            });

            chatHeartbeatCount++;

            if (itemsfound > 0) {
                chatHeartbeatTime = minChatHeartbeat;
                chatHeartbeatCount = 1;
            } else if (chatHeartbeatCount >= 10) {
                chatHeartbeatTime *= 2;
                chatHeartbeatCount = 1;
                if (chatHeartbeatTime > maxChatHeartbeat) {
                    chatHeartbeatTime = maxChatHeartbeat;
                }
            }

        }});
    setTimeout('chatHeartbeat();',chatHeartbeatTime);

}

function get_all_msg(url){
    var last_time = null,
        page = 0;
    $.ajax({
        url: url,
        cache: false,
        dataType: "json",
        success: function(items) {
            $.each(items, function(i,item){
                if (item) { // fix strange ie bug

                    var chatid = item.chatid,
                        pages = item.pages,
                        msgtype = item.mtype,
                        time = item.time,
                        date = item.date,
                        seen = item.seen,
                        recd = item.recd,
                        message_content = item.message,
                        position = item.position;

                    page = item.page;

                    if (item.page != "" && i == 0) {
                        $("#chatbox_" + chatid + " .chatboxcontent").prepend('<input type="hidden" class="pagenum" value="' + item.page + '" /><input type="hidden" class="total-page" value="' + pages + '" />');
                    }

                    if (msgtype == "text") {
                        message_content = emojione.shortnameToImage(message_content);
                    }
                    else if (msgtype=="file") {

                        var str = message_content;
                        str = str.replace(/&quot;/g, '"');
                        var file_content = JSON.parse(str);
                        var message_content="";

                        if (file_content.file_type == "image") {
                            message_content = "<a url='" + file_content.file_path + "' onclick='trigq(this)'><img src='" + siteurl + "storage/user_files/" + file_content.file_name + "' class='userfiles'/></a>";
                        }
                        else if(file_content.file_type == "video") {
                            message_content = '<video class="userfiles" controls>' +
                            '<source src="' + file_content.file_path + '" type="video/mp4">' +
                            'Your browser does not support HTML5 video.' +
                            '</video>';
                        }
                        else{
                            message_content = "<a href='"+file_content.file_path+"' class='download-link' download></a>";
                        }
                    }

                    if(last_time){
                        var start = new Date(date),
                            end   = new Date(last_time),
                            diff  = new Date(end - start),
                            days  = diff/1000/60/60/24;
                        if(days){
                            $("#chatbox_"+chatid+" .chatboxcontent").prepend('<div class="zechat-time">'+last_time+'</div>');
                        }
                    }
                    last_time = date;

                    if (item.s == 2) {
                        $("#chatbox_"+chatid+" .chatboxcontent").prepend('<div class="zechat-time">'+message_content+'</div>');
                    } else {
                        if (position == 'even') {
                            msg_eventpl(chatid,message_content,msgtype,time,"prepend");
                        } else {
                            msg_oddtpl(chatid,message_content,msgtype,time,"prepend","fa-check");
                        }
                    }

                    $("#chatbox_"+chatid+" .chatboxcontent").scrollTop($("#chatbox_"+chatid+" .chatboxcontent")[0].scrollHeight);
                }
            });
        }});
}

function checkChatBoxInputKey(event,chatboxtextarea,chatid,postid,userid) {

    if(event.keyCode == 13 && event.shiftKey == 0)  {

        message = $(chatboxtextarea).html();
        message =  message.replace(/<img(.*?)title="(.*?)"(.*?)>/g,"$2"); // Set imotions
        message = message.replace(/^\s+|\s+$/g,"");

        $(chatboxtextarea).html('');
        $(chatboxtextarea).empty();
        $(chatboxtextarea).focus();
        $("#chatbox_"+chatid+" .input-placeholder").css('visibility','visible');
        if (message != '') {
            $.post(siteurl+plugin_directory+"?action=sendchat", {to: userid, postid: postid, message: message} , function(data){

                message = message.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\"/g,"&quot;");
                message = message.replace(/\n/g, "<br />");
                message = emojione.shortnameToImage(message);

                var $con = message;
                var $words = $con.split(' ');
                for (i in $words) {
                    if ($words[i].indexOf('http://') == 0 || $words[i].indexOf('https://') == 0) {
                        $words[i] = '<a href="' + $words[i] + '">' + $words[i] + '</a>';
                    }
                    else if ($words[i].indexOf('www') == 0 ) {
                        $words[i] = '<a href="' + $words[i] + '">' + $words[i] + '</a>';
                    }
                }
                message = $words.join(' ');

                $('#chatbox_'+chatid+' .target-emoji').css({'display':'none'});
                $('#chatbox_'+chatid+' .btn-emoji').removeClass('ti-arrow-circle-down').addClass('ti-face-smile');

                msg_oddtpl(chatid,message,'text',LANG_JUST_NOW,"append","fa-clock-o");

                $("#chatbox_"+chatid+" .chatboxcontent").scrollTop($("#chatbox_"+chatid+" .chatboxcontent")[0].scrollHeight);
            });
        }
        chatHeartbeatTime = minChatHeartbeat;
        chatHeartbeatCount = 1;

        return false;
    }
}

function get_postdata(chatid,postid){
    var postdata;
    $.ajax({
        url: siteurl + plugin_directory+"?action=get_postdata&postid="+postid,
        cache: false,
        dataType: "json",
        type: "POST",
        success: function (data) {
            postdata = data;
            var post_title = postdata['post_title'];
            var post_link = postdata['post_link'];
            $("#chatbox_"+chatid+" .chat_post_title").html('<a href="'+post_link+'" title="'+post_title+'">'+post_title+'</a>');
        },
        error: function( error )
        {

        }
    });
}

function lastseen(chatid,userid){
    $.ajax({
        url: siteurl + plugin_directory+"?action=lastseen&userid="+userid,
        cache: false,
        type: "POST",
        success: function (data) {
            if(data == "online"){
                $("#chatbox_"+chatid+" .panel-heading .status").removeClass("offline");
            } else{
                $("#chatbox_"+chatid+" .panel-heading .status").removeClass("online");
            }
            $("#chatbox_"+chatid+" .panel-heading .status").addClass(data).html(data);
        },
        error: function( error )
        {

        }
    });
}

function startChatSession(){
    $.ajax({
        url: siteurl+plugin_directory+"?action=startchatsession",
        cache: false,
        dataType: "json",
        success: function(data) {

            $.each(data, function(i,item){
                if (item)	{ // fix strange ie bug

                    var chatid = item.chatid,
                        postid = item.postid,
                        userid = item.userid,
                        fullname = item.fullname,
                        userimage = item.picname,
                        userstatus = item.userstatus;

                    if ($("#chatbox_"+chatid).length <= 0) {
                        createChatBox({
                            chatid: chatid,
                            postid: postid,
                            userid: userid,
                            fullname: fullname,
                            userimage: userimage,
                            userstatus: userstatus,
                            minimizeChatBox: 1
                        });
                    }
                }
            });

            for (i=0;i<chatBoxes.length;i++) {
                chatid = chatBoxes[i];
                $("#chatbox_"+chatid+" .chatboxcontent").scrollTop($("#chatbox_"+chatid+" .chatboxcontent")[0].scrollHeight);
                setTimeout('$("#chatbox_"+chatid+" .chatboxcontent").scrollTop($("#chatbox_"+chatid+" .chatboxcontent")[0].scrollHeight);', 100); // yet another strange ie bug

            }

            setTimeout('chatHeartbeat();',chatHeartbeatTime);
        }});
}

function closeChatBox(chatid) {
    $('#chatbox_'+chatid).css('display','none');
    restructureChatBoxes();

    $.post(siteurl+plugin_directory+"?action=closechat", { chatbox: chatid} , function(data){
    });

}

function toggleChatBoxGrowth(chatid) {

    if ($('#chatbox_'+chatid+' .chatboxcontent').css('display') == 'none') {
        var minimizedChatBoxes = new Array();

        if ($.cookie('chatbox_minimized')) {
            minimizedChatBoxes = $.cookie('chatbox_minimized').split(/\|/);
        }

        var newCookie = '';

        for (i=0;i<minimizedChatBoxes.length;i++) {
            if (minimizedChatBoxes[i] != chatid) {
                newCookie += chatid+'|';
            }
        }

        newCookie = newCookie.slice(0, -1)


        $.cookie('chatbox_minimized', newCookie);
        $('#chatbox_'+chatid+' .chatboxcontent').css('display','block');
        $('#chatbox_'+chatid+' .chatboxinput').css('display','block');
        $("#chatbox_"+chatid+" .chatboxcontent").scrollTop($("#chatbox_"+chatid+" .chatboxcontent")[0].scrollHeight);
    } else {
        var newCookie = chatid;

        if ($.cookie('chatbox_minimized')) {
            newCookie += '|'+$.cookie('chatbox_minimized');
        }


        $.cookie('chatbox_minimized',newCookie);
        $('#chatbox_'+chatid+' .chatboxcontent').css('display','none');
        $('#chatbox_'+chatid+' .chatboxinput').css('display','none');
    }

}

function chatfrindList(limitStart){
    $.ajax({
        url: siteurl + plugin_directory+"?action=chatfrindList",
        cache: false,
        dataType: "json",
        type: "POST",
        data: {
            limitStart: limitStart
        },
        success: function (result) {
            var tpl = '';
            var contact_count = result.contact_count;
            $.each(result.data, function(i,item){
                if(item.unread_msg != 0){
                    var unread_count = '<div class="zechat-badge">' +item.unread_msg + '</div>';
                }else{
                    var unread_count = '';
                }
                tpl += '<div class="zechat-contact-wrap start_zechat" id="contact_'+item.chatid+'" ' +
                    'data-chatid="'+item.chatid+'" ' +
                    'data-postid="'+item.postid+'" ' +
                    'data-userid="'+item.userid+'" ' +
                    'data-fullname="'+item.fullname+'" ' +
                    'data-userimage="'+item.userimage+'" ' +
                    'data-userstatus="'+item.userstatus+'">' +
                    '<div class="zechat-contact">' +
                    '<div class="zechat-pic" style="background-image: url('+siteurl+'storage/profile/small_'+item.userimage+')";></div>' +
                    unread_count +
                    '<div class="zechat-name">'+item.fullname+'</div>' +
                    '<div class="zechat-message"><i class="fa fa-file-text-o"></i> ' +
                    item.post_title +
                    '</div>' +
                    '</div>' +
                    '</div>';
            });

            if(tpl==''){
                tpl = '<div class="zechat-contact-wrap"><div class="zechat-contact">'+LANG_NO_MSG_FOUND+'</div></div>';
            }
            if ($(".zechat-contacts").length == 0) {
                $("<div />").addClass("zechat-contacts zechat-hide-under-768px")
                    .html('<i class="fa fa-bars"></i>' +
                        '<h2>' + LANG_CHATS + '</h2>' +
                        '<div class="zechat-contact-list" data-contact_count="'+contact_count+'">' + tpl + '</div>'
                    )
                    .appendTo($("body"));

                $(".zechat-contact-list").scroll( function(){
                    if($(this).data('contact_count') != $(".zechat-contact-list .zechat-contact-wrap").length) {
                        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                            var limitStart = $(".zechat-contact-list .zechat-contact-wrap").length;
                            chatfrindList(limitStart);
                        }
                    }
                });
            }else{
                $(".zechat-contact-list").append(tpl);
            }
        },
        error: function( error )
        {

        }
    });


}

$(document).on('focus','.chatboxtextarea', function (e) {
    var parent = $(this).parents('.chatbox');
    var chatid = parent.data('chatid');
    var postid = parent.data('postid');
    var userid = parent.data('userid');
    if (unseenMessage[chatid]){
        $.post(siteurl+plugin_directory+"?action=updateSeenmsg", {userid: userid, postid: postid});
        $('#contact_'+chatid+' .zechat-badge').remove();
        delete unseenMessage[chatid];
    }
});

$(document).on('input','.chatboxtextarea', function (e) {
    var parent = $(this).parents('.chatbox');
    var chatid = parent.data('chatid');

    if($(this).html() == '') {
        $("#chatbox_"+chatid+" .input-placeholder").css('visibility','visible');
    }else{
        $("#chatbox_"+chatid+" .input-placeholder").css('visibility','hidden');
    }
});

$(document).on('click', ".uploadFile", function (e){
    var parent = $(this).parents('.chatbox');
    var chatid = parent.data('chatid');
    var postid = parent.data('postid');
    var to_id = parent.data('userid');
    $(function() {

        $('#uploader_'+chatid).plupload({
            // General settings
            runtimes : 'html5,flash,silverlight,html4',
            url: siteurl+"php/upload-chat-file.php?to_id="+to_id+"&chatid="+chatid+"&post_id="+postid,

            // User can upload no more then 20 files in one go (sets multiple_queues to false)
            max_file_count: 5,

            chunk_size: '1mb',

            // Resize images on clientside if we can
            resize : {
                width : 200,
                height : 200,
                quality : 90,
                crop: false // crop to exact dimensions
            },

            filters : {
                // Maximum file size
                max_file_size : '100mb',
                // Specify what files to browse for
                mime_types: [
                    {title : "Image files", extensions : "jpg,gif,png"},
                    {title : "Zip files", extensions : "zip,rar,mp3,mp4,txt,doc,docx,pdf,ppt,psd,xls,xlsx,xml"}
                ]
            },

            // Rename files by clicking on their titles
            rename: false,

            // Sort files
            sortable: true,

            // Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
            dragdrop: true,

            // Views to activate
            views: {
                list: true,
                thumbs: true, // Show thumbs
                active: 'thumbs'
            },

            // Flash settings
            flash_swf_url : '../plugins/uploader/Moxie.swf',

            // Silverlight settings
            silverlight_xap_url : '../plugins/uploader/Moxie.xap',

            init: {

                FileUploaded: function(up, file, info) {
                    // Called when file has finished uploading
                    plupload_log('[FileUploaded] File:', file, "Info:", info);
                },
                Destroy: function(up) {
                    // Called when uploader is destroyed
                    //log('[Destroy] ');
                },
                Error: function(up, err) {
                    document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
                }
            }

        });

        $('#uploader_'+chatid).on('click', '#close_uploadFile', function(e) {
            $('#uploader_'+chatid).plupload('destroy');
            $('#uploader_'+chatid).css({'display':'none'});
            $('#chatbox_'+chatid+' .chat-conversation').css({'display':'block'});
        });
        $('#uploader_'+chatid).on('complete', function() {
            $('#uploader_'+chatid).plupload('destroy');
            $('#uploader_'+chatid).css({'display':'none'});
            $('#chatbox_'+chatid+' .chat-conversation').css({'display':'block'});
        });
    });
    $('#uploader_'+chatid).css({'display':'block'});
    $('#chatbox_'+chatid+' .chat-conversation').css({'display':'none'});
});
function plupload_log() {
    plupload.each(arguments, function(arg) {
        if (typeof(arg) != "string") {
            plupload.each(arg, function(value, key) {
                if (typeof(value) != "function") {
                    if(key == "response"){
                        var json_var = JSON.parse(value);
                        var msg_id = json_var.id;
                        var chatid = json_var.chatid;
                        var file_name = json_var.file_name;
                        var file_path = json_var.file_path;
                        var file_type = json_var.file_type;

                        if (file_type == "image"){
                            var message_content = "<a url='"+file_path+"' onclick='trigq(this)'><img src='"+file_path+"' class='userfiles'/></a>";
                        }
                        else if(file_type == "video"){
                            message_content = '<video class="userfiles" controls>' +
                                '<source src="'+file_path+'" type="video/mp4">'+
                                'Your browser does not support HTML5 video.'+
                                '</video>';
                        }
                        else{
                            message_content = "<a href='"+file_path+"' class='download-link' download></a>";
                        }

                        msg_oddtpl(chatid,message_content,'file',LANG_JUST_NOW,"append",0);

                        setTimeout(function () {
                            $("#chatbox_"+chatid+" .chatboxcontent").scrollTop($("#chatbox_"+chatid+" .chatboxcontent")[0].scrollHeight);
                        }, 100);


                    }
                }
            });
        } else {

        }
    });
}

jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};