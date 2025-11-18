<?php
header("Content-Type: application/json");

require_once("...configuracion/conexion.php");
require_once("../modelos/EncabezadoPrestamo.php");
require_once("../modelos/DetallePrestamo.php");

$encabezado = new EncabezadoPrestamo();
$detalle = new DetallePrestamo();
$body = json_decode(file_get_contents("php://input"), true);

switch ($_GET["op"]) {

    // Encabezado
    case "ObtenerPrestamosDetalles":
        $prestamos = $encabezado->obtener_prestamos();
        $resultado = [];

        foreach($prestamos as $p) {
            $detalles = $detalle->obtener_detalles_por_prestamo($p['id']);
            if(count($detalles) > 0) {
                foreach($detalles as $d) {
                    $resultado[] = [
                        "id" => $p["id"],
                        "cedulaCliente" => $p["cedulaCliente"],
                        "nombreCliente" => $p["nombreCliente"] ?? "",
                        "codigoLibro" => $d["codigoLibro"],
                        "nombreLibro" => $d["nombreLibro"],
                        "fechaPrestamo" => $p["fechaPrestamo"],
                        "fechaDevolucion" => $d["fechaDevolucion"]
                    ];
                }
            } else {
                $resultado[] = [
                    "id" => $p["id"],
                    "cedulaCliente" => $p["cedulaCliente"],
                    "nombreCliente" => $p["nombreCliente"] ?? "",
                    "codigoLibro" => "",
                    "nombreLibro" => "",
                    "fechaPrestamo" => $p["fechaPrestamo"],
                    "fechaDevolucion" => ""
                ];
            }
        }
        echo json_encode($resultado);
        break;

    case "ObtenerPrestamos":
        echo json_encode($encabezado->obtener_prestamos());
        break;

    case "ObtenerPrestamoPorId":
        echo json_encode($encabezado->obtener_prestamo_por_id($body["id"]));
        break;

    case "InsertarPrestamo":
        $idPrestamo = $encabezado->insertar_prestamo($body["cedulaCliente"], $body["fechaPrestamo"]);
        if(isset($body["detalles"])) {
            foreach($body["detalles"] as $d) {
                $detalle->insertar_detalle($idPrestamo, $d["codigoLibro"], $d["fechaDevolucion"]);
            }
        }
        echo json_encode(["Correcto" => "Préstamo registrado"]);
        break;

    case "ActualizarPrestamo":
        $encabezado->actualizar_prestamo($body["id"], $body["cedulaCliente"], $body["fechaPrestamo"]);
        echo json_encode(["Correcto" => "Préstamo actualizado"]);
        break;

    case "EliminarPrestamo":
        $encabezado->eliminar_prestamo($body["id"]);
        echo json_encode(["Correcto" => "Préstamo eliminado"]);
        break;

    // Detalles
    case "ObtenerTodosDetalles":
        echo json_encode($detalle->obtener_todos_los_detalles());
        break;

    case "InsertarDetalle":
        $detalle->insertar_detalle($body["idPrestamo"], $body["codigoLibro"], $body["fechaDevolucion"]);
        echo json_encode(["Correcto" => "Detalle registrado"]);
        break;

    case "ActualizarDetalle":
        $detalle->actualizar_detalle($body["id"], $body["idPrestamo"], $body["codigoLibro"], $body["fechaDevolucion"]);
        echo json_encode(["Correcto" => "Detalle actualizado"]);
        break;

    case "EliminarDetalle":
        $detalle->eliminar_detalle($body["id"]);
        echo json_encode(["Correcto" => "Detalle eliminado"]);
        break;
}
?>
