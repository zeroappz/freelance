<?php
/** DO NOT MODIFY OPTIONS BELOW. YOU CAN MODIFY THEM VIA ADMIN PANEL. */
define('VERSION', '2.50');
define('RECORDS_PER_PAGE', '20');
define('DEMO_MODE', false);
define('STATUS_DRAFT', 0);
define('STATUS_ACTIVE', 1);
define('STATUS_EXPIRED', 2);
define('STATUS_PENDING', 7);
define('ABSPATH', dirname(dirname(__FILE__)));

$options = array (
	"version" => VERSION,
	"owner_email" => "owner@gmail.com",
	"from_name" => "Quick Banner Manager",
	"from_email" => "helpdesk.bylancer@gmail.com",
	"success_email_subject" => "Payment completed",
	"success_email_body" => "Dear {payer_name},".PHP_EOL.PHP_EOL."Thank you for your payment for \"{banner_title}\".".PHP_EOL.PHP_EOL."Thanks,".PHP_EOL."Quick Banner Manager",
	"failed_email_subject" => "Payment not completed",
	"failed_email_body" => "Dear {payer_name},".PHP_EOL.PHP_EOL."Thank you for your payment. Unfortunately, it was not completed.".PHP_EOL."Payment status: {payment_status}.".PHP_EOL."We will review your payment shortly.".PHP_EOL.PHP_EOL."Thanks,".PHP_EOL."Quick Banner Manager",
	"stats_email_subject" => "Statistics",
	"stats_email_body" => "Dear Sir or Madam,".PHP_EOL.PHP_EOL."We would like to inform you that we have finished showing your banner \"{banner_title}\".".PHP_EOL."{statistics}".PHP_EOL.PHP_EOL."You can publish new banner on this page: {signup_page}.".PHP_EOL.PHP_EOL."Thanks,".PHP_EOL."Quick Banner Manager",
	"currency" => "USD",
	"minimum_days" => 10,
	"signup_page" => $link['ADVERTISE_HERE'],
	"enable_approval" => "",
	"approved_email_body" => "Dear Sir or Madam,".PHP_EOL.PHP_EOL."We would like to inform that your banner \"{banner_title}\" was approved.".PHP_EOL.PHP_EOL."Thanks,".PHP_EOL."Quick Banner Manager",
	"rejected_email_body" => "Dear Sir or Madam,".PHP_EOL.PHP_EOL."We are sorry, but your banner \"{banner_title}\" was not approved. We will do refund as soon as possible.".PHP_EOL.PHP_EOL."Thanks,".PHP_EOL."Quick Banner Manager",
	"intro" => 'Do you want to promote your website? Do it now! Publish your banner on our website. It is really easy - just fill up the form below and pay with prefered payment system. After that we will show your banner as many days as you purchased. You can purchase {minimum_days} days and more.',
	"terms" => '1. All Materials and/or Banners that the Advertiser supplies to this website shall be legal, non-adult, non-infringing, decent, honest and truthful.'.PHP_EOL.
'2. The reproduction and/or publication by the Publisher of the Banner (including but not limited to any photographs contained in the Banner) and/or of any Materials supplied by the Advertiser within the Banner and/or the use by the Publisher of the Advertiser logo and trade marks in the Banner will not breach any contract; infringe any third party Intellectual Property Rights or other rights; render the Publisher liable to any proceedings whatsoever; and/or harm or detriment the reputation of the Publisher and/or of any other companies.'.PHP_EOL.
'3. In respect of any Banner submitted for publication by the Advertiser, which contains any copy and/or photographs by which any living person is or can be identified, the Advertiser has obtained the necessary authority of such living person for the Publisher to make use of such copy under this Agreement and has complied in all respects with the Data Protection Act 1998 (as amended from time to time).'.PHP_EOL.
'4. Website administration may refuse, edit and/or require to be amended any copy, artwork and/or materials set out in a Banner and reserves the right to make any alteration it considers necessary or desirable to the Banners and to require illustrations, artwork or copy to be amended to meet its approval for any reason.'.PHP_EOL.
'5. Website administration may at any time remove any or all of the Banners and /or other of the Advertiser`s materials from website, which in the Publisher`s opinion are unlawful or have been placed on the website in breach of this Agreement or in the event of non-payment or any other breach of the Agreement.'.PHP_EOL.
'6. The Advertiser agrees that all questions and complaints relating to a Banner and/or the Advertiser are the sole and exclusive responsibility of the Advertiser. The Advertiser agrees to indemnify the Publisher in respect of all costs, damages or other charges falling upon the Publisher as the result of any complaints, legal actions or threatened legal actions arising from the publication of any Banners, or any part of a Banner.'.PHP_EOL.
'7. The Advertiser shall be solely responsible for checking the accuracy of any Banner for errors and for amending copy.'.PHP_EOL.
'8. The Advertiser shall report to the Publisher any suspected faults to the Service as soon as the suspected faults come to the Advertiser`s attention.'.PHP_EOL.
'9. The Advertiser shall not provide files that contain a virus or corrupted data.',
	"login" => "admin",
	"password" => "21232f297a57a5a743894a0e4a801fc3"
);

$types = array(
	array (
		"id" => 1,
		"width" => 728,
		"height" => 90
	),
	array (
		"id" => 2,
		"width" => 468,
		"height" => 60
	),
	array (
		"id" => 3,
		"width" => 234,
		"height" => 60
	),
	array (
		"id" => 4,
		"width" => 125,
		"height" => 125
	),
	array (
		"id" => 5,
		"width" => 120,
		"height" => 90
	),
	array (
		"id" => 6,
		"width" => 120,
		"height" => 600
	),
	array (
		"id" => 7,
		"width" => 160,
		"height" => 600
	),
	array (
		"id" => 8,
		"width" => 300,
		"height" => 250
	),
    array (
        "id" => 9,
        "width" => 258,
        "height" => 52
    ),
	array (
		"id" => 0,
		"width" => 0,
		"height" => 0
	)
);

?>