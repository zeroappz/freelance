<?php
/**
 * Check rating exist
 *
 * @param int $project_id
 * @return bool
 */
function rating_exist($project_id){
    global $config;

    if($_SESSION['user']['user_type'] == 'employer'){
        $num_rows = ORM::for_table($config['db']['pre'] . 'reviews')
            ->where(array(
                'project_id'=> $project_id,
                'employer_id'=> validate_input($_SESSION['user']['id']),
                'rated_by'=> 'employer'
            ))
            ->count();
    }else{
        $num_rows = ORM::for_table($config['db']['pre'] . 'reviews')
            ->where(array(
                'project_id'=> $project_id,
                'freelancer_id'=> validate_input($_SESSION['user']['id']),
                'rated_by'=> 'user'
            ))
            ->count();
    }

    if($num_rows == 1)
        return true;
    else
        return false;
}

/**
 * Check product is favorite
 *
 * @param int $product_id
 * @return bool
 */
function check_product_favorite($product_id){

    global $config;

    if(checkloggedin()) {
        $num_rows = ORM::for_table($config['db']['pre'].'favads')
            ->where(array(
                'product_id' => $product_id,
                'user_id' => $_SESSION['user']['id']
            ))
            ->count();
        if($num_rows == 1)
            return true;
        else
            return false;

    }else{
        return false;
    }
}

/**
 * Check user is favorite
 *
 * @param int $user_id
 * @return bool
 */
function check_user_favorite($user_id){

    global $config;

    if(checkloggedin()) {
        $num_rows = ORM::for_table($config['db']['pre'].'fav_users')
            ->where(array(
                'fav_user_id' => $user_id,
                'user_id' => $_SESSION['user']['id']
            ))
            ->count();
        if($num_rows == 1)
            return true;
        else
            return false;

    }else{
        return false;
    }
}

/**
 * Check job applied
 *
 * @param int $job_id
 * @return bool
 */
function check_user_applied($job_id){

    global $config;

    if(checkloggedin()) {
        $num_rows = ORM::for_table($config['db']['pre'].'user_applied')
            ->where(array(
                'job_id' => $job_id,
                'user_id' => $_SESSION['user']['id']
            ))
            ->count();
        if($num_rows == 1)
            return true;
        else
            return false;

    }else{
        return false;
    }
}

/**
 * Check bid exists
 *
 * @param int $product_id
 * @return bool
 */
function check_bid_exist($product_id){

    global $config;

    if(checkloggedin()) {
        $num_rows = ORM::for_table($config['db']['pre'].'bids')
            ->where(array(
                'project_id' => $product_id,
                'user_id' => $_SESSION['user']['id']
            ))
            ->count();
        if($num_rows == 1)
            return true;
        else
            return false;

    }else{
        return false;
    }
}

/**
 * Check login user is the product owner
 *
 * @param int $product_id
 * @return bool
 */
function check_valid_author($product_id){

    global $config;

    if(checkloggedin()) {
        $num_rows = ORM::for_table($config['db']['pre'].'product')
            ->where(array(
                'id' => $product_id,
                'user_id' => $_SESSION['user']['id']
            ))
            ->count();
        if($num_rows == 1)
            return true;
        else
            return false;

    }else{
        return false;
    }
}

/**
 * Check product status
 *
 * @param int $product_id
 * @return string|null
 */
function check_item_status($product_id){

    global $config;
    $info = ORM::for_table($config['db']['pre'].'product')
        ->select('status')
        ->where('id',$product_id)
        ->find_one();

    return $info->status;
}

/**
 * Check user can resubmit
 *
 * @param int $product_id
 * @return bool
 */
function check_valid_resubmission($product_id){

    global $config;

    if(checkloggedin()) {
        $num_rows = ORM::for_table($config['db']['pre'].'product_resubmit')
            ->where(array(
                'product_id' => $product_id,
                'user_id' => $_SESSION['user']['id']
            ))
            ->count();
        if($num_rows == 1)
            return false;
        else
            return true;

    }else{
        return false;
    }
}

/**
 * Create product slug
 *
 * @param string $title
 * @return string
 */
function create_post_slug($title){
    global $config;
    $slug = create_slug($title);
    $numHits = ORM::for_table($config['db']['pre'].'product')
        ->where_like('slug', ''.$slug.'%')
        ->count();

    return ($numHits > 0) ? ($slug.'-'.$numHits) : $slug;
}

/**
 * Check category exists
 *
 * @param int $cat_id
 * @return int
 */
function check_category_exists($cat_id){
    global $config;
    $count = ORM::for_table($config['db']['pre'].'catagory_main')
        ->where('cat_id', $cat_id)
        ->count();

    // check existing email
    if ($count) {
        return $count;
    } else {
        return 0;
    }
}

/**
 * Check sub category exists
 *
 * @param int $cat_id
 * @return int
 */
function check_sub_category_exists($cat_id){
    global $config;
    $count = ORM::for_table($config['db']['pre'].'catagory_sub')
        ->where('sub_cat_id', $cat_id)
        ->count();

    // check existing email
    if ($count) {
        return $count;
    } else {
        return 0;
    }
}

/**
 * Get category id by slug
 *
 * @param string $slug
 * @return int|bool
 */
function get_category_id_by_slug($slug){
    global $config;
    $info = ORM::for_table($config['db']['pre'].'catagory_main')
        ->select('cat_id')
        ->where('slug', $slug)
        ->find_one();

    if(!empty($info)){
        return $info['cat_id'];
    }else{
        $info = ORM::for_table($config['db']['pre'].'category_translation')
            ->select('translation_id')
            ->where(array(
                'slug' => $slug,
                'category_type' => 'main',
            ))
            ->find_one();
        return $info['translation_id'];
    }
}

/**
 * Get subcategory id by slug
 *
 * @param string $slug
 * @return int|bool
 */
function get_subcategory_id_by_slug($slug){
    global $config;
    $info = ORM::for_table($config['db']['pre'].'catagory_sub')
        ->select('sub_cat_id')
        ->where('slug', $slug)
        ->find_one();

    if(!empty($info)){
        return $info['sub_cat_id'];
    }else{
        $info = ORM::for_table($config['db']['pre'].'category_translation')
            ->select('translation_id')
            ->where(array(
                'slug' => $slug,
                'category_type' => 'sub',
            ))
            ->find_one();
        return $info['translation_id'];
    }
}

/**
 * Create category slug
 *
 * @param string $title
 * @return string
 */
function create_category_slug($title){
    global $config;
    $slug = create_slug($title);
    $numHits = ORM::for_table($config['db']['pre'].'catagory_main')
        ->where_like('slug', ''.$slug.'%')
        ->count();

    return ($numHits > 0) ? ($slug.'-'.$numHits) : $slug;
}

/**
 * Create sub category slug
 *
 * @param string $title
 * @return string
 */
function create_sub_category_slug($title){
    global $config;
    $slug = create_slug($title);
    $numHits = ORM::for_table($config['db']['pre'].'catagory_sub')
        ->where_like('slug', ''.$slug.'%')
        ->count();

    return ($numHits > 0) ? ($slug.'-'.$numHits) : $slug;
}

/**
 * Create category translation slug
 *
 * @param string $title
 * @return string
 */
function create_category_translation_slug($title){
    global $config;
    $slug = create_slug($title);
    $numHits = ORM::for_table($config['db']['pre'].'category_translation')
        ->where_like('slug', ''.$slug.'%')
        ->count();

    return ($numHits > 0) ? ($slug.'-'.$numHits) : $slug;
}

/**
 * Get category translation
 *
 * @param string $cattype
 * @param int $catid
 * @return false|ORM
 */
function get_category_translation($cattype, $catid){
    global $config;
    $info = ORM::for_table($config['db']['pre'].'category_translation')
        ->select_many('title','slug')
        ->where(array(
            'translation_id' => $catid,
            'lang_code' => $config['lang_code'],
            'category_type' => $cattype,
        ))
        ->find_one();
    return $info;
}

/**
 * Delete category translation
 *
 * @param string $type
 * @param int $translation_id
 * @return bool
 */
function delete_language_translation($type, $translation_id){
    global $config;
    return ORM::for_table($config['db']['pre'].'category_translation')
        ->where(array(
            'translation_id' => $translation_id,
            'category_type' => $type
        ))
        ->delete_many();
}

/**
 * Get all categories
 *
 * @param string $selected
 * @param string $selected_text
 * @return array
 */
function get_maincategory($selected="", $selected_text='selected'){
    global $config,$link;
    $cat = array();

    $result = ORM::for_table($config['db']['pre'].'catagory_main')
        ->order_by_asc('cat_order')
        ->find_many();
    foreach ($result as $info) {
        $cat[$info['cat_id']]['id'] = $info['cat_id'];
        $cat[$info['cat_id']]['icon'] = $info['icon'];
        $cat[$info['cat_id']]['picture'] = $info['picture'];

        if($config['lang_code'] != 'en' && $config['userlangsel'] == '1'){
            $maincat = get_category_translation("main",$info['cat_id']);
            $cat[$info['cat_id']]['name'] = $maincat['title'];
            $cat[$info['cat_id']]['slug'] = $maincat['slug'];
        }else{
            $cat[$info['cat_id']]['name'] = $info['cat_name'];
            $cat[$info['cat_id']]['slug'] = $info['slug'];
        }

        $cat[$info['cat_id']]['link'] = $config['site_url'].'category/'.$cat[$info['cat_id']]['slug'];
        $cat[$info['cat_id']]['project_link'] = $link['SEARCH_PROJECTS'].'/'.$cat[$info['cat_id']]['slug'];
        if($selected!="")
        {
            if(is_array($selected))
            {
                foreach($selected as $select)
                {

                    $select = strtoupper(str_replace('"','',$select));
                    if($select == $info['cat_id'])
                    {
                        $cat[$info['cat_id']]['selected'] = $selected_text;
                    }
                }
            }
            else{
                if($selected==$info['cat_id'] || $selected==$info['cat_name'])
                {
                    $cat[$info['cat_id']]['selected'] = $selected_text;
                }else{
                    $cat[$info['cat_id']]['selected'] = "";
                }
            }
        }else
        {
            $cat[$info['cat_id']]['selected'] = "";
        }

        // check sub-cat exist or not
        $sub_cat = ORM::for_table($config['db']['pre'].'catagory_sub')
            ->where('main_cat_id',$info['cat_id'])
            ->count('sub_cat_id');

        if($sub_cat > 0){
            $cat[$info['cat_id']]['sub_cat'] = 1;
        }else{
            $cat[$info['cat_id']]['sub_cat'] = 0;
        }
    }

    return $cat;
}

/**
 * get category by id
 *
 * @param int $id
 * @return false|ORM
 */
function get_maincat_by_id($id){
    global $config;

    $info = ORM::for_table($config['db']['pre'].'catagory_main')
        ->where('cat_id',$id)
        ->find_one();
    if($config['lang_code'] != 'en' && $config['userlangsel'] == '1'){
        $maincat = get_category_translation("main",$info['cat_id']);
        $info['cat_name'] = $maincat['title'];
        $info['slug'] = $maincat['slug'];
    }
    return $info;
}

/**
 * Get all subcategories
 *
 * @return array
 */
function get_subcategories(){
    global $config;
    $info = ORM::for_table($config['db']['pre'].'catagory_sub')
        ->find_many();

    $subcat = array();
    foreach ($info as $key => $value){
        $subcat[$key]['id'] = $value['sub_cat_id'];
        $subcat[$key]['main_cat_id'] = $value['main_cat_id'];
        $subcat[$key]['name'] = $value['sub_cat_name'];
        $subcat[$key]['slug'] = $value['slug'];

        if($config['lang_code'] != 'en' && $config['userlangsel'] == '1'){
            $subcat_trans = get_category_translation("sub",$value['sub_cat_id']);
            $subcat[$key]['name'] = $subcat_trans['title'];
            $subcat[$key]['slug'] = $subcat_trans['slug'];
        }
    }

    return $subcat;
}

/**
 * get subcategory by id
 *
 * @param int
 * @return false|ORM
 */
function get_subcat_by_id($id){
    global $config;
    $info = ORM::for_table($config['db']['pre'].'catagory_sub')
        ->where('sub_cat_id',$id)
        ->find_one();
    if($config['lang_code'] != 'en' && $config['userlangsel'] == '1'){
        $subcat = get_category_translation("sub",$info['sub_cat_id']);
        $info['sub_cat_name'] = $subcat['title'];
        $info['slug'] = $subcat['slug'];
    }
    return $info;
}

/**
 * get subcategory by category id
 *
 * @param int $category_id
 * @param false $adcount
 * @param string $selected
 * @param string $selected_text
 * @return array
 */
function get_subcat_of_maincat($category_id, $adcount=false, $selected="", $selected_text='selected'){
    global $config,$link;
    $subcat = array();
    $result = ORM::for_table($config['db']['pre'].'catagory_sub')
        ->where('main_cat_id',$category_id)
        ->order_by_asc('cat_order')
        ->find_many();

    foreach($result as $info){
        $subcat[$info['sub_cat_id']]['id'] = $info['sub_cat_id'];
        $subcat[$info['sub_cat_id']]['photo_show'] = $info['photo_show'];
        $subcat[$info['sub_cat_id']]['price_show'] = $info['price_show'];

        if($config['lang_code'] != 'en' && $config['userlangsel'] == '1'){
            $subcategory = get_category_translation("sub",$info['sub_cat_id']);

            $subcat[$info['sub_cat_id']]['name'] = $subcategory['title'];
            $subcat[$info['sub_cat_id']]['slug'] = $subcategory['slug'];
        }else{
            $subcat[$info['sub_cat_id']]['name'] = $info['sub_cat_name'];
            $subcat[$info['sub_cat_id']]['slug'] =  $info['slug'];
        }

        $get_main = get_maincat_by_id($category_id);
        $category_slug = $get_main['slug'];

        $subcat_slug = $subcat[$info['sub_cat_id']]['slug'];
        $subcat[$info['sub_cat_id']]['link'] = $config['site_url'].'category/'.$category_slug.'/'.$subcat_slug;
        $subcat[$info['sub_cat_id']]['project_link'] = $link['SEARCH_PROJECTS'].'/'.$category_slug.'/'.$subcat_slug;
        if($adcount){
            $subcat[$info['sub_cat_id']]['adcount'] = get_items_count(false,"active",false,$info['sub_cat_id'],null,true);
        }

        if($selected!="") {
            if($selected==$info['sub_cat_id'] || $selected==$info['sub_cat_name'])
            {
                $subcat[$info['sub_cat_id']]['selected'] = $selected_text;
            }
        }else
        {
            $subcat[$info['sub_cat_id']]['selected'] = "";
        }
    }

    return $subcat;
}

/**
 * Get categories dropdown
 *
 * @param array $lang
 * @return string
 */
function get_categories_dropdown($lang){
    global $config;
    $dropdown = '<ul class="dropdown-menu category-change" id="category-change">
                          <li><a href="#" class="no-arrow" data-cat-type="all"><i class="fa fa-th"></i>'.__("All Categories").'</a></li>';

    $result1 = ORM::for_table($config['db']['pre'].'catagory_main')
        ->order_by_asc('cat_order')
        ->find_many();

    foreach($result1 as $info1){

        $cat_picture = $info1['picture'];
        $cat_icon = $info1['icon'];
        $catname = $info1['cat_name'];
        $cat_id = $info1['cat_id'];
        $cat_picture = $info1['picture'];
        if($config['lang_code'] != 'en' && $config['userlangsel'] == '1'){
            $maincat = get_category_translation("main",$info1['cat_id']);
            $catname = $maincat['title'];
        }

        if($cat_picture != ""){
            $icon = '<img src="'.$cat_picture.'" style="width: 20px;"/>';
        }else{
            $icon = '<i class="'.$cat_icon.'"></i>';
        }


        $result = ORM::for_table($config['db']['pre'].'catagory_sub')
            ->where('main_cat_id',$cat_id)
            ->order_by_asc('cat_order')
            ->find_many();
        if(count($result) > 0){
            $dropdown .= '<li><a href="#" data-ajax-id="'.$cat_id.'" data-cat-type="maincat">'.$icon.' '.$catname.'</a><span class="dropdown-arrow"><i class="fa fa-angle-right"></i></span><ul>';
        }else{
            $dropdown .= '<li><a href="#" class="no-arrow" data-ajax-id="'.$cat_id.'" data-cat-type="maincat">'.$icon.' '.$catname.'</a>';
        }
        foreach($result as $info){
            $subcat_id = $info['sub_cat_id'];

            if($config['lang_code'] != 'en' && $config['userlangsel'] == '1'){
                $subcat = get_category_translation("sub",$info['sub_cat_id']);
                $subcat_name = $subcat['title'];
            }else{
                $subcat_name = $info['sub_cat_name'];
            }

            $dropdown .= '<li><a href="#" data-ajax-id="'.$subcat_id.'" data-cat-type="subcat">'.$subcat_name.'</a></li>';
        }
        if(count($result) > 0){
            $dropdown .= '</ul>';
        }

        $dropdown .= '</li>';
    }

    $dropdown .= '</ul>';

    return $dropdown;
}

/**
 * get categories and subcategories
 *
 * @param array $selected
 * @param string $selected_text
 * @return array
 */
function get_categories($selected=array(), $selected_text='selected'){
    global $config;

    $k = 1;
    $k2 = 2;
    $jobtypes = array();
    $jobtypes2 = array();
    $parents = array();

    $result = ORM::for_table($config['db']['pre'].'catagory_sub')
        ->order_by_asc('cat_order')
        ->find_many();

    foreach($result as $info){
        if(!isset($info['parent_id']))
        {
            $info['parent_id'] = 0;
        }
        else
        {
            if(isset($parents[$info['parent_id']]))
            {
                $parents[$info['parent_id']] = ($parents[$info['parent_id']]+1);
            }
            else
            {
                $parents[$info['parent_id']] = 1;
            }
        }

        if($info['main_cat_id'] == $k2)
        {
            $jobtypes[$info['parent_id']][$info['sub_cat_id']]['sec'] = 'show';
            $k2++;
        }
        else
        {
            $jobtypes[$info['parent_id']][$info['sub_cat_id']]['sec'] = $k2;
        }
        if($info['main_cat_id'] == $k)
        {
            $info1 = ORM::for_table($config['db']['pre'].'catagory_main')
                ->where('cat_id',$info['main_cat_id'])
                ->find_one();

            $jobtypes[$info['parent_id']][$info['sub_cat_id']]['icon'] = $info1['icon'];
            $jobtypes[$info['parent_id']][$info['sub_cat_id']]['main_title'] = $info1['cat_name'];
            $jobtypes[$info['parent_id']][$info['sub_cat_id']]['main_id'] = $info1['cat_id'];
            $jobtypes[$info['parent_id']][$info['sub_cat_id']]['show'] = 'yes';

            if($config['lang_code'] != 'en' && $config['userlangsel'] == '1'){
                $maincat = get_category_translation("main",$info1['cat_id']);
                $jobtypes[$info['parent_id']][$info['sub_cat_id']]['main_title'] = $maincat['title'];
            }

            if($k == 1)
            {
                $jobtypes[$info['parent_id']][$info['sub_cat_id']]['select'] = 'show';
            }

            $k++;

        }
        else
        {
            $jobtypes[$info['parent_id']][$info['sub_cat_id']]['show'] = 'no';
        }

        if($info['main_cat_id']++)
        {
            $jobtypes[$info['parent_id']][$info['sub_cat_id']]['section'] = 'show';
        }
        else
        {
            $jobtypes[$info['parent_id']][$info['sub_cat_id']]['section'] = 'notshow';
        }


        if($config['lang_code'] != 'en' && $config['userlangsel'] == '1'){
            $subcat = get_category_translation("sub",$info['sub_cat_id']);
            $jobtypes[$info['parent_id']][$info['sub_cat_id']]['title'] = $subcat['title'];
        }else{
            $jobtypes[$info['parent_id']][$info['sub_cat_id']]['title'] = stripslashes($info['sub_cat_name']);
        }
        $jobtypes[$info['parent_id']][$info['sub_cat_id']]['id'] = $info['sub_cat_id'];
        $jobtypes[$info['parent_id']][$info['sub_cat_id']]['selected'] = '';
        $jobtypes[$info['parent_id']][$info['sub_cat_id']]['parent_id'] = $info['parent_id'];
        $jobtypes[$info['parent_id']][$info['sub_cat_id']]['catcount'] = 0;
        $jobtypes[$info['parent_id']][$info['sub_cat_id']]['counter'] = 0;
        $jobtypes[$info['parent_id']][$info['sub_cat_id']]['totalads'] = get_items_count(false,"active",$info['sub_cat_id']);
        foreach($selected as $select)
        {
            if($select==$info['sub_cat_id'])
            {
                $jobtypes[$info['parent_id']][$info['sub_cat_id']]['selected'] = $selected_text;
            }
        }
    }

    foreach($jobtypes as $key=>$value)
    {
        foreach($value as $key2=>$value2)
        {
            if(isset($parents[$key2]))
            {
                $jobtypes[$key][$key2]['catcount']  = $parents[$key2];
            }
        }
    }

    $counter = 1;

    foreach($jobtypes[0] as $key=>$value)
    {
        $value['counter'] = $counter;
        if($value['catcount'])
        {
            $value['ctype'] = 1;
        }
        else
        {
            $value['ctype'] = 0;
        }

        $jobtypes2[$key] =  $value;
        $counter++;

        if(isset($jobtypes[$key]))
        {
            foreach($jobtypes[$key] as $key2=>$value2)
            {
                $value2['counter'] = $counter;
                $value2['ctype'] = 2;

                $jobtypes2[$key2] =  $value2;

                $counter++;
            }
        }
    }

    return $jobtypes2;

}

/**
 * Get product details by id
 *
 * @param int $product_id
 * @return array|false
 */
function get_item_by_id($product_id){
    global $config;
    $iteminfo = array();

    $info = ORM::for_table($config['db']['pre'].'product')
        ->where('id',$product_id)
        ->find_one();

    if(!empty($info)){

        $iteminfo['id'] = $info['id'];
        $iteminfo['title'] = $info['product_name'];
        $iteminfo['location'] = $info['location'];
        $iteminfo['city'] = get_cityName_by_id($info['city']);
        $iteminfo['state'] = get_stateName_by_id($info['state']);
        $iteminfo['country'] = get_countryName_by_code($info['country']);
        $iteminfo['status'] = $info['status'];
        $iteminfo['view'] = $info['view'];
        $iteminfo['created_at'] = timeAgo($info['created_at']);

        $get_main = get_maincat_by_id($info['category']);
        $get_sub = get_subcat_by_id($info['sub_category']);
        $iteminfo['category'] = $get_main['cat_name'];
        $iteminfo['sub-category'] = $get_sub['sub_cat_name'];


        $item_author_id = $info['user_id'];
        $info2 = get_user_data(null,$item_author_id);

        $iteminfo['author_id'] = $item_author_id;
        $iteminfo['author_name'] = ucfirst($info2['name']);
        $iteminfo['author_username'] = ucfirst($info2['username']);
        $iteminfo['author_email'] = $info2['email'];
        $iteminfo['author_image'] = $info2['image'];

        return $iteminfo;
    }
    else {
        return false;
    }
}

/**
 * Get projects
 *
 * @param int|null $userid
 * @param string|null $status
 * @param bool $premium
 * @param int|null $page
 * @param int|null $limit
 * @param string $sort
 * @param bool $location
 * @param bool $order
 * @param string $sort_order
 * @return array
 */
function get_projects($userid=null, $status=null, $premium=false, $page=null, $limit=null, $sort="p.id", $location=false, $order=false, $sort_order="DESC"){

    global $config,$lang,$link;
    $where = '';
    $item = array();
    if($userid != null){
        if($where == '')
            $where .= "where p.user_id = '".$userid."'";
        else
            $where .= " AND p.user_id = '".$userid."'";
    }
    if($status != null){
        if($where == '')
            $where .= "where p.status = '".$status."'";
        else
            $where .= " AND p.status = '".$status."'";
    }

    if($premium){
        if($where == '')
            $where .= "where (p.featured = '1' or p.urgent = '1' or p.highlight = '1')";
        else
            $where .= " AND (p.featured = '1' or p.urgent = '1' or p.highlight = '1')";
    }

    if($order){
        $order_by = "
      (CASE
        WHEN p.featured = '1' and p.urgent = '1' and p.highlight = '1' THEN 1
        WHEN p.urgent = '1' and p.featured = '1' THEN 2
        WHEN p.urgent = '1' and p.highlight = '1' THEN 3
        WHEN p.featured = '1' and p.highlight = '1' THEN 4
        WHEN p.urgent = '1' THEN 5
        WHEN p.featured = '1' THEN 6
        WHEN p.highlight = '1' THEN 7
        ELSE 8
      END), ".$sort." ".$sort_order;
    }else{
        $order_by = $sort." ".$sort_order;
    }

    $pagelimit = "";
    if($page != null && $limit != null){
        $pagelimit = "LIMIT  ".($page-1)*$limit.",".$limit;
    }

    $query = "SELECT p.*
FROM `".$config['db']['pre']."project`  p
LEFT JOIN `".$config['db']['pre']."user` u ON u.id = p.user_id
$where ORDER BY $order_by $pagelimit";
    $result = ORM::for_table($config['db']['pre'].'project')->raw_query($query)->find_many();

    if ($result) {
        foreach ($result as $info)
        {
            $item[$info['id']]['id'] = $info['id'];
            $item[$info['id']]['user_id'] = $info['user_id'];
            $item[$info['id']]['product_name'] = $info['product_name'];
            $item[$info['id']]['cat_id'] = $info['category'];
            $item[$info['id']]['sub_cat_id'] = $info['sub_category'];
            $item[$info['id']]['salary_min'] = price_format($info['salary_min']);
            $item[$info['id']]['salary_max'] = price_format($info['salary_max']);
            $item[$info['id']]['salary_type'] = ($info['salary_type'] == 0)? __("Fixed Price") : __("Hourly Price");
            $item[$info['id']]['featured'] = $info['featured'];
            $item[$info['id']]['urgent'] = $info['urgent'];
            $item[$info['id']]['highlight'] = $info['highlight'];
            $item[$info['id']]['highlight_bgClr'] = ($info['highlight'] == 1)? "highlight-premium-ad" : "";

            $full_amount = 0;
            $data = ORM::for_table($config['db']['pre'] . 'bids')
                ->select('amount')
                ->table_alias('ua')
                ->where(array(
                    'u.status' => '1',
                    'u.user_type' => 'user',
                    'ua.project_id' => $info['id']
                ))
                ->join($config['db']['pre'] . 'user', array('ua.user_id', '=', 'u.id'), 'u')
                ->find_many();
            $bids_count = count($data);
            foreach($data as $d){
                $full_amount = $full_amount + $d['amount'];
            }

            $avg_bid = ($bids_count == 0)? 0 : $full_amount / $bids_count;
            $item[$info['id']]['bids_count'] = $bids_count;
            $item[$info['id']]['avg_bid'] = price_format($avg_bid);

            $item[$info['id']]['status'] = $info['status'];

            $item[$info['id']]['created_at'] = timeAgo($info['created_at']);

            $item[$info['id']]['cat_id'] = $info['category'];
            $item[$info['id']]['sub_cat_id'] = $info['sub_category'];
            $get_main = get_maincat_by_id($info['category']);
            $item[$info['id']]['category'] = $get_main['cat_name'];

            $get_sub = $item_sub_category = $item_subcatlink = null;
            if (!empty($info['sub_category'])) {
                $skills = explode(',', $info['sub_category']);
                $skills2 = implode('\' OR sub_cat_id=\'', $skills);

                $skills3 = array();

                $query = "SELECT sub_cat_id,sub_cat_name,slug FROM `".$config['db']['pre']."catagory_sub` WHERE sub_cat_id='" . $skills2 . "' ORDER BY sub_cat_name LIMIT " . count($skills);

                $result2 = ORM::for_table($config['db']['pre'].'catagory_sub')->raw_query($query)->find_many();
                foreach ($result2 as $info2)
                {
                    $skills_link = $link['SEARCH_PROJECTS'] . '/' . $get_main['slug'] . '/' . $info2['slug'];
                    $skills3[] = '<span>'.$info2['sub_cat_name'].'</span>';
                }

                $item[$info['id']]['skills'] = implode('  ', $skills3);
            }else{
                $item[$info['id']]['skills'] = '';
            }

            $item[$info['id']]['favorite'] = check_product_favorite($info['id']);

            if($info['tag'] != ''){
                $item[$info['id']]['showtag'] = "1";
                $tag = explode(',', $info['tag']);
                $tag2 = array();
                foreach ($tag as $val)
                {
                    //REMOVE SPACE FROM $VALUE ----
                    $val = preg_replace("/[\s_]/","-", trim($val));
                    $tag2[] = '<li><a href="'.$link['SEARCH_PROJECTS'].'?keywords='.$val.'">'.$val.'</a> </li>';
                }
                $item[$info['id']]['tag'] = implode('  ', $tag2);
            }else{
                $item[$info['id']]['tag'] = "";
                $item[$info['id']]['showtag'] = "0";
            }

            $userinfo = get_user_data(null,$info['user_id']);

            $item[$info['id']]['authorname'] = $userinfo['name'];
            $item[$info['id']]['username'] = $userinfo['username'];
            $author_url = create_slug($userinfo['username']);

            $item[$info['id']]['author_link'] = $link['PROFILE'].'/'.$author_url;

            if(check_user_upgrades($info['user_id']))
            {
                $sub_info = get_user_membership_detail($info['user_id']);
                $item[$info['id']]['sub_title'] = $sub_info['sub_title'];
                $item[$info['id']]['sub_image'] = $sub_info['sub_image'];
            }else{
                $item[$info['id']]['sub_title'] = '';
                $item[$info['id']]['sub_image'] = '';
            }
            $pro_url = create_slug($info['product_name']);
            $item[$info['id']]['link'] = $config['site_url'].'project/' . $info['id'] . '/'.$pro_url;
            $item[$info['id']]['catlink'] = $link['SEARCH_PROJECTS'].'/'.$get_main['slug'];
        }
    }
    else {
        //echo "0 results";
    }
    return $item;
}

/**
 * Get products
 *
 * @param int|null $userid
 * @param string|null $status
 * @param bool $premium
 * @param int|null $page
 * @param int|null $limit
 * @param string $sort
 * @param bool $location
 * @param bool $order
 * @param string $sort_order
 * @return array
 */
function get_items($userid=null, $status=null, $premium=false, $page=null, $limit=null, $sort="p.id", $location=false, $order=false, $sort_order="DESC"){

    global $config,$link;
    $where = '';
    $item = array();
    if($userid != null){
        if($where == '')
            $where .= "where p.user_id = '".$userid."'";
        else
            $where .= " AND p.user_id = '".$userid."'";
    }
    if($status != null && $status != "hide"){
        if($where == '')
            $where .= "where p.status = '".$status."'";
        else
            $where .= " AND p.status = '".$status."'";
    }

    if($status == "hide"){
        if($where == '')
            $where .= "where p.hide = '1'";
        else
            $where .= " AND p.hide = '1'";
    }else{
        if($where == '')
            $where .= "where p.hide = '0'";
        else
            $where .= " AND p.hide = '0'";
    }

    if($premium){
        if($where == '')
            $where .= "where (p.featured = '1' or p.urgent = '1' or p.highlight = '1')";
        else
            $where .= " AND (p.featured = '1' or p.urgent = '1' or p.highlight = '1')";
    }

    if($location){
        $country_code = check_user_country();
        if($where == '')
            $where .= "where p.country = '".$country_code."'";
        else
            $where .= " AND p.country = '".$country_code."'";
    }

    if($order){
        $order_by = "
      (CASE
        WHEN p.featured = '1' and p.urgent = '1' and p.highlight = '1' THEN 1
        WHEN p.urgent = '1' and p.featured = '1' THEN 2
        WHEN p.urgent = '1' and p.highlight = '1' THEN 3
        WHEN p.featured = '1' and p.highlight = '1' THEN 4
        WHEN p.urgent = '1' THEN 5
        WHEN p.featured = '1' THEN 6
        WHEN p.highlight = '1' THEN 7
        ELSE 8
      END), ".$sort." ".$sort_order;
    }else{
        $order_by = $sort." ".$sort_order;
    }

    $pagelimit = "";
    if($page != null && $limit != null){
        $pagelimit = "LIMIT  ".($page-1)*$limit.",".$limit;
    }

    $query = "SELECT p.*, c.id company_id, c.name company_name, c.logo company_image
FROM `".$config['db']['pre']."product`  p
LEFT JOIN `".$config['db']['pre']."user` u ON u.id = p.user_id
LEFT JOIN `".$config['db']['pre']."companies` c on p.company_id = c.id 
$where ORDER BY $order_by $pagelimit";
    $result = ORM::for_table($config['db']['pre'].'product')->raw_query($query)->find_many();

    if ($result) {
        foreach ($result as $info)
        {
            $item[$info['id']]['id'] = $info['id'];
            $item[$info['id']]['user_id'] = $info['user_id'];
            $item[$info['id']]['product_name'] = $info['product_name'];
            $item[$info['id']]['company_id'] = $info['company_id'];
            $item[$info['id']]['company_name'] = $info['company_name'];
            $item[$info['id']]['company_image'] = !empty($info['company_image'])?$info['company_image']:'default.png';
            $item[$info['id']]['product_type'] = get_productType_title_by_id($info['product_type']);
            $item[$info['id']]['salary_type'] = get_salaryType_title_by_id($info['salary_type']);
            $item[$info['id']]['cat_id'] = $info['category'];
            $item[$info['id']]['sub_cat_id'] = $info['sub_category'];
            $item[$info['id']]['salary_min'] = $info['salary_min'];
            $item[$info['id']]['salary_max'] = $info['salary_max'];
            $item[$info['id']]['featured'] = $info['featured'];
            $item[$info['id']]['urgent'] = $info['urgent'];
            $item[$info['id']]['highlight'] = $info['highlight'];
            $item[$info['id']]['highlight_bgClr'] = ($info['highlight'] == 1)? "highlight-premium-ad" : "";

            $cityname = get_cityName_by_id($info['city']);
            $item[$info['id']]['location'] = $cityname;
            $item[$info['id']]['city'] = $cityname;
            $item[$info['id']]['status'] = $info['status'];
            $item[$info['id']]['hide'] = $info['hide'];
            $item[$info['id']]['view'] = $info['view'];

            $item[$info['id']]['created_at'] = timeAgo($info['created_at']);
            $expire_date_timestamp = $info['expire_date'];
            $expire_date = date('d M, Y', $expire_date_timestamp);
            $item[$info['id']]['expire_date'] = $expire_date;

            $item[$info['id']]['cat_id'] = $info['category'];
            $item[$info['id']]['sub_cat_id'] = $info['sub_category'];
            $get_main = get_maincat_by_id($info['category']);
            $get_sub = get_subcat_by_id($info['sub_category']);
            $item[$info['id']]['category'] = $get_main['cat_name'];
            $item[$info['id']]['sub_category'] = $get_sub['sub_cat_name'];

            $item[$info['id']]['image'] = !empty($info['screen_shot'])?$info['screen_shot']:$item[$info['id']]['company_image'];

            $item[$info['id']]['favorite'] = check_product_favorite($info['id']);

            if($info['tag'] != ''){
                $item[$info['id']]['showtag'] = "1";
                $tag = explode(',', $info['tag']);
                $tag2 = array();
                foreach ($tag as $val)
                {
                    //REMOVE SPACE FROM $VALUE ----
                    $val = preg_replace("/[\s_]/","-", trim($val));
                    $tag2[] = '<li><a href="'.$config['site_url'].'listing?keywords='.$val.'">'.$val.'</a> </li>';
                }
                $item[$info['id']]['tag'] = implode('  ', $tag2);
            }else{
                $item[$info['id']]['tag'] = "";
                $item[$info['id']]['showtag'] = "0";
            }

            $salary_min = price_format($info['salary_min'],$info['country']);
            $item[$info['id']]['salary_min'] = $salary_min;
            $salary_max = price_format($info['salary_max'],$info['country']);
            $item[$info['id']]['salary_max'] = $salary_max;

            $userinfo = get_user_data(null,$info['user_id']);

            $item[$info['id']]['authorname'] = $userinfo['name'];
            $item[$info['id']]['username'] = $userinfo['username'];
            $author_url = create_slug($userinfo['username']);

            $item[$info['id']]['author_link'] = $link['PROFILE'].'/'.$author_url;

            if(check_user_upgrades($info['user_id']))
            {
                $sub_info = get_user_membership_detail($info['user_id']);
                $item[$info['id']]['sub_title'] = $sub_info['sub_title'];
                $item[$info['id']]['sub_image'] = $sub_info['sub_image'];
            }else{
                $item[$info['id']]['sub_title'] = '';
                $item[$info['id']]['sub_image'] = '';
            }
            $pro_url = create_slug($info['product_name']);
            $item[$info['id']]['link'] = $link['POST-DETAIL'].'/' . $info['id'] . '/'.$pro_url;
            $item[$info['id']]['catlink'] = $config['site_url'].'category/'.$get_main['slug'];
            $item[$info['id']]['subcatlink'] = $config['site_url'].'category/'.$get_main['slug'].'/'.$get_sub['slug'];

            $city = create_slug($item[$info['id']]['city']);
            $item[$info['id']]['citylink'] = $config['site_url'].'city/'.$info['city'].'/'.$city;

        }
    }
    else {
        //echo "0 results";
    }
    return $item;
}

/**
 * Get product count
 *
 * @param int|null $userid
 * @param string|null $status
 * @param bool $premium
 * @param int|null $getbysubcat
 * @param int|null $getbymaincat
 * @param bool $location
 * @return int
 */
function get_items_count($userid=false, $status=null, $premium=false, $getbysubcat=null, $getbymaincat=null, $location=false){

    global $config;
    $where = '';
    $where_array = array();
    if($userid){
        $where_array['user_id'] = $userid;
        if($where == '')
            $where .= "where user_id = '".$userid."'";
        else
            $where .= " AND user_id = '".$userid."'";
    }

    if($status != null && $status != "hide"){
        $where_array['status'] = $status;
        if($where == '')
            $where .= "where status = '".$status."'";
        else
            $where .= " AND status = '".$status."'";
    }

    if($status == "hide"){
        $where_array['status'] = "hide";
        if($where == '')
            $where .= "where hide = '1'";
        else
            $where .= " AND hide = '1'";
    }else{
        $where_array['hide'] = 0;
        if($where == '')
            $where .= "where hide = '0'";
        else
            $where .= " AND hide = '0'";
    }

    if($premium){
        if($where == '')
            $where .= "where (featured = '1' or urgent = '1' or highlight = '1')";
        else
            $where .= " AND (featured = '1' or urgent = '1' or highlight = '1')";
    }

    if($getbysubcat != null){
        $where_array['sub_category'] = $getbysubcat;
        if($where == '')
            $where .= "where sub_category = '".$getbysubcat."'";
        else
            $where .= " AND sub_category = '".$getbysubcat."'";
    }

    if($getbymaincat != null){
        $where_array['category'] = $getbymaincat;
        if($where == '')
            $where .= "where category = '".$getbymaincat."'";
        else
            $where .= " AND category = '".$getbymaincat."'";
    }

    if($location){
        $country_code = check_user_country();
        $where_array['country'] = $country_code;
        if($where == '')
            $where .= "where country = '".$country_code."'";
        else
            $where .= " AND country = '".$country_code."'";
    }

    $pdo = ORM::get_db();
    $query = "SELECT id FROM `".$config['db']['pre']."product` $where ";
    $result = $pdo->query($query);
    $item_count = $result->rowCount();

    return $item_count;
}

/**
 * Get resubmitted products
 *
 * @param int|null $userid
 * @param string|null $status
 * @param int|null $page
 * @param int|null $limit
 * @param string $sort
 * @return array
 */
function get_resubmited_items($userid=false, $status=null, $page=null, $limit=null, $sort="id"){

    global $config,$lang;
    $where = '';
    $item = array();
    if($userid){
        if($where == '')
            $where .= "where p.user_id = '".$userid."'";
        else
            $where .= " AND p.user_id = '".$userid."'";
    }
    if($status != null){
        if($where == '')
            $where .= "where p.status = '".$status."'";
        else
            $where .= " AND p.status = '".$status."'";
    }

    $pagelimit = "";
    if($page != null && $limit != null){
        $pagelimit = "LIMIT  ".($page-1)*$limit.",".$limit;
    }
    $query = "SELECT p.*, c.name company_name, pt.id product_type FROM `".$config['db']['pre']."product_resubmit` p
    LEFT JOIN `".$config['db']['pre']."companies` c on p.company_id = c.id 
    LEFT JOIN `".$config['db']['pre']."product_type` pt on p.product_type = pt.id
     $where ORDER BY $sort DESC $pagelimit";
    $result = ORM::for_table($config['db']['pre'].'product_resubmit')->raw_query($query)->find_many();
    if ($result) {
        foreach ($result as $info)
        {
            $item[$info['id']]['id'] = $info['id'];
            $item[$info['id']]['product_id'] = $info['product_id'];
            $item[$info['id']]['product_name'] = $info['product_name'];
            $item[$info['id']]['company_name'] = $info['company_name'];
            $item[$info['id']]['product_type'] = get_productType_title_by_id($info['product_type']);
            $item[$info['id']]['salary_type'] = get_salaryType_title_by_id($info['salary_type']);
            $item[$info['id']]['desc'] = strlimiter($info['description'],80);
            $item[$info['id']]['featured'] = $info['featured'];
            $item[$info['id']]['urgent'] = $info['urgent'];
            $item[$info['id']]['highlight'] = $info['highlight'];
            $item[$info['id']]['address'] = strlimiter($info['location'],20);
            $item[$info['id']]['location'] = get_cityName_by_id($info['city']);
            $item[$info['id']]['city'] = $info['city'];
            $item[$info['id']]['state'] = $info['state'];
            $item[$info['id']]['country'] = $info['country'];
            $item[$info['id']]['status'] = $info['status'];
            $item[$info['id']]['created_at'] = timeago($info['created_at']);
            $item[$info['id']]['author_id'] = $info['user_id'];

            $salary_min = price_format($info['salary_min'],$info['country']);
            $item[$info['id']]['salary_min'] = $salary_min;
            $salary_max = price_format($info['salary_max'],$info['country']);
            $item[$info['id']]['salary_max'] = $salary_max;

            $item[$info['id']]['cat_id'] = $info['category'];
            $item[$info['id']]['sub_cat_id'] = $info['sub_category'];

            $get_main = get_maincat_by_id($info['category']);
            $get_sub = get_subcat_by_id($info['sub_category']);
            $item[$info['id']]['category'] = $get_main['cat_name'];
            $item[$info['id']]['sub_category'] = $get_sub['sub_cat_name'];
            $catslug = $get_main['slug'];
            $subcatslug = $get_sub['slug'];

            $item[$info['id']]['favorite'] = check_product_favorite($info['id']);

            $tag = explode(',', $info['tag']);
            $tag2 = array();
            foreach ($tag as $val)
            {
                //REMOVE SPACE FROM $VALUE ----
                $val = trim($val);
                $tag2[] = '<li><a href="'.$config['site_url'].'listing?keywords='.$val.'">'.$val.'</a> </li>';
            }
            $item[$info['id']]['tag'] = implode('  ', $tag2);

            $pro_url = create_slug($info['product_name']);

            $item[$info['id']]['link'] = $link['POST-DETAIL'].'/' . $info['id'] . '/'.$pro_url;


            $userinfo = get_user_data(null,$info['user_id']);

            $item[$info['id']]['username'] = $userinfo['username'];
            $author_url = create_slug($userinfo['username']);

            $item[$info['id']]['author_link'] = $link['PROFILE'].'/'.$author_url;

            $item[$info['id']]['catlink'] = $config['site_url'].'category/'.$catslug;

            $item[$info['id']]['subcatlink'] = $config['site_url'].'category/'.$catslug.'/'.$subcatslug;

            $city = create_slug($item[$info['id']]['city']);
            $item[$info['id']]['citylink'] = $config['site_url'].'city/'.$info['city'].'/'.$city;
        }
    }
    else {
        //echo "0 results";
    }
    return $item;
}

/**
 * Renew product by user id
 *
 * @param int|null $userid
 */
function renew_item_by_userid($userid=null){
    global $config;
    $pdo = ORM::get_db();
    // Get usergroup details
    $user_info = ORM::for_table($config['db']['pre'].'user')
        ->select('group_id')
        ->find_one($userid);

    $group_id = isset($user_info['group_id'])? $user_info['group_id'] : 0;

    $timenow = date('Y-m-d H:i:s');
    if($group_id > 0) {
        // Get membership details
        $group_info = get_user_membership_detail();

        $ad_duration = $group_info['ad_duration'];
        $expire_time = date('Y-m-d H:i:s', strtotime($timenow . ' +'.$ad_duration.' day'));
        $expire_timestamp = strtotime($expire_time);
    }else{
        $ad_duration = 7;
        $expire_time = date('Y-m-d H:i:s', strtotime($timenow . ' +'.$ad_duration.' day'));
        $expire_timestamp = strtotime($expire_time);
    }

    $query = "UPDATE `".$config['db']['pre']."product` SET
    `status` = 'active', `expire_date` = '" . $expire_timestamp . "'
    WHERE  user_id='" . $userid . "'";
    $pdo->query($query);
}

/**
 * get resubmitted product count
 *
 * @param int $id
 * @return int
 */
function resubmited_ads_count($id){
    global $config;
    $num_rows = ORM::for_table($config['db']['pre'].'product_resubmit')
        ->where('user_id',$id)
        ->count();
    return $num_rows;
}

/**
 * get product count
 *
 * @param int $id
 * @return int
 */
function myads_count($id){
    global $config;
    $num_rows = ORM::for_table($config['db']['pre'].'product')
        ->where('user_id',$id)
        ->count();
    return $num_rows;
}

/**
 * get active product count
 *
 * @param int $id
 * @return int
 */
function active_ads_count($id){
    global $config;
    $num_rows = ORM::for_table($config['db']['pre'].'product')
        ->where(array(
            'status' => "active",
            'user_id' => $id
        ))
        ->count();
    return $num_rows;
}

/**
 * get pending product count
 *
 * @param int $id
 * @return int
 */
function pending_ads_count($id){
    global $config;
    $num_rows = ORM::for_table($config['db']['pre'].'product')
        ->where(array(
            'status' => "pending",
            'user_id' => $id
        ))
        ->count();
    return $num_rows;
}

/**
 * get expire product count
 *
 * @param int $id
 * @return int
 */
function expire_ads_count($id){
    global $config;
    $num_rows = ORM::for_table($config['db']['pre'].'product')
        ->where(array(
            'status' => "expire",
            'user_id' => $id
        ))
        ->count();
    return $num_rows;
}

/**
 * get hidden product count
 *
 * @param int $id
 * @return int
 */
function hidden_ads_count($id){
    global $config;
    $num_rows = ORM::for_table($config['db']['pre'].'product')
        ->where(array(
            'hide' => '1',
            'user_id' => $id
        ))
        ->count();
    return $num_rows;
}

/**
 * get favorite product count
 *
 * @param int $id
 * @return int
 */
function favorite_ads_count($id){
    global $config;
    $num_rows = ORM::for_table($config['db']['pre'].'favads')
        ->table_alias('f')
        ->where('f.user_id' , $id)
        ->where(array(
            'p.status' => 'active',
            'p.hide' => '0',
            'f.user_id' => $id
        ))
        ->join($config['db']['pre'] . 'product', array('f.product_id', '=', 'p.id'), 'p')
        ->count();
    return $num_rows;
}

/**
 * get applied jobs count
 *
 * @param int $id
 * @return int
 */
function applied_jobs_count($id){
    global $config;
    $num_rows = ORM::for_table($config['db']['pre'].'user_applied')
        ->table_alias('a')
        ->where('a.user_id' , $id)
        ->where(array(
            'p.status' => 'active',
            'p.hide' => '0',
            'a.user_id' => $id
        ))
        ->join($config['db']['pre'] . 'product', array('a.job_id', '=', 'p.id'), 'p')
        ->count();
    return $num_rows;
}

/**
 * get favorite users count
 *
 * @param int $id
 * @return int
 */
function favorite_users_count($id){
    global $config;
    $num_rows = ORM::for_table($config['db']['pre'].'fav_users')
        ->table_alias('f')
        ->where(array(
            'u.status' => '1',
            'u.user_type' => 'user',
            'f.user_id' => $id
        ))
        ->join($config['db']['pre'] . 'user', array('f.fav_user_id', '=', 'u.id'), 'u')
        ->count();
    return $num_rows;
}

/**
 * get resumes count
 *
 * @param int $id
 * @return int
 */
function resumes_count($id){
    global $config;
    $num_rows = ORM::for_table($config['db']['pre'].'resumes')
        ->where('user_id' , $id)
        ->count();
    return $num_rows;
}

/**
 * get companies count
 *
 * @param int $id
 * @return int
 */
function companies_count($id){
    global $config;
    $num_rows = ORM::for_table($config['db']['pre'].'companies')
        ->where('user_id' , $id)
        ->count();
    return $num_rows;
}

/**
 * get total jobs of company
 *
 * @param int $id
 * @return int
 */
function count_company_jobs($id){
    global $config;
    $num_rows = ORM::for_table($config['db']['pre'].'product')
        ->where(array(
            'company_id' => $id
        ))
        ->where('status','active')
        ->where('hide','0')
        ->count();
    return $num_rows;
}

/**
 * Count user review
 *
 * @param int $user_id
 * @param string $user_type
 * @return int
 */
function count_user_review($user_id, $user_type){
    global $config;
    if($user_type == 'employer'){
        $array = array('employer_id' => $user_id, 'rated_by' => 'user');
    }else{
        $array = array('freelancer_id' => $user_id, 'rated_by' => 'employer');
    }
    $count = ORM::for_table($config['db']['pre'].'reviews')
        ->where($array)
        ->count();
    return $count;
}

/**
 * Get average rating
 *
 * @param int $user_id
 * @param string $user_type
 * @return string
 */
function averageRating($user_id, $user_type)
{
    global $config,$lang;

    if($user_type == 'employer'){
        $array = array('employer_id' => $user_id, 'rated_by' => 'user');
    }else{
        $array = array('freelancer_id' => $user_id, 'rated_by' => 'employer');
    }

    $q_star1_result = ORM::for_table($config['db']['pre'].'reviews')
        ->where(array(
            'rating' => '1',
            'publish' => '1'
        ))
        ->where($array)
        ->count();

    $q_star2_result = ORM::for_table($config['db']['pre'].'reviews')
        ->where(array(
            'rating' => '2',
            'publish' => '1'
        ))
        ->where($array)
        ->count();

    $q_star3_result = ORM::for_table($config['db']['pre'].'reviews')
        ->where(array(
            'rating' => '3',
            'publish' => '1'
        ))
        ->where($array)
        ->count();

    $q_star4_result = ORM::for_table($config['db']['pre'].'reviews')
        ->where(array(
            'rating' => '4',
            'publish' => '1'
        ))
        ->where($array)
        ->count();

    $q_star5_result = ORM::for_table($config['db']['pre'].'reviews')
        ->where(array(
            'rating' => '5',
            'publish' => '1'
        ))
        ->where($array)
        ->count();

    $total = $q_star1_result + $q_star2_result + $q_star3_result + $q_star4_result + $q_star5_result;

    if ($total != 0) {
        $rating = ($q_star1_result*1 + $q_star2_result*2 + $q_star3_result*3 + $q_star4_result*4 + $q_star5_result*5) / $total;
    } else {
        $rating = 0;
    }

    $rating = round($rating * 2) / 2;
    return $rating = number_format($rating,1);
    /* '<h3>'.__("Average rating").'</h3><p><small><strong>'.$rating.'</strong> '.__("average based on").' <strong>'.$total.'</strong> '.__("Reviews").'.</small></p><div class="rating-passive" data-rating="'.$rating.'"><span class="stars"></span></div>';*/
}

/**
 * update product view
 *
 * @param int $product_id
 */
function update_itemview($product_id){
    global $config;
    $product = ORM::for_table($config['db']['pre'].'product')->find_one($product_id);
    $product->set_expr('view', 'view+1');
    $product->save();
}

/**
 * update project view
 *
 * @param int $project_id
 */
function update_projectview($project_id){
    global $config;
    $product = ORM::for_table($config['db']['pre'].'project')->find_one($project_id);
    $product->set_expr('view', 'view+1');
    $product->save();
}

/**
 * Check custom field exist
 *
 * @param int $id
 * @return int
 */
function get_customField_exist_id($id){
    global $config;
    $num_rows = ORM::for_table($config['db']['pre'].'custom_fields')
        ->where('custom_id' , $id)
        ->count();
    return $num_rows;
}

/**
 * Get salary type title by id
 *
 * @param int $id
 * @return string
 */
function get_salaryType_title_by_id($id){
    global $config;
    $custom_fields_title = "";

    $info = ORM::for_table($config['db']['pre'].'salary_type')
        ->select_many('title', 'translation_lang', 'translation_name')
        ->where('id' , $id)
        ->find_one();

    if($config['lang_code'] != 'en' && $config['userlangsel'] == '1'){
        if($info['translation_lang'] != '' && $info['translation_name'] != ''){
            $translation_lang = explode(',',$info['translation_lang']);
            $translation_name = explode(',',$info['translation_name']);

            $count = 0;
            foreach($translation_lang as $key=>$value)
            {
                if($value != '')
                {
                    $translation[$translation_lang[$key]] = $translation_name[$key];

                    $count++;
                }
            }

            $trans_name = (isset($translation[$config['lang_code']]))? $translation[$config['lang_code']] : '';

            if($trans_name != ''){
                $custom_fields_title = $trans_name;
            }else{
                $custom_fields_title = (isset($info['title']))? $info['title'] : '';
            }
        }
    }else{

        $custom_fields_title = (isset($info['title']))? $info['title'] : '';
    }
    return $custom_fields_title;
}

/**
 * Get product type title by id
 *
 * @param int $id
 * @return string
 */
function get_productType_title_by_id($id){
    global $config;
    $custom_fields_title = "";

    $info = ORM::for_table($config['db']['pre'].'product_type')
        ->select_many('title', 'translation_lang', 'translation_name')
        ->where('id' , $id)
        ->find_one();
    if($config['lang_code'] != 'en' && $config['userlangsel'] == '1'){
        if($info['translation_lang'] != '' && $info['translation_name'] != ''){
            $translation_lang = explode(',',$info['translation_lang']);
            $translation_name = explode(',',$info['translation_name']);

            $count = 0;
            foreach($translation_lang as $key=>$value)
            {
                if($value != '')
                {
                    $translation[$translation_lang[$key]] = $translation_name[$key];

                    $count++;
                }
            }

            $trans_name = (isset($translation[$config['lang_code']]))? $translation[$config['lang_code']] : '';

            if($trans_name != ''){
                $custom_fields_title = stripslashes($trans_name);
            }else{
                $custom_fields_title = (isset($info['title']))? $info['title'] : '';
            }
        }
    }else{
        $custom_fields_title = (isset($info['title']))? $info['title'] : '';
    }
    return $custom_fields_title;
}

/**
 * Get custom field title by id
 *
 * @param int $id
 * @return string
 */
function get_customField_title_by_id($id){
    global $config;
    $custom_fields_title = "";

    $info = ORM::for_table($config['db']['pre'].'custom_fields')
        ->select_many('custom_title', 'translation_lang', 'translation_name')
        ->where('custom_id' , $id)
        ->find_one();

    if($config['lang_code'] != 'en' && $config['userlangsel'] == '1'){
        if($info['translation_lang'] != '' && $info['translation_name'] != ''){
            $translation_lang = explode(',',$info['translation_lang']);
            $translation_name = explode(',',$info['translation_name']);

            $count = 0;
            foreach($translation_lang as $key=>$value)
            {
                if($value != '')
                {
                    $translation[$translation_lang[$key]] = $translation_name[$key];

                    $count++;
                }
            }

            $trans_name = (isset($translation[$config['lang_code']]))? $translation[$config['lang_code']] : '';

            if($trans_name != ''){
                $custom_fields_title = stripslashes($trans_name);
            }else{
                $custom_fields_title = (isset($info['title']))? $info['title'] : '';
            }
        }
    }else{
        $custom_fields_title = (isset($info['title']))? $info['title'] : '';
    }
    return $custom_fields_title;
}

/**
 * Get custom option title by id
 *
 * @param int $option_id
 * @return string
 */
function get_customOption_by_id($option_id){
    global $config;

    $info = ORM::for_table($config['db']['pre'].'custom_options')
        ->select('title')
        ->where('option_id' , $option_id)
        ->find_one();

    if($config['lang_code'] != 'en' && $config['userlangsel'] == '1'){
        $customoption = get_category_translation("custom_option",$option_id);
        $info['title'] = $customoption['title'];
    }
    return (!empty($info['title']))? $info['title'] : '';
}

/**
 * add/update product custom field data
 *
 * @param int $category_id
 * @param int $subcategory_id
 * @param int $product_id
 */
function add_post_customField_data($category_id, $subcategory_id, $product_id){

    global $config;
    $custom_fields = get_customFields_by_catid($category_id, $subcategory_id);

    foreach ($custom_fields as $key => $value) {
        if ($value['userent']) {
            $field_id = $value['id'];
            $field_type = $value['type'];
            if($field_type == "textarea")
                $field_data = validate_input($value['default'],true);
            else
                $field_data = validate_input($value['default']);

            if(isset($product_id)){
                //Checking Data exist
                $exist = ORM::for_table($config['db']['pre'].'custom_data')
                    ->where(array(
                        'product_id' => $product_id,
                        'field_id' => $field_id
                    ))
                    ->count();

                if($exist > 0){
                    //Update here
                    $pdo = ORM::get_db();
                    $query = "UPDATE `".$config['db']['pre']."custom_data` set field_type = '".$field_type."', field_data = '".$field_data."' where product_id = '".$product_id."' and field_id = '".$field_id."' LIMIT 1";
                    $pdo->query($query);

                }else{
                    //Insert here
                    if($field_data != "") {
                        $field_insert = ORM::for_table($config['db']['pre'].'custom_data')->create();
                        $field_insert->product_id = $product_id;
                        $field_insert->field_id = $field_id;
                        $field_insert->field_type = $field_type;
                        $field_insert->field_data = $field_data;
                        $field_insert->save();
                    }
                }
            }
        }
    }
}

/**
 * Get custom fields by cat id
 *
 * @param int|null $maincatid
 * @param int|null $subcatid
 * @param bool $require
 * @param array $fields
 * @param array $data
 * @return array
 */
function get_customFields_by_catid($maincatid=null, $subcatid=null, $require=true, $fields=array(), $data=array()){

    global $config,$lang;
    $custom_fields = array();
    $pdo = ORM::get_db();
    if(isset($subcatid) && $subcatid != "" && is_numeric($subcatid)){
        $query = "SELECT * FROM `".$config['db']['pre']."custom_fields` WHERE find_in_set($subcatid,custom_subcatid) <> 0 order by custom_id ASC";
    }elseif(isset($maincatid) && $maincatid != "" && is_numeric($maincatid)){
        $query = "SELECT * FROM `".$config['db']['pre']."custom_fields` WHERE find_in_set($maincatid,custom_catid) <> 0 order by custom_id ASC";
    }else{
        $query = "SELECT * FROM `".$config['db']['pre']."custom_fields` WHERE custom_anycat = 'any' order by custom_id ASC";
    }
    $result = $pdo->query($query);
    foreach ($result as $info)
    {
        $custom_fields[$info['custom_id']]['id'] = $info['custom_id'];
        $custom_fields[$info['custom_id']]['type'] = $info['custom_type'];
        $custom_fields[$info['custom_id']]['name'] = $info['custom_name'];
        $custom_fields[$info['custom_id']]['title'] = stripslashes($info['custom_title']);
        $custom_fields[$info['custom_id']]['maxlength'] = $info['custom_max'];

        if($config['lang_code'] != 'en' && $config['userlangsel'] == '1'){
            if($info['translation_lang'] != '' && $info['translation_name'] != ''){
                $translation_lang = explode(',',$info['translation_lang']);
                $translation_name = explode(',',$info['translation_name']);

                $count = 0;
                foreach($translation_lang as $key=>$value)
                {
                    if($value != '')
                    {
                        $translation[$translation_lang[$key]] = $translation_name[$key];

                        $count++;
                    }
                }

                $trans_name = (isset($translation[$config['lang_code']]))? $translation[$config['lang_code']] : '';

                if($trans_name != ''){
                    $custom_fields[$info['custom_id']]['title'] = stripslashes($trans_name);
                }else{
                    $custom_fields[$info['custom_id']]['title'] = stripslashes($info['custom_title']);
                }
            }
        }

        $required = "0";
        if($require){
            $required = ($info['custom_required'] == 1)?  '1' : '0';
        }
        $custom_fields[$info['custom_id']]['required'] = $required;

        if(isset($_REQUEST['custom'][$info['custom_id']]))
        {
            if($custom_fields[$info['custom_id']]['type'] == "checkboxes"){
                $checkbox1=$_REQUEST['custom'][$info['custom_id']];
                if(is_array($checkbox1)){
                    $chk="";
                    $chkCount = 0;
                    foreach($checkbox1 as $chk1)
                    {
                        if($chkCount == 0)
                            $chk .= $chk1;
                        else
                            $chk .= ",".$chk1;

                        $chkCount++;
                    }
                    $custom_fields[$info['custom_id']]['default'] = $chk;
                }
                else{
                    $custom_fields[$info['custom_id']]['default'] = $_REQUEST['custom'][$info['custom_id']];
                }

            }
            else{
                //$custom_fields[$info['custom_id']]['default'] = substr(strip_tags($_REQUEST['custom'][$info['custom_id']]),0,$info['custom_max']);
                $custom_fields[$info['custom_id']]['default'] = $_REQUEST['custom'][$info['custom_id']];
            }

            $custom_fields[$info['custom_id']]['userent'] = 1;
        }
        else
        {
            $custom_fields[$info['custom_id']]['default'] = $info['custom_default'];
            $custom_fields[$info['custom_id']]['userent'] = 0;
        }

        foreach($fields as $key=>$value)
        {
            if($value != '')
            {
                if($value == $info['custom_id']){
                    $custom_fields[$info['custom_id']]['default'] = $data[$key];
                    break;
                }

            }
        }

        //Text-field
        if($info['custom_type'] == 'text-field'){
            $textbox = '<input name="custom['.$info['custom_id'].']" id="custom['.$info['custom_id'].']" class="form-control with-border quick-custom-field"  type="text" value="'.$custom_fields[$info['custom_id']]['default'].'" placeholder="'.$custom_fields[$info['custom_id']]['title'].'" data-name="'.$info['custom_id'].'" data-req="'.$required.'"/><div class="quick-error">'.__("This field is required.").'</div>';
            $custom_fields[$info['custom_id']]['textbox'] = $textbox;
        }
        else{
            $custom_fields[$info['custom_id']]['textbox'] = '';
        }

        //Textarea
        if($info['custom_type'] == 'textarea'){
            $textarea= '<textarea class="materialize-textarea form-control with-border quick-custom-field" name="custom['.$info['custom_id'].']" id="custom['.$info['custom_id'].']" placeholder="'.$custom_fields[$info['custom_id']]['title'].'" data-name="'.$info['custom_id'].'" data-req="'.$required.'">'.$custom_fields[$info['custom_id']]['default'].'</textarea><div class="quick-error">'.__("This field is required.").'</div>';
            $custom_fields[$info['custom_id']]['textarea'] = $textarea;
        }
        else{
            $custom_fields[$info['custom_id']]['textarea'] = '';
        }

        //SelectList
        if($info['custom_type'] == 'drop-down')
        {
            $options = explode(',',stripslashes($info['custom_options']));

            //$selectbox = '<select class="meterialselect" name="custom['.$info['custom_id'].']" '.$required.'><option value="" selected>'.$info['custom_title'].'</option>';
            $selectbox = '';
            foreach($options as $key3=>$value3)
            {
                $option_title = get_customOption_by_id($value3);
                if($value3 == $custom_fields[$info['custom_id']]['default'])
                {
                    $selectbox.= '<option value="'.$value3.'" selected>'.$option_title.'</option>';
                }
                else
                {
                    $selectbox.= '<option value="'.$value3.'">'.$option_title.'</option>';
                }
            }
            //$selectbox.= '</select>';

            $custom_fields[$info['custom_id']]['selectbox'] = $selectbox;
        }
        else
        {
            $custom_fields[$info['custom_id']]['selectbox'] = '';
        }

        //RadioButton
        if($info['custom_type'] == 'radio-buttons')
        {
            $options = explode(',',stripslashes($info['custom_options']));
            $radiobtn = "";
            $i = 0;
            foreach($options as $key3=>$value3)
            {

                $checked = "";
                $option_title = get_customOption_by_id($value3);
                if($value3 == $custom_fields[$info['custom_id']]['default']) {
                    $checked = "checked";
                }

                $radiobtn .= '<div class="radio radio-primary radio-inline"><input class="with-gap" type="radio" name="custom['.$info['custom_id'].']" id="'.$value3.$i.'" value="'.$value3.'" data-name="'.$info['custom_id'].'" '.$checked.' />';
                $radiobtn .= '<label for="'.$value3.$i.'"><span class="radio-label"></span>'.$option_title.'</label></div><br>';

                $i++;
            }
            $radiobtn .= '<input type="hidden" class="quick-radioCheck"
                                                                   data-name="'.$info['custom_id'].'"
                                                                   data-req="'.$required.'"><div class="quick-error">'.__("This field is required.").'</div>';
            $custom_fields[$info['custom_id']]['radio'] = $radiobtn;
        }
        else
        {
            $custom_fields[$info['custom_id']]['radio'] = '';
        }

        //Checkbox
        if($info['custom_type'] == 'checkboxes')
        {
            $options = explode(',',stripslashes($info['custom_options']));
            $Checkbox = "";
            $CheckboxBootstrap = "";
            $j = 0;
            $selected = "";
            foreach($options as $key4=>$value4)
            {
                $default_checkbox = $custom_fields[$info['custom_id']]['default'];
                if(is_array($default_checkbox)){
                    $checked = $custom_fields[$info['custom_id']]['default'];
                }else{
                    $checked = explode(',',$custom_fields[$info['custom_id']]['default']);
                }

                foreach ($checked as $val)
                {
                    if($value4 == $val)
                    {
                        $selected = "checked";
                        break;
                    }
                    else{
                        $selected = "";
                    }
                }

                $option_title = get_customOption_by_id($value4);
                $Checkbox .= '<div class="checkbox"><input type="checkbox" name="custom['.$info['custom_id'].'][]" id="'.$value4.$j.'" value="'.$value4.'" '.$selected.' data-name="'.$info['custom_id'].'" />';
                $Checkbox .= '<label for="'.$value4.$j.'"><span class="checkbox-icon"></span>'.$option_title.'</label></div><br>';

                $j++;
            }
            $Checkbox .= '<input type="hidden" class="quick-radioCheck"
                                                                   data-name="'.$info['custom_id'].'"
                                                                   data-req="'.$required.'"><div class="quick-error">'.__("This field is required.").'</div>';
            $custom_fields[$info['custom_id']]['checkbox'] = $Checkbox;
        }
        else
        {
            $custom_fields[$info['custom_id']]['checkbox'] = '';
        }
    }

    return $custom_fields;
}

/**
 * Minus amount from user balance
 *
 * @param int $user_id
 * @param float $amount
 */
function minus_balance($user_id, $amount){
    global $config;

    $user = ORM::for_table($config['db']['pre'] . 'user')
        ->select('balance')
        ->find_one($user_id);
    $balance = $user['balance'];
    $less = $balance - $amount;

    $user = ORM::for_table($config['db']['pre'] . 'user')->find_one($user_id);
    $user->set('balance', $less);
    $user->save();

    $now = time();
    $ip = encode_ip($_SERVER, $_ENV);
    $trans_insert = ORM::for_table($config['db']['pre'].'transaction')->create();
    $trans_insert->product_name = 'Site Comission Fee';
    $trans_insert->product_id = null;
    $trans_insert->seller_id = $user_id;
    $trans_insert->status = 'success';
    $trans_insert->amount = $amount;
    $trans_insert->transaction_gatway = 'Wallet';
    $trans_insert->transaction_ip = $ip;
    $trans_insert->transaction_time = $now;
    $trans_insert->transaction_description = 'Project Comission';
    $trans_insert->transaction_method = 'project_fee';
    $trans_insert->save();
}

/**
 * Add amount to user balance
 *
 * @param int $user_id
 * @param float $amount
 */
function add_balance($user_id, $amount){
    global $config;

    $user = ORM::for_table($config['db']['pre'] . 'user')
        ->select('balance')
        ->find_one($user_id);
    $balance = $user['balance'];
    $add = $balance + $amount;

    $user = ORM::for_table($config['db']['pre'] . 'user')->find_one($user_id);
    $user->set('balance', $add);
    $user->save();

    $now = time();
    $ip = encode_ip($_SERVER, $_ENV);
    $trans_insert = ORM::for_table($config['db']['pre'].'transaction')->create();
    $trans_insert->product_name = 'Amount Added';
    $trans_insert->product_id = '';
    $trans_insert->seller_id = $user_id;
    $trans_insert->status = 'success';
    $trans_insert->amount = $amount;
    $trans_insert->transaction_gatway = 'Wallet';
    $trans_insert->transaction_ip = $ip;
    $trans_insert->transaction_time = $now;
    $trans_insert->transaction_description = 'Amount Added';
    $trans_insert->transaction_method = 'refund';
    $trans_insert->save();
}

/**
 * Get unread count for notification and chat conversation (ACTION: unread_note_chat_count)
 *
 * @param string|null $type
 * @return int
 */
function unread_note_count($type=null){
    global $config;

    if($type != null){
        $array = array(
            'owner_id' => $_SESSION['user']['id'],
            'type' => $type,
            'recd' => '0'
        );
    }else{
        $array = array(
            'owner_id' => $_SESSION['user']['id'],
            'recd' => '0'
        );
    }
    $notification_count = ORM::for_table($config['db']['pre'].'push_notification')
        ->where($array)
        ->count();

    return $notification_count;
}

/**
 * Get Notification (ACTION: get_notification)
 *
 * @param int $user_id
 * @param int|null $limit
 * @return array
 */
function get_firebase_notification($user_id, $limit=null)
{
    global $config, $lang, $results;

    $notification = array();
    if($limit){
        $rows = ORM::for_table($config['db']['pre'].'push_notification')
            ->where('owner_id',$user_id)
            ->orderByDesc('id')
            ->limit($limit)
            ->find_many();
    }else{
        $rows = ORM::for_table($config['db']['pre'].'push_notification')
            ->where('owner_id',$user_id)
            ->orderByDesc('id')
            ->find_many();
    }


    foreach ($rows as $info)
    {
        $note['sender_id'] = $info['sender_id'];
        $note['sender_name'] = $info['sender_name'];
        $note['owner_id'] = $info['owner_id'];
        $note['owner_name'] = $info['owner_name'];
        $note['product_id'] = $info['product_id'];
        $note['product_title'] = $info['product_title'];
        $note['type'] = $info['type'];
        $note['message'] = $info['message'];

        $notification[] = $note;
    }

    return $results = $notification;
}

/**
 * Add firebase notification
 *
 * @param $SenderName
 * @param $SenderId
 * @param $OwnerName
 * @param $OwnerId
 * @param $productId
 * @param $productTitle
 * @param $type
 * @param $message
 * @return int
 * @throws Exception
 */
function add_firebase_notification($SenderName, $SenderId, $OwnerName, $OwnerId, $productId, $productTitle, $type, $message)
{
    global $config;
    if($OwnerId){
        $insert_note = ORM::for_table($config['db']['pre'].'push_notification')->create();
        $insert_note->sender_name = $SenderName;
        $insert_note->sender_id = $SenderId;
        $insert_note->owner_name = $OwnerName;
        $insert_note->owner_id = $OwnerId;
        $insert_note->product_id = $productId;
        $insert_note->product_title = $productTitle;
        $insert_note->type = $type;
        $insert_note->message = $message;
        $insert_note->save();

        return $insert_note->id();
    }else{
        return 0;
    }

}

/**
 * Send FCM
 *
 * @param string $message
 * @param int $user_id
 * @param string|null $title
 * @param string $sending_type
 */
function sendFCM($message, $user_id, $title=null, $sending_type = "one_user") {
    global $config;
    $title = ($title != null)? $title : $config['app_name'];

    if($sending_type == "all_user"){
        $result = ORM::for_table($config['db']['pre'].'firebase_device_token')
            ->select('token')
            ->where_not_equal('user_id', $user_id)
            ->find_many();
        if(isset($result)){
            $token = array();
            foreach($result as $info){
                $token[] = $info['token'];
            }
        }else{
            return;
        }
    }else{
        $result = ORM::for_table($config['db']['pre'].'firebase_device_token')
            ->select('token')
            ->where('user_id', $user_id)
            ->find_many();
        if(isset($result)){
            $token = array();
            foreach($result as $info){
                $token[] = $info['token'];
            }
        }else{
            return;
        }
    }

    $url = 'https://fcm.googleapis.com/fcm/send';
    $fields = array (
        'registration_ids' => $token ,
        'notification' => array (
            "body" => $message,
            "title" => $title,
            "icon" => "myicon"
        )
    );

    $fields = json_encode ( $fields );
    $headers = array (
        'Authorization: key=' . $config['firebase_server_key'],
        'Content-Type: application/json'
    );
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    $result = curl_exec ( $ch );
    curl_close ( $ch );
}

/**
 * Get unread chat messages
 *
 * @return int
 */
function unread_chat_count(){
    global $config;

    $chat_count = ORM::for_table($config['db']['pre'].'messages')
        ->where($array = array(
            'to_id' => $_SESSION['user']['id'],
            'recd' => '0'
        ))
        ->count();

    return $chat_count;
}

/**
 * Get last unread message
 *
 * @param $limit
 * @return array
 */
function get_last_unread_message($limit) {
    global $config;

    $message = array();

    $result = ORM::for_table($config['db']['pre'].'messages')
        ->where('to_id', $_SESSION['user']['id'])
        ->order_by_asc('message_id')
        ->limit($limit)
        ->find_many();

    foreach($result as $chat)
    {
        $info = ORM::for_table($config['db']['pre'].'project')
            ->select('product_name')
            ->find_one($chat['post_id']);
        $post_title = $info['product_name'];
        $picname = ($chat['image'] == "")? "default_user.png" : $chat['image'];
        $status  = ($info['online'] == "0")? "offline" : "online";
        $from_name = ($chat['name'] != '')? $chat['name'] : $chat['username'];
        $chat['message_content'] = escape($chat['message_content']);

        if (strpos($chat['message_content'], 'file_name') !== false) {

        }
        else{
            // The Regular Expression filter
            $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,10}(\/\S*)?/";

            // Check if there is a url in the text
            if (preg_match($reg_exUrl, $chat['message_content'], $url)) {

                // make the urls hyper links
                $chat['message_content'] = preg_replace($reg_exUrl, "<a href='{$url[0]}'>{$url[0]}</a>", $chat['message_content']);

            } else {
                // The Regular Expression filter
                $reg_exUrl = "/(www)\.[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,10}(\/\S*)?/";

                // Check if there is a url in the text
                if (preg_match($reg_exUrl, $chat['message_content'], $url)) {

                    // make the urls hyper links
                    $chat['message_content'] = preg_replace($reg_exUrl, "<a href='{$url[0]}'>{$url[0]}</a>", $chat['message_content']);

                }
            }
        }

        $timeago = timeAgo($chat['message_date']);
        $chatContent = stripslashes($chat['message_content']);

        $message[$chat['message_id']]['image'] = $picname;
        $message[$chat['message_id']]['status'] = $status;
        $message[$chat['message_id']]['from_name'] = $from_name;
        $message[$chat['message_id']]['post_title'] = $post_title;
        $message[$chat['message_id']]['message'] = strlimiter(strip_tags($chatContent),45);;
        $message[$chat['message_id']]['time'] = $timeago;
    }
    return $message;
}