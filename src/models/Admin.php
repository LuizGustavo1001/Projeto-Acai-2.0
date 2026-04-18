<?php 

require_once __DIR__ . "/User.php";

class Admin extends User{

    public function getAvatar($id){
        $stmt = $this->executeQuery("SELECT adminPicture FROM admin_data WHERE idAdmin = ?", "i", $id);
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result;
    }
}