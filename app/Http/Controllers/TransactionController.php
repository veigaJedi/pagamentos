<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transactions;
use App\Interfaces\TransactionInterface;

class TransactionController extends Controller
{
  	private $transaction;

    public function __construct(TransactionInterface $transaction)
    {
      $this->transaction = $transaction;
    }

    /**
    * Efetuar transações
    *
    * $id_user
    */
    public function create(Request $request)
    {

      $params = new \stdClass();
      $params->id_user_paye    = $request->id_user_paye;
      $params->id_user_payee   = $request->id_user_payee;
      $params->value           = $request->value;
      $params->typeTransaction = "transaction_add";

      $result = $this->transaction->create($params);
      
      return $result;
    }

}