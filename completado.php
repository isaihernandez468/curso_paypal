<?php

    require 'config/config.php';
    require 'config/database.php';
    $db = new Database();
    $con = $db->conectar();

    $id_transaccion = isset($_GET['key']) ? $_GET['key'] : '0';

    $error = '';
    if($id_transaccion == ''){
        $error = 'Error al procesar la peticion';
    } else {

            $sql = $con->prepare("SELECT count(id) FROM compra WHERE id_transaccion=? AND status=?");
            $sql->execute([$id_transaccion, 'COMPLETED']);
            /**aqui se esta llamando a todos los rroductos de la tabla y asociaro¿los mediante el nombre de las columnas */
            if($sql->fetchColumn() > 0) {

                $sql = $con->prepare("SELECT id, fecha, email, total FROM compra WHERE id_transaccion=? AND status=? LIMIT 1");
                $sql->execute([$id_transaccion, 'COMPLETED']);
                /**esto es para que traiga lo que encontro */
                $row = $sql->fetch(PDO::FETCH_ASSOC);

                $idCompra = $row['id'];
                $total = $row['total'];
                $fecha = $row['fecha'];

                $sqlDet = $con->prepare("SELECT nombre, precio, cantidad FROM detalle_compra WHERE id_compra = ?");
                $sqlDet->execute([$idCompra]);
            } else {
                $error = 'Error al comprobar la compra';
            }
            
    }



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <div class="navbar  navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a href="#" class="navbar-brand">
                    <strong>Tienda Online</strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarHeader">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="#" class="nav-link active">Catalogo</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Contacto</a>
                        </li>
                    </ul>
                    <a href="checkout.php" class="btn btn-primary">
                        Carrito <span id="num_cart" class="badge bg_secondary"> <?php echo $num_cart; ?></span>
                    </a>
                </div>
            </div>
        </div>
    </header>
<!--contenido-->
    <main>
        <div class="container">

            <?php if(strlen($error) > 0){  ?>
                
                <div class="row">
                    <div class="col">
                        <h3><?php echo $error; ?></h3>
                    </div>
                </div>

            <?php } else {  ?>
                    
                    <div class="row">
                        <div class="col">
                            <b>Folio de la compra: </b><?php echo $id_transaccion; ?><br>
                            <b>Fecha de compra: </b><?php echo $fecha; ?><br>
                            <b>Total: </b><?php echo MONEDA . number_format($total, 2, '.', ','); ?><br>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Cantidad</th>
                                        <th>Producto</th>
                                        <th>Importe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row_det = $sqlDet->fetch(PDO::FETCH_ASSOC)) {
                                        $importe = $row_det['precio'] * $row_det['cantidad']; ?>
                                        <tr>
                                            <td><?php echo $row_det['cantidad']; ?></td>
                                            <td><?php echo $row_det['nombre']; ?></td>
                                            <td><?php echo $importe; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                    
                        </div>
                    </div>
                <?php } ?>
        </div>


    </main>
</body>
</html>
