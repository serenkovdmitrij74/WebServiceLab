<?php
session_start();
require_once 'auth.php';

if (!isAuthorized()) {
    redirect('login.php');
}

require_once 'db.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'] ?? '';
    
    if (empty($url)) {
        $error = 'Введите ссылку';
    } else {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $json_data = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code != 200) {
            $error = 'Не удалось скачать файл';
        } else {
            $services = json_decode($json_data, true);
            
            if ($services === null) {
                $error = 'Ошибка в JSON файле';
            } else {
                $pdo->exec("DROP TABLE IF EXISTS services_imported");
                
                $pdo->exec("
                    CREATE TABLE services_imported (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        name VARCHAR(255),
                        description TEXT,
                        cost INT,
                        worker_id INT
                    )
                ");
                
                $count = 0;
                foreach ($services as $item) {
                    $stmt = $pdo->prepare("INSERT INTO services_imported (name, description, cost, worker_id) VALUES (?, ?, ?, ?)");
                    $stmt->execute([
                        $item['name'] ?? '',
                        $item['description'] ?? '',
                        $item['cost'] ?? 0,
                        $item['worker_id'] ?? null
                    ]);
                    $count++;
                }
                
                $message = "Файл с данными получен из внешней ссылки и обработан. Создана таблица services_imported и $count записей в ней";
            }
        }
    }
}

require_once 'inc/header.php';
?>

<div class="container mt-5">
    <h4>Импорт данных</h4>
    <p><strong>Формат:</strong> JSON</p>
    <p><strong>Откуда:</strong> По внешней ссылке</p>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Ссылка на JSON файл:</label>
            <input type="url" name="url" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Импорт</button>
    </form>
</div>

<?php require_once 'inc/footer.php'; ?>