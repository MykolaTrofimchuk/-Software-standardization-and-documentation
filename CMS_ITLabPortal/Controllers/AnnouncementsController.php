<?php

namespace Controllers;

use core\Controller;
use Models\Announcements;
use Models\Files;
use Models\Users;

class AnnouncementsController extends Controller
{
    public function actionAdd()
    {
        if ($this->isPost) {
            $userId = \core\Core::get()->session->get('user')['id'];

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
                            $uploadFile = $uploadDir . basename($_FILES['files']['name'][$index]);

                            $check = getimagesize($tmpName);
                            if ($check !== false) {
                                move_uploaded_file($tmpName, $uploadFile);
                            } else {
                                $this->addErrorMessage('Файл ' . $_FILES['carImages']['name'][$index] . ' не є зображенням.');
                            }
                        } else {
                            $this->addErrorMessage('Не вдалося завантажити файл: ' . $_FILES['files']['name'][$index]);
                        }
                    }

                    Files::AddImages($announcementId, $uploadDir);
                }

                // Переадресація на сторінку успіху або виконання інших необхідних дій
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

            if (!$announcement) {
                return $this->render("Views/site/index.php");
            }
            $GLOBALS['announcement'] = $announcement;
            $GLOBALS['images'] = $announcementImages;

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
            $this->redirect("1");
        }

        if ($currentPage !== null) {
            $announcementsPerPage = 5;
            $totalAnnouncements = Announcements::CountAll(); // Get the total number of announcements
            $totalAnnouncementsCount = isset($totalAnnouncements[0]['count']) ? (int)$totalAnnouncements[0]['count'] : 0;

            $totalPages = ceil($totalAnnouncementsCount / $announcementsPerPage);
            if ($currentPage > $totalPages) {
                $this->redirect("$totalPages");
            }
            $offset = ($currentPage - 1) * $announcementsPerPage;
            $announcements = Announcements::SelectPaginated($announcementsPerPage, $offset);

            foreach ($announcements as &$announcement) {
                $announcement['pathToImages'] = Files::FindPathByAnnouncementId($announcement['id']);
            }

            $GLOBALS['announcements'] = $announcements;
            $GLOBALS['currentPage'] = $currentPage;
            $GLOBALS['totalPages'] = $totalPages;
            return $this->render();
        }
        return $this->render('Views/announcements/view.php');
    }

    public function actionAddsuccess()
    {
        if (!Users::IsUserLogged()) {
            $this->redirect('/');
        }
        return $this->render();
    }
}