<?php

namespace App\Http\Controllers;

use App\DatatableHelper;
use App\HasPermission;

abstract class Controller
{
    use DatatableHelper, HasPermission;

    public function callAction($method, $parameters)
    {
        return call_user_func_array([$this, $method], $parameters);
    }
}
