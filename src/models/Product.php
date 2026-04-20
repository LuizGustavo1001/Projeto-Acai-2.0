<?php 
require_once __DIR__ . "/Model.php";

class Product extends Model{
    public function getById($id){
        $stmt = $this->executeQuery("SELECT * FROM product_data WHERE idProduct = ?", "i", $id);
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result;
    }

    public function getLikeName($name){
        $name = "%{$name}%";

        $stmt = $this->executeQuery("
            SELECT pd.*, pv.*
                FROM product_data pd
                JOIN product_variant pv 
                    ON pv.idProduct = pd.idProduct
                WHERE pd.printName LIKE ?
                AND pv.price = (
                    SELECT MIN(pv2.price)
                    FROM product_variant pv2
                    WHERE pv2.idProduct = pd.idProduct
                );
        ", "s", $name);
        
        $resultObject = $stmt->get_result();
        $result = $resultObject->fetch_all(MYSQLI_ASSOC);
        $amount = $resultObject->num_rows;
        $stmt->close();

        return ["items" => $result, "amount" => $amount];
    }

    public function getRandom($amount){
        $stmt = $this->executeQuery("
            SELECT pd.*, pv.*
            FROM product_data pd
            JOIN product_variant pv
                ON pv.idProduct = pd.idProduct
            ORDER BY RAND()
            LIMIT ?
        ", "i", $amount);
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $result;
    }

    public function getTypes(){
        $stmt = $this->executeQuery("
            SELECT DISTINCT typeProduct FROM product_data
        ");

        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return array_column($rows, 'typeProduct');
    }

    public function getByType($type, $orderType){
        switch($orderType){
            case 'nameAsc':
                $orderAttr = 'pd.altName';
                $order = 'ASC';

                break;
            case 'nameDesc':
                $orderAttr = 'pd.altName';
                $order = 'DESC';

                break;
            case 'priceAsc':
                $orderAttr = 'pv.price';
                $order = 'ASC';

                break;
            case 'priceDesc':
                $orderAttr = 'pv.price';
                $order = 'DESC';

                break;
            default:
                $orderAttr = '';
                $order = '';
        }

        $sql = "
            SELECT pd.*, pv.*
            FROM product_data pd
            JOIN product_variant pv 
                ON pv.idProduct = pd.idProduct
            WHERE pd.typeProduct = ?
            AND pv.idVariant = (
                SELECT pv2.idVariant
                FROM product_variant pv2
                WHERE pv2.idProduct = pd.idProduct
                ORDER BY pv2.price ASC, pv2.idVariant ASC
                LIMIT 1
            )
        ";

        if($orderAttr != '' && $order != ''){
            $sql .= " ORDER BY $orderAttr $order";
        }
        
        $stmt = $this->executeQuery($sql, "s", $type);
        
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $result;
    }
}
