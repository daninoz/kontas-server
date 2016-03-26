<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Services\ExpenseService;

class ExpenseController extends Controller
{
    /**
     * Expense Service
     *
     * @var ExpenseService
     */
    protected $expenseService;

    /**
     * ExpenseController constructor.
     *
     * @param ExpenseService $expenseService
     */
    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
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
            $this->expenseService->validateInput($request->all());
        } catch (\Exception $e) {
            abort(422);
        }

        $response = $this->expenseService->create($request);

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
            $this->expenseService->validateInput($request->all(), true, $id);
        } catch (\Exception $e) {
            abort(422);
        }

        $response = $this->expenseService->update($request, $id);

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
        $this->expenseService->delete($id);

        return response('');
    }
}
