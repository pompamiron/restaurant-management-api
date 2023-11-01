<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;

class MenuItemController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::all();
        return response()->json($menuItems, 200);
    }
}
