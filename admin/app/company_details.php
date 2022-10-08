<?php
require_once('../includes.php');

$info = ORM::for_table($config['db']['pre'].'companies')->find_one($_GET['id']);


if (!empty($info)) {

    $item_id = $info['id'];
    
    $item_title = $info['name'];
    $item_description = nl2br(stripcslashes($info['description']));
    $item_location = $info['location'];
    $item_city = $info['city'];
    $item_state = $info['state'];
    $item_country = $info['country'];
    $item_status = $info['status'];
    $item_created_at = timeAgo($info['created_at']);
    $item_updated_at = date('d M Y', $info['updated_at']);

    $item_phone = $info['phone'];
    $item_fax = $info['fax'];
    $item_email = $info['email'];
    $item_website = $info['website'];
    $item_facebook = $info['facebook'];
    $item_twitter = $info['twitter'];
    $item_linkedin = $info['linkedin'];
    $item_pinterest = $info['pinterest'];
    $item_youtube = $info['youtube'];
    $item_instagram = $info['instagram'];

    if($info['logo'] != ""){
        $image = $info['logo'];
    }else{
        $image = "default.png";
    }
    $jobs = count_company_jobs($info['id']);

    $latlong = $info['latlong'];
    $map = explode(',', $latlong);
    $lat = $map[0];
    $long = $map[1];

    $item_author_id = $info['user_id'];
    $info2 = get_user_data(null,$item_author_id);

    $item_author_username = ucfirst($info2['username']);
    $item_author_email = $info2['email'];
    $item_author_image = $info2['image'];
    $item_author_country = $info2['country'];
    $item_author_joined = $info2['created_at'];

    $pro_url = create_slug($info['name']);
    $item_link = $config['site_url'].'/company/' . $info['id'] . '/'.$pro_url;

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
                <div>
                <div class="pull-left m-r"><img class="img-avatar img-avatar-squre" src="<?php echo $config['site_url'];?>storage/products/<?php echo $image; ?>"></div>
                <h4 class="font-500 m-t-0 m-b-0"><?php echo $item_title; ?></h4>
                <p class="text-muted m-b-0"><?php echo $jobs; ?> jobs</p>
                </div>
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
                                            
                                            <div class="description">
                                                <div class="detail-title">
                                                    <h2 class="title-left">Description</h2>
                                                </div>
                                                <div class="user-html"><?php echo $item_description;?></div>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- end col -->
                        <div class="col-sm-4" style="border: 0px solid #000;">
                            <div class="pad-top-20 pad-bot-20">
                                <div class="item-box">
                                    <div class="pad-20">
                                        <div class="row">
                                            <div class="col-sm-9">
                                                <div>
                                                    <div class="align-left fbold">
                                                        <?php echo $item_author_username ?>
                                                    </div>
                                                    <div class="align-left font13 pad-3"><?php echo $item_author_country ?> <span class="flags flag-br"></span></div>
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
                                            <td class="meta-attributes__attr-name">Company ID</td>
                                            <td class="meta-attributes__attr-detail"><?php echo $item_id ?></td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">Created</td>
                                            <td class="meta-attributes__attr-detail">
                                                <time itemprop="dateCreated" datetime="<?php echo $item_created_at ?>">
                                                    <?php echo $item_created_at ?>
                                                </time>
                                            </td>
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
                                            <td class="meta-attributes__attr-name">Fax</td>
                                            <td class="meta-attributes__attr-detail"><?php echo $item_fax ?></td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">Email</td>
                                            <td class="meta-attributes__attr-detail"><?php echo $item_email ?></td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">Website</td>
                                            <td class="meta-attributes__attr-detail"><?php echo $item_website ?></td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">Facebook</td>
                                            <td class="meta-attributes__attr-detail"><?php echo $item_facebook ?></td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">Twitter</td>
                                            <td class="meta-attributes__attr-detail"><?php echo $item_twitter ?></td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">Linkedin</td>
                                            <td class="meta-attributes__attr-detail"><?php echo $item_linkedin ?></td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">Pinterest</td>
                                            <td class="meta-attributes__attr-detail"><?php echo $item_pinterest ?></td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">Youtube</td>
                                            <td class="meta-attributes__attr-detail"><?php echo $item_youtube ?></td>
                                        </tr>
                                        <tr>
                                            <td class="meta-attributes__attr-name">Instagram</td>
                                            <td class="meta-attributes__attr-detail"><?php echo $item_instagram ?></td>
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

