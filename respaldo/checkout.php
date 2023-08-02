<?php
/**aqui se conecta con el archivo database.php */
    require 'config/config.php';
    require 'config/database.php';
    $db = new Database();
    $con = $db->conectar();

    $productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;


    print_r($_SESSION);

    $lista_carrito = array();


    if($productos != null){
        foreach($productos as $clave => $cantidad){
            
            /**aqui se esta creando consultas preparadas */
    $sql = $con->prepare("SELECT id, nombre, precio, descuento, $cantidad AS cantidad FROM productos WHERE id=? AND activo=1");
    $sql->execute([$clave]);
    /**aqui se esta llamando a todos los rroductos de la tabla y asociaro¿los mediante el nombre de las columnas */
    $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);


    /*session_destroy();*/
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
            <a href="carrito.php" class="btn btn-primary">
                    Carrito <span id="num_cart" class="badge bg_secondary"> <?php echo $num_cart; ?></span>
            </a>
        </div>
        </div>
    </div>
    </header>
<!--contenido-->
    <main>
        <div class="container">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($lista_carrito == null){
                                echo '<tr><td colspan="5" class="text-center"><b>Lista Vacia</b></td></tr>';
                            } else{

                                $total = 0;
                                foreach($lista_carrito as $producto){
                                    $_id = $producto['id'];
                                    $nombre = $producto['nombre'];
                                    $precio = $producto['precio'];
                                    $descuento = $producto['descuento'];
                                    $cantidad = $producto['cantidad'];
                                    $precio_desc = $precio - (($precio * $descuento) / 100);
                                    $subtotal = $cantidad * $precio_desc;
                                    $total += $subtotal;
                        ?>

                            <tr>
                                <td><?php echo $nombre; ?></td>
                                <td><?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?></td>
                                <td>
                                    <input type="number" min="1" max="10" step="1" value="<?php echo $cantidad ?>" size="5" id="cantidad_<?php echo $_id; ?>" onchange="actualizaCantidad(this.value, <?php echo $_id ?>)">
                                </td>
                                <td>
                                    <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?></div>
                                </td>
                                <td>
                                    <a href="#" id="eliminar" class="btn btn-warning btn-sm" data-bs-id="<?php echo $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal">Eliminar</a>
                                </td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="3"></td>
                                <td colspan="2">
                                    <p class="h3" id="total"><?php echo MONEDA . number_format($total, 2 ,'.', ','); ?></p>
                                </td>
                            </tr>

                    </tbody>
                    <?php } ?>   

                </table>
            </div>

            <?php if($lista_carrito != null) {  ?>
                <div class="row">
                    <div class="col-md-5 offset-md-7 d-grid grap-2">
                        <a href="pago.php" class="btn btn-primary btn-lg">Realizar pago</a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>

    <!-- Modal -->
<div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="eliminaModalLabel">Alerta</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Desea eliminar el producto de la lista?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button id="btn-elimina" type="button" class="btn btn-danger" onclick="eliminar()">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
<script>

    let eliminaModal = document.getElementById('eliminaModal');
    eliminaModal.addEventListener('show.bs.modal', function(event){
        /**esta linea trae los deatos del vinculo de eliminar */
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina');
        buttonElimina.value = id;
    });


    function actualizaCantidad(cantidad, id){
        /**aqui se configuro la peticion ajax y se esta enviando por metodo POST */
        let url = 'clases/actualizar_carrito.php';
        let formData = new FormData();  /**este ayuda a enviar los parametros mediante metodo post */
        formData.append('action', 'agregar');
        formData.append('id', id);
        formData.append('cantidad', cantidad);

        fetch(url, {
            method:'POST',
            body: formData,
            mode: 'cors'
        }).then(response => response.json())
        .then(data => {
            if(data.ok){
                let divsubtotal = document.getElementById('subtotal_' + id);
                divsubtotal.innerHTML = data.sub;

                let total = 0.00
                let list = document.getElementsByName('subtotal[]');
                /**aqui recorremos todos los precios de subtotal para ir acumulando */
                for(let i = 0; i < list.length; i++){
                    total+= parseFloat(list[i].innerHTML.replace(/[$,]/g, ''));             

                }

                total = new Intl.NumberFormat('en-US', {
                    minimumFractionDigits: 2
                }).format(total)
                document.getElementById('total').innerHTML = '<?php echo MONEDA; ?>' + total
            }
        })
    }

    function eliminar(){
        let botonElimina = document.getElementById('btn-elimina');
        let id = botonElimina.value

        /**aqui se configuro la peticion ajax y se esta enviando por metodo POST */
        let url = 'clases/actualizar_carrito.php';
        let formData = new FormData();  /**este ayuda a enviar los parametros mediante metodo post */
        formData.append('action', 'eliminar');
        formData.append('id', id);

        fetch(url, {
            method:'POST',
            body: formData,
            mode: 'cors'
        }).then(response => response.json())
        .then(data => {
            if(data.ok){
                location.reload();
            }
        })
    }
</script>

</body>
</html>