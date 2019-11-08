<?php

    namespace base;


    class Application
    {

        private static $path;
        private static $instance = null;
        private $session;

        public function __construct()
        {
            if(!self::$instance) {
                self::$path = $_SERVER['REQUEST_URI'];
                self::$instance = $this;
                $this->setSession();
            }
            return self::$instance;
        }

        public static function getInstance()
        {
            return self::$instance;
        }

        public function getPath()
        {
            return self::$path;
        }

        public function dump($dbg, $var_dump = 0, $die = 0)
        {
            $dbg_type = ($var_dump) ? 'var_dump' :'print_r';
            echo '<pre>'; $dbg_type($dbg); echo '</pre>';
            if ($die) die;
        }

        public function getCsrfToken()
        {
            return $this->session['_token'] ?? null;
        }

        public function setCsrfToken()
        {
            $this->session['_token'] = bin2hex(random_bytes(32));
            return $this;
        }

        private function setSession()
        {
            if(!$this->session) session_start();
            $this->session = &$_SESSION;
        }

    }