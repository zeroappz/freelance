<?php
define("ROOTPATH", dirname(dirname(dirname(__DIR__))));
define("APPPATH", ROOTPATH."/php/");
require_once ROOTPATH . '/includes/autoload.php';
require_once ROOTPATH . '/includes/lang/lang_'.$config['lang'].'.php';
admin_session_start();
$pdo = ORM::get_db();

?>
<header class="slidePanel-header overlay">
    <div class="overlay-panel overlay-background vertical-align">
        <div class="service-heading">
            <h2>Add Tax</h2>
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
                    <form name="form2"  class="form form-horizontal" method="post" data-ajax-action="addTax" id="sidePanel_form">
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Internal Name</label>
                                <div class="col-sm-8">
                                    <input type="text" name="internal_name" value="" class="form-control">
                                    <p class="help-block">Only visible in the admin.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Name</label>
                                <div class="col-sm-8">
                                    <input type="text" name="name" value="" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Description</label>
                                <div class="col-sm-8">
                                    <input type="text" name="description" value="" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Tax Value</label>
                                <div class="col-sm-8">
                                    <input type="number" name="value" value="" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Value Type</label>
                                <div class="col-sm-8">
                                    <select name="value_type" class="form-control">
                                        <option value="percentage">Percentage</option>
                                        <option value="fixed">Fixed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Type</label>
                                <div class="col-sm-8">
                                    <select name="type" class="form-control">
                                        <option value="inclusive">Inclusive</option>
                                        <option value="exclusive">Exclusive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Billing for</label>
                                <div class="col-sm-8">
                                    <select id="billing_type" name="billing_type" class="form-control">
                                        <option value="personal">Personal</option>
                                        <option value="business">Business</option>
                                        <option value="both">Both</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Country</label>
                                <div class="col-sm-8">
                                    <select class="form-control js-select2" name="countries[]" multiple>
                                        <?php
                                        $country = get_country_list();
                                        foreach ($country as $value){
                                            echo '<option value="'.$value['code'].'">'.$value['asciiname'].'</option>';
                                        }
                                        ?>
                                    </select>
                                    <p class="help-block">Leave empty for all countries.</p>
                                </div>
                            </div>
                            <input type="hidden" name="submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
</div>
<script>
    $(function()
    {
        App.initHelpers('select2');
    });
</script>