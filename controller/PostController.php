<?php

    namespace controller;

    use base\Application;
    use base\BaseController;
    use model\Comment;
    use model\Post;

    class PostController extends BaseController
    {

        public function index($page = 1, $limit = 5)
        {
            if($page <= 0) $page = 1;
            if($limit <= 0) $limit = 5;

            $postModel = new Post();
            $posts = $postModel->getPostsPaging(($page - 1) * $limit, $limit);
            $allPosts = $postModel->getAllPostsCount();
            $popularPosts = $postModel->getPopularPosts(5);

            $this->extraData['popularPosts'] = $popularPosts;
            $this->extraData['renderSidebar'] = true;

            return $this->render('post/index', [
                'posts' => $posts,
                'page' => $page,
                'limit' => $limit,
                'allPostsCount' => $allPosts->count,
                'title' => 'Все публикации'
            ]);
        }

        public function create()
        {
            if($_POST) {
                if(!empty($_POST['_token']) && $_POST['_token'] != Application::getInstance()->getCsrfToken()) {
                    exit('Incorrect token!');
                }
                $postModel = new Post();
                if($postModel->insert($_POST['Post'])) {
                    $this->redirect('posts');
                }
                Application::getInstance()->dump($_POST, 1, 1);
            }
            $_token = Application::getInstance()->setCsrfToken()->getCsrfToken();

            return $this->render('post/create', [
                '_post' => ($_POST['Post']) ?? null,
                '_token' => $_token,
                'title' => 'Добавление публикации'
            ]);
        }

        /**
         * @param $id
         * @return mixed
         */
        public function view($id)
        {
            $postModel = new Post();
            $commentsModel = new Comment();
            $post = $postModel->getPostById($id);

            if (!$post)
                return $this->notFound();

            $comments = $commentsModel->getAllCommentsByPostId($id);

            $_token = Application::getInstance()->setCsrfToken()->getCsrfToken();

            return $this->render('post/view', [
                '_token' => $_token,
                'title' => $post->title,
                'post' => $post,
                'comments' => $comments
            ]);
        }

        public function addComment()
        {
            $this->layout = null;
            if($_POST) {
                header('Content-Type:application/json; charset=UTF-8');
                if(!empty($_POST['_token']) && $_POST['_token'] != Application::getInstance()->getCsrfToken()) {
                    exit(json_encode(['status' => 'error', 'response' => 'Incorrect token!']));
                }
                foreach ($_POST['Comment'] as $key => $value) {
                    if(!$value) exit(json_encode(['status' => 'error', 'response' => 'Заполните все поля!']));
                }

                $commentModel = new Comment();
                if($commentModel->insert($_POST['Comment'])) {
                    header('Content-Type:text/html; charset=UTF-8');
                    $comment = $commentModel->getCommentById($commentModel->getLastInsertId());
                    exit($this->render('post/partial/comment', ['comment' => $comment]));
                } else {
                    header('HTTP/1.1 500 Internal Server Error');
                    exit(json_encode(['status' => 'error', 'response' => 'Unable to add record!']));
                }
            }
        }

    }