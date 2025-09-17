<?php
require_once __DIR__ . '/../includes/Database.php';

class Order {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($userId, $items, $totalAmount, $shippingAddress, $paymentMethod = 'cod') {
        try {
            $this->db->beginTransaction();
            
            // Create order
            $stmt = $this->db->prepare("
                INSERT INTO orders (user_id, total_amount, shipping_address, payment_method) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$userId, $totalAmount, $shippingAddress, $paymentMethod]);
            
            $orderId = $this->db->lastInsertId();
            
            // Create order items
            $stmt = $this->db->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, price) 
                VALUES (?, ?, ?, ?)
            ");
            
            foreach ($items as $item) {
                $stmt->execute([
                    $orderId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price']
                ]);
                
                // Update product stock
                $updateStock = $this->db->prepare("
                    UPDATE products SET stock = stock - ? WHERE id = ?
                ");
                $updateStock->execute([$item['quantity'], $item['product_id']]);
            }
            
            $this->db->commit();
            return $orderId;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function getByUserId($userId) {
        $stmt = $this->db->prepare("
            SELECT o.*, 
                   GROUP_CONCAT(p.name || ' (x' || oi.quantity || ')' SEPARATOR ', ') as items
            FROM orders o
            LEFT JOIN order_items oi ON o.id = oi.order_id
            LEFT JOIN products p ON oi.product_id = p.id
            WHERE o.user_id = ?
            GROUP BY o.id
            ORDER BY o.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function getAll($limit = null) {
        $sql = "
            SELECT o.*, u.name as user_name, u.email as user_email,
                   GROUP_CONCAT(p.name || ' (x' || oi.quantity || ')' SEPARATOR ', ') as items
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            LEFT JOIN order_items oi ON o.id = oi.order_id
            LEFT JOIN products p ON oi.product_id = p.id
            GROUP BY o.id
            ORDER BY o.created_at DESC
        ";
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT o.*, u.name as user_name, u.email as user_email
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            WHERE o.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getOrderItems($orderId) {
        $stmt = $this->db->prepare("
            SELECT oi.*, p.name as product_name, p.image as product_image
            FROM order_items oi
            LEFT JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }
    
    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("
            UPDATE orders SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?
        ");
        return $stmt->execute([$status, $id]);
    }
    
    public function getCount() {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM orders");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    public function getTotalRevenue() {
        $stmt = $this->db->prepare("
            SELECT SUM(total_amount) FROM orders 
            WHERE status IN ('completed', 'shipped', 'delivered')
        ");
        $stmt->execute();
        return $stmt->fetchColumn() ?: 0;
    }
    
    public function getRecentOrders($limit = 5) {
        return $this->getAll($limit);
    }
}
?>