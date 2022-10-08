<?php
require_once("includes/lib/curl/curl.php");
require_once("includes/lib/curl/CurlResponse.php");

if(checkloggedin())
{
    if(isset($_REQUEST['upgrade']))
    {
        $user_id = $_SESSION['user']['id'];
        $plan_taxes = array();
        $price_without_inclusive = 0;
        $tax_ids = array();
        if($_REQUEST['upgrade'] == 'trial'){
            if(get_user_option($user_id,'package_trial_done')){
                error(__("Your trial option was already used, you can't use it anymore."), __LINE__, __FILE__, 1);
                exit();
            }
            $plan = json_decode(get_option('trial_membership_plan'), true);
            $price = 0;
            $term = $plan['days'];
        }else{
            $plan = ORM::for_table($config['db']['pre'].'plans')
                ->where('id', $_REQUEST['upgrade'])
                ->find_one();

            switch ($_REQUEST['billed-type']){
                case 'monthly':
                    $price = $plan['monthly_price'];
                    $term = 'MONTHLY';
                    break;
                case 'yearly':
                    $price = $plan['annual_price'];
                    $term = 'YEARLY';
                    break;
                case 'lifetime':
                    $price = $plan['lifetime_price'];
                    $term = 'LIFETIME';
                    break;
            }

            $base_amount = $price;

            if(!empty($plan['taxes_ids'])){
                $taxes = ORM::for_table($config['db']['pre'].'taxes')
                    ->where_id_in(explode(',', $plan['taxes_ids']))
                    ->find_many();

                $inclusive_tax = $exclusive_tax = 0;

                foreach ($taxes as $tax){

                    /* filter plan taxes */

                    /* Type */
                    if (
                        $tax['billing_type'] != get_user_option($_SESSION['user']['id'], 'billing_details_type') &&
                        $tax['billing_type'] != 'both'
                    ) {
                        continue;
                    }

                    /* Countries */
                    if (
                        $tax['countries'] &&
                        !in_array(get_user_option($_SESSION['user']['id'], 'billing_country'), explode(',', $tax['countries']))
                    ) {
                        continue;
                    }

                    /* Create variable */
                    $plan_taxes[$tax['id']]['id'] = $tax['id'];
                    $plan_taxes[$tax['id']]['name'] = $tax['name'];
                    $plan_taxes[$tax['id']]['description'] = $tax['description'];
                    $plan_taxes[$tax['id']]['type'] = $tax['type'] == 'inclusive' ? __("Inclusive") : __("Exclusive");
                    $plan_taxes[$tax['id']]['value_formatted'] = $tax['value_type'] == 'percentage' ? (float) $tax['value'] .'%' : price_format($tax['value']);

                    /* calculate inclusive taxes */
                    if($tax['type'] == 'inclusive'){
                        $inclusive_tax += $tax['value_type'] == 'percentage' ? $price * ($tax['value'] / 100) : $tax['value'];
                    }

                    $tax_ids[] = $tax['id'];
                }

                $price_without_inclusive = $price - $inclusive_tax;

                /* calculate exclusive taxes */
                foreach ($taxes as $tax){
                    /* filter plan taxes */

                    /* Type */
                    if (
                        $tax['billing_type'] != get_user_option($_SESSION['user']['id'], 'billing_details_type') &&
                        $tax['billing_type'] != 'both'
                    ) {
                        continue;
                    }

                    /* Countries */
                    if (
                        $tax['countries'] &&
                        !in_array(get_user_option($_SESSION['user']['id'], 'billing_country'), explode(',', $tax['countries']))
                    ) {
                        continue;
                    }

                    if($tax['type'] == 'exclusive'){
                        $exclusive_tax += $tax['value_type'] == 'percentage' ? $price_without_inclusive * ($tax['value'] / 100) : $tax['value'];
                    }
                }
                /* total price */
                $price += $exclusive_tax;
            }
        }

        $title = $plan['name'];
        $amount = price_format($price);

        $payment_type = "subscr";

        if(isset($_POST['payment_method_id']))
        {
            if($_REQUEST['upgrade'] == 'trial'){
                if(get_user_option($user_id,'package_trial_done')){
                    error(__("Your trial option was already used, you can't use it anymore."), __LINE__, __FILE__, 1);
                    exit();
                }

                ORM::for_table($config['db']['pre'].'upgrades')
                    ->where_equal('user_id', $user_id)
                    ->delete_many();

                $upgrades_insert = ORM::for_table($config['db']['pre'].'upgrades')->create();
                $upgrades_insert->sub_id = $_REQUEST['upgrade'];
                $upgrades_insert->user_id = $user_id;
                $upgrades_insert->upgrade_lasttime = time();
                $upgrades_insert->upgrade_expires = time() + $plan['days'] * 86400;
                $upgrades_insert->status = 'Active';
                $upgrades_insert->save();

                $person = ORM::for_table($config['db']['pre'].'user')->find_one($user_id);
                $person->group_id = $_REQUEST['upgrade'];
                $person->save();

                update_user_option($user_id, 'package_trial_done',1);
                message(__("Success"),__("Payment Successful"),$link['MEMBERSHIP']);
                exit();
            } else {
                $access_token = uniqid();
                $_SESSION['quickad'][$access_token]['name'] = $title . " " . __("Membership Plan");
                $_SESSION['quickad'][$access_token]['amount'] = $price;
                $_SESSION['quickad'][$access_token]['base_amount'] = $base_amount;
                $_SESSION['quickad'][$access_token]['payment_type'] = $payment_type;
                $_SESSION['quickad'][$access_token]['sub_id'] = $_REQUEST['upgrade'];
                $_SESSION['quickad'][$access_token]['plan_interval'] = $term;
                $_SESSION['quickad'][$access_token]['taxes_ids'] = implode(',',$tax_ids);

                $info = ORM::for_table($config['db']['pre'] . 'payments')
                    ->where(array(
                        'payment_id' => $_POST['payment_method_id'],
                        'payment_install' => '1'
                    ))
                    ->find_one();

                $folder = $info['payment_folder'];

                if ($folder == "2checkout") {
                    $_SESSION['quickad'][$access_token]['firstname'] = $_POST['checkoutCardFirstName'];
                    $_SESSION['quickad'][$access_token]['lastname'] = $_POST['checkoutCardLastName'];
                    $_SESSION['quickad'][$access_token]['BillingAddress'] = $_POST['checkoutBillingAddress'];
                    $_SESSION['quickad'][$access_token]['BillingCity'] = $_POST['checkoutBillingCity'];
                    $_SESSION['quickad'][$access_token]['BillingState'] = $_POST['checkoutBillingState'];
                    $_SESSION['quickad'][$access_token]['BillingZipcode'] = $_POST['checkoutBillingZipcode'];
                    $_SESSION['quickad'][$access_token]['BillingCountry'] = $_POST['checkoutBillingCountry'];
                }

                $_SESSION['quickad'][$access_token]['payment_mode'] = !empty($_POST['payment_mode']) ? $_POST['payment_mode'] : 'one_time';
                if($folder == 'paypal' || $folder == 'stripe'){
                    $payment_mode = get_option($folder.'_payment_mode');
                    if($payment_mode == 'both'){
                        $_SESSION['quickad'][$access_token]['payment_mode'] = !empty($_POST['payment_mode']) ? $_POST['payment_mode'] : 'one_time';
                    }else{
                        $_SESSION['quickad'][$access_token]['payment_mode'] = $payment_mode;
                    }
                }

                $_SESSION['quickad'][$access_token]['folder'] = $folder;
                if (file_exists('includes/payments/' . $folder . '/pay.php')) {
                    require_once('includes/payments/' . $folder . '/pay.php');
                } else {
                    error(__("This payment method is not enabled."), __LINE__, __FILE__, 1);
                    exit();
                }
            }
        }
        else
        {
            $payment_types = array();
            $rows = ORM::for_table($config['db']['pre'].'payments')
                ->where('payment_install', '1')
                ->find_many();

            $num_rows = count($rows);
            foreach ($rows as $info)
            {
                $payment_image = $config['site_url']."includes/payments/".$info['payment_folder']."/logo/logo.png";
                $payment_types[$info['payment_id']]['id'] = $info['payment_id'];
                $payment_types[$info['payment_id']]['title'] = $info['payment_title'];
                $payment_types[$info['payment_id']]['folder'] = $info['payment_folder'];
                $payment_types[$info['payment_id']]['desc'] = $info['payment_desc'];
                $payment_types[$info['payment_id']]['image'] = $payment_image;
            }

            $period = 0;
            if($_REQUEST['upgrade'] == 'trial'){
                $period = (int) $plan['days'] * 86400;
            }else{
                if($_REQUEST['billed-type'] == "monthly") {
                    $period = 2678400;
                }
                elseif($_REQUEST['billed-type'] == "yearly") {
                    $period = 31536000;
                }
            }

            $expires = (time()+$period);
            $start_date = date("d-m-Y",time());
            $expiry_date = $period ? date("d-m-Y",$expires) : __("Lifetime");

            // assign posted variables to local variables
            $bank_information = nl2br(get_option('company_bank_info'));
            $userdata = get_user_data($_SESSION['user']['username']);
            $email = $userdata['email'];

            //Print Template
            HtmlTemplate::display('global/membership_payment', array(
                'payment_types' => $payment_types,
                'upgrade' => $_REQUEST['upgrade'],
                'plan_id' => $_REQUEST['upgrade'],
                'billed_type' => $_REQUEST['billed-type'],
                'payment_method_count' => $num_rows,
                'bank_info' => $bank_information,
                'start_date' => $start_date,
                'expiry_date' => $expiry_date,
                'order_title' => $title,
                'amount' => $amount,
                'price' => $price,
                'price_without_inclusive' => price_format($price_without_inclusive),
                'email' => $email,
                'country_code' => strtoupper(check_user_country()),
                'show_taxes' => (int) !empty($plan_taxes),
                'taxes' => $plan_taxes,
                'stripe_publishable_key' => isset($config['stripe_publishable_key'])? $config['stripe_publishable_key']: '',
                'paystack_public_key' => isset($config['paystack_public_key'])? $config['paystack_public_key']: '',
                'sandbox_mode_2checkout' => isset($config['2checkout_sandbox_mode'])? $config['2checkout_sandbox_mode']: '',
                'checkout_account_number' => isset($config['checkout_account_number'])? $config['checkout_account_number']: '',
                'checkout_public_key' => isset($config['checkout_public_key'])? $config['checkout_public_key']: '',
                'token' => ''
            ));
            exit;
        }
    }
	else
	{
		$upgrades = array();

		if(isset($_GET['change_plan']) && $_GET['change_plan'] == "changeplan")
		{
            
            check_validation_for_subscribePlan();
            $sub_info = get_user_membership_detail($_SESSION['user']['id']);

            // custom settings
            $plan_custom = ORM::for_table($config['db']['pre'].'plan_options')
                ->where('active', 1)
                ->order_by_asc('position')
                ->find_many();

            $sub_types = array();

            $plan = json_decode(get_option('free_membership_plan'), true);
            if($plan['status']){
                if($plan['id'] == $sub_info['id']) {
                    $sub_types[$plan['id']]['Selected'] = 1;
                } else {
                    $sub_types[$plan['id']]['Selected'] = 0;
                }

                $sub_types[$plan['id']]['id'] = $plan['id'];
                $sub_types[$plan['id']]['title'] = $plan['name'];
                $sub_types[$plan['id']]['monthly_price'] = price_format(0);
                $sub_types[$plan['id']]['annual_price'] = price_format(0);
                $sub_types[$plan['id']]['lifetime_price'] = price_format(0);

                $settings = $plan['settings'];
                $sub_types[$plan['id']]['employer_commission'] = $settings['employer_commission'];
                $sub_types[$plan['id']]['freelancer_commission'] = $settings['freelancer_commission'];
                $sub_types[$plan['id']]['bids'] = $settings['bids'];
                $sub_types[$plan['id']]['skills'] = $settings['skills'];
                $sub_types[$plan['id']]['limit'] = ($settings['ad_limit'] == "999")? __("Unlimited") : $settings['ad_limit'];
                $sub_types[$plan['id']]['duration'] = $settings['ad_duration'];
                $sub_types[$plan['id']]['featured_fee'] = $settings['featured_project_fee'];
                $sub_types[$plan['id']]['urgent_fee'] = $settings['urgent_project_fee'];
                $sub_types[$plan['id']]['highlight_fee'] = $settings['highlight_project_fee'];
                $sub_types[$plan['id']]['featured_duration'] = $settings['featured_duration'];
                $sub_types[$plan['id']]['urgent_duration'] = $settings['urgent_duration'];
                $sub_types[$plan['id']]['highlight_duration'] = $settings['highlight_duration'];
                $sub_types[$plan['id']]['top_search_result'] = $settings['top_search_result'];
                $sub_types[$plan['id']]['show_on_home'] = $settings['show_on_home'];
                $sub_types[$plan['id']]['show_in_home_search'] = $settings['show_in_home_search'];

                $sub_types[$plan['id']]['custom_settings'] = '';
                if(!empty($plan_custom)) {
                    foreach ($plan_custom as $custom) {
                        if(!empty($custom['title']) && trim($custom['title']) != '') {
                            $tpl = '<li><span class="icon-text no"><i class="icon-feather-x-circle margin-right-2"></i></span> ' . $custom['title'] . '</li>';

                            if (isset($settings['custom'][$custom['id']]) && $settings['custom'][$custom['id']] == '1') {
                                $tpl = '<li><span class="icon-text yes"><i class="icon-feather-check-circle margin-right-2"></i></span> ' . $custom['title'] . '</li>';
                            }
                            $sub_types[$plan['id']]['custom_settings'] .= $tpl;
                        }
                    }
                }
            }

            $plan = json_decode(get_option('trial_membership_plan'), true);
            if($plan['status']){
                if($plan['id'] == $sub_info['id']) {
                    $sub_types[$plan['id']]['Selected'] = 1;
                } else {
                    $sub_types[$plan['id']]['Selected'] = 0;
                }

                $sub_types[$plan['id']]['id'] = $plan['id'];
                $sub_types[$plan['id']]['title'] = $plan['name'];
                $sub_types[$plan['id']]['monthly_price'] = price_format(0);
                $sub_types[$plan['id']]['annual_price'] = price_format(0);
                $sub_types[$plan['id']]['lifetime_price'] = price_format(0);;

                $settings = $plan['settings'];
                $sub_types[$plan['id']]['employer_commission'] = $settings['employer_commission'];
                $sub_types[$plan['id']]['freelancer_commission'] = $settings['freelancer_commission'];
                $sub_types[$plan['id']]['bids'] = $settings['bids'];
                $sub_types[$plan['id']]['skills'] = $settings['skills'];
                $sub_types[$plan['id']]['limit'] = ($settings['ad_limit'] == "999")? $lang['UNLIMITED']: $settings['ad_limit'];
                $sub_types[$plan['id']]['duration'] = $settings['ad_duration'];
                $sub_types[$plan['id']]['featured_fee'] = $settings['featured_project_fee'];
                $sub_types[$plan['id']]['urgent_fee'] = $settings['urgent_project_fee'];
                $sub_types[$plan['id']]['highlight_fee'] = $settings['highlight_project_fee'];
                $sub_types[$plan['id']]['featured_duration'] = $settings['featured_duration'];
                $sub_types[$plan['id']]['urgent_duration'] = $settings['urgent_duration'];
                $sub_types[$plan['id']]['highlight_duration'] = $settings['highlight_duration'];
                $sub_types[$plan['id']]['top_search_result'] = $settings['top_search_result'];
                $sub_types[$plan['id']]['show_on_home'] = $settings['show_on_home'];
                $sub_types[$plan['id']]['show_in_home_search'] = $settings['show_in_home_search'];

                $sub_types[$plan['id']]['custom_settings'] = '';
                if(!empty($plan_custom)) {
                    foreach ($plan_custom as $custom) {
                        if(!empty($custom['title']) && trim($custom['title']) != '') {
                            $tpl = '<li><span class="icon-text no"><i class="icon-feather-x-circle margin-right-2"></i></span> ' . $custom['title'] . '</li>';

                            if (isset($settings['custom'][$custom['id']]) && $settings['custom'][$custom['id']] == '1') {
                                $tpl = '<li><span class="icon-text yes"><i class="icon-feather-check-circle margin-right-2"></i></span> ' . $custom['title'] . '</li>';
                            }
                            $sub_types[$plan['id']]['custom_settings'] .= $tpl;
                        }
                    }
                }
            }

            $total_monthly = $total_annual = $total_lifetime = 0;

            $rows = ORM::for_table($config['db']['pre'].'plans')
                ->where('status', '1')
                ->find_many();

            foreach ($rows as $plan)
            {
                if($plan['id'] == $sub_info['id']) {
                    $sub_types[$plan['id']]['Selected'] = 1;
                } else {
                    $sub_types[$plan['id']]['Selected'] = 0;
                }

                $sub_types[$plan['id']]['id'] = $plan['id'];
                $sub_types[$plan['id']]['title'] = $plan['name'];
                $sub_types[$plan['id']]['recommended'] = $plan['recommended'];

                $total_monthly += $plan['monthly_price'];
                $total_annual += $plan['annual_price'];
                $total_lifetime += $plan['lifetime_price'];

                $sub_types[$plan['id']]['monthly_price'] = price_format($plan['monthly_price']);
                $sub_types[$plan['id']]['annual_price'] = price_format($plan['annual_price']);
                $sub_types[$plan['id']]['lifetime_price'] = price_format($plan['lifetime_price']);

                $settings = json_decode($plan['settings'], true);
                $sub_types[$plan['id']]['employer_commission'] = $settings['employer_commission'];
                $sub_types[$plan['id']]['freelancer_commission'] = $settings['freelancer_commission'];
                $sub_types[$plan['id']]['bids'] = $settings['bids'];
                $sub_types[$plan['id']]['skills'] = $settings['skills'];
                $sub_types[$plan['id']]['limit'] = ($settings['ad_limit'] == "999")? $lang['UNLIMITED']: $settings['ad_limit'];
                $sub_types[$plan['id']]['duration'] = $settings['ad_duration'];
                $sub_types[$plan['id']]['featured_fee'] = $settings['featured_project_fee'];
                $sub_types[$plan['id']]['urgent_fee'] = $settings['urgent_project_fee'];
                $sub_types[$plan['id']]['highlight_fee'] = $settings['highlight_project_fee'];
                $sub_types[$plan['id']]['featured_duration'] = $settings['featured_duration'];
                $sub_types[$plan['id']]['urgent_duration'] = $settings['urgent_duration'];
                $sub_types[$plan['id']]['highlight_duration'] = $settings['highlight_duration'];
                $sub_types[$plan['id']]['top_search_result'] = $settings['top_search_result'];
                $sub_types[$plan['id']]['show_on_home'] = $settings['show_on_home'];
                $sub_types[$plan['id']]['show_in_home_search'] = $settings['show_in_home_search'];

                $sub_types[$plan['id']]['custom_settings'] = '';
                if(!empty($plan_custom)) {
                    foreach ($plan_custom as $custom) {
                        if(!empty($custom['title']) && trim($custom['title']) != '') {
                            $tpl = '<li><span class="icon-text no"><i class="icon-feather-x-circle margin-right-2"></i></span> ' . $custom['title'] . '</li>';

                            if (isset($settings['custom'][$custom['id']]) && $settings['custom'][$custom['id']] == '1') {
                                $tpl = '<li><span class="icon-text yes"><i class="icon-feather-check-circle margin-right-2"></i></span> ' . $custom['title'] . '</li>';
                            }
                            $sub_types[$plan['id']]['custom_settings'] .= $tpl;
                        }
                    }
                }
            }

            //Print Template
            HtmlTemplate::display('global/membership_plan', array(
                'sub_types' => $sub_types,
                'total_monthly' => $total_monthly,
                'total_annual' => $total_annual,
                'total_lifetime' => $total_lifetime
            ));
            exit;
		}
        else if(isset($_GET['action']) && $_GET['action'] == "cancel_auto_renew")
        {
            $action = $_GET['action'];

            $sub_info = get_user_membership_detail($_SESSION['user']['id']);

            if ( isset($sub_info['id'])) {

                $subscription = ORM::for_table($config['db']['pre'].'upgrades')
                    ->where('user_id', $_SESSION['user']['id'])
                    ->find_one();


                if ( $info['pay_mode'] == 'recurring' ) {
                    try {
                        cancel_recurring_payment($_SESSION['user']['id']);
                    } catch (\Exception $exception) {
                        error_log($exception->getCode());
                        error_log($exception->getMessage());
                    }
                }
                transfer($link['MEMBERSHIP'],__("Settings Saved Successfully"),__("Settings Saved Successfully"));
                exit;
            }
        }
		else
		{
            $info = ORM::for_table($config['db']['pre'].'upgrades')
                ->where('user_id', $_SESSION['user']['id'])
                ->find_one();

            $show_cancel_button = 0;
            $payment_mode = 'one_time';
            if(!isset($info['sub_id'])){
                $sub_info = json_decode(get_option('free_membership_plan'), true);
                $price = 0;
                $upgrades_term = $upgrades_start_date = $upgrades_expiry_date = '-';
            }else{
                if($info['sub_id'] == 'trial'){
                    $sub_info = json_decode(get_option('trial_membership_plan'), true);
                    $price = 0;
                    $upgrades_term = '-';
                }else{
                    $sub_info = ORM::for_table($config['db']['pre'].'plans')
                        ->where('id', $info['sub_id'])
                        ->find_one();
                    $price = $sub_info['monthly_price'];
                    $payment_mode = $info['pay_mode'];
                    $show_cancel_button = (int) ($payment_mode == 'recurring');
                }
                $upgrades_start_date = date("d-m-Y",$info['upgrade_lasttime']);
                $upgrades_expiry_date = date("d-m-Y",$info['upgrade_expires']);
            }

            $upgrades_title = $sub_info['name'];
            $upgrades_cost = price_format($price);

            //Print Template
            HtmlTemplate::display('global/membership_current', array(
                'upgrades_title' => $upgrades_title,
                'upgrades_start_date' => $upgrades_start_date,
                'upgrades_expiry_date' => $upgrades_expiry_date,
                'payment_mode' => $payment_mode,
                'show_cancel_button' => $show_cancel_button
            ));
            exit;
		}
	}
}
else
{
    headerRedirect($link['LOGIN']);
}