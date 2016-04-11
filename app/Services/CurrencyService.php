<?php

namespace App\Services;

use App\Helpers\MoneyHelper;
use App\Currency;

class CurrencyService
{
    /**
     * Currency model
     *
     * @var Currency
     */
    protected $currency;

    /**
     * Money Helper
     *
     * @var MoneyHelper
     */
    protected $money;

    /**
     * CurrencyService constructor.
     *
     * @param Currency    $currency
     * @param MoneyHelper $money
     */
    public function __construct(Currency $currency, MoneyHelper $money)
    {
        $this->currency = $currency;
        $this->money = $money;
    }

    /**
     * Get a list of currencies
     *
     * @return static
     */
    public function getList()
    {
        $currencies = $this->currency->all();

        $response = $currencies->map(function ($item) {
            return [
                "id" => $item->id,
                "name" => $item->name,
                "exchange_rate" => $this->money->fromStoredMoney($item->exchange_rate),
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
        $currency = $this->currency->findOrFail($id);

        return [
            "id" => $currency->id,
            "name" => $currency->name,
            "exchange_rate" => $this->money->fromStoredMoney($currency->exchange_rate),
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
            'name' => ['required', 'max:100', 'unique:currencies,name'],
            'exchange_rate' => ['required', 'numeric']
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
     * Creates a new currency
     *
     * @param $input
     *
     * @return array
     */
    public function create($input)
    {
        $currency = $this->currency->create([
            'name' => $input->name,
            'exchange_rate' => $this->money->toStoredMoney($input->exchange_rate)
        ]);

        return ["id" => $currency->id];
    }

    /**
     * Updates a currency
     *
     * @param $input
     * @param $id
     *
     * @return array
     */
    public function update($input, $id)
    {
        $currency = $this->currency->findOrFail($id);

        $currency->update([
            'name' => $input->name,
            'exchange_rate' => $this->money->toStoredMoney($input->exchange_rate)
        ]);

        return ["id" => $currency->id];
    }
}