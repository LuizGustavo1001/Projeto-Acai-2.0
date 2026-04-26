<?php
require_once __DIR__ . "/Model.php";

class Order extends Model{
    
    // return products inserted in a order
    public function getProductsAtOrder($idOrder){
        $stmt = $this->executeQuery("
            SELECT * 
            FROM product_order
            WHERE idOrder = ?
        ", "i", $idOrder);
        $resultObject = $stmt->get_result();
        $result = $resultObject->fetch_all(MYSQLI_ASSOC);
        $amount = $resultObject->num_rows;

        if($amount == 0){
            return False;
        }

        foreach($result as $key => $item){
            $totalPrice = $item['amount']*$item['price'];
            $result[$key]['totalPrice'] = $totalPrice;
        }

        return $result;
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

    public function getOrderSubtotal($idOrder){
        $stmt = $this->executeQuery("
            SELECT SUM(price) as sumPrice
            FROM product_order
            WHERE idOrder = ?
        ", "i", $idOrder);

        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return ($result['sumPrice'] == NULL) ? 0 : $result['sumPrice'];
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

    public function removeVariant($idVariant, $idOrder){
        $stmt = $this->executeQuery("
            DELETE FROM product_order
            WHERE idVariant = ? and idOrder = ?
            LIMIT 1
        ", "ii", $idVariant, $idOrder);
        
        if(!$stmt){
            throw new Exception("Erro ao remover produto do carrinho");
        }

        return True;
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


    public function modifyStatus($idOrder, $newStatus){
        $stmt = $this->executeQuery("
            UPDATE order_data
            SET status = ?
            WHERE idOrder = ?
        ", "si", $newStatus, $idOrder);
        
        if(! $stmt){
            throw new Exception("Erro ao alterar status de pedido");
        }

        $stmt->close();

        return True;
    }
}

