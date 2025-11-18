<?php
require_once("conexion.php");

class EncabezadoPrestamo extends Conectar {

    // Obtener todos los préstamos
    public function obtener_prestamos() {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "SELECT e.id, e.cedulaCliente, c.nombre AS nombreCliente, e.fechaPrestamo
                FROM encabezadoprestamo e
                LEFT JOIN cliente c ON e.cedulaCliente = c.cedula";

        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un préstamo por ID
    public function obtener_prestamo_por_id($id) {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "SELECT * FROM encabezadoprestamo WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Insertar préstamo
    public function insertar_prestamo($cedulaCliente, $fechaPrestamo) {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "INSERT INTO encabezadoprestamo (cedulaCliente, fechaPrestamo) VALUES (?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $cedulaCliente);
        $stmt->bindValue(2, $fechaPrestamo);
        $stmt->execute();

        return $conexion->lastInsertId();
    }

    // Actualizar préstamo
    public function actualizar_prestamo($id, $cedulaCliente, $fechaPrestamo) {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "UPDATE encabezadoprestamo SET cedulaCliente = ?, fechaPrestamo = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $cedulaCliente);
        $stmt->bindValue(2, $fechaPrestamo);
        $stmt->bindValue(3, $id);
        $stmt->execute();
    }

    // Eliminar préstamo
    public function eliminar_prestamo($id) {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "DELETE FROM encabezadoprestamo WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
    }
}
?>
