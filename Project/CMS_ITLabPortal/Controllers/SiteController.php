<?php

namespace Controllers;

use core\Controller;
use core\Template;

class SiteController extends Controller
{
    public function actionIndex()
    {
        return $this->render();
    }

    public function actionPrivacyPolicy()
    {
        return $this->render();
    }
}