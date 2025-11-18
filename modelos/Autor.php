<?php
// Clase Autor hereda de la clase Conectar
class Autor extends Conectar {

    public function obtener_autores() {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $consulta_sql = "SELECT * FROM autor";   
        $consulta = $conexion->prepare($consulta_sql);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);   
    }


    // Obtiene un autor específico por su ID
    public function obtener_autor_por_id($id_autor) {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $consulta_sql = "SELECT * FROM autor WHERE id = ?";
        $consulta = $conexion->prepare($consulta_sql);
        $consulta->bindValue(1, $id_autor);
        $consulta->execute();

        return $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    // Inserta un nuevo autor
    public function insertar_autor($nombre, $nacionalidad) {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sentencia_sql = "INSERT INTO autor (id, nombre, nacionalidad) VALUES (NULL, ?, ?)";
        $sentencia = $conexion->prepare($sentencia_sql);
        $sentencia->bindValue(1, $nombre);
        $sentencia->bindValue(2, $nacionalidad);
        $sentencia->execute();

        // No hay fetchAll porque un INSERT no devuelve resultados
        return true;
    }

    // Actualiza un autor existente
    public function actualizar_autor($id_autor, $nombre, $nacionalidad) {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sentencia_sql = "UPDATE autor SET nombre = ?, nacionalidad = ? WHERE id = ?";
        $sentencia = $conexion->prepare($sentencia_sql);
        $sentencia->bindValue(1, $nombre);
        $sentencia->bindValue(2, $nacionalidad);
        $sentencia->bindValue(3, $id_autor);
        $sentencia->execute();

        return true;
    }

    // Elimina un autor (eliminación física)
    public function eliminar_autor($id_autor) {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sentencia_sql = "DELETE FROM autor WHERE id = ?";
        $sentencia = $conexion->prepare($sentencia_sql);
        $sentencia->bindValue(1, $id_autor);
        $sentencia->execute();

        return true;
    }

}
?>
