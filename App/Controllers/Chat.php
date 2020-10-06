<?php

namespace app\Controllers;

use \Core\Controller;
use \Core\View;

class Chat extends Controller
{
    public function index()
    {
        View::renderTemplate('Chat/index.html');
    }
}