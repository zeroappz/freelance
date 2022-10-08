<?php
overall_header($name);
?>
<div id="titlebar">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?php _esc($name)?></h2>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="listing_job">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>">
                                <?php _e("Home") ?>
                            </a></li>
                        <li><a href="<?php url("COMPANIES") ?>">
                                <?php _e("Companies") ?>
                            </a></li>
                        <li><?php _esc($name)?></li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</div>
<div class="container">
	<div class="row">
		<!-- Content -->
		<div class="col-xl-8 col-lg-8">
      <div class="single-page-section margin-bottom-30">
        <div class="single-page-inner">
          <div class="single-page-image"><img src="<?php _esc($logo)?>" alt="<?php _esc($name)?>"></div>
          <div class="single-page-details">
            <h3><?php _esc($name)?></h3>
            <ul>
                <?php
                if($cityname != "") {
                    echo '<li><i class="icon-feather-map-pin"></i> <span>'._esc($cityname,false).', '._esc($statename,false).'</span></li>';
                }
                if(!$hide_contact) {
                    if($phone != "") {
                        echo '<li><i class="icon-feather-phone-call"></i> <span><a href="tel:'._esc($phone,false).'" rel="nofollow">'._esc($phone,false).'</a></span></li>';
                    }
                    if($fax != ""){
                        echo '<li><i class="icon-feather-printer"></i> <span>'._esc($fax,false).'</span></li>';
                    }
                    if($email != ""){
                        echo '<li><i class="icon-feather-mail"></i> <span><a href="mailto:'._esc($email,false).'" rel="nofollow">'._esc($phone,false).'</a></span></li>';
                    }
                }
                if($website != ""){
                    echo '<li><i class="icon-feather-link"></i> <span><a href="'._esc($website,false).'" rel="nofollow">'._esc($website,false).'</a></span></li>';
                }
                if($config['reg_no_enable'] && $company_reg_no != ""){
                    echo '<li><i class="icon-feather-file-text"></i> <span>'._esc($company_reg_no,false).'</span></li>';
                }
                ?>
              </ul>
        </div>
        </div>
      </div>
			<div class="single-page-section">
				<?php _esc($description)?>
			</div>

            <?php if($totalitem) { ?>
			<div class="boxed-list margin-bottom-60" id="all-jobs">
				<div class="boxed-list-headline">
					<h3><i class="icon-feather-briefcase"></i> <?php _e("All Jobs") ?></h3>
				</div>
                <div class="listings-container compact-list-layout margin-top-30">
                    <?php foreach ($items as $item) { ?>
                      <a href="<?php _esc($item['link'])?>" class="job-listing">
                          <div class="job-listing-details">
                              <div class="job-listing-description">
                                  <h3 class="job-listing-title"><?php _esc($item['name'])?>
                                      <?php
                                      if($item['featured']=="1") {
                                          echo '<div class="dashboard-status-button blue">'.__("Featured").'</div>';
                                      }
                                      if($item['urgent']=="1") {
                                          echo '<div class="dashboard-status-button yellow">'.__("Urgent").'</div>';
                                      }
                                      if($item['highlight']=="1") {
                                          echo '<div class="dashboard-status-button red">'.__("Highlight").'</div>';
                                      }
                                      ?>
                                  </h3>
                                  <div class="job-listing-footer">
                                      <ul>
                                          <li><i class="la la-map-marker"></i> <?php _esc($item['city'])?></li>
                                          <?php if($item['salary_min']!="0") { ?>
                                              <li><i class="la la-credit-card"></i> <?php _esc($item['salary_min'])?>-<?php _esc($item['salary_max'])?></li>
                                         <?php } ?>
                                          <li><i class="la la-clock-o"></i> <?php _esc($item['created_at'])?></li>
                                      </ul>
                                  </div>
                              </div>
                              <span class="job-type"><?php _esc($item['product_type'])?></span>
                          </div>
                      </a>
                    <?php } ?>
                </div>
			</div>
            <?php } ?>
		</div>



		<!-- Sidebar -->
		<div class="col-xl-4 col-lg-4">
			<div class="sidebar-container">
                <?php if($facebook != "" || $twitter != "" || $linkedin != "" || $pinterest != "" || $youtube != "" || $instagram != "") { ?>

                <div class="sidebar-widget">
                    <h3><?php _e("Social Profiles") ?></h3>
                    <div class="freelancer-socials margin-top-25">
                        <ul>
                            <?php
                            if($facebook != "") {
                                echo '<li><a href="'._esc($facebook,false).'" data-button-color="#3b5998" title="'.__("Facebook").'" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="icon-brand-facebook"></i></a></li>';
                            }
                            if($twitter != "") {
                                echo '<li><a href="'._esc($twitter,false).'" data-button-color="#1da1f2" title="'.__("Twitter").'" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="icon-brand-twitter"></i></a></li>';
                            }
                            if($linkedin != "") {
                                echo '<li><a href="'._esc($linkedin,false).'" data-button-color="#0077b5" title="'.__("Linkedin").'" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="icon-brand-linkedin"></i></a></li>';
                            }
                            if($pinterest != "") {
                                echo '<li><a href="'._esc($pinterest,false).'" data-button-color="#bd081c" title="'.__("Pinterest").'" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="icon-brand-pinterest-p"></i></a></li>';
                            }
                            if($youtube != "") {
                                echo '<li><a href="'._esc($youtube,false).'" data-button-color="#ff0000" title="'.__("Youtube").'" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="icon-brand-youtube"></i></a></li>';
                            }
                            if($instagram != "") {
                                echo '<li><a href="'._esc($instagram,false).'" data-button-color="#e1306c" title="'.__("Instagram").'" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="icon-brand-instagram"></i></a></li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <?php } ?>
                <?php if($latitude != "" && $longitude != "") { ?>
				<!-- Location -->
				<div class="sidebar-widget">
					<h3><?php _e("Location") ?></h3>
					<div id="single-job-map-container">
						<div id="map-detail" class="map-widget map height-200px"></div>
					</div>
                    <div id="single-job-map-container">
                        <div id="singleListingMap" data-latitude="<?php _esc($latitude)?>" data-longitude="<?php _esc($longitude)?>" data-map-icon="im im-icon-Hamburger"></div>
                        <a href="#" id="streetView"><?php _e("Street View") ?></a>
                    </div>
				</div>
                <?php } ?>
                <div class="sidebar-widget">
                    <h3><?php _e("Share it") ?></h3>
                    <!-- Copy URL -->
                    <div class="copy-url">
                        <input id="copy-url" type="text" value="" class="with-border">
                        <button class="copy-url-button ripple-effect" data-clipboard-target="#copy-url" title="<?php _e("Copy to Clipboard") ?>" data-tippy-placement="top"><i class="icon-material-outline-file-copy"></i></button>
                    </div>

                    <!-- Share Buttons -->
                    <div class="share-buttons margin-top-25">
                        <div class="share-buttons-trigger"><i class="icon-feather-share-2"></i></div>
                        <div class="share-buttons-content">
                            <span><?php _e("Interesting") ?>? <strong><?php _e("Share it") ?>!</strong></span>
                            <ul class="share-buttons-icons">
                                <li><a href="mailto:?subject=<?php _esc($name)?>&body=<?php _esc($item_link)?>" data-button-color="#dd4b39" title="<?php _e("Share on Email") ?>" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="fa fa-envelope"></i></a></li>
                                <li><a href="https://facebook.com/sharer/sharer.php?u=<?php _esc($item_link)?>" data-button-color="#3b5998" title="<?php _e("Share on Facebook") ?>" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="https://twitter.com/share?url=<?php _esc($item_link)?>&text=<?php _esc($name)?>" data-button-color="#1da1f2" title="<?php _e("Share on Twitter") ?>" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php _esc($item_link)?>" data-button-color="#0077b5" title="<?php _e("Share on LinkedIn") ?>" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="https://pinterest.com/pin/create/bookmarklet/?&url=<?php _esc($item_link)?>&description=<?php _esc($name)?>" data-button-color="#bd081c" title="<?php _e("Share on Pinterest") ?>" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="fa fa-pinterest-p"></i></a></li>
                                <li><a href="https://web.whatsapp.com/send?text=<?php _esc($item_link)?>" data-button-color="#25d366" title="<?php _e("Share on WhatsApp") ?>" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="fa fa-whatsapp"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
			</div>
		</div>

	</div>
</div>
<div class="margin-top-15"></div>

<?php
if($config['map_type']=="google"){
    ?>
    <link href="<?php _esc($config['site_url']);?>includes/assets/plugins/map/google/map-marker.css" type="text/css" rel="stylesheet">
    <script type='text/javascript' src='//maps.google.com/maps/api/js?key=<?php _esc($config['gmap_api_key'])?>&#038;libraries=places%2Cgeometry&#038;ver=2.2.1'></script>
    <script type='text/javascript' src='<?php _esc($config['site_url']);?>includes/assets/plugins/map/richmarker-compiled.js'></script>
    <script type='text/javascript' src='<?php _esc($config['site_url']);?>includes/assets/plugins/map/markerclusterer_packed.js'></script>
    <script type='text/javascript' src='<?php _esc($config['site_url']);?>includes/assets/plugins/map/gmapAdBox.js'></script>
    <script type='text/javascript' src='<?php _esc($config['site_url']);?>includes/assets/plugins/map/maps.js'></script>
    <script>
        var element = "singleListingMap";
        var getCity = false;
        var _latitude = '<?php _esc($latitude)?>';
        var _longitude = '<?php _esc($longitude)?>';
        var color = '<?php _esc($map_color)?>';
        var site_url = '<?php _esc($config['site_url']);?>';
        var path = site_url;
        simpleMap(_latitude, _longitude, element);
    </script>
    <?php
}else{
    ?>
    <script>
        var openstreet_access_token = '<?php _esc($config['openstreet_access_token'])?>';
    </script>
    <link rel="stylesheet" href="<?php _esc($config['site_url']);?>includes/assets/plugins/map/openstreet/css/style.css">
    <!-- Leaflet // Docs: https://leafletjs.com/ -->
    <script src="<?php _esc($config['site_url']);?>includes/assets/plugins/map/openstreet/leaflet.min.js"></script>

    <!-- Leaflet Maps Scripts (locations are stored in leaflet-quick.js) -->
    <script src="<?php _esc($config['site_url']);?>includes/assets/plugins/map/openstreet/leaflet-markercluster.min.js"></script>
    <script src="<?php _esc($config['site_url']);?>includes/assets/plugins/map/openstreet/leaflet-gesture-handling.min.js"></script>
    <script src="<?php _esc($config['site_url']);?>includes/assets/plugins/map/openstreet/leaflet-quick.js"></script>

    <!-- Leaflet Geocoder + Search Autocomplete // Docs: https://github.com/perliedman/leaflet-control-geocoder -->
    <script src="<?php _esc($config['site_url']);?>includes/assets/plugins/map/openstreet/leaflet-autocomplete.js"></script>
    <script src="<?php _esc($config['site_url']);?>includes/assets/plugins/map/openstreet/leaflet-control-geocoder.js"></script>

    <?php
}
overall_footer();
?>
