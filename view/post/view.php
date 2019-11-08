<?php
    setlocale(LC_TIME, 'ru_RU');
?>

<!-- Post Content Column -->
<div class="col-lg-12">
    <!-- Title -->
    <h1 class="mt-4"><?php echo $post->title; ?></h1>

    <div>
        <!-- Author -->
        <i class="fas fa-user-circle"></i> <?php echo $post->author; ?>

        <!-- Date/Time -->
        <div class="float-right">
            <?php echo strftime('%d %B %Y, %H:%M', strtotime($post->date_created)) ?>
        </div>
    </div>

    <hr>

    <!-- Post Content -->
    <p><?php echo $post->content ?></p>

    <hr>

    <!-- Comments Form -->
    <div class="card my-4">
        <h5 class="card-header">Оставьте комментарий:</h5>
        <div class="card-body">
            <form id="comment-form" action="/post/add-comment" method="post">
                <input type="hidden" name="_token" value="<?php echo $_token; ?>">
                <input type="hidden" name="Comment[post_id]" value="<?php echo $post->id; ?>">
                <div class="form-group">
                    <label for="comment-author">Автор</label>
                    <input type="text" id="comment-author" class="form-control" name="Comment[author]" value="<?php echo $_post['author'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="comment-content">Содержание</label>
                    <textarea id="comment-content" class="form-control" rows="3" name="Comment[content]" required><?php echo $_post['content'] ?? null; ?></textarea>
                </div>

                <div class="error mb-2">
                    <span class="text-danger"></span>
                </div>

                <input type="submit" class="btn btn-primary" value="Отправить">
            </form>
        </div>
    </div>

    <div class="comments">
        <?php
            if($comments) {
                foreach ($comments as $comment) : ?>
                    <div class="card mb-4">
                        <div class="card-header bg-transparent">
                            <span class="mt-0 h5"><?php echo $comment->author; ?></span>
                            <span class="float-right">
                                <small class="text-muted">
                                    <?php echo strftime('%Y-%m-%d %H:%M', strtotime($comment->date_created)); ?>
                                </small>
                            </span>
                        </div>
                        <div class="card-body">
                            <?php echo $comment->content; ?>
                        </div>
                    </div>
        <?php   endforeach;
            } else {
                echo '<p>Пока нет комментариев</p>';
            }
        ?>
    </div>
</div>

<?php
    // if including script refers to script(s) from layout
    ob_start();
    echo '<script src="/js/add-comment.js"></script>';
    $extraJs = ob_get_contents();
    ob_end_clean();
?>
