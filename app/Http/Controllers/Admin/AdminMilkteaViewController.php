<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminMilkteaViewController extends Controller
{
    //
    public function reviews() {
        return Inertia::render('Admin/Milktea/Reviews');
    }

    public function orders() {
        return Inertia::render('Admin/Milktea/Orders');
    }

    public function modifier_groups() {
        return Inertia::render('Admin/Milktea/ModifierGroups');
    }

    public function new_modifier_group() {
        return Inertia::render('Admin/Milktea/NewModifierGroup');
    }

}
