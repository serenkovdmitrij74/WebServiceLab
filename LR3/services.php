<?php
session_start();

require_once 'auth.php';

if (!isAuthorized()) {
    redirect('login.php');
}

require_once 'logic.php';
require_once 'inc/header.php';
?>

<main class="main">
      <div class="container">

        <div class="col">
          <h4>Управляющие компании ЖКХ и ТСЖ</h4>
          <p>МинЖКХ - некоммерческий общественный инициативный проект повышения общественной осведомлённости в области функционирования управляющих компаний и ТСЖ. Мы полны решимости побудить управляющие компании к поиску и реализации путей решения проблем в сфере жилищно-коммунального хозяйства и оптимизации расходов на содержание жилого фонда. За проектом стоят неравнодушные граждане, желающие порядка в сфере жилищно-коммунального хозяйства и связанных с ним отраслей. Вы тоже можете быть одним из них. Мы стремимся к эффективному использованию информации во имя прогресса общества и поддержки государства в раскрытии информации для большей доступности, прозрачности и подотчетности.</p>
        </div>
        <h4 class="card-title mt-4 mb-3 pb-2 border-bottom">Фильтрация результата поиска</h4>

        <div class="p-3 mb-4 border rounded bg-light">
  <form action="services.php" method="GET">
    
    <div class="mb-3">
      <label class="form-label">Название услуги:</label>
      <input type="text" class="form-control" name="filter_name"
             value="<?= htmlspecialchars($_GET['filter_name'] ?? '') ?>"
             placeholder="Поиск по вхождению">
    </div>
    
    <div class="mb-3">
      <label class="form-label">Описание услуги:</label>
      <input type="text" class="form-control" name="filter_description"
             value="<?php echo htmlspecialchars($_GET['filter_description'] ?? '') ?>"
             placeholder="Ключевые слова в описании">
    </div>

    <div class="mb-3">
      <label class="form-label">Рабочий:</label>
      <select class="form-select" name="filter_worker">
        <option value="">Все рабочие</option>
        <?php foreach ($workersList as $worker): ?>
          <option value="<?= $worker['id'] ?>"
            <?php
              if (isset($_GET['filter_worker']) && $_GET['filter_worker'] == $worker['id']) {
                echo 'selected';
              }
            ?>>
            <?= htmlspecialchars($worker['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    
    <div class="mb-3">
      <label class="form-label">Стоимость (руб.):</label>
      <div class="row g-2">
        <div class="col">
          <input type="number" class="form-control" name="filter_cost_min"
                 value="<?= htmlspecialchars($_GET['filter_cost_min'] ?? '') ?>"
                 placeholder="От" min="0">
        </div>
        <div class="col">
          <input type="number" class="form-control" name="filter_cost_max"
                 value="<?= htmlspecialchars($_GET['filter_cost_max'] ?? '') ?>"
                 placeholder="До" min="0">
        </div>
      </div>
    </div>
    
    <div class="d-flex justify-content-end">
      <button class="btn btn-primary me-2">Применить</button>
      <a href="services.php" class="btn btn-danger">Сброс</a>
    </div>
  </form>
</div>
        <?php if (count($services) > 0): ?>
          <table class="table">
              <tr>
                <th>Фото</th>
                <th>Название услуги</th>
                <th>Кто выполняет (Рабочий)</th>
                <th>Описание</th>
                <th>Стоимость (руб.)</th>
              </tr>
              <?php foreach ($services as $row): ?>
                <tr>
                  <td>
                    <?php $img = 'inc/image/' . htmlspecialchars($row['img_path']); ?>
                    <img src="<?= $img ?>" alt="Фото услуги" class="service-img">
                  </td>
                  <td><?php echo htmlspecialchars($row['name']) ?></td>
                  <td><?= htmlspecialchars($row['worker_name']) ?></td>
                  <td><?= htmlspecialchars($row['description']) ?></td>
                  <td><?= htmlspecialchars($row['cost']) ?></td>
                </tr>
              <?php endforeach; ?>
          </table>

        <?php else: ?>
          <div class="alert alert-warning" role="alert">
            По вашему запросу ничего не найдено.
          </div>
        <?php endif; ?>
    </main>

<?php require_once 'inc/footer.php'; ?>