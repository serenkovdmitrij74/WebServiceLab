<?php
session_start();

require_once 'auth.php';

if (!isAuthorized()) {
    redirect('login.php');
}

require_once 'logic.php';
require_once 'inc/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="d-grid gap-3">
              <a href="export.php" class="btn btn-primary">Экспорт</a>
              <a href="import.php" class="btn btn-primary">Импорт</a>
          </div>
        </div>
    </div>
</div>

<?php require_once 'inc/footer.php'; ?>