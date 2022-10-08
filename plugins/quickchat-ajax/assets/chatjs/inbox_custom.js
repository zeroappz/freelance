$(document).ready(function() {

    $(".e1").on('click',function(event){
        var prevMsg = $('#chatFrom .chatboxtextarea').html();
        var shortname = $(this).data('shortname');
        var emoji_tpl = emojione.toImage(shortname);
        $(".input-placeholder").css('visibility','hidden');
        $('.chatboxtextarea').html(prevMsg+' '+emoji_tpl);
        $('.chatboxtextarea').focus();
    });
    $(".chat-head .personName").on('click',function(){
        var personName = $(this).text();
    });

    $(".header-close").on('click',function(){
        $('#wchat .wchat').removeClass('three');
        $('#wchat .wchat').addClass('two');
        $('.wchat-three').css({'display':'none'});
    });

    $(".scroll-down").on('click',function(){
        scrollDown();
    });

    $("#mute-sound").on('click',function(){
        if(eval(localStorage.sound)){
            localStorage.sound = false;
            $("#mute-sound").html('<i class="icon icon-volume-off"></i>');
        }
        else{
            localStorage.sound = true;
            $("#mute-sound").html('<i class="icon icon-volume-2"></i>');
            audiomp3.play();
            audioogg.play();
        }
    });
    $("#MobileChromeplaysound").on('click',function(){
        if(eval(localStorage.sound)){
            audiomp3.play();
            audioogg.play();
        }
    });
    if(eval(localStorage.sound)){
        $("#mute-sound").html('<i class="icon icon-volume-2"></i>');
    }
    else{
        $("#mute-sound").html('<i class="icon icon-volume-off"></i>');
    }
});

$("#launchProfile").on('click',function(){
    ShowProfile();
});
function ShowProfile() {
    var userid = $('.chat.active-chat').data('userid');
    $('#wchat .wchat').removeClass('two');
    $('#wchat .wchat').addClass('three');
    $('.wchat-three').slideDown(50);
    $('.wchat-three').toggleClass("shw-rside");

    $("#userProfile").html('<div class="preloader"><div class="cssload-speeding-wheel"></div></div>');
    userProfile(userid);
}

function chatemoji() {
    $(".target-emoji").slideToggle( 'fast', function(){

        if ($(".target-emoji").css('display') == 'block') {
            $('.wchat-filler').css({'height':225+'px'});
            $('.btn-emoji').removeClass('ti-face-smile').addClass('ti-arrow-circle-down');
        } else {
            $('.wchat-filler').css({'height':0+'px'});
            $('.btn-emoji').removeClass('ti-arrow-circle-down').addClass('ti-face-smile');
        }
    });
    var heit = $('#resultchat').css('max-height');
}

/*Get get on scroll*/
$(window).bind("load", function() {
    $('.chatboxtextarea').on('focus',function(e) {
        $(".target-emoji").css({'display':'none'});
        $('.wchat-filler').css({'height':0+'px'});
    });
});

//Open-Close-right sidebar
$(".right-side-toggle").on('click',function () {
    $(".right-sidebar").slideDown(50);
    $(".right-sidebar").toggleClass("shw-rside");

});

// This is for resize window
$(function () {
    $(window).bind("load resize", function () {
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 1170) {
            $('body').addClass('content-wrapper');
            $(".open-close i").removeClass('icon-arrow-left-circle');
            $(".sidebar-nav, .slimScrollDiv").css("overflow-x", "visible").parent().css("overflow", "visible");
            $(".logo span").hide();
        } else {
            $('body').removeClass('content-wrapper');
            $(".open-close i").addClass('icon-arrow-left-circle');
            $(".logo span").show();
        }
    });
});


// This is for click on open close button
// Sidebar open close
$(".open-close").on('click', function () {
    if ($("body").hasClass("content-wrapper")) {
        $("body").trigger("resize");
        $(".sidebar-nav, .slimScrollDiv").css("overflow", "hidden").parent().css("overflow", "visible");
        $("body").removeClass("content-wrapper");
        $(".open-close i").addClass("icon-arrow-left-circle");
        $(".logo span").show();

    } else {
        $("body").trigger("resize");
        $(".sidebar-nav, .slimScrollDiv").css("overflow-x", "visible").parent().css("overflow", "visible");

        $("body").addClass("content-wrapper");
        $(".open-close i").removeClass("icon-arrow-left-circle");
        $(".logo span").hide();
    }

});

$(".open-panel").on('click',function () {
    $(".chat-left-aside").toggleClass("open-pnl");
    $(".open-panel i").toggleClass("ti-angle-left");
});
$(".chatboxhead").on('click',function () {
    $(".chat-left-aside").toggleClass("open-pnl");
    $(".open-panel i").toggleClass("ti-angle-left");
});


var specialCharacters = ["–", "’"],
    normalCharacters = ["-", "'"]

/*
 * Private Methods
 */

// Replaces invalid characters with safe versions
function replaceInvalidCharacters (string) {
    var regEx;

    // Loop the array of special and normal characters
    for (var x = 0; x < specialCharacters.length; x++) {
        // Create a regular expression to do global replace
        regEx = new RegExp(specialCharacters[x], 'g');

        // Do the replace
        string = string.replace(regEx, normalCharacters[x]);
    }

    return string;
}

$(document).on("paste", ".chatboxtextarea", function(event){
    // We got this
    event.preventDefault();

    // Get the plain text
    var clipboardData = event.clipboardData || window.clipboardData || event.originalEvent.clipboardData;
    var plainText = clipboardData.getData('text/plain');

    // Clean up the text
    var cleanText = replaceInvalidCharacters(plainText);

    // Tell the browser to insert the text
    document.execCommand('inserttext', false, cleanText);

    // Backup to the event.preventDefault()
    return false;
});
