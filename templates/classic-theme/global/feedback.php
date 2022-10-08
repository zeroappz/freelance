<?php overall_header(__("Feedback")); ?>
<div id="titlebar">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?php _e("Feedback") ?></h2>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("Feedback") ?></li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</div>
<div class="container margin-bottom-50">
    <div class="row">
        <div class="col-xl-8 margin-0-auto">
            <h2 class="margin-bottom-20"><?php _e("Tell us what you think of us") ?></h2>
            <span><?php _e("We would like to hear your opinions about the website. We would be grateful if you could take the time to fill out this form") ?></span>
            <div class="feed-back-form margin-top-20">
                <form method="post">
                    <div class="submit-field">
                        <h5><?php _e("Full Name") ?></h5>
                        <input type="text" class="with-border" name="name" required="">
                    </div>
                    <div class="submit-field">
                        <h5><?php _e("Email Address") ?></h5>
                        <input type="text" class="with-border" name="email" required="">
                    </div>
                    <div class="submit-field">
                        <h5><?php _e("Phone Number") ?></h5>
                        <input type="text" class="with-border" name="phone" required="">
                    </div>
                    <div class="submit-field">
                        <h5><?php _e("Subject") ?></h5>
                        <input type="text" class="with-border" name="subject" required="">
                    </div>
                    <div class="submit-field">
                        <h5><?php _e("Is there anything you would like to tell us?") ?></h5>
                        <textarea type="text" class="with-border" name="message" placeholder="<?php _e("Message") ?>..." required=""></textarea>
                    </div>
                    <div class="submit-field">
                        <?php
                        if($config['recaptcha_mode'] == '1'){
                            echo '<div class="g-recaptcha" data-sitekey="'._esc($config['recaptcha_public_key'],false).'"></div>';
                        }
                        if($recaptcha_error != ''){
                            echo '<span class="status-not-available">'.$recaptcha_error.'</span>';
                        }
                        ?>
                    </div>

                    <input type="submit" name="Submit" class="button" value="<?php _e("Submit") ?>">
                </form>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 content-left-offset">
            <div class="sidebar-container">

                <div class="sidebar-widget">
                    <h3><?php _e("Recent Blog") ?></h3>
                    <ul class="widget-tabs">
                        <!-- Post #1 -->
                        <?php
                        foreach($recent_blog as $recent_blogs){
                            $image_url = $config['site_url'].'storage/blog/'.$recent_blogs['image'];
                            ?>
                            <li>
                                <a href="<?php _esc($recent_blogs['link']) ?>" class="widget-content <?php _esc($recent_blogs['class']) ?>">
                                    <?php
                                    if($config['blog_banner']){
                                        echo '<img src="'._esc($image_url,false).'">';
                                    }
                                    ?>
                                    <div class="widget-text">
                                        <h5><?php _esc($recent_blogs['title']) ?></h5>
                                        <span><?php _esc($recent_blogs['created_at']) ?></span>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>

                </div>


                <?php
                if($config['testimonials_enable'] && $config['show_testimonials_blog']){
                    ?>
                    <!-- Testimonials Widget -->
                    <div class="sidebar-widget">
                        <h3><?php _e("Testimonials") ?></h3>
                        <div class="single-carousel">
                            <?php
                            foreach($testimonials as $testimonial){
                                ?>
                                <div class="single-testimonial">
                                    <div class="single-inner">
                                        <div class="testimonial-content">
                                            <p><?php _esc($testimonial['content']) ?></p>
                                        </div>
                                        <div class="testi-author-info">
                                            <div class="image"><img src="<?php _esc($config['site_url']);?>storage/testimonials/<?php _esc($testimonial['image']) ?>" alt="<?php _esc($testimonial['name']) ?>"></div>
                                            <h5 class="name"><?php _esc($testimonial['name']) ?></h5>
                                            <span class="designation"><?php _esc($testimonial['designation']) ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- Testimonials Widget / End-->
                <?php } ?>

                <!-- Social Widget -->
                <div class="sidebar-widget">
                    <h3><?php _e("Social Profiles") ?></h3>
                    <div class="freelancer-socials margin-top-25">
                        <ul>
                            <?php
                            if($config['facebook_link'] != "")
                                echo '<li><a href="'._esc($config['facebook_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-facebook"></i></a></li>';
                            if($config['twitter_link'] != "")
                                echo '<li><a href="'._esc($config['twitter_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i></a></li>';
                            if($config['instagram_link'] != "")
                                echo '<li><a href="'._esc($config['instagram_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-instagram"></i></a></li>';
                            if($config['linkedin_link'] != "")
                                echo '<li><a href="'._esc($config['linkedin_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-linkedin"></i></a></li>';
                            if($config['pinterest_link'] != "")
                                echo '<li><a href="'._esc($config['pinterest_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-pinterest"></i></a></li>';
                            if($config['youtube_link'] != "")
                                echo '<li><a href="'._esc($config['youtube_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-youtube"></i></a></li>';
                            ?>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- main -->
<script src='https://www.google.com/recaptcha/api.js'></script>
<?php
overall_footer();
?>
