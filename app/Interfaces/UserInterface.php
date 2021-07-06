<?php

namespace App\Interfaces;

Interface UserInterface {
    public function getUser();
    public function getUserId($id);
    public function create($params);
    public function update($params, $id);
    public function destroy($id);
}

?>