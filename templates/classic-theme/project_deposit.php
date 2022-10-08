<?php
overall_header(__("Deposit"));
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
        <h3><?php _e("Deposit") ?></h3>
        <!-- Breadcrumbs -->
        <nav id="breadcrumbs" class="dark">
            <ul>
                <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                <li><?php _e("Deposit") ?></li>
            </ul>
        </nav>
    </div>


    <!-- Row -->
    <div class="row">
        <!-- Dashboard Box -->
        <div class="col-xl-12">
            <div class="dashboard-box">
                <div class="headline">
                    <h3><i class="icon-material-baseline-notifications-none"></i> <?php _e("Deposit Amount to wallet") ?></h3>
                </div>
                <div class="content">
                    <div class="content with-padding">
                        <?php if($warning == "1"){ ?>
                            <span style="color:#FF0000;"><?php _e("Your balance is currently").'-'._esc($config['currency_sign'])._esc($balance).', '._e("You must bring your account back to a positive amount before you can use it.");?></span>

                        <?php }
                        if($warning == "0"){ _e("Choose from several methods to deposit funds"); }
                        if($error != ""){
                            echo '<div class="notification error">'._esc($error,false).'</div>';
                        } ?>
                        <p>
                            <span class="info no-mar"><i class="icon-info-sign"></i> <?php _e("Minimum deposit amount") ?> : <?php _esc(price_format($config['payment_minimum_deposit']))?></span><br>
                        </p>
                        <div class="table-responsive">
                            <form id="send" action="" name="form1" method="post">
                                <p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="amount"><?php _e("Deposit Amount") ?></label>
                                        <div class="input-with-icon margin-bottom-30">
                                            <input class="with-border" type="text" placeholder="<?php _e("Amount") ?>" name="amount">
                                            <i class="fa fa-money"></i>
                                        </div>
                                    </div>
                                </div>

                                <span class="info no-mar"><i class="icon-info-sign"></i>&nbsp; <?php _e("Currency") ?> : <?php _esc($config['currency_code'])?></span>
                                </p>
                                <button name="Submit" class="button" type="submit"><?php _e("Deposit") ?></button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Row / End -->
<?php include_once TEMPLATE_PATH.'/overall_footer_dashboard.php'; ?>