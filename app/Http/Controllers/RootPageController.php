<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class RootPageController extends Controller
{
    /**
     * Return view for a root page
     */
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->first();

        if ($page == null || $page->pageCategory->id != 1) {
            return response()->redirectTo('/');
        }

        return view('page', ['page' => $page]);
    }
}
