<?php

namespace App;

trait DatatableHelper
{
    public static function generateButtons($path, $routes)
    {
        $detail = user()->can('read ' . $path) && !empty($routes['detail']) ?
            '<a href="' . $routes['detail'] . '" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm me-1 action hover-scale"
        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Detail">
        <i class="ki-duotone ki-eye fs-2"><span class="path1"></span><span class="path2"></span><span
                class="path3"></span></i> </a>'
            : '';

        $edit = user()->can('update ' . $path) && !empty($routes['edit']) ?
            '<a href="' . $routes['edit'] . '" class="btn btn-icon btn-bg-light btn-active-color-success btn-sm me-1 action hover-scale"
                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i> </a>'
            : '';

        $hapus = user()->can('delete ' . $path) && !empty($routes['hapus']) ?
            '<a href="' . $routes['hapus'] . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete hover-scale"
                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Hapus">
                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span
                        class="path3"></span><span class="path4"></span><span class="path5"></span></i> </a>'
            : '';

        return trim($detail . ' ' . $edit . ' ' . $hapus);
    }
}
