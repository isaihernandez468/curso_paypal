<?php

require '../config/config.php';

/**esto es  una validaicon con isset para saber si nos estan enviando una variable id*/
if(isset($_POST['id'])){

    $id = $_POST['id'];
    $token = $_POST['token'];

    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);
        
    if($token == $token_tmp){

        if(isset($_SESSION['carrito']['productos'][$id])){
            $_SESSION['carrito']['productos'][$id] += 1;     /** aqui se esta teniendo el producto 1 y va a tener una cantidad*/
        }else{
            $_SESSION['carrito']['productos'][$id] = 1;     /** aqui se esta teniendo el producto 1 y va a tener una cantidad*/
        }

        $datos['numero'] = count($_SESSION['carrito']['productos']);
        $datos['ok'] = true;
        

    }else{
        $datos['ok'] = false;
    }

}else{
    $datos['ok'] = false;
}

echo json_encode($datos);    /**qui estamos enviando los datos con formato json */


?>