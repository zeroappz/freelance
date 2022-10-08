<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

$mysqli = db_connect();

if (isset($_SESSION['quickad'][$access_token]['payment_type'])) {
    if(!checkloggedin()){
        header("Location: ".$link['LOGIN']);
        exit();
    }else{

        $title = $_SESSION['quickad'][$access_token]['name'];
        $amount = $_SESSION['quickad'][$access_token]['amount'];
        $base_amount = isset($_SESSION['quickad'][$access_token]['base_amount'])? $_SESSION['quickad'][$access_token]['base_amount'] : $amount;
        $folder = $_SESSION['quickad'][$access_token]['folder'];
        $payment_type = $_SESSION['quickad'][$access_token]['payment_type'];
        $user_id = $_SESSION['user']['id'];

        $billing = array(
            'type' => get_user_option($_SESSION['user']['id'],'billing_details_type'),
            'tax_id' => get_user_option($_SESSION['user']['id'],'billing_tax_id'),
            'name' => get_user_option($_SESSION['user']['id'],'billing_name'),
            'address' => get_user_option($_SESSION['user']['id'],'billing_address'),
            'city' => get_user_option($_SESSION['user']['id'],'billing_city'),
            'state' => get_user_option($_SESSION['user']['id'],'billing_state'),
            'zipcode' => get_user_option($_SESSION['user']['id'],'billing_zipcode'),
            'country' => get_user_option($_SESSION['user']['id'],'billing_country')
        );

        $taxes_ids = isset($_SESSION['quickad'][$access_token]['taxes_ids'])? $_SESSION['quickad'][$access_token]['taxes_ids'] : null;

        if($payment_type == "subscr") {
            $trans_desc = $title;
            $subcription_id = $_SESSION['quickad'][$access_token]['sub_id'];
            $plan_interval = $_SESSION['quickad'][$access_token]['plan_interval'];

            $trans_insert = ORM::for_table($config['db']['pre'].'transaction')->create();
            $trans_insert->product_name = validate_input($title);
            $trans_insert->product_id = $subcription_id;
            $trans_insert->seller_id = $_SESSION['user']['id'];
            $trans_insert->status = 'pending';
            $trans_insert->base_amount = $base_amount;
            $trans_insert->amount = $amount;
            $trans_insert->transaction_gatway = $folder;
            $trans_insert->transaction_ip = encode_ip($_SERVER, $_ENV);
            $trans_insert->transaction_time = time();
            $trans_insert->transaction_description = validate_input($trans_desc);
            $trans_insert->transaction_method = 'Subscription';
            $trans_insert->frequency = $plan_interval;
            $trans_insert->billing = json_encode($billing);
            $trans_insert->taxes_ids = $taxes_ids;
            $trans_insert->save();
        }
        elseif($payment_type == "banner-advertise"){
            $item_pro_id = $_SESSION['quickad'][$access_token]['product_id'];
            $trans_desc = $_SESSION['quickad'][$access_token]['trans_desc'];

            $trans_insert = ORM::for_table($config['db']['pre'].'transaction')->create();
            $trans_insert->product_name = validate_input($title);
            $trans_insert->product_id = $item_pro_id;
            $trans_insert->seller_id = $user_id;
            $trans_insert->status = 'pending';
            $trans_insert->base_amount = $base_amount;
            $trans_insert->amount = $amount;
            $trans_insert->transaction_gatway = $folder;
            $trans_insert->transaction_ip = encode_ip($_SERVER, $_ENV);
            $trans_insert->transaction_time = time();
            $trans_insert->transaction_description = validate_input($trans_desc);
            $trans_insert->transaction_method = 'banner-advertise';
            $trans_insert->billing = json_encode($billing);
            $trans_insert->taxes_ids = $taxes_ids;
            $trans_insert->save();
        }
        else{
            $item_pro_id = $_SESSION['quickad'][$access_token]['product_id'];
            $item_featured = isset($_SESSION['quickad'][$access_token]['featured'])? $_SESSION['quickad'][$access_token]['featured'] : 0;
            $item_urgent = isset($_SESSION['quickad'][$access_token]['urgent'])? $_SESSION['quickad'][$access_token]['urgent'] : 0;
            $item_highlight = isset($_SESSION['quickad'][$access_token]['highlight'])? $_SESSION['quickad'][$access_token]['highlight'] : 0;
            $trans_desc = $_SESSION['quickad'][$access_token]['trans_desc'];

            $trans_insert = ORM::for_table($config['db']['pre'].'transaction')->create();
            $trans_insert->product_name = validate_input($title);
            $trans_insert->product_id = $item_pro_id;
            $trans_insert->seller_id = $user_id;
            $trans_insert->status = 'pending';
            $trans_insert->base_amount = $base_amount;
            $trans_insert->amount = $amount;
            $trans_insert->featured = $item_featured;
            $trans_insert->urgent = $item_urgent;
            $trans_insert->highlight = $item_highlight;
            $trans_insert->transaction_gatway = $folder;
            $trans_insert->transaction_ip = encode_ip($_SERVER, $_ENV);
            $trans_insert->transaction_time = time();
            $trans_insert->transaction_description = validate_input($trans_desc);
            $trans_insert->transaction_method = 'Premium Ad';
            $trans_insert->billing = json_encode($billing);
            $trans_insert->taxes_ids = $taxes_ids;
            $trans_insert->save();
        }

        $transaction_id = $trans_insert->id();



        // assign posted variables to local variables
        $bank_information = nl2br(get_option('company_bank_info'));
        $item_name = $trans_desc;
        unset($_SESSION['quickad'][$access_token]);

        //Print Template
        HtmlTemplate::display('includes/payments/wire_transfer/pay_template', array(
            'pagetitle' => __("Offline Payment"),
            'bank_info' => $bank_information,
            'transaction_id' => $transaction_id,
            'order_title' => $item_name,
            'amount' => price_format($amount),
        ),true,true);
        exit;

    }
}else{
    exit('Invalid Process');
    headerRedirect($link['LOGIN']);
}
?>