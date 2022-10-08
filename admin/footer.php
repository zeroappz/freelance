</div>
<!-- .app-layout-container -->
</div>
<!-- .app-layout-canvas -->

<!-- Apps Modal -->
<!-- Opens from the button in the header -->
<div id="apps-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-sm modal-dialog modal-dialog-top">
        <div class="modal-content">
            <!-- Apps card -->
            <div class="card m-b-0">
                <div class="card-header bg-app bg-inverse">
                    <h4>Apps</h4>
                    <ul class="card-actions">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="ion-close"></i></button>
                        </li>
                    </ul>
                </div>
                <div class="card-block">
                    <div class="row text-center">
                        <div class="col-xs-6">
                            <a class="card card-block m-b-0 bg-app-secondary bg-inverse" href="<?php echo ADMINURL;?>">
                                <i class="ion-speedometer fa-4x"></i>
                                <p>Admin</p>
                            </a>
                        </div>
                        <div class="col-xs-6">
                            <a class="card card-block m-b-0 bg-app-tertiary bg-inverse" target="_blank" href="<?php echo $config['site_url'];?>home">
                                <i class="ion-laptop fa-4x"></i>
                                <p>Frontend</p>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- .card-block -->
            </div>
            <!-- End Apps card -->
        </div>
    </div>
</div>
<!-- End Apps Modal -->

<div class="app-ui-mask-modal"></div>

<!-- Zeunix Core JS: jQuery, Bootstrap, slimScroll, scrollLock and App.js -->
<script src="<?php echo ADMINURL; ?>assets/js/core/jquery.min.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/core/bootstrap.min.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/core/jquery.slimscroll.min.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/core/jquery.scrollLock.min.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/core/jquery.placeholder.min.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/app.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/app-custom.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>


<script src="<?php echo ADMINURL; ?>assets/js/admin-ajax.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/plugins/ajaxForm/jquery.form.js"></script>
<!-- Sweet-Alert  -->
<script src="<?php echo ADMINURL; ?>assets/js/plugins/sweetalert/sweetalert.min.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/plugins/sweetalert/jquery.sweet-alert.custom.js"></script>

<!-- datatables JS Code -->
<script src="<?php echo ADMINURL; ?>assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/pages/base_tables_datatables.js"></script>
<!-- select2  -->
<script src="<?php echo ADMINURL; ?>assets/js/plugins/select2/select2.full.min.js"></script>


<script src="<?php echo ADMINURL; ?>assets/js/plugins/asscrollable/jquery.asScrollable.all.min.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/plugins/slidepanel/jquery-slidePanel.min.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/plugins/bootbox/bootbox.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/slidepanel/core.min.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/plugins/alertify/alertify.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/slidepanel/action-btn.min.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/slidepanel/selectable.min.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/slidepanel/components.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/slidepanel/app.min.js"></script>

