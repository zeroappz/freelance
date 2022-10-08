<?php
// if company is disable
if(!$config['company_enable']){
    error(__("Page Not Found"), __LINE__, __FILE__, 1);
}
if(checkloggedin())
{
	update_lastactive();
	$ses_userdata = get_user_data($_SESSION['user']['username']);
	if($ses_userdata['user_type'] != 'employer'){
		headerRedirect($link['DASHBOARD']);
	}
	$id = $name = $error = $company_logo = $description = $reg_no = $city = $location = $phone = $fax = $email = $website = $facebook = $twitter = $linkedin = $pinterest = $youtube = $instagram = '';

    $country_code = check_user_country();
	if($latlong = get_lat_long_of_country($country_code)){
	    $mapLat     =  $latlong['lat'];
	    $mapLong    =  $latlong['lng'];
	}else{
	    $mapLat     =  get_option("home_map_latitude");
	    $mapLong    =  get_option("home_map_longitude");
	}

	if(isset($_POST['submit'])){
		if(empty($_POST['name'])){
			$error = __("Company Name Required.");
		}

		if(empty($_POST['company_desc'])){
			$error = __("Company Description Required.");
		}

		$name = $_POST['name'];
		$description = $_POST['company_desc'];
		$city = $_POST['city'];
		$phone = $_POST['phone'];
		$fax = $_POST['fax'];
		$email = $_POST['email'];
		$website = $_POST['website'];
		$facebook = $_POST['facebook'];
		$twitter = $_POST['twitter'];
		$linkedin = $_POST['linkedin'];
		$pinterest = $_POST['pinterest'];
		$youtube = $_POST['youtube'];
		$instagram = $_POST['instagram'];

		$citydata = get_cityDetail_by_id($city);
		if($citydata){
			$country = $citydata['country_code'];
			$state = $citydata['subadmin1_code'];
		}else{
			$country = null;
			$state = null;
		}

		$latlong = '';
        if(isset($_POST['location'])){
            $location = $_POST['location'];
            $mapLat = $_POST['latitude'];
            $mapLong = $_POST['longitude'];
            $latlong = $mapLat . "," . $mapLong;
        }

		if($config['reg_no_enable']){
			$reg_no = $_POST['reg_no'];
			if(isset($reg_no)){
				$regno_count = ORM::for_table($config['db']['pre'].'companies')
					->where('reg_no', $reg_no)
					->where_not_equal('id', $_POST['id'])
					->count();

				if ($regno_count) {
					$error = __("Registration no. already exist.");
				}
			}else{
				$error = __("Registration no. required.");
			}
		}else{
			$reg_no = 0;
		}

		if($error == ''){
			if(!empty($_FILES['logo'])){
				$file = $_FILES['logo'];
				// Valid formats
				$valid_formats = array("jpeg", "jpg", "png");
				$filename = $file['name'];
				$ext = getExtension($filename);
				$ext = strtolower($ext);
				if (!empty($filename)) {
	                //File extension check
					if (in_array($ext, $valid_formats)) {
						$main_path = ROOTPATH . "/storage/products/";
						$filename = uniqid(time()).'.'.$ext;
						if(move_uploaded_file($file['tmp_name'], $main_path.$filename)){
							$company_logo = $filename;
							resizeImage(200,$main_path.$filename,$main_path.$filename);
						}else{
							$error = __("Error: Please try again.");
						}
					} else {
						$error = __("Sorry, only JPG, JPEG and PNG files are allowed.");
					}
				}
			}
		}

		if($error == ''){
			$now = date("Y-m-d H:i:s");
			if(!empty($_POST['id'])){
				$add_company = ORM::for_table($config['db']['pre'].'companies')
				->where('id',$_POST['id'])
				->where('user_id',$_SESSION['user']['id'])
				->find_one();

				if($add_company){
					if(!empty($company_logo)){
						$add_company->set('logo', $company_logo);
					}
					$add_company->set('name',removeEmailAndPhoneFromString($name));
					$add_company->set('description',validate_input($description));
					$add_company->set('reg_no',validate_input($reg_no));
					$add_company->set('city',validate_input($city));
					$add_company->set('location',validate_input($location));
					$add_company->set('city',validate_input($city));
					$add_company->set('state',validate_input($state));
					$add_company->set('country',validate_input($country));
					$add_company->set('latlong',$latlong);
					$add_company->set('phone',validate_input($phone));
					$add_company->set('fax',validate_input($fax));
					$add_company->set('email',validate_input($email));
					$add_company->set('website',validate_input($website));
					$add_company->set('facebook',validate_input($facebook));
					$add_company->set('twitter',validate_input($twitter));
					$add_company->set('linkedin',validate_input($linkedin));
					$add_company->set('pinterest',validate_input($pinterest));
					$add_company->set('youtube',validate_input($youtube));
					$add_company->set('instagram',validate_input($instagram));
					$add_company->set('updated_at', $now);
					$add_company->save();
				}

				transfer($link['MYCOMPANIES'],__("Company Edited."),__("Company Edited."));
			}
			else{
				$add_company = ORM::for_table($config['db']['pre'].'companies')->create();
				$add_company->name = removeEmailAndPhoneFromString($name);
				$add_company->reg_no = validate_input($reg_no);
				$add_company->logo = $company_logo;
				$add_company->description = validate_input($description);
				$add_company->location = validate_input($location);
				$add_company->city = validate_input($city);
				$add_company->state = validate_input($state);
				$add_company->country = validate_input($country);
				$add_company->latlong = $latlong;
				$add_company->phone = validate_input($phone);
				$add_company->fax = validate_input($fax);
				$add_company->email = validate_input($email);
				$add_company->website = validate_input($website);
				$add_company->facebook = validate_input($facebook);
				$add_company->twitter = validate_input($twitter);
				$add_company->linkedin = validate_input($linkedin);
				$add_company->pinterest = validate_input($pinterest);
				$add_company->youtube = validate_input($youtube);
				$add_company->instagram = validate_input($instagram);
				$add_company->user_id = $_SESSION['user']['id'];
				$add_company->created_at = $now;
				$add_company->updated_at = $now;
				$add_company->save();

				transfer($link['MYCOMPANIES'],__("Company Added."),__("Company Added."));
			}
			exit;
		}
	}

	if(isset($_GET['id'])){
		$result = ORM::for_table($config['db']['pre'].'companies')
		->where('user_id' , $_SESSION['user']['id'])
		->where('id' , $_GET['id'])
		->where('status' , '1')
		->find_one();

		$name = $result['name'];
		$description = stripcslashes(nl2br($result['description']));
		$reg_no = $result['reg_no'];
		$city = $result['city'];
		$location = $result['location'];
        if(!empty($result['latlong'])){
            $latlong = explode(',', $result['latlong']);
            $mapLat = $latlong[0];
            $mapLong = $latlong[1];
        }
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
		$id = $_GET['id'];
	}

	//Print Template
	HtmlTemplate::display('add-company', array(
		'name' => $name,
		'description' => $description,
		'registration_no' => $reg_no,
		'city' => $city,
		'cityname' => get_cityName_by_id($city),
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
		'error' => $error
	));
	exit;
}else{
	headerRedirect($link['LOGIN']);
}
?>
