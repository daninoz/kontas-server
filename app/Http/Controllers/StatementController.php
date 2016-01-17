<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Services\StatementService;

class StatementController extends Controller
{
    /**
     * Statement Service
     *
     * @var StatementService
     */
    protected $statementService;

    /**
     * StatementController constructor.
     *
     * @param StatementService $statementService
     */
    public function __construct(StatementService $statementService)
    {
        $this->statementService = $statementService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->statementService->getList();

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
            $this->statementService->validateInput($request->all());
        } catch (\Exception $e) {
            abort(422);
        }

        $response = $this->statementService->create($request);

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
            $this->statementService->validateInput($request->all(), true, $id);
        } catch (\Exception $e) {
            abort(422);
        }

        $response = $this->statementService->update($request, $id);

        return response()->json($response);
    }
}
