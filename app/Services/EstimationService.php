<?php

namespace App\Services;

use App\Helpers\MoneyHelper;
use App\Estimation;
use DateTime;

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
     * Get a currency
     *
     * @param $id
     *
     * @return mixed
     */
    public function get($id)
    {
        $estimation = $this->estimation->findOrFail($id);

        return [
            "id" => $estimation->id,
            "amount" => $this->money->fromStoredMoney($estimation->amount),
            "start_date" => $estimation->start_date_formatted,
            "end_date" => $estimation->end_date_formatted,
            "day" => $estimation->day,
            "currency_id" => $estimation->currency->id,
            "category_id" => $estimation->category->id,
        ];
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
        $data = [
            'amount' => $this->money->toStoredMoney($input->amount),
            'start_date' => new DateTime($input->start_date),
            'day' => $input->day,
            'category_id' => $input->category_id,
            'currency_id' => $input->currency_id,
        ];

        if (array_key_exists('end_date', $input)) {
            $data->end_date = new DateTime($input->end_date);
        }

        $estimation = $this->estimation->create($data);

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

        $data = [
            'amount' => $this->money->toStoredMoney($input->amount),
            'start_date' => new DateTime($input->start_date),
            'day' => $input->day,
            'category_id' => $input->category_id,
            'currency_id' => $input->currency_id,
        ];

        if (array_key_exists('end_date', $input)) {
            $data->end_date = new DateTime($input->end_date);
        }

        $estimation->update($data);

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