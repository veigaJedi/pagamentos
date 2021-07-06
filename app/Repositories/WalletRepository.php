<?php

namespace App\Repositories;
use App\Interfaces\WalletInterface;
use App\Wallets;

class WalletRepository implements WalletInterface {
    
    /**
    * Injeção de dependencias, criação de cateria, regras de negocio.
    *
    * $id_user
    */
    public function create($id_user) {
      $wallets = new Wallets();

      $wallets->id_user  = $id_user;
      $wallets->balance  = '0.00';
      $wallets->status  = 'active';
      $wallets->save();
      
      if($wallets){
        return response()->json([
          'message' => 'Ok',
        ], 201);
      }
    }

    /**
    * Adição de saldo na carteira
    *
    * $params
    */
    public function add($params) {
      $wallet = Wallets::with('user.wallet')->where('id_user', $params->id_user)->first();
      if($wallet->user->type == 'L'){
        return response()->json([
          'message' => 'Usuario do tipo lojista não pode fazer este tipo de operação',
        ], 201);
      }
      $saldoAtual = $wallet->balance;
      $saldoAtualizado = $saldoAtual + $params->value;

      $wallet->id_user  = $params->id_user;
      $wallet->balance  = $saldoAtualizado;
      $wallet->save();
  
      if($wallet){
        // criar transação
        $transaction = new TransactionRepository;
        
        $data = new \stdClass();
        $data->id_wallet       = $wallet->id;
        $data->id_user         = $params->id_user;
        $data->value           = $params->value;
        $data->typeTransaction = 'wallet_add';

        $transaction->create($data);

        return response()->json([
          'message' => 'Saldo Inserido com sucesso',
        ], 201);
      }
    }

    /**
    * Ver Carteira por usuario
    *
    * $idUser
    * return $data
    */
    public function getWallet($idUser) {
      $wallet = Wallets::with('user.wallet')->where('id_user', $idUser)->first();
      
      $data = array(
        "id_user" => $wallet->user->id,
        "name" => $wallet->user->name,
        "balance" => $wallet->balance,
      );

      if($wallet){
        return response()->json([
          $data
        ], 201);
      }
    }     
}
?>