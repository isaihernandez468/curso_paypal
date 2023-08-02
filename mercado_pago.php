<?php

require 'vendor/autoload.php';


MercadoPago\SDK::setAccessToken('TEST-4552717144952309-080119-1f89a9def09a088b4aa07a0ca61bacff-604804602');


$preference = new MercadoPago\Preference();

$item = new MercadoPago\Item();
$item->id = '0001';
$item->title = 'Producto CDP';
$item->quantity = 1;
$item->unit_price = 150.00;
$item->currency_id = "MXN";

$preference->items = array($item);

$preference->back_urls = array(
    "success" => "http://localhost/curso_paypal/captura.php",
    "failure" => "http://localhost/curso_paypal/fallo.php",

);

$preference->auto_return = "approved";
$preference->binary_mode = true;


$preference->save();



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <script src="https://sdk.mercadopago.com/js/v2"></script>
</head>
<body>

    <h3>Mercado pago</h3>

    <div class="checkout-btn"></div>

    <script>
        const mp = new MercadoPago('TEST-25522e66-e6b0-4406-b53a-bc4c2a7c4efc',{
            locale: 'es-MX'
        });

        mp.checkout({
            preference: {
                id: '<?php echo $preference->id; ?>'
            },
            render: {
                container: '.checkout-btn',
                label: 'Pagar con MP'
            }
        })
    </script>
    
</body>
</html>