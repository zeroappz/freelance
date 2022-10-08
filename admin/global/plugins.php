<?php
require_once('../includes.php');
$server_file_path = "https://bylancer.com/api/quickjob-advertise.json";
$ads = json_decode(file_get_contents($server_file_path),true);
?>
<style>
    .pro-list-img{
        min-height: 150px;
        background: url("../assets/images/quickad_loading.gif") no-repeat center;
    }
</style>
<main class="app-layout-content">

    <!-- Page Content -->
    <div class="container-fluid p-y-md">
        <!-- Partial Table -->
        <div class="card">
            <div class="card-header">
                <h4>Plugins</h4>
            </div>
            <div class="card-block">
                <!-- /row -->
                <div class="row">
                    <?php
                    foreach($ads as $info){
                        //print_r($info);
                        ?>
                        <div class="col-sm-6 col-md-4 col-lg-4 pad-10">
                            <div class="white-box pro-box p-0">
                                <div class="pro-list-img">
                                    <img src="<?php echo $info['image_url'] ?>" width="100%"/>
                                </div>
                                <div class="pro-content-3-col">
                                    <div class="pro-list-details">
                                        <h4>
                                            <a class="text-dark" href="<?php echo $info['item_url'] ?>"><?php echo $info['title'];?> </a>
                                        </h4>
                                        <h4 class="text-danger">Price: <?php echo $info['price'] ?></h4>
                                    </div>
                                </div>

                                <hr class="m-0">
                                <div class="pro-agent-col-3">
                                    <div class="agent-name">
                                        <?php
                                        if(is_dir($info['item_path']))
                                        {
                                            echo '<a href="#" class="btn btn-default btn-rounded waves-effect waves-light btn-sm"><span class="btn-label"><i class="ti-check"></i></span>Installed</a>';
                                        }else{
                                            if( $info['price'] != 'Free')
                                            {
                                                echo '<a href="'.$info['item_url'].'" class="btn btn-info btn-rounded waves-effect waves-light btn-sm"><span class="btn-label"><i class="ti-check"></i></span>Buy now</a>';
                                            }
                                            else{
                                                echo '<a href="'.$info['item_url'].'" class="btn btn-success btn-rounded waves-effect waves-light btn-sm"><span class="btn-label"><i class="ti-check"></i></span>Free download</a>';
                                            }
                                        }

                                        ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>


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


<?php include('../footer.php'); ?>
</body>

</html>