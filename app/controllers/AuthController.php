<?php
class AuthController extends Controller {
    public function login() {
        if (!empty($_SESSION['user'])) {
            header('Location: ' . (BASE_URL ?: '') . '/');
            exit;
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $userModel = $this->loadModel('User');
            $user = $userModel ? $userModel->login($email, $password) : false;

            if ($user) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                ];
                header('Location: ' . (BASE_URL ?: '') . '/');
                exit;
            }

            $error = 'Credenciales incorrectas';
        }

        $this->render('auth/login', ['error' => $error]);
    }

    public function register() {
        if (!empty($_SESSION['user'])) {
            header('Location: ' . (BASE_URL ?: '') . '/');
            exit;
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if ($name === '' || $email === '' || $password === '' || $confirm === '') {
                $error = 'Todos los campos son obligatorios';
            } elseif ($password !== $confirm) {
                $error = 'Las contraseñas no coinciden';
            } else {
                $userModel = $this->loadModel('User');
                if ($userModel && !$userModel->findByEmail($email)) {
                    $ok = $userModel->register($name, $email, $password);
                    if ($ok) {
                        $success = 'Registro exitoso. Ya puedes iniciar sesión.';
                    } else {
                        $error = 'No se pudo completar el registro';
                    }
                } else {
                    $error = 'Este correo ya está registrado';
                }
            }
        }

        $this->render('auth/register', ['error' => $error, 'success' => $success]);
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ' . (BASE_URL ?: '') . '/login');
        exit;
    }
}
