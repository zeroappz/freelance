<?php
require_once('../includes.php');

if(isset($_GET['resubmit'])) {
    $sql = "SELECT p.*, u.name as user_name,u.image as user_image,u.created_at as user_created, c.id company_id, c.name company_name, c.logo company_image, c.created_at company_created_at FROM `".$config['db']['pre']."product_resubmit` p LEFT JOIN `".$config['db']['pre']."companies` c on p.company_id = c.id INNER JOIN `".$config['db']['pre']."user` as u ON u.id = p.user_id WHERE p.product_id = '".$_GET['id']."' ";

    $info = ORM::for_table($config['db']['pre'].'product_resubmit')->raw_query($sql)->find_one();
}else{
    $sql = "SELECT p.*, u.username as username,u.name as user_name,u.image as user_image,u.created_at as user_created, c.id company_id, c.name company_name, c.logo company_image, c.created_at company_created_at FROM `".$config['db']['pre']."product` p LEFT JOIN `".$config['db']['pre']."companies` c on p.company_id = c.id INNER JOIN `".$config['db']['pre']."user` as u ON u.id = p.user_id WHERE p.id = '".$_GET['id']."' ";

    $info = ORM::for_table($config['db']['pre'].'product')->raw_query($sql)->find_one();
}

$item_custom = array();
$item_checkbox = array();

if (!empty($info)) {
    // output data of each row
    //update_itemview($_GET['id'],$config);

    if(isset($_GET['resubmit'])) {
        $item_id = $info['product_id'];
    }
    else{
        $item_id = $info['id'];
    }

    $item_title = $info['product_name'];

    if($config['company_enable']) {
        $company_id = $info['company_id'];
        $company_name = $info['company_name'];
        $company_image = !empty($info['company_image']) ? $info['company_image'] : 'default.png';
        $company_image = $config['site_url'].'storage/products/'.$company_image;
        $company_created_at = timeago($info['company_created_at']);
        $company_link = $config['site_url'] . 'company/' . $info['company_id'] . '/' . create_slug($info['company_name']);
    }else{
        $company_id = $info['user_id'];
        $company_name = $info['user_name'];
        $company_image = !empty($info['user_image']) ? $info['user_image'] : 'default_user.png';
        $company_image = $config['site_url'].'storage/profile/'.$company_image;
        $company_created_at = timeago($info['user_created']);
        $company_link = $link['PROFILE'] . '/' . $info['username'];
    }

    $item_description = nl2br(stripcslashes($info['description']));
    $item_catid = $info['category'];
    $item_featured = $info['featured'];
    $item_urgent = $info['urgent'];
    $item_highlight = $info['highlight'];
    $item_product_type = get_productType_title_by_id($info['product_type']);
    $item_salary_type = get_salaryType_title_by_id($info['salary_type']);
    $item_salary_min = price_format($info['salary_min'],$info['country']);
    $item_salary_max = price_format($info['salary_max'],$info['country']);
    $item_tag = $info['tag'];
    $item_location = $info['location'];
    $item_city = $info['city'];
    $item_state = $info['state'];
    $item_country = $info['country'];
    $item_status = $info['status'];
    $item_view = $info['view'];
    $item_created_at = timeAgo($info['created_at']);
    $item_updated_at = date('d M Y', $info['updated_at']);
    $expire_date_timestamp = $info['expire_date'];
    $expire_date = date('d-M-y', $expire_date_timestamp);

    $image = !empty($info['screen_shot'])?$info['screen_shot']:$company_image;

    $get_main = get_maincat_by_id($info['category']);
    $get_sub = get_subcat_by_id($info['sub_category']);
    $item_category = $get_main['cat_name'];
    $item_sub_category = $get_sub['sub_cat_name'];
    $item_category_slug = $get_main['slug'];
    $item_sub_category_slug = $get_sub['slug'];

    $status = '';
    if ($item_status == "active"){
        $status = '<span class="label label-success">Approved</span>';
    }
    elseif($item_status == "pending")
    {
        $status = '<span class="label label-warning">Pending</span>';
    }
    elseif($item_status == "hide")
    {
        $status = '<span class="label label-info">Hidden</span>';
    }elseif($item_status == "expire")
    {
        $status = '<span class="label label-danger">Expire</span>';
    }

    if(isset($_GET['resubmit'])) {
        $status = '<span class="label label-warning">Re-Submitted</span>';
    }

    if($info['negotiable'] == '0'){
        $item_negotiable = "No";
    }else{
        $item_negotiable = "Yes";
    }

    $item_phone = $info['phone'];
    $item_hide_phone = $info['hide_phone'];

    if($item_phone != "" && $item_hide_phone == '0'){
        $item_hide_phone = "No";
    }else{
        $item_hide_phone = "Yes";
    }


    $item_catlink = $config['site_url'].'category/'.$item_category_slug;

    $item_subcatlink = $config['site_url'].'category/'.$item_category_slug.'/'.$item_sub_category_slug;

    $latlong = $info['latlong'];
    $map = explode(',', $latlong);
    $lat = $map[0];
    $long = $map[1];

    $pro_url = create_slug($info['product_name']);
    $item_link = $link['POST-DETAIL'].'/' . $info['id'] . '/'.$pro_url;


    $tag = explode(',', $info['tag']);
    $tag2 = array();
    foreach ($tag as $val)
    {
        //REMOVE SPACE FROM $VALUE ----
        $val = trim($val);
        $tag2[] = '<a href="'.$config['site_url'].'listing?keywords='.$val.'" class="bylabel bylabelmini" target="_blank">'.$val.'</a>';
    }
    $item_tag = implode('  ', $tag2);

}
else {
    header('Location: 404.php');
    exit();
}




?>

<link href="../assets/css/user-html.css" rel="stylesheet">
<main class="app-layout-content">

    <!-- Page Content -->
    <div class="container-fluid p-y-md">
        <!-- Partial Table -->
        <div class="card">
            <div class="card-header">
                <h4><?php echo $item_title; ?>
                    <span class="label-wrap hidden-sm hidden-xs">
                        <?php
                        if($item_featured == "1")
                            echo '<span class="label featured"> Featured</span>';
                        if($item_urgent == "1")
                            echo '<span class="label urgent"> Urgent</span>';
                        if($item_highlight == "1")
                            echo '<span class="label highlight"> Highlight</span>';
                        ?>
                    </span>
                </h4>
            </div>
            <div class="card-block">

                <section class="content">
                    <div class="row">
                        <div class="col-sm-8" style="border: 0px solid #000;">
                            <div class="item-box" style="margin-top: 20px">
                                <ul class="nav nav-tabs dark">
                                    <li class="active">
                                        <a data-toggle="tab" href="#4" aria-expanded="true">Item Details</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div id="4" class="tab-pane active fade in">
                                        <div>
                                            <?php
                                            $item_custom = array();
                                            $item_custom_textarea = array();
                                            $item_checkbox = array();
                                            $rows = ORM::for_table($config['db']['pre'].'custom_data')
                                                ->where('product_id', $item_id)
                                                ->find_many();

                                            foreach ($rows as $customdata){
                                                $field_id = $customdata['field_id'];
                                                $field_type = $customdata['field_type'];
                                                $field_data = $customdata['field_data'];

                                                $custom_fields_title = get_customField_title_by_id($field_id);

                                                if($field_type == 'checkboxes'){

                                                    $checkbox_value = explode(',', $field_data);
                                                    $checkbox_value2 = array();
                                                    foreach ($checkbox_value as $val)
                                                    {
                                                        $val = get_customOption_by_id(trim($val));
                                                        $checkbox_value2[] = '<div class="col-md-4 col-sm-4"><div style="line-height: 30px;"><i class="fa fa-check"></i> '.$val.'</div></div>';
                                                    }
                                                    if($custom_fields_title != ""){
                                                        $item_checkbox[$field_id]['title'] = $custom_fields_title;
                                                        $item_checkbox[$field_id]['value'] = implode('  ', $checkbox_value2);
                                                    }

                                                }
                                                elseif($field_type == 'textarea') {
                                                    $item_custom_textarea[$field_id]['title'] = $custom_fields_title;
                                                    $item_custom_textarea[$field_id]['value'] = stripslashes($field_data);
                                                }
                                                else{
                                                    if($field_type == 'radio-buttons' or  $field_type == 'drop-down') {
                                                        $custom_fields_data = get_customOption_by_id($field_data);
                                                    }else{
                                                        $custom_fields_data = stripslashes($field_data);
                                                    }
                                                    $item_custom[$field_id]['title'] = $custom_fields_title;
                                                    $item_custom[$field_id]['value'] = $custom_fields_data;
                                                }
                                            }

                                            ?>
                                            <div class="quick-info">
                                                <div class="detail-title">
                                                    <h2 class="title-left">Additional Details</h2>
                                                </div>
                                                <ul class="clearfix">
                                                    <?php
                                                    foreach($item_custom as $value)
                                                    {
                                                        echo '<li>
                                                                    <div class="inner clearfix">
                                                                        <span class="label">'.$value['title'].'</span>
                                                                        <span class="desc">'.$value['value'].'</span>
                                                                    </div>
                                                                </li>';
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                            <?php
                                            foreach($item_custom_textarea as $value)
                                            {
                                                echo '<div class="text-widget">
                                                                <div class="detail-title">
                                                                    <h2 class="title-left">'.$value['title'].'</h2>
                                                                </div>
                                                                <div class="inner row">
                                                                    <div class="user-html">'.$value['value'].'</div>
                                                                </div>
                                                            </div>';
                                            }

                                            foreach($item_checkbox as $value)
                                            {
                                                echo '<div class="text-widget">
                                                                <div class="detail-title">
                                                                    <h2 class="title-left">'.$value['title'].'</h2>
                                                                </div>
                                                                <div class="inner row">
                                                                    '.$value['value'].'
                                                                </div>
                                                            </div>';
                                            }
                                            ?>
                                            <div class="description">
                                                <div class="detail-title">
                                                    <h2 class="title-left">Description</h2>
                                                </div>
                                                <div class="user-html"><?php echo $item_description;?></div>

                                            </div>

                                        </div>
                                    </div>
                                    <!--<div id="5" class="tab-pane fade">
                                        <p>0</p>
                                    </div>
                                    <div id="6" class="tab-pane fade">
                                        <p></p>
                                    </div>-->
                                </div>

                            </div>
                        </div>
                        <!-- end col -->
                        <div class="col-sm-4" style="border: 0px solid #000;">
                            <div class="pad-bot-20" id="js-delete-single">
                                <div class="item-box fbold">
                                    <div class="pad-20 item-sale text-center ajax-item-listing" data-item-id="<?php echo $item_id ?>">

                                        <?php
                                        if(isset($_GET['resubmit'])) {

                                            echo '<button class="btn btn-success btn-rounded waves-effect waves-light m-r-5 btn-sm item-js-action" type="button" data-ajax-action="approveResubmitItem" data-ajax-type="approve"><span class="btn-label"><i class="ti-check"></i></span>Approve</button>';

                                            echo '<button class="btn btn-danger btn-rounded waves-effect waves-light btn-sm  m-r-5 item-js-action" type="button" data-ajax-action="deleteResubmitItem" data-ajax-type="reject"><span class="btn-label"><i class="ti-trash"></i></span>Reject</button>';

                                        }
                                        else{
                                            if ($item_status == "pending") {
                                                echo '<button class="btn btn-success btn-rounded waves-effect waves-light m-r-5 btn-sm item-js-action" type="button" data-ajax-action="approveitem" data-ajax-type="approve"><span class="btn-label"><i class="ti-check"></i></span>Approve</button>';
                                            }

                                            echo '<button class="btn btn-danger btn-rounded waves-effect waves-light  m-r-5 btn-sm item-js-action" type="button" data-ajax-action="deleteads" data-ajax-type="delete"><span class="btn-label"><i class="ti-trash"></i></span>Delete</button>';


                                        }

                                        echo '<a class="btn btn-info btn-rounded waves-effect waves-light btn-sm mar-10" href="#" data-url="panel/post_edit.php?id='.$item_id.'" data-toggle="slidePanel"><span class="btn-label"><i class="ti-pencil"></i></span>edit</a>';
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                            if(isset($_GET['resubmit'])) {
                                ?>
                                <div class="item-box">
                                    <div class="item-price">
                                        <div class="fbold">Message to reviewer</div>

                                        <p style="padding-top: 10px"><?php echo $info['comments'] ?></p>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="pad-top-20 pad-bot-20">
                                <div class="item-box">
                                    <div class="pad-20">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="profile-picture medium-profile-picture mpp XxGreen">
                                                    <a href="<?php echo $company_link ?>" target="_blank">
                                                        <img width="70px" style="min-height:70px" alt="<?php echo $company_name ?>" src="<?php echo $company_image ?>"></a>
                                                </div>
                                            </div>
                                            <div class="col-sm-9">
                                                <div>
                                                    <div class="align-left fbold">
                                                        <a href="<?php echo $company_link ?>" style="text-decoration:none" target="_blank"><?php echo $company_name ?></a>
                                                        <div class="align-left text-muted font13 pad-3">Created at: <?php echo $company_created_at ?></div>
                                                    </div>
                                                    <div class="align-left"><a href="<?php echo $company_link ?>" class="bylabel bylabelLarge" target="_blank"><?php echo $config['company_enable']?'View Company':'View Profile'; ?></a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="item-box meta-attributes">
                                <div class="pad-20">
                                    <table class="meta-attributes__table align-left" cellspacing="0" cellpadding="10" border="0">
                                        <tbody>

                                        <tr>
                                            <td class="meta-attributes__attr-name">Job ID</td>
                                            <td class="meta-attributes__attr-detail"><?php echo $item_id ?></td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">Views</td>
                                            <td class="meta-attributes__attr-detail"><?php echo $item_view ?></td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">Status</td>
                                            <td class="meta-attributes__attr-detail"><?php echo $status ?></td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">Posted</td>
                                            <td class="meta-attributes__attr-detail">
                                                <time itemprop="dateCreated" datetime="<?php echo $item_created_at ?>">
                                                    <?php echo $item_created_at ?>
                                                </time>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">Expire On</td>
                                            <td class="meta-attributes__attr-detail">
                                                <time itemprop="dateCreated" datetime="<?php echo $expire_date ?>">
                                                    <?php echo $expire_date ?>
                                                </time>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="meta-attributes__attr-name">Category</td>
                                            <td class="meta-attributes__attr-detail">
                                                <a class="bylabel bylabelmini" href="<?php echo $item_catlink ?>" target="_blank"><?php echo $item_category ?></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">SubCategory</td>
                                            <td class="meta-attributes__attr-detail">
                                                <a class="bylabel bylabelmini" href="<?php echo $item_subcatlink ?>" target="_blank"><?php echo $item_sub_category ?></a></td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">Job Type</td>
                                            <td class="meta-attributes__attr-detail"><?php echo $item_product_type; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">Salary</td>
                                            <td class="meta-attributes__attr-detail"><?php echo $item_salary_min.' - '.$item_salary_max." per ".$item_salary_type; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">Negotiated</td>
                                            <td class="meta-attributes__attr-detail"><?php echo $item_negotiable; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">Location</td>
                                            <td class="meta-attributes__attr-detail"><?php echo $item_location ?></td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">City</td>
                                            <td class="meta-attributes__attr-detail"><?php echo get_cityName_by_id($item_city); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">State</td>
                                            <td class="meta-attributes__attr-detail"><?php echo get_stateName_by_id($item_state); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">Country</td>
                                            <td class="meta-attributes__attr-detail"><?php echo get_countryName_by_code($item_country); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">Phone</td>
                                            <td class="meta-attributes__attr-detail"><?php echo $item_phone ?></td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">Hide Phone</td>
                                            <td class="meta-attributes__attr-detail"><?php echo $item_hide_phone; ?></td>
                                        </tr>

                                        <tr>
                                            <td class="meta-attributes__attr-name">Tags</td>
                                            <td>
                                                    <span class="meta-attributes__attr-tags">
                                                        <?php echo $item_tag; ?>
                                                    </span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div><!-- end col -->
                    </div><!-- end row -->
                </section>

            </div>
            <!-- .card-block -->
        </div>
        <!-- .card -->
        <!-- End Partial Table -->

    </div>
    <!-- .container-fluid -->
    <!-- End Page Content -->

</main>



<?php include("../footer.php"); ?>
<script src="../assets/js/admin-ajax.js"></script>
<script src="../assets/js/alert.js"></script>

</body>

</html>

