<?php
overall_header(__("My Companies"));
?>
<!-- Dashboard Container -->
<div class="dashboard-container">

    <?php include_once TEMPLATE_PATH.'/dashboard_sidebar.php'; ?>


    <!-- Dashboard Content
    ================================================== -->
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner" >

            <!-- Dashboard Headline -->
            <div class="dashboard-headline">
                <h3><?php _e("My Companies") ?></h3>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("My Companies") ?></li>
                    </ul>
                </nav>
            </div>

            <!-- Row -->
            <div class="row">
                <!-- Dashboard Box -->
                <div class="col-xl-12">
                    <div class="dashboard-box">
                        <div class="headline">
                            <h3><i class="icon-feather-box"></i> <?php _e("My Companies") ?></h3>
                            <div class="margin-right-10">
                                <form method="get" action="">
                                    <div class="input-with-icon">
                                        <input class="with-border margin-bottom-0" type="text" name="keywords" value="<?php _esc($keywords)?>" placeholder="<?php _e("Search") ?>...">
                                        <i class="icon-feather-search"></i>
                                    </div>
                                </form>
                            </div>
                            <a href="<?php url("CREATE-COMPANY") ?>" class="button ripple-effect"><?php _e("Create New Company") ?></a>
                        </div>
                        <!-- Content Start -->
                        <div class="content">
                            <div class="content with-padding">
                                <div class="table-responsive">
                                    <table id="js-table-list" class="basic-table dashboard-box-list">
                                        <tr>
                                            <th class="big-width"><?php _e("Name") ?></th>
                                            <th class="small-width"><?php _e("Jobs") ?></th>
                                            <th class="small-width"><?php _e("Actions") ?></th>
                                        </tr>
                                        <?php
                                        if($totalitem){
                                            foreach ($items as $item){
                                                ?>
                                            <tr class="company-row" data-item-id="<?php _esc($item['id'])?>">
                                                <td>
                                                    <div class="job-listing">
                                                        <div class="job-listing-details">
                                                            <div class="job-listing-company-logo">
                                                                <img src="<?php _esc($config['site_url'])?>storage/products/<?php _esc($item['image'])?>" alt="">
                                                            </div>
                                                            <div class="job-listing-description">
                                                                <a href="<?php _esc($item['link'])?>"><h3 class="job-listing-title"><?php _esc($item['name'])?></h3></a>
                                                                <p class="job-listing-text"><?php _esc($item['description'])?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><a href="<?php _esc($item['link'])?>#all-jobs"><strong><?php _esc($item['jobs'])?></strong></a></td>
                                                <td>
                                                    <a href="<?php url("EDIT-COMPANY") ?>/<?php _esc($item['id'])?>" class="button gray ripple-effect ico" data-tippy-placement="top" title="<?php _e("Edit") ?>"><i class="icon-feather-edit"></i></a>
                                                    <a href="#" class="button gray ripple-effect ico ajax-delete-company" data-tippy-placement="top" title="<?php _e("Delete") ?>"><i class="icon-feather-trash-2"></i></a>
                                                </td>
                                            </tr>
                                            <?php }
                                        }else{ ?>
                                            <tr>
                                                <td colspan="3" class="text-center"><?php _e("No result found.") ?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Pagination -->
                                        <div class="pagination-container margin-top-20">
                                            <nav class="pagination">
                                                <ul>
                                                    <?php
                                                    foreach($pages as $page) {
                                                        if ($page['current'] == 0){
                                                            ?>
                                                            <li><a href="<?php _esc($page['link'])?>"><?php _esc($page['title'])?></a></li>
                                                        <?php }else{
                                                            ?>
                                                            <li><a href="#" class="current-page"><?php _esc($page['title'])?></a></li>
                                                        <?php }
                                                    }
                                                    ?>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Content End -->
                    </div>
                </div>
            </div>
            <!-- Row / End -->
<?php include_once TEMPLATE_PATH.'/overall_footer_dashboard.php'; ?>