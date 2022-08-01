<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PageCategory;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    /**
     * Return instance of documentation index view
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = PageCategory::where('id', '>', 1)
            ->get();

        return view('documentation.index', [
            'categories' => $categories
        ]);
    }
}
