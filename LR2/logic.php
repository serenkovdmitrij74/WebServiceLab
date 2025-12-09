<?php

require_once 'db.php';

$sqlWorkers = "SELECT id, name FROM workers";
$stmtWorkers = $pdo->query($sqlWorkers);
$workersList = $stmtWorkers->fetchAll();

$sql = "SELECT services.*, workers.name AS worker_name 
        FROM services 
        JOIN workers ON services.worker_id = workers.id 
        WHERE 1=1"; 

$params = []; 

if (!empty($_GET['filter_name'])) {
    $sql .= " AND services.name LIKE :name";
    $params[':name'] = '%' . $_GET['filter_name'] . '%';
}

if (!empty($_GET['filter_description'])) {
    $sql .= " AND services.description LIKE :description";
    $params[':description'] = '%' . $_GET['filter_description'] . '%';
}

if (!empty($_GET['filter_worker'])) {
    $sql .= " AND services.worker_id = :worker_id";
    $params[':worker_id'] = (int)$_GET['filter_worker']; 
}

if (!empty($_GET['filter_cost_min']) && is_numeric($_GET['filter_cost_min'])) {
    $sql .= " AND services.cost >= :cost_min";
    $params[':cost_min'] = (int)$_GET['filter_cost_min'];
}

if (!empty($_GET['filter_cost_max']) && is_numeric($_GET['filter_cost_max'])) {
    $sql .= " AND services.cost <= :cost_max";
    $params[':cost_max'] = (int)$_GET['filter_cost_max'];
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$services = $stmt->fetchAll();
?>
