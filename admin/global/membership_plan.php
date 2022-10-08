<?php
require_once('../includes.php');

?>
<!-- Page JS Plugins CSS -->

<main class="app-layout-content">

    <!-- Page Content -->
    <div class="container-fluid p-y-md">
        <!-- Partial Table -->
        <div class="card">
            <div class="card-header">
                <h4>Membership Plan</h4>
                <div class="pull-right">
                    <a href="#" data-url="panel/membership_plan_add.php" data-toggle="slidePanel" class="btn btn-success waves-effect waves-light m-r-10">Add Plan</a>
                </div>
            </div>
            <div class="card-block">
                <div id="js-table-list">
                    <table id="ajax_datatable" data-jsonfile="membership_plan.php" class="table table-vcenter table-hover" data-tablesaw-mode="stack" data-plugin="animateList" data-animate="fade" data-child="tr" data-selectable="selectable">
                        <thead>
                        <tr>
                            <th>Plan Name</th>
                            <th>Monthly Price</th>
                            <th>Annual Price</th>
                            <th>Lifetime Price</th>
                            <th style="width: 200px">Action</th>
                        </tr>
                        </thead>
                        <tbody id="ajax-services">
                        </tbody>
                    </table>

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

<!-- Site Action -->
<div class="site-action">
    <button data-url="panel/membership_plan_add.php" data-toggle="slidePanel" id="slidepanel-show" style="display: none;"> </button>
    <button type="button" class="site-action-toggle btn-raised btn btn-success btn-floating">
        <i class="front-icon ion-android-add animation-scale-up" aria-hidden="true"></i>
        <i class="back-icon ion-android-close animation-scale-up" aria-hidden="true"></i>
    </button>
    <div class="site-action-buttons">

    </div>
</div>
<!-- End Site Action -->

<?php include("../footer.php"); ?>


<script>
    $(function()
    {
        // Init page helpers (Table Tools helper)
        App.initHelpers('table-tools');
    });
</script>
</body>

</html>