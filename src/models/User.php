<?php 
require_once __DIR__ . '/Model.php';

class User extends Model{
    public function getById($id){
        $stmt = $this->executeQuery("
            SELECT * FROM user_data WHERE idUser = ?",
        "i", $id);

        if(!$stmt){
            throw new Exception("Erro ao buscar usuário com base no identificador");
        }

        $resultObject = $stmt->get_result();
        $amount = $resultObject->num_rows;
        $result = $resultObject->fetch_assoc();
        $stmt->close();

        if($amount <= 0){
            return False;
        }

        return $result;
    }

    public function getByEmail($mail){
        $stmt = $this->executeQuery("
            SELECT * FROM user_data WHERE mailUser = ?
        ", "s", $mail);

        if(!$stmt){
            throw new Exception("Erro ao buscar usuário com base no email");
        }

        $resultObject = $stmt->get_result();
        $amount = $resultObject->num_rows;
        $result = $resultObject->fetch_assoc();
        $stmt->close();

        if($amount <= 0){
            return False;
        }

        return $result;
    }

    public function getAllCustomers(){ 
        $stmt   = $this->executeQuery("
            SELECT *
            FROM user_data
            WHERE typeUser = 'customer'
        ");

        if(!$stmt){
            throw new Exception("Erro ao buscar usuário com base no email");
        }

        $resultObject = $stmt->get_result();
        $amount = $resultObject->num_rows;
        $result = $resultObject->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        if($amount <= 0){
            return False;
        }

        return $result;
    }

    public function verifyEmail($email){
        $stmt = $this->executeQuery("
            SELECT idUser, passwordUser
            FROM user_data
            WHERE mailUser = ?
            LIMIT 1
        ", "s", $email);

        if(!$stmt){
            throw new Exception ("Erro ao verificar e-mail");
        }

        $resultObject = $stmt->get_result();
        $amount = $resultObject->num_rows;
        $stmt->close();

        if($amount <= 0){
            return False;
        }

        return True;
    }

    public function updateData($attribute, $value, $idUser){
        $stmt = $this->executeQuery("
            UPDATE user_data
            SET $attribute = ?
            WHERE idUser = ?
            ", 
        "si", $value, $idUser);

        if(!$stmt){
            throw new Exception("Erro ao alterar dados do usuário");
        }

        $stmt->close();
    }

    public function addUser(...$userData){ // always as customer
        $stmt = $this->executeQuery("
            INSERT INTO user_data(
                nameUser, mailUser, phoneUser, passwordUser, a_district, 
                a_street, a_referencePoint, a_numHouse, a_city, a_state
            ) VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", "ssssssssss", ...$userData);

        if(!$stmt){
            throw new Exception("Erro ao adicionar novo usuário");
        }

        $insertId = $stmt->insert_id;
        $stmt->close();

        return $insertId;
    }

    public function addCustomer($idUser){
        $stmt = $this->executeQuery("
            INSERT INTO customer_data (idCustomer) VALUES (?)
        ", "i", $idUser);

        if(!$stmt){ 
            throw new Exception("Erro ao adicionar novo cliente");
        }

        $stmt->close();
    }

    public function getPasswordById($idUser){
        $stmt = $this->executeQuery("
            SELECT passwordUser FROM user_data WHERE idUser = ?
        ", "i", $idUser);

        if(!$stmt){
            throw new Exception("Usuário não encontrado");
        }

        $resultObject = $stmt->get_result();
        $amount = $resultObject->num_rows;
        $result = $resultObject->fetch_assoc();
        $stmt->close();

        if($amount === 0){
            return False;
        }

        return $result['passwordUser'];
    }
}