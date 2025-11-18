<?php
// Clase Usuarios hereda de la clase Conectar
class Usuarios extends Conectar {

  // Obtiene una Usuarios específica por su ID
    public function VerificarKEY($llave) {
        // Establece la conexión a la base de datos
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();
        
        // Consulta SQL para obtener un usuario específica por su llave
        $consulta_sql = "SELECT * FROM usuarios WHERE llave=?";

        // Prepara la consulta SQL
        $consulta = $conexion->prepare($consulta_sql);
        $consulta->bindValue(1, $llave);  // Asocia el valor de la llave del usuario

        // Ejecuta la consulta
        $consulta->execute();

        // Retorna el resultado como un array asociativo
    return $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

  // Obtiene un usuario por su cédula y devuelve una fila asociativa (o false si no existe)
  public function obtener_por_cedula($cedula) {
    $conexion = parent::conectar_bd();
    parent::establecer_codificacion();

    $consulta_sql = "SELECT * FROM usuarios WHERE cedula = ? LIMIT 1";
    $consulta = $conexion->prepare($consulta_sql);
    $consulta->bindValue(1, $cedula);
    $consulta->execute();

    return $consulta->fetch(PDO::FETCH_ASSOC);
  }

  // Devuelve solamente la llave (clave) de un usuario según su cédula
  public function obtener_llave_por_cedula($cedula) {
    $conexion = parent::conectar_bd();
    parent::establecer_codificacion();

    $consulta_sql = "SELECT llave FROM usuarios WHERE cedula = ? LIMIT 1";
    $consulta = $conexion->prepare($consulta_sql);
    $consulta->bindValue(1, $cedula);
    $consulta->execute();

    $row = $consulta->fetch(PDO::FETCH_ASSOC);
    if ($row && isset($row['llave'])) {
      return $row['llave'];
    }
    return false;
  }
}