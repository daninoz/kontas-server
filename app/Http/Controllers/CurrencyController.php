<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\CurrencyService;

class CurrencyController extends Controller
{
    /**
     * Currency Service
     *
     * @var CurrencyService
     */
    protected $currencyService;

    /**
     * CurrencyController constructor.
     *
     * @param CurrencyService $currencyService
     */
    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->currencyService->getList();

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
            $this->currencyService->validateInput($request->all());
        } catch (\Exception $e) {
            abort(422);
        }

        $response = $this->currencyService->create($request);

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
            $this->currencyService->validateInput($request->all(), true, $id);
        } catch (\Exception $e) {
            abort(422);
        }

        $response = $this->currencyService->update($request, $id);

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
