<?php

namespace App\Services;

use App\Helpers\MoneyHelper;
use App\CreditCard;

class CreditCardService
{
    /**
     * Credit Card model
     *
     * @var CreditCard
     */
    protected $creditCard;

    /**
     * Money Helper
     *
     * @var MoneyHelper
     */
    protected $money;

    /**
     * CreditCardService constructor.
     *
     * @param CreditCard    $creditCard
     * @param MoneyHelper $money
     */
    public function __construct(CreditCard $creditCard, MoneyHelper $money)
    {
        $this->creditCard = $creditCard;
        $this->money = $money;
    }

    /**
     * Get a list of credit cards
     *
     * @return static
     */
    public function getList()
    {
        $creditCards = $this->creditCard->all();

        $response = $creditCards->map(function ($item) {
            return [
                "id" => $item->id,
                "name" => $item->name,
                "fee" => $this->money->fromStoredMoney($item->fee),
                "insurance" => $this->money->fromStoredMoney($item->insurance),
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
            'name' => ['required', 'max:100', 'unique:credit_cards,name'],
            'fee' => ['required', 'numeric'],
            'insurance' => ['required', 'numeric'],
        ];

        if ($update) {
            $rules['name'][2] .= "," . $id;
        }

        $validator = app('validator')->make($input, $rules);

        if ($validator->fails()) {
            throw new \Exception;
        }
    }

    /**
     * Creates a new credit card
     *
     * @param $input
     *
     * @return array
     */
    public function create($input)
    {
        $creditCard = $this->creditCard->create([
            'name' => $input->name,
            'fee' => $this->money->toStoredMoney($input->fee),
            'insurance' => $this->money->toStoredMoney($input->insurance),
        ]);

        return ["id" => $creditCard->id];
    }

    /**
     * Updates a credit card
     *
     * @param $input
     * @param $id
     *
     * @return array
     */
    public function update($input, $id)
    {
        $creditCard = $this->creditCard->findOrFail($id);

        $creditCard->update([
            'name' => $input->name,
            'fee' => $this->money->toStoredMoney($input->fee),
            'insurance' => $this->money->toStoredMoney($input->insurance),
        ]);

        return ["id" => $creditCard->id];
    }
}