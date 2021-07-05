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
}