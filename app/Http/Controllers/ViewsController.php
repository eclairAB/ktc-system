<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewsController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    public function roleRedirect()
    {
        return redirect()->intended('/admin/' . Auth::user()->role->name);
    }

    public function roleView($role)
    {
        return view('vendor.voyager.role-views.'.$role, ['name' => $role]);
    }
}
