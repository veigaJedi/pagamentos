<?php

namespace App\Repositories;
use App\Interfaces\WalletInterface;
use App\Wallets;

class WalletRepository implements WalletInterface {
    
    /**
    * Injeção de dependencias, criação de cateria, regras de negocio.
    *
    * $params
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

    
}
?>