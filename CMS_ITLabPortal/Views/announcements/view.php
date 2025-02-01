<?php
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
        .card {
            cursor: pointer;
        }
        .card:hover {
            background-color: #f8f9fa;
        }
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
            <form method="GET" action="/announcements/view" class="mb-3">
                <label for="start_date" class="form-label">Початкова дата:</label>
                <input type="date" class="form-control" name="start_date" id="start_date" value="<?= $_GET['start_date'] ?? '' ?>">

                <label for="end_date" class="form-label">Кінцева дата:</label>
                <input type="date" class="form-control" name="end_date" id="end_date" value="<?= $_GET['end_date'] ?? '' ?>">

                <label for="sort" class="form-label">Сортувати за:</label>
                <select class="form-select" name="sort" id="sort" onchange="this.form.submit()">
                    <option value="date_desc" <?= ($GLOBALS['sort'] === 'date_desc') ? 'selected' : '' ?>>Найновіші</option>
                    <option value="date_asc" <?= ($GLOBALS['sort'] === 'date_asc') ? 'selected' : '' ?>>Найстаріші</option>
                    <option value="likes_desc" <?= ($GLOBALS['sort'] === 'likes_desc') ? 'selected' : '' ?>>Найпопулярніші</option>
                    <option value="likes_asc" <?= ($GLOBALS['sort'] === 'likes_asc') ? 'selected' : '' ?>>Найменш популярні</option>
                </select>
            </form>

            <hr>


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
                            $imageSrc = "../../../../src/resourses/no-photo.jpg";
                            $imagesPath = "./" . $announcement['pathToImages'];

                            $realImagesPath = realpath($imagesPath);
                            $realImagesPath = str_replace('\\', '/', $realImagesPath);

                            if (!is_null($announcement['pathToImages']) && is_dir($realImagesPath)) {
                                $images = array_values(array_diff(scandir($realImagesPath), ['.', '..']));

                                if (!empty($images)) {
                                    $firstImage = reset($images);
                                    $imageSrc = "../../../../../" . $announcement['pathToImages'] . "/" . $firstImage;
                                }
                            }
                            ?>
                            <div class="card mb-4" onclick="window.location.href='/announcements/index/<?= $announcement['id'] ?>'">
                                <img class="card-img-top" alt="Зображення оголошення"
                                     style="height: 225px; width: 100%; display: block;"
                                     src="<?= htmlspecialchars($imageSrc) ?>" data-holder-rendered="true">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($announcement['title']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($announcement['text']) ?></p>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted"><?= htmlspecialchars($announcement['publicationDate']) ?></small>
                                        <div class="fw-bolder text-danger">
                                            &#9829; <?= htmlspecialchars($announcement['countLikes'][0]['count']) ?>
                                        </div>
                                    </div>
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