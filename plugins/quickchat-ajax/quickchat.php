<?php
/**
 * Quickchat - Fully Responsive PHP AJAX Chat Script
 * @author Bylancer
 * @version 1.0
 * @Date: 09/May/2020
 * @url https://bylancer.com
 * @Copyright (c) 2015-18 Devendra Katariya (bylancer.com)
 */

define("ROOTPATH", dirname(dirname(__DIR__)));
define("APPPATH", ROOTPATH."/php/");

require_once ROOTPATH . '/includes/autoload.php';
require_once ROOTPATH . '/includes/lang/lang_'.$config['lang'].'.php';
sec_session_start();

$con = db_connect($config);

if(isset($_SESSION['user']['id'])){
    $sesUsername    = $_SESSION['user']['username'];
    $sesId          = $_SESSION['user']['id'];
}
else{
    exit();
}

if ($_GET['action'] == "get_postdata") {get_postdata();}
if ($_GET['action'] == "updateSeenmsg") { updateSeenmsg();}
if ($_GET['action'] == "checkMsgSeen") {checkMsgSeen();}
if ($_GET['action'] == "lastseen") {lastseen();}
if ($_GET['action'] == "userProfile") {userProfile();}
if ($_GET['action'] == "chatfrindList") {chatfrindList();}
if ($_GET['action'] == "get_all_msg") { get_all_msg(); }
if ($_GET['action'] == "chatheartbeat") { chatHeartbeat(); }
if ($_GET['action'] == "sendchat") { sendChat(); }
if ($_GET['action'] == "closechat") { closeChat(); }
if ($_GET['action'] == "startchatsession") { startChatSession(); }


function get_userdata($id){
    global $con,$config;
    $query1 = "SELECT * FROM `".$config['db']['pre']."user` WHERE id='" .mysqli_real_escape_string($con,$id). "' LIMIT 1";
    $query_result = mysqli_query ($con, $query1);
    $info = mysqli_fetch_array($query_result);

    return $info;
}

function update_chat_lastactive($con,$config){

    $q = "UPDATE `".$config['db']['pre']."user` SET online='1', lastactive = '".$GLOBALS['timenow']."' WHERE id = '".$GLOBALS['sesId']."' ";
    mysqli_query($con, $q);
}

function getlastActiveTime($userid){
    global $con,$config;

     $res = mysqli_query($con, "SELECT * FROM `".$config['db']['pre']."user` WHERE id = '$userid' AND TIMESTAMPDIFF(MINUTE, lastactive, NOW()) > 1");
     if($res === FALSE) {
         die(mysqli_error($con)); // TODO: better error handling
     }
     $num = mysqli_num_rows($res);
     if($num == "0")
         $onofst = "online";
     else
         $onofst = "offline";

    return $onofst;

}

if (!isset($_SESSION['chatHistory'])) {
    $_SESSION['chatHistory'] = array();
}

if (!isset($_SESSION['openChatBoxes'])) {
    $_SESSION['openChatBoxes'] = array();
}

if (!isset($_SESSION['chatpage'])) {
    $_SESSION['chatpage'] = 1;
}

function updateSeenmsg()
{
    global $con, $config;
    $userid = $_POST['userid'];
    $postid = $_POST['postid'];
    $query = "Update `".$config['db']['pre']."messages` set seen='1' where to_id = '".$GLOBALS['sesId']."' AND from_id = '$userid' AND  post_id = '$postid'";
    $con->query($query);
    echo '1';
    die();
}

function checkMsgSeen()
{
    global $con, $config;
    if($_GET['msgid'] == "last"){
        $query1 = "SELECT seen FROM `".$config['db']['pre']."messages` where to_uname = '".$_GET['uname']."' and from_uname = '".$GLOBALS['sesUsername']."' ORDER BY message_id DESC LIMIT 1";
    }
    else{
        $query1 = "SELECT seen FROM `".$config['db']['pre']."messages` where message_id = '".$_GET['msgid']."' LIMIT 1";
    }

    $result1 = $con->query($query1);
    $row1 = mysqli_fetch_assoc($result1);

    if(isset($row1['seen']))
        echo $seen = $row1['seen'];
    else
        echo $seen = "null";
    die();
}

function lastseen() {
    global $con,$config;
    echo $lastseen =  getlastActiveTime($_GET['userid']);
}

function get_postdata() {
    global $con,$config,$link;
    $postid = $_GET['postid'];
    $sql = "SELECT product_name from `".$config['db']['pre']."project` WHERE id = '".$postid."' LIMIT 1";
    $query = $con->query($sql);
    $info = mysqli_fetch_array($query);
    $post_title = $info['product_name'];
    $post_link = $link['PROJECT-DETAIL'].'/'.$postid;
    $item = array();
    $item['post_title'] = $post_title;
    $item['post_link'] = $post_link;

    echo json_encode($item);
    die();
}

function userProfile()
{
    global $con,$config;

    $sql = "SELECT username,name,email,sex,description,image from `".$config['db']['pre']."user` WHERE id = '".mysqli_real_escape_string($con,$_GET['userid'])."' LIMIT 1";
    $query = $con->query($sql);
    $info = mysqli_fetch_array($query);
    $item = array();
    $item['username']   = $info['username'];
    $item['name']       = ($info['name'] != '')? $info['name'] : $info['username'];
    $item['email']      = $info['email'];
    $item['sex']     = $info['sex'];
    $item['about']        = $info['description'];
    $item['image']    = ($info['image'] != "")? $info['image'] : "default_user.png";

    send_json($item);
}

function chatfrindList() {
    global $con,$config;

    $limitStart = $_POST['limitStart'];
    $searchKey = isset($_POST['searchKey'])? $_POST['searchKey'] : '';
    if($searchKey != ''){
        $where = "( u.username like '%$searchKey%' ) AND";
    }else{
        $where = '';
    }

    $limitCount = 20; // Set how much data you have to fetch on each request
    if(isset($limitStart ) || !empty($limitStart)) {
//This query shows user contact list by conversation
        $sql = "select id,username,name,image, message_date, post_id from `".$config['db']['pre']."user` as u
            INNER JOIN
            (
                select max(message_id) as message_id,to_id,from_id,message_date,post_id from `".$config['db']['pre']."messages` where to_id = '".$_SESSION['user']['id']."' or from_id = '".$_SESSION['user']['id']."' GROUP BY post_id
            )
            m ON u.id = m.from_id or u.id = m.to_id  where $where (u.id != '".$_SESSION['user']['id']."') GROUP BY post_id
            ORDER BY message_id DESC ";
        $limit = "limit $limitStart, $limitCount";
        $query = $sql." ".$limit;

        $rowcount = mysqli_num_rows(mysqli_query($con, $sql));

        $result = $con->query($query);
        $results = array();
        $results['contact_count'] = $rowcount;
        while ($row = mysqli_fetch_array($result)) {
            $id = $row['id'];
            $username = $row['username'];
            $fullname = ($row['name'] != '')? $row['name'] : $username;
            $picname = $row['image'];
            $postid = $row['post_id'];
            $chatid = $id."_".$postid;
            if($picname == "")
                $picname = "default_user.png";

            $sql = "SELECT product_name from `".$config['db']['pre']."project` WHERE id = '".$postid."' LIMIT 1";
            $query = $con->query($sql);
            $info = mysqli_fetch_array($query);
            $post_title = $info['product_name'];

            $sql = "SELECT 1 FROM `".$config['db']['pre']."messages` where to_id = '".$_SESSION['user']['id']."' AND from_id = '$id' AND post_id = '".$postid."' and recd = '0'";
            $countrecd = mysqli_num_rows(mysqli_query($con,$sql));

            $onofst =  getlastActiveTime($id);

            $results['data'][] = array(
                "chatid"=> $chatid,
                "postid"=> $postid,
                "userid"=> $id,
                "username"=> $username,
                "fullname"=> $fullname,
                "userimage"=> $picname,
                "userstatus"=> $onofst,
                "post_title"=> $post_title,
                "unread_msg"=> $countrecd
            );

        }
        echo json_encode($results);
    }

    die();
}

function get_all_msg() {
    global $con,$config;
    $perPage = 25;

    $sql = "select * from `".$config['db']['pre']."messages` where  
    ((to_id = '".mysqli_real_escape_string($con, $GLOBALS['sesId'])."' AND from_id = '".mysqli_real_escape_string($con,$_GET['client'])."' AND recd = '1') 
    OR 
    (to_id = '".mysqli_real_escape_string($con,$_GET['client'])."' AND from_id = '".mysqli_real_escape_string($con,$GLOBALS['sesId'])."')) AND post_id = '".mysqli_real_escape_string($con,$_GET['postid'])."' order by message_id DESC ";

    $page = 1;
    if(!empty($_GET["page"])) {
        $_SESSION['chatpage'] = $page = $_GET["page"];
    }

    $start = ($page-1)*$perPage;
    if($start < 0) $start = 0;

    $query =  $sql . " limit " . $start . "," . $perPage;

    $query = $con->query($query);

    if(empty($_GET["rowcount"])) {
        $_GET["rowcount"] = $rowcount = mysqli_num_rows(mysqli_query($con, $sql));
    }

    $pages  = ceil($_GET["rowcount"]/$perPage);

    $items = array();

    while ($chat = mysqli_fetch_array($query)) {

        $from_userdata = get_userdata($chat['from_id']);
        $to_id = $from_userdata['id'];
        $picname = $from_userdata['image'];
        $status = $from_userdata['online'];

        $picname = ($picname == "")? "default_user.png" : $picname;
        $status  = ($status == "0")? "Offline" : "Online";

        $to_userdata = get_userdata($chat['to_id']);
        $picname2 = $to_userdata['image'];

        $picname2 = ($picname2 == "")? "default_user.png" : $picname2;


        $chat['message_content'] = escape($chat['message_content'],false);

        if($chat['from_id'] == $GLOBALS['sesId'])
        {
            $position = 'odd';
            $chatid = $chat['to_id'].'_'.$chat['post_id'];
        }
        else{
            $position = 'even';
            $chatid = $chat['from_id'].'_'.$chat['post_id'];
        }

        if (strpos($chat['message_content'], 'file_name') !== false) {

        }
        else{
            // The Regular Expression filter
            $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,10}(\/\S*)?/";

            // Check if there is a url in the text
            if (preg_match($reg_exUrl, $chat['message_content'], $url)) {

                // make the urls hyper links
                $chat['message_content'] = preg_replace($reg_exUrl, "<a href='{$url[0]}'>{$url[0]}</a>", $chat['message_content']);

            } else {
                // The Regular Expression filter
                $reg_exUrl = "/(www)\.[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,10}(\/\S*)?/";

                // Check if there is a url in the text
                if (preg_match($reg_exUrl, $chat['message_content'], $url)) {

                    // make the urls hyper links
                    $chat['message_content'] = preg_replace($reg_exUrl, "<a href='{$url[0]}'>{$url[0]}</a>", $chat['message_content']);

                }
            }
        }
        $msgtime = date('d M, H:i A', strtotime($chat['message_date']));
        $msgdate = date('F d, Y', strtotime($chat['message_date']));

        $items[] =  array(
            "s"=> '0',
            "chatid"=> $chatid,
            "page"=> $page,
            "pages"=> $pages,
            "mtype"=> $chat['message_type'],
            "message"=> $chat['message_content'],
            "seen"=> $chat['seen'],
            "recd"=> $chat['recd'],
            "time"=> $msgtime,
            "date"=> $msgdate,
            "position"=> $position
        );

    }// End While Loop

    send_json($items);
}

function chatHeartbeat() {
    global $con,$config;
    $sql = "select * from `".$config['db']['pre']."messages` where (to_id = '".mysqli_real_escape_string($con,$GLOBALS['sesId'])."' AND recd = 0) order by message_id ASC";
    $query = $con->query($sql);
    $items = array();
    while ($chat = mysqli_fetch_array($query)) {
        $from_id = $chat['from_id'];
        $from_userdata = get_userdata($from_id);
        $from_name = ($from_userdata['name'] != '')? $from_userdata['name'] : $from_userdata['username'];
        $picname = $from_userdata['image'];
        $picname = ($picname == "")? "default_user.png" : $picname;
        $status = $from_userdata['online'];
        $status  = ($status == "0")? "offline" : "online";
        $postid = $chat['post_id'];
        $chatid = $from_id."_".$postid;

        $chat['message_content'] = escape($chat['message_content'],false);

        if (strpos($chat['message_content'], 'file_name') !== false) {

        }
        else{
            // The Regular Expression filter
            $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,10}(\/\S*)?/";

            // Check if there is a url in the text
            if (preg_match($reg_exUrl, $chat['message_content'], $url)) {

                // make the urls hyper links
                $chat['message_content'] = preg_replace($reg_exUrl, "<a href='{$url[0]}'>{$url[0]}</a>", $chat['message_content']);

            } else {
                // The Regular Expression filter
                $reg_exUrl = "/(www)\.[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,10}(\/\S*)?/";

                // Check if there is a url in the text
                if (preg_match($reg_exUrl, $chat['message_content'], $url)) {

                    // make the urls hyper links
                    $chat['message_content'] = preg_replace($reg_exUrl, "<a href='{$url[0]}'>{$url[0]}</a>", $chat['message_content']);

                }
            }
        }

        $msgtime = date('d M, H:i A',strtotime($chat['message_date']));
        $items[] = array(
            "s"=> 0,
            "postid"=> $postid,
            "chatid"=> $chatid,
            "from_name"=> $from_name,
            "from_id"=> $from_id,
            "picname"=> $picname,
            "status"=> $status,
            "message"=> $chat['message_content'],
            "message_type"=> $chat['message_type'],
            "time"=> $msgtime
        );
        if(!isset($_POST['wchat'])) {
            if (isset($_SESSION['chatHistory'][$chatid])) {
                $_SESSION['chatHistory'][$chatid][] = array(
                    "s" => "1",
                    "chatid" => $chatid,
                    "postid" => $postid,
                    "fullname" => $from_name,
                    "userid" => $from_id,
                    "picname" => $picname,
                    "status" => $status
                );

            } else {

                $_SESSION['chatHistory'][$chatid] = array(
                    "s" => "1",
                    "chatid" => $chatid,
                    "postid" => $postid,
                    "fullname" => $from_name,
                    "userid" => $from_id,
                    "picname" => $picname,
                    "status" => $status
                );

            }

            unset($_SESSION['tsChatBoxes'][$chatid]);
            $_SESSION['openChatBoxes'][$chatid] = $chat['message_date'];
        }
    }

    if (!empty($_SESSION['openChatBoxes']) && !isset($_POST['wchat']))
    {
        foreach ($_SESSION['openChatBoxes'] as $chatbox => $time) {

            if (!isset($_SESSION['tsChatBoxes'][$chatbox]))
            {
                $now = time()-strtotime($time);
                $timenow = date('M d, g:i A', strtotime($time));

                $message = $timenow;
                if ($now > 3000)
                {
                    $items[] = array(
                        "s"=> 2,
                        "chatid"=> $chatbox,
                        "message"=> $message
                    );

                    if (!isset($_SESSION['chatHistory'][$chatbox])) {
                        $_SESSION['chatHistory'][$chatbox] = array();
                    }

                    $_SESSION['chatHistory'][$chatbox][] = array(
                        "s"=> 2,
                        "chatid"=> $chatbox,
                        "message"=> $message
                    );

                    $_SESSION['tsChatBoxes'][$chatbox] = 1;
                }
            }
        }
    }

    $sql = "update `".$config['db']['pre']."messages` set recd = 1 where to_id = '".mysqli_real_escape_string($con,$GLOBALS['sesId'])."' and recd = 0";
    $con->query($sql);

    send_json($items);
}

function sendChat() {
    global $con,$config;
    if(isset($GLOBALS['sesId'])){
        $from_id = $GLOBALS['sesId'];
        $to_id = $_POST['to'];
        $postid = $_POST['postid'];

        $message = sanitize_string($_POST['message']);
        $timenow = date('Y-m-d H:i:s');
        $to_userdata = get_userdata($to_id);
        if(count($to_userdata) > 0){
            $to_name = ($to_userdata['name'] != '')? $to_userdata['name'] : $to_userdata['username'];
            $picname = $to_userdata['image'];
            $status = $to_userdata['online'];
            $picname = ($picname == "")? "default_user.png" : $picname;
            $status  = ($status == "0")? "offline" : "online";
            $chatid = $to_id.'_'.$postid;

            if(!isset($_POST['wchat'])) {
                if (isset($_SESSION['chatHistory'][$chatid])) {
                    $_SESSION['chatHistory'][$chatid][] = array(
                        "s" => "1",
                        "chatid" => $chatid,
                        "postid" => $postid,
                        "fullname" => $to_name,
                        "userid" => $to_id,
                        "picname" => $picname,
                        "status" => $status
                    );

                } else {

                    $_SESSION['chatHistory'][$chatid] = array(
                        "s" => "1",
                        "chatid" => $chatid,
                        "postid" => $postid,
                        "fullname" => $to_name,
                        "userid" => $to_id,
                        "picname" => $picname,
                        "status" => $status
                    );
                }

                unset($_SESSION['tsChatBoxes'][$chatid]);

                $_SESSION['openChatBoxes'][$chatid] = date('Y-m-d H:i:s', time());

                if (!isset($_SESSION['chatHistory'][$chatid])) {
                    $_SESSION['chatHistory'][$chatid] = array();
                }
            }
            $sql = "insert into `".$config['db']['pre']."messages` (from_id,to_id,message_content,message_type,message_date,post_id) values ('".mysqli_real_escape_string($con,$from_id)."','".mysqli_real_escape_string($con,$to_id)."','".mysqli_real_escape_string($con,$message)."','text','".$timenow."','".mysqli_real_escape_string($con,$postid)."')";

            $con->query($sql);

            echo "1";
        }
        else{
            echo "0";
        }
        exit(0);

    }
    else{
        echo "0";
    }
    exit(0);
}

function startChatSession() {
    $items = array();
    if (!empty($_SESSION['openChatBoxes'])) {
        foreach ($_SESSION['openChatBoxes'] as $chatbox => $void) {
            if (isset($_SESSION['chatHistory'][$chatbox])) {
                $items[] = $_SESSION['chatHistory'][$chatbox];
            }
        }
    }

    send_json($items);
}

function closeChat() {
    unset($_SESSION['openChatBoxes'][$_POST['chatbox']]);
    echo "1";
    exit(0);
}

function send_json($results = array()){
    echo json_encode($results);
    die();
}
?>