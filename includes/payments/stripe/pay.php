<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

include 'stripe-php/init.php';

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');


// manually set action for stripe payments
if (empty($action)) {
    $action = 'stripe_payment';
}

$currency = $config['currency_code'];
$user_id = $_SESSION['user']['id'];
$code = '';

if (isset($access_token)) {
    $payment_type = $_SESSION['quickad'][$access_token]['payment_type'];

    $title = $_SESSION['quickad'][$access_token]['name'];
    $total = $_SESSION['quickad'][$access_token]['amount'];

    if($payment_type == "subscr") {
        $currency = $config['currency_code'];
        $user_id = $_SESSION['user']['id'];

        $base_amount = $_SESSION['quickad'][$access_token]['base_amount'];
        $plan_interval = $_SESSION['quickad'][$access_token]['plan_interval'];
        $payment_mode = $_SESSION['quickad'][$access_token]['payment_mode'];
        $package_id = $_SESSION['quickad'][$access_token]['sub_id'];
        $taxes_ids = isset($_SESSION['quickad'][$access_token]['taxes_ids']) ? $_SESSION['quickad'][$access_token]['taxes_ids'] : null;

        if ($plan_interval == 'LIFETIME') {
            $payment_mode = 'one_time';
        }

        $cancel_url = $link['PAYMENT']."/?access_token=".$access_token."&status=cancel";

        $stripe_secret_key = get_option('stripe_secret_key');
        $stripe_publishable_key = get_option('stripe_publishable_key');
    }else{
        $plan_interval = 'Order';
        $payment_mode = 'one_time';
        $order_id = $_SESSION['quickad'][$access_token]['order_id'];
        $restaurant_id = $_SESSION['quickad'][$access_token]['restaurant_id'];
        $restaurant = ORM::for_table($config['db']['pre'] . 'restaurant')
            ->find_one($restaurant_id);

        $userdata = get_user_data(null, $restaurant['user_id']);
        $currency = !empty($userdata['currency'])?$userdata['currency']:get_option('currency_code');

        $cancel_url = $link['PAYMENT']."/?access_token=".$access_token;

        $stripe_secret_key = get_restaurant_option($restaurant_id,'restaurant_stripe_secret_key');
        $stripe_publishable_key = get_restaurant_option($restaurant_id,'restaurant_stripe_publishable_key');
    }
}

if (!empty($action)) {
    switch ($action) {
        case 'stripe_payment':

            /* Initiate Stripe */
            \Stripe\Stripe::setApiKey($stripe_secret_key);

            $stripe_formatted_price = in_array($currency, ['MGA', 'BIF', 'CLP', 'PYG', 'DJF', 'RWF', 'GNF', 'UGX', 'JPY', 'VND', 'VUV', 'XAF', 'KMF', 'KRW', 'XOF', 'XPF']) ? number_format($total, 0, '.', '') : number_format($total, 2, '.', '') * 100;

            switch ($payment_mode) {
                case 'one_time':
                    if($payment_type == "subscr") {
                        $meta_data = array(
                            'user_id' => $user_id,
                            'package_id' => $package_id,
                            'payment_frequency' => $plan_interval,
                            'base_amount' => $base_amount,
                            'taxes_ids' => $taxes_ids
                        );
                    }else{
                        $meta_data = array(
                            'order_id' => $order_id,
                            'restaurant_id' => $restaurant_id,
                            'amount' => $total
                        );
                    }

                    try {
                        $stripe_session = \Stripe\Checkout\Session::create(array(
                            'payment_method_types' => array('card'),
                            'line_items' => array(
                                array(
                                    'name' => $title,
                                    'description' => $plan_interval,
                                    'amount' => $stripe_formatted_price,
                                    'currency' => $currency,
                                    'quantity' => 1,
                                )
                            ),
                            'metadata' => $meta_data,
                            'success_url' => $link['PAYMENT'] . "/?access_token=" . $access_token . "&i=stripe&action=stripe_ipn",
                            'cancel_url' => $cancel_url,
                        ));
                    } catch (Exception $exception) {
                        payment_fail_save_detail($access_token);
                        payment_error("error", $exception->getMessage(), $access_token);
                    }
                    break;

                case 'recurring':

                    try {
                        $stripe_product = \Stripe\Product::retrieve($package_id);
                    } catch (\Exception $exception) {

                    }

                    if(!isset($stripe_product)) {
                        try {
                            $stripe_product = \Stripe\Product::create(array(
                                'id' => $package_id,
                                'name' => $title,
                                'type' => 'service',
                            ));
                        } catch (Exception $exception) {
                            payment_fail_save_detail($access_token);
                            payment_error("error", $exception->getMessage(), $access_token);
                        }
                    }

                    $stripe_plan_id = $package_id . '_' . $plan_interval . '_' . $stripe_formatted_price . '_' . $currency;

                    try {
                        $stripe_plan = \Stripe\Plan::retrieve($stripe_plan_id);
                    } catch (\Exception $exception) {
                    }

                    if(!isset($stripe_plan)) {
                        try {
                            $stripe_plan = \Stripe\Plan::create([
                                'amount' => $stripe_formatted_price,
                                'interval' => $plan_interval == 'MONTHLY' ? 'month' : 'year',
                                'product' => $stripe_product->id,
                                'currency' => $currency,
                                'id' => $stripe_plan_id,
                            ]);
                        } catch (\Exception $exception) {
                            payment_fail_save_detail($access_token);
                            payment_error("error",$exception->getMessage(),$access_token);
                        }
                    }

                    try {
                        $stripe_session = \Stripe\Checkout\Session::create(array(
                            'payment_method_types' => array('card'),
                            'subscription_data' => array(
                                'items' => array(
                                    array('plan' => $stripe_plan->id)
                                ),
                                'metadata' => array(
                                    'user_id' => $user_id,
                                    'package_id' => $package_id,
                                    'payment_frequency' => $plan_interval,
                                    'code' => $code
                                ),
                            ),
                            'metadata' => array(
                                'user_id' => $user_id,
                                'package_id' => $package_id,
                                'payment_frequency' => $plan_interval,
                                'base_amount' => $base_amount,
                                'taxes_ids' => $taxes_ids
                            ),
                            'success_url' => $link['PAYMENT'] . "/?access_token=" . $access_token . "&i=stripe&action=stripe_ipn",
                            'cancel_url' => $link['PAYMENT'] . "/?access_token=" . $access_token . "&status=cancel",
                        ));
                    } catch (\Exception $exception) {
                        payment_fail_save_detail($access_token);
                        payment_error("error", $exception->getMessage(), $access_token);
                    }

                    break;
            }
            ?>
            <html>
            <head>
                <title>Redirecting...</title>
            </head>
            <body>
            <p>Please do not refresh this page...</p>
            <script src="https://js.stripe.com/v3/"></script>
            <script>
                let stripe = Stripe(<?php echo json_encode($stripe_publishable_key) ?>);

                stripe.redirectToCheckout({
                    sessionId: <?php echo json_encode($stripe_session->id) ?>,
                }).then((result) => {});
            </script>
            </body>
            </html>
            <?php
            exit;

            break;

        case 'stripe_ipn':

            /* Success */
            unset($_SESSION['quickad'][$access_token]);

            if($payment_type == "subscr") {
                message(__("Success"), __("Payment Successful"), $link['TRANSACTION']);
            }else{
                headerRedirect($config['site_url'].'restaurant/'.$restaurant_id.'?return=success');
            }
            exit();
            break;
    }
}