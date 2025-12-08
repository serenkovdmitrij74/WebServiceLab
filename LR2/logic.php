<?php
// logic.php
require_once 'db.php';

// 1. Получаем список рабочих для выпадающего списка (фильтр)
// Запрос к вспомогательной таблице (требование ЛР)
$sqlWorkers = "SELECT id, name FROM workers";
$stmtWorkers = $pdo->query($sqlWorkers);
$workersList = $stmtWorkers->fetchAll();

// 2. Подготовка основного запроса для таблицы услуг
// Используем INNER JOIN, чтобы вывести ИМЯ рабочего, а не его ID
$sql = "SELECT services.*, workers.name AS worker_name 
        FROM services 
        JOIN workers ON services.worker_id = workers.id 
        WHERE 1=1"; 

$params = []; // Массив для безопасной передачи параметров (prepared statements)

// 3. Обработка фильтров (защита от SQL-инъекций)

// Фильтр по Названию (текстовое поле - поиск по вхождению LIKE)
if (!empty($_GET['filter_name'])) {
    $sql .= " AND services.name LIKE :name";
    $params[':name'] = '%' . $_GET['filter_name'] . '%';
}

// Фильтр по Рабочему (выпадающий список - точное совпадение по ID)
if (!empty($_GET['filter_worker'])) {
    $sql .= " AND services.worker_id = :worker_id";
    // Проверяем, что это число, как требуется в ЛР
    $params[':worker_id'] = (int)$_GET['filter_worker']; 
}

// Фильтр по Стоимости (числовое поле - точное совпадение)
if (!empty($_GET['filter_cost']) && is_numeric($_GET['filter_cost'])) {
    $sql .= " AND services.cost = :cost";
    // Проверяем, что это число, как требуется в ЛР
    $params[':cost'] = (int)$_GET['filter_cost']; 
}

// 4. Выполнение запроса
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$services = $stmt->fetchAll();
?>
