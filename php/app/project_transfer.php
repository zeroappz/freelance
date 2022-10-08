<?php
if(checkloggedin()) {

    //Print Template
    HtmlTemplate::display('project_transfer', array(
        'pages' => $pagging
    ));
    exit;
}
else{
    error(__("Page Not Found"), __LINE__, __FILE__, 1);
    exit();
}
?>
