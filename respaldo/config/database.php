<?php
/**este archivo es para la conexion de la pagina con la base de datos en phpmyadmin */


class Database {
/**aqui se hace la conexion */
    private $hostname = "localhost";
    private $database = "tienda_online";
    private $username = "root";
    private $password = "";
    private $charset = "utf8";

    function conectar(){
/**esto es necesario para hacer la conexion */
        try{
        $conexion = "mysql:host=" . $this->hostname . "; dbname=" . $this->database . "; charset=" . $this->charset;
        $options = [
            /**esto es para que genereexcepciones en caso de que haya un error con la conexion */
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false       /**esto es una configuraicoin qpara que las preparaciones de la consultas no sean emuladas y tengan seguridad */
        ];
    
        $pdo = new PDO($conexion, $this->username, $this->password, $options);

        return $pdo;
        }catch(PDOException $e){
            echo 'error conexion: ' . $e->getMessage();
            exit;
        }
    }
    
}



?>