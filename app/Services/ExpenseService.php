<?php

namespace App\Services;

use App\Services\CreditCardPurchaseService;
use App\Helpers\MoneyHelper;
use App\Expense;
use App\Services\PurchaseService;
use App\Services\SimpleExpenseService;
use App\Services\AccountService;

class ExpenseService
{
    /**
     * Expense model
     *
     * @var Expense
     */
    protected $expense;

    /**
     * Credit Card Purchase Service
     *
     * @var CreditCardPurchaseService
     */
    protected $credit_card_purchase_service;

    /**
     * Account Service
     *
     * @var AccountService
     */
    protected $account_service;

    /**
     * Simple Expense service
     *
     * @var SimpleExpenseService
     */
    protected $simple_expense_service;

    /**
     * Purchase Service
     *
     * @var PurchaseService
     */
    protected $purchase_service;

    /**
     * Money Helper
     *
     * @var MoneyHelper
     */
    protected $money;

    /**
     * ExpenseService constructor.
     *
     * @param Expense                                 $expense
     * @param CreditCardPurchaseService               $credit_card_purchase_service
     * @param AccountService                          $account_service
     * @param SimpleExpenseService                    $simple_expense_service
     * @param PurchaseService                         $purchase_service
     * @param MoneyHelper                             $money
     */
    public function __construct(Expense $expense, CreditCardPurchaseService $credit_card_purchase_service,
                                AccountService $account_service, SimpleExpenseService $simple_expense_service,
                                PurchaseService $purchase_service, MoneyHelper $money)
    {
        $this->expense = $expense;
        $this->credit_card_purchase_service = $credit_card_purchase_service;
        $this->account_service = $account_service;
        $this->simple_expense_service = $simple_expense_service;
        $this->purchase_service = $purchase_service;
        $this->money = $money;
    }

    /**
     * Validate the input
     *
     * @param      $input
     *
     * @throws \Exception
     */
    public function validateInput($input)
    {
        $rules = [
            'description' => ['required', 'max:250'],
            'date' => ['required', 'date'],
            'amount' => ['required_if:type,simple', 'numeric'],
            'items' => ['required_if:type,composed', 'array'],
            'number_installments' => ['required_if:source_type,credit_card'],
            'type' => ['required', 'in:simple,composed'],
            'source_type' => ['required', 'in:account,credit_card'],
            'source_id' => ['required', 'exists:'.str_plural($input['source_type']).',id'],
            'category_id' => ['required_if:type,simple', 'exists:categories,id'],
            'currency_id' => ['required', 'exists:currencies,id'],
        ];

        $validator = app('validator')->make($input, $rules);

        if ($validator->fails()) {
            throw new \Exception;
        }

        $item_rules = [
            'amount' => ['required', 'numeric'],
            'category_id' => ['required', 'exists:categories,id'],
        ];

        if ($input['type'] == 'composed') {
            foreach ($input['items'] as $item) {
                $validator = app('validator')->make($item, $item_rules);

                if ($validator->fails()) {
                    throw new \Exception;
                }
            }
        }
    }

    private function getSource($input)
    {
        $source = null;

        $amount = 0;
        if ($input->type == 'simple') {
            $amount = $input->amount;
        } else {
            foreach ($input->items as $item) {
                $amount += $item['amount'];
            }
        }

        switch ($input->source_type) {
            case 'credit_card':
                $source = $this->credit_card_purchase_service
                    ->get($input->source_id, $input->date, $amount, $input->number_installments);
                break;
            case 'account':
                $source = $this->account_service
                    ->get($input->source_id);
                break;
        }

        return $source;
    }

    private function getType($input)
    {
        $type = null;

        switch($input->type) {
            case 'simple':
                $type = $this->simple_expense_service->get($input->amount, $input->category_id);
                break;
            case 'composed':
                $type = $this->purchase_service->get($input->items);
                break;
        }

        return $type;
    }

    /**
     * Creates a new expense
     *
     * @param $input
     *
     * @return array
     */
    public function create($input)
    {
        $expense = $this->expense->newInstance();

        $expense->description = $input->description;
        $expense->date = $input->date;
        $expense->currency_id = $input->currency_id;

        $type = $this->getType($input);
        $expense->type()->associate($type);

        $source = $this->getSource($input);
        $expense->source()->associate($source);

        $expense->save();

        return ["id" => $expense->id];
    }

    /**
     * Updates a expense
     *
     * @param $input
     * @param $id
     *
     * @return array
     */
    public function update($input, $id)
    {
        $expense = $this->expense->findOrFail($id);

        $expense->description = $input->description;
        $expense->date = $input->date;
        $expense->amount = $this->money->toStoredMoney($input->amount);
        $expense->category_id = $input->category_id;
        $expense->currency_id = $input->currency_id;

        $destination = $this->getDestination($input->destination_type, $input->destination_id, $expense->date);

        $destination->expenses()->save($expense);

        return ["id" => $expense->id];
    }

    /**
     * Deletes an expense
     *
     * @param $id
     *
     * @return array
     */
    public function delete($id)
    {
        $expense = $this->expense->findOrFail($id);

        $expense->delete($id);
    }
}