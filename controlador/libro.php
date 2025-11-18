<?php
header("Content-Type: application/json");

require_once("../configuracion/conexion.php");
require_once("../modelos/Libro.php");

// Instancia del modelo
$libro = new Libro();

// Para recibir JSON (PUT/POST/DELETE)
$body = json_decode(file_get_contents("php://input"), true);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case "GET":

        // Si viene ?codigo=X → Obtener un libro
        if (isset($_GET["codigo"])) {
            $datos = $libro->obtener_libro_por_codigo($_GET["codigo"]);
            echo json_encode($datos);
            break;
        }

        // Si NO viene código → obtener todos
        $datos = $libro->obtener_libros();
        echo json_encode($datos);
        break;

    case "POST":
        $libro->insertar_libro(
            $body["codigo"],
            $body["nombre"],
            $body["idAutor"],
            $body["genero"]
        );
        echo json_encode(["Correcto" => "Libro insertado correctamente"]);
        break;

    case "PUT":
        $libro->actualizar_libro(
            $body["codigo"],
            $body["nombre"],
            $body["idAutor"],
            $body["genero"]
        );
        echo json_encode(["Correcto" => "Libro actualizado correctamente"]);
        break;

    case "DELETE":

        // 👉 ESTA LÍNEA ES EL ARREGLO REAL
        $codigo = $body["codigo"];  // PHP recibe DELETE por JSON, no GET

        $libro->eliminar_libro($codigo);
        echo json_encode(["Correcto" => "Libro eliminado correctamente"]);
        break;
}
?>
