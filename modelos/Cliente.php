<?php

class Cliente extends Conectar {

    // Obtener todos los clientes
    public function obtener_clientes() {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "SELECT * FROM cliente";

        $stmt = $conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un cliente por cédula
    public function obtener_cliente_por_cedula($cedula) {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "SELECT * FROM cliente WHERE cedula = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $cedula);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insertar cliente
    public function insertar_cliente($cedula, $nombre, $telefono) {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "INSERT INTO cliente (cedula, nombre, telefono) VALUES (?, ?, ?)";

        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $cedula);
        $stmt->bindValue(2, $nombre);
        $stmt->bindValue(3, $telefono);

        $stmt->execute();
    }

    // Actualizar cliente
    public function actualizar_cliente($cedula, $nombre, $telefono) {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "UPDATE cliente SET nombre = ?, telefono = ? WHERE cedula = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $nombre);
        $stmt->bindValue(2, $telefono);
        $stmt->bindValue(3, $cedula);

        $stmt->execute();
    }

    // Eliminar cliente
    public function eliminar_cliente($cedula) {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "DELETE FROM cliente WHERE cedula = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $cedula);

        $stmt->execute();
    }
}
?>
