$(document).ready(function() {
    $('#lightbox').on('click',function() {
        $('#lightbox').hide();
    });
});


function trigq(event) {
    var image_href = $(event).attr("url");
    if ($('#lightbox').length > 0) {
        $('#content').html('<img src="' + image_href + '" />');
        $('#lightbox').show();
    }
    else { //#lightbox does not exist - create and insert (runs 1st time only)
        //create HTML markup for lightbox window
        var lightbox =
            '<div id="lightbox">' +
            '<p>Click to close</p>' +
            '<div id="content">' + //insert clicked link's href into img src
            '<img src="' + image_href +'" />' +
            '</div>' +
            '</div>';
        //insert lightbox HTML into page
        $('body').append(lightbox);
    }
}

