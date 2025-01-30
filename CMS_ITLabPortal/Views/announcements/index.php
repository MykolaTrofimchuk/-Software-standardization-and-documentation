<?php
$this->Title = '';

$announcement = $GLOBALS['announcement'];
$pathToImages = $GLOBALS['images'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="lightbox.js"></script>
    <style>
        .big-photo {
            width: 100%;
            height: auto;
        }

        .small-photos {
            max-height: 300px;
            overflow-y: auto;
        }

        .small-photos {
            flex: 0 0 calc(33.33% - 10px);
            margin-bottom: 10px;
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
                    // Default image path
                    $imageSrc = "../../../../src/resourses/no-photo.jpg";
                    $imagesPath = "./" . $pathToImages;

                    // Use realpath to debug the path issue
                    $realImagesPath = realpath($imagesPath);
                    $realImagesPath = str_replace('\\', '/', $realImagesPath);

                    if (!is_null($pathToImages) && is_dir($realImagesPath)) {
                        $images = scandir($realImagesPath);
                        $images = array_diff($images, array('.', '..'));
                        $firstImage = !empty($images) ? reset($images) : null;
                        $firstImageSrc = "../../../../../" . $pathToImages . "/" . $firstImage;
                        array_shift($images);
                    }else{
                        $images = ['../../../src/resourses/no-photo.jpg'];
                        $firstImageSrc = '../../../src/resourses/no-photo.jpg';
                    }
                    ?>
                    <div class="col-12 mb-3">
                        <img class="card-img-top big-photo" src="<?php echo($firstImageSrc) ?>" alt="...">
                    </div>
                    <div class="col-12">
                        <div class="row small-photos">
                            <?php foreach ($images as $image): ?>
                                <div class="col-md-3 mb-2">
                                    <a href="<?php echo "../../../../../" . $pathToImages . "/" . $image ?>"
                                       data-lightbox="gallery">
                                        <img class="card-img-top"
                                             src="<?php echo "../../../../../" . $pathToImages . "/" . $image ?>"
                                             alt="...">
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" style="margin-top: -50px;">
                <?php if (isset($announcement)): ?>
                    <div class="small mb-1"><?= htmlspecialchars($announcement->publicationDate) ?> <?= htmlspecialchars($announcement->id) ?></div>
                    <h1 class="display-5 fw-bolder"><?= htmlspecialchars($announcement->title) ?></h1>
                    <p class="lead"><?= $announcement->text ?></p>
                <?php endif; ?>
                <div class="d-flex">
                    <button class="btn btn-outline-dark flex-shrink-0" type="button">
                        <i class="bi-cart-fill me-1"></i>
                        В обрані
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

</body>
</html>
