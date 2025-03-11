<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        // TODO: Implement index method
    }

    public function create()
    {
        return view('employees.create');
    }

    // ...other resource methods
}
