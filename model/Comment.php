<?php


    namespace model;

    use base\Application;
    use base\BaseDB;

    class Comment extends BaseDB
    {

        protected $tableName = 'comment';

        public function __construct()
        {
            parent::__construct();
            $this->setFields();
        }

        public function getCommentById($id)
        {
            return $this->select('*')->where('id', '=', $id)->one();
        }

        public function getAllCommentsByPostId($postId)
        {
            return $this->select('*')
                ->where('post_id', '=', $postId)
                ->orderBy(['date_created' => 'DESC'])
                ->all();
        }

        public function insert($values)
        {
            return parent::insert($values);
        }

    }