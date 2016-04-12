<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Services\CategoryService;

class CategoryController extends Controller
{
    /**
     * Category Service
     *
     * @var CategoryService
     */
    protected $categoryService;

    /**
     * CategoryController constructor.
     *
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->categoryService->getList();

        return response()->json($response);
    }

    /**
     * Returns a resource
     *
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        $response = $this->categoryService->get($id);

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->categoryService->validateInput($request->all());
        } catch (\Exception $e) {
            abort(422);
        }

        $response = $this->categoryService->create($request);

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->categoryService->validateInput($request->all(), true, $id);
        } catch (\Exception $e) {
            abort(422);
        }

        $response = $this->categoryService->update($request, $id);

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // TODO
    }
}
