<?php

namespace App\Http\Controllers;

use App\Models\PageCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PageCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageCategories = PageCategory::all();

        return view('documentation.categories.index', [
            'pageCategories' =>  $pageCategories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('documentation.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:4|max:60'
        ]);

        try {
            $validator->validate();
        } catch (ValidationException $e)
        {
            Log::error($e->getMessage());
            /**
             * @var Illuminate\Session\Store $session
             */
            $session = $request->session();
            $session->flash('errors', $validator->errors());
        }

        $validated = $validator->validated();

        $pageCategory = new PageCategory();
        $pageCategory->title = $validated['title'];
        $pageCategory->save();

        return response()->redirectToRoute('pagecategories.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id 
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageCategory = PageCategory::find($id);

        if ($pageCategory == null)
        {
            return response()->redirectToRoute('pagecategories.index');
        }

        return view('documentation.categories.edit', [
            'pageCategory' => $pageCategory
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:4|max:60'
        ]);

        try {
            $validator->validate();
        } catch (ValidationException $e) {
            Log::error($e->getMessage());
            /**
             * @var Illuminate\Session\Store $session
             */
            $session = $request->session();
            $session->flash('errors', $validator->errors());
        }

        $validated = $validator->validated();

        $pageCategory = PageCategory::find($id);
        
        if ($pageCategory == null)
        {
            return response()->redirectToRoute('pagecategories.index');
        }

        $pageCategory->title = $validated['title'];
        $pageCategory->save();

        return response()->redirectToRoute('pagecategories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $pageCategory = PageCategory::find($id);

        if ($pageCategory == null)
        {
            return response()->redirectToRoute('pagecategories.index');
        }

        $pageCategory->delete();

        return response()->redirectToRoute('pagecategories.index');
    }
}
