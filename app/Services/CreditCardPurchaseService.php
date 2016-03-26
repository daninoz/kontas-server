<?php

namespace App\Services;

use App\Helpers\MoneyHelper;
use App\CreditCardPurchase;

class CreditCardPurchaseService
{
    protected $credit_card_purchase;

    protected $statement_service;

    protected $money;

    public function __construct(CreditCardPurchase $credit_card_purchase, StatementService $statement_service,
                                MoneyHelper $money)
    {
        $this->credit_card_purchase = $credit_card_purchase;
        $this->statement_service = $statement_service;
        $this->money = $money;
    }

    public function get($credit_card_id, $date, $amount, $number_installments)
    {
        $credit_card_purchase = $this->credit_card_purchase->newInstance();
        $credit_card_purchase->save();

        for ($installment = 0; $installment < $number_installments; $installment++) {
            if ($installment == 0) {
                $installment_amount = $this->getFirstInstallmentAmount($amount, $number_installments);
            } else {
                $installment_amount = $this->getInstallmentAmount($amount, $number_installments);
            }

            $statement = $this->statement_service->get($credit_card_id, $date, $installment);

            $credit_card_purchase->installments()->create([
                'amount' => $this->money->toStoredMoney($installment_amount),
                'statement_id' => $statement->id
            ]);
        }

        return $credit_card_purchase;
    }

    private function getFirstInstallmentAmount($amount, $number_installments)
    {
        $installmentAmount = $this->getInstallmentAmount($amount, $number_installments);

        return $installmentAmount + ($amount - ($installmentAmount * $number_installments));
    }

    private function getInstallmentAmount($amount, $number_installments)
    {
        return round($amount / $number_installments, 2);
    }
}