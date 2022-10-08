<?php
overall_header(__("Companies"));
?>
<div id="titlebar">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?php _e("Companies") ?></h2>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("Companies") ?></li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</div>
<div class="container margin-bottom-50">
    <div class="col-xl-12">
        <form>
            <div class="input-with-icon">
                <input class="with-border" type="text" placeholder="<?php _e("Search") ?>..." name="keyword"
                       value="<?php _esc($keyword)?>">
                <i class="icon-feather-search"></i>
            </div>
        </form>
        <div class="companies-list">
            <?php
            if($total){
                foreach($companies as $company){
                    ?>
                    <a href="<?php _esc($company['link']) ?>" class="company">
                        <div class="company-inner-alignment">
                            <span class="company-logo"><img src="<?php _esc($config['site_url']);?>storage/products/<?php _esc($company['image']) ?>" alt=""></span>
                            <h4><?php _esc($company['name']) ?> (<?php _esc($company['jobs']) ?>)</h4>
                        </div>
                    </a>
                    <?php
                }
            }else{
                echo '<p>'.__("No result found.").'</p>';
            }
            ?>
        </div>
        <!-- Pagination -->
        <div class="pagination-container margin-top-20">
            <nav class="pagination">
                <ul>
                    <?php
                    if($total){
                        foreach($pages as $page) {
                            if ($page['current'] == 0){
                                ?>
                                <li><a href="<?php _esc($page['link'])?>"><?php _esc($page['title'])?></a></li>
                            <?php }else{
                                ?>
                                <li><a href="#" class="current-page"><?php _esc($page['title'])?></a></li>
                            <?php }
                        }
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>
</div>
<?php
overall_footer();
?>