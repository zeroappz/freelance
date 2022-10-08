<?php
if(checkloggedin()) {
    $error = "";
    $warning = 0;
    $user_data = get_user_data(null,$_SESSION['user']['id']);
    $balance = $user_data['balance'];
    if(isset($_GET['warning'])) {
        $warning = 1;
    }
    if(isset($_POST['amount']))
    {
        if($_POST['amount'] < $config['payment_minimum_deposit'])
        {
            $min_amount = price_format($config['payment_minimum_deposit']);
            $error = __("You must deposit more than").' '.$min_amount;
        }

        if(!is_numeric($_POST['amount']) || $_POST['amount'] <= 0){
            $error = __("Amount not valid");
        }

        if($error != ""){
            HtmlTemplate::display('project_deposit', array(
                'error' => $error,
                'warning' => $warning
            ));
            exit;
        }else{
            /*These details save in session and get on payment sucecess*/
            $title = "Deposit to wallet";
            $payment_type = "deposit";
            $access_token = uniqid();
            $amount = validate_input($_POST['amount']);

            $_SESSION['quickad'][$access_token]['product_id'] = '';
            $_SESSION['quickad'][$access_token]['name'] = $title;
            $_SESSION['quickad'][$access_token]['amount'] = $amount;
            $_SESSION['quickad'][$access_token]['payment_type'] = $payment_type;
            $_SESSION['quickad'][$access_token]['trans_desc'] = $title;
            /*End These details save in session and get on payment sucecess*/

            $url = $link['PAYMENT']."/" . $access_token;
            header("Location: ".$url);
            exit;
        }
    }
    else
    {
        //Print Template
        HtmlTemplate::display('project_deposit', array(
            'error' => $error,
            'warning' => $warning
        ));
        exit;
    }
}
else{
    error(__("Page Not Found"), __LINE__, __FILE__, 1);
    exit();
}
?>
