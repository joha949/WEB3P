<?php
class Conectar {
    protected $conexion_bd;

    protected function conectar_bd() {
        try {            
            $host = getenv('DB_HOST');
            $db   = getenv('DB_NAME');
            $user = getenv('DB_USER');
            $pass = getenv('DB_PASSWORD');
            $port = getenv('DB_PORT');

            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8";

            $this->conexion_bd = new PDO($dsn, $user, $pass);
            return $this->conexion_bd;
        } catch (Exception $e) {
            print "Error de conexión: " . $e->getMessage();
            die();
        }
    }

    public function establecer_codificacion() {
        return $this->conexion_bd->query("SET NAMES 'utf8'");
    }
}
?>
