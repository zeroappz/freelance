jQuery(function ($) {
    // user login
    $("#login-form").on('submit',function (e) {
        e.preventDefault();
        $("#login-status").slideUp();
        $('#login-button').addClass('button-progress').prop('disabled', true);
        var form_data = {
            action: 'ajaxlogin',
            username: $("#username").val(),
            password: $("#password").val(),
            is_ajax: 1
        };
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: form_data,
            success: function (response) {
                $('#login-button').removeClass('button-progress').prop('disabled', false);
                if (response == "success") {
                    $("#login-status").addClass('success').removeClass('error').html('<p>'+LANG_LOGGED_IN_SUCCESS+'</p>').slideDown();
                    location.reload();
                }
                else {
                    $("#login-status").removeClass('success').addClass('error').html('<p>'+response+'</p>').slideDown();
                }
            }
        });
        return false;
    });

    // set button fav
    $('.set-item-fav').on('click', function (e) {
        e.stopPropagation();
        e.preventDefault();

        var adId = $(this).data('item-id');
        var userId = $(this).data('userid');
        var action = $(this).data('action');
        var $item = $(this).closest('.fav-listing');
        var $this = $(this);

        if (userId == 0) {
            //window.location.href = loginurl;
            $('[href="#sign-in-dialog"]').trigger('click');
            return;
        }
        $this.addClass('button-loader');
        var data = {action: action, id: adId, userId: userId};
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: data,
            success: function (result) {
                if (result == 1) {
                    if (action == 'removeFavAd') {
                        $item.remove();
                        var val = $('.fav-ad-count').text();
                        var favcount = val - 1;
                        $('.fav-ad-count').html(favcount);
                    }
                    else {
                        $this.removeClass('button-loader').addClass('added');
                    }

                }
                else if (result == 2) {
                    $this.removeClass('button-loader').removeClass('added');
                }
                else {
                    //alert("else");
                }
            }
        });
    });

    // set user fav
    $('.set-user-fav').on('click', function (e) {
        e.stopPropagation();
        e.preventDefault();

        var adId = $(this).data('favuser-id');
        var userId = $(this).data('userid');
        var action = $(this).data('action');
        var $item = $(this).closest('.fav-listing');
        var $this = $(this);

        if (userId == 0) {
            //window.location.href = loginurl;
            $('[href="#sign-in-dialog"]').trigger('click');
            return;
        }
        $this.addClass('button-loader');
        var data = {action: action, id: adId, userId: userId};
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: data,
            success: function (result) {
                if (result == 1) {
                    if (action == 'removeFavAd') {
                        $item.remove();
                        var val = $('.fav-user-count').text();
                        var favcount = val - 1;
                        $('.fav-user-count').html(favcount);
                    }
                    else {
                        $this.removeClass('button-loader').addClass('added');
                    }
                }
                else if (result == 2) {
                    $this.removeClass('button-loader').removeClass('added');
                    if ($item != undefined) {
                        $item.remove();
                        var val = $('.fav-user-count').text();
                        var favcount = val - 1;
                        $('.fav-user-count').html(favcount);
                    }
                }
                else {
                    //alert("else");
                }
            }
        });
    });

    /* === Search city === */
    $('#country-popup').on('click', '#getCities ul li .statedata', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $('#getCities #results').hide();
        $('#getCities .loader').show();
        var $item = $(this).closest('.statedata');
        var id = $item.data('id');
        var action = "ModelGetCityByStateID";
        var data = {action: action, id: id};

        $.post(ajaxurl, data, function (result) {
            $("#getCities #results").html(result);
            $('#getCities .loader').hide();
            $('#getCities #results').show();
        });
    });

    $('#country-popup').on('click', '#getCities ul li #changeState', function (e) {
        // Keep ads item click from being executed.
        e.stopPropagation();
        // Prevent navigating to '#'.
        e.preventDefault();
        // Ask user if he is sure.
        $('#getCities #results').hide();
        $('#getCities .loader').show();
        var $item = $(this).closest('.quick-states');
        var id = $item.data('country-id');
        var action = "ModelGetStateByCountryID";
        var data = {action: action, id: id};

        $.post(ajaxurl, data, function (result) {
            $("#getCities #results").html(result);
            $('#getCities .loader').hide();
            $('#getCities #results').show();
        });
    });

    $('#country-popup').on('click', 'ul li .selectme', function (e) {
        e.stopPropagation();
        e.preventDefault();
        var id = $(this).data('id');
        var name = $(this).data('name');
        var type = $(this).data('type');
        var country = $('.quick-states').data('country-id');
        $('#inputStateCity').val(name);
        $('#searchStateCity').val(name);
        $('#headerStateCity').html(name + ' <i class="fa fa-pencil"></i>');
        $('#searchPlaceType').val(type);
        $('#searchPlaceId').val(id);
        if ($('#countryModal').length) {
            $.magnificPopup.close();
        }

        localStorage.Quick_placeText = name;
        localStorage.Quick_PlaceId = id;
        localStorage.Quick_PlaceType = type;
        localStorage.Quick_Country = country;
        $("#searchDisplay").html('').hide();
    });

    $('.category-dropdown').on('click', '#category-change a', function (ev) {
        if ("#" === $(this).attr('href')) {
            ev.preventDefault();
            var parent = $(this).parents('.category-dropdown');
            parent.find('.change-text').html($(this).html());
            var id = $(this).data('ajax-id');
            var type = $(this).data('cat-type');

            if (type == "all") {
                $('#input-subcat').val('');
                $('#input-maincat').val('');
            }
            else if (type == "maincat") {
                $('#input-subcat').val('');
            }
            else {
                $('#input-maincat').val('');
            }
            $('#input-' + type).val(id);
        }
    }).on('click', '#category-change .dropdown-arrow', function (e) {
        e.preventDefault();
        $(this).parent().toggleClass('open');
        return false;
    });

    $('#searchStateCity').on('click', function () {
        $('#change-city').trigger('click');
    });

    var country = $('.quick-states').data('country-id');
    if (localStorage.Quick_placeText != "") {
        if(localStorage.Quick_Country == country) {
            var placeText = localStorage.Quick_placeText;
            var PlaceId = localStorage.Quick_PlaceId;
            var PlaceType = localStorage.Quick_PlaceType;

            if (placeText != null) {
                $('#inputStateCity').val(placeText);
                $('#searchStateCity').val(placeText);
                $('#headerStateCity').html(placeText + ' <i class="fa fa-pencil"></i>');
                $('#searchPlaceId').val(PlaceId);
                $('#searchPlaceType').val(PlaceType);
            }
        }
    }

    var searchCityAjax = null;
    $("#inputStateCity").keyup(function () {
        if (searchCityAjax) {
            searchCityAjax.abort();
        }
        var searchbox = $(this).val();
        var dataString = 'searchword1=' + searchbox;

        var action = "searchStateCountry";
        var data = {action: action, dataString: searchbox};

        if (searchbox == '') {
            $('#searchDisplay').hide();
        }
        else {
            $('#searchDisplay').show();
            searchCityAjax = $.post(ajaxurl, data, function (result) {
                $("#searchDisplay").html(result).show();
            });
        }
        return false;
    });


    var inputField = jQuery('.qucikad-ajaxsearch-input');
    var inputSubcatField = jQuery('#input-subcat');
    var inputCatField = jQuery('#input-maincat');
    var inputKeywordsField = jQuery('#input-keywords');
    var myDropDown = jQuery("#qucikad-ajaxsearch-dropdown");
    var myDropDown1 = jQuery("#qucikad-ajaxsearch-dropdown ul li");
    var myDropOption = jQuery('#qucikad-ajaxsearch-dropdown > option');
    var html = jQuery('html');
    var select = jQuery('.qucikad-ajaxsearch-input, #qucikad-ajaxsearch-dropdown > option');
    var lps_tag = jQuery('.qucikad-as-tag');
    var lps_cat = jQuery('.qucikad-as-cat');


    jQuery("#def-cats").append(jQuery('#qucikad-ajaxsearch-dropdown ul').html());

    var length = myDropOption.length;
    inputField.on('click', function (event) {
        //event.preventDefault();
        myDropDown.attr('size', length);
        myDropDown.css('display', 'block');
    });

    //myDropDown1.on('click', function(event) {
    jQuery(document).on('click', '#qucikad-ajaxsearch-dropdown ul li', function (event) {
        myDropDown.attr('size', 0);
        var dropValue = jQuery.trim(jQuery(this).text());
        var tagVal = jQuery(this).data('tagid');
        var catVal = jQuery(this).data('catid');
        var moreVal = jQuery(this).data('moreval');

        inputField.val(dropValue);
        inputSubcatField.val(tagVal);
        inputCatField.val(catVal);
        inputKeywordsField.val("");
        if (tagVal == null && catVal == null && moreVal != null) {
            inputField.val(moreVal);
            inputKeywordsField.val(moreVal);
        }
        jQuery("form i.qucikad-ajaxsearch-close").css("display", "block");
        myDropDown.css('display', 'none');
    });

    jQuery('form i.qucikad-ajaxsearch-close').on('click', function () {
        jQuery("form i.qucikad-ajaxsearch-close").css("display", "none");
        jQuery('form .qucikad-ajaxsearch-input').val('');
        jQuery("img.loadinerSearch").css("display", "block");
        var qString = '';

        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajaxurl,
            data: {
                'action': 'quickad_ajax_home_search',
                'tagID': qString
            },
            success: function (data) {
                if (data) {
                    jQuery("#qucikad-ajaxsearch-dropdown ul").empty();
                    var resArray = [];
                    if (data.suggestions.cats) {
                        jQuery.each(data.suggestions.cats, function (i, v) {
                            resArray.push(v);
                        });

                    }
                    jQuery('img.loadinerSearch').css('display', 'none');
                    jQuery("#qucikad-ajaxsearch-dropdown ul").append(resArray);
                    myDropDown.css('display', 'block');
                }
            }
        });
        jQuery('img.loadinerSearch').css('display', 'none');
    });

    html.on('click', function (event) {
        //event.preventDefault();
        myDropDown.attr('size', 0);
        myDropDown.css('display', 'none');
        //$("#searchDisplay").attr('size', 0);
        jQuery("#searchDisplay").css('display', 'none');
    });

    select.on('click', function (event) {
        event.stopPropagation();
    });

    var resArray = [];
    var newResArray = [];
    var bufferedResArray = [];
    var prevQString = '?';

    function trimAttributes(node) {
        jQuery.each(node.attributes, function () {
            var attrName = this.name;
            var attrValue = this.value;
            // remove attribute name start with "on", possible unsafe,
            // for example: onload, onerror...
            //
            // remvoe attribute value start with "javascript:" pseudo protocol, possible unsafe,
            // for example href="javascript:alert(1)"
            if (attrName.indexOf('on') == 0 || attrValue.indexOf('javascript:') == 0) {
                jQuery(node).removeAttr(attrName);
            }
        });
    }

    function sanitize(html) {
        var output = jQuery($.parseHTML('<div>' + html + '</div>', null, false));
        output.find('*').each(function () {
            trimAttributes(this);
        });
        return output.html();
    }

    inputField.on('input', function () {
        var $this = jQuery(this);
        var qString = sanitize(this.value);
        lpsearchmode = jQuery('body').data('lpsearchmode');
        lpsearchmode = "titlematch";
        noresultMSG = jQuery(this).data('noresult');
        jQuery("#qucikad-ajaxsearch-dropdown ul").empty();
        jQuery("#qucikad-ajaxsearch-dropdown ul li").remove();
        prevQuery = $this.data('prev-value');
        $this.data("prev-value", qString.length);
        inputKeywordsField.val(qString);

        if (qString.length == 0) {

            defCats = jQuery('#def-cats').html();
            myDropDown.css('display', 'none');
            jQuery("#qucikad-ajaxsearch-dropdown ul").empty();

            jQuery("#qucikad-ajaxsearch-dropdown ul").append(defCats);
            myDropDown.css('display', 'block');
            $this.data("prev-value", qString.length);
            inputKeywordsField.val("");
            jQuery("form i.qucikad-ajaxsearch-close").css("display", "none");
        }
        else if ((qString.length == 1 && prevQString != qString) || (qString.length == 1 && prevQuery < qString.length)) {

            myDropDown.css('display', 'none');
            jQuery("#qucikad-ajaxsearch-dropdown ul").empty();
            resArray = [];
            //jQuery('#selector').val().length
            jQuery("form i.qucikad-ajaxsearch-close").css("display", "block");
            jQuery("img.loadinerSearch").css("display", "block");
            //jQuery(this).addClass('loaderimg');
            jQuery.ajax({
                type: 'POST',
                dataType: 'json',
                url: ajaxurl,
                data: {
                    'action': 'quickad_ajax_home_search',
                    'tagID': qString
                },
                success: function (data) {
                    if (data) {

                        if (data.suggestions.tag || data.suggestions.tagsncats || data.suggestions.cats || data.suggestions.titles) {

                            if (data.suggestions.tag) {
                                jQuery.each(data.suggestions.tag, function (i, v) {
                                    resArray.push(v);
                                });
                            }

                            if (data.suggestions.tagsncats) {
                                jQuery.each(data.suggestions.tagsncats, function (i, v) {
                                    resArray.push(v);
                                });

                            }


                            if (data.suggestions.cats) {
                                jQuery.each(data.suggestions.cats, function (i, v) {

                                    resArray.push(v);

                                });

                                if (data.suggestions.tag == null && data.suggestions.tagsncats == null && data.suggestions.titles == null) {
                                    resArray = resArray;
                                }
                                else {
                                }
                            }

                            if (data.suggestions.titles) {
                                jQuery.each(data.suggestions.titles, function (i, v) {

                                    resArray.push(v);

                                });

                            }

                        }
                        else {
                            if (data.suggestions.more) {
                                jQuery.each(data.suggestions.more, function (i, v) {
                                    resArray.push(v);
                                });

                            }
                        }

                        prevQString = data.tagID;

                        jQuery('img.loadinerSearch').css('display', 'none');
                        if (jQuery('form #select').val() == '') {
                            jQuery("form i.qucikad-ajaxsearch-close").css("display", "none");
                        }
                        else {
                            jQuery("form i.qucikad-ajaxsearch-close").css("display", "block");
                        }

                        bufferedResArray = resArray;
                        filteredRes = [];
                        qStringNow = jQuery('.qucikad-ajaxsearch-input').val();
                        jQuery.each(resArray, function (key, value) {

                            if (jQuery(value).find('a').length == "1") {
                                rText = jQuery(value).find('a').text();
                            }
                            else {
                                rText = jQuery(value).text();
                            }

                            if (lpsearchmode == "keyword") {

                                qStringNow = qStringNow.toUpperCase();
                                rText = rText.toUpperCase();
                                var regxString = new RegExp(qStringNow, 'g');
                                var lpregxRest = rText.match(regxString);
                                if (lpregxRest) {
                                    filteredRes.push(value);
                                }

                            } else {
                                if (rText.substr(0, qStringNow.length).toUpperCase() == qStringNow.toUpperCase()) {
                                    filteredRes.push(value);
                                }
                            }
                        });

                        if (filteredRes.length > 0) {
                            myDropDown.css('display', 'none');
                            jQuery("#qucikad-ajaxsearch-dropdown ul").empty();

                            jQuery("#qucikad-ajaxsearch-dropdown ul").append(filteredRes);
                            myDropDown.css('display', 'block');
                            $this.data("prev-value", qString.length);

                        }

                        else if (filteredRes.length < 1 && qStringNow.length < 2) {
                            myDropDown.css('display', 'none');
                            jQuery("#qucikad-ajaxsearch-dropdown ul").empty();
                            jQuery('#qucikad-ajaxsearch-dropdown ul li').remove();
                            $mResults = '<strong>' + noresultMSG + ' </strong>';
                            $mResults = $mResults + qString;
                            var defRes = '<li class="qucikad-ajaxsearch-li-more-results" data-moreval="' + qString + '">' + $mResults + '</li>';
                            newResArray.push(defRes);
                            jQuery("#qucikad-ajaxsearch-dropdown ul").append(newResArray);
                            myDropDown.css('display', 'block');
                            $this.data("prev-value", qString.length);
                        }
                    }
                }

            });
        }
        /* get results from buffered data */
        else {
            newResArray = [];
            myDropDown.css('display', 'none');
            jQuery("#qucikad-ajaxsearch-dropdown ul").empty();
            jQuery.each(bufferedResArray, function (key, value) {
                var stringToCheck = jQuery(value).find('span').first().text();

                if (lpsearchmode == "keyword") {

                    qString = qString.toUpperCase();
                    stringToCheck = stringToCheck.toUpperCase();

                    var regxString = new RegExp(qString, 'g');
                    var lpregxRest = stringToCheck.match(regxString);
                    if (lpregxRest) {
                        newResArray.push(value);
                    }

                } else {

                    if (stringToCheck.substr(0, qString.length).toUpperCase() == qString.toUpperCase()) {
                        newResArray.push(value);
                    }
                }
            });
            if (newResArray.length == 0) {
                jQuery("#qucikad-ajaxsearch-dropdown ul").empty();
                jQuery('#qucikad-ajaxsearch-dropdown ul li').remove();
                $mResults = '<strong>' + noresultMSG + ' </strong>';
                $mResults = $mResults + qString;
                var defRes = '<li class="qucikad-ajaxsearch-li-more-results" data-moreval="' + qString + '">' + $mResults + '</li>';
                newResArray.push(defRes);
            }

            jQuery("#qucikad-ajaxsearch-dropdown ul").append(newResArray);
            myDropDown.css('display', 'block');
            jQuery("form i.qucikad-ajaxsearch-close").css("display", "block");
        }
    });


    jQuery('.qucikad-ajaxsearch-input').on('click', function (event) {

        jQuery("#qucikad-ajaxsearch-dropdown").niceScroll({
            cursorcolor: "#c9c9c9",
            cursoropacitymax: 1,
            boxzoom: false,
            cursorwidth: "8px",
            cursorborderradius: "0",
            cursorborder: "0",
            touchbehavior: true,
            preventmultitouchscrolling: false,
            cursordragontouch: true,
            background: "#fff",
            horizrailenabled: false,
            autohidemode: false,
            zindex: "999999"
        });
    });

    <!-- Bid Acceptance Ajax -->
    $(document).on('click', ".accept-offer" ,function(e){
        e.stopPropagation();
        e.preventDefault();

        var bidid = $(this).data('bidid'),
            amount = $(this).data('amount'),
            userid = $(this).data('userid'),
            username = $(this).data('username'),
            fullname = $(this).data('fullname'),
            action = 'accept_bid';
        $('.bidder-name').text(fullname);
        $('.bid-acceptance').text(amount);
        $('.bid-id').val(bidid);
        $.magnificPopup.open({
            items: {
                src: '#small-dialog-1',
                type: 'inline',
                fixedContentPos: false,
                fixedBgPos: true,
                overflowY: 'auto',
                closeBtnInside: true,
                preloader: false,
                midClick: true,
                removalDelay: 300,
                mainClass: 'my-mfp-zoom-in'
            }
        });
    });

    $("#accept-bid-form").on('submit',function (e) {
        e.preventDefault();
        var form_data = {
            action: 'accept_bid',
            id: $(".bid-id").val()
        };

        $('#accept-bid-button').addClass('button-progress').prop('disabled', true);
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: form_data,
            dataType: 'json',
            success: function (response) {
                if(response.success){
                    $("#accept-bid-status").addClass('success').removeClass('error').html('<p>'+response.message+'</p>').slideDown();
                    location.reload();
                }
                else {
                    $("#accept-bid-status").removeClass('success').addClass('error').html('<p>'+response.message+'</p>').slideDown();
                }
                $('#accept-bid-button').removeClass('button-progress').prop('disabled', false);
            }
        });
        return false;
    });
    <!-- Bid Acceptance Ajax -->

    $("#create-milestone-form").on('submit',function (e) {
        e.preventDefault();
        var action = 'create_milestone';
        var data = $(this).serialize();

        $('#create-milestone-button').addClass('button-progress').prop('disabled', true);
        $.ajax({
            type: "POST",
            url: ajaxurl+'?action='+action,
            data: data,
            dataType: 'json',
            success: function (response) {
                if(response.success){
                    $("#create-milestone-status").addClass('success').removeClass('error').html('<p>'+response.message+'</p>').slideDown();
                    location.reload();
                }
                else {
                    $("#create-milestone-status").removeClass('success').addClass('error').html('<p>'+response.message+'</p>').slideDown();
                }
                $('#create-milestone-button').removeClass('button-progress').prop('disabled', false);
            }
        });
        return false;
    });

    $('#js-table-list').on('click', '.item-ajax-button', function (e) {
        // Keep ads item click from being executed.
        e.stopPropagation();
        // Prevent navigating to '#'.
        e.preventDefault();
        // Ask user if he is sure.
        var action = $(this).data('ajax-action');
        var alert_mesg = $(this).data('alert-message');
        var $item = $(this).closest('.ajax-item-listing');
        var data = {action: action, id: $item.data('item-id')};
        if (confirm(alert_mesg)) {
            $.ajax({
                type: "POST",
                url: ajaxurl+'?action='+action,
                data: data,
                dataType: 'json',
                success: function (response) {
                    if(response.success){
                        Snackbar.show({text: response.message});
                        location.reload();
                    }else{
                        Snackbar.show({text: response.message});
                    }
                }
            });
        }
    });
    $('#js-table-list').on('click', '.item-js-close', function (e) {
        // Keep ads item click from being executed.
        e.stopPropagation();
        // Prevent navigating to '#'.
        e.preventDefault();
        // Ask user if he is sure.
        var action = $(this).data('ajax-action');
        var $item = $(this).closest('.ajax-item-listing');
        var data = {action: action, id: $item.data('item-id')};
        if (confirm(LANG_ARE_YOU_SURE)) {
            $.post(ajaxurl + '?action=' + action, data, function (response) {
                if (response != 0) {
                    $item.remove();
                    Snackbar.show({text: LANG_PROJECT_CLOSED});
                } else {
                    Snackbar.show({text: LANG_ERROR_TRY_AGAIN});
                }
            });
        }
    });
    $('#js-table-list').on('click', '.item-js-delete', function (e) {
        // Keep ads item click from being executed.
        e.stopPropagation();
        // Prevent navigating to '#'.
        e.preventDefault();
        // Ask user if he is sure.
        var action = $(this).data('ajax-action');
        var $item = $(this).closest('.ajax-item-listing');
        var data = {action: action, id: $item.data('item-id')};
        if (confirm(LANG_ARE_YOU_SURE)) {
            $.post(ajaxurl + '?action=' + action, data, function (response) {
                if (response != 0) {
                    $item.remove();
                    Snackbar.show({text: LANG_PROJECT_DELETED});
                } else {
                    Snackbar.show({text: LANG_ERROR_TRY_AGAIN});
                }
            });
        }
    });

    $('#js-table-list').on('click', '.item-js-hide', function (e) {
        e.stopPropagation();
        e.preventDefault();
        var action = $(this).data('ajax-action');
        var $item = $(this).closest('.ajax-item-listing');
        var data = {action: action, id: $item.data('item-id')};

        $.post(ajaxurl + '?action=' + action, data, function (response) {
            if (response == 1) {
                $item.addClass('opapcityLight');
                $item.find('.label-hidden').html(LANG_HIDDEN)
                $item.find('.item-js-hide').attr('title', LANG_SHOW);
                $item.find('.item-js-hide').html('<i class="icon-feather-eye"></i>');
            }
            else if (response == 2) {
                $item.removeClass('opapcityLight');
                $item.find('.label-hidden').html(LANG_SHOW)
                $item.find('.item-js-hide').attr('title', LANG_HIDE);
                $item.find('.item-js-hide').html('<i class="icon-feather-eye-off"></i>');
            }
            else {
                Snackbar.show({text: LANG_ERROR_TRY_AGAIN});
            }
        });
    });

    $('.ajax-delete-resume').on('click', function (e) {
        // Keep ads item click from being executed.
        e.stopPropagation();
        // Prevent navigating to '#'.
        e.preventDefault();
        // Ask user if he is sure.
        var action = 'deleteResume';
        var $item = $(this).closest('.resume-row');
        var data = {action: action, id: $item.data('item-id')};
        if (confirm(LANG_ARE_YOU_SURE)) {
            $.post(ajaxurl + '?action=' + action, data, function (response) {
                if (response != 0) {
                    $item.remove();
                    Snackbar.show({text: LANG_RESUME_DELETED});
                } else {
                    Snackbar.show({text: LANG_ERROR_TRY_AGAIN});
                }
            });
        }
    });

    $('.ajax-delete-experience').on('click', function (e) {
        // Keep ads item click from being executed.
        e.stopPropagation();
        // Prevent navigating to '#'.
        e.preventDefault();
        // Ask user if he is sure.
        var action = 'deleteExperience';
        var $item = $(this).closest('.experience-row');
        var data = {action: action, id: $item.data('item-id')};
        if (confirm(LANG_ARE_YOU_SURE)) {
            $.post(ajaxurl + '?action=' + action, data, function (response) {
                if (response != 0) {
                    $item.remove();
                    Snackbar.show({text: LANG_EXPERIENCE_DELETED});
                } else {
                    Snackbar.show({text: LANG_ERROR_TRY_AGAIN});
                }
            });
        }
    });

    $('.ajax-delete-company').on('click', function (e) {
        // Keep ads item click from being executed.
        e.stopPropagation();
        // Prevent navigating to '#'.
        e.preventDefault();
        // Ask user if he is sure.
        var action = 'deleteCompany';
        var $item = $(this).closest('.company-row');
        var data = {action: action, id: $item.data('item-id')};
        if (confirm(LANG_ARE_YOU_SURE)) {
            $.post(ajaxurl + '?action=' + action, data, function (response) {
                if (response != 0) {
                    $item.remove();
                    Snackbar.show({text: LANG_COMPANY_DELETED});
                } else {
                    Snackbar.show({text: LANG_ERROR_TRY_AGAIN});
                }
            });
        }
    });

    // blog comment with ajax
    $('.blog-comment-form').on('submit', function (e) {
        e.preventDefault();
        var action = 'submitBlogComment';
        var data = $(this).serialize();
        var $parent_cmnt = $(this).find('#comment_parent').val();
        var $cmnt_field = $(this).find('#comment-field');
        var $btn = $(this).find('.button');
        $btn.addClass('button-loader').prop('disabled',true);
        $.ajax({
            type: "POST",
            url: ajaxurl+'?action='+action,
            data: data,
            dataType: 'json',
            success: function (response) {
                $btn.removeClass('button-loader').prop('disabled',false);
                if(response.success){
                    if($parent_cmnt == 0){
                        $('.latest-comments > ul').prepend(response.html);
                    }else{
                        $('#li-comment-'+$parent_cmnt).after(response.html);
                    }
                    $('html, body').animate({
                        scrollTop: $("#li-comment-"+response.id).offset().top
                    }, 2000);
                    $cmnt_field.val('');
                }else{
                    $('#respond > .widget-content').prepend('<div class="notification error"><p>'+response.error+'</p></div>');
                }
            }
        });
    });
});

/* Live Location Detect
 /* ========================================================================== */
jQuery(document).ready(function($) {
    var loc = jQuery('.loc-tracking').data('option');
    var apiType = jQuery('#page').data('ipapi');
    var currentlocationswitch = '1';
    currentlocationswitch = jQuery('#page').data('showlocationicon');

    if (currentlocationswitch == "0") {
        loc = 'locationifoff';
        jQuery('.loc-tracking > i').fadeOut('fast');
    }

    if (loc == 'yes') {
        if (jQuery('.intro-search-field').is('.live-location-search')) {
            if (apiType === "geo_ip_db") {
                jQuery.getJSON('https://geoip-db.com/json/geoip.php?jsonp=?')
                    .done(function (location) {

                        getCityidByCityName(location.country_code, location.state, location.city);
                        jQuery('input[name=location]').val(location.city);

                        jQuery('.live-location-search .loc-tracking > i').fadeOut('slow');
                    });
            }
            else if (apiType === "ip_api") {
                jQuery.get("https://ipapi.co/json", function (location) {

                    getCityidByCityName(location.country, location.region, location.city);
                    jQuery('input[name=location]').val(location.city);

                    jQuery('.live-location-search .loc-tracking > i').fadeOut('slow');
                }, "json");
            }
            else {
                GetCurrentGpsLoc(function (GpsLocationCityData) {
                    myCurrentGpsLocation = GpsLocationCityData;
                    getCityidByCityName(myCurrentGpsLocation.country, myCurrentGpsLocation.region, myCurrentGpsLocation.city);
                    jQuery('input[name=location]').val(myCurrentGpsLocation.city);

                    jQuery('.live-location-search .loc-tracking > i').fadeOut('slow');
                });
            }

        }
    }
    else if (loc == 'no') {
        jQuery('.live-location-search .loc-tracking > i').on('click', function (event) {
            event.preventDefault();
            jQuery(this).addClass('fa fa-circle-o-notch fa-spin');
            jQuery(this).removeClass('la la-crosshairs');
            if (jQuery('.intro-search-field').is('.live-location-search')) {
                if (apiType === "geo_ip_db") {
                    jQuery.getJSON('https://geoip-db.com/json/geoip.php?jsonp=?')
                        .done(function (location) {

                            if (location.city == null) {
                            }
                            else {
                                getCityidByCityName(location.country_code, location.state, location.city);
                                jQuery('input[name=latitude]').val(location.latitude);
                                jQuery('input[name=longitude]').val(location.longitude);
                                jQuery('input[name=location]').val(location.city);
                            }
                            jQuery('.live-location-search .loc-tracking > i').fadeOut('slow');
                        });
                }
                else if (apiType === "ip_api") {
                    jQuery.get("https://ipapi.co/json", function (location) {
                        if (location.city == null) {
                        }
                        else {
                            getCityidByCityName(location.country, location.region, location.city);

                            jQuery('input[name=latitude]').val(location.latitude);
                            jQuery('input[name=longitude]').val(location.longitude);
                            jQuery('input[name=location]').val(location.city);
                        }
                        jQuery('.live-location-search .loc-tracking > i').fadeOut('slow');

                    }, "json");
                }
                else {

                    GetCurrentGpsLoc(function (GpsLocationCityData) {
                        myCurrentGpsLocation = GpsLocationCityData;
                        getCityidByCityName(myCurrentGpsLocation.country, myCurrentGpsLocation.region, myCurrentGpsLocation.city);
                        jQuery('input[name=location]').val(myCurrentGpsLocation.city);
                        jQuery('.live-location-search .loc-tracking > i').fadeOut('slow');
                    });

                }
            }
        });
    }
});

//GPS LIVE LOCATION
var geocoderr;
function GetCurrentGpsLoc(lpcalback){
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position){
            var clat = position.coords.latitude;
            var clong = position.coords.longitude;
            jpCodeLatLng(clat,clong, function(citynamevalue){

                lpcalback(citynamevalue);

            });
        });

    } else {
        alert("Geolocation is not supported by this browser.");
    }

}

function lpgeocodeinitialize() {
    geocoderr = new google.maps.Geocoder();
}

function jpCodeLatLng(lat, lng, lpcitycallback) {

    latlng 	 = new google.maps.LatLng(lat, lng),
        geocoderrr = new google.maps.Geocoder();
    geocoderrr.geocode({'latLng': latlng}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
                for (var i = 0; i < results.length; i++) {
                    if (results[i].types[0] === "locality") {
                        var city = results[i].address_components[0].short_name;
                        var region = results[i].address_components[2].long_name;
                        var country = results[i].address_components[3].short_name;

                        var $citydata = {};
                        $citydata['city'] = city;
                        $citydata['region'] = region;
                        $citydata['country'] = country;
                        lpcitycallback($citydata);
                    }
                }
            }
            else {console.log("No reverse geocode results.")}
        }
        else {console.log("Geocoder failed: " + status)}
    });
}

function getCityidByCityName(country,state,city) {
    var data = {action: "getCityidByCityName", city: city, state: state, country: country};
    $.ajax({
        type: "POST",
        url: ajaxurl,
        data: data,
        success: function (result) {
            $('#searchPlaceType').val("city");
            $('#searchPlaceId').val(result);
        }
    });
}