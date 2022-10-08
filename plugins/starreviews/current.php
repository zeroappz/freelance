<?php
/**
* Quickad Rating & Reviews - jQuery & Ajax php
* @author Bylancer
* @version 1.0
*/

include_once('setting.php');
// returns average rating 
function average_Rating($user_id,$user_type)
{
    global $config,$lang;

    if($user_type == 'employer'){
        $array = array('freelancer_id' => $user_id);
    }else{
        $array = array('employer_id' => $user_id);
    }

    $q_star1_result = ORM::for_table($config['db']['pre'].'reviews')
        ->where(array(
            'rating' => '1',
            'publish' => '1'
        ))
        ->where($array)
        ->count();

    $q_star2_result = ORM::for_table($config['db']['pre'].'reviews')
        ->where(array(
            'rating' => '2',
            'publish' => '1'
        ))
        ->where($array)
        ->count();

    $q_star3_result = ORM::for_table($config['db']['pre'].'reviews')
        ->where(array(
            'rating' => '3',
            'publish' => '1'
        ))
        ->where($array)
        ->count();

    $q_star4_result = ORM::for_table($config['db']['pre'].'reviews')
        ->where(array(
            'rating' => '4',
            'publish' => '1'
        ))
        ->where($array)
        ->count();

    $q_star5_result = ORM::for_table($config['db']['pre'].'reviews')
        ->where(array(
            'rating' => '5',
            'publish' => '1'
        ))
        ->where($array)
        ->count();
                            
    $total = $q_star1_result + $q_star2_result + $q_star3_result + $q_star4_result + $q_star5_result;
    
    if ($total != 0) {                      
        $rating = ($q_star1_result*1 + $q_star2_result*2 + $q_star3_result*3 + $q_star4_result*4 + $q_star5_result*5) / $total;
    } else {
        $rating = 0;
    }
    
    $rating = round($rating * 2) / 2;

    echo '<h3>'.__("Average rating").'</h3><p><small><strong>'.$rating.'</strong> '.__("average based on").' <strong>'.$total.'</strong> '.__("Reviews").'.</small></p><div class="rating-passive" data-rating="'.$rating.'"><span class="stars"></span></div>';
}

if(isset($_GET['productid'])){
    $user_id = $_GET['productid'];
    $userdata = get_user_data('',$user_id);
    $user_type = $userdata['user_type'];
}else{
    echo '<li>'.__("No reviews").'</li>';
    exit();
}
// show average rating
if (isset($_GET['show'])) {
    if ($_GET['show'] == "average") {
        average_Rating($user_id,$user_type);
    }
} else {

    if($user_type == 'employer'){
        $qReviews = ORM::for_table($config['db']['pre'].'reviews')
            ->where(array(
                'employer_id' => $user_id,
                'rated_by'=> 'user'
            ))
            ->order_by_desc('created_at')
            ->find_many();
    }else{
        $qReviews = ORM::for_table($config['db']['pre'].'reviews')
            ->where(array(
                'freelancer_id' => $user_id,
                'rated_by'=> 'employer'
            ))
            ->order_by_desc('created_at')
            ->find_many();
    }

    // show reviews
    $rReviews = count($qReviews);

    if ($rReviews == 0) {
        echo '<li>'.__("No reviews").'</li>';
    } else {
        foreach ($qReviews as $fReviews) {

            $pro = ORM::for_table($config['db']['pre'].'project')
                ->select('product_name')
                ->find_one($fReviews['project_id']);
            $project_id = $fReviews['project_id'];
            $project_title = $pro['product_name'];

            if($user_type == 'employer'){
                $user_id = $fReviews['freelancer_id'];
            }else{
                $user_id = $fReviews['employer_id'];
            }
            $info = ORM::for_table($config['db']['pre'].'user')
                ->select_many('name','username','image')
                ->find_one($user_id);

            $fullname = $info['name'];
            $username = $info['username'];
            $image = $info['image'];
            $star = '';
            for($i=1; $i<=5; $i++){

                if($i <= $fReviews['rating']){
                    $checked = "starchecked";
                }else{
                    $checked = "";
                }
                $star .= '<span class="fa fa-star font-18 '.$checked.'"></span>';
            }

            $created_at = date('d-M-Y', strtotime($fReviews['created_at']));

            echo '
            <li id="'.$fReviews['id'].'">
                <div class="boxed-list-item bid">
                    <div class="bids-avatar">
                        <div class="freelancer-avatar">
                            <div class="verified-badge"></div>
                            <a href="'.$link['PROFILE'].'/'.$username.'">
                                <img src="'.$config['site_url'].'storage/profile/small_'.$image.'" alt="'.$fullname.'">
                            </a>
                        </div>
                    </div>
                    <!-- Content -->
                    <div class="item-content">

                        <h4>
                            <a href="'.$link['PROJECT'].'/'.$project_id.'">'.$project_title.'</a> 
                            <span>'.$fullname.' 
                                <a href="'.$link['PROFILE'].'/'.$username.'">@'.$username.'</a>
                            </span>
                        </h4>
                        <div class="item-details margin-top-10">
                            <div class="star-rating" data-rating="'.number_format($fReviews['rating'],1).'"></div>
                            <div class="detail-item"><i class="icon-material-outline-date-range"></i> '.$created_at.'</div>
                        </div>
                        <div class="item-description">
                            <p>'.$fReviews['comments'].'</p>
                        </div>
                    </div>
                </div>
            </li>
        <!--end review-->';
        } 
    } 
}
?>