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
    * Lista usuario.
    *
    * 
    */
    public function getUser(){
      $result = $this->user->getUser();
      return $result;
    }


    /**
    * Lista usuario por id.
    *
    * $id
    */
    public function getUserId($id){
      $result = $this->user->getUserId($id);
      return $result;
    }

    /**
    * Adicionar usuario.
    *
    * $request
    */
    public function create(Request $request)
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

    /**
    * Atualização de usuario.
    * 
    * $params
    */
    public function update(Request $request, $id) {
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

      $result = $this->user->update($params, $id);
      
      return $result;
    }

    /**
    * Excluir usuario.
    * 
    * $id
    */
    public function destroy($id) {
      $result = $this->user->destroy($id);
      return $result;
    }

}