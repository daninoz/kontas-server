<?php

namespace App\Services;

use App\Helpers\MoneyHelper;
use Validator;
use App\Currency;

class CurrencyService
{
    protected $currency;

    protected $money;

    public function __construct(Currency $currency, MoneyHelper $money)
    {
        $this->currency = $currency;
        $this->money = $money;
    }

    public function getList()
    {
        $currencies = $this->currency->all();

        $response = $currencies->map(function ($item) {
            return [
                "id" => $item->id,
                "name" => $item->name,
                "exchange_rate" => $this->money->fromStoredCurrency($item->exchange_rate),
            ];
        });

        return $response;
    }

    public function validateInput($input, $update = false, $id = null)
    {
        $rules = [
            'name' => ['required', 'max:100', 'unique:currencies,name'],
            'exchange_rate' => ['required', 'numeric']
        ];

        if ($update) {
            $rules['name'][2] .= "," . $id;
        }

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            throw new \Exception;
        }
    }

    public function create($input)
    {
        $currency = $this->currency->create([
            'name' => $input->name,
            'exchange_rate' => $this->money->toStoredCurrency($input->exchange_rate)
        ]);

        return ["id" => $currency->id];
    }

    public function update($input, $id)
    {
        $currency = $this->currency->findOrFail($id);

        $currency->update([
            'name' => $input->name,
            'exchange_rate' => $this->money->toStoredCurrency($input->exchange_rate)
        ]);

        return ["id" => $currency->id];
    }
}