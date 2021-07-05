<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wallets;

class TransactionController extends Controller
{
  
    public function __construct()
    {
      
    }

    /**
    * Adicionar carteira do usuario.
    *
    * $request
    */
    public function store($id_user)
    {

      $wallets = new Wallets();

      $wallets->id_user  = $id_user;
      $wallets->balance  = '0.00';
      $wallets->status  = 'active';
      $wallets->save();
      
      return response()->json([
        'message' => 'Ok',
      ], 201);
    }
}