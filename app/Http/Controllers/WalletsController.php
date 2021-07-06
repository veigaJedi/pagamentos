<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\WalletInterface;
use stdClass;


class WalletsController extends Controller
{

    protected $wallet;

    public function __construct(WalletInterface $wallet)
    {
      $this->wallet = $wallet;
    }

    /**
    * Adicionar carteira do usuario.
    *
    * $id_user
    */
    public function store($id_user)
    {

      $params = new \stdClass();

      $params->id_user  = $id_user;
      $params->balance  = '0.00';
      $params->status  = 'active';

      $result = $this->wallet->create($params);
      
      return $result;
    }

    /**
    * Adicionar saldo na carteira.
    *
    * $request
    */
    public function add(Request $request)
    {

      $params = new \stdClass();

      $params->id_user  = $request->id_user;
      $params->value  = $request->value;

      $result = $this->wallet->add($params);
      
      return $result;
    } 
    
    /**
    * Ver Carteira por usuario
    *
    * $request
    */
    public function getWallet($idUser)
    {
      $result = $this->wallet->getWallet($idUser); 
      return $result;
    }     
}