<?php

namespace App\Http\Controllers;

use App\Models\Page;
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
        $pages = Page::all();

        return view('documentation.index', ['pages' => $pages]);
    }
}
