<?php
require_once('iyzico/config.php');
$token=$_POST['token'];

$request = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
$request->setLocale(\Iyzipay\Model\Locale::TR);
$request->setToken("$token");
$checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($request, Config::options());

//print_r($checkoutForm->getPaymentStatus());
$payment_status = $checkoutForm->getStatus();
$transaction_number = $checkoutForm->getpaymentId();

if ($payment_status=="failure") {

    payment_fail_save_detail($access_token);

    mail($config['admin_email'],'iyzico error in '.$config['site_title'],'iyzico error in '.$config['site_title'].', status from iyzico');

    $error_msg = "Transaction was not successful: Last gateway response was: ".$payment_status;
    payment_error("error",$error_msg,$access_token);
    exit();

} elseif ($payment_status=="success") {

	$msg = "Your Super Successful Payment transaction number :".$transaction_number;
    payment_success_save_detail($access_token);
}