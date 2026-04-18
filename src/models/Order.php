<?php
require_once __DIR__ . "/Model.php";

class Order extends Model{
    
    public function getProductsOrder($idOrder){
        // return all products inserted in a order
    }

    public function orderItemAmount($idOrder){
        // return amount of items in a order
        $stmt = $this->executeQuery("SELECT COUNT(*) AS itemCount FROM product_order WHERE idOrder = ?", "i", $idOrder);
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if($result){
            return $result['itemCount'];
        }else{
            return null;
        }
    }

    public function removeExpiredOrders(){
        $stmt = $this->executeQuery("
            DELETE FROM order_data
            WHERE 
                dateTime < NOW() - INTERVAL 1 DAY
                AND status = 'pending'
                AND NOT EXISTS(
                    SELECT 1 
                    FROM product_order 
                    WHERE idOrder = order_data.idOrder
                )",
        );
        if(!$stmt){
            throw new Exception("Erro ao remover pedidos expirados");
        }
        $stmt->close();
    }

    public function addOrder(...$orderData){
        $stmt = $this->executeQuery("
        INSERT INTO order_data (idCustomer) VALUES (?)
        ", "i", ...$orderData);
        $result = $stmt->insert_id;

        if(!$stmt){
            throw new Exception("Erro ao adicionar novo pedido");
        }

        $stmt->close();

        return ['newId' => $result];
    }

}

