<?php

namespace App\Services;

use App\Helpers\MoneyHelper;
use App\Estimation;

class EstimationService
{
    /**
     * Estimation model
     *
     * @var Estimation
     */
    protected $estimation;

    /**
     * Money Helper
     *
     * @var MoneyHelper
     */
    protected $money;

    /**
     * EstimationService constructor.
     *
     * @param Estimation    $estimation
     * @param MoneyHelper $money
     */
    public function __construct(Estimation $estimation, MoneyHelper $money)
    {
        $this->estimation = $estimation;
        $this->money = $money;
    }

    /**
     * Get a list of estimations
     *
     * @return static
     */
    public function getList()
    {
        $estimations = $this->estimation->with(['category', 'currency'])->get();

        $response = $estimations->map(function ($estimation) {
            return [
                "id" => $estimation->id,
                "amount" => $this->money->fromStoredMoney($estimation->amount),
                "start_date" => $estimation->start_date,
                "end_date" => $estimation->end_date,
                "day" => $estimation->day,
                "currency" => $estimation->currency->name,
                "category" => $estimation->category->name,
            ];
        });

        return $response;
    }

    /**
     * Validate the input
     *
     * @param      $input
     * @param bool $update
     * @param null $id
     *
     * @throws \Exception
     */
    public function validateInput($input, $update = false, $id = null)
    {
        $rules = [
            'amount' => ['required', 'numeric'],
            'start_date' => ['required', 'date'],
            'end_date' => ['date'],
            'day' => ['required', 'numeric', 'min:1', 'max:31'],
            'category_id' => ['required', 'exists:categories,id'],
            'currency_id' => ['required', 'exists:currencies,id'],
        ];

        $validator = app('validator')->make($input, $rules);

        if ($validator->fails()) {
            throw new \Exception;
        }
    }

    /**
     * Creates a new estimation
     *
     * @param $input
     *
     * @return array
     */
    public function create($input)
    {
        $estimation = $this->estimation->create([
            'amount' => $this->money->toStoredMoney($input->amount),
            'start_date' => $input->start_date,
            'end_date' => $input->end_date,
            'day' => $input->day,
            'category_id' => $input->category_id,
            'currency_id' => $input->currency_id,
        ]);

        return ["id" => $estimation->id];
    }

    /**
     * Updates a estimation
     *
     * @param $input
     * @param $id
     *
     * @return array
     */
    public function update($input, $id)
    {
        $estimation = $this->estimation->findOrFail($id);

        $estimation->update([
            'amount' => $this->money->toStoredMoney($input->amount),
            'start_date' => $input->start_date,
            'end_date' => $input->end_date,
            'day' => $input->day,
            'category_id' => $input->category_id,
            'currency_id' => $input->currency_id,
        ]);

        return ["id" => $estimation->id];
    }

    /**
     * Deletes an estimation
     *
     * @param $id
     *
     * @return array
     */
    public function delete($id)
    {
        $estimation = $this->estimation->findOrFail($id);

        $estimation->delete($id);
    }
}