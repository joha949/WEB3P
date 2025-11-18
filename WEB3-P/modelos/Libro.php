<?php
class Libro extends Conectar {

    // GET - Obtener todos los libros
    public function obtener_libros() {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "SELECT * FROM libros";
        $consulta = $conexion->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_libro_por_codigo($codigo) {
    $conexion = parent::conectar_bd();
    parent::establecer_codificacion();

    $sql = "SELECT * FROM libros WHERE codigo = ?";
    $consulta = $conexion->prepare($sql);
    $consulta->bindValue(1, $codigo);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

    // POST - Insertar libro
    public function insertar_libro($codigo, $nombre, $idAutor, $genero) {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "INSERT INTO libros (codigo, nombre, idAutor, genero) VALUES (?, ?, ?, ?)";
        $consulta = $conexion->prepare($sql);

        $consulta->bindValue(1, $codigo);
        $consulta->bindValue(2, $nombre);
        $consulta->bindValue(3, $idAutor);
        $consulta->bindValue(4, $genero);

        return $consulta->execute();
    }

    // PUT - Actualizar libro
    public function actualizar_libro($codigo, $nombre, $idAutor, $genero) {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "UPDATE libros SET nombre = ?, idAutor = ?, genero = ? WHERE codigo = ?";
        $consulta = $conexion->prepare($sql);

        $consulta->bindValue(1, $nombre);
        $consulta->bindValue(2, $idAutor);
        $consulta->bindValue(3, $genero);
        $consulta->bindValue(4, $codigo);

        return $consulta->execute();
    }

    // DELETE - Borrar libro
    public function eliminar_libro($codigo) {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "DELETE FROM libros WHERE codigo = ?";
        $consulta = $conexion->prepare($sql);

        $consulta->bindValue(1, $codigo);

        return $consulta->execute();
    }
}

?>
