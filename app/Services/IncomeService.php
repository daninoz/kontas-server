<?php

namespace App\Services;

use App\Helpers\MoneyHelper;
use Validator;
use App\Income;
use App\Statement;
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
     * Statement model
     *
     * @var Statement
     */
    protected $statement;

    /**
     * Account model
     *
     * @var Account
     */
    protected $account;

    /**
     * Money Helper
     *
     * @var MoneyHelper
     */
    protected $money;

    /**
     * IncomeService constructor.
     *
     * @param Income    $income
     * @param MoneyHelper $money
     */
    public function __construct(Income $income, Statement $statement, Account $account, MoneyHelper $money)
    {
        $this->income = $income;
        $this->statement = $statement;
        $this->account = $account;
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
            'destination_id' => ['required', 'exists:'.str_plural($input['destination_type']).',id'],
            'category_id' => ['required', 'exists:categories,id'],
            'currency_id' => ['required', 'exists:currencies,id'],
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            throw new \Exception;
        }
    }

    private function getDestinationType($type)
    {
        switch ($type) {
            case 'account':
                return $this->account;
                break;
            case 'credit_card':
                return $this->statement;
                break;
        }
    }

    private function getDestination($type, $id, $date)
    {
        $destination_type = $this->getDestinationType($type);

        return $destination_type->getDestination($id, $date);
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

        $destination = $this->getDestination($input->destination_type, $input->destination_id, $income->date);

        $destination->incomes()->save($income);

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