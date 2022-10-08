<?php
define("ROOTPATH", dirname(dirname(dirname(__DIR__))));
define("APPPATH", ROOTPATH."/php/");
require_once ROOTPATH . '/includes/autoload.php';
require_once ROOTPATH . '/includes/lang/lang_'.$config['lang'].'.php';
admin_session_start();
$pdo = ORM::get_db();

$info = ORM::for_table($config['db']['pre'].'companies')->find_one($_GET['id']);

$item_id = $info['id'];
$item_title = $info['name'];
$item_description = nl2br(stripcslashes($info['description']));
$item_city = $info['city'];
$item_state = $info['state'];
$item_country = $info['country'];

$item_phone = $info['phone'];
$item_fax = $info['fax'];
$item_email = $info['email'];
$item_website = $info['website'];
$item_facebook = $info['facebook'];
$item_twitter = $info['twitter'];
$item_linkedin = $info['linkedin'];
$item_pinterest = $info['pinterest'];
$item_youtube = $info['youtube'];
$item_instagram = $info['instagram'];

?>
<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="../assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css" />

<header class="slidePanel-header overlay">
    <div class="overlay-panel overlay-background vertical-align">
        <div class="service-heading">
            <h2>Edit - <?php echo $item_title; ?></h2>
        </div>
        <div class="slidePanel-actions">
            <div class="btn-group-flat">
                <button type="button" class="btn btn-floating btn-warning btn-sm waves-effect waves-float waves-light margin-right-10" id="post_sidePanel_data"><i class="icon ion-android-done" aria-hidden="true"></i></button>
                <button type="button" class="btn btn-pure btn-inverse slidePanel-close icon ion-android-close font-size-20" aria-hidden="true"></button>
            </div>
        </div>
    </div>
</header>
<div class="slidePanel-inner">
    <div class="panel-body">
        <!-- /.row -->
        <div class="row">
            <div class="col-sm-12">

                <div class="white-box">
                    <div id="post_error"></div>
                    <form name="form2"  class="form form-horizontal" method="post" data-ajax-action="companyEdit" id="sidePanel_form">
                        <div class="form-body">
                            <input type="hidden" name="id" value="<?php echo $item_id ?>">

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Name:</label>
                                <div class="col-sm-9">
                                    <input name="title" type="text" class="form-control" value="<?php echo $item_title ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Description:</label>
                                <div class="col-sm-9">
                                    <textarea name="content" <?php if($config['post_desc_editor'] == 1){ echo 'id="pageContent"'; } ?>  type="text" class="form-control" rows="6"><?php echo de_sanitize($item_description) ?></textarea>
                                    <p class="help-block">Html tags are allow.</p>
                                </div>
                            </div>

                            <!-- Select2 -->
                            <!-- Select2 (.js-select2 class is initialized in App() -> uiHelperSelect2()) -->
                            <!-- For more info and examples please check https://github.com/select2/select2 -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Country</label>
                                <div class="col-sm-9">
                                    <select name="country" class="form-control js-select2" id="country" data-ajax-action="getStateByCountryID"  data-placeholder="Select country..">
                                        <option></option><!-- Required for data-placeholder attribute to work with Chosen plugin -->
                                        <?php $country = get_country_list($item_country);
                                        foreach ($country as $value){
                                            echo '<option value="'.$value['code'].'" '.$value['selected'].'>'.$value['name'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">region</label>
                                <div class="col-sm-9">
                                    <select name="state" id="state" class="form-control js-select2" data-ajax-action="getCityByStateID" data-placeholder="Select region..">
                                        <option value="<?php echo $item_state ?>" checked><?php echo get_stateName_by_id($item_state) ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">City</label>
                                <div class="col-sm-9">
                                    <select name="city" id="city" class="form-control js-select2" data-placeholder="Select city..">
                                        <option value="<?php echo $item_city ?>" checked><?php echo get_cityName_by_id($item_city) ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Phone:</label>
                                <div class="col-sm-9">
                                    <input name="phone" type="text" class="form-control" value="<?php echo $item_phone ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Fax:</label>
                                <div class="col-sm-9">
                                    <input name="fax" type="text" class="form-control" value="<?php echo $item_fax ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Email:</label>
                                <div class="col-sm-9">
                                    <input name="email" type="text" class="form-control" value="<?php echo $item_email ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Website:</label>
                                <div class="col-sm-9">
                                    <input name="website" type="text" class="form-control" value="<?php echo $item_website ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Facebook:</label>
                                <div class="col-sm-9">
                                    <input name="facebook" type="text" class="form-control" value="<?php echo $item_facebook ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Twitter:</label>
                                <div class="col-sm-9">
                                    <input name="twitter" type="text" class="form-control" value="<?php echo $item_twitter ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">LinkedIn:</label>
                                <div class="col-sm-9">
                                    <input name="linkedin" type="text" class="form-control" value="<?php echo $item_linkedin ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Pinterest:</label>
                                <div class="col-sm-9">
                                    <input name="pinterest" type="text" class="form-control" value="<?php echo $item_pinterest ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Youtube:</label>
                                <div class="col-sm-9">
                                    <input name="youtube" type="text" class="form-control" value="<?php echo $item_youtube ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Instagram:</label>
                                <div class="col-sm-9">
                                    <input name="instagram" type="text" class="form-control" value="<?php echo $item_instagram ?>">
                                </div>
                            </div>


                        </div>

                    </form>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
</div>

<script>
    $("#category").change(function(){
        var catid = $(this).val();
        var action = $(this).data('ajax-action');
        var data = { action: action, catid: catid };
        $.ajax({
            type: "POST",
            url: ajaxurl+"?action="+action,
            data: data,
            success: function(result){
                $("#sub_category").html(result);
            }
        });
    });

    $("#country").change(function () {
        var id = $(this).val();
        var action = $(this).data('ajax-action');
        var data = {action: action, id: id};
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: data,
            success: function (result) {
                $("#state").html(result);
                $("#state").select2();
                $("#city").html('');
                $("#city").select2();
            }
        });
    });

    $("#state").change(function () {
        var id = $(this).val();
        var action = $(this).data('ajax-action');
        var data = {action: action, id: id};
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: data,
            success: function (result) {
                $("#city").html(result);
                $("#city").select2();
            }
        });
    });

    jQuery(function($) {
        getsubcat("<?php echo $item_catid; ?>","getsubcatbyid","<?php echo $item_subcatid; ?>");
        getcountryToStateSelected("<?php echo $item_country; ?>","getStateByCountryID","<?php echo $item_state; ?>");
        getCitySelected("<?php echo $item_state; ?>","getCityByStateID","<?php echo $item_city; ?>");
    });
    //$(".select2").select2();
</script>

<!-- Page JS Code -->


<script src="../assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script>
    $(function()
    {
        // Init page helpers (BS Datepicker + BS Colorpicker + Select2 + Masked Input + Tags Inputs plugins)
        App.initHelpers(['datepicker', 'select2']);
    });
</script>

<?php
if($config['post_desc_editor'] == 1)
{
    ?>
    <script src="../assets/js/plugins/tinymce/tinymce.min.js"></script>
    <script>
        $(document).ready(function() {
            tinymce.init({
                selector: '#pageContent',
                plugins: 'quickbars image lists code table codesample',
                toolbar: 'blocks | forecolor backcolor | bold italic underline strikethrough | link image blockquote codesample | align bullist numlist | code ',
            });
        });
    </script>

<?php } ?>

<!-- Page JS Plugins -->
