<?php
/**
 * Сторінка профілю користувача
 *
 * Цей файл відповідає за відображення інформації про користувача, зокрема його профілю, зображення та іншої інформації.
 * @var string $error_message Повідомлення про помилку, яке може бути виведене на сторінці
 * @var string $this->Title Заголовок сторінки, встановлюється як "Профіль користувача"
 * @var array $userInfo Містить інформацію про користувача, отриману з бази даних
 * @var string $userImage Шлях до зображення користувача або шлях до зображення за замовчуванням, якщо зображення не задано
 */
$this->Title = 'Профіль користувача';

$userInfo = \Models\Users::GetUserInfo(\core\Core::get()->session->get('user')['id']);
$userImage = isset($userInfo[0]['image_path']) ? $userInfo[0]['image_path'] : '../../../src/resourses/user-default.png';
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
        .custom-file-upload {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .custom-file-upload:hover {
            background-color: #0056b3;
        }

        .custom-file-upload i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="main-body">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/users">User</a></li>
                <li class="breadcrumb-item active" aria-current="page">User Profile</li>
            </ol>
        </nav>
        <!-- /Breadcrumb -->

        <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img src="<?php echo "../". $userImage; ?>" alt="User" class="rounded-circle" width="150">
                            <div class="mt-3">
                                <h4><?php echo $userInfo[0]['first_name'] ." ". $userInfo[0]['last_name']; ?></h4>
                                <p class="text-secondary mb-1 "><?php echo "ID ". $userInfo[0]['id']; ?></p>
                                <p class="text-muted font-size-sm">
                                    <?php
                                    switch ($userInfo[0]['role']) :
                                        case 'admin':
                                            echo "Користувач (адміністратор)";
                                            break;
                                        default:
                                            echo "Користувач";
                                            break;
                                    endswitch;
                                    ?>
                                </p>
                                <form action="/users/editphoto" method="post" enctype="multipart/form-data">
                                    <button type="button" class="custom-file-upload" onclick="document.getElementById('file-upload').click();">
                                        <i class="fas fa-cloud-upload-alt"></i> Змінити Фото
                                    </button>
                                    <input id="file-upload" name="file-upload" type="file" accept="image/jpeg, image/png, image/gif" style="display: none;" onchange="handleFileChange()"/>
                                    <button id="upload-button" type="submit" class="btn btn-primary" style="display: none;">Завантажити зміни</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram mr-2 icon-inline text-danger"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>Instagram</h6>
                            <span class="text-secondary">bootdey</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter mr-2 icon-inline text-info"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>Twitter</h6>
                            <span class="text-secondary">@bootdey</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook mr-2 icon-inline text-primary"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>Facebook</h6>
                            <span class="text-secondary">bootdey</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Прізвище</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $userInfo[0]['last_name']; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Ім'я</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $userInfo[0]['first_name']; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Email</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $userInfo[0]['email']; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <a class="btn btn-info" target="__blank" href="/users/edit">Змінити дані</a>
                            </div>
                            <div class="col-sm-3">
                                <a class="btn btn-info" target="__blank" href="/users/editpassword">Змінити пароль</a>
                            </div>
                        </div>
                    </div>
                </div>

<!--                <div class="row gutters-sm">-->
<!--                    <div class="col-sm-6 mb-3">-->
<!--                        <div class="card h-100">-->
<!--                            <div class="card-body">-->
<!--                                <h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">assignment</i>Project Status</h6>-->
<!--                                <small>Web Design</small>-->
<!--                                <div class="progress mb-3" style="height: 5px">-->
<!--                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>-->
<!--                                </div>-->
<!--                                <small>Website Markup</small>-->
<!--                                <div class="progress mb-3" style="height: 5px">-->
<!--                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>-->
<!--                                </div>-->
<!--                                <small>One Page</small>-->
<!--                                <div class="progress mb-3" style="height: 5px">-->
<!--                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 89%" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>-->
<!--                                </div>-->
<!--                                <small>Mobile Template</small>-->
<!--                                <div class="progress mb-3" style="height: 5px">-->
<!--                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>-->
<!--                                </div>-->
<!--                                <small>Backend API</small>-->
<!--                                <div class="progress mb-3" style="height: 5px">-->
<!--                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 66%" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-sm-6 mb-3">-->
<!--                        <div class="card h-100">-->
<!--                            <div class="card-body">-->
<!--                                <h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">assignment</i>Project Status</h6>-->
<!--                                <small>Web Design</small>-->
<!--                                <div class="progress mb-3" style="height: 5px">-->
<!--                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>-->
<!--                                </div>-->
<!--                                <small>Website Markup</small>-->
<!--                                <div class="progress mb-3" style="height: 5px">-->
<!--                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>-->
<!--                                </div>-->
<!--                                <small>One Page</small>-->
<!--                                <div class="progress mb-3" style="height: 5px">-->
<!--                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 89%" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>-->
<!--                                </div>-->
<!--                                <small>Mobile Template</small>-->
<!--                                <div class="progress mb-3" style="height: 5px">-->
<!--                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>-->
<!--                                </div>-->
<!--                                <small>Backend API</small>-->
<!--                                <div class="progress mb-3" style="height: 5px">-->
<!--                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 66%" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->



            </div>
        </div>

    </div>
</div>
<script>
    function handleFileChange() {
        var fileUpload = document.getElementById('file-upload');
        var uploadButton = document.getElementById('upload-button');
        if (fileUpload.files && fileUpload.files.length > 0) {
            uploadButton.style.display = 'inline-block';
        } else {
            uploadButton.style.display = 'none';
        }
    }
</script>
</body>
</html>
