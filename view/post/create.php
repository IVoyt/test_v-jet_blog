<div class="col-md-12">
    <h1 class="mt-4">Новый пост</h1>

    <form id="post-form" action="/post/create" method="post">
        <input type="hidden" name="_token" value="<?php echo $_token; ?>">
        <div class="form-group">
            <label for="post-author">Автор</label>
            <input type="text" id="post-author" name="Post[author]" value="<?php echo ($_post['author']) ?? '' ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="post-title">Заголовок</label>
            <input type="text" id="post-title" name="Post[title]" value="<?php echo ($_post['title']) ?? '' ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="post-content">Содержание</label>
            <textarea name="Post[content]" id="post-content" cols="30" rows="10" class="form-control" required>
                <?php echo ($_post['content']) ?? '' ?>
            </textarea>
        </div>
        <div class="form-group">
            <input type="submit" value="Добавить" class="btn btn-primary">
        </div>
    </form>
</div>

<script src="/js/tinymce/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: 'textarea#post-content',
        height: 500,
        menubar: 'file edit view insert format tools table help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment',
        plugins: [
            'autolink lists link image charmap print preview anchor',
            'visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount'
        ],
        external_filemanager_path:"/js/filemanager/",
        external_plugins: { "filemanager" : "/js/filemanager/plugin.min.js"}
    });
</script>


