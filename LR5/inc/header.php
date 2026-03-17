<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Каталог услуг УК «Жэк» - ЛР3</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>.service-img {width: 100px;height: auto;object-fit: cover;}</style>
</head>
<body class="small">
    <header>
        <nav class="navbar navbar-expand-lg bg-white">
            <div class="container">
                <a class="navbar-brand" href="services.php">
                    <img src="./inc/image/favicon-32x32.png" alt="logo"> минжкх
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Компании</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">По регионам</a></li>
                                <li><a class="dropdown-item" href="#">По городам</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#">Жилой фонд</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Новости</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">API</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">О проекте</a></li>
                    </ul>

                    <div class="align-items-center">
                        <?php
                        if (isset($_SESSION['user_id'])) {
                            $user_email = $_SESSION['user_email'] ?? '';
                            echo '<span class="me-3">Вы вошли как <strong>' . htmlspecialchars($user_email) . '</strong></span>';
                            echo '<a href="logout.php" class="btn btn-outline-danger btn-sm">Выйти</a>';
                        } else {
                            echo '<span>Вы не авторизованы. </span>';
                            echo '<a href="login.php">Войдите</a>';
                            echo '<span> или </span>';
                            echo '<a href="register.php">зарегистрируйтесь</a>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>