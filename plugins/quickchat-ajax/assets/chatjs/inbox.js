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

var single_tick = '<svg fill="#888" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="16" height="15"><path d="M 41.9375 8.625 C 41.273438 8.648438 40.664063 9 40.3125 9.5625 L 21.5 38.34375 L 9.3125 27.8125 C 8.789063 27.269531 8.003906 27.066406 7.28125 27.292969 C 6.5625 27.515625 6.027344 28.125 5.902344 28.867188 C 5.777344 29.613281 6.078125 30.363281 6.6875 30.8125 L 20.625 42.875 C 21.0625 43.246094 21.640625 43.410156 22.207031 43.328125 C 22.777344 43.242188 23.28125 42.917969 23.59375 42.4375 L 43.6875 11.75 C 44.117188 11.121094 44.152344 10.308594 43.78125 9.644531 C 43.410156 8.984375 42.695313 8.589844 41.9375 8.625 Z"/></svg>';
var double_tick = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" id="msg-dblcheck-ack" x="2063" y="2076"><path d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z" fill="#888"></path></svg>';
var color_double_tick = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" id="msg-dblcheck-ack" x="2063" y="2076"><path d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z" fill="#4fc3f7"></path></svg>';

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

    var wchat_eventpl = '<div class="wchat-message '+msgtype+'">'+
                '<p>'+message_content+'</p>'+
                '<span class="qc-metadata">' +
                '<span>'+time+'</span>' +
                '</span>' +
                '</div>';

    if(position == "append"){
        $("#chatbox_"+chatid).append(wchat_eventpl);
    }
    else{
        $("#chatbox_"+chatid).prepend(wchat_eventpl);
    }

}
function msg_oddtpl(chatid,message_content,msgtype,time,position,icontype){

    if(icontype == 0) { // Send
        var icon = single_tick;
        var status = 'sent';
    }else if(icontype == 1){ // Recieved
        var icon = double_tick;
        var status = 'received';
    }else if(icontype == 2){ // Seen
        var icon = color_double_tick;
        var status = 'seen';
    }

    var wchat_oddtpl = '<div class="wchat-message odd '+msgtype+'">' +
        '<p>'+message_content+'</p> ' +
        '<span class="qc-metadata">' +
        '<span>'+time+'</span>' +
        '<span class="msg-status msg-'+status+' ">'+icon+'</span>' +
        '</span>' +
        '</div>';

    if(position == "append"){
        $("#chatbox_"+chatid).append(wchat_oddtpl);
    }
    else{
        $("#chatbox_"+chatid).prepend(wchat_oddtpl);
    }
}

$(document).ready(function(){
    originalTitle = document.title;
    $([window, document]).blur(function(){
        windowFocus = false;
    }).focus(function(){
        windowFocus = true;
        document.title = originalTitle;
    });

    chatfrindList(0);

    $('.search_bg').on('keyup', function(){

        var searchbox = $(this).val();
        var dataString = 'searchword1='+ searchbox;

        if(searchbox == '') {
            $("#display").css('display','block');
            $("#display").html("<div class='cssload-speeding-wheel' style='margin-top: 100px;'></div>");

            chatfrindList(0);
        }
        else {
            chatfrindList(0);
        }return false;
    });
    setTimeout('chatHeartbeat();',chatHeartbeatTime);
});

$(document).on('click', ".start_wchat" ,function(){
    $(this).removeClass('chatboxblink');
    if ($(this).hasClass('active')) {
        return;
    }
    var chatid = $(this).data('chatid');
    var postid = $(this).data('postid');
    var userid = $(this).data('userid');
    var fullname = $(this).data('fullname');
    var userimage = $(this).data('userimage');
    var userstatus = $(this).data('userstatus');

    $('.start_wchat').removeClass('active');
    $(this).addClass('active');

    chatWith(chatid,userid,fullname,userimage,userstatus,postid);
});

function chatWith(chatid,userid,fullname,userimage,userstatus,postid) {

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

    $(".chat-left-aside").toggleClass("open-pnl");
    $(".open-panel i").toggleClass("ti-angle-left");

    if ($("#pane-intro").css('visibility') == 'visible') {
        $("#pane-intro").css({'visibility':'hidden'});
        $(".chat-right-aside").css({'visibility':'visible'});
    }

    if (!$(this).hasClass('active')) {
        if(userstatus == 'online'){
            var lang_status = LANG_ONLINE;
        }else{
            var lang_status = LANG_OFFLINE;
        }

        $('#typing_on').html(lang_status);
        $('#typing_on').attr('data-userid',userid);
        $('.right .top .personName').html(fullname);
        var userImage = '<img src="' + siteurl + 'storage/profile/small_' + userimage + '" class="avatar-image is-loaded bg-theme" width="100%" alt="' + fullname + '">';
        $('.right .top .userimage').html(userImage);
        $('.chat').removeClass('active-chat');
        $("#chatbox_"+chatid).addClass('active-chat');
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

    lastseen(chatid,userid);
    var chatFormTpl =
        '<div class="block-wchat" id="chatForm_'+chatid+'">' +
        '<button class="icon ti-face-smile font-24 btn-emoji" onclick="javascript:chatemoji()" href="#" id="toggle-emoji"></button>' +
        '<div class="input-container">' +
        '<div class="input-emoji">' +
        '<div class="input-placeholder" style="visibility: visible;">'+LANG_TYPE_A_MESSAGE+'</div>' +
        '<div class="input chatboxtextarea" id="chatboxtextarea" name="chattxt" onkeydown=\'javascript:return checkChatBoxInputKey(event,this,"'+chatid+'",'+postid+',"'+userid+'");\' contenteditable="true" spellcheck="true"></div>' +
        '</div>' +
        '</div>' +
        '<button onclick=\'javascript:return clickTosendMessage(event,"#chatboxtextarea","'+chatid+'",'+postid+',"'+userid+'");\' class="btn-icon icon-send fa fa-paper-plane-o font-24 send-container"></button>' +
        '</div>';


    if ($("#chatbox_"+chatid).length > 0) {

        $("#chatFrom").html(chatFormTpl);

        $(".chatboxtextarea").blur(function(){
            chatboxFocus[chatid] = false;
            $(".chatboxtextarea").removeClass('chatboxtextareaselected');
        }).focus(function(){
            chatboxFocus[chatid] = true;
            newMessages[chatid] = false;
            $('#chatbox1_'+chatid+'.chatboxhead').removeClass('chatboxblink');
            $(".chatboxtextarea").addClass('chatboxtextareaselected');
        });

        if (!$("#chatbox_"+chatid).hasClass('active-chat')) {
            $("#chatbox_"+chatid).addClass('active-chat');
        }

        $(".chatboxtextarea").focus();
        scrollDown();
        return;
    }


    $(" <div />" ).attr("id","chatbox_"+chatid)
        .addClass("chat chatboxcontent active-chat")
        .data('userid',userid)
        .data('chatid',chatid)
        .data('postid',postid)
        .data('fullname',fullname)
        .data('userstatus',userstatus)
        .data('userimage',userimage)
        .appendTo($( "#resultchat" ));
    if (minimizeChatBox != 1) {
        $("#chatFrom").html(chatFormTpl);
    }

    get_all_msg(siteurl+plugin_directory+"?page=1&action=get_all_msg&client="+userid+"&postid="+postid);
    smiley_tpl(chatid)
    chatBoxeslength = 0;

    for (x in chatBoxes) {
        if ($("#chatbox_"+chatBoxes[x]).css('display') != 'none') {
            chatBoxeslength++;
        }
    }

    if (chatBoxeslength == 0) {

    } else {
        width = (chatBoxeslength)*(273+7)+300;

    }

    chatBoxes.push(chatid);



    chatboxFocus[chatid] = false;

    $(".chatboxtextarea").blur(function(){
        chatboxFocus[chatid] = false;
        $(".chatboxtextarea").removeClass('chatboxtextareaselected');
    }).focus(function(){
        chatboxFocus[chatid] = true;
        newMessages[chatid] = false;
        $('#chatbox1_'+chatid+'.chatboxhead').removeClass('chatboxblink');
        $(".chatboxtextarea").addClass('chatboxtextareaselected');
    });

    if (minimizeChatBox == 1) {
        $('#chatbox_'+chatid).removeClass('active-chat');
    }
    else{
        $("#chatbox_"+chatid).addClass('active-chat');
    }

    scrollDown();
}

function chatHeartbeat(){

    var itemsfound = 0;

    $.ajax({
        url: siteurl+plugin_directory+"?action=chatheartbeat",
        cache: false,
        dataType: "json",
        type: "POST",
        data: {wchat: 1},
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
                        $('#contact_' + chatid).toggleClass('chatboxblink');

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
                            userstatus: status,
                            minimizeChatBox: 1
                        });
                        return;
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
                        $("#chatbox_"+chatid+" .chatboxcontent").append('<div class="wchat-time">'+message_content+'</div>');
                    }
                    else {
                        msg_eventpl(chatid,message_content,msgtype,time,"append");
                    }

                    itemsfound += 1;
                    if (itemsfound > 0) {
                        scrollDown();
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
                        $("#chatbox_"+chatid).prepend('<input type="hidden" class="pagenum" value="' + item.page + '" /><input type="hidden" class="total-page" value="' + pages + '" />');
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
                            $("#chatbox_"+chatid).prepend('<div class="wchat-time">'+last_time+'</div>');
                        }
                    }
                    last_time = date;

                    if (position == 'even') {
                        msg_eventpl(chatid,message_content,msgtype,time,"prepend");
                    } else
                    {
                        var icontype = 0;
                        if(seen == 1){
                            icontype = 2;
                        }else if(recd == 1){
                            icontype = 1;
                        }
                        msg_oddtpl(chatid,message_content,msgtype,time,"prepend",icontype);
                    }
                }
            });
        }});
}

function checkChatBoxInputKey(event,chatboxtextarea,chatid,postid,userid) {
    if((event.keyCode == 13 && event.shiftKey == 0) )  {
        clickTosendMessage(event,chatboxtextarea,chatid,postid,userid);
        return false;
    }
}

function clickTosendMessage(event,chatboxtextarea,chatid,postid,userid) {
    message = $(chatboxtextarea).html();
    message =  message.replace(/<img(.*?)title="(.*?)"(.*?)>/g,"$2"); // Set imotions
    message = message.replace(/^\s+|\s+$/g,"");


    if (message != '') {
        $.post(siteurl+plugin_directory+"?action=sendchat", {wchat: 1, to: userid, postid: postid, message: message} , function(data){

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

            $(chatboxtextarea).html('');
            $(chatboxtextarea).empty();
            $(chatboxtextarea).focus();
            $(".input-placeholder").css('visibility','visible');

            msg_oddtpl(chatid,message,'text',LANG_JUST_NOW,"append","0");

            $(".target-emoji").css({'display':'none'});
            $('.wchat-filler').css({'height':0+'px'});

            msgid = data;
            scrollDown();
        });
        chatfrindList(0);
    }
    chatHeartbeatTime = minChatHeartbeat;
    chatHeartbeatCount = 1;

    return false;
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

function userProfile(userid){
    $.ajax({
        url: siteurl + plugin_directory+"?action=userProfile&userid="+userid,
        cache: false,
        dataType: "json",
        type: "POST",
        success: function (data) {
            var username = data.username,
                name = data.name,
                email = data.email,
                sex = data.sex,
                about = data.about,
                image = data.image;

            var profile_tpl = '<div class="">\n' +
                '            <div class="user-bg">\n' +
                '                <div class="overlay-box">\n' +
                '                    <div class="user-content"> <a href="#">\n' +
                '                            <img class="thumb-lg img-circle" src="'+siteurl+'storage/profile/small_'+image+'" alt="'+name+'"></a>\n' +
                '                        <h4 class="text-white">'+username+'</h4>\n' +
                '                        <h5 class="text-white">'+email+'</h5>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '            </div>\n' +
                '            <div class="user-btm-box">\n' +
                '                <div class="row text-center m-t-10">\n' +
                '                    <div class="col-md-6 b-r"><strong>'+LANG_NAME+'</strong><p>'+name+'</p></div>\n' +
                '                    <div class="col-md-6"><strong>'+LANG_GENDER+'</strong><p>'+sex+'</p></div>\n' +
                '                </div>\n' +
                '                <hr>\n' +
                '                <div class="row text-center m-t-10">\n' +
                '                    <div class="col-md-12"><strong>'+LANG_ABOUT+'</strong><p>'+about+'</p></div>\n' +
                '                </div>\n' +
                '                <hr>\n' +
                '                <div class="col-md-1 col-sm-1 text-center">&nbsp;</div>\n' +
                '            </div>\n' +
                '        </div>';
            $("#userProfile").html(profile_tpl);
        }
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

function scrollDown(){
    $(".scroll-down").css({'visibility':'hidden'});
    setTimeout(function () {										//Simulate server delay;
        $(".wchat-chat-msgs").scrollTop($(".wchat-chat-msgs")[0].scrollHeight);						// Reset scroll
    }, 100);
}


var listajax = null, last_search = '';
function chatfrindList(limitStart){
    if(listajax){
        listajax.abort();
    }

    listajax = $.ajax({
        url: siteurl + plugin_directory+"?action=chatfrindList",
        cache: false,
        dataType: "json",
        type: "POST",
        data: {
            limitStart: limitStart,
            searchKey: $('.live-search-box').val()
        },
        success: function (result) {
            var tpl = '';
            var contact_count = result.contact_count;
            $('.contact-list').data('contact_count',contact_count);
            if(result.data) {
                $.each(result.data, function (i, item) {
                    if (item.unread_msg != 0) {
                        var unread_count = '<span class="icon-meta unread-count">' + item.unread_msg + '</span>';
                    } else {
                        var unread_count = '';
                    }
                    tpl += '<li class="start_wchat person chatboxhead" id="contact_' + item.chatid + '" ' +
                        'data-chatid="'+item.chatid+'" ' +
                        'data-postid="'+item.postid+'" ' +
                        'data-userid="'+item.userid+'" ' +
                        'data-fullname="'+item.fullname+'" ' +
                        'data-userimage="'+item.userimage+'" ' +
                        'data-userstatus="'+item.userstatus+'">' +
                        '<a href="#">' +
                        '<span class="userimage profile-picture min-profile-picture"><img src="' + siteurl + 'storage/profile/small_' + item.userimage + '" class="avatar-image is-loaded bg-theme" width="100%" alt="' + item.fullname + '"></span>' +
                        '<span>' +
                        '<span class="bname personName">' + item.fullname + '</span>' +
                        '<span class="personStatus"><span class="time ' + item.userstatus + '"> <i class="fa fa-circle" aria-hidden="true"></i></span></span> ' +
                        '<span class="count">' + unread_count + '</span><br>' +
                        '<small class="preview"><i class="fa fa-file-text-o"></i> ' + item.post_title + '</small>' +
                        '</span>' +
                        '</a>' +
                        '</li>';
                });
            }
            if(tpl==''){
                tpl = '<li class="chatboxhead"><a>'+LANG_NO_MSG_FOUND+'</a></li>';
                $(".chat-left-inner .chatonline").html(tpl);
            }else{
                if (limitStart == 0) {
                    $(".chat-left-inner .chatonline").html(tpl);
                    $(".contact-list").scroll( function(){
                        if($(this).data('contact_count') != $(".contact-list li").length) {
                            if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                                var limitStart = $(".contact-list li").length;
                                chatfrindList(limitStart);
                            }
                        }
                    });
                }else{
                    if (last_search != $('.live-search-box').val()) {
                        $(".chat-left-inner .chatonline").html(tpl);
                    }else{
                        $(".chat-left-inner .chatonline").append(tpl);
                    }
                }
            }
            last_search = $('.live-search-box').val();

        },
        error: function( error )
        {

        }
    });


}

$('.wchat-chat-msgs').scroll(function(){
    if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
        $(".scroll-down").css({'visibility':'hidden'});
    }
    if ($('.wchat-chat-msgs ').scrollTop() == 0){

        $(".scroll-down").css({'visibility':'visible'});
        var userid = $('.chat.active-chat').data('userid');
        var postid = $('.chat.active-chat').data('postid');
        var chatid = $('.chat.active-chat').data('chatid');
        if($("#chatbox_"+chatid+" .pagenum:first").val() != $("#chatbox_"+chatid+" .total-page").val()) {

            var pagenum = parseInt($("#chatbox_"+chatid+" .pagenum:first").val()) + 1;

            get_all_msg(siteurl+plugin_directory+"?page="+pagenum+"&action=get_all_msg&client="+userid+"&postid="+postid);

            if(pagenum != $("#chatbox_"+chatid+" .total-page").val()) {
                setTimeout(function () {
                    $('.wchat-chat-msgs').scrollTop(100);
                }, 458);
            }
        }

    }
});


$(document).on('input','.chatboxtextarea', function (e) {
    if($(this).html() == '') {
        $(".input-placeholder").css('visibility','visible');
    }else{
        $(".input-placeholder").css('visibility','hidden');
    }
});

$(document).on('focus','.chatboxtextarea', function (e) {
    var userid = $('.chat.active-chat').data('userid');
    var postid = $('.chat.active-chat').data('postid');
    var chatid = $('.chat.active-chat').data('chatid');
    if (unseenMessage[chatid]){
        $.post(siteurl+plugin_directory+"?action=updateSeenmsg", {userid: userid, postid: postid});
        $('#contact_'+chatid+' .unread-count').remove();
        delete unseenMessage[chatid];
    }
});

$(document).on('click', ".uploadFile", function (e){
    var to_id = $('.chat.active-chat').data('userid');
    var postid = $('.chat.active-chat').data('postid');
    var chatid = $('.chat.active-chat').data('chatid');
    $(function() {

        $('#uploader').plupload({
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

        $('#close_uploadFile').on('click',function(){
            $('#uploader').plupload('destroy');
            $('#uploader').css({'display':'none'});
            //console.clear();
        });
        $('#uploader').on('complete', function() {
            $('#uploader').plupload('destroy');
            $('#uploader').css({'display':'none'});
            //console.clear();
        });
    });
    $('#uploader').css({'display':'block'});
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

                        scrollDown();
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
