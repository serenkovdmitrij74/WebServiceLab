<?php
session_start();
require_once 'auth.php';

if (!isAuthorized()) {
    redirect('login.php');
}

require_once 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->query("SELECT * FROM services");
    $services = $stmt->fetchAll();
    
    $json_data = json_encode($services, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
    $filename = 'services_exported.json';
    $temp_file = __DIR__ . '/temp_' . $filename;
    file_put_contents($temp_file, $json_data);
    
    $url = 'http://' . $_SERVER['HTTP_HOST'] . '/LrTest/worker.php';
    
    $post_data = ['file' => new CURLFile($temp_file)];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    unlink($temp_file);
    
    $result = json_decode($response, true);
    if ($result && isset($result['file_url']))
        $message = 'services_exported.json передан скрипту worker.php по протоколу HTTP методом POST. Ссылка для скачивания: <a href="' . $result['file_url'] . '" >' . $result['file_url'] . '</a>';
    else
        $message = "Ошибка при отправке файла";
}

require_once 'inc/header.php';
?>

<div class="container mt-5">
        <h4>Экспорт данных</h4>
        <p><strong>Формат:</strong> JSON</p>
        <p><strong>Куда:</strong> Внешнему скрипту worker.php</p>
        
        <?php if ($message): ?>
            <div class="alert alert-info"><?= $message ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <button type="submit" class="btn btn-primary">Экспорт</button>
        </form>
    </div>
</div>

<?php require_once 'inc/footer.php'; ?>