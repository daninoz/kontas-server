<?php

namespace App\Services;

use App\Helpers\MoneyHelper;
use Validator;
use App\Statement;

class StatementService
{
    /**
     * Statement model
     *
     * @var Statement
     */
    protected $statement;

    /**
     * StatementService constructor.
     *
     * @param Statement    $statement
     */
    public function __construct(Statement $statement)
    {
        $this->statement = $statement;
    }

    /**
     * Get a list of statements
     *
     * @return static
     */
    public function getList()
    {
        $statements = $this->statement->with('credit_card')->get();

        $response = $statements->map(function ($item) {
            return [
                "id" => $item->id,
                "period" => $item->period,
                "due_date" => $item->due_date,
                "deadline" => $item->deadline,
                "has_real_dates" => $item->has_real_dates,
                "credit_card" => $item->credit_card->name,
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
            "period" => ['required', 'date'],
            "due_date" => ['required', 'date'],
            "deadline" => ['required', 'date'],
            "has_real_dates" => ['boolean'],
            "credit_card_id" => ['required', 'exists:credit_cards,id'],
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            throw new \Exception;
        }
    }

    /**
     * Creates a new statement
     *
     * @param $input
     *
     * @return array
     */
    public function create($input)
    {
        $statement = $this->statement->create([
            "period" => $input->period,
            "due_date" => $input->due_date,
            "deadline" => $input->deadline,
            "has_real_dates" => $input->has_real_dates,
            "credit_card_id" => $input->credit_card_id,
        ]);

        return ["id" => $statement->id];
    }

    /**
     * Updates a statement
     *
     * @param $input
     * @param $id
     *
     * @return array
     */
    public function update($input, $id)
    {
        $statement = $this->statement->findOrFail($id);

        $statement->update([
            "period" => $input->period,
            "due_date" => $input->due_date,
            "deadline" => $input->deadline,
            "has_real_dates" => $input->has_real_dates,
            "credit_card_id" => $input->credit_card_id,
        ]);

        return ["id" => $statement->id];
    }
}