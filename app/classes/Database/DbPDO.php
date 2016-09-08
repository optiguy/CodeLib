<?php
/**
 * Created by PhpStorm.
 * User: lilto
 * Date: 25-08-2016
 * Time: 12:11
 */

namespace Database;


class DbPDO extends \PDO
{
    //Fields
    public $host;
    public $user;
    public $pass;
    private $conn;
    private $query;
    private $debug = false;

    //properties
    public function debug($bool = true)
    {
        $this->debug = $bool;
    }

    private function setQuery($sql)
    {
        $this->query = $this->prepare($sql);
    }

    public function DbPDO($dbhost, $dbuser, $dbpass, $options = [])
    {
        try {
            $this->conn = parent::__construct($dbhost, $dbuser, $dbpass, $options);
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }

    }
    public function query($sql, $params = false, $returnType = \PDO::FETCH_OBJ)
    {
        $this->setQuery($sql);
        $this->execute($params);
        $this->count = $this->query->rowCount();

        return $this->query->fetchAll($returnType);
    }
    private function execute($params)
    {
        if($params){
            $this->query->execute($params);
        } else {
            $this->query->execute();
        }
        if($this->debug){
            echo '<pre id="debug_params">',$this->query->debugDumpParams(),'</pre>';
        }
    }
    public function Transaction($list){
        try {
            $this->beginTransaction();
            foreach ($list as $query) {
                if (isset($query['params'])) {
                    $this->setQuery($query['query']);
                    $this->execute($query['params']);
                }
            }
            $this->commit();
            return true;
        } catch (\PDOException $e){
            print "Error!: " . $e->getMessage() . "<br/>";
            $this->rollBack();
            return false;
        }
        return false;
    }
}