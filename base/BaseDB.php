<?php

    namespace base;


    class BaseDB
    {
        protected $instance;
        protected $tableName;
        protected $fields = [];
        protected $query;
        protected $whereValues = [];

        /**
         * @var \PDO
         */
        private $dbh;
        /**
         * @var $sth \PDOStatement
         */
        private $sth;
        private $driver;
        private $host;
        private $port;
        private $username;
        private $password;
        private $database;

        public function __construct()
        {
            if(!$this->instance) {
                $this->instance = $this;

                $dbConf = require __DIR__.'/../config/db.php';
                $this->driver   = ($dbConf['driver'])   ?? die('"Driver" cannot be null');
                $this->host     = ($dbConf['host'])     ?? die('"Host" cannot be null');
                $this->port     = ($dbConf['port'])     ?? die('"Port" cannot be null');
                $this->username = ($dbConf['username']) ?? die('"Username" cannot be null');
                $this->password = ($dbConf['password']) ?? die('"Password" cannot be null');
                $this->database = ($dbConf['database']) ?? die('"Database" cannot be null');

                $this->dbh = $this->init();
            }

            return $this->instance;
        }

        public function getDatabaseName()
        {
            return $this->database;
        }

        public function getTableName()
        {
            return $this->tableName;
        }

        public function getLastInsertId()
        {
            return $this->dbh->lastInsertId();
        }


        /**
         * @param string|array  $fields
         * @param string        $from
         * @return $this
         */
        public function select($fields, $from = '')
        {
            $this->whereValues = [];

            if($fields == '*') $fields = $this->fields;
            if($from == '') $from = $this->tableName;
            $selectFields = (is_array($fields)) ? implode(',', $fields) : $fields;
            $this->query = "SELECT $selectFields FROM $from";

            return $this;
        }

        /**
         * @param string $joinType      - LEFT|RIGHT|INNER
         * @param string $joinTable
         * @param string $srcField      - tableName.fieldName
         * @param string $targetField   - tableName.fieldName
         * @return $this
         */
        public function join(string $joinType, string $joinTable, string $srcField, string $targetField)
        {
            $this->query .= " $joinType JOIN $joinTable ON $srcField = $targetField";

            return $this;
        }

        public function where(string $field, string $cmp, $value)
        {
            return $this->whereConstructor('WHERE', $field, $cmp, $value);
        }

        public function andWhere(string $field, string $cmp, $value)
        {
            return $this->whereConstructor('AND', $field, $cmp, $value);
        }

        public function orWhere(string $field, string $cmp, $value)
        {
            return $this->whereConstructor('OR', $field, $cmp, $value);
        }

        /**
         * @param string        $filterOperator - WHERE|AND|OR
         * @param string        $field          - Field Name
         * @param string        $cmp            - Comparison Operator
         * @param string|array  $value          - Comparing Value
         * @return $this
         */
        public function whereConstructor(string $filterOperator, string $field, string $cmp, $value)
        {
            $whereValuesPrepare = [];
            if(is_array($value)) {
                foreach ($value as $elm) {
                    $this->whereValues[] = $elm;
                    $whereValuesPrepare[] = '?';
                }
                $value = '('.implode(',', $whereValuesPrepare).')';
            } else {
                $this->whereValues[] = $value;
                $value = '?';
            }
            $this->query .= " $filterOperator $field $cmp $value";

            return $this;
        }

        public function groupBy(array $groupFields)
        {
            $group = [];
            foreach ($groupFields as $item) {
                $group[] = $item;
            }
            $group = implode(',', $group);
            $this->query .= " GROUP BY $group";

            return $this;
        }

        /**
         * @param array|string $orderFields - fieldName => ASC|DESC
         * @return $this
         */
        public function orderBy($orderFields)
        {
            if(is_array($orderFields)) {
                $orderBy = [];
                foreach ($orderFields as $key => $item) {
                    if(!is_numeric($key))   $orderBy[] = "$key $item";
                    else                    $orderBy[] = $item;
                }
                $orderBy = implode(',', $orderBy);
            } else {
                $orderBy = $orderFields;
            }

            $this->query .= " ORDER BY $orderBy";

            return $this;
        }

        /**
         * @param int $start
         * @param int $end
         * @return $this
         */
        public function limit(int $start, int $end = 0)
        {
            $this->query .= " LIMIT $start";
            if ($end) $this->query .= ",$end";

            return $this;
        }

        /**
         * @return mixed
         */
        public function all()
        {
            return $this->selectResult(1);
        }

        /**
         * @return mixed
         */
        public function one()
        {
            return $this->selectResult();
        }

        /**
         * For debug purposes
         * @return mixed
         */
        public function printQueryString()
        {
            Application::getInstance()->dump($this->query);
            return $this;
        }

        /**
         * Here we fill property $fields with table fields
         */
        protected function setFields()
        {
            $this->fields = [];
            $fields = $this->select('COLUMN_NAME', 'INFORMATION_SCHEMA.COLUMNS')
                ->where('TABLE_SCHEMA', '=', $this->getDatabaseName())
                ->andWhere('TABLE_NAME', '=', $this->tableName)
                ->all();

            foreach ($fields as $field) {
                $this->fields[] = "$this->tableName.$field->COLUMN_NAME";
            }
        }

        /**
         * @param array $values
         * @return bool
         */
        protected function insert(array $values)
        {
            $this->checkDbh();

            $fields = $insertValues = [];
            foreach ($values as $key => $value) {
                $fields[] = $key;
                $insertValues[] = (trim($value));
                $insertValuesPrepare[] = '?';
            }
            if(!empty($fields) && !empty($insertValues) && !empty($insertValuesPrepare)) {
                $fields = implode(',', $fields);
                $insertValuesPrepare = implode(',', $insertValuesPrepare);
                $this->query = "INSERT INTO `$this->tableName` ($fields) VALUES ($insertValuesPrepare)";

                $this->sth = $this->dbh->prepare($this->query);
                return $this->queryExecute($insertValues);
            }

            return false;
        }


        private function checkDbh()
        {
            if(!$this->dbh) {
                die('Connect DB first');
            }
        }

        private function init()
        {
            return new \PDO(
                "$this->driver:host=$this->host:$this->port;dbname=$this->database",
                $this->username,
                $this->password
            );
        }

        /**
         * @param int $fetchAll
         * @return array|mixed
         */
        private function selectResult(int $fetchAll = 0)
        {
            $this->checkDbh();

            if(empty($this->whereValues)) {
                if(!$this->sth = $this->dbh->query($this->query)) {
                    Application::getInstance()->dump($this->dbh->errorinfo(), 1, 1);
                }
            } else {
                $this->sth = $this->dbh->prepare($this->query);
                $this->queryExecute($this->whereValues);
            }
            if($fetchAll)   return $this->sth->fetchAll(\PDO::FETCH_CLASS, 'stdClass');
            else            return $this->sth->fetchObject('stdClass');
        }

        /**
         * @param $values
         * @return bool
         */
        private function queryExecute($values)
        {
            try {
                return $this->sth->execute($values);
            } catch (\PDOException $DBH) {
                $this->dbh->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
            }
        }

    }