<?php
require_once('../includes.php');
?>

<link href="../assets/js/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">

<!-- /.Language Translation modal -->
<div id="modal_LangTranslation" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Edit Language Translation</h4>
                </div>
                <div class="modal-body">
                    <div class="loader" style="text-align: center;">
                        <img src="<?php echo SITEURL; ?>includes/assets/images/loading.gif"/>
                    </div>
                    <div class="form-horizontal" id="displayData">
                        <!--Dynamic form fields-->
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="saveEditLanguage">Save</button>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Language Translation modal -->

<!-- Page Content -->
<main class="app-layout-content">
    <div class="container-fluid p-y-md">
        <div class="card">
            <div class="card-block">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <div id="quickad-tbs" class="wrap">
                                <div class="quickad-tbs-body">
                                    <div id="quickad-services-wrapper">
                                        <div class="panel panel-default quickad-main">
                                            <div class="panel-body">
                                                <h4 class="quickad-block-head">
                                                    <span class="quickad-category-title">All Salary Types</span>
                                                    <button type="button" class="new-type ladda-button pull-right btn btn-success"
                                                    data-spinner-size="40" data-style="zoom-in">
                                                    <span class="ladda-label"><i class="glyphicon glyphicon-plus"></i> Add New</span>
                                                </button>
                                            </h4>
                                            <form method="post" id="new-type-form" style="display: none">
                                                <div class="form-group quickad-margin-bottom-md">
                                                    <div class="form-field form-required">
                                                        <label for="new-type-name">Title</label>
                                                        <input class="form-control" id="new-type-name" type="text" name="name" required=""/>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-success confirm">Save</button>
                                                    <button type="button" id="cancel-button" class="btn btn-default">Cancel</button>
                                                </div>
                                            </form>

                                            <div class="quickad-margin-top-xlg" id="ab-services-list">
                                                <div class="panel-group ui-sortable" id="services_list" role="tablist" aria-multiselectable="true">
                                                    <?php
                                                    $rows = ORM::for_table($config['db']['pre'].'salary_type')
                                                    ->order_by_asc('position')
                                                    ->find_many();
                                                      foreach ($rows as $row) {
                                                          ?>
                                                          <div class="panel panel-default quickad-js-collapse" data-type-id="<?php echo $row['id']; ?>">
                                                              <div class="panel-heading" role="tab" id="s_<?php echo $row['id']; ?>">
                                                                  <div class="row">
                                                                      <div class="col-sm-8 col-xs-10">
                                                                          <div class="quickad-flexbox">
                                                                              <div class="quickad-flex-cell quickad-vertical-middle" style="width: 1%">
                                                                                  <i class="quickad-js-handle quickad-icon quickad-icon-draghandle quickad-margin-right-sm quickad-cursor-move ui-sortable-handle" title="Reorder"></i>
                                                                              </div>
                                                                              <div class="quickad-flex-cell quickad-vertical-middle">
                                                                                  <a role="button" class="panel-title collapsed quickad-js-service-title" data-toggle="collapse" data-parent="#services_list"  href="#service_<?php echo $row['id']; ?>" aria-expanded="false" aria-controls="service_<?php echo $row['id']; ?>">
                                                                                      <?php echo $row['title']; ?>
                                                                                  </a>
                                                                              </div>
                                                                          </div>
                                                                      </div>
                                                                      <div class="col-sm-4 col-xs-2">
                                                                          <div class="quickad-flexbox">
                                                                              <div class="quickad-flex-cell quickad-vertical-middle text-right" style="width: 10%">
                                                                                  <label class="css-input css-checkbox css-checkbox-default m-t-0 m-b-0">
                                                                                      <input type="checkbox" id="checkbox<?php echo $row['id']; ?>" name="check-all" value="<?php echo $row['id']; ?>" class="service-checker"><span></span>
                                                                                  </label>
                                                                              </div>
                                                                          </div>
                                                                      </div>
                                                                  </div>
                                                              </div>
                                                              <div id="service_<?php echo $row['id']; ?>" class="panel-collapse collapse" role="tabpanel" style="height: 0">
                                                                  <div class="panel-body">
                                                                      <form method="post" id="<?php echo $row['id']; ?>">
                                                                          <div class="row">
                                                                              <div class="col-md-6 col-sm-12">
                                                                                  <div class="form-group">
                                                                                      <label for="title_<?php echo $row['id']; ?>">Title</label>
                                                                                      <input name="title" value="<?php echo $row['title']; ?>" id="title_<?php echo $row['id']; ?>" class="form-control" type="text">
                                                                                      <input name="id" value="<?php echo $row['id']; ?>" type="hidden">
                                                                                  </div>
                                                                              </div>
                                                                              <div class="col-md-6 col-sm-12">
                                                                                  <div class="form-group">
                                                                                      <label for="status_<?php echo $row['id']; ?>">Enable/Disable</label>
                                                                                      <select name="status" id="status_<?php echo $row['id']; ?>" class="form-control">
                                                                                          <option value="1">Enable</option>
                                                                                          <option value="0" <?php echo ($row['active'] == 0)? "selected" :  "" ?>>Disable</option>
                                                                                      </select>
                                                                                  </div>
                                                                              </div>
                                                                          </div>
                                                                          <div class="panel-footer">
                                                                              <button type="button" class="btn btn-lg btn-warning quickad-type-edit" data-type-id="<?php echo $row['id']; ?>">
                                                                                  <span class="ladda-label"><i class="fa fa-language"></i> Edit Language</span>
                                                                              </button>
                                                                              <button type="button" class="btn btn-lg btn-success ladda-button ajax-type-edit" data-style="zoom-in" data-spinner-size="40" onclick="editSalaryType(<?php echo $row['id']; ?>);">
                                                                                  <span class="ladda-label">Save</span>
                                                                              </button>
                                                                              <button class="btn btn-lg btn-default js-reset" type="reset">Reset </button>
                                                                          </div>
                                                                      </form>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      <?php }
                                                     ?>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <button type="button" id="quickad-delete" class="btn btn-danger ladda-button"
                                                data-spinner-size="40" data-style="zoom-in"><span class="ladda-label"><i
                                                    class="glyphicon glyphicon-trash"></i> Delete</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="quickad-alert" class="quickad-alert"></div>
                        </div>
                    </div>

                </div>
                <!-- .card-block -->
            </div>
            <!-- .card -->
            <!-- End Partial Table -->

        </div>
        <!-- .container-fluid -->
        <!-- End Page Content -->

    </main>

    <script>
        function editSalaryType(id){
            $('.ajax-type-edit').addClass('bookme-progress');
            var data = $('#'+id).serialize();
            $.post(ajaxurl+'?action=editSalaryType&'+data, function (response) {
                if (response != 0) {
                    quickadAlert({success: ['Successfully edited']});
                } else {
                    quickadAlert({error: ['Problem in saving, Please try again.']});
                }
                $('.ajax-type-edit').removeClass('bookme-progress');
            });
        }
    </script>
    <?php include("../footer.php"); ?>
    <script src="../assets/js/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="js/salary-types.js"></script>
    <script src="../assets/js/alert.js"></script>
</body></html>
