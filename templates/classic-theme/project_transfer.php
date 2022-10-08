<?php
overall_header(__(""));
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
                <h3><?php _e("Transfer") ?></h3>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("Transfer") ?></li>
                    </ul>
                </nav>
            </div>


            <!-- Row -->
            <div class="row">
                <!-- Dashboard Box -->
                <div class="col-xl-12">
                    <div class="dashboard-box">
                        <div class="headline">
                            <h3><i class="icon-material-baseline-notifications-none"></i> <?php _e("Transfer") ?></h3>
                        </div>
                        <!-- Content Start -->
                        <div class="content">
                            <div class="content with-padding">
                                <p class="alert alert-info"><i class="icon-info-sign"></i><span class="fbold"> Remember : </span>Once amount is transfered then will not be refund.</p>

                                <form id="send" action="" method="post" name="form1" style="width:70%">

                                    <p>
                                        <span class="fs26 fbold">Trasnsfer  Money to a Freelancer</span>
                                    </p>
                                    <p>
                                    <div class="input text required validation:{'rule1':{'rule':'notempty','message':'Required'},'rule2':{'rule':'alphaNumeric','message':'Must be a valid character'},'rule3':{'rule':['between',6,30],'message':'Must be between of 6 to 30 characters'}}">
                                        <label>{TYPE} <?php _e("Username") ?> : </label>
                                        <input type="text" name="username" id="username" value=""/>
                                    </div>
                                    </p>
                                    <p>
                                    <div class="input text required validation:{'rule1':{'rule':'notempty','message':'Required'},'rule2':{'rule':'numeric','message':'Should be numeric'}}">
                                        <label>Amount (<?php _esc($config['currency_sign'])?>) : </label>
                                        <input type="text" name="amount" id="amount" value=""/>
                                    </div>
                                    <br><span class="info no-mar"><i class="icon-info-sign"></i>&nbsp; Site Commission: <?php _esc($config['currency_sign'])?>0</span>
                                    </p>
                                    <p>
                                        <button name="Submit" class="sky button" type="submit"><?php _e("Send Payment") ?></button>
                                    </p>

                                </form>
                            </div>
                        </div>
                        <!-- Content End -->
                    </div>
                </div>
            </div>
            <!-- Row / End -->
<?php include_once TEMPLATE_PATH.'/overall_footer_dashboard.php'; ?>