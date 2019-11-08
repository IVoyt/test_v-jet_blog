<?php
    ob_start();
    echo '<link rel="stylesheet" href="/css/flexslider/flexslider.css">';
    $extraCss = ob_get_contents();
    ob_end_flush();
?>

<!-- Sidebar Widgets Column -->
<div class="col-md-4">
    <!-- Popular Posts Widget -->
    <div class="card">
        <h5 class="card-header">Популярное</h5>
        <div class="card-body slider">
            <?php if($popularPosts) : ?>
                <div class="flexslider">
                    <ul class="slides">
                        <?php foreach ($popularPosts as $popularPost) : ?>
                            <li class="slide text-center">
                                <div class="item">
                                    <a href="/post/<?php echo $popularPost->id; ?>"><?php echo $popularPost->title; ?></a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php
    // if including script refers to script(s) from layout
    ob_start();
    echo '<script src="/js/popular-posts.js"></script>';
    echo '<script src="/js/flexslider/jquery.flexslider-min.js"></script>';
    $extraJs = ob_get_contents();
    ob_end_clean();
?>
