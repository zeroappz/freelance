<?php
$config['lang'] = check_user_lang();
$config['lang_code'] = get_current_lang_code();
$config['tpl_name'] = check_user_theme();

/**
 * Change user country
 *
 * @param string $country_code
 */
function change_user_country($country_code){
    if(get_option("country_type") == "multi"){
        $countryName = get_countryName_by_code($country_code);
        if(!$countryName) return;
        $_SESSION['user']['country'] = $country_code;
        set_user_currency($country_code);
    }
}

/**
 * Check user country
 *
 * @return mixed|string
 */
function check_user_country(){
    global $config;

    if(isset($_SESSION['user']['country']))
    {
        $country_code = $_SESSION['user']['country'];
    }
    else{
        if($config['country_type'] == 'multi'){
            $ip = encode_ip($_SERVER, $_ENV);

            try{
                require_once  ROOTPATH . '/includes/database/geoip/autoload.php';
                // Country DB
                $reader = new \MaxMind\Db\Reader(ROOTPATH .'/includes/database/geoip/geo_country.mmdb');
                $data = $reader->get($ip);
                $country_code = @strtoupper(trim($data['country']['iso_code']));
            } catch (Exception $e){
                error_log($e->getMessage());
                $country_code = $config['specific_country'];
            }

        }else{
            $country_code = $config['specific_country'];
        }

        if(isset($country_code) && $config['country_type'] == 'multi'){

            if(check_country_activated($country_code)){
                $_SESSION['user']['country'] = $country_code;
            }else{
                $_SESSION['user']['country'] = $config['specific_country'];
                $country_code = $_SESSION['user']['country'];
            }
        }else{
            $_SESSION['user']['country'] = $config['specific_country'];
            $country_code = $_SESSION['user']['country'];
        }
    }

    return $country_code;
}

/**
 * Add user option
 *
 * @param int $user_id
 * @param string $option
 * @param mixed $value
 * @return array|false|mixed
 * @throws Exception
 */
function add_user_option($user_id, $option, $value = null) {

    global $config;
    $option = trim($option);
    if ( empty($option) )
        return false;

    $option_id = ORM::for_table($config['db']['pre'].'user_options')->create();
    $option_id->user_id = $user_id;
    $option_id->option_name = $option;
    $option_id->option_value = $value;
    $option_id->save();

    return $option_id->id();
}

/**
 * Get user option
 *
 * @param int $user_id
 * @param string $option
 * @param null $default
 * @return array|mixed|null
 */
function get_user_option($user_id, $option, $default = null) {

    global $config;
    $option = trim($option);
    if ( empty($option) )
        return $default;

    $result = ORM::for_table($config['db']['pre'].'user_options')
        ->where('option_name', $option)
        ->where('user_id', $user_id)
        ->find_one();

    if ( isset($result['option_value']))
        return $result['option_value'];
    else
        return $default;
}

/**
 * Check user option exist
 *
 * @param int $user_id
 * @param string $option
 * @return bool
 */
function check_user_option_exist($user_id, $option) {

    global $config;
    $option = trim($option);
    if ( empty($option) )
        return false;

    $num_rows = ORM::for_table($config['db']['pre'].'user_options')
        ->where('option_name',$option)
        ->where('user_id', $user_id)
        ->count();
    if($num_rows != 0)
        return true;
    else
        return false;
}

/**
 * Update user option
 *
 * @param int $user_id
 * @param string $option
 * @param mixed $value
 * @return bool
 * @throws Exception
 */
function update_user_option($user_id, $option, $value) {

    global $config;
    $option = trim($option);
    if ( empty($option) )
        return false;

    if(check_user_option_exist($user_id, $option )){
        $pdo = ORM::get_db();
        $data = [
            'user_id' => $user_id,
            'option_value' => $value,
            'option_name' => $option
        ];
        $sql = "UPDATE ".$config['db']['pre']."user_options SET option_value=:option_value WHERE option_name=:option_name AND user_id=:user_id";
        $query_result = $pdo->prepare($sql)->execute($data);

        if (!$query_result)
            return false;
        else
            return true;
    }
    else{
        add_user_option($user_id,$option,$value);
        return true;
    }
}

/**
 * Delete user option
 *
 * @param int $user_id
 * @param string $option
 * @return bool
 */
function delete_user_option($user_id, $option) {

    global $config;
    $option = trim($option);
    if ( empty($option) )
        return false;

    $result = ORM::for_table($config['db']['pre'].'user_options')
        ->where('option_name',$option)
        ->where('user_id', $user_id)
        ->delete_many();

    if ( ! $result )
        return false;
    else
        return true;
}

/**
 * Get user plan id
 *
 * @return int|string|null
 */
function get_user_group(){
    global $config;
    $usergroup = 'free';
    if(isset($_SESSION['user']['id'])) {

        $user_info = ORM::for_table($config['db']['pre'].'user')
            ->select('group_id')
            ->find_one($_SESSION['user']['id']);

        $usergroup = isset($user_info['group_id'])? $user_info['group_id'] : 'free';

    }
    return $usergroup;
}

/**
 * Get user membership settings
 *
 * @return array
 */
function get_user_membership_settings()
{
    global $config;
    // Get usergroup details
    $group_id = get_user_group();

    // Get membership details
    switch ($group_id) {
        case 'free':
            $plan = json_decode(get_option('free_membership_plan'), true);
            $settings = $plan['settings'];
            break;
        case 'trial':
            $plan = json_decode(get_option('trial_membership_plan'), true);
            $settings = $plan['settings'];
            break;
        default:
            $plan = ORM::for_table($config['db']['pre'] . 'plans')
                ->select('settings')
                ->where('id', $group_id)
                ->find_one();
            if (!isset($plan['settings'])) {
                $plan = json_decode(get_option('free_membership_plan'), true);
                $settings = $plan['settings'];
            } else {
                $settings = json_decode($plan['settings'], true);
            }
            break;
    }
    return $settings;
}

/**
 * Get user membership settings by user id
 *
 * @param int $user_id
 * @return array|ORM|false
 */
function get_user_membership_detail($user_id){
    global $config;
    $info = ORM::for_table($config['db']['pre'].'upgrades')
        ->where('user_id', $user_id)
        ->find_one();
    if(!isset($info['sub_id'])){
        return json_decode(get_option('free_membership_plan'), true);
    }
    if($info['sub_id'] == 'trial'){
        $sub_info = json_decode(get_option('trial_membership_plan'), true);
    }else{
        $sub_info = ORM::for_table($config['db']['pre'].'plans')
            ->where('id', $info['sub_id'])
            ->find_one();

        if(!isset($sub_info['id'])){
            return json_decode(get_option('free_membership_plan'), true);
        }
    }
    return $sub_info;
}

/**
 * Set user currency by country code
 *
 * @param string $country_code
 * @return false|ORM
 */
function set_user_currency($country_code){

    global $config;

    $info = ORM::for_table($config['db']['pre'].'countries')
        ->select('currency_code')
        ->where('code', $country_code)
        ->find_one();
    $currency_code = $info['currency_code'];

    $currency_info = ORM::for_table($config['db']['pre'].'currencies')
        ->where('code', $currency_code)
        ->find_one();

    $config['currency_code'] = $currency_info['code'];
    $config['currency_sign'] = $currency_info['html_entity'];
    $config['currency_pos'] = $currency_info['in_left'];

    return $currency_info;
}

/**
 * Change user language
 *
 * @param string $lang_code
 */
function change_user_lang($lang_code){

    global $config;

    $lang_code = get_language_by_code($lang_code,true);
    if(!$lang_code) return;
    $cookie_name = "Quick_lang";
    $cookie_value = $lang_code['file_name'];
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
    if($config['userlangsel'] == '1')
    {
        $config['lang'] = $lang_code['file_name'];
        $config['lang_code'] = get_current_lang_code();
    }
}

/**
 * Check user language
 *
 * @return string
 */
function check_user_lang(){

    global $config;

    if($config['userlangsel'] == '1')
    {
        $cookie_name = "Quick_lang";
        if(isset($_COOKIE[$cookie_name])) {
            $config['lang'] = $_COOKIE[$cookie_name];
        }
    }
    return $config['lang'];
}

/**
 * Get current language
 *
 * @return string
 */
function get_current_lang_code(){
    global $config;

    $info = ORM::for_table($config['db']['pre'].'languages')
        ->select('code')
        ->where('file_name', $config['lang'])
        ->find_one();
    return strtolower($info['code']);
}

/**
 * Check user theme
 *
 * @return string
 */
function check_user_theme(){
    global $config;

    if($config['userthemesel'])
    {
        $cookie_name = "Quick_theme";
        if(isset($_COOKIE[$cookie_name])) {
            $config['tpl_name'] = $_COOKIE[$cookie_name];
        }
    }

    return $config['tpl_name'];
}

/**
 * Check email exist
 *
 * @param string $email
 * @return int
 */
function check_account_exists($email){
    global $config;

    $count = ORM::for_table($config['db']['pre'].'user')
        ->where('email', $email)
        ->count();

    // check existing email
    if ($count) {
        return $count;
    } else {
        return 0;
    }
}

/**
 * Check username exist
 *
 * @param string $username
 * @return int
 */
function check_username_exists($username){

    global $config;

    $count = ORM::for_table($config['db']['pre'].'user')
        ->where('username', $username)
        ->count();

    // check row exist
    if ($count) {
        return $count;
    } else {
        return 0;
    }
}

/**
 * Get user id by username
 *
 * @param string $username
 * @return int
 */
function get_user_id($username){

    global $config;

    $info = ORM::for_table($config['db']['pre'].'user')
        ->select('id')
        ->where('username', $username)
        ->find_one();

    if(isset($info['id'])){
        return $info['id'];
    }
    else{
        return FALSE;
    }
}

/**
 * Get user id by email
 *
 * @param string $email
 * @return int
 */
function get_user_id_by_email($email){

    global $config;

    $info = ORM::for_table($config['db']['pre'].'user')
        ->select('id')
        ->where('email', $email)
        ->find_one();

    if(isset($info['id'])){
        return $info['id'];
    }
    else{
        return FALSE;
    }
}

/**
 * Get username by email
 *
 * @param string $email
 * @return string|bool
 */
function get_username_by_email($email){

    global $config;

    $info = ORM::for_table($config['db']['pre'].'user')
        ->select('username')
        ->where('email', $email)
        ->find_one();

    if(isset($info['username'])){
        return $info['username'];
    }
    else{
        return FALSE;
    }
}

/**
 * Create user session
 *
 * @param int $userid
 * @param string $username
 * @param string $password
 * @param string $user_type
 */
function create_user_session($userid, $username, $password, $user_type = ''){
    $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.

    $user_id = preg_replace("/[^0-9]+/", "", $userid); // XSS protection as we might print this value
    $_SESSION['user']['id']  = $user_id;

    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); // XSS protection as we might print this value
    $_SESSION['user']['username'] = $username;

    $_SESSION['user']['login_string'] = hash('sha512', $password . $user_browser);

    $_SESSION['user']['user_type'] = $user_type;
}

/**
 * User login
 *
 * @param string $email
 * @param string $password
 * @return array|false
 */
function userlogin($email, $password)
{
    global $config, $user_id, $username,  $db_password, $where;

    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';

    if(!preg_match("/^[[:alnum:]]+$/", $email))
    {
        if(!preg_match($regex,$email))
        {
            return false;
        }
        else{
            //checking in email
            $where = 'email';
        }
    }
    else{
        //checking in username
        $where = 'username';
    }

    $num_rows = ORM::for_table($config['db']['pre'].'user')
        ->select_many('id', 'status', 'username', 'password_hash')
        ->where($where, $email)
        ->count();

    if ($num_rows >= 1) {
        $info = ORM::for_table($config['db']['pre'].'user')
            ->select_many('id', 'status', 'username', 'password_hash', 'user_type')
            ->where($where, $email)
            ->find_one();

        $user_id = $info['id'];
        $status = $info['status'];
        $username = $info['username'];
        $db_password = $info['password_hash'];

        // If the user exists we check if the account is locked
        // from too many login attempts

        /*if (checkbrute($user_id) == true) {
            // Account is locked
            // Send an email to user saying their account is locked
            return false;
        } else {
            // Check if the password in the database matches
            // the password the user submitted. We are using
            // the password_verify function to avoid timing attacks.

        }*/
        if (password_verify($password, $db_password)) {
            // Password is correct!

            // Login successful.
            $userinfo = array();
            $userinfo['id'] = $user_id;
            $userinfo['status'] = $status;
            $userinfo['username'] = $username;
            $userinfo['password'] = $db_password;
            $userinfo['user_type'] = $info['user_type'];

            return $userinfo;

        } else {
            // Password is not correct
            // We record this attempt in the database
            $now = time();
            $login_attempts = ORM::for_table($config['db']['pre'].'login_attempts')->create();
            $login_attempts->user_id = $user_id;
            $login_attempts->time = $now;
            $login_attempts->save();

            return false;
        }
    } else {
        // No user exists.
        return false;
    }
	
}

/**
 * Check user logged in
 *
 * @return bool
 */
function checkloggedin()
{
    global $config,$password;

    // Check if all session variables are set
    if (isset($_SESSION['user']['id'],
        $_SESSION['user']['username'],
        $_SESSION['user']['login_string'])) {

        $user_id = $_SESSION['user']['id'];
        $login_string = $_SESSION['user']['login_string'];
        $username = $_SESSION['user']['username'];

        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        $result = ORM::for_table($config['db']['pre'].'user')
            ->select('password_hash')
            ->where('id', $user_id)
            ->find_one();

        if (!empty($result)) {

            $login_check = hash('sha512', $result['password_hash'] . $user_browser);

            if (hash_equals($login_check, $login_string) ){
                // Logged In!!!!
                return true;
            } else {
                // Not logged in
                return false;
            }
        } else {
            // Not logged in
            return false;
        }
    } else {
        // Not logged in
        return false;
    }
}

/**
 * Create username by name
 *
 * @param string $title
 * @return string
 */
function createusernameslug($title){
    global $config;
    $numHits = 0;
    $slug = $title;

    $numHits = ORM::for_table($config['db']['pre'].'user')
        ->where_like('username', ''.$slug.'%')
        ->count();

    return ($numHits > 0) ? ($slug.$numHits) : $slug;
}

/**
 * Create/get user for social login
 *
 * @param array $userData
 * @param string $picname
 * @return array
 * @throws Exception
 */
function checkSocialUser($userData, $picname){

    global $config;

    if(!empty($userData)){

        $fullname = $userData['first_name'].' '.$userData['last_name'];
        $fbfirstname = $userData['first_name'];

        // Check whether user data already exists in database
        $info = ORM::for_table($config['db']['pre'].'user')
            ->where_any_is(array(
                array('email' => $userData['email']),
                array('oauth_uid' => $userData['oauth_uid'])))
            ->find_one();

        if(!empty($info)){
            $userData = $info;
        }else{
            if(check_username_exists($fbfirstname)){
                $username = createusernameslug($fbfirstname);
            }
            else{
                $username = $fbfirstname;
            }

            $location = getLocationInfoByIp();
            $gender = ($userData['gender'] == "") ? "Male" : $userData['gender'];
            $password = get_random_id();
            $pass_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 13]);
            // Insert user data
            $now = date("Y-m-d H:i:s");

            $insert_user = ORM::for_table($config['db']['pre'].'user')->create();
            $insert_user->oauth_provider = $userData['oauth_provider'];
            $insert_user->oauth_uid = $userData['oauth_uid'];
            $insert_user->status = '1';
            $insert_user->name = $fullname;
            $insert_user->username = $username;
            $insert_user->password_hash = $pass_hash;
            $insert_user->email = $userData['email'];
            $insert_user->sex = $gender;
            $insert_user->image = $picname;
            $insert_user->oauth_link = $userData['link'];
            $insert_user->created_at = $now;
            $insert_user->updated_at = $now;
            $insert_user->country = $location['country'];
            $insert_user->city = $location['city'];
            $insert_user->save();

            $user_id = $insert_user->id();
            // Get user data from the database
            $userData['id'] = $user_id;
            $userData['username'] = $username;
            $userData['password_hash'] = $pass_hash;
            $userData['status'] = 1;
            $userData['user_type'] = null;
        }
    }else{
        $userData = array();
    }

    // Return user data
    return $userData;
}

/**
 * Get user's data
 *
 * @param int|null $username
 * @param int|null $userid
 * @return array|int
 */
function get_user_data($username=null, $userid=null){

    global $config;

    if($username != null){
        $info = ORM::for_table($config['db']['pre'].'user')
            ->where('username', $username)
            ->find_one();
    }
    else{
        $info = ORM::for_table($config['db']['pre'].'user')
            ->where('id', $userid)
            ->find_one();
    }

    if (isset($info['id'])) {
        $userinfo['id']         = $info['id'];
        $userinfo['username']   = $info['username'];
        $userinfo['user_type']  = $info['user_type'];
        $userinfo['balance']  = $info['balance'];
        $userinfo['name']       = $info['name'];
        $userinfo['email']      = $info['email'];
        $userinfo['confirm']    = $info['confirm'];
        $userinfo['password']   = $info['password_hash'];
        $userinfo['forgot']     = $info['forgot'];
        $userinfo['status']     = $info['status'];
        $userinfo['view']       = $info['view'];
        $userinfo['image']      = $info['image'];
        $userinfo['tagline']    = stripslashes($info['tagline']);
        $userinfo['description']= stripslashes($info['description']);
        $userinfo['category']   = $info['category'];
        $userinfo['subcategory']= $info['subcategory'];
        $userinfo['salary_min'] = $info['salary_min'];
        $userinfo['salary_max'] = $info['salary_max'];
        $userinfo['dob']        = $info['dob'];
        $userinfo['sex']        = $info['sex'];
        $userinfo['phone']      = $info['phone'];
        $userinfo['postcode']   = $info['postcode'];
        $userinfo['address']    = $info['address'];
        $userinfo['country']    = $info['country'];
        $userinfo['city']       = $info['city'];
        $userinfo['city_code']  = $info['city_code'];
        $userinfo['state_code'] = $info['state_code'];
        $userinfo['country_code']= $info['country_code'];
        $userinfo['lastactive'] = $info['lastactive'];
        $userinfo['online']     = $info['online'];
        $userinfo['created_at'] = $info['created_at'];
        $userinfo['updated_at'] = $info['updated_at'];

        $userinfo['facebook']   = $info['facebook'];
        $userinfo['twitter']    = $info['twitter'];
        $userinfo['googleplus'] = $info['googleplus'];
        $userinfo['instagram']  = $info['instagram'];
        $userinfo['linkedin']   = $info['linkedin'];
        $userinfo['youtube']    = $info['youtube'];

        $userinfo['notify']     = $info['notify'];
        $userinfo['notify_cat'] = $info['notify_cat'];
        $userinfo['website']    = $info['website'];
        return $userinfo;
    }
    else{
        return 0;
    }
}

/**
 * Update last active time
 */
function update_lastactive(){

    global $config;

    if(isset($_SESSION['user']['id']))
    {
        $person = ORM::for_table($config['db']['pre'].'user')->find_one($_SESSION['user']['id']);
        $person->set_expr('lastactive', 'NOW()');
        $person->save();
    }
}

/**
 * Send forgot email
 *
 * @param string $to
 * @param int $id
 */
function send_forgot_email($to, $id)
{
    global $config,$lang,$link;
	$time = time();
	$rand = randomPassword();
	$forgot = md5($time.'_:_'.$rand.'_:_'.$to);

    $person = ORM::for_table($config['db']['pre'].'user')->find_one($id);
    $person->forgot = $forgot;
    $person->save();

    $get_userdata = get_user_data(null,$id);
    $to_name = $get_userdata['name'];

    $html = $config['email_sub_forgot_pass'];
    $html = str_replace ('EMAIL', $to, $html);
    $html = str_replace ('USER_FULLNAME', $to_name, $html);
    $email_subject = $html;

    $forget_password_link = $config['site_url']."login?forgot=".$forgot."&r=".$rand."&e=".$to."&t=".$time;

    $html = $config['email_message_forgot_pass'];
    $html = str_replace ('FORGET_PASSWORD_LINK', $forget_password_link, $html);
    $html = str_replace ('EMAIL', $to, $html);
    $html = str_replace ('USER_FULLNAME', $to_name, $html);
    $email_body = $html;

    email($to,$to_name,$email_subject,$email_body);
}

/**
 * Get random password
 *
 * @return string
 */
function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

/**
 * Update profile view
 *
 * @param int $user_id
 */
function update_profileview($user_id){

    global $config;

    $person = ORM::for_table($config['db']['pre'].'user')->find_one($user_id);
    $person->set_expr('view', 'view+1');
    $person->save();
}

/**
 * Secure session start
 */
function sec_session_start() {
    define("CAN_REGISTER", "any");
    define("DEFAULT_ROLE", "member");
    define("SECURE", FALSE);    // FOR DEVELOPMENT ONLY!!!!
    $session_name = 'sec_session_id';   // Set a custom session name
    $secure = SECURE;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
    // Sets the session name to the one set above.
    session_name($session_name);
    session_start();            // Start the PHP session
    session_regenerate_id();    // regenerated the session, delete the old one.
}

/**
 * Limit users fail login attempts
 *
 * @param int $user_id
 * @return bool
 */
function checkbrute($user_id) {

    global $config;
    // Get timestamp of current time
    $now = time();

    // All login attempts are counted from the past 2 hours.
    $valid_attempts = $now - (2 * 60 * 60);

    $num_rows = ORM::for_table($config['db']['pre'].'login_attempts')
        ->where_any_is(array(
            array('user_id' => $user_id, 'time' => $valid_attempts)), array('time' => '>'))
        ->count();

    // If there have been more than 5 failed login
    if ($num_rows > 5) {
        return true;
    } else {
        return false;
    }
}


/**
 * Check negative balance
 *
 * @return bool
 */
function check_negative_balance(){
    if(isset($_SESSION['user']['id']))
    {
        $userdata = get_user_data(null,$_SESSION['user']['id']);
        if($userdata['balance'] < 0)
        {
            return true;
        }
    }
    return false;
}


