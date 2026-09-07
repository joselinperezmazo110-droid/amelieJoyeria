<?php
class Product extends Model {
    public function all() {
        $db = $this->db();

        if ($db) {
            try {
                $stmt = $db->query('SELECT id, name, price, description FROM products ORDER BY id ASC');
                return $stmt->fetchAll();
            } catch (PDOException $e) {
                // Si la tabla no existe o hay un problema temporal, usamos datos de respaldo
            }
        }

        return [
            ['id' => 1, 'name' => 'Anillo de oro', 'price' => 150.00, 'description' => 'Anillo elegante de oro 18k'],
            ['id' => 2, 'name' => 'Collar plata', 'price' => 80.00, 'description' => 'Collar delicado en plata 925'],
            ['id' => 3, 'name' => 'Pulsera fina', 'price' => 120.50, 'description' => 'Pulsera moderna para uso diario'],
        ];
    }
}
