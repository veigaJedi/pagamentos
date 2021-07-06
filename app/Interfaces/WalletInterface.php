<?php

namespace App\Interfaces;

Interface WalletInterface {
    public function create($id_user);
    public function add($params);
    public function getWallet($idUser);
}

?>