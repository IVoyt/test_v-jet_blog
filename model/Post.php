<?php


    namespace model;

    use base\Application;
    use base\BaseDB;

    class Post extends BaseDB
    {

        protected $tableName = 'post';

        public function __construct()
        {
            parent::__construct();
            $this->setFields();
        }

        /**
         * @return mixed
         */
        public function getAllPosts()
        {
            $commentModel = new Comment();
            $this->fields[] = 'COUNT(c.id) as comments_count';
            return $this->select($this->fields)
                ->join('LEFT', $commentModel->getTableName().' as c', 'c.post_id', $this->tableName.'.id')
                ->groupBy([$this->getTableName().'.id'])
                ->orderBy(['DESC' => $this->getTableName().'.date_created'])
                ->all();
        }

        /**
         * @return mixed
         */
        public function getAllPostsCount()
        {
            return $this->select('COUNT(id) as count')->one();
        }

        /**
         * @return mixed
         */
        public function getPostsPaging($start, $limit)
        {
            $commentModel = new Comment();
            $this->fields[] = 'COUNT(c.id) as comments_count';
            return $this->select($this->fields)
                ->join('LEFT', $commentModel->getTableName().' as c', 'c.post_id', $this->tableName.'.id')
                ->groupBy([$this->getTableName().'.id'])
                ->orderBy([$this->getTableName().'.date_created' => 'DESC'])
                ->limit($start, $limit)
                ->all();
        }

        /**
         * @param $id
         * @return mixed
         */
        public function getPostById($id)
        {
            return $this->select('*')->where('id', '=', $id)->one();
        }

        /**
         * @param $limit
         * @return mixed
         */
        public function getPopularPosts($limit)
        {
            $commentModel = new Comment();
            $this->fields[] = 'COUNT(c.id) as comments';
            return $this->select($this->fields)
                ->join('LEFT', $commentModel->getTableName().' as c', 'c.post_id', $this->tableName.'.id')
                ->groupBy([$this->getTableName().'.id'])
                ->orderBy(['comments' => 'DESC', $this->getTableName().'.date_created'])
                ->limit($limit)
                ->all();
        }

        /**
         * @param array $values
         * @return bool
         */
        public function insert($values)
        {
            return parent::insert($values);
        }

    }