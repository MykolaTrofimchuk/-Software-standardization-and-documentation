<?php

namespace Controllers;

use core\Controller;
use DateTime;
use Models\Announcements;
use Models\Files;
use Models\UserLikeAnnouncements;
use Models\Users;

class AnnouncementsController extends Controller
{
    public function actionAdd()
    {
        if ($this->isPost) {
            $userId = \core\Core::get()->session->get('user')['id'];

            if (!Users::IsAdmin($userId)) {
                $this->redirect('/');
            }

            if (strlen($this->post->title) === 0) {
                $this->addErrorMessage('Заголовок не вказаний!');
            }
            if (strlen($this->post->text) === 0) {
                $this->addErrorMessage('Текст не вказано!');
            }
            $publicationDate = date('Y-m-d H:i:s'); // Assuming publication date is today

            if (!$this->isErrorMessagesExists()) {
                Announcements::AddAnnouncement(
                    $this->post->title,
                    $this->post->text,
                    $publicationDate
                );

                $announcementId = Announcements::lastInsertedId();

                // Обробка завантажених фотографій
                if (isset($_FILES['files'])) {
                    $uploadDir = "src/database/announcements/announcement" . $announcementId . "/";
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    foreach ($_FILES['files']['tmp_name'] as $index => $tmpName) {
                        if ($_FILES['files']['error'][$index] === UPLOAD_ERR_OK) {
                            $uploadFile = $uploadDir . $index . '.' . pathinfo($_FILES['files']['name'][$index], PATHINFO_EXTENSION);

                            if (exif_imagetype($tmpName) !== false) {
                                move_uploaded_file($tmpName, $uploadFile);
                            } else {
                                $this->addErrorMessage('Файл ' . $_FILES['files']['name'][$index] . ' не є зображенням.');
                            }
                        } else {
                            $this->addErrorMessage('Не вдалося завантажити файл: ' . $_FILES['files']['name'][$index]);
                        }
                    }

                    Files::AddImages($announcementId, $uploadDir);
                }

                $this->redirect('/announcements/addsuccess');
            }
        } else {
            if (!Users::IsUserLogged()) {
                $this->redirect('/');
            }
            return $this->render();
        }

        return $this->render();
    }

    public function actionEdit()
    {
        if (!Users::IsUserLogged()) {
            $this->redirect('/');
        }

        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $id = end($queryParts);

        if ($id !== null) {
            $announcementId = $id;
            $userId = \core\Core::get()->session->get('user')['id'];

            $announcementInfo = Announcements::findByCondition(['id' => $announcementId]);
            if (empty($announcementInfo) || !Users::IsAdmin($userId)) {
                $this->redirect('/');
            }

            if ($this->isPost) {
                if (strlen($this->post->title) === 0) {
                    $this->addErrorMessage('Заголовок не вказаний!');
                }
                if (strlen($this->post->text) === 0) {
                    $this->addErrorMessage('Текст опису не вказано!');
                }

                if (!$this->isErrorMessagesExists()) {

                    $announcementDataToUpdate = [
                        'title' => $this->post->title,
                        'text' => $this->post->text
                    ];

                    $resUpdateAnn = Announcements::EditAnnouncementInfo($announcementId, $announcementDataToUpdate);

                    if (!empty($_FILES['files'])) {
                        $uploadDir = "src/database/announcements/announcement" . $announcementId . "/";
                        if (!file_exists($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }

                        $existingImages = scandir($uploadDir);
                        $existingImages = array_diff($existingImages, array('.', '..'));

                        foreach ($_FILES['files']['tmp_name'] as $index => $tmpName) {
                            if ($_FILES['files']['error'][$index] === UPLOAD_ERR_OK) {
                                $extension = strtolower(pathinfo($_FILES['files']['name'][$index], PATHINFO_EXTENSION));
                                $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');

                                if (in_array($extension, $allowedExtensions)) {
                                    // Перевірка наявності файлу з таким ім'ям
                                    $i = 0;
                                    $newFileName = ($index + count($existingImages)) . '.' . $extension;
                                    while (in_array($newFileName, $existingImages)) {
                                        $i++;
                                        $newFileName = ($index + count($existingImages) + $i) . '.' . $extension;
                                    }

                                    $uploadFile = $uploadDir . $newFileName;

                                    if (exif_imagetype($tmpName) !== false) {
                                        move_uploaded_file($tmpName, $uploadFile);
                                    } else {
                                        $this->addErrorMessage('Файл ' . $_FILES['carImages']['name'][$index] . ' не є зображенням.');
                                    }
                                } else {
                                    $this->addErrorMessage('Файл ' . $_FILES['carImages']['name'][$index] . ' має неприпустиме розширення.');
                                }
                            } else {
                                $this->addErrorMessage('Не вдалося завантажити файл: ' . $_FILES['carImages']['name'][$index]);
                            }
                        }
                        if (is_null(Files::FindPathByAnnouncementId($announcementId)))
                            Files::AddImages($announcementId, $uploadDir);
                    }

                    if (strlen($this->post->deletedImages) !== 0) {
                        $deletedImagesArray = is_array($this->post->deletedImages) ? $this->post->deletedImages : explode(', ', $this->post->deletedImages);

                        foreach ($deletedImagesArray as $deletedImage) {
                            $imagePath = "src/database/announcements/announcement" . $announcementId . "/" . $deletedImage;
                            if (file_exists($imagePath)) {
                                unlink($imagePath);
                            }
                        }
                    }

                    if ($resUpdateAnn)
                        $this->redirect("/announcements/index/{$announcementId}");
                }
            }

            $newAnnouncementData = (array)$announcementInfo;
            $newAnnouncementData[0]['pathToImages'] = Files::FindPathByAnnouncementId($announcementId);
            $GLOBALS['announcementInfo'] = $newAnnouncementData ?? null;

            return $this->render();
        }
    }

    public function actionDelete()
    {
        if (!Users::IsUserLogged()) {
            $this->redirect('/');
        }

        $userId = \core\Core::get()->session->get('user')['id'];
        if (!Users::IsAdmin($userId)) {
            $this->redirect('/');
        }

        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $id = end($queryParts);

        if ($id !== null) {
            $announcementId = $id;

            $uploadDir = "src/database/announcements/announcement" . $announcementId . "/";
            if (file_exists($uploadDir)) {
                $files = array_diff(scandir($uploadDir), ['.', '..']);
                foreach ($files as $file) {
                    unlink($uploadDir . $file);
                }
                rmdir($uploadDir);
            }

            $deletedRows = Announcements::DeleteRow(['id' => $announcementId]);

            if ($deletedRows === 0) {
                $this->redirect('/announcements');
            }

            $this->redirect('/');
        }
    }

    public function actionIndex()
    {
        // Отримати значення параметра id зі шляху
        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $id = end($queryParts);

        if ($id !== null) {
            $announcementId = $id;
            $announcement = Announcements::SelectById($announcementId);
            $announcementImages = Files::FindPathByAnnouncementId($announcementId);
            $countFavorites = UserLikeAnnouncements::CountByAnnouncementId($announcementId);

            if (!$announcement) {
                return $this->render("Views/site/index.php");
            }
            $GLOBALS['announcement'] = $announcement;
            $GLOBALS['images'] = $announcementImages;
            $GLOBALS['countLikes'] = $countFavorites;

            return $this->render();
        }
    }

    public function actionView()
    {
        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $currentPage = end($queryParts);

        if ($currentPage === null || $currentPage === 'null') {
            $currentPage = 1;
        } else {
            $currentPage = (int)$currentPage;
        }
        if ($currentPage < 1) {
            $this->redirect("view/1");
        }

        $filterAssocArray = [];

        // Обробка параметра сортування
        $sort = $this->post->sort;

        if ($sort) {
            $dateCondition = '';
            $currentDate = new DateTime();

            switch ($sort) {
                case 'yesterday':
                    $yesterday = $currentDate->modify('-1 day')->format('Y-m-d');
                    $dateCondition = ['publicationDate >=' => $yesterday];
                    break;
                case 'week':
                    $weekAgo = $currentDate->modify('-1 week')->format('Y-m-d');
                    $dateCondition = ['publicationDate >=' => $weekAgo];
                    break;
                case 'month':
                    $monthAgo = $currentDate->modify('-1 month')->format('Y-m-d');
                    $dateCondition = ['publicationDate >=' => $monthAgo];
                    break;
                case 'year':
                    $yearAgo = $currentDate->modify('-1 year')->format('Y-m-d');
                    $dateCondition = ['publicationDate >=' => $yearAgo];
                    break;
            }

            if ($dateCondition) {
                $filterAssocArray = array_merge($filterAssocArray, $dateCondition);
            }
        }

        $announcementsPerPage = 5;
        $totalAnnouncements = Announcements::CountAll($filterAssocArray);
        $totalAnnouncementsCount = isset($totalAnnouncements) ? (int)$totalAnnouncements : 0;

        $totalPages = ceil($totalAnnouncementsCount / $announcementsPerPage);
        if ($currentPage > $totalPages) {
            $this->redirect("$totalPages");
        }

        $offset = ($currentPage - 1) * $announcementsPerPage;
        $announcements = Announcements::SelectPaginated($announcementsPerPage, $offset, $filterAssocArray);

        foreach ($announcements as &$announcement) {
            $announcement['pathToImages'] = Files::FindPathByAnnouncementId($announcement['id']);
            $announcement['countLikes'] = UserLikeAnnouncements::CountByAnnouncementId($announcement['id']);
        }

        $GLOBALS['announcements'] = $announcements;
        $GLOBALS['currentPage'] = $currentPage;
        $GLOBALS['totalPages'] = $totalPages;
        $GLOBALS['sort'] = $sort;

        return $this->render();
    }

    public function actionAddsuccess()
    {
        if (!Users::IsUserLogged()) {
            $this->redirect('/');
        }
        return $this->render();
    }

    public function actionAddtofavorites()
    {
        if (!\Models\Users::IsUserLogged()) {
            $this->redirect('/');
        }
        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $id = end($queryParts);
        if ($id !== null) {
            $announcementId = $id;
            $userId = \core\Core::get()->session->get('user')['id'];
            $existingFavorite = \Models\UserLikeAnnouncements::findByCondition(['user_id' => $userId, 'announcement_id' => $announcementId]);
            if ($existingFavorite) {
                $successMessage = "Це оголошення вже вподобано!";
            } else {
                \Models\UserLikeAnnouncements::AddRow($userId, $announcementId);
                $successMessage = "Оголошення успішно вподобано!";
            }
            $GLOBALS['successMessage'] = isset($successMessage) ? $successMessage : null;
            $referer = $_SERVER['HTTP_REFERER'] ?? '/announcements';
            $this->redirect($referer);
        }
    }

    public function actionRemovefromfavorites()
    {
        if (!\Models\Users::IsUserLogged()) {
            $this->redirect('/');
        }
        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $id = end($queryParts);
        if ($id !== null) {
            $announcementId = $id;
            $userId = \core\Core::get()->session->get('user')['id'];
            $existingFavorite = \Models\UserLikeAnnouncements::findByCondition(['user_id' => $userId, 'announcement_id' => $announcementId]);
            if ($existingFavorite) {
                \Models\UserLikeAnnouncements::RemoveRow($userId, $announcementId);
                $successMessage = "Відмітку успішно прибрано!";
            } else {
                $successMessage = "Оголошення не відмічене!";
            }
            $GLOBALS['successMessage'] = isset($successMessage) ? $successMessage : null;
            $referer = $_SERVER['HTTP_REFERER'] ?? '/announcements';
            $this->redirect($referer);
        }
    }
}