<?php

$app->group(['prefix' => 'api', 'namespace' => 'App\Http\Controllers'], function () use ($app) {
    $app->get('category', 'CategoryController@index');
    $app->post('category', 'CategoryController@store');
    $app->put('category/{id}', 'CategoryController@update');
    $app->delete('category/{id}', 'CategoryController@destroy');

    $app->get('currency', 'CurrencyController@index');
    $app->get('currency/{id}', 'CurrencyController@get');
    $app->post('currency', 'CurrencyController@store');
    $app->put('currency/{id}', 'CurrencyController@update');
    $app->delete('currency/{id}', 'CurrencyController@destroy');

    $app->get('account', 'AccountController@index');
    $app->get('account/{id}', 'AccountController@get');
    $app->post('account', 'AccountController@store');
    $app->put('account/{id}', 'AccountController@update');
    $app->delete('account/{id}', 'AccountController@destroy');

    $app->get('credit_card', 'CreditCardController@index');
    $app->post('credit_card', 'CreditCardController@store');
    $app->put('credit_card/{id}', 'CreditCardController@update');
    $app->delete('credit_card/{id}', 'CreditCardController@destroy');

    $app->get('estimation', 'EstimationController@index');
    $app->post('estimation', 'EstimationController@store');
    $app->put('estimation/{id}', 'EstimationController@update');
    $app->delete('estimation/{id}', 'EstimationController@destroy');

    $app->get('statement', 'StatementController@index');
    $app->post('statement', 'StatementController@store');
    $app->put('statement/{id}', 'StatementController@update');

    $app->post('income', 'IncomeController@store');
    $app->put('income/{id}', 'IncomeController@update');
    $app->delete('income/{id}', 'IncomeController@destroy');

    $app->post('expense', 'ExpenseController@store');
});