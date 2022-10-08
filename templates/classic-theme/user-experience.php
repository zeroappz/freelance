<?php
overall_header(__("Add New Experience"));
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
                <h3><?php _e("Add New Experience") ?></h3>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("Add New Experience") ?></li>
                    </ul>
                </nav>
            </div>

            <!-- Row -->
            <div class="row">
                <!-- Dashboard Box -->
                <div class="col-xl-12">
                    <div class="dashboard-box">
                        <!-- Headline -->
                        <div class="headline">
                            <h3><i class="icon-feather-award"></i> <?php _e("Add New Experience") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <?php
                            if($error != ''){
                                echo '<span class="status-not-available">'.$error.'</span>';
                            }
                            ?>
                            <form method="post" accept-charset="UTF-8">
                                <div class="submit-field">
                                    <h5><?php _e("Title") ?> *</h5>
                                    <input type="text" class="with-border" id="title" name="title" value="<?php _esc($title)?>" required="">
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e("Company") ?> *</h5>
                                    <input type="text" class="with-border" id="company" name="company" value="<?php _esc($company)?>" required="">
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e("City") ?> *</h5>
                                    <input type="text" class="with-border" id="city" name="city" value="<?php _esc($city)?>">
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e("Description") ?> *</h5>
                                    <textarea rows="3" class="with-border" name="description" required="" style="white-space: pre-line;"><?php _esc($description)?></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-12">
                                        <div class="submit-field">
                                            <h5><?php _e("Start Date") ?> *</h5>
                                            <input type="text" class="with-border margin-bottom-0" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-autoclose="true" data-date-language="<?php _esc($config['lang_code']) ?>" data-date-end-date="0d" name="start_date" value="<?php _esc($start_date)?>" <?php if($language_direction == 'rtl') echo 'data-date-rtl="true"'; ?> required>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-12">
                                        <div class="submit-field">
                                            <h5><?php _e("End Date") ?></h5>
                                            <input type="text" class="with-border margin-bottom-0" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-autoclose="true" data-date-language="<?php _esc($config['lang_code']) ?>" data-date-end-date="0d" name="end_date" value="<?php _esc($end_date)?>" <?php if($language_direction == 'rtl') echo 'data-date-rtl="true"'; ?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e("Currently Working?") ?></h5>
                                    <div class="radio margin-right-20">
                                        <input class="with-gap" type="radio" name="currently_working" id="1" value="1" checked />
                                        <label for="1"><span class="radio-label"></span><?php _e("Yes") ?></label>
                                    </div>
                                    <div class="radio margin-right-20">
                                        <input class="with-gap" type="radio" name="currently_working" id="0" value="0" <?php if($currently_working == '0') echo "checked"; ?>/>
                                        <label for="0"><span class="radio-label"></span><?php _e("No") ?></label>
                                    </div>
                                </div>
                                <?php if($id != '') echo '<input type="hidden" name="id" value="'._esc($id,false).'">'; ?>
                                <button type="submit" name="submit" class="button ripple-effect"><?php _e("Save") ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Row / End -->


<link href="<?php _esc(TEMPLATE_URL);?>/css/bootstrap-datepicker3.min.css" rel="stylesheet"/>
<script src="<?php _esc(TEMPLATE_URL);?>/js/bootstrap-datepicker.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.<?php _esc($config['lang_code']) ?>.min.js" charset="UTF-8"></script>
<?php include_once TEMPLATE_PATH.'/overall_footer_dashboard.php'; ?>
