<?php
session_start();
require_once 'auth.php';

if (isAuthorized()) {
    redirect('services.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Заполните все поля';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user) {
            $error = 'Логина нет в системе';
        } elseif (!password_verify($password, $user['password_hash'])) {
            $error = 'Неверный пароль';
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            redirect('services.php');
        }
    }
}

require_once 'inc/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h4 class="mb-3">Вход в аккаунт</h4>  
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Пароль</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class=" btn btn-primary w-100">Войти</button>
                <div class="mt-2 text-center">
                    <a href="register.php" class="text-decoration-none">Зарегистрироваться</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'inc/footer.php'; ?>