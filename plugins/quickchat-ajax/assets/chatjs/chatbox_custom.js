var searchSel = null;

function pasteHtmlAtCaret(html, selectPastedContent) {
    var sel, range;
    if (window.getSelection) {
        // IE9 and non-IE
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();

            // Range.createContextualFragment() would be useful here but is
            // only relatively recently standardized and is not supported in
            // some browsers (IE9, for one)
            var el = document.createElement("div");
            el.innerHTML = html;
            var frag = document.createDocumentFragment(), node, lastNode;
            while ( (node = el.firstChild) ) {
                lastNode = frag.appendChild(node);
            }
            var firstNode = frag.firstChild;
            range.insertNode(frag);

            // Preserve the selection
            if (lastNode) {
                range = range.cloneRange();
                range.setStartAfter(lastNode);
                if (selectPastedContent) {
                    range.setStartBefore(firstNode);
                } else {
                    range.collapse(true);
                }
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    } else if ( (sel = document.selection) && sel.type != "Control") {
        // IE < 9
        var originalRange = sel.createRange();
        originalRange.collapse(true);
        sel.createRange().pasteHTML(html);
        if (selectPastedContent) {
            range = sel.createRange();
            range.setEndPoint("StartToStart", originalRange);
            range.select();
        }
    }
}

function createRange(node, chars, range) {
    if (!range) {
        range = document.createRange()
        range.selectNode(node);
        range.setStart(node, 0);
    }

    if (chars.count === 0) {
        range.setEnd(node, chars.count);
    } else if (node && chars.count >0) {
        if (node.nodeType === Node.TEXT_NODE) {
            if (node.textContent.length < chars.count) {
                chars.count -= node.textContent.length;
            } else {
                range.setEnd(node, chars.count);
                chars.count = 0;
            }
        } else {
            for (var lp = 0; lp < node.childNodes.length; lp++) {
                range = createRange(node.childNodes[lp], chars, range);

                if (chars.count === 0) {
                    break;
                }
            }
        }
    }

    return range;
}

function setCurrentCursorPosition(el,chars) {
    if (chars >= 0) {
        var selection = window.getSelection();

        range = createRange(el, { count: chars });

        if (range) {
            range.collapse(false);
            selection.removeAllRanges();
            selection.addRange(range);
        }
    }
}

function getCaretCharacterOffsetWithin(node) {

    var range = window.getSelection().getRangeAt(0);
    var treeWalker = document.createTreeWalker(
        node,
        NodeFilter.ELEMENT_NODE,
        function(node) {
            var nodeRange = document.createRange();
            nodeRange.selectNodeContents(node);
            return nodeRange.compareBoundaryPoints(Range.END_TO_END, range) < 1 ?
                NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
        },
        false
    );

    var charCount = 0, lastNodeLength = 0;

    if (range.startContainer.nodeType == 3) {
        charCount += range.startOffset;
    }

    while (treeWalker.nextNode()) {
        charCount += lastNodeLength;
        lastNodeLength = 0;

        if(range.startContainer != treeWalker.currentNode) {
            if(treeWalker.currentNode instanceof Text) {
                lastNodeLength += treeWalker.currentNode.length;
            } else if(treeWalker.currentNode instanceof HTMLBRElement ||
                treeWalker.currentNode instanceof HTMLImageElement /* ||
                      treeWalker.currentNode instanceof HTMLDivElement*/)
            {
                lastNodeLength++;
            }
        }
    }
    return charCount + lastNodeLength;
}



function emojify(elArg){
//  i lost url
    function placeCaretAtEnd(el, moveTo) {
        el.focus()
        if (typeof window.getSelection != "undefined"
            && typeof document.createRange != "undefined") {
            var range = document.createRange()
            // range.selectNodeContents(el)
            range.setStartBefore(moveTo) //this does the trick
            range.collapse(false)
            var sel = window.getSelection()
            sel.removeAllRanges()
            sel.addRange(range)
        }
    }
//

    $.each(elArg.childNodes, function (i,e){
        var node = e
        var nodeIndex = i

        var ascii = new RegExp(emojione.regAscii)
        var short = new RegExp(emojione.regShortNames)

        var combination = new RegExp(ascii.source+'|'+short.source , 'g')

        var matches = node.textContent.match(combination)
        if(matches){
            var e =  matches[0]
            var start = node.textContent.indexOf(e)
            var end = node.textContent.indexOf(e)+e.length

            var stringToConvert = node.textContent.slice(start, end)

            var temp_container = document.createElement('div')
            temp_container.innerHTML = emojione.toImage(stringToConvert)

            var emo = $(temp_container).find('.emojione').get(0) || temp_container.firstChild


            var beforeText = document.createTextNode(node.textContent.slice(0, start))
            var afterText = document.createTextNode(node.textContent.slice(end))

            node.parentNode.insertBefore(beforeText, node)
            node.parentNode.insertBefore(afterText, node.nextSibling)
            node.parentNode.replaceChild(emo, node)
            placeCaretAtEnd(elArg, emo.nextSibling)
            emojify(elArg) // check for other
        }
    })
}

$(document).on('click', "#toggle-emoji", function (e){
    // Keep ads item click from being executed.
    e.stopPropagation();
    // Prevent navigating to '#'.
    e.preventDefault();
    var $item = $(this).parents('.chatbox');
    var chat_id = $item.data('chatid');

    $("#chatbox_"+chat_id+" .target-emoji").slideToggle( 'fast', function(){

        if ($("#chatbox_"+chat_id+" .target-emoji").css('display') == 'block') {
            $('#chatbox_'+chat_id+' .btn-emoji').removeClass('ti-face-smile').addClass('ti-arrow-circle-down');
        } else {
            $('#chatbox_'+chat_id+' .btn-emoji').removeClass('ti-arrow-circle-down').addClass('ti-face-smile');
        }
    });
    var heit = $('#resultchat').css('max-height');
    $("#chatbox_"+chat_id+" .chatboxtextarea").focus();
});


$(document).on('click', ".e1", function (e){
    // Keep ads item click from being executed.
    e.stopPropagation();
    // Prevent navigating to '#'.
    e.preventDefault();
    var $item = $(this).parents('.chatbox');
    var chat_id = $item.data('chatid');
    var emoji_id = $(this).attr('id');

    var prevMsg = $("#chatbox_"+chat_id+" .chatboxtextarea").html();
    var shortname = $(this).data('shortname');
    var emoji_tpl = emojione.toImage(shortname);

    $("#chatbox_"+chat_id+" .chatboxtextarea").html(prevMsg+' '+emoji_tpl);
    $("#chatbox_"+chat_id+" .chatboxtextarea").focus();
    $("#chatbox_"+chat_id+" .input-placeholder").css('visibility','hidden');
});

function typePlace() {
    if(!$('#textarea').html() == '')
    {
        $(".input-placeholder").css({'visibility':'hidden'});
    }
    else{
        $(".input-placeholder").css({'visibility':'visible'});
    }
}

$(document).ready(function() {
    $("#minmaxchatlist").click(function(){
        if(eval(localStorage.chatlist)){
            localStorage.chatlist = false;
            $("#showhidechatlist").css('display','none');
        }
        else{
            localStorage.chatlist = true;
            $("#showhidechatlist").css('display','block');
        }
    });

    $("#mute-sound").click(function(){
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

    if(eval(localStorage.chatlist)){
        $("#showhidechatlist").css('display','block');
    }
    else{
        $("#showhidechatlist").css('display','none');
    }

    if(eval(localStorage.sound)){
        $("#mute-sound").html('<i class="icon icon-volume-2"></i>');
    }
    else{
        $("#mute-sound").html('<i class="icon icon-volume-off"></i>');
    }
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

