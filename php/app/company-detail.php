<?php
// if company is disable
if(!$config['company_enable']){
    error(__("Page Not Found"), __LINE__, __FILE__, 1);
}
if(checkloggedin()) {
    update_lastactive();
}

if(!isset($_GET['id']))
{
    error(__("Page Not Found"), __LINE__, __FILE__, 1);
    exit;
}

$num_rows = ORM::for_table($config['db']['pre'].'companies')
    ->where('id',$_GET['id'])
    ->count();

if ($num_rows > 0) {

    $result = ORM::for_table($config['db']['pre'].'companies')->find_one($_GET['id']);
	$id = $result['id'];
	$name = $result['name'];
	$description = nl2br(stripcslashes($result['description']));
	$reg_no = $result['reg_no'];
	$city = $result['city'];
	$location = $result['location'];
	$phone = $result['phone'];
	$fax = $result['fax'];
	$email = $result['email'];
	$website = $result['website'];
	$facebook = $result['facebook'];
	$twitter = $result['twitter'];
	$linkedin = $result['linkedin'];
	$pinterest = $result['pinterest'];
	$youtube = $result['youtube'];
	$instagram = $result['instagram'];

	$logo = '';
    if(!empty($result['logo'])){
    	$logo = $config['site_url'].'storage/products/'.$result['logo'];
    }else{
    	$logo = $config['site_url'].'storage/products/default.png';
    }

    if(!empty($result['latlong'])){
	    $latlong = explode(',', $result['latlong']);
	    $mapLat = $latlong[0];
	    $mapLong = $latlong[1];
	    if(empty($mapLat) || empty($mapLong)){
	    	$data = get_cityDetail_by_id($city);
			$mapLat = $data['latitude'];
			$mapLong = $data['longitude'];
	    }
	}else{
		$data = get_cityDetail_by_id($city);
		if(!empty($data)){
			$mapLat = $data['latitude'];
			$mapLong = $data['longitude'];
		}else{
			$mapLat = '';
			$mapLong = '';
		}
	}

    $hide_contact = 0;
    if($config['contact_validation'] == '1'){
        if(!checkloggedin()){
            $hide_contact = 1;
        }
    }

    $meta_desc = substr(strip_tags($description),0,150);
	$meta_desc = trim(preg_replace('/\s\s+/', ' ', $meta_desc));

	// get company jobs
	$results = ORM::for_table($config['db']['pre'].'product')
	->where('company_id',validate_input($_GET['id']))
	->where('status','active')
	->where('hide','0')
	->find_many();
	$total_job = $results->count();
	$items = array();
	foreach($results as $info){
		$items[$info['id']]['id'] = $info['id'];
        $items[$info['id']]['name'] = $info['product_name'];
        $items[$info['id']]['product_type'] = get_productType_title_by_id($info['product_type']);
        $items[$info['id']]['featured'] = $info['featured'];
        $items[$info['id']]['urgent'] = $info['urgent'];
        $items[$info['id']]['highlight'] = $info['highlight'];

        $salary_min = price_format($info['salary_min'],$info['country']);
        $items[$info['id']]['salary_min'] = $salary_min;
        $salary_max = price_format($info['salary_max'],$info['country']);
        $items[$info['id']]['salary_max'] = $salary_max;

        $cityname = get_cityName_by_id($info['city']);
        $items[$info['id']]['city'] = $cityname;
        $items[$info['id']]['created_at'] = timeAgo($info['created_at']);
        $pro_url = create_slug($info['product_name']);
        $items[$info['id']]['link'] = $link['POST-DETAIL'].'/' . $info['id'] . '/'.$pro_url;
	}

	$page_link = $link['COMPANY-DETAIL'].'/'.$id.'/'.create_slug($name);

	//Print Template
	HtmlTemplate::display('company-detail', array(
		'items' => $items,
		'totalitem' => $total_job,
		'item_link' => $page_link,
		'name' => $name,
		'logo' => $logo,
		'description' => $description,
		'company_reg_no' => $reg_no,
		'city' => $city,
		'cityname' => get_cityName_by_id($city),
		'statename' => get_stateName_by_id($result['state']),
		'location' => $location,
		'phone' => $phone,
		'fax' => $fax,
		'email' => $email,
		'website' => $website,
		'facebook' => $facebook,
		'twitter' => $twitter,
		'linkedin' => $linkedin,
		'pinterest' => $pinterest,
		'youtube' => $youtube,
		'instagram' => $instagram,
		'id' => $id,
		'latitude' => $mapLat,
		'longitude' => $mapLong,
		'hide_contact' => $hide_contact
	));
	exit;
} else {
    error(__("Page Not Found"), __LINE__, __FILE__, 1);
    exit;
}

?>
