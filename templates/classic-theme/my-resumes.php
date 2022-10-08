<?php
overall_header(__("My Resumes"));
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
                <h3><?php _e("My Resumes") ?></h3>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("My Resumes") ?></li>
                    </ul>
                </nav>
            </div>

            <!-- Row -->
            <div class="row">
                <!-- Dashboard Box -->
                <div class="col-xl-12">
                    <div class="dashboard-box">
                        <div class="headline">
                            <h3><i class="icon-material-baseline-notifications-none"></i> <?php _e("My Resumes") ?></h3>
                            <form method="get" action="">
                                <div class="input-with-icon margin-right-10">
                                    <input class="with-border margin-bottom-0" type="text" name="keywords" value="<?php _esc($keywords) ?>" placeholder="<?php _e("Search") ?>...">
                                    <i class="icon-feather-search"></i>
                                </div>
                            </form>
                            <a href="<?php url("ADD-RESUME") ?>" class="button ripple-effect"><?php _e("Add New Resume") ?></a>
                        </div>
                        <!-- Content Start -->
                        <div class="content">
                            <div class="content with-padding">
                                <div class="table-responsive">
                                    <table class="basic-table dashboard-box-list">
                                        <tr>
                                            <th><?php _e("File") ?></th>
                                            <th class="big-width"><?php _e("Name") ?></th>
                                            <th class="small-width"><?php _e("Actions") ?></th>
                                        </tr>

                                        <?php
                                        if($resumes){
                                            foreach ($items as $item){
                                                ?>
                                                <tr class="resume-row" data-item-id="<?php _esc($item['id'])?>">
                                                    <td>
                                                        <a href="<?php _esc($config['site_url']);?>storage/resumes/<?php _esc($item['filename'])?>" title="<?php _esc($item['filename'])?>" class="button ripple-effect" download>
                                                            <i class="icon-feather-download"></i> <?php _e("Download") ?>
                                                        </a>
                                                    </td>
                                                    <td><?php _esc($item['name'])?></td>
                                                    <td>
                                                        <a href="<?php url("EDIT-RESUME") ?>/<?php _esc($item['id'])?>" class="button gray ripple-effect ico" data-tippy-placement="top" title="<?php _e("Edit") ?>"><i class="icon-feather-edit"></i></a>
                                                        <a href="#" class="button gray ripple-effect ico ajax-delete-resume" data-tippy-placement="top" title="<?php _e("Delete") ?>"><i class="icon-feather-trash-2"></i></a>
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

                            </div>
                        </div>
                        <!-- Content End -->
                    </div>
                </div>
            </div>
            <!-- Row / End -->
            <?php include_once TEMPLATE_PATH.'/overall_footer_dashboard.php'; ?>
