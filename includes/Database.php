<?php
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        $config = require __DIR__ . '/../config/database.php';
        
        try {
            // Using SQLite for simplicity in development
            $this->connection = new PDO('sqlite:' . __DIR__ . '/../database/kopi_cepoko.db');
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            $this->createTables();
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    private function createTables() {
        // Users table
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                role VARCHAR(50) DEFAULT 'user',
                phone VARCHAR(20),
                address TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        // Products table
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS products (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255) NOT NULL,
                description TEXT,
                price DECIMAL(10, 2) NOT NULL,
                stock INTEGER DEFAULT 0,
                image VARCHAR(255),
                category VARCHAR(100),
                status VARCHAR(20) DEFAULT 'active',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        // Orders table
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS orders (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                total_amount DECIMAL(10, 2) NOT NULL,
                status VARCHAR(50) DEFAULT 'pending',
                shipping_address TEXT,
                payment_method VARCHAR(50),
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id)
            )
        ");
        
        // Order items table
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS order_items (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                order_id INTEGER NOT NULL,
                product_id INTEGER NOT NULL,
                quantity INTEGER NOT NULL,
                price DECIMAL(10, 2) NOT NULL,
                FOREIGN KEY (order_id) REFERENCES orders(id),
                FOREIGN KEY (product_id) REFERENCES products(id)
            )
        ");
        
        // Sessions table for user sessions
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS sessions (
                id VARCHAR(128) PRIMARY KEY,
                user_id INTEGER,
                data TEXT,
                last_activity INTEGER,
                FOREIGN KEY (user_id) REFERENCES users(id)
            )
        ");
        
        $this->seedData();
    }
    
    private function seedData() {
        // Check if admin user exists
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute(['admin@kopicepoko.com']);
        
        if ($stmt->fetchColumn() == 0) {
            // Create admin user
            $stmt = $this->connection->prepare("
                INSERT INTO users (name, email, password, role) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                'Administrator',
                'admin@kopicepoko.com',
                password_hash('admin123', PASSWORD_DEFAULT),
                'admin'
            ]);
            
            // Create sample user
            $stmt->execute([
                'John Doe',
                'user@example.com',
                password_hash('user123', PASSWORD_DEFAULT),
                'user'
            ]);
        }
        
        // Check if products exist
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM products");
        $stmt->execute();
        
        if ($stmt->fetchColumn() == 0) {
            // Create sample products
            $products = [
                ['Kopi Arabica Gayo', 'Kopi arabica premium dari dataran tinggi Gayo, Aceh. Cita rasa yang khas dengan aroma yang harum.', 85000, 50, 'arabica-gayo.jpg', 'Arabica'],
                ['Kopi Robusta Lampung', 'Kopi robusta terbaik dari Lampung dengan rasa yang kuat dan kafein tinggi.', 65000, 75, 'robusta-lampung.jpg', 'Robusta'],
                ['Kopi Luwak Asli', 'Kopi luwak asli Indonesia dengan proses fermentasi alami yang menghasilkan rasa unik.', 250000, 20, 'kopi-luwak.jpg', 'Premium'],
                ['Kopi Toraja Kalosi', 'Kopi dari Sulawesi Selatan dengan body yang penuh dan rasa earthy yang khas.', 95000, 40, 'toraja-kalosi.jpg', 'Arabica'],
                ['Kopi Java Preanger', 'Kopi tradisional dari dataran tinggi Jawa Barat dengan karakteristik mild dan balance.', 75000, 60, 'java-preanger.jpg', 'Arabica'],
                ['Kopi Bali Kintamani', 'Kopi arabica dari Bali dengan rasa fruity dan floral yang menyegarkan.', 80000, 35, 'bali-kintamani.jpg', 'Arabica']
            ];
            
            $stmt = $this->connection->prepare("
                INSERT INTO products (name, description, price, stock, image, category) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            foreach ($products as $product) {
                $stmt->execute($product);
            }
        }
    }
}
?>