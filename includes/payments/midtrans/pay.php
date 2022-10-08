<?php
namespace Midtrans;

use ORM;

require_once 'Midtrans.php';

global $config, $lang, $link;

if (isset($access_token)) {
    $payment_type = $_SESSION['quickad'][$access_token]['payment_type'];
    $title = $_SESSION['quickad'][$access_token]['name'];
    $amount = $_SESSION['quickad'][$access_token]['amount'];

    if ($payment_type == "order") {
        $restaurant_id = $_SESSION['quickad'][$access_token]['restaurant_id'];

        $mt_client_key = get_restaurant_option($restaurant_id, 'restaurant_midtrans_client_key');
        $mt_server_key = get_restaurant_option($restaurant_id, 'restaurant_midtrans_server_key');
        $mt_payment_mode = get_restaurant_option($restaurant_id, 'restaurant_midtrans_sandbox_mode');
    } else {

        $mt_client_key = get_option('midtrans_client_key');
        $mt_server_key = get_option('midtrans_server_key');
        $mt_payment_mode = get_option('midtrans_sandbox_mode');
    }

} else {
    error(__("Invalid Payment Processor"), __LINE__, __FILE__, 1);
    exit();
}

if ($mt_payment_mode == 'test') {
    $payment_link = 'https://app.sandbox.midtrans.com/snap/snap.js';
} else {
    $payment_link = 'https://app.midtrans.com/snap/snap.js';
    Config::$isProduction = true;
}

$return_url = $link['IPN'] . "/?access_token=" . $access_token . "&i=midtrans";
$cancel_url = $link['PAYMENT'] . "/?access_token=" . $access_token . "&status=cancel";

//Set Your server key
Config::$serverKey = $mt_server_key;
Config::$isSanitized = Config::$is3ds = true;

// Required
$transaction_details = array(
    'order_id' => rand(),
    'gross_amount' => $amount, // no decimal allowed for creditcard
);
// Optional
$item_details = array(
    array(
        'id' => rand(),
        'price' => $amount,
        'quantity' => 1,
        'name' => $title
    ),
);

// Fill transaction details
$transaction = array(
    'transaction_details' => $transaction_details,
    'item_details' => $item_details,
);

try {
    $snapToken = Snap::getSnapToken($transaction);
    // Get Snap Payment Page URL
    //$paymentUrl = \Midtrans\Snap::createTransaction($transaction)->redirect_url;
    // Redirect to Snap Payment Page
    //header('Location: ' . $paymentUrl);
} catch (\Exception $e) {
    payment_error("error", $e->getMessage(), $access_token);
    exit();
}
?>

<!DOCTYPE html>
<html>
<body onload="paynow()">
<script src="<?php echo $payment_link ?>" data-client-key="<?php echo $mt_client_key ?>"></script>
<script type="text/javascript">
    paynow = function () {
        // SnapToken acquired from previous step
        snap.pay('<?php echo $snapToken?>', {
            // Optional
            onSuccess: function (result) {
                //console.log(result);
                window.location = '<?php echo $return_url ?>';
            },
            // Optional
            onPending: function (result) {
                window.location = '<?php echo $cancel_url ?>';
            },
            // Optional
            onError: function (result) {
                window.location = '<?php echo $cancel_url ?>';
            }
        });
    };
</script>
</body>
</html>