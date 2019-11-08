<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?php echo ($title) ? $title.' | ' : ''; ?>V-Jet Test Blog</title>

        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/fontawesome/css/fontawesome.min.css">
        <link rel="stylesheet" href="/css/fontawesome/css/solid.min.css">
        <link rel="stylesheet" href="/css/custom.css">
        <?php echo $extraCss ?? ''; ?>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="/posts">V-Jet Test Blog</a>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="container">
            <div class="content">
                <div class="row">
                    <?php echo $content; ?>

                    <?php
                        if(!empty($renderSidebar) && $renderSidebar) require_once __DIR__.'/../post/partial/sidebar.php'
                    ?>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="py-5 bg-dark">
            <div class="container">
                <p class="m-0 text-center text-white">Copyright &copy; Igor Voytovich 2019</p>
            </div>
        </footer>

        <script src="/js/jquery-3.4.0.min.js"></script>
        <?php echo $extraJs ?? ''; ?>
    </body>
</html>
