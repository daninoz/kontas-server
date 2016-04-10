<?php

namespace App\Services;

use App\Account;

class AccountService
{
    /**
     * Account model
     *
     * @var Account
     */
    protected $account;

    /**
     * AccountService constructor.
     *
     * @param Account    $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * Get an account
     * 
     * @param $id
     *
     * @return mixed
     */
    public function get($id)
    {
        return $this->account->findOrFail($id);
    }

    /**
     * Get a list of accounts
     *
     * @return static
     */
    public function getList()
    {
        $accounts = $this->account->all();

        $response = $accounts->map(function ($item) {
            return [
                "id" => $item->id,
                "name" => $item->name,
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
            'name' => ['required', 'max:100', 'unique:accounts,name'],
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
     * Creates a new account
     *
     * @param $input
     *
     * @return array
     */
    public function create($input)
    {
        $account = $this->account->create([
            'name' => $input->name
        ]);

        return ["id" => $account->id];
    }

    /**
     * Updates a account
     *
     * @param $input
     * @param $id
     *
     * @return array
     */
    public function update($input, $id)
    {
        $account = $this->account->findOrFail($id);

        $account->update([
            'name' => $input->name
        ]);

        return ["id" => $account->id];
    }
}