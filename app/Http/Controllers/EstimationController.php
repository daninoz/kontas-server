<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Services\EstimationService;

class EstimationController extends Controller
{
    /**
     * Estimation Service
     *
     * @var EstimationService
     */
    protected $estimationService;

    /**
     * EstimationController constructor.
     *
     * @param EstimationService $estimationService
     */
    public function __construct(EstimationService $estimationService)
    {
        $this->estimationService = $estimationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->estimationService->getList();

        return response()->json($response);
    }

    /**
     * Returns a resource
     *
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        $response = $this->estimationService->get($id);

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
            $this->estimationService->validateInput($request->all());
        } catch (\Exception $e) {
            abort(422);
        }

        $response = $this->estimationService->create($request);

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
            $this->estimationService->validateInput($request->all(), true, $id);
        } catch (\Exception $e) {
            abort(422);
        }

        $response = $this->estimationService->update($request, $id);

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
        $this->estimationService->delete($id);

        return response('');
    }
}
