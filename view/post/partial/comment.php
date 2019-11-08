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