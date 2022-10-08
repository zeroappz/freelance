<?php
define("ROOTPATH", dirname(dirname(dirname(__DIR__))));
define("APPPATH", ROOTPATH."/php/");
require_once ROOTPATH . '/includes/autoload.php';
require_once ROOTPATH . '/includes/lang/lang_'.$config['lang'].'.php';
admin_session_start();
$pdo = ORM::get_db();

switch ($_GET['id']){
    case 'free':
        $info = json_decode(get_option('free_membership_plan'), true);
        $settings = $info['settings'];
        break;
    case 'trial':
        $info = json_decode(get_option('trial_membership_plan'), true);
        $settings = $info['settings'];
        break;
    default:
        $info = ORM::for_table($config['db']['pre'].'plans')
            ->where('id', $_GET['id'])
            ->find_one();
        $settings = json_decode($info['settings'],true);
        break;
}

?>

<header class="slidePanel-header overlay">
    <div class="overlay-panel overlay-background vertical-align">
        <div class="service-heading">
            <h2>Edit Plan</h2>
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
                    <form name="form2"  class="form" method="post" data-ajax-action="editMembershipPlan" id="sidePanel_form">
                        <div class="form-body">
                            <input type="hidden" name="id" value="<?php echo $_GET['id']?>">

                            <div class="row form-group">
                                <label class="col-sm-4 control-label">Activate</label>
                                <div class="col-sm-8">
                                    <label class="css-input switch switch-sm switch-success">
                                        <input  name="active" type="checkbox" value="1" <?php if($info['status'] == '1') echo "checked"; ?> /><span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-4 control-label">Plan Name</label>
                                <div class="col-sm-8">
                                    <input name="name" type="Text" class="form-control" placeholder="Plan Name" value="<?php echo stripslashes($info['name']);?>">
                                </div>
                            </div>
                            <div class="row form-group hidden">
                                <label class="col-sm-4 control-label">Plan Badge</label>
                                <div class="col-sm-8">
                                    <input name="badge" type="text" class="form-control" placeholder="Plan Badge"  value="<?php echo stripslashes($info['badge']);?>">
                                    <p class="help-block">Paste Image Url, This badge will display in user profile after username.</p>
                                </div>
                            </div>
                            <?php if($_GET['id'] != 'free' && $_GET['id'] != 'trial'){ ?>
                                <div class="row form-group">
                                    <label class="col-sm-4 control-label">Monthly Price</label>
                                    <div class="col-sm-8">
                                        <input name="monthly_price" type="number" class="form-control" id="monthly_price" placeholder="Monthly Price"  value="<?php echo stripslashes($info['monthly_price']);?>">
                                        <p class="help-block">Set 0 to disable it.</p>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-sm-4 control-label">Annual Price</label>
                                    <div class="col-sm-8">
                                        <input name="annual_price" type="number" class="form-control" id="annual_price" placeholder="Annual Price"  value="<?php echo stripslashes($info['annual_price']);?>">
                                        <p class="help-block">Set 0 to disable it.</p>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-sm-4 control-label">Lifetime Price</label>
                                    <div class="col-sm-8">
                                        <input name="lifetime_price" type="number" class="form-control" id="lifetime_price" placeholder="Lifetime Price"  value="<?php echo stripslashes($info['lifetime_price']);?>">
                                        <p class="help-block">Set 0 to disable it.</p>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-sm-4 control-label">Recommended</label>
                                    <div class="col-sm-8">
                                        <label class="css-input switch switch-sm switch-success">
                                            <input  name="recommended" type="checkbox" value="yes" <?php if($info['recommended'] == 'yes') echo "checked"; ?> /><span></span>
                                        </label>
                                    </div>
                                </div>
                            <?php }
                            if($_GET['id'] == 'trial'){ ?>
                                <div class="row form-group">
                                    <label class="col-sm-4 control-label">Days</label>
                                    <div class="col-sm-8">
                                        <input name="days" type="number" class="form-control" id="days" placeholder="Days"  value="<?php echo stripslashes($info['days']);?>">
                                        <p class="help-block">The number of days that the trial plan can be used.</p>
                                    </div>
                                </div>
                            <?php } ?>
                            <h4>Plan Settings</h4>
                            <hr>
                            <div class="row form-group">
                                <label class="col-sm-4 control-label">Project Commision for Employer</label>
                                <div class="col-sm-6">
                                    <input name="employer_commission" type="number" class="form-control" value="<?php echo $settings['employer_commission'];?>">
                                    <p class="help-block">Comission in percentage of project bid.</p>
                                </div>
                            </div>

                            <div class="row form-group">
                                <label class="col-sm-4 control-label">Project Commision for Freelancer</label>
                                <div class="col-sm-6">
                                    <input name="freelancer_commission" type="number" class="form-control" value="<?php echo $settings['freelancer_commission'];?>">
                                    <p class="help-block">Comission in percentage of project bid.</p>
                                </div>
                            </div>

                            <div class="row form-group">
                                <label class="col-sm-4 control-label">Bids for Freelancer</label>
                                <div class="col-sm-6">
                                    <input name="bids" type="number" class="form-control" value="<?php echo $settings['bids'];?>">
                                    <p class="help-block">This is the limit on bids for Freelancer. For unlimited enter 999</p>
                                </div>
                            </div>

                            <div class="row form-group">
                                <label class="col-sm-4 control-label">Skills Limit</label>
                                <div class="col-sm-6">
                                    <input name="skills" type="number" class="form-control" value="<?php echo $settings['skills'];?>">
                                    <p class="help-block">This is the limit on skills add for Freelancer. For unlimited enter 999</p>
                                </div>
                            </div>

                            <div class="row form-group">
                                <label class="col-sm-4 control-label">Job Posting Limit(Max No)</label>
                                <div class="col-sm-6">
                                    <input name="ad_limit" type="number" class="form-control" id="ad_limit" placeholder="Ad Limit" value="<?php echo stripslashes($settings['ad_limit']);?>" min="1" max="3">
                                    <p class="help-block">For unlimited ads enter 999</p>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-4 control-label">Job Expire in days</label>
                                <div class="col-sm-6">
                                    <input name="ad_duration" type="Text" class="form-control" id="ad_duration" value="<?php echo stripslashes($settings['ad_duration']);?>">
                                    <p class="help-block">Duration to show posts (in days).</p>
                                </div>

                            </div>
                            <div class="row form-group">
                                <label class="col-sm-4 control-label">Featured Badge Fee</label>
                                <div class="col-sm-6">
                                    <input name="featured_project_fee" type="number" class="form-control" id="featured_project_fee" value="<?php echo stripslashes($settings['featured_project_fee']);?>">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-4 control-label">Featured Duration</label>
                                <div class="col-sm-6">
                                    <input name="featured_duration" type="Text" class="form-control" id="featured_duration" value="<?php echo stripslashes($settings['featured_duration']);?>">
                                    <p class="help-block">Duration to show Featured Badge (in days).</p>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-4 control-label">Urgent Badge Fee</label>
                                <div class="col-sm-6">
                                    <input name="urgent_project_fee" type="number" class="form-control" id="urgent_project_fee" placeholder="Urgent Ad Fee" value="<?php echo stripslashes($settings['urgent_project_fee']);?>">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-4 control-label">Urgent Duration</label>
                                <div class="col-sm-6">
                                    <input name="urgent_duration" type="Text" class="form-control" id="urgent_duration" value="<?php echo stripslashes($settings['urgent_duration']);?>">
                                    <p class="help-block">Duration to show Urgent Badge (in days).</p>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-4 control-label">Highlight Badge Fee</label>
                                <div class="col-sm-6">
                                    <input name="highlight_project_fee" type="number" class="form-control" id="highlight_project_fee" placeholder="Highlight Ad Fee" value="<?php echo stripslashes($settings['highlight_project_fee']);?>">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-4 control-label">Highlight Duration</label>
                                <div class="col-sm-6">
                                    <input name="highlight_duration" type="Text" class="form-control" id="highlight_duration" value="<?php echo stripslashes($settings['highlight_duration']);?>">
                                    <p class="help-block">Duration to show Highlight Badge (in days).</p>
                                </div>
                            </div>
                            <h3 class="heading">Package Option (Check it if you want to allow)</h3>
                            <div class="row form-group">
                                <div class="inside" style="padding: 0 20px">
                                    <label class="css-input css-checkbox css-checkbox-primary">
                                        <input type="checkbox" name="top_search_result" value="yes" <?php if($settings['top_search_result'] == 'yes') echo "checked"; ?>><span></span>
                                        Top in search results and category.
                                    </label>
                                    <br>
                                    <label class="css-input css-checkbox css-checkbox-primary">
                                        <input type="checkbox" name="show_on_home" value="yes" <?php if($settings['show_on_home'] == 'yes') echo "checked"; ?>><span></span>
                                        Show ad on home page premium ad section.
                                    </label>
                                    <br>
                                    <label class="css-input css-checkbox css-checkbox-primary">
                                        <input type="checkbox" name="show_in_home_search" value="yes" <?php if($settings['show_in_home_search'] == 'yes') echo "checked"; ?>><span></span>
                                        Show ad on home page search result list.
                                    </label>

                                </div>
                            </div>
                            <?php
                            $plan_custom = ORM::for_table($config['db']['pre'].'plan_options')
                                ->where('active', 1)
                                ->order_by_asc('position')
                                ->find_many();
                            if(!empty($plan_custom)) {?>
                                <h4>Custom Settings</h4>
                                <hr>
                                <?php
                                foreach ($plan_custom as $key => $custom) {
                                    if(!empty($custom['title']) && trim($custom['title']) != '') {
                                        ?>
                                        <div class="row form-group">
                                            <label class="col-sm-4 control-label"><?php echo $custom['title']; ?></label>
                                            <div class="col-sm-8">
                                                <label class="css-input switch switch-sm switch-success">
                                                    <input name="<?php echo 'custom_'.$custom['id'] ?>" type="checkbox" value="1" <?php echo (isset($settings['custom'][$custom['id']]) && $settings['custom'][$custom['id']] == '1')?'checked':'' ?>/><span></span>
                                                </label>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                            ?>
                            <h4>Taxes</h4>
                            <hr>
                            <div class="row form-group">
                                <label class="col-sm-4 control-label">Select Taxes</label>
                                <div class="col-sm-8">
                                    <select class="form-control js-select2" name="taxes[]" multiple>
                                        <?php
                                        $plan_taxes = explode(',', $info['taxes_ids']);
                                        $taxes = ORM::for_table($config['db']['pre'].'taxes')
                                            ->find_many();
                                        foreach ($taxes as $tax){
                                            $value = ($tax['value_type'] == 'percentage' ? (float) $tax['value'] .'%' : price_format($tax['value']));
                                            echo '<option value="'.$tax['id'].'" '. (in_array($tax['id'], $plan_taxes)? 'selected':'') .'>'.$tax['name'].' ('.$value.')</option>';
                                        }
                                        ?>
                                    </select>
                                    <p class="help-block">Select taxes for this plan.</p>
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
        // Init page helpers (BS Datepicker + BS Colorpicker + Select2 + Masked Input + Tags Inputs plugins)
        App.initHelpers('select2');
    });
</script>
