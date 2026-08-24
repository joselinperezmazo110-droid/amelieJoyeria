<?php
class Product extends Model {
    public function all() {
        return [
            ['id' => 1, 'name' => 'Anillo de oro', 'price' => 150],
            ['id' => 2, 'name' => 'Collar plata', 'price' => 80],
        ];
    }
}
