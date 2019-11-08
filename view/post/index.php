<?php
    setlocale(LC_TIME, 'ru_RU');
?>

<div class="col-sm-12 my-4">
    <a href="/post/create" class="btn btn-success">Добавить пост</a>
</div>


<!-- Blog Entries Column -->
<div class="col-md-8">

    <!-- Blog Post -->
    <?php if(!empty($posts)) :
        foreach ($posts as $post) : ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title"><?php echo $post->title ?></h2>
                    <?php $shortContent = mb_substr(strip_tags(html_entity_decode($post->content)), 0, 100, "utf-8"); ?>
                    <?php $contentLength = mb_strlen($shortContent, "utf-8"); ?>
                    <?php if($contentLength >= 100) $shortContent .= '...'; ?>
                    <p class="card-text"><?php echo $shortContent; ?></p>
                    <a href="/post/<?php echo $post->id; ?>" class="btn btn-sm btn-primary">Читать полностью &rarr;</a>
                </div>
                <div class="card-footer text-muted">
                    <?php echo strftime('%d %B %Y, %H:%M', strtotime($post->date_created)) ?>
                    <i class="fas fa-user-circle"></i>
                    <?php echo $post->author; ?>
                    <div class="float-right"><i class="fas fa-comments"></i> <?php echo $post->comments_count; ?></div>
                </div>
            </div>
        <?php endforeach;
    endif; ?>

    <?php if($allPostsCount > count($posts)) :?>
        <!-- Pagination -->
        <ul class="pagination justify-content-center mb-4">
            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="/posts?page=<?php echo ($page <= 1) ? 1 : $page - 1 ?>">&larr; Назад</a>
            </li>
            <li class="page-item <?php echo (($allPostsCount - ($limit * $page)) <= 0) ? 'disabled' : '' ?>">
                <a class="page-link" href="/posts?page=<?php echo ($page >= 1) ? $page + 1 : 1 ?>">Вперед &rarr;</a>
            </li>
        </ul>
    <?php endif; ?>

</div>