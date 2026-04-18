<?php 
require_once __DIR__ . '/Model.php';

class User extends Model{
    
    public function getById($id){
        $stmt   = $this->executeQuery("SELECT * FROM user_data WHERE idUser = ?", "i", $id);
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result;
    }

    public function getAllCostumers(){ // test
        $stmt   = $this->executeQuery("SELECT * FROM customer_data");
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result;
    }

    public function verifyEmail($email){
        $idUser = null;
        $password = null;

        $stmt = $this->executeQuery("
            SELECT idUser, passwordUser
            FROM user_data
            WHERE mailUser = ?
            LIMIT 1
        ", "s", $email);

        $stmt->store_result();

        if($stmt->num_rows > 0){
            $stmt->bind_result($idUser, $password);
            $stmt->fetch();
            $stmt->close();
            
            return ["emailExists" => True, "userId" => $idUser, "password" => $password];
        }
        return ["emailExists" => False];
    }

    public function updateData($attribute, $value, $idUser){
        $stmt = $this->executeQuery("
            UPDATE user_data
            SET $attribute = ?
            WHERE idUser = ?
            ", 
            "si", $value, $idUser);

        if(!$stmt) throw new Exception("Erro ao alterar dados do usuário");
        
        $stmt->close();

        return True;
    }

    public function addUser(...$userData){ // always as customer
        $stmt = $this->executeQuery("
            INSERT INTO user_data (
                nameUser, mailUser, phoneUser, passwordUser, a_district, 
                a_street, a_referencePoint, a_numHouse, a_city, a_state
            ) VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", "ssssssssss", ...$userData);

        if(!$stmt) throw new Exception("Erro ao adicionar novo usuário");

        $insertId = $stmt->insert_id;
        $stmt->close();

        return ["insert" => True, "idUser" => $insertId];
    }

    public function addCustomer($idUser){
        $stmt = $this->executeQuery("
            INSERT INTO customer_data (idCustomer) VALUES (?)
        ", "i", $idUser);

        if(!$stmt) throw new Exception("Erro ao adicionar novo cliente");

        $stmt->close();

        return True;
    }
}