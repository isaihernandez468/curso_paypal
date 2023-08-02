<?php
/**este archivo es para cambiar el id de los productos */
define("CLIENT_ID", "AV_8DSb5FAoDvzx_Vhk9XoP-YDTrEvHCt9I_jcltJYk_DnEXU53W185ousOA5K-aT9jUKm2MEeQ6kWgQ");
define("CURRENCY", "MXN");
define("KEY_TOKEN", "*21082001Ihc");
define("MONEDA", "$");

/**esto es para que inicie la sesion una vez que se ingresa al portal */
session_start();

$num_cart = 0;
if(isset($_SESSION['carrito']['productos'])){
    $num_cart = count($_SESSION['carrito']['productos']);
}

?>