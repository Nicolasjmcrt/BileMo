<?php

namespace App\Controller;

use PhpParser\Node\Expr\FuncCall;

class TestController
{
    public function index()
    {
        var_dump("Ça fonctionne !");
        die();
    }
}