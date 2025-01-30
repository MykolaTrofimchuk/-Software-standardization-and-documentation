<?php

namespace core;

use core\RequestMethod;

class Get extends RequestMethod
{
    public function __construct()
    {
        parent::__construct($_GET);
    }
}