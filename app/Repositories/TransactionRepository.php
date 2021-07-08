<?php

namespace App\Repositories;
use App\Interfaces\TransactionInterface;
use App\Transactions;
use App\TransactionsUsers;
use App\Wallets;

class TransactionRepository implements TransactionInterface {
    
    /**
    * Injeção de dependencias, criação da transação, regras de negocio.
    *
    * $params
    */
    public function create($params) {
        $transaction = new Transactions;
        $transactionUser = new TransactionsUsers;

        if($params->typeTransaction == 'wallet_add'){
            $transaction->id_wallet      = $params->id_wallet;
            $transaction->value          = $params->value;
            $transaction->dt_transaction = date('Y-m-d h:i:s');
            $transaction->status         = 'pendente';
            $transaction->save();

            $transactionUser->id_transaction = $transaction->id;
            $transactionUser->id_user_payer = $params->id_user;
            $transactionUser->save();
            $message = "Saldo adicionado a carteira";
        }

        if($params->typeTransaction == 'transaction_add'){
            $walletPaye  = Wallets::with('user.wallet')->where('id_user', $params->id_user_paye)->first();
            $walletPayee = Wallets::with('user.wallet')->where('id_user', $params->id_user_payee)->first();
            
            // verifica se existe usuario pagador
            if(!$walletPaye){
                return response()->json([
                    'message' => 'Usuário pagador não encontrado.'
                ], 400);
            } 
            
            // verifica se existe usuario recebedor
            if(!$walletPayee){
                return response()->json([
                    'message' => 'Usuário recebedor não encontrado.'
                ], 400);
            }             

            // verifica tipo de usuario
            if($walletPaye->user->type == 'L'){
                return response()->json([
                    'message' => 'Usuário sem permissão para fazer este tipo de operação'
                ], 400);
            }

            if($walletPaye->user->type == 'C'){
                // verificar saldo na carteira
                if($walletPaye->balance < $params->value){
                    return response()->json([
                        'message' => 'Saldo insuficiente para fazer a transação'
                    ], 400);
                }
                $transaction->id_wallet      = $walletPayee->id;
                $transaction->value          = $params->value;
                $transaction->dt_transaction = date('Y-m-d h:i:s');
                $transaction->status         = 'pendente';
                $transaction->save();
    
                $transactionUser->id_transaction = $transaction->id;
                $transactionUser->id_user_payer  = $params->id_user_paye;
                $transactionUser->id_user_payee  = $params->id_user_payee;
                $transactionUser->save();

                $updateBalanceWalletPaye = $walletPaye->balance - $params->value;
                $updateBalanceWalletPayee = $walletPayee->balance + $params->value;

                $walletPaye->balance = $updateBalanceWalletPaye;
                $walletPaye->save();

                $walletPayee->balance = $updateBalanceWalletPayee;
                $walletPayee->save();

                $message = "Transaferencia Efetuada com sucesso";
            }
        }

        if($transaction){
            return response()->json([
                'message' => $message
            ], 201);
        }
    }    
}
?>