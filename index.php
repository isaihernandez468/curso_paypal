<?php
/**aqui se conecta con el archivo database.php */
    

    require 'config/config.php';
    require 'config/database.php';
    $db = new Database();
    $con = $db->conectar();
/**aqui se esta creando consultas preparadas */
    $sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1");
    $sql->execute();
    /**aqui se esta llamando a todos los rroductos de la tabla y asociaro¿los mediante el nombre de las columnas */
    $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

    /*session_destroy();*/
    
    print_r($_SESSION);

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
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <!--aqui en php se van a visualizar los productos dinamicamente-->
                <?php foreach($resultado as $row) {?>
                    <div class="col">
                        <div class="card shadow-sm">
                            <!--este php es para ias imagenes y que vayan cambiando-->
                            <?php  
                            
                            $id = $row['id'];
                            $imagen = "images/productos/" . $id . "/principal.jpg";

                            if(!file_exists($imagen)) {
                                $imagen = "images/productos/1/principal.jpg";
                            }
                            ?>
                            <!--aqui estamos mostrnado las imagenenes como normalmebte se hace en html pero se muestra la imagen en php-->
                            <img src="<?php echo $imagen; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['nombre']; ?></h5>
                                <p class="card-text">$ <?php echo number_format($row['precio'], 2, '.', ','); ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="detalles.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>" class="btn btn-primary">Detalles</a>   <!--el hash_hmac es para poder cifrar una contraseña-->
                                    </div>
                                    <button class="btn btn-outline-success" type="button" onclick="addProducto(<?php echo $row['id'];  ?>, '<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>')" >Agregar al carrito</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
    </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
<script>
    function addProducto(id, token){
        /**aqui se configuro la peticion ajax y se esta enviando por metodo POST */
        let url = 'clases/carrito.php';
        let formData = new FormData();  /**este ayuda a enviar los parametros mediante metodo post */
        formData.append('id', id);
        formData.append('token', token);

        fetch(url, {
            method:'POST',
            body: formData,
            mode: 'cors'
        }).then(response => response.json())
        .then(data => {
            if(data.ok){
                let elemento = document.getElementById("num_cart")
                elemento.innerHTML = data.numero
            }
        })
    }
</script>

</body>
</html>