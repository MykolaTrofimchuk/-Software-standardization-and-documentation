<?php
$this->Title = 'Список оголошень';
$announcements = \Models\Announcements::SelectAll();
?>
<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->Title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            color: #007bff;
            text-decoration: none;
            padding: 8px 16px;
            margin: 0 4px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .pagination a.active {
            background-color: #007bff;
            color: white;
            border: 1px solid #007bff;
        }
        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="row">
        <!-- Бокова панель -->
        <aside class="col-md-3">
            <form method="GET" action="/announcements/filter">
                <div class="mb-3">
                    <label for="period" class="form-label">Оберіть період:</label>
                    <select class="form-select" name="period" id="period">
                        <option value="day">Останній день</option>
                        <option value="week">Останній тиждень</option>
                        <option value="month">Останній місяць</option>
                        <option value="year">Останній рік</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm w-100">Застосувати</button>
            </form>

            <hr>

<!--            <h5>Рубрики</h5>-->
<!--            <ul class="list-group">-->
<!--                <li class="list-group-item"><a href="#">Анонси (505)</a></li>-->
<!--                <li class="list-group-item"><a href="#">Навчання (1614)</a></li>-->
<!--                <li class="list-group-item"><a href="#">Наукова діяльність (1095)</a></li>-->
<!--                <li class="list-group-item"><a href="#">Новини (1551)</a></li>-->
<!--                <li class="list-group-item"><a href="#">Оголошення (1156)</a></li>-->
<!--                <li class="list-group-item"><a href="#">Події (1893)</a></li>-->
<!--                <li class="list-group-item"><a href="#">Різне (2331)</a></li>-->
<!--            </ul>-->
<!---->
<!--            <hr>-->

            <h5>Архів</h5>
            <select class="form-select">
                <option>Обрати місяць</option>
                <option>Січень 2025</option>
                <option>Грудень 2024</option>
                <option>Листопад 2024</option>
            </select>
        </aside>

        <!-- Основний контент (один стовпець) -->
        <div class="col-md-9">
            <?php if (!empty($announcements)): ?>
                <div class="row">
                    <div class="col-md-12">
                        <?php foreach ($GLOBALS['announcements'] as $announcement): ?>
                            <?php
                            // Default image path
                            $imageSrc = "../../../../src/resourses/no-photo.jpg";
                            $imagesPath = "./" . $announcement['pathToImages'];

                            // Use realpath to debug the path issue
                            $realImagesPath = realpath($imagesPath);
                            $realImagesPath = str_replace('\\', '/', $realImagesPath);

                            if (!is_null($announcement['pathToImages']) && is_dir($realImagesPath)) {
                                $images = scandir($realImagesPath);
                                $images = array_diff($images, array('.', '..'));
                                $firstImage = !empty($images) ? reset($images) : null;
                                $imageSrc = "../../../../../". $announcement['pathToImages'] . "/" . $firstImage;
                            }
                            ?>
                            <div class="card mb-4">
                                <img class="card-img-top" alt="<?php echo($imageSrc) ?>" style="height: 225px; width: 100%; display: block;" src="<?php echo($imageSrc) ?>" data-holder-rendered="true">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($announcement['title']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($announcement['text']) ?></p>
                                    <a href="/announcements/index/<?= $announcement['id'] ?>" class="btn btn-sm btn-outline-primary">Переглянути</a>
                                    <small class="text-muted d-block mt-2"><?= htmlspecialchars($announcement['publicationDate']) ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-center">Немає доступних оголошень.</p>
            <?php endif; ?>
        </div>
        <div class="pagination">
            <?php for ($i = 1; $i <= $GLOBALS['totalPages']; $i++): ?>
                <a href="/announcements/view/<?= $i ?>" class="<?= $i == $GLOBALS['currentPage'] ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</div>
</body>
</html>