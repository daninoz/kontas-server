<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Services\CreditCardService;

class CreditCardController extends Controller
{
    /**
     * CreditCard Service
     *
     * @var CreditCardService
     */
    protected $creditCardService;

    /**
     * CreditCardController constructor.
     *
     * @param CreditCardService $creditCardService
     */
    public function __construct(CreditCardService $creditCardService)
    {
        $this->creditCardService = $creditCardService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->creditCardService->getList();

        return response()->json($response);
    }

    /**
     * Returns a resource
     *
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        $response = $this->creditCardService->get($id);

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
            $this->creditCardService->validateInput($request->all());
        } catch (\Exception $e) {
            abort(422);
        }

        $response = $this->creditCardService->create($request);

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
            $this->creditCardService->validateInput($request->all(), true, $id);
        } catch (\Exception $e) {
            abort(422);
        }

        $response = $this->creditCardService->update($request, $id);

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
