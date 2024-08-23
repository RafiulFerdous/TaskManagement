<?php

namespace App\Http\Controllers\Admin;

//use App\Jobs\Status;
use App\Models\User;
use App\Models\Order;
use App\Models\Status;
use Illuminate\Http\Request;
use App\Models\Statushistory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{

    public function index()
    {
       return view('admin.home');
    }

}
