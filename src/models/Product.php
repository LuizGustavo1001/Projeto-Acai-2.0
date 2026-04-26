<?php 
require_once __DIR__ . "/Model.php";

class Product extends Model{
    public function getById($id, $attribute){
        $attrMap = ["pd.idProduct", "pv.idVariant"];

        if(! in_array($attribute, $attrMap)){
            return false;
        }

        $stmt = $this->executeQuery("
            SELECT pd.*, pv.* 
            FROM product_data AS pd 
                JOIN product_variant AS pv ON pv.idProduct = pd.idProduct
            WHERE $attribute = ?
            ORDER BY $attribute",
        "i", $id);
        $resultObject = $stmt->get_result();
        $result = $resultObject->fetch_all(MYSQLI_ASSOC);
        $amount = $resultObject->num_rows;

        $stmt->close();

        return ["data" => $result, "amount" => $amount];
    }

    public function getLikeName($name, $attribute){
        $attrMap = ["pd.printName", "pd.altName"];

        if(! in_array($attribute, $attrMap)){
            return false;
        }

        $name = "%{$name}%";

        $stmt = $this->executeQuery("
            SELECT pd.*, pv.*
                FROM product_data pd
                JOIN product_variant pv 
                    ON pv.idProduct = pd.idProduct
                WHERE $attribute LIKE ?
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
            SELECT DISTINCT typeProduct 
            FROM product_data
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


class Variant extends Model{


}