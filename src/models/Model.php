<?php 

class Model{
    protected $mysqli;

    public function __construct($mysqli){
        $this->mysqli = $mysqli;
    }

    protected function executeQuery($query, $types = null, ...$params){
        $stmt = $this->mysqli->prepare($query);

        if(! $stmt){ 
            die("Prepare Error: ". $this->mysqli->error); 
        }

        if ($types && $params) {
            $stmt->bind_param($types, ...$params);
        }

        if(! $stmt->execute()){ 
            die("Execute Error: ". $this->mysqli->error);
        }

        return $stmt;
    }

}