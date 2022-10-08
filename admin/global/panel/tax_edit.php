<?php
define("ROOTPATH", dirname(dirname(dirname(__DIR__))));
define("APPPATH", ROOTPATH."/php/");
require_once ROOTPATH . '/includes/autoload.php';
require_once ROOTPATH . '/includes/lang/lang_'.$config['lang'].'.php';
admin_session_start();
$pdo = ORM::get_db();
$info = ORM::for_table($config['db']['pre'].'taxes')->find_one($_GET['id']);
?>
<header class="slidePanel-header overlay">
    <div class="overlay-panel overlay-background vertical-align">
        <div class="service-heading">
            <h2>Edit Tax</h2>
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
                    <form name="form2"  class="form form-horizontal" method="post" data-ajax-action="editTax" id="sidePanel_form">
                        <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Internal Name</label>
                                <div class="col-sm-8">
                                    <input type="text" name="internal_name" value="<?php echo $info['internal_name'] ?>" class="form-control">
                                    <p class="help-block">Only visible in the admin.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Name</label>
                                <div class="col-sm-8">
                                    <input type="text" name="name" value="<?php echo $info['name'] ?>" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Description</label>
                                <div class="col-sm-8">
                                    <input type="text" name="description" value="<?php echo $info['description'] ?>" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Tax Value</label>
                                <div class="col-sm-8">
                                    <input type="number" name="value" value="<?php echo $info['value'] ?>" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Value Type</label>
                                <div class="col-sm-8">
                                    <select name="value_type" class="form-control">
                                        <option value="percentage" <?php echo $info['value_type'] == 'percentage'? 'selected': ''; ?>>Percentage</option>
                                        <option value="fixed" <?php echo $info['value_type'] == 'fixed'? 'selected': ''; ?>>Fixed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Type</label>
                                <div class="col-sm-8">
                                    <select name="type" class="form-control">
                                        <option value="inclusive" <?php echo $info['type'] == 'inclusive'? 'selected': ''; ?>>Inclusive</option>
                                        <option value="exclusive" <?php echo $info['type'] == 'exclusive'? 'selected': ''; ?>>Exclusive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Billing for</label>
                                <div class="col-sm-8">
                                    <select id="billing_type" name="billing_type" class="form-control">
                                        <option value="personal" <?php echo $info['billing_type'] == 'personal'? 'selected': ''; ?>>Personal</option>
                                        <option value="business" <?php echo $info['billing_type'] == 'business'? 'selected': ''; ?>>Business</option>
                                        <option value="both" <?php echo $info['billing_type'] == 'both'? 'selected': ''; ?>>Both</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Country</label>
                                <div class="col-sm-8">
                                    <select class="form-control js-select2" name="countries[]" multiple>
                                        <?php
                                        $tax_countries = explode(',', $info['countries']);
                                        $country = get_country_list();
                                        foreach ($country as $value){
                                            echo '<option value="'.$value['code'].'" '. (in_array($value['code'], $tax_countries)? 'selected':'') .'>'.$value['asciiname'].'</option>';
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