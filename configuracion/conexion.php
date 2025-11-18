<?php
class Conectar {
    protected $conexion_bd;

    protected function conectar_bd() {
        try {
            // Variables reales de Railway
            $host = getenv('MYSQLHOST');
            $db   = getenv('MYSQLDATABASE');
            $user = getenv('MYSQLUSER');
            $pass = getenv('MYSQLPASSWORD');
            $port = getenv('MYSQLPORT');

            // Cadena DSN correcta
            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

            // Conexión
            $this->conexion_bd = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            return $this->conexion_bd;

        } catch (Exception $e) {
            echo json_encode([
                "error" => "Error de conexión",
                "detalles" => $e->getMessage(),
                "host" => $host,
                "db" => $db,
                "user" => $user,
                "port" => $port
            ]);
            die();
        }
    }

    public function establecer_codificacion() {
        return $this->conexion_bd->query("SET NAMES utf8mb4");
    }
}
