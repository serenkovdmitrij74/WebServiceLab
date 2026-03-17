<?php
session_start();
require_once 'auth.php';


if (isAuthorized()) {
    redirect('services.php');
}

$errors = [];
$old = $_POST; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $full_name = trim($_POST['full_name'] ?? '');
    $birth_date = $_POST['birth_date'] ?? '';
    $address = trim($_POST['address'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $interests = trim($_POST['interests'] ?? '');
    $vk_profile = trim($_POST['vk_profile'] ?? '');
    $blood_type = trim($_POST['blood_type'] ?? '');
    $rh_factor = $_POST['rh_factor'] ?? '';
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Введите корректный email';
    }
    
    $passwordErrors = validatePassword($password);
    $errors = array_merge($errors, $passwordErrors);
    
    if ($password !== $password_confirm) {
        $errors[] = 'Пароли не совпадают';
    }
    
    if (empty($full_name)) {
        $errors[] = 'ФИО обязательно для заполнения';
    }
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Пользователь с таким email уже зарегистрирован';
        }
    }
    
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("
            INSERT INTO users (email, password_hash, full_name, birth_date, address, gender, interests, vk_profile, blood_type, rh_factor) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $email,
            $password_hash,
            $full_name,
            $birth_date ?: null,
            $address ?: null,
            $gender ?: null,
            $interests ?: null,
            $vk_profile ?: null,
            $blood_type ?: null,
            $rh_factor ?: null
        ]);
        
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['user_email'] = $email;
        
        redirect('services.php');
    }
}

require_once 'inc/header.php';
?>

<div class="container">
    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
                <h4 class="mb-3">Регистрация</h4>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ФИО *</label>
                            <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Пароль *</label>
                            <input type="password" name="password" class="form-control" required>
                            <small>Минимум 7 символов, заглавные и строчные латиница, цифры, спецсимволы, пробел, дефис, подчеркивание</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Подтверждение пароля *</label>
                            <input type="password" name="password_confirm" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Дата рождения</label>
                            <input type="date" name="birth_date" class="form-control" value="<?= htmlspecialchars($_POST['birth_date'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Пол</label>
                            <select name="gender" class="form-select">
                                <option value="" <?= ($_POST['gender'] ?? '') == '' ? 'selected' : '' ?>>Не указано</option>
                                <option value="male" <?= ($_POST['gender'] ?? '') == 'male' ? 'selected' : '' ?>>Мужской</option>
                                <option value="female" <?= ($_POST['gender'] ?? '') == 'female' ? 'selected' : '' ?>>Женский</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Адрес</label>
                        <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Интересы</label>
                        <textarea name="interests" class="form-control" rows="3"><?= htmlspecialchars($_POST['interests'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ссылка на профиль ВК</label>
                        <input type="url" name="vk_profile" class="form-control" value="<?= htmlspecialchars($_POST['vk_profile'] ?? '') ?>">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Группа крови</label>
                            <select name="blood_type" class="form-select">
                                <option value="">Выберите группу крови</option>
                                <option value="A" <?= ($_POST['blood_type'] ?? '') == 'A' ? 'selected' : '' ?>>A</option>
                                <option value="B" <?= ($_POST['blood_type'] ?? '') == 'B' ? 'selected' : '' ?>>B</option>
                                <option value="AB" <?= ($_POST['blood_type'] ?? '') == 'AB' ? 'selected' : '' ?>>AB</option>
                                <option value="0" <?= ($_POST['blood_type'] ?? '') == '0' ? 'selected' : '' ?>>0</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Резус-фактор</label>
                            <select name="rh_factor" class="form-select">
                                <option value="">Не указано</option>
                                <option value="positive" <?= ($_POST['rh_factor'] ?? '') == 'positive' ? 'selected' : '' ?>>Положительный</option>
                                <option value="negative" <?= ($_POST['rh_factor'] ?? '') == 'negative' ? 'selected' : '' ?>>Отрицательный</option>
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class=" btn btn-primary w-100">Зарегистрироваться</button>
                    <div class="mt-2 text-center">
                        <a href="login.php" class="text-decoration-none">Войти</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'inc/footer.php'; ?>
