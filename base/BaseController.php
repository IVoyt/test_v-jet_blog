<?php

    namespace base;

    class BaseController
    {

        private $baseUrl;
        protected $layout = 'app.php';
        protected $extraData = [];

        /**
         * BaseController constructor.
         */
        public function __construct()
        {
            $this->baseUrl = $this->getBaseUrl();
        }

        public function render($view, $data = null)
        {
            if(!empty($data))               extract($data);
            if(!empty($this->extraData))    extract($this->extraData);

            ob_start();
            require __DIR__.'/../view/'.$view.'.php';
            $content = ob_get_contents();
            ob_end_clean();
            $layout = $this->layout ?? 'empty.php';
            return require __DIR__.'/../view/layout/'.$layout;
        }

        public function redirect($route)
        {
            header("Location: $this->baseUrl/$route");
        }

        public function getBaseUrl(){
            return sprintf(
                "%s://%s",
                isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
                $_SERVER['SERVER_NAME']
            );
        }

        public function notFound()
        {
            return $this->render('404', ['title' => 'Page Not Found']);
        }


    }