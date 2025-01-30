<?php
$this->Title = '';

$announcement = $GLOBALS['announcement'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .big-photo {
            width: 100%;
            height: auto;
        }

        .small-photos {
            max-height: 300px;
            overflow-y: auto;
        }

        .small-photos .col-md-4 {
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
                    <div class="col-12 mb-3">
                        <img class="card-img-top big-photo" src="https://dummyimage.com/600x700/dee2e6/6c757d.jpg" alt="...">
                    </div>
                    <div class="col-12">
                        <div class="row small-photos">
                            <div class="col-md-3 mb-2">
                                <img class="card-img-top" src="https://dummyimage.com/600x700/dee2e6/6c757d.jpg" alt="...">
                            </div>
                            <div class="col-md-3 mb-2">
                                <img class="card-img-top" src="https://dummyimage.com/600x700/dee2e6/6c757d.jpg" alt="...">
                            </div>
                            <div class="col-md-3 mb-2">
                                <img class="card-img-top" src="https://dummyimage.com/600x700/dee2e6/6c757d.jpg" alt="...">
                            </div>
                            <div class="col-md-3 mb-2">
                                <img class="card-img-top" src="https://dummyimage.com/600x700/dee2e6/6c757d.jpg" alt="...">
                            </div>
                            <div class="col-md-3 mb-2">
                                <img class="card-img-top" src="https://dummyimage.com/600x700/dee2e6/6c757d.jpg" alt="...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" style="margin-top: -250px;">
                <?php if (isset($announcement)): ?>
                    <div class="small mb-1"><?= htmlspecialchars($announcement->publicationDate) ?></div>
                    <h1 class="display-5 fw-bolder"><?= htmlspecialchars($announcement->title) ?></h1>
                    <div class="d-flex flex-wrap fs-5 mb-5">
                    </div>
                    <p class="lead"><?= htmlspecialchars($announcement->text) ?></p>
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
