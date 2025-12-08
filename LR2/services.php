<?php

// services.php - подключаем логику

// Обязательно убедитесь, что logic.php лежит в той же папке!

require_once 'logic.php';

?>

<!doctype html>

<html lang="ru">

  <head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Каталог услуг УК «Жэк» - ЛР2</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="style.css">

    <style>

        /* Стили для картинки, так как это требование ЛР (фиксированная ширина) */

        .service-img {

            width: 100px; /* Уменьшаем для компактности в таблице */

            height: auto;

            object-fit: cover;

        }

    </style>

  </head>

  <body class="small">

    <header>

      <nav class="navbar navbar-expand-lg bg-white">

        <div class="container">

          <a class="navbar-brand">

            <img src="./inc/images/favicon-32x32.png" alt="logo"> минжкх

          </a>

          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>

          </button>

          <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav me-auto">

              <li class="nav-item dropdown">

                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">

                  Компании

                </a>

                <ul class="dropdown-menu">

                  <li><a class="dropdown-item" href="#">По регионам</a></li>

                  <li><a class="dropdown-item" href="#">По городам</a></li>

                </ul>

              </li>

              <li class="nav-item">

                <a class="nav-link" href="#">Жилой фонд</a>

              </li>

              <li class="nav-item">

                <a class="nav-link" href="#">Новости</a>

              </li>

              <li class="nav-item">

                <a class="nav-link" href="#">API</a>

              </li>

              <li class="nav-item">

                <a class="nav-link" href="#">О проекте</a>

              </li>

            </ul>

            <div class="d-flex">

              <a class="btn btn-outline-primary" href="#">

                <i class="bi bi-search"></i>

              </a>

            </div>

          </div>

        </div>

      </nav>





    <main class="main">

      <div class="container my-4">

       

        <div class="col">

          <h4>Управляющие компании ЖКХ и ТСЖ</h4>

          <p>МинЖКХ - некоммерческий общественный инициативный проект повышения общественной осведомлённости в области функционирования управляющих компаний и ТСЖ. Мы полны решимости побудить управляющие компании к поиску и реализации путей решения проблем в сфере жилищно-коммунального хозяйства и оптимизации расходов на содержание жилого фонда. За проектом стоят неравнодушные граждане, желающие порядка в сфере жилищно-коммунального хозяйства и связанных с ним отраслей. Вы тоже можете быть одним из них. Мы стремимся к эффективному использованию информации во имя прогресса общества и поддержки государства в раскрытии информации для большей доступности, прозрачности и подотчетности.</p>

        </div>

        <h4 class="card-title mt-4 mb-3 pb-2 border-bottom">Фильтрация результата поиска</h4>



        <div class="p-3 mb-4 border rounded bg-light">

          <form action="services.php" method="GET" class="row g-3 align-items-end">

            <div class="col-md-4">

              <label for="filter_name" class="form-label">Название услуги:</label>

              <input type="text" class="form-control form-control-sm" name="filter_name"

                     value="<?= htmlspecialchars($_GET['filter_name'] ?? '') ?>"

                     placeholder="Поиск по вхождению">

            </div>



            <div class="col-md-3">

              <label for="filter_worker" class="form-label">Рабочий:</label>

              <select class="form-select form-select-sm" name="filter_worker">

                <option value="">-- Все рабочие --</option>

                <?php foreach ($workersList as $worker): ?>

                  <option value="<?= $worker['id'] ?>"

                    <?php

                        // Сохранение примененного фильтра

                        if (isset($_GET['filter_worker']) && $_GET['filter_worker'] == $worker['id']) {

                            echo 'selected';

                        }

                    ?>>

                    <?= htmlspecialchars($worker['name']) ?>

                  </option>

                <?php endforeach; ?>

              </select>

            </div>



            <div class="col-md-3">

              <label for="filter_cost" class="form-label">Стоимость (точно):</label>

              <input type="number" class="form-control form-control-sm" name="filter_cost"

                     value="<?= htmlspecialchars($_GET['filter_cost'] ?? '') ?>"

                     placeholder="Точное значение">

            </div>



            <div class="col-md-2 d-flex justify-content-end">

              <button type="submit" class="btn btn-primary btn-sm me-2">Применить</button>

              <a href="services.php" class="btn btn-danger btn-sm">Сброс</a>

            </div>

          </form>

        </div>



        <?php if (count($services) > 0): ?>

          <table class="table table-striped table-hover table-bordered table-sm">

            <thead class="table-success">

              <tr>

                <th>Фото</th>

                <th>Название услуги</th>

                <th>Кто выполняет (Рабочий)</th>

                <th>Описание</th>

                <th>Стоимость (руб.)</th>

              </tr>

            </thead>

            <tbody>

              <?php foreach ($services as $row): ?>

                <tr>

                  <td>

                    <?php $img = 'images/' . htmlspecialchars($row['img_path']); ?>

                    <img src="<?= $img ?>" alt="Фото услуги" class="service-img">

                  </td>

                  <td><?= htmlspecialchars($row['name']) ?></td>

                  <td><?= htmlspecialchars($row['worker_name']) ?></td>

                  <td><?= htmlspecialchars($row['description']) ?></td>

                  <td><?= htmlspecialchars($row['cost']) ?></td>

                </tr>

              <?php endforeach; ?>

            </tbody>

          </table>

        <?php else: ?>

          <div class="alert alert-warning" role="alert">

            По вашему запросу ничего не найдено.

          </div>

        <?php endif; ?>

    </main>
</body>





     <footer class="footer bg-dark text-light py-4 border-top border-4 border-primary">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h6><i class="bi bi-building"></i> Общественный проект «МинЖКХ.РУ»</h6>
                    <p class="text-light-50">Сайт общественного инициативного проекта по раскрытию информации о деятельности управляющих компаний и товариществ собственников жилья</p>
                </div>
                
                <div class="col-md-5">
                    <h6><i class="bi bi-building"></i> Министерства и ведомства</h6>
                    <ul class="list-unstyled">
                        <li>Роспотребнадзор</li>
                        <li>Министерство строительства и ЖКХ</li>
                        <li>Фонд единого института развития в жилищной сфере</li>
                        <li>Некоммерческое партнерство «ЖКХ Развитие»</li>
                    </ul>
                </div>
            </div>
            
            <hr class="border-secondary">
            
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <p class="mb-0 text-light-50">&copy; 2015 - 2025 <a href="#" class="text-decoration-none text-light">МинЖКХ.РУ</a></p>
                
                <ul class="nav">
                    <li class="nav-item px-2">О проекте</li>
                    <li class="nav-item px-2">Новости<li>
                    <li class="nav-item px-2">Компании</li>
                    <li class="nav-item px-2">API</li>
                    <li class="nav-item px-2">Обратная связь</a></li>
                </ul>
            </div>
        </div>
    </footer>




  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</html>