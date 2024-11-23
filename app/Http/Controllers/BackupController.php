<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;


class BackupController extends Controller
{
    //
    public function index(): View
    {
        //
        return view("backup.index");
    }

}
