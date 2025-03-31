<?php

namespace Controllers;

use core\Controller;

class ErrorController extends Controller
{
    public function actionError($errorCode)
    {
        $this->template->setParam('errorCode', $errorCode);
        $this->template->setTemplateFilePath('Views/error/error.php');
        return [
            'Content' => $this->template->getHTML()
        ];
    }
}