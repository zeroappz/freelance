<?php
define("ROOTPATH", dirname(dirname(dirname(__DIR__))));
define("APPPATH", ROOTPATH."/php/");
require_once ROOTPATH . '/includes/autoload.php';
require_once ROOTPATH . '/includes/lang/lang_'.$config['lang'].'.php';
admin_session_start();
$pdo = ORM::get_db();

$info = ORM::for_table($config['db']['pre'].'payments')
    ->where('payment_id',$_GET['id'])
    ->find_one();
$status = $info['payment_install'];
$folder = $info['payment_folder'];
?>

<header class="slidePanel-header overlay">
    <div class="overlay-panel overlay-background vertical-align">
        <div class="service-heading">
            <h2><?php echo ucfirst($folder);?> - Settings</h2>
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
                    <form name="form2"  class="form form-horizontal" method="post" data-ajax-action="paymentEdit" id="sidePanel_form">
                        <div class="form-body">
                            <input type="hidden" name="id" value="<?php echo $_GET['id']?>">

                            <?php
                            if($folder == "paypal") {
                                ?>
                                <div class="form-group">
                                    <h4>Integration Steps:</h4>
                                    <ol>
                                        <li>Go to <a href="https://developer.paypal.com/" target="_blank">PayPal Developer Console</a> and Login to your account after clicking the Log into Dashboard button</li>
                                        <li>Go to <strong>REST API apps</strong> section and click the <strong>Create App</strong> button.</li>
                                        <li>Add your own details for the new app and create it.</li>
                                        <li>Switch to Live by clicking the button near your new App's Name.</li>
                                        <li>Copy the <strong>Client ID</strong> and <strong>Secret</strong> and paste below.</li>
                                        <li>Go to the newly created App in the Paypal Developer Console and click on the <strong>Add Webhook</strong> button.</li>
                                        <li>In the Webhook Url field, paste the Webhook Url <code><?php echo $config['site_url'].'webhook/paypal'?></code>.</li>
                                        <li>In the <strong>Event types</strong> field, check the <strong>Payment sale completed</strong> event and submit the Webhook.</li>
                                    </ol>
                                </div>
                                <?php
                            }elseif($folder == "stripe"){
                            ?>
                                <div class="form-group">
                                    <h4>Integration Steps:</h4>
                                    <ol>
                                        <li>Go to <a href="https://dashboard.stripe.com/">Stripe Dashboard</a> and Login to your account.</li>
                                        <li>Go to <a href="https://dashboard.stripe.com/account/apikeys">Stripe API Keys</a> page.</li>
                                        <li>Make sure your API keys are set to <strong>Live Mode</strong> so that you can accept real payments.</li>
                                        <li>Copy the <strong>Publishable key</strong> and <strong>Secret key</strong> and paste below.</li>
                                        <li>From the sidebar, under <strong>Developers</strong> click on <strong>Webhooks</strong> link.</li>
                                        <li>Click on the <strong>Add endpoint</strong> button</li>
                                        <li>In the <strong>Endpoint URL</strong> field, paste the Webhook Url <code><?php echo $config['site_url'].'webhook/stripe'?></code>.</li>
                                        <li>Select <strong>Version</strong> 2019-12-03.</li>
                                        <li>In the <strong>Events to send</strong> field, select the <strong>checkout.session.completed</strong>, <strong>invoice.paid</strong>, <strong>invoice.upcoming</strong>, <strong>invoice.payment_succeeded</strong> and click the <strong>Add endpoint</strong> button.</li>
                                        <li>Copy the <strong>Signing secret</strong> key and paste below in <strong>Webhook Secret</strong> field.</li>
                                    </ol>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Title:</label>
                                <div class="col-sm-6">
                                    <input name="title" type="text" class="form-control" value="<?php echo $info['payment_title']?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Turn On/Off</label>
                                <div class="col-sm-6">
                                    <select name="install" id="install" class="form-control">
                                        <option value="1" <?php if($status == '1') echo "selected"; ?>>On</option>
                                        <option value="0" <?php if($status == '0') echo "selected"; ?>>Off</option>
                                    </select>
                                </div>
                            </div>

                            <?php
                            if($folder == "paypal"){
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Live Mode/ Sandbox Mode </label>
                                    <div class="col-sm-6">
                                        <select name="paypal_sandbox_mode"  class="form-control">
                                            <option value="Yes" <?php if(get_option('paypal_sandbox_mode') == 'Yes') echo "selected"; ?>>Sandbox Test Mode</option>
                                            <option value="No" <?php if(get_option('paypal_sandbox_mode') == 'No') echo "selected"; ?>>Live Mode</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Payment Mode:</label>
                                    <div class="col-sm-6">
                                        <select name="paypal_payment_mode" class="form-control">
                                            <option value="one_time" <?php if(get_option('paypal_payment_mode') == 'one_time') echo "selected"; ?>>One Time</option>
                                            <option value="recurring" <?php if(get_option('paypal_payment_mode') == 'recurring') echo "selected"; ?>>Recurring</option>
                                            <option value="both" <?php if(get_option('paypal_payment_mode') == 'both') echo "selected"; ?>>Both</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Paypal API Client Id:</label>
                                    <div class="col-sm-6">
                                        <input name="paypal_api_client_id" type="text" class="form-control" value="<?php echo get_option('paypal_api_client_id')?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Paypal API Secret:</label>
                                    <div class="col-sm-6">
                                        <input name="paypal_api_secret" type="text" class="form-control" value="<?php echo get_option('paypal_api_secret')?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Paypal API WebHook Url:</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" value="<?php echo $config['site_url'].'webhook/paypal'?>" readonly>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            <?php
                            if($folder == "stripe"){
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Payment Mode:</label>
                                    <div class="col-sm-6">
                                        <select name="stripe_payment_mode" class="form-control">
                                            <option value="one_time" <?php if(get_option('stripe_payment_mode') == 'one_time') echo "selected"; ?>>One Time</option>
                                            <option value="recurring" <?php if(get_option('stripe_payment_mode') == 'recurring') echo "selected"; ?>>Recurring</option>
                                            <option value="both" <?php if(get_option('stripe_payment_mode') == 'both') echo "selected"; ?>>Both</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Stripe Publishable Key:</label>
                                    <div class="col-sm-6">
                                        <input name="stripe_publishable_key" type="text" class="form-control" value="<?php echo get_option('stripe_publishable_key')?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Stripe Secret Key:</label>
                                    <div class="col-sm-6">
                                        <input name="stripe_secret_key" type="text" class="form-control" value="<?php echo get_option('stripe_secret_key')?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Stripe Webhook Secret:</label>
                                    <div class="col-sm-6">
                                        <input name="stripe_webhook_secret" type="text" class="form-control" value="<?php echo get_option('stripe_webhook_secret')?>">
                                        <small>Leave empty if the version 2019-12-03 is not available.</small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Stripe WebHook Url:</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" value="<?php echo $config['site_url'].'webhook/stripe'?>" readonly>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>

                            <?php
                            if($folder == "ccavenue"){
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">CCAvenue Merchant key:</label>
                                    <div class="col-sm-6">
                                        <input name="CCAVENUE_MERCHANT_KEY" type="text" class="form-control" placeholder="Enter your CCAvenue Merchant key" value="<?php echo get_option('CCAVENUE_MERCHANT_KEY')?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">CCAvenue Access Code:</label>
                                    <div class="col-sm-6">
                                        <input name="CCAVENUE_ACCESS_CODE" type="text" class="form-control" placeholder="Enter your CCAvenue Access Code" value="<?php echo get_option('CCAVENUE_ACCESS_CODE')?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">CCAvenue Working Key:</label>
                                    <div class="col-sm-6">
                                        <input name="CCAVENUE_WORKING_KEY" type="text" class="form-control" placeholder="Enter your CCAvenue Working Key" value="<?php echo get_option('CCAVENUE_WORKING_KEY')?>">
                                    </div>
                                </div>
                            <?php
                            }
                            ?>

                            <?php
                            if($folder == "paytm"){
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Live Mode/ Sandbox Mode </label>
                                    <div class="col-sm-6">
                                        <select name="PAYTM_ENVIRONMENT"  class="form-control">
                                            <option value="TEST" <?php if(get_option('PAYTM_ENVIRONMENT') == 'TEST') echo "selected"; ?>>Sandbox Test Mode</option>
                                            <option value="PROD" <?php if(get_option('PAYTM_ENVIRONMENT') == 'PROD') echo "selected"; ?>>Live Mode</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Paytm Merchant key:</label>
                                    <div class="col-sm-6">
                                        <input name="PAYTM_MERCHANT_KEY" type="text" class="form-control" placeholder="Enter your Merchant key" value="<?php echo get_option('PAYTM_MERCHANT_KEY')?>">
                                        <code class="help-block">Change this constant's value with Merchant key downloaded from portal</code>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Paytm Merchant ID:</label>
                                    <div class="col-sm-6">
                                        <input name="PAYTM_MERCHANT_MID" type="text" class="form-control" placeholder="Enter your MID (Merchant ID)" value="<?php echo get_option('PAYTM_MERCHANT_MID')?>">
                                        <code class="help-block">Change this constant's value with MID (Merchant ID) received from Paytm</code>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Paytm Website name:</label>
                                    <div class="col-sm-6">
                                        <input name="PAYTM_MERCHANT_WEBSITE" type="text" class="form-control" placeholder="Enter your Website name" value="<?php echo get_option('PAYTM_MERCHANT_WEBSITE')?>">
                                        <code class="help-block">Change this constant's value with Website name received from Paytm</code>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>

                            <?php
                            if($folder == "paystack"){
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Paystack Secret Key:</label>
                                    <div class="col-sm-6">
                                        <input name="paystack_secret_key" type="text" class="form-control" placeholder="Enter your Paystack Secret Key" value="<?php echo get_option('paystack_secret_key')?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Paystack Public Key:</label>
                                    <div class="col-sm-6">
                                        <input name="paystack_public_key" type="text" class="form-control" placeholder="Enter your Paystack Public Key" value="<?php echo get_option('paystack_public_key')?>">
                                    </div>
                                </div>
                            <?php
                            }
                            ?>

                            <?php
                            if($folder == "payumoney"){
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Live Mode/ Test Mode </label>
                                    <div class="col-sm-6">
                                        <select name="payumoney_sandbox_mode"  class="form-control">
                                            <option value="live" <?php if(get_option('payumoney_sandbox_mode') == 'live') echo "selected"; ?>>Live Mode</option>
                                            <option value="test" <?php if(get_option('payumoney_sandbox_mode') == 'test') echo "selected"; ?>>Test Mode</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Payumoney Merchant ID:</label>
                                    <div class="col-sm-6">
                                        <input name="payumoney_merchant_id" type="text" class="form-control" placeholder="Enter your Payumoney Merchant ID" value="<?php echo get_option('payumoney_merchant_id')?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Payumoney Merchant Key:</label>
                                    <div class="col-sm-6">
                                        <input name="payumoney_merchant_key" type="text" class="form-control" placeholder="Enter your Payumoney Merchant Key" value="<?php echo get_option('payumoney_merchant_key')?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Payumoney Merchant Salt:</label>
                                    <div class="col-sm-6">
                                        <input name="payumoney_merchant_salt" type="text" class="form-control" placeholder="Enter your Payumoney Merchant Salt" value="<?php echo get_option('payumoney_merchant_salt')?>">
                                    </div>
                                </div>

                            <?php
                            }
                            ?>

                            <?php
                            if($folder == "2checkout"){
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Live Mode/ Test Mode </label>
                                    <div class="col-sm-6">
                                        <select name="2checkout_sandbox_mode" class="form-control">
                                            <option value="production" <?php if(get_option('2checkout_sandbox_mode') == 'production') echo "selected"; ?>>Live Mode</option>
                                            <option value="sandbox" <?php if(get_option('2checkout_sandbox_mode') == 'sandbox') echo "selected"; ?>>Test Mode</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">2Checkout Account Number:</label>
                                    <div class="col-sm-6">
                                        <input name="checkout_account_number" type="text" class="form-control" placeholder="Enter your 2Checkout Account Number" value="<?php echo get_option('checkout_account_number')?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Publishable Key:</label>
                                    <div class="col-sm-6">
                                        <input name="checkout_public_key" type="text" class="form-control" placeholder="Enter your 2Checkout Publishable Key." value="<?php echo get_option('checkout_public_key')?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Private API Key:</label>
                                    <div class="col-sm-6">
                                        <input name="checkout_private_key" type="text" class="form-control" placeholder="Enter your 2Checkout Private Key" value="<?php echo get_option('checkout_private_key')?>">
                                    </div>
                                </div>
                            <?php
                            }
                            ?>

                            <?php
                            if($folder == "moneybookers"){
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Skrill Merchant Id:</label>
                                    <div class="col-sm-6">
                                        <input name="skrill_merchant_id" type="text" class="form-control" placeholder="Enter your skrill(moneybookers) merchant id" value="<?php echo get_option('skrill_merchant_id')?>">
                                    </div>
                                </div>
                            <?php
                            }
                            ?>

                            <?php
                            if($folder == "nochex"){
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">NoChex Merchant Id:</label>
                                    <div class="col-sm-6">
                                        <input name="nochex_merchant_id" type="text" class="form-control" placeholder="Enter your NoChex Merchant Id" value="<?php echo get_option('nochex_merchant_id')?>">
                                    </div>
                                </div>
                            <?php
                            }
                            ?>

                            <?php
                            if($folder == "wire_transfer"){
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Bank Information :</label>
                                    <div class="col-sm-6">
                                        <textarea name="company_bank_info" rows="6" type="text" placeholder="Write Information about Bank transfer" class="form-control"><?php echo get_option('company_bank_info')?></textarea>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            <?php
                            if($folder == "cheque"){
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Cheque Information:</label>
                                    <div class="col-sm-6">
                                        <textarea name="company_cheque_info" rows="6" type="text" placeholder="Write Cheque Information" class="form-control"><?php echo get_option('company_cheque_info')?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Cheque Payable To:</label>
                                    <div class="col-sm-6">
                                        <input name="cheque_payable_to" type="text" class="form-control" placeholder="Payable To" value="<?php echo get_option('cheque_payable_to')?>">
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            <?php
                            if($folder == "mollie"){
                                ?>
                                <div class="form-group">
                                    <label for="mollie_api_key" class="col-sm-4 control-label">API Key</label>
                                    <div class="col-sm-6">
                                        <input id="mollie_api_key" class="form-control" type="text"
                                           name="mollie_api_key"
                                           value="<?php echo get_option('mollie_api_key')?>">
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                            <?php
                            if($folder == "iyzico"){
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Live Mode/ Sandbox Mode </label>
                                    <div class="col-sm-6">
                                        <select name="iyzico_sandbox_mode"  class="form-control">
                                            <option value="live" <?php if(get_option('iyzico_sandbox_mode') == 'live') echo "selected"; ?>>Live Mode</option>
                                            <option value="test" <?php if(get_option('iyzico_sandbox_mode') == 'test') echo "selected"; ?>>Test Mode</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">iyzico API Key:</label>
                                    <div class="col-sm-6">
                                        <input name="iyzico_api_key" type="text" class="form-control" placeholder="Enter your iyzico API Key" value="<?php echo get_option('iyzico_api_key')?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">iyzico Secret Key:</label>
                                    <div class="col-sm-6">
                                        <input name="iyzico_secret_key" type="text" class="form-control" placeholder="Enter your iyzico Secret Key" value="<?php echo get_option('iyzico_secret_key')?>">
                                    </div>
                                </div>


                                <?php
                            }
                            ?>

                            <?php
                            if($folder == "midtrans"){
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Live Mode/ Sandbox Mode </label>
                                    <div class="col-sm-6">
                                        <select name="midtrans_sandbox_mode"  class="form-control">
                                            <option value="live" <?php if(get_option('midtrans_sandbox_mode') == 'live') echo "selected"; ?>>Live Mode</option>
                                            <option value="test" <?php if(get_option('midtrans_sandbox_mode') == 'test') echo "selected"; ?>>Test Mode</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Midtrans Client Key:</label>
                                    <div class="col-sm-6">
                                        <input name="midtrans_client_key" type="text" class="form-control" placeholder="Enter your midtrans Client Key" value="<?php echo get_option('midtrans_client_key')?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Midtrans Server Key:</label>
                                    <div class="col-sm-6">
                                        <input name="midtrans_server_key" type="text" class="form-control" placeholder="Enter your midtrans Server Key" value="<?php echo get_option('midtrans_server_key')?>">
                                    </div>
                                </div>

                                <?php
                            }
                            ?>

                            <?php
                            if($folder == "paytabs"){
                                ?>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Paytabs Profile id:</label>
                                    <div class="col-sm-6">
                                        <input name="paytabs_profile_id" type="text" class="form-control" placeholder="Enter Your Paytabs Profile id" value="<?php echo get_option('paytabs_profile_id')?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Paytabs Server Key:</label>
                                    <div class="col-sm-6">
                                        <input name="paytabs_secret_key" type="text" class="form-control" placeholder="Enter your Paytabs Server Key" value="<?php echo get_option('paytabs_secret_key')?>">
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                            <?php
                            if($folder == "telr"){
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Live Mode/ Sandbox Mode </label>
                                    <div class="col-sm-6">
                                        <select name="telr_sandbox_mode"  class="form-control">
                                            <option value="live" <?php if(get_option('telr_sandbox_mode') == 'live') echo "selected"; ?>>Live Mode</option>
                                            <option value="test" <?php if(get_option('telr_sandbox_mode') == 'test') echo "selected"; ?>>Test Mode</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Telr Store ID:</label>
                                    <div class="col-sm-6">
                                        <input name="telr_store_id" type="text" class="form-control" placeholder="Enter Your Telr Store ID" value="<?php echo get_option('telr_store_id')?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Telr Auth Key:</label>
                                    <div class="col-sm-6">
                                        <input name="telr_authkey" type="text" class="form-control" placeholder="Enter your Telr Auth Key" value="<?php echo get_option('telr_authkey')?>">
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                            <?php
                            if($folder == "razorpay"){
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Razorpay API Key:</label>
                                    <div class="col-sm-6">
                                        <input name="razorpay_api_key" type="text" class="form-control" placeholder="Enter your Razorpay API Key" value="<?php echo get_option('razorpay_api_key')?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Razorpay Secret Key:</label>
                                    <div class="col-sm-6">
                                        <input name="razorpay_secret_key" type="text" class="form-control" placeholder="Enter your Razorpay Secret Key" value="<?php echo get_option('razorpay_secret_key')?>">
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                        </div>

                    </form>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
</div>