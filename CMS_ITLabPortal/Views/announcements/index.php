<?php
$this->Title = '';

$announcement = $GLOBALS['announcement'];
$pathToImages = $GLOBALS['images'];
$countOfLikes = $GLOBALS['countLikes'];

$user = \core\Core::get()->session->get('user');
if (!empty($user) && isset($user['id'])) {
    $userInfo = \Models\Users::GetUserInfo($user['id']);
} else {
    $userInfo = null;
}
?>
<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Оголошення</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="lightbox.css">
    <style>
        .big-photo {
            width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .small-photos {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 10px 0;
        }

        .small-photos .col-md-3 {
            flex: 0 0 30%;
            max-width: 30%;
        }

        .small-photos img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .small-photos img:hover {
            transform: scale(1.1);
        }

        .announcement-header {
            margin-bottom: 20px;
        }

        .announcement-title {
            font-size: 2rem;
            font-weight: 600;
            color: #212529;
        }

        .announcement-text {
            font-size: 1.1rem;
            color: #6c757d;
        }

        .likes-count {
            font-weight: 600;
            color: #e74a3b;
        }

        .btn-favorite {
            border-radius: 20px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-favorite:hover {
            background-color: #e74a3b;
            color: white;
        }
    </style>
</head>
<body>
<section class="py-5" style="margin-top: -100px;">
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5 align-items-center">
            <div class="col-md-6">
                <div class="row">
                    <?php
                    $imageSrc = "../../../../src/resourses/no-photo.jpg";
                    $imagesPath = "./" . $pathToImages;

                    $realImagesPath = realpath($imagesPath);
                    $realImagesPath = str_replace('\\', '/', $realImagesPath);

                    $images = [];

                    if (!is_null($pathToImages) && is_dir($realImagesPath)) {
                        $scannedImages = array_values(array_diff(scandir($realImagesPath), ['.', '..']));

                        if (!empty($scannedImages)) {
                            $firstImage = reset($scannedImages);
                            $firstImageSrc = "../../../../../" . $pathToImages . "/" . $firstImage;
                            $images = array_slice($scannedImages, 1);
                        } else {
                            $firstImageSrc = '../../../../src/resourses/no-photo.jpg';
                            $images = [];
                        }
                    } else {
                        $firstImageSrc = '../../../../src/resourses/no-photo.jpg';
                        $images = [];
                    }
                    ?>
                    <div class="col-12 mb-3">
                        <img class="card-img-top big-photo" src="<?php echo($firstImageSrc) ?>" alt="Фото оголошення">
                    </div>
                    <div class="col-12">
                        <div class="row small-photos">
                            <?php foreach ($images as $image): ?>
                                <div class="col-md-3 mb-2">
                                    <a href="<?php echo "../../../../../" . $pathToImages . "/" . $image ?>"
                                       data-lightbox="gallery">
                                        <img class="card-img-top" src="<?php echo "../../../../../" . $pathToImages . "/" . $image ?>"
                                             alt="Фото галереї">
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" style="margin-top: -50px;">
                <?php if (isset($announcement)): ?>
                    <div class="announcement-header mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bolder d-inline"><?= htmlspecialchars($announcement->id) ?> ||</div>
                                <span class="text-muted"><?= htmlspecialchars($announcement->publicationDate) ?></span>
                            </div>
                            <div class="likes-count">
                                &#9829; <?= htmlspecialchars($countOfLikes[0]['count']) ?>
                            </div>
                        </div>
                    </div>
                    <h1 class="announcement-title"><?= htmlspecialchars($announcement->title) ?></h1>
                    <p class="announcement-text"><?= $announcement->text ?></p>
                <?php endif; ?>
                <?php if (\Models\Users::IsUserLogged()): ?>
                    <?php
                    $isFavorite = \Models\UserLikeAnnouncements::IsFavorite($userInfo[0]['id'], $announcement->id);
                    if (!$isFavorite): ?>
                        <div class="d-flex">
                            <a href="/announcements/addtofavorites/<?= $announcement->id ?>"
                               class="btn btn-sm btn-outline-secondary btn-favorite">Додати в обрані</a>
                        </div>
                    <?php endif; ?>
                    <?php if ($isFavorite): ?>
                        <div class="d-flex">
                            <a href="/announcements/removefromfavorites/<?= $announcement->id ?>"
                               class="btn btn-sm btn-outline-secondary btn-favorite">Видалити з обраних</a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script src="lightbox-plus-jquery.js"></script>
</body>
</html>
