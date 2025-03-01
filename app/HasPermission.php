<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

trait HasPermission
{
    protected $abilities = [
        'index'     => 'read',
        'create'    => 'create',
        'store'     => 'create',
        'show'      => 'read',
        'edit'      => 'update',
        'update'    => 'update',
        'destroy'   => 'delete',
    ];
    public function callAction($method, $parameters)
    {
        $action = Arr::get($this->abilities, $method);
        if (!$action) {
            return parent::callAction($method, $parameters);
        }
        $staticPath     = request()->route()->getCompiled()->getStaticPrefix();
        $urlMenu        = urlMenu();
        $staticPath     = substr($staticPath, 1);

        if (!in_array($staticPath, $urlMenu)) {
            foreach (array_reverse(explode('/', $staticPath)) as $path) {
                $staticPath = str_replace("/$path", "", $staticPath);
                if (in_array($staticPath, $urlMenu)) {
                    break;
                }
            }
        }

        if (in_array($staticPath, $urlMenu)) {
            Gate::authorize("$action $staticPath");
        }

        return parent::callAction($method, $parameters);
    }
}
