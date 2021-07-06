<?php

namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\User;

class UserRepository implements UserInterface {

    /**
    * Listagem de usuarios.
    *
    */
    public function getUser() {
      $result = User::orderBy('created_at', 'desc')->paginate(10);
      return response()->json($result,201);
    }
  
    /**
    * Listagem de usuario por id.
    * 
    * $id
    */
    public function getUserId($id) {
      $result = User::findOrFail($id);
      if($result){
        return response()->json($result,201);
      }else{
        return response()->json([
          'message' => 'Usuario a'
        ], 201);
      }
    }

    /**
    * Injeção de dependencias e regras de negocio de cadastro de usuario.
    *
    * $params
    */
    public function create($params) {
          // valida tipo de usuario comum
          if($params->type === 'C'){
            $verifyUserCpf = $this->verifyUserCpf($params->cpf);
            if($verifyUserCpf){
              return $verifyUserCpf;
            }
          }
    
          // valida tipo de usuario lojista
          if($params->type === 'L'){
            $verifyUserCnpj = $this->verifyUserCnpj($params->cnpj);
            if($verifyUserCnpj){
              return $verifyUserCnpj;
            }
          }
          
          // varifca se existe email cadastrado
          $verifyUserEmail = $this->verifyUserEmail($params->email);
          if($verifyUserEmail){
            return $verifyUserEmail;
          }
          
          $user = new User;
          $user->email     = $params->email;
          $user->name      = $params->name;
          $user->type      = $params->type;
          $user->cpf       = $params->cpf;
          $user->cnpj      = $params->cnpj;
          $user->password  = app('hash')->make($params->password);
          $user->save();          

          if($user){
            // criar carteira
            $wallet = new WalletRepository;
            $wallet->create($user->id);

            return response()->json([
                'message' => 'Usuario cadastrado com sucesso'
            ], 201);
          }
    }

    /**
    * Atualização de usuario.
    * 
    * $params, $id
    */
    public function update($params, $id) {
      $result = User::findOrFail($id);
      $result->name = $params->name;
      $result->save();
      
      if($result){
        return response()->json([
          'message' => 'Usuario atualizado com sucesso!',
        ], 201); 
      }
    }

    /**
    * Excluir usuario.
    * 
    * $id
    */
    public function destroy($id) {
      $result = User::findOrFail($id);
      $result->delete();
      
      if($result){
        return response()->json([
          'message' => 'Usuario excluido com sucesso!',
        ], 201); 
      }
    }

    /**
    * Verificar se existe cpf cadastrado.
    *
    * $cpf
    */
    public function verifyUserCpf($cpf){
        $result = User::where('cpf',$cpf)->first();
        if($result){
          return response()->json([
            'message' => 'Cpf já cadastrado!',
          ], 201);          
        }else{
          return false;
        }
    }

    /**
    * Verificar se existe cnpj cadastrado.
    *
    * $cnpj
    */    
    public function verifyUserCnpj($cnpj){
        $result = User::where('cnpj',$cnpj)->first();
        if($result){
          return response()->json([
            'message' => 'Cnpj já cadastrado!',
          ], 201);          
        }else{
          return false;
        }
    }

    /**
    * Verificar se existe email cadastrado.
    *
    * $cnpj
    */    
    public function verifyUserEmail($email){
        $result = User::where('email',$email)->first();
        if($result){
          return response()->json([
            'message' => 'Email já cadastrado!',
          ], 201);          
        }else{
          return false;
        }
    }
}

?>