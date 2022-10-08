<?php
overall_header(__("Withdraw"));
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
        <h3><?php _e("Withdraw") ?></h3>
        <!-- Breadcrumbs -->
        <nav id="breadcrumbs" class="dark">
            <ul>
                <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                <li><?php _e("Withdraw") ?></li>
            </ul>
        </nav>
    </div>


    <!-- Row -->
    <div class="row">
        <!-- Dashboard Box -->
        <div class="col-xl-12">
            <div class="dashboard-box margin-top-0">
                <div class="headline">
                    <h3><i class="icon-material-baseline-notifications-none"></i> <?php _e("Withdraw") ?></h3>
                </div>
                <!-- Content Start -->
                <div class="content">
                    <div class="content with-padding">
                        <form name="form1" method="post" action="" id="send">
                            <p class="alert alert-info"><i class="icon-info-sign"></i> <?php _e("The requested amount will be deducted from your wallet and the amount will be blocked until it get approved or rejected by the administrator. Once its approved, the requested amount will be manually pay to you.") ?></p>

                            <p>
                                <span class="fs20 fbold"><?php _e("Withdraw Amount") ?> : <?php _esc($config['currency_sign'])?> </span>
                            </p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-with-icon margin-bottom-30">
                                        <input class="with-border" type="text" placeholder="<?php _e("Amount") ?>" name="amount">
                                        <i class="fa fa-money"></i>
                                    </div>
                                </div>
                            </div>

                            <p>
                                <span class="info no-mar"><i class="icon-info-sign"></i> <?php _e("Minimum withdraw amount") ?> : <?php _esc(price_format($config['payment_minimum_withdraw']))?></span><br>
                            </p>
                            <p>

                                <?php foreach ($payment_types as $payment) { ?>

                            <div style="min-height:20px; margin-top:10px">

                                <div class="radio">
                                    <input id="<?php _esc($payment['id']) ?>" name="payment_id" type="radio" value="<?php _esc($payment['id']) ?>">
                                    <label for="<?php _esc($payment['id']) ?>"><span class="radio-label"></span> <?php _esc($payment['title']) ?></label>
                                </div>

                            </div>

                            <?php } ?>
                            </p>
                            <div class="row">
                                <div class="col-md-6">
                                    <span><?php _e("Write here your payment id or payment details of selected payment gatways.") ?></span>
                                    <div class="margin-bottom-30">

                                        <textarea name="account_details" class="with-border" placeholder="<?php _e("Write Payment Details...") ?>"></textarea>
                                    </div>

                                </div>
                            </div>

                            <p class="margin-top-10">
                                <button name="Submit" class="button" type="submit"><?php _e("Withdraw") ?></button>
                            </p>
                            <p>&nbsp;</p>
                            <p class="fbold fs20"><?php _e("Widthdraw Requests") ?></p><p>
                            <div class="table-responsive">
                                <table id="js-table-list" class="basic-table dashboard-box-list">
                                    <tr>
                                        <th><?php _e("Requested On") ?></th>
                                        <th><?php _e("Amount") ?> (<?php _esc($config['currency_sign'])?>)</th>
                                        <th><?php _e("Status") ?></th>
                                    </tr>
                                    <tbody>
                                    <?php foreach ($withdraw as $info) { ?>
                                        <tr>
                                            <td><?php _esc($info['time']) ?></td>
                                            <td><?php _esc($config['currency_sign'])?><?php _esc($info['amount']) ?></td>
                                            <td><?php _esc($info['status']) ?></td>
                                        </tr>
                                    <?php } ?>

                                    <?php if($withdraw_count == "0"){ ?>
                                        <tr>
                                            <td colspan="3" class="text-center"><?php _e("No result found.") ?></td>
                                        </tr>
                                    <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Content End -->
            </div>
        </div>
    </div>
    <!-- Row / End -->
<?php include_once TEMPLATE_PATH.'/overall_footer_dashboard.php'; ?>