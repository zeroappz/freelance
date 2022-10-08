<?php
overall_header(__("Membership Plan"));
?>
<!-- Titlebar
================================================== -->
<div id="titlebar" class="gradient">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?php _e("Membership Plan") ?></h2>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("Membership Plan") ?></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Page Content
================================================== -->
<div class="container">
    <div class="row">
        <div class="col-xl-12">
            <form name="form1" method="post">
                <div class="billing-cycle-radios margin-bottom-70">
                    <?php
                    if($total_monthly){
                        ?>
                        <div class="radio billed-monthly-radio">
                            <input id="radio-monthly" name="billed-type" type="radio" value="monthly" checked="">
                            <label for="radio-monthly"><span class="radio-label"></span> <?php _e("Monthly") ?></label>
                        </div>
                        <?php
                    }
                    if($total_annual){
                        ?>
                        <div class="radio billed-yearly-radio">
                            <input id="radio-yearly" name="billed-type" type="radio" value="yearly">
                            <label for="radio-yearly"><span class="radio-label"></span> <?php _e("Yearly") ?></label>
                        </div>
                        <?php
                    }
                    if($total_lifetime){
                        ?>
                        <div class="radio billed-lifetime-radio">
                            <input id="radio-lifetime" name="billed-type" type="radio" value="lifetime">
                            <label for="radio-lifetime"><span class="radio-label"></span> <?php _e("Lifetime") ?></label>
                        </div>
                    <?php } ?>
                </div>
                <!-- Pricing Plans Container -->
                <div class="pricing-plans-container">
                    <?php
                    foreach($sub_types as $plan){
                        ?>
                        <!-- Plan -->
                        <div class='pricing-plan <?php if(isset($plan['recommended']) && $plan['recommended']=="yes"){ echo 'recommended';} ?>'>

                            <?php
                            if(isset($plan['recommended']) && $plan['recommended']=="yes"){
                                echo '<div class="recommended-badge">'.__("Recommended").'</div> ';
                            }
                            ?>
                            <h3><?php _esc($plan['title'])?></h3>
                            <?php
                            if($plan['id']=="free" || $plan['id']=="trial"){
                                ?>
                                <div class="pricing-plan-label"><strong>
                                        <?php
                                        if($plan['id']=="free")
                                            _e("Free");
                                        else
                                            _e("Trial");
                                        ?>
                                    </strong></div>

                                <?php
                            }
                            else{
                                if($total_monthly != 0)
                                    echo '<div class="pricing-plan-label billed-monthly-label"><strong>'._esc($plan['monthly_price'],false).'</strong>/ '.__("Monthly").'</div>';
                                if($total_annual != 0)
                                    echo '<div class="pricing-plan-label billed-yearly-label"><strong>'._esc($plan['annual_price'],false).'</strong>/ '.__("Yearly").'</div>';
                                if($total_lifetime != 0)
                                    echo '<div class="pricing-plan-label billed-lifetime-label"><strong>'._esc($plan['lifetime_price'],false).'</strong>/ '.__("Lifetime").'</div>';
                            }
                            ?>

                            <div class="pricing-plan-features">
                                <strong><?php _e("Features of") ?> <?php _esc($plan['title'])?></strong>
                                <ul>
                                    <?php if($usertype == "user"){ ?>
                                        <li><?php _e("Project Fee") ?> <?php _esc($plan['freelancer_commission'])?>%</li>
                                        <li><?php _esc($plan['bids'])?> <?php _e("Bids") ?></li>
                                        <li><?php _esc($plan['skills'])?> <?php _e("Skills") ?></li>
                                    <?php }else{ ?>
                                        <li><?php _e("Project Fee") ?> <?php _esc($plan['employer_commission'])?>%</li>
                                        <li><?php _e("Job Post Limit") ?> <?php _esc($plan['limit'])?></li>
                                        <li><?php _e("Job expiry in") ?> <?php _esc($plan['duration'])?> <?php _e("days") ?></li>
                                        <li><?php _e("Featured badge fee") ?> <?php _esc($config['currency_sign'])?><?php _esc($plan['featured_fee'])?> <?php _e("for") ?> <?php _esc($plan['featured_duration'])?> <?php _e("days") ?></li>
                                        <li>
                                            <?php _e("Urgent badge fee") ?> <?php _esc($config['currency_sign'])?><?php _esc($plan['urgent_fee'])?> <?php _e("for") ?> <?php _esc($plan['urgent_duration'])?> <?php _e("days") ?>
                                        </li>
                                        <li>
                                            <?php _e("Highlight badge fee") ?> <?php _esc($config['currency_sign'])?><?php _esc($plan['highlight_fee'])?> <?php _e("for") ?> <?php _esc($plan['highlight_duration'])?> <?php _e("days") ?>
                                        </li>
                                        <li>
                                            <?php
                                            if($plan['top_search_result'] == "yes") {
                                                echo '<span class="icon-text yes"><i class="icon-feather-check-circle margin-right-2"></i></span>';
                                            }else{
                                                echo '<span class="icon-text no"><i class="icon-feather-x-circle margin-right-2"></i></span>';
                                            }
                                            _e("Top in search results and category");
                                            ?>
                                        </li>
                                        <li>
                                            <?php
                                            if($plan['show_on_home'] == "yes") {
                                                echo '<span class="icon-text yes"><i class="icon-feather-check-circle margin-right-2"></i></span>';
                                            }else{
                                                echo '<span class="icon-text no"><i class="icon-feather-x-circle margin-right-2"></i></span>';
                                            }
                                            _e("Show job on home page premium job section");
                                            ?>
                                        </li>
                                        <li>
                                            <?php
                                            if($plan['show_in_home_search'] == "yes") {
                                                echo '<span class="icon-text yes"><i class="icon-feather-check-circle margin-right-2"></i></span>';
                                            }else{
                                                echo '<span class="icon-text no"><i class="icon-feather-x-circle margin-right-2"></i></span>';
                                            }
                                            _e("Show job on home page search result list");
                                            ?>
                                        </li>
                                    <?php } ?>

                                    <?php _esc($plan['custom_settings'])?>
                                </ul>
                            </div>

                            <?php
                            if($plan['Selected'] == 0){
                                echo '<button type="submit" class="button full-width margin-top-20 ripple-effect" name="upgrade" value="'._esc($plan['id'],false).'">'.__("Upgrade").'</button>';
                            }
                            if($plan['Selected'] == 1){
                                echo '<a href="javascript:void(0);" class="button full-width margin-top-20 ripple-effect">'.__("Current Plan").'</a>';
                            }
                            ?>
                        </div>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="margin-top-80"></div>
<?php
overall_footer();
?>