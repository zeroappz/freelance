<?php
ini_set('memory_limit', '512M');
ini_set('post_max_size', '512M');
ini_set('upload_max_filesize', '512M');
require_once('../includes.php');

?>

<!-- The mini-upload-form main CSS file -->
<link href="../assets/js/plugins/mini-upload-form/assets/css/style.css" rel="stylesheet" />

<main class="app-layout-content">

    <!-- Page Content -->
    <div class="container-fluid p-y-md">
        <!-- Partial Table -->
        <div class="card">
            <div class="card-header">
                <h4>Update Quicklancer CMS</h4>
            </div>
            <div class="card-block">

                <?php
                echo "Zip Module: ", extension_loaded('zip') ? '<label class="label label-info">OK</label>' : '<label class="label label-danger">Missing</label> Install php zip module for using update feature',"<br><br>";
                ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('max_execution_time',3600);
$server_file_path = "https://bylancer.com/api/quickad-release/";
$update_dir = ADMINPATH."/uploads/";
$installable_dir = "../";
$notallowed =  array('gif','png','jpg','jpeg','ttf','woff','woff2','eot','svg');

//Check For An Update
$getVersions = file_get_contents('https://bylancer.com/api/quicklancer-release-versions.php') or die ('ERROR');
if ($getVersions != '')
{
    echo '<p>CURRENT VERSION: <span id="version">'.$config['version'].'</span></p>';
    $versionList = explode("\n", $getVersions);
    foreach ($versionList as $aV)
    {
        if ( $aV > $config['version']) {
            if (!isset($_GET['doUpdate']))
            echo '<p>New Update Found: <label class="label label-success">v'.$aV.'</label></p>';
            $found = true;

            if ( !is_file(  $update_dir.'QUICKLANCER-CMS-'.$aV.'.zip' )) {

                if ( !is_dir( $update_dir ) ) mkdir ( $update_dir );
                ?>
                <form id="upload" method="post" action="upload.php" enctype="multipart/form-data">
                    <div id="drop">
                        Drop Here <br>QUICKLANCER-CMS-<?php echo $aV ?>.zip
                        <a>Browse</a>
                        <input type="file" name="upl" multiple />
                    </div>
                    <ul>
                        <!-- The file uploads will be shown here -->
                    </ul>

                </form>
                <?php
                break;
            }else{
                if (!isset($_GET['doUpdate']))
                echo '<p>Update already downloaded.</p>';
            }

            if (isset($_GET['doUpdate']) && $_GET['doUpdate'] == true) {
                //Open The File And Do Stuff
                echo "<pre class='upgrade-pre'>";
                $zipHandle = zip_open($update_dir.'QUICKLANCER-CMS-'.$aV.'.zip');
                echo '<div id="updating"><span>Updating Please wait...</span> <span class="loader"></span></div>';
                echo '<div id="update-completed"></div>';
                echo '<ul class="update_content">';
                while ($aF = zip_read($zipHandle) )
                {
                    $thisFileName = zip_entry_name($aF);
                    $thisFileDir = dirname($thisFileName);

                    $filename = explode('/',$thisFileName);
                    $filename = end($filename);
                    $extention = getExtension($filename);


                    if($thisFileDir != ""){
                        $basedir = explode('/',$thisFileDir);
                        if($basedir[0] == "storage") continue;
                        if($basedir[0] == "install" && $filename != 'upgrade.php') continue;
                        if($basedir[0] == "admin" && $filename == '.htaccess') continue;
                        if($filename == '.htaccess') continue;
                    }else{
                        if($filename == '.htaccess') continue;
                    }

                    //Continue if its not a file
                    if ( substr($thisFileName,-1,1) == '/') continue;

                    /*if (file_exists($installable_dir.$thisFileName)) {
                        //Continue if its image or font file Only if file exist with not-allow array
                        if(in_array($extention,$notallowed) )
                            continue;
                    }*/

                    //Make the directory if we need to...
                    if ( !is_dir ( $installable_dir.$thisFileDir ) )
                    {
                        mkdir ( $installable_dir.$thisFileDir, 0755, true );
                        echo '<li>Created Directory '.$thisFileDir.'</li>';
                    }

                    //Overwrite the file
                    if ( !is_dir($installable_dir.$thisFileName) ) {
                        echo '<li>'.$thisFileName.'...........';
                        $contents = zip_entry_read($aF, zip_entry_filesize($aF));

                        if(!in_array($extention,$notallowed) )
                            $contents = str_replace("\r\n", "\n", $contents);

                        $updateThis = '';

                        //If we need to run commands, then do it.
                        if ( $filename == 'upgrade.php' )
                        {
                            $upgradeExec = fopen ('upgrade.php','w');
                            fwrite($upgradeExec, $contents);
                            fclose($upgradeExec);
                            include ('upgrade.php');
                            unlink('upgrade.php');
                            echo' <span class="label label-warning">EXECUTED</span></li>';
                        }
                        else if ( $filename == 'config.php' )
                        {
                            echo' Leave this file as it is</li>';
                        }
                        else if(strpos($filename, 'lang_') !== false) // update language files
                        {
                            // create temp lang file
                            $tmp_file = 'temp_'.$filename;
                            file_put_contents($tmp_file,$contents);

                            $new_lang = getLanguageFileVariable(ROOTPATH.'/admin/'.$tmp_file);
                            $old_lang = getLanguageFileVariable($installable_dir.$thisFileName);
                            $lang_var = array_merge($old_lang,$new_lang);

                            ksort($lang_var);
                            // Build the new file as a string
                            $newFileStr = "<?php\n";
                            foreach ($lang_var as $name => $val) {
                                $newFileStr .= "\$lang['$name'] = " . var_export($val, true) . ";\n";
                            }
                            file_put_contents($installable_dir.$thisFileName, $newFileStr);

                            unlink($tmp_file);
                            echo' <span class="label label-success">UPDATED</span></li>';
                        }
                        else
                        {
                            file_put_contents($installable_dir.$thisFileName,$contents);
                            unset($contents);
                            echo' <span class="label label-success">UPDATED</span></li>';
                        }
                    }
                }

                if(isset($config['purchase_key']) && $config['purchase_key'] != ""){
                    // Set API Key
                    $code = $config['purchase_key'];
                    $buyer_email = "";
                    $installing_version = $config['version'];

                    $url = "https://bylancer.com/api/api.php?verify-purchase=" . $code . "&version=" . $installing_version . "&site_url=". $config['site_url']."&email=" . $buyer_email;
                    // Open cURL channel
                    $ch = curl_init();

                    // Set cURL options
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                    //Set the user agent
                    $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
                    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
                    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
                    // Decode returned JSON
                    $output = json_decode(curl_exec($ch), true);
                    // Close Channel
                    curl_close($ch);

                    if ($output['success']) {
                        if(isset($config['quickad_secret_file']) && $config['quickad_secret_file'] != ""){
                            $fileName = $config['quickad_secret_file'];
                        }else{
                            $fileName = get_random_string();
                        }
                        file_put_contents( $fileName . '.php', $output['data']);
                        $success = true;
                        update_option("quickad_secret_file",$fileName);
                        update_option("purchase_key",$config['purchase_key']);
                        $status = "success";
                        echo $message = '<br> Purchase code verified successfully. <br> ';
                    } else {
                        $status = "error";
                        echo $message = $output['error']."<br>";
                        echo '<div class="app-layout-content">
                                <div class="alert alert-warning" style="margin: 10px 10px 0 10px">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <strong>Important!</strong> Please Re-verify purchase code to update admin.
                                    <a class="text-info" style="cursor: pointer" href="setting.php#quickad_purchase_code"><strong>Click here</strong></a>
                                </div>
                            </div>';
                    }
                }

                echo '</ul>';
                echo "</pre>";
                $updated = TRUE;
                $installing_version = $aV;

                echo '<script>document.getElementById("updating").style.visibility = "hidden"; </script>';
                echo '<script>document.getElementById("update-completed").innerHTML = "Completed 100%"; </script>';
                echo '<script>document.getElementById("version").innerHTML = "'.$installing_version.'"; </script>';

                // Content that will be written to the config file
                $content = "<?php\n";
                $content .= "\$config['db']['host'] = '" . $config['db']['host'] . "';\n";
                $content .= "\$config['db']['name'] = '" . $config['db']['name'] . "';\n";
                $content .= "\$config['db']['user'] = '" . $config['db']['user'] . "';\n";
                $content .= "\$config['db']['pass'] = '" . $config['db']['pass'] . "';\n";
                $content .= "\$config['db']['pre'] = '" . $config['db']['pre'] . "';\n";
                $content .= "\n";
                $content .= "\$config['version'] = '" . $installing_version . "';\n";
                $content .= "\$config['installed'] = '1';\n";
                $content .= "?>";

                // Open the config.php for writting
                $handle = fopen('../includes/config.php', 'w');
                // Write the config file
                fwrite($handle, $content);
                // Close the file
                fclose($handle);
                unlink($update_dir.'QUICKLANCER-CMS-'.$aV.'.zip');
                //unlink($update_dir);


            }
            else{
                echo '<p>Update ready. &raquo; <a href="?doUpdate=true" class="btn btn-success">Install Now?</a></p>';
                break;
            }
        }else{
            $found = false;
        }
    }

    if (isset($updated) && $updated == true) {
        echo '<p class="success">&raquo; CMS Updated to v'.$aV.'</p>';
        echo '<script>setTimeout(function () {
                   window.location = "'.$config['site_url'].'admin";
                }, 2000);</script>';
    }
    else if (isset($found) && $found == false) echo '<p>&raquo; No update is available.</p>';


}
else echo '<p>Could not find latest realeases.</p>';
?>

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

<!-- JavaScript Includes -->

<script src="../assets/js/plugins/mini-upload-form/assets/js/jquery.knob.js"></script>

<!-- jQuery File Upload Dependencies -->
<script src="../assets/js/plugins/mini-upload-form/assets/js/jquery.ui.widget.js"></script>
<script src="../assets/js/plugins/mini-upload-form/assets/js/jquery.iframe-transport.js"></script>
<script src="../assets/js/plugins/mini-upload-form/assets/js/jquery.fileupload.js"></script>

<!-- Our mini-upload-form main JS file -->
<script src="../assets/js/plugins/mini-upload-form/assets/js/script.js"></script>