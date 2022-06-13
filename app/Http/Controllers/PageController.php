<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new page
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('documentation.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:6|max:60',
            'content' => 'required|string',
            'slug' => 'required|string|alpha_dash|unique:App\Models\Page,slug'
        ]);

        try {
            $validator->validate();
        } catch (ValidationException $e) {
            Log::error($e->getMessage());
            $request->session()->flash('errors', $validator->errors());
        }

        $validated = $validator->validated();

        $page = new Page();
        $page->title = $validated['title'];
        $page->content = $validated['content'];
        $page->last_modified_by = $user->id;
        $page->category_id = 2;
        $page->slug = $validated['slug'];
        $page->save();

        return view('documentation.show', ['page' => $page, 'page_category' => $page->pageCategory]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function show(string $page_category, string $slug)
    {
        $page = Page::where('slug', $slug)->first();
        
        if ($page == null) {
            return response()->redirectTo('/docs');
        }

        $pageCategory = $page->pageCategory;
        if ($pageCategory == null)
        {
            Log::emergency("Attempting to display page with no page category. Page ID: " . $page->id);
            return response('', 500);
        }

        return view('documentation.show', [
            'page' => $page,
            'page_category' => $pageCategory
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function edit(string $slug)
    {
        Log::debug('here');
        $page = Page::where('slug', $slug)->first();

        if ($page == null) {
            return response()->redirectTo('/docs');
        }

        return view('documentation.edit', ['page' => $page]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $slug)
    {
        $page = Page::where('slug', $slug)->first();

        if ($page == null) {
            return response()->redirectTo('/docs');
        }

        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:10|max:60',
            'content' => 'required|string',
        ]);

        try {
            $validator->validate();
        } catch (ValidationException $e) {
            Log::error($e->getMessage());
            $request->session()->flash('errors', $validator->errors());
        }

        $validated = $validator->validated();

        $page->title = $validated['title'];
        $page->content = $validated['content'];
        $page->last_modified_by = $user->id;
        $page->save();

        return view('documentation.show', ['page' => $page]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $slug)
    {
        $page = Page::where('slug', $slug)->first();

        if ($page == null)
        {
            return response()->redirectTo('/docs');
        }

        $page->deleted = true;
        $page->save();

        return redirect('/docs')->with('status', $page->title . ' has been deleted.');
    }
}
