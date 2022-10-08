<?php
require_once('../includes.php');
$info = ORM::for_table($config['db']['pre'] . 'project')
    ->select('product_name')
    ->where('id', $_GET['id'])
    ->find_one();

$count = ORM::for_table($config['db']['pre'] . 'milestone')
    ->where('project_id', $_GET['id'])
    ->count();
?>
<!-- Page JS Plugins CSS -->
<main class="app-layout-content">

    <!-- Page Content -->
    <div class="container-fluid p-y-md">
        <!-- Partial Table -->
        <div class="card">
            <div class="card-header">
                <h4>Milestone <br>
                    <small>For <a href="<?php echo $link['PROJECT'].'/'.$_GET['id'] ?>" target="_blank"><?php echo $info['product_name']; ?></a></small>
                </h4>

                <div class="pull-right hidden">
                    <a href="setting.php#project_setting" class="btn btn-success waves-effect waves-light m-r-10">Project setting</a>
                </div>
            </div>
            <div class="card-block">
                <div id="js-table-list">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th>Description</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Created</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if($count == 0){ ?>
                            <tr>
                                <td colspan="4" class="text-center">No Milestone created for this project</td>
                            </tr>
                        <?php } ?>

                        <?php
                        $result = ORM::for_table($config['db']['pre'] . 'milestone')
                            ->where('project_id', $_GET['id'])
                            ->find_many();
                        foreach($result as $info){
                            $created = date('d-M-Y', strtotime($info['start_date']));
                            if($info['request'] == 0) {
                                $status = '<span class="badge btn-success fs-12">'.__("Funded in milestone").'</span>';
                            }
                            elseif($info['request'] == 1) {
                                $status = '<span class="badge btn-warning fs-12">'.__("Request for release").'</span>';
                            }
                            elseif($info['request'] == 2) {
                                $status = '<span class="badge btn-info fs-12">'.__("Released").'</span>';
                            }
                            elseif($info['request'] == 3) {
                                $status = '<span class="badge btn-danger fs-12">'.__("Request for release").'</span>';
                            }
                            ?>
                            <tr>
                                <td> <?php echo $info['title']; ?></td>
                                <td class="text-center"><?php echo $info['amount']; ?></td>
                                <td class="text-center"><?php echo $status; ?></td>
                                <td class="text-center"><?php echo $created; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
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


<?php include("../footer.php"); ?>

</body>

</html>

