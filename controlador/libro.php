<?php
header("Content-Type: application/json");
require_once("../configuracion/conexion.php");
require_once("../modelos/Libro.php");

$libro = new Libro();
$body = json_decode(file_get_contents("php://input"), true);
$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {

    case "GET":
        if (isset($_GET["codigo"])) {
            $datos = $libro->obtener_libro_por_codigo($_GET["codigo"]);
        } else {
            $datos = $libro->obtener_libros();
        }
        echo json_encode($datos);
    break;

    case "POST":
        $libro->insertar_libro(
            $body["codigo"],
            $body["nombre"],
            $body["idAutor"],
            $body["genero"]
        );
        echo json_encode(["mensaje" => "Libro registrado"]);
    break;

    case "PUT":
        $libro->actualizar_libro(
            $body["codigo"],
            $body["nombre"],
            $body["idAutor"],
            $body["genero"]
        );
        echo json_encode(["mensaje" => "Libro actualizado"]);
    break;

    case "DELETE":
        $libro->eliminar_libro($body["codigo"]);
        echo json_encode(["mensaje" => "Libro eliminado"]);
    break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
    break;
}
