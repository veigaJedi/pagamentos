<?php

namespace App\Repositories;
use App\Interfaces\TransactionInterface;
use App\Transactions;
use App\TransactionsUsers;

class TransactionRepository implements TransactionInterface {
    
    /**
    * Injeção de dependencias, criação da transação, regras de negocio.
    *
    * $params
    */
    public function create($params) {
        $transaction = new Transactions;
        $transaction->id_wallet      = $params->id_wallet;
        $transaction->value          = $params->value;
        $transaction->dt_transaction = date('Y-m-d h:i:s');
        $transaction->status         = 'pendente';
        $transaction->save();

        if($params->typeTransaction == 'wallet_add'){
            $transactionUser = new TransactionsUsers;
            $transactionUser->id_transaction = $transaction->id;
            $transactionUser->id_user_payer = $params->id_user;
            $transactionUser->save();
        }

        if($transaction){
            return response()->json([
                'message' => ''
            ], 201);
        }
    }    
}
?>