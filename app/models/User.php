<?php
class User extends Model {
    public function findByEmail($email) {
        $stmt = Database::getConnection()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function register($name, $email, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = Database::getConnection()->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $hash,
        ]);
    }

    public function login($email, $password) {
        $user = $this->findByEmail($email);
        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        return $user;
    }
}
