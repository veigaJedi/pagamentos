<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\UserInterface;
use stdClass;

class UserController extends Controller
{
    protected $user;

    public function __construct(UserInterface $user)
    {
      $this->user = $user;
    }

    /**
    * Adicionar usuario.
    *
    * $request
    */
    public function store(Request $request)
    {
      // Valida Request
      $this->validate($request, [
          'name' => 'required',
          'email' => 'required|email',
          // 'cpf' => 'nullable|required|min:11|max:11',
          // 'cnpj' => 'nullable|required|min:14|max:14',
          'password' => 'required'
      ]);

      $params = new \stdClass();
      if($request->type === 'C'){
      		$params->cpf   = $request->cpf;
          $params->cnpj  = '';
      }

      if($request->type === 'L'){
          $params->cpf   = '';
          $params->cnpj  = $request->cnpj;
      }
      
      $params->email     = $request->email;
      $params->name      = $request->name;
      $params->type      = $request->type;
      $params->password  = $request->password;
      
      $result = $this->user->create($params);
      
      return $result;
    }
}