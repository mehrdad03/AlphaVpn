<?php

namespace App\Http\Livewire\Admin\Auth;

use Livewire\Component;

class Login extends Component
{
    public function render()
    {
        return view('Admin.livewire.auth.login')->extends('admin.layouts.app');
    }
}
