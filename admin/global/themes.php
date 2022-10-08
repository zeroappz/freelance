<?php
require_once('../includes.php');
$template_path = ROOTPATH.'/templates/';
if(isset($_POST['tpl_name']))
{
    if(!check_allow()){
        ?>
        <script>
            $(document).ready(function(){
                $('#sa-title').trigger('click');
            });
        </script>
    <?php

    }
    else {
        update_option("tpl_name",$_POST['tpl_name']);

        transfer('themes.php', 'Theme Changed');
        exit;
    }
}


?>

<script language="JavaScript">
    <?php
        echo "\n";
        echo '  var img=new Array();';
        echo "\n";
        if ($handle = opendir($template_path))
        {
           while (false !== ($file = readdir($handle)))
           {
               if ($file != "." && $file != "..")
               {
                    echo 'img["' . $file . '"]="' .$template_path. $file . '/screenshot.png";';
                    echo "\n";
               }
           }
           closedir($handle);
        }
    ?>

    function swap(type){
        document.getElementById("imgMain").src=img[type];
        var sel=document.shoeFrm.shoeSel;
        for(i=0;i<sel.length;i++){if(sel.options[i].text==type)
        {
            sel.selectedIndex=i;}}
    }
</script>

<main class="app-layout-content">

    <!-- Page Content -->
    <div class="container-fluid p-y-md">
        <!-- Partial Table -->
        <div class="card">
            <div class="card-header">
                <h4>Themes</h4>
                <div class="pull-right">
                    <a href="setting.php#quickad_theme_setting" class="btn btn-success waves-effect waves-light m-r-10">Theme setting</a>
                </div>
            </div>
            <div class="card-block">
        <!-- /row -->
        <div class="row">
            <?php
            if ($handle = opendir($template_path))
            {
                while (false !== ($folder = readdir($handle)))
                {
                    if ($folder != "." && $folder != "..")
                    {
                        $filepath = $template_path . $folder . "/theme-info.txt";
                        if(file_exists($filepath)){
                            $themefile = fopen($filepath,"r");

                            $themeinfo = array();
                            while(! feof($themefile)) {
                                $lineRead = fgets($themefile);
                                if (strpos($lineRead, ':') !== false) {
                                    $line = explode(':',$lineRead);
                                    $key = trim($line[0]);
                                    $value = trim($line[1]);
                                    $themeinfo[$key] = $value;
                                }
                            }
                            ?>
                            <div class="col-sm-6 col-md-4 col-lg-4 pad-10">
                                <div class="white-box pro-box p-0">
                                    <div class="pro-list-img">
                                        <img src="<?php echo $config['site_url'] ?>templates/<?php echo $folder ?>/screenshot.png" width="100%"/>
                                    </div>
                                    <div class="pro-content-3-col">
                                        <div class="pro-list-details">
                                            <h4>
                                                <a class="text-dark" href="#"><?php echo $themeinfo['Theme Name'] ?></a>
                                            </h4>
                                            <h4 class="text-danger"><small>Author</small> <?php echo $themeinfo['Author'] ?></h4> Price: <?php echo $themeinfo['Price'] ?>
                                        </div>
                                    </div>

                                    <hr class="m-0">
                                    <div class="pro-agent-col-3">
                                        <div class="agent-name">
                                            <form action="themes.php" method="post" name="f1" id="f1">
                                                <input type="hidden" value="<?php echo $folder ?>" name="tpl_name">
                                                <?php
                                                if($folder == $config['tpl_name'])
                                                {
                                                    echo '<button class="btn btn-default btn-rounded waves-effect waves-light btn-sm" type="button"><span class="btn-label"><i class="ti-check"></i></span>Current Theme</button>';
                                                }
                                                else{
                                                    echo '<button class="btn btn-success btn-rounded waves-effect waves-light btn-sm" type="submit"><span class="btn-label"><i class="ti-check"></i></span>Activate Me</button>';
                                                }
                                                ?>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <?php
                            fclose($themefile);
                        }
                    }
                }
                closedir($handle);
            }

            ?>

            <?php
            if ($handle = opendir($template_path))
            {
                while (false !== ($file = readdir($handle)))
                {
                    if ($file != '.' && $file != '..')
                    {
                        ?>

                    <?php
                    }
                }
                closedir($handle);
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

<?php include("../footer.php"); ?>
</body>

</html>