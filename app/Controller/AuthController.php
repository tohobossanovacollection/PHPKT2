<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\User;
use App\Repositories\UserRepository;

final class AuthController extends BaseController
{
    public function __construct(private UserRepository $userRepository, private string $baseUrl = '')
    {
    }

    public function showLogin(): void
    {
        if ($this->currentUser() !== null) {
            $this->redirect($this->baseUrl . '/');
            return;
        }

        $this->render('auth/login', [
            'baseUrl' => $this->baseUrl,
            'error' => $_SESSION['error'] ?? null,
            'success' => $_SESSION['success'] ?? null,
            'oldUsername' => (string) ($_SESSION['old_username'] ?? ''),
        ]);

        unset($_SESSION['error'], $_SESSION['success'], $_SESSION['old_username']);
    }

    public function login(): void
    {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $_SESSION['old_username'] = $username;

        if ($username === '' || $password === '') {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ tài khoản và mật khẩu.';
            $this->redirect($this->baseUrl . '/login');
            return;
        }

        $user = $this->userRepository->findByUsername($username);
        if ($user === null || !password_verify($password, $user->passwordHash)) {
            $_SESSION['error'] = 'Thông tin đăng nhập không chính xác.';
            $this->redirect($this->baseUrl . '/login');
            return;
        }

        $_SESSION['user'] = [
            'id' => $user->id,
            'username' => $user->username,
        ];

        unset($_SESSION['old_username']);
        $_SESSION['success'] = 'Đăng nhập thành công.';
        $this->redirect($this->baseUrl . '/');
    }

    public function showRegister(): void
    {
        if ($this->currentUser() !== null) {
            $this->redirect($this->baseUrl . '/');
            return;
        }

        $this->render('auth/register', [
            'baseUrl' => $this->baseUrl,
            'error' => $_SESSION['error'] ?? null,
            'success' => $_SESSION['success'] ?? null,
            'oldUsername' => (string) ($_SESSION['old_username'] ?? ''),
        ]);

        unset($_SESSION['error'], $_SESSION['success'], $_SESSION['old_username']);
    }

    public function register(): void
    {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

        $_SESSION['old_username'] = $username;

        if ($username === '' || $password === '' || $confirmPassword === '') {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin đăng ký.';
            $this->redirect($this->baseUrl . '/register');
            return;
        }

        if (mb_strlen($username) < 4 || mb_strlen($username) > 32) {
            $_SESSION['error'] = 'Tên tài khoản phải từ 4 đến 32 ký tự.';
            $this->redirect($this->baseUrl . '/register');
            return;
        }

        if (!preg_match('/^[a-zA-Z0-9_\.]+$/', $username)) {
            $_SESSION['error'] = 'Tên tài khoản chỉ được chứa chữ, số, dấu gạch dưới hoặc dấu chấm.';
            $this->redirect($this->baseUrl . '/register');
            return;
        }

        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Mật khẩu cần tối thiểu 6 ký tự.';
            $this->redirect($this->baseUrl . '/register');
            return;
        }

        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Mật khẩu xác nhận không khớp.';
            $this->redirect($this->baseUrl . '/register');
            return;
        }

        if ($this->userRepository->findByUsername($username) !== null) {
            $_SESSION['error'] = 'Tài khoản đã tồn tại.';
            $this->redirect($this->baseUrl . '/register');
            return;
        }

        $user = new User(
            id: bin2hex(random_bytes(16)),
            username: $username,
            passwordHash: password_hash($password, PASSWORD_DEFAULT),
            createdAt: date('Y-m-d H:i:s')
        );

        $this->userRepository->save($user);

        $_SESSION['user'] = [
            'id' => $user->id,
            'username' => $user->username,
        ];

        unset($_SESSION['old_username']);
        $_SESSION['success'] = 'Tạo tài khoản thành công.';

        $this->redirect($this->baseUrl . '/');
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        $_SESSION['success'] = 'Bạn đã đăng xuất.';
        $this->redirect($this->baseUrl . '/login');
    }
}
