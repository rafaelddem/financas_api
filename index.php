<?php

error_reporting(E_ALL ^ E_WARNING);

require_once "vendor/autoload.php";

use financas_api\controller\Controller;
use financas_api\controller\Response;
use financas_api\controller\Route;
use financas_api\model\businessObject\Owner;
use financas_api\model\businessObject\PaymentMethod;
use financas_api\model\businessObject\Transaction;
use financas_api\model\businessObject\TransactionType;
use financas_api\model\businessObject\Wallet;

try {
    Route::addPost('/owner', new Controller(Owner::class, 'create'));
    Route::addPut('/owner', new Controller(Owner::class, 'update'));
    Route::addDelete('/owner', new Controller(Owner::class, 'delete'));
    Route::addGet('/owner', new Controller(Owner::class, 'find'));
    // Route::add('/owner/wallets', new Controller(Owner::class, 'findWallets'));

    Route::addPost('/wallet', new Controller(Wallet::class, 'create'));
    Route::addPut('/wallet', new Controller(Wallet::class, 'update'));
    Route::addDelete('/wallet', new Controller(Wallet::class, 'delete'));
    Route::addGet('/wallet', new Controller(Wallet::class, 'find'));

    Route::addPost('/payment_method', new Controller(PaymentMethod::class, 'create'));
    Route::addPut('/payment_method', new Controller(PaymentMethod::class, 'update'));
    Route::addDelete('/payment_method', new Controller(PaymentMethod::class, 'delete'));
    Route::addGet('/payment_method', new Controller(PaymentMethod::class, 'find'));

    Route::addPost('/transaction_type', new Controller(TransactionType::class, 'create'));
    Route::addPut('/transaction_type', new Controller(TransactionType::class, 'update'));
    Route::addDelete('/transaction_type', new Controller(TransactionType::class, 'delete'));
    Route::addGet('/transaction_type', new Controller(TransactionType::class, 'find'));

    Route::addPost('/transaction', new Controller(Transaction::class, 'create'));
    Route::addPut('/transaction', new Controller(Transaction::class, 'update'));
    Route::addDelete('/transaction', new Controller(Transaction::class, 'delete'));
    Route::addGet('/transaction', new Controller(Transaction::class, 'find'));

    Route::getPath();
} catch (\Throwable $th) {
    Response::send(['code' => $th->getCode(), 'message' => $th->getMessage()], true, 404);
}

?>