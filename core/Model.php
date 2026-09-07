<?php
class Model {
    protected $db;

    public function __construct() {
        $this->db = $this->db();
    }

    protected function db() {
        try {
            return Database::getConnection();
        } catch (Throwable $e) {
            return null;
        }
    }
}
