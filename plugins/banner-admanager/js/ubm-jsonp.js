var ubm_objects = new Array();
var ubm_urls = new Array();
var ubm_idx = 0;
var ubm_json = "";
var ubm_submitted = false;
var catid = 0;
var subcatid = 0;
var placeid = '';
var placetype = '';
var ubm_baseurl = (function() {
	var re = new RegExp('js/ubm-jsonp(\.min)?\.js.*'),
	scripts = document.getElementsByTagName('script');
	for (var i = 0, ii = scripts.length; i < ii; i++) {
		var path = scripts[i].getAttribute('src');
		if(re.test(path)) return path.replace(re, '');
	}
})();
function load_banner(){
    var ubm_boxes = jQuery("div.qbm-box");
    jQuery.each(ubm_boxes, function() {
        var url = jQuery(this).attr("data-url");
        ubm_urls[ubm_idx] = url;
        ubm_objects[ubm_idx] = this;
        ubm_idx++;
    });
    if (ubm_idx > 0) ubm_getbox(0);

    var ubm_tmp_banners = jQuery("a.quick-bm-banner");
    ubm_idx = 0;
    jQuery.each(ubm_tmp_banners, function() {
        var id = jQuery(this).attr("data-id");
        var $wrapper = jQuery(this).parent().parent();
        if ($wrapper.data("cat")) {
            catid = $wrapper.data("cat");
        }
        if ($wrapper.data("subcat")) {
            subcatid = $wrapper.data("subcat");
        }

        if (localStorage.Quick_PlaceId != "") {
            placeid = localStorage.Quick_PlaceId;
            placetype = localStorage.Quick_PlaceType;
        }

        var intRegex = /^\d+$/;
        if (id && intRegex.test(id)) {
            jQuery(this).attr("id", "ubm_" + ubm_idx);
            ubm_json = ubm_json + ubm_idx.toString() + ":" + id + ',';
            ubm_idx++;
        }
    });
    if (ubm_idx > 0) {
        jQuery.ajax({
            url: ubm_baseurl + "ajax.php",
            data: {
                catid: catid,
                subcatid: subcatid,
                placeid: placeid,
                placetype: placetype,
                ubm_banners: ubm_json,
                ubm_anticache: (Math.random()).toString(),
                action: "ubm_getbanner"
            },
            dataType: "jsonp",
            success: function(data) {
                var html_data = data.html;
                var banners = jQuery.parseJSON(html_data);
                for (var id in banners){
                    banner = banners[id];
                    if(banner && banner.match("ubm_banner") != null) {
                        jQuery("#"+id).replaceWith(banner);
                    }
                }
            }
        });
    }
}
jQuery(document).ready(function() {
    load_banner();
});

function ubm_getbox(idx) {
	var action = ubm_baseurl + "ajax.php";
	jQuery.ajax({
		url: action, 
		data: {
			ubm_url: ubm_urls[idx],
			action: "ubm_getbox"
		},
		dataType: "jsonp",
		success: function(data) {
			var html_data = data.html;
			if(html_data.match("ubm_container") != null) {
				jQuery(ubm_objects[idx]).css("display", "none");
				jQuery(ubm_objects[idx]).append(html_data);
				jQuery(ubm_objects[idx]).slideDown(600);
			}
		}
	});
}

function ubm_calc() {
	var days = parseInt(jQuery("#ubm_period").val(), 10);
	var price = jQuery("#ubm_type_"+jQuery("#ubm_type").val()).val();
	if (days && price > 0) {
		var total = days*price/10;
		jQuery("#ubm_total").val(total.toFixed(2));
	}
}	

function ubm_presubmit() {
	ubm_submitted = true;
	jQuery("#ubm_submit").attr("disabled","disabled");
	jQuery("#ubm_loading").fadeIn(300);
	jQuery("#ubm_message").slideUp("slow");
}

function ubm_load() {
	var id_str = jQuery("#ubm_id_str").val();
	if (id_str && ubm_submitted) {
		ubm_submitted = false;
		jQuery.ajax({
			url: ubm_baseurl+"ajax.php", 
			data: {
				ubm_id_str: id_str,
				action: "ubm_postsubmit"
			},
			dataType: "jsonp",
			success: function(data) {
				var html_data = data.html;
				jQuery("#ubm_loading").fadeOut(300);
				jQuery("#ubm_submit").removeAttr("disabled");
				if(html_data.match("ubm_confirmation_info") != null) {
					jQuery("#ubm_signup_form").fadeOut(500, function() {
						jQuery("#ubm_confirmation_container").html(html_data);
						jQuery("#ubm_confirmation_container").fadeIn(500, function() {});
					});
				} else {
					jQuery("#ubm_message").html(html_data);
					jQuery("#ubm_message").slideDown("slow");
				}
			}
		});
	}
}

function ubm_edit() {
	jQuery("#ubm_confirmation_container").fadeOut(500, function() {
		jQuery("#ubm_signup_form").fadeIn(500, function() {});
	});
}

function ubm_bitpay(banner_id, payment_url) {
	var button_label = jQuery("#ubm_bitpay").val();
	jQuery("#ubm_bitpay").val("Processing...");
	jQuery("#ubm_bitpay").attr("disabled","disabled");
	jQuery("#ubm_bitpay_edit").attr("disabled","disabled");
	jQuery("#ubm_loading2").fadeIn(300);
	jQuery("#ubm_message").slideUp("slow");

    location.href = payment_url;
}

function ubm_stripe(banner_id, return_url) {
	var token = function(res) {
		if (res && res.id) {
			var button_label = jQuery("#ubm_stripe").val();
			jQuery("#ubm_stripe").val("Processing...");
			jQuery("#ubm_stripe").attr("disabled","disabled");
			jQuery("#ubm_stripe_edit").attr("disabled","disabled");
			jQuery("#ubm_loading2").fadeIn(300);
			jQuery("#ubm_message").slideUp("slow");
			jQuery.ajax({
				url: ubm_baseurl+"ajax.php", 
				data: {
					ubm_id: banner_id,
					ubm_token: res.id,
					action: "ubm_stripecharge"
				},
				dataType: "jsonp",
				success: function(data) {
					var html_data = data.html;
					jQuery("#ubm_loading2").fadeOut(200);
					jQuery("#ubm_stripe").removeAttr("disabled");
					jQuery("#ubm_stripe_edit").removeAttr("disabled");
					jQuery("#ubm_stripe").val(button_label);
					if(html_data.match("ubm_confirmation_info") != null) {
						jQuery("#ubm_confirmation_container").fadeOut(500, function() {
							jQuery("#ubm_confirmation_container").html(html_data);
							jQuery("#ubm_confirmation_container").fadeIn(500, function() {location.href = return_url;});
						});
					} else {
						jQuery("#ubm_message").html(html_data);
						jQuery("#ubm_message").slideDown("slow");
					}
				}
			});
		}
	};
	StripeCheckout.open({
		key:         jQuery("#ubm_stripe_publishable").val(),
		address:     false,
		amount:      jQuery("#ubm_stripe_amount").val(),
		currency:    jQuery("#ubm_stripe_currency").val(),
		name:        jQuery("#ubm_stripe_label").val(),
		description: jQuery("#ubm_stripe_label").val(),
		panelLabel:  'Checkout',
		token:       token
	});
}