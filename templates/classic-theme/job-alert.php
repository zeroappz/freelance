<?php
overall_header(__("Job Alert"));
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
                <h3><?php _e("Job Alert") ?></h3>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("Job Alert") ?></li>
                    </ul>
                </nav>
            </div>

            <!-- Row -->
            <div class="row">
                <!-- Dashboard Box -->
                <div class="col-xl-12">
                    <div class="dashboard-box">
                        <div class="headline">
                            <h3><i class="icon-material-baseline-notifications-none"></i> <?php _e("Job Alert") ?></h3>
                        </div>
                        <!-- Content Start -->
                        <div class="content">
                            <div class="content with-padding">
                                <form method="post">
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <input type="checkbox" id="notify" name="notify" value="1" onchange="NotifyValueChanged()" <?php if($notify) { echo "checked"; }?>>
                                            <label for="notify"><span class="checkbox-icon"></span> <?php _e("Notify me by e-mail when a job gets posted that is relevant to my choice.") ?></label>
                                        </div>
                                        <ul class="skills margin-left-20">
                                             <?php foreach ($categories as $category){ ?>
                                                <li>
                                                    <div class="checkbox">
                                                        <input type="checkbox" id="<?php _esc($category['id'])?>" name="choice[<?php _esc($category['id'])?>]" value="<?php _esc($category['id'])?>" <?php _esc($category['selected'])?>>
                                                        <label for="<?php _esc($category['id'])?>"><span class="checkbox-icon"></span> <?php _esc($category['name'])?></label>
                                                    </div>
                                                </li>
                                             <?php } ?>
                                        </ul>
                                    </div>
                                    <button type="submit" name="submit" class="button ripple-effect"><?php _e("Save Changes") ?></button>
                                </form>
                                <script type="text/javascript">
                                    function NotifyValueChanged()
                                    {
                                        if($('#notify').is(":checked"))
                                            $(".skills").slideDown();
                                        else
                                            $(".skills").slideUp();
                                    }
                                    NotifyValueChanged();
                                </script>
                            </div>
                        </div>
                        <!-- Content End -->
                    </div>
                </div>
            </div>
            <!-- Row / End -->
            <?php include_once TEMPLATE_PATH.'/overall_footer_dashboard.php'; ?>