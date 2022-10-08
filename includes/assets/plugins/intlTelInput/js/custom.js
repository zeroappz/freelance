(function ($) {
    'use strict';

    $('#verify-mobile').intlTelInput({
        //preferredCountries: [Bookme.intlTelInput.country],
        initialCountry: 'auto',
        geoIpLookup: function (callback) {
            $.get('https://ipinfo.io', function () {
            }, 'jsonp').always(function (resp) {
                var countryCode = (resp && resp.country) ? resp.country : '';
                callback(countryCode);
            });
        },
        utilsScript: siteurl+"includes/assets/plugins/intlTelInput/js/intlTelInput.utils.js"
    });
    $(document).ready(function (e) {
        $('.go-back').on('click', function (e){
            $('.reset-confirmation')
                .removeClass('d-block')
                .addClass('d-none');
            $('.reset-form').addClass('d-block').removeClass('d-none');
        });
        $($('[name="pv-form"]')).on('submit', function (e){
            e.preventDefault();
            var getNumber = $('#verify-mobile').intlTelInput("getNumber");
            $('#verify-mobile').val(getNumber);
            var $btn = $(this).find('.button');
            $btn.addClass('button-progress').prop('disabled',true);
            var action = $("#pv-form").attr('action');
            var mobile_no = getNumber;
            var form_data = $(this).serialize();
            $.ajax({
                type: "POST",
                url: ajaxurl+'?action='+action,
                data: form_data,
                success: function (response) {
                    $btn.removeClass('button-progress').prop('disabled',false);
                    if (response == "success") {
                        $('.otp_mobile').html(mobile_no);
                        $('.reset-form')
                            .removeClass('d-block')
                            .addClass('d-none');
                        $('.reset-confirmation').addClass('d-block').removeClass('d-none');

                        $('.reset-confirmation .otp_mobile_no').val(mobile_no);
                    }
                    else {
                        $("#mobile-status").html('<span class="status-not-available text-danger">'+response+'</span>');
                    }
                }
            });
            return false;
        });

        $($('[name="otp-form"]')).on('submit', function (e){
            e.preventDefault();
            var $btn = $(this).find('.button');
            $btn.addClass('button-progress').prop('disabled',true);
            var action = $("#otp-form").attr('action');
            var form_data = $(this).serialize();
            $.ajax({
                type: "POST",
                url: ajaxurl+'?action='+action,
                data: form_data,
                success: function (response) {
                    $btn.removeClass('button-progress').prop('disabled',false);
                    if (response == "success") {
                        $(".otp-form-content").slideUp('slow', function () {
                            $("#otp-status").html('<span class="text-success">Thank you! Your phone number has been verified.</span>');
                            location.reload();
                        });
                    }
                    else {
                        $("#otp-status").html('<span class="status-not-available text-danger">'+response+'</span>');
                    }
                }
            });
            return false;
        });
    });
})(jQuery);