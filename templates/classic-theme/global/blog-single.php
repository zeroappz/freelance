<?php
overall_header($title, $meta_desc, $meta_image, true);
?>
<!-- Content
================================================== -->
<div id="titlebar" class="gradient">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?php _esc($title);?></h2>
                <span><?php _e("by") ?> <?php _esc($author);?></span>

                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><a href="<?php url("BLOG") ?>"><?php _e("Blog") ?></a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Post Content -->
<div class="container">
    <div class="row">

        <!-- Inner Content -->
        <div class="col-xl-8 col-lg-8">
            <!-- Blog Post -->
            <div class="blog-post single-post">

                <!-- Blog Post Thumbnail -->
                <div class="blog-post-thumbnail">
                    <div class="blog-post-thumbnail-inner">
                        <span class="blog-item-tag"><?php _e("Tips") ?></span>
                        <?php if($config['blog_banner'] && isset($image)){ ?>
                            <img src="<?php _esc($config['site_url']);?>storage/blog/<?php _esc($image);?>" alt="<?php _esc($title);?>">
                        <?php } ?>

                    </div>
                </div>
                <div class="blog-listing-footer">
                    <ul>
                        <li><a href="<?php _esc($author_link);?>">
                                <img src="<?php _esc($config['site_url']);?>storage/profile/<?php _esc($author_pic);?>"
                                                         class="author-avatar">
                                <?php _e("by") ?> <?php _esc($author);?></a></li>
                        <li><i class="la la-clock-o"></i> <?php _esc($created_at);?></li>
                        <li>
                            <div class="blog-cat"><i class="fa fa-folder-o"></i> <?php _esc($categories);?></div>
                        </li>
                    </ul>
                </div>
                <!-- Blog Post Content -->
                <div class="blog-post-content">
                    <h3 class="margin-bottom-10"><?php _esc($title);?></h3>

                    <div class="user-html"><?php _esc($description);?></div>
                    <?php if($show_tag){ ?>
                        <div class="job-tags margin-bottom-20">
                            <?php _e("Tags") ?>: <?php _esc($blog_tags);?>
                        </div>
                    <?php } ?>
                    <!-- Share Buttons -->
                    <div class="share-buttons margin-top-25">
                        <div class="share-buttons-trigger"><i class="icon-feather-share-2"></i></div>
                        <div class="share-buttons-content">
                            <span><?php _e("Interesting?") ?> <strong><?php _e("Share It!") ?></strong></span>
                            <ul class="share-buttons-icons">

                                <li><a href="mailto:?subject={TITLE}&body={BLOG_LINK}" data-button-color="#dd4b39"
                                       title="<?php _e("Share on Email") ?>" data-tippy-placement="top" rel="nofollow"
                                       target="_blank"><i class="fa fa-envelope"></i></a></li>
                                <li><a href="https://facebook.com/sharer/sharer.php?u={BLOG_LINK}"
                                       data-button-color="#3b5998" title="<?php _e("Share on Facebook") ?>"
                                       data-tippy-placement="top" rel="nofollow" target="_blank"><i
                                                class="fa fa-facebook"></i></a></li>
                                <li><a href="https://twitter.com/share?url={BLOG_LINK}&text={TITLE}"
                                       data-button-color="#1da1f2" title="<?php _e("Share on Twitter") ?>"
                                       data-tippy-placement="top" rel="nofollow" target="_blank"><i
                                                class="fa fa-twitter"></i></a></li>
                                <li><a href="https://www.linkedin.com/shareArticle?mini=true&url={BLOG_LINK}"
                                       data-button-color="#0077b5" title="<?php _e("Share on LinkedIn") ?>"
                                       data-tippy-placement="top" rel="nofollow" target="_blank"><i
                                                class="fa fa-linkedin"></i></a></li>
                                <li>
                                    <a href="https://pinterest.com/pin/create/bookmarklet/?&url={BLOG_LINK}&description={TITLE}"
                                       data-button-color="#bd081c" title="<?php _e("Share on Pinterest") ?>"
                                       data-tippy-placement="top" rel="nofollow" target="_blank"><i
                                                class="fa fa-pinterest-p"></i></a></li>
                                <li><a href="https://web.whatsapp.com/send?text={BLOG_LINK}" data-button-color="#25d366"
                                       title="<?php _e("Share on WhatsApp") ?>" data-tippy-placement="top" rel="nofollow"
                                       target="_blank"><i class="fa fa-whatsapp"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Blog Post Content / End -->
            <div id="comments">
                <?php if($config['blog_comment_enable']){
                    if($comments_count){ ?>
                        <div class="blog-widget">
                            <h3 class="widget-title margin-bottom-25"><?php _e("Comments") ?> (<?php _esc($comments_count) ?>)</h3>

                            <div class="latest-comments comments">
                                <ul>
                                    <?php
                                    foreach($comments as $comment){
                                        ?>
                                    <li id="li-comment-<?php _esc($comment['id']) ?>"
                                        <?php if($comment['is_child']){ echo 'class="children-'._esc($comment['level'],false).'"'; } ?> >
                                        <div class="comments-box" id="comment-<?php _esc($comment['id']) ?>">
                                            <div class="avatar">
                                                <img src="<?php _esc($config['site_url']);?>storage/profile/<?php _esc($comment['avatar']) ?>" alt="<?php _esc($comment['name']) ?>">
                                            </div>
                                            <div class="comment-content"><div class="arrow-comment"></div>
                                                <div class="comment-by">
                                                    <?php _esc($comment['name']) ?>
                                                    <span class="date"><?php _esc($comment['created_at']) ?></span>

                                                    <?php if($comment['level'] < 3){ ?>
                                                    <a class="reply comments-reply comment-reply-link" href="javascript:void(0)"
                                                       data-commentid="<?php _esc($comment['id']) ?>" data-postid="<?php _esc($blog_id) ?>"
                                                       data-belowelement="comment-<?php _esc($comment['id']) ?>"
                                                       data-respondelement="respond"><i class="fa fa-reply"></i><?php _e("Reply") ?></a>
                                                    <?php } ?>
                                                </div>
                                                <p><?php _esc($comment['comment']) ?></p>
                                            </div>
                                        </div>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>

                        <?php if($show_paging){ ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pagination-container margin-top-10 margin-bottom-20">
                                        <nav class="pagination">
                                            <ul>
                                                <?php
                                                foreach($comment_paging as $c_paging) {
                                                    if ($c_paging['current'] == 0)
                                                        echo '<li><a href="' . _esc($c_paging['link'],false) . '">' . _esc($c_paging['title'],false) . '</a></li>';
                                                    else
                                                        echo '<li><a href="#" class="current-page">' . _esc($c_paging['title'],false) . '</a></li>';
                                                }
                                                ?>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    }

                    if($show_comment_form){ ?>
                        <!-- Leave a Comment -->
                        <div class="blog-widget" id="respond">
                            <h3 class="margin-top-35 margin-bottom-30"><?php _e("Post a Comment") ?>
                                <small><a rel="nofollow" id="cancel-comment-reply-link" href="javascript:void(0)"
                                          style="display: none;"><?php _e("Cancel reply") ?></a></small>
                            </h3>

                            <div>
                                <?php
                                if($comment_error){
                                    echo '<div class="notification error"><p>'._esc($comment_error,false).'</p></div>';
                                }
                                if($comment_success){
                                    echo '<div class="notification success"><p>'._esc($comment_success,false).'</p></div>';
                                }
                                ?>

                                <form action="#respond" method="post" id="commentform" class="blog-comment-form">
                                    <div class="row">

                                        <?php
                                        if(!$admin_logged_in || $is_login){ ?>
                                        <div class="col-xl-6">
                                            <div class="input-with-icon-left no-border">
                                                <i class="icon-material-outline-account-circle"></i>
                                                <input class="input-text" type="text" placeholder="<?php _e("Your Name") ?> *" name="user_name"
                                                       value="<?php _esc($user_name) ?>" required="">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="input-with-icon-left no-border">
                                                <i class="icon-material-baseline-mail-outline"></i>
                                                <input class="input-text" type="email" placeholder="<?php _e("Your E-Mail") ?> *"
                                                       name="user_email" value="<?php _esc($user_email) ?>" required>
                                            </div>
                                        </div>


                                        <?php }
                                        if($admin_logged_in && $is_login){ ?>
                                        <div class="col-md-12">
                                            <div class="commenting-as">
                                                <label for="commenting-as"><?php _e("You are commenting as:") ?></label>
                                                <select id="commenting-as" name="commenting-as"
                                                        class="selectpicker with-border col-md-4">
                                                    <option value="admin"><?php _esc($admin_username) ?> (<?php _e("Admin") ?>)</option>
                                                    <option value="user"><?php _esc($username) ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <?php }
                                        else if($admin_logged_in){ ?>
                                        <div class="col-md-12">
                                            <p><?php _e("You are commenting as:") ?> <strong><?php _esc($admin_username) ?></strong> (<?php _e("Admin") ?>)</p>
                                        </div>
                                        <?php }
                                        else if($is_login){ ?>
                                        <div class="col-md-12">
                                            <p><?php _e("You are commenting as:") ?> <strong><?php _esc($username) ?></strong></p>
                                        </div>
                                        <?php } ?>
                                        <div class="col-md-12">
                                    <textarea rows="5" id="comment-field" name="comment" placeholder="<?php _e("Your comment...") ?>"
                                              required><?php _esc($comment) ?></textarea>
                                            <button type="submit" name="comment-submit"
                                                    class="button ripple-effect"><?php _e("Submit") ?></button>
                                            <input type="hidden" name="comment_parent" id="comment_parent" value="0">
                                            <input type="hidden" name="comment_post_ID" value="<?php _esc($blog_id) ?>" id="comment_post_ID">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Leave a Comment / End -->
                    <?php }else{ ?>
                        <div class="blog-widget">
                            <?php _e("Please login to post a comment.") ?>
                        </div>
                    <?php } ?>

                <?php } ?>


            </div>

        </div>
        <!-- Inner Content / End -->


        <div class="col-xl-4 col-lg-4 content-left-offset">
            <div class="sidebar-container margin-top-65">
                <form action="<?php url("BLOG") ?>">
                    <div class="sidebar-widget margin-bottom-40">
                        <div class="input-with-icon">
                            <input type="text" placeholder="<?php _e("Search") ?>..." name="s"
                                   id="search-widget">
                            <i class="icon-material-outline-search"></i>
                        </div>
                    </div>
                </form>

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


                <!-- Category Widget -->
                <div class="sidebar-widget">
                    <h3><?php _e("Categories") ?></h3>
                    <ul>
                        <?php
                        foreach($blog_cat as $blog_cats){
                        ?>
                            <li class="clearfix">
                                <a href="<?php _esc($blog_cats['link']) ?>">
                                    <span class="pull-left"><?php _esc($blog_cats['title']) ?></span>
                                    <span class="pull-right">(<?php _esc($blog_cats['blog']) ?>)</span></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <!-- Category Widget / End-->

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

                <!-- Tags Widget -->
                <div class="sidebar-widget">
                    <h3><?php _e("Tags") ?></h3>
                    <div class="task-tags">
                        <?php _esc($all_tags) ?>
                    </div>
                </div>

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

<!-- Spacer -->
<div class="padding-top-40"></div>
<!-- Spacer -->


<script src="<?php _esc(TEMPLATE_URL);?>/js/comment-reply.js"></script>
<?php
overall_footer();
?>