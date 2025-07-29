<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function users()
    {
        return redirect()->route('admin.users.index');
    }

    public function roles()
    {
        return redirect()->route('admin.roles.index');
    }

    public function menus()
    {
        return redirect()->route('admin.menu.index');
    }

    public function company()
    {
        return view('settings.company');
    }

    public function accounting()
    {
        return redirect()->route('accounting.settings.index');
    }

    public function email()
    {
        return view('settings.email');
    }

    public function backup()
    {
        return view('settings.backup');
    }

    public function logs()
    {
        return view('settings.logs');
    }
}
