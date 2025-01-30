<?php

namespace core;

class Controller
{
    protected $template;
    protected $errorMessages;
    public $isPost = false;
    public $isGet = false;
    public $post;
    public $get;

    public function __construct()
    {
        $action = Core::get()->actionName;
        $module = Core::get()->moduleName;
        $path = "Views/{$module}/{$action}.php";
        $this->template = new Template($path);

        switch ($_SERVER['REQUEST_METHOD']){
            case 'POST' :
                $this->isPost = true;
                break;
            case 'GET':
                $this->isGet = true;
                break;
        }
        $this->post = new Post();
        $this->get = new Get();
        $this->errorMessages = [];
    }

    public function render($pathToView = null): array
    {
        if (!empty($pathToView))
            $this->template->setTemplateFilePath($pathToView);
        return [
            'Content' => $this->template->getHTML()
        ];
    }

    public function redirect($path): void
    {
        header("Location: {$path}");
        die;
    }

    public function addErrorMessage($message = null): void
    {
        $this->errorMessages [] = $message;
        $this->template->setParam('error_message', implode('<br>', $this->errorMessages));
    }

    public function clearErrorMessage(): void
    {
        $this->errorMessages = [];
        $this->template->setParam('error_message', null);
    }

    public function isErrorMessagesExists(): bool
    {
        return count($this->errorMessages) > 0;
    }
}