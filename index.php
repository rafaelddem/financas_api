<?php

error_reporting(E_ALL ^ E_WARNING);

require_once "vendor/autoload.php";

use api\controller\Route;
use financas_api\controller\Home;
use financas_api\model\businessObject\Installment;
use financas_api\model\businessObject\Owner;
use financas_api\model\businessObject\PaymentMethod;
use financas_api\model\businessObject\Reports;
use financas_api\model\businessObject\Transaction;
use financas_api\model\businessObject\TransactionType;
use financas_api\model\businessObject\Wallet;

Route::addGet('/', Home::class, 'home');
Route::addGet('/home', Home::class, 'home');
Route::addGet('/backup', Home::class, 'backup');

Route::addPost('/owner', Owner::class, 'create');
Route::addPut('/owner', Owner::class, 'update');
Route::addDelete('/owner', Owner::class, 'delete');
Route::addGet('/owner', Owner::class, 'find');

Route::addPost('/wallet', Wallet::class, 'create');
Route::addPut('/wallet', Wallet::class, 'update');
Route::addDelete('/wallet', Wallet::class, 'delete');
Route::addGet('/wallet', Wallet::class, 'find');

Route::addPost('/payment_method', PaymentMethod::class, 'create');
Route::addPut('/payment_method', PaymentMethod::class, 'update');
Route::addDelete('/payment_method', PaymentMethod::class, 'delete');
Route::addGet('/payment_method', PaymentMethod::class, 'find');

Route::addPost('/transaction_type', TransactionType::class, 'create');
Route::addPut('/transaction_type', TransactionType::class, 'update');
Route::addDelete('/transaction_type', TransactionType::class, 'delete');
Route::addGet('/transaction_type', TransactionType::class, 'find');

Route::addPost('/transaction', Transaction::class, 'create');
Route::addPut('/transaction', Transaction::class, 'update');
Route::addDelete('/transaction', Transaction::class, 'delete');
Route::addGet('/transaction', Transaction::class, 'find');

Route::addPut('/transaction/installment', Installment::class, 'update');
Route::addGet('/transaction/installment', Installment::class, 'find');

Route::addGet('/reports/calculatesTotals', Reports::class, 'calculatesTotals');

Route::getPath();

?>