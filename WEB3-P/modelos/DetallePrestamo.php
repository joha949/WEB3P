<?php
require_once("conexion.php");

class DetallePrestamo extends Conectar {

    // Obtener todos los detalles
    public function obtener_todos_los_detalles() {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "SELECT * FROM detalleprestamo";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener detalles de un préstamo específico
    public function obtener_detalles_por_prestamo($idPrestamo) {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "SELECT d.id, d.codigoLibro, l.nombre AS nombreLibro, d.fechaDevolucion
                FROM detalleprestamo d
                INNER JOIN libros l ON d.codigoLibro = l.codigo
                WHERE d.idPrestamo = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $idPrestamo);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insertar detalle
    public function insertar_detalle($idPrestamo, $codigoLibro, $fechaDevolucion) {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "INSERT INTO detalleprestamo (idPrestamo, codigoLibro, fechaDevolucion) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $idPrestamo);
        $stmt->bindValue(2, $codigoLibro);
        $stmt->bindValue(3, $fechaDevolucion);
        $stmt->execute();
    }

    // Actualizar detalle
    public function actualizar_detalle($id, $idPrestamo, $codigoLibro, $fechaDevolucion) {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "UPDATE detalleprestamo SET idPrestamo = ?, codigoLibro = ?, fechaDevolucion = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $idPrestamo);
        $stmt->bindValue(2, $codigoLibro);
        $stmt->bindValue(3, $fechaDevolucion);
        $stmt->bindValue(4, $id);
        $stmt->execute();
    }

    // Eliminar detalle
    public function eliminar_detalle($id) {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "DELETE FROM detalleprestamo WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
    }
}
?>
