<?php

namespace App\Services;

use App\Helpers\MoneyHelper;
use App\Income;
use App\StatementService;
use App\Account;

class IncomeService
{
    /**
     * Income model
     *
     * @var Income
     */
    protected $income;

    /**
     * Statement service

     * @var StatementService
     */
    protected $statement_service;

    /**
     * Account service
     *
     * @var AccountService
     */
    protected $account_service;

    /**
     * Money Helper
     *
     * @var MoneyHelper
     */
    protected $money;

    /**
     * IncomeService constructor.
     *
     * @param Income           $income
     * @param StatementService $statement_service
     * @param AccountService   $account_service
     * @param MoneyHelper      $money
     */
    public function __construct(Income $income, StatementService $statement_service,
                                AccountService $account_service, MoneyHelper $money)
    {
        $this->income = $income;
        $this->statement_service = $statement_service;
        $this->account_service = $account_service;
        $this->money = $money;
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
            'description' => ['required', 'max:250'],
            'date' => ['required', 'date'],
            'amount' => ['required', 'numeric'],
            'destination_type' => ['required', 'in:account,credit_card'],
            'destination_id' => ['required', 'exists:'.str_plural($input['destination_type']).',id'],
            'category_id' => ['required', 'exists:categories,id'],
            'currency_id' => ['required', 'exists:currencies,id'],
        ];

        $validator = app('validator')->make($input, $rules);

        if ($validator->fails()) {
            throw new \Exception;
        }
    }

    private function getDestination($input)
    {
        $destination = null;

        switch ($input->source_type) {
            case 'credit_card':
                $destination = $this->statement_service
                    ->get($input->source_id, $input->date);
                break;
            case 'account':
                $destination = $this->account_service
                    ->get($input->source_id);
                break;
        }

        return $destination;
    }

    /**
     * Creates a new income
     *
     * @param $input
     *
     * @return array
     */
    public function create($input)
    {
        $income = $this->income->newInstance();

        $income->description = $input->description;
        $income->date = $input->date;
        $income->amount = $this->money->toStoredMoney($input->amount);
        $income->category_id = $input->category_id;
        $income->currency_id = $input->currency_id;

        $source = $this->getDestination($input);
        $income->source()->associate($source);

        $income->save();

        return ["id" => $income->id];
    }

    /**
     * Updates a income
     *
     * @param $input
     * @param $id
     *
     * @return array
     */
    public function update($input, $id)
    {
        $income = $this->income->findOrFail($id);

        $income->description = $input->description;
        $income->date = $input->date;
        $income->amount = $this->money->toStoredMoney($input->amount);
        $income->category_id = $input->category_id;
        $income->currency_id = $input->currency_id;

        $destination = $this->getDestination($input->destination_type, $input->destination_id, $income->date);

        $destination->incomes()->save($income);

        return ["id" => $income->id];
    }

    /**
     * Deletes an income
     *
     * @param $id
     *
     * @return array
     */
    public function delete($id)
    {
        $income = $this->income->findOrFail($id);

        $income->delete($id);
    }
}