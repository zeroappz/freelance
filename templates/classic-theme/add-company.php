<?php
overall_header(__("Create New Company"));
?>
<!-- Dashboard Container -->
<div class="dashboard-container">

    <?php include_once TEMPLATE_PATH.'/dashboard_sidebar.php'; ?>


    <!-- Dashboard Content
    ================================================== -->
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner" >

            <!-- Dashboard Headline -->
            <div class="dashboard-headline">
                <h3><?php _e("Create New Company") ?></h3>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("Create New Company") ?></li>
                    </ul>
                </nav>
            </div>

            <!-- Row -->
            <div class="row">
                <!-- Dashboard Box -->
                <div class="col-xl-12">
                    <div class="dashboard-box margin-top-0">
                        <!-- Headline -->
                        <div class="headline">
                            <h3><i class="icon-feather-box"></i> <?php _e("Create New Company") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <?php
                            if($error != ''){
                                echo '<span class="status-not-available">'.$error.'</span>';
                            }
                            ?>
                            <form method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                                <div class="submit-field">
                                    <h5><?php _e("Name") ?> *</h5>
                                    <input type="text" class="with-border" id="name" name="name" value="<?php _esc($name)?>" required="">
                                </div>

                                <?php if($config['reg_no_enable']){ ?>
                                    <div class="submit-field">
                                        <h5><?php _e("Registration no.") ?> *</h5>
                                        <input type="text" class="with-border" id="reg_no" name="reg_no" value="<?php _esc($registration_no)?>" required="">
                                    </div>
                                <?php } ?>
                                <div class="submit-field">
                                    <h5><?php _e("Logo") ?></h5>
                                    <div class="uploadButton">
                                        <input class="uploadButton-input" type="file" accept="images/*" id="company_logo" name="logo"/>
                                        <label class="uploadButton-button ripple-effect" for="company_logo"><?php _e("Upload Logo") ?></label>
                                        <span class="uploadButton-file-name"><?php _e("Use 200x200px size for better view.") ?></span>
                                    </div>
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e("Description") ?> *</h5>
                                    <textarea cols="30" rows="5" class="with-border" name="company_desc" required="" style="white-space: pre-line;"><?php _esc($description)?></textarea>
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e("City") ?></h5>
                                    <select id="jobcity" class="with-border" name="city" data-size="7" title="<?php _e("Select") ?> <?php _e("City") ?>">
                                        <option value="0" selected="selected"><?php _e("Select") ?> <?php _e("City") ?></option>
                                        <?php if($city != ""){ ?>
                                            <option value="<?php _esc($city)?>" selected="selected"><?php _esc($cityname)?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <?php if($config['post_address_mode']){ ?>
                                    <div class="submit-field">
                                        <h5><?php _e("Address") ?></h5>
                                        <div class="input-with-icon">
                                            <div id="autocomplete-container" data-autocomplete-tip="<?php _e("type and hit enter") ?>">
                                                <input class="with-border" type="text" placeholder="<?php _e("Address") ?>" name="location" id="address-autocomplete">
                                            </div>
                                            <div class="geo-location"><i class="la la-crosshairs"></i></div>
                                        </div>
                                        <div class="map shadow" id="singleListingMap" data-latitude="<?php _esc($latitude)?>" data-longitude="<?php _esc($longitude)?>"  style="height: 200px" data-map-icon="map-marker"></div>
                                        <small class="hidden"><?php _e("Drag the map marker to exact address.") ?></small>
                                        <input type="hidden" id="latitude" name="latitude"  value="<?php _esc($latitude)?>"/>
                                        <input type="hidden" id="longitude" name="longitude" value="<?php _esc($longitude)?>"/>
                                    </div>
                                <?php } ?>

                                <div class="submit-field">
                                    <h5><?php _e("Phone Number") ?></h5>
                                    <div class="input-with-icon">
                                        <input class="with-border" type="text" name="phone" value="<?php _esc($phone)?>">
                                        <i class="icon-feather-phone"></i>
                                    </div>
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e("Fax") ?></h5>
                                    <div class="input-with-icon">
                                        <input class="with-border" type="text" name="fax" value="<?php _esc($fax)?>">
                                        <i class="icon-feather-printer"></i>
                                    </div>
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e("Email Address") ?></h5>
                                    <div class="input-with-icon">
                                        <input class="with-border" type="email" name="email" value="<?php _esc($email)?>">
                                        <i class="icon-feather-mail"></i>
                                    </div>
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e("Website") ?></h5>
                                    <div class="input-with-icon">
                                        <input class="with-border" type="url" name="website" value="<?php _esc($website)?>">
                                        <i class="icon-feather-link"></i>
                                    </div>
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e("Facebook") ?></h5>
                                    <div class="input-with-icon">
                                        <input class="with-border" type="url" name="facebook" value="<?php _esc($facebook)?>">
                                        <i class="icon-feather-facebook"></i>
                                    </div>
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e("Twitter") ?></h5>
                                    <div class="input-with-icon">
                                        <input class="with-border" type="url" name="twitter" value="<?php _esc($twitter)?>">
                                        <i class="icon-feather-twitter"></i>
                                    </div>
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e("Linkedin") ?></h5>
                                    <div class="input-with-icon">
                                        <input class="with-border" type="url" name="linkedin" value="<?php _esc($linkedin)?>">
                                        <i class="icon-feather-linkedin"></i>
                                    </div>
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e("Pinterest") ?></h5>
                                    <div class="input-with-icon">
                                        <input class="with-border" type="url" name="pinterest" value="<?php _esc($pinterest)?>">
                                        <i class="fa fa-pinterest-p"></i>
                                    </div>
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e("Youtube") ?></h5>
                                    <div class="input-with-icon">
                                        <input class="with-border" type="url" name="youtube" value="<?php _esc($youtube)?>">
                                        <i class="icon-feather-youtube"></i>
                                    </div>
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e("Instagram") ?></h5>
                                    <div class="input-with-icon">
                                        <input class="with-border" type="url" name="instagram" value="<?php _esc($instagram)?>">
                                        <i class="icon-feather-instagram"></i>
                                    </div>
                                </div>
                                <?php if($id != ""){ ?>
                                <input type="hidden" name="id" value="<?php _esc($id)?>">
                                <?php } ?>
                                <button type="submit" name="submit" class="button ripple-effect"><?php _e("Save") ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Row / End -->
            <link href="<?php _esc(TEMPLATE_URL);?>/css/select2.min.css" rel="stylesheet"/>
            <script src="<?php _esc(TEMPLATE_URL);?>/js/select2.min.js"></script>
            <script>
                /* Get and Bind cities */
                $('#jobcity').select2({
                    ajax: {
                        url: ajaxurl + '?action=searchCityFromCountry',
                        dataType: 'json',
                        delay: 50,
                        data: function (params) {
                            return {
                                q: params.term, /* search term */
                                page: params.page
                            };
                        },
                        processResults: function (data, params) {
                            /*
                             // parse the results into the format expected by Select2
                             // since we are using custom formatting functions we do not need to
                             // alter the remote JSON data, except to indicate that infinite
                             // scrolling can be used
                             */
                            params.page = params.page || 1;

                            return {
                                results: data.items,
                                pagination: {
                                    more: (params.page * 10) < data.totalEntries
                                }
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function (markup) { return markup; }, /* let our custom formatter work */
                    minimumInputLength: 2,
                    templateResult: function (data) {
                        return data.text;
                    },
                    templateSelection: function (data, container) {
                        return data.text;
                    }
                });
            </script>

            <?php
            if($config['post_address_mode']){
                if($config['map_type']=="google"){
                    ?>
                <link href="<?php _esc($config['site_url'])?>includes/assets/plugins/map/google/map-marker.css" type="text/css" rel="stylesheet">
                    <script type='text/javascript' src='<?php _esc($config['site_url'])?>includes/assets/plugins/map/google/jquery-migrate-1.2.1.min.js'></script>
                    <script type='text/javascript' src='//maps.google.com/maps/api/js?key=<?php _esc($config['gmap_api_key'])?>&#038;libraries=places%2Cgeometry&#038;ver=2.2.1'></script>
                    <script type='text/javascript' src='<?php _esc($config['site_url'])?>includes/assets/plugins/map/google/richmarker-compiled.js'></script>
                    <script type='text/javascript' src='<?php _esc($config['site_url'])?>includes/assets/plugins/map/google/markerclusterer_packed.js'></script>
                    <script type='text/javascript' src='<?php _esc($config['site_url'])?>includes/assets/plugins/map/google/gmapAdBox.js'></script>
                    <script type='text/javascript' src='<?php _esc($config['site_url'])?>includes/assets/plugins/map/google/maps.js'></script>
                    <script>
                        var element = "singleListingMap";
                        var getCity = false;
                        var _latitude = '<?php _esc($latitude)?>';
                        var _longitude = '<?php _esc($longitude)?>';
                        var color = '<?php _esc($map_color)?>';
                        var site_url = '<?php _esc($config['site_url'])?>';
                        var path = site_url;
                        simpleMap(_latitude, _longitude, element);

                        $('#jobcity').on('change', function() {
                            var data = $("#jobcity option:selected").val();
                            var custom_data= $("#jobcity").select2('data')[0];
                            var latitude = custom_data.latitude;
                            var longitude = custom_data.longitude;
                            var address = custom_data.text;
                            $('#latitude').val(latitude);
                            $('#longitude').val(longitude);
                            simpleMap(latitude, longitude, element, true, address);
                        });
                    </script>
                <?php }else{ ?>
                    <script>
                        var openstreet_access_token = '<?php _esc($config['openstreet_access_token'])?>';
                    </script>
                <link rel="stylesheet" href="<?php _esc($config['site_url'])?>includes/assets/plugins/map/openstreet/css/style.css">
                    <!-- Leaflet // Docs: https://leafletjs.com/ -->
                    <script src="<?php _esc($config['site_url'])?>includes/assets/plugins/map/openstreet/leaflet.min.js"></script>

                    <!-- Leaflet Maps Scripts (locations are stored in leaflet-quick.js) -->
                    <script src="<?php _esc($config['site_url'])?>includes/assets/plugins/map/openstreet/leaflet-markercluster.min.js"></script>
                    <script src="<?php _esc($config['site_url'])?>includes/assets/plugins/map/openstreet/leaflet-gesture-handling.min.js"></script>
                    <script src="<?php _esc($config['site_url'])?>includes/assets/plugins/map/openstreet/leaflet-quick.js"></script>

                    <!-- Leaflet Geocoder + Search Autocomplete // Docs: https://github.com/perliedman/leaflet-control-geocoder -->
                    <script src="<?php _esc($config['site_url'])?>includes/assets/plugins/map/openstreet/leaflet-autocomplete.js"></script>
                    <script src="<?php _esc($config['site_url'])?>includes/assets/plugins/map/openstreet/leaflet-control-geocoder.js"></script>
                    <script>
                        $('#jobcity').on('change', function() {
                            var data = $("#jobcity option:selected").val();
                            var custom_data= $("#jobcity").select2('data')[0];
                            var latitude = custom_data.latitude;
                            var longitude = custom_data.longitude;
                            console.log(custom_data);
                            var address = custom_data.text;
                            $('#latitude').val(latitude);
                            $('#longitude').val(longitude);
                            if (document.getElementById("singleListingMap") !== null && singleListingMap) {
                                $("#address-autocomplete").val(address);
                                var newLatLng = new L.LatLng(latitude, longitude);
                                singleListingMapMarker.setLatLng(newLatLng);
                                singleListingMap.flyTo(newLatLng, 10);
                            }
                        });
                    </script>
                <?php }
            }?>
            <?php include_once TEMPLATE_PATH.'/overall_footer_dashboard.php'; ?>
