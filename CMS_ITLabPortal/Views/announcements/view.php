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
            background-color: #fff2e1;
        }
        .card:hover {
            background-color: #ffead2;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            margin: 0 4px;
            border: 1px solid #291000;
            border-radius: 4px;
            background-color: #602500;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .pagination a.active {
            background-color: #e67e22;
            color: white;
            border: 1px solid #e67e22;
        }

        .pagination a:hover:not(.active) {
            background-color: #f39c12;
            transform: scale(1.05);
        }

        .pagination a:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(241, 196, 15, 0.5);
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="row">
        <!-- Бокова панель -->
        <aside class="col-md-3">
            <form method="POST" action="" class="mb-3">
                <label for="sort" class="form-label">Сортувати за:</label>
                <select class="form-select" name="sort" id="sort" onchange="this.form.submit()">
                    <option value="">Всі</option>
                    <option value="yesterday">Вчора</option>
                    <option value="week">Тиждень</option>
                    <option value="month">Місяць</option>
                    <option value="year">Рік</option>
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