<?php

Route::resource('category', 'CategoryController', ['only' => ['index', 'store', 'update', 'destroy']]);

Route::resource('currency', 'CurrencyController', ['only' => ['index', 'store', 'update', 'destroy']]);

Route::resource('account', 'AccountController', ['only' => ['index', 'store', 'update', 'destroy']]);

Route::resource('credit_card', 'CreditCardController', ['only' => ['index', 'store', 'update', 'destroy']]);

Route::resource('estimation', 'EstimationController', ['only' => ['index', 'store', 'update', 'destroy']]);

Route::resource('statement', 'StatementController', ['only' => ['index', 'store', 'update']]);
