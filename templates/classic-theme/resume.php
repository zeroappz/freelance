<?php
overall_header(__(""));
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
                <h3><?php _e("Add New Resume") ?></h3>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("Add New Resume") ?></li>
                    </ul>
                </nav>
            </div>

            <!-- Row -->
            <div class="row">
                <!-- Dashboard Box -->
                <div class="col-xl-12">
                    <div class="dashboard-box">
                        <div class="headline">
                            <h3><i class="icon-material-baseline-notifications-none"></i> <?php _e("Add New Resume") ?></h3>
                        </div>
                        <!-- Content Start -->
                        <div class="content">
                            <div class="content with-padding">
                                <?php
                                if($error != ''){
                                    echo '<span class="status-not-available">'.$error.'</span>';
                                }
                                ?>
                                <form method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                                    <div class="submit-field">
                                        <h5><?php _e("Name") ?></h5>
                                        <input type="text" class="with-border" id="name" name="name" value="<?php _esc($name)?>">
                                    </div>
                                    <div class="submit-field">
                                        <h5><?php _e("File") ?> *</h5>
                                        <div class="uploadButton">
                                            <input class="uploadButton-input" type="file" id="resume" name="resume"/>
                                            <label class="uploadButton-button ripple-effect" for="resume"><?php _e("Upload Resume") ?></label>
                                            <span class="uploadButton-file-name"><?php _e("Only pdf, doc, docx, rtf, rtx, ppt, pptx, jpeg, jpg, bmp, png file types allowed.") ?></span>
                                        </div>
                                    </div>
                                    <?php if($id != '') echo '<input type="hidden" name="id" value="'._esc($id,false).'">'; ?>
                                    <button type="submit" name="submit" class="button ripple-effect"><?php _e("Save") ?></button>
                                </form>
                            </div>
                        </div>
                        <!-- Content End -->
                    </div>
                </div>
            </div>
            <!-- Row / End -->
            <?php include_once TEMPLATE_PATH.'/overall_footer_dashboard.php'; ?>

