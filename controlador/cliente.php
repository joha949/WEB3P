<?php

header("Content-Type: application/json");

// Conexión y modelo
require_once("../configuracion/conexion.php");
require_once("../modelos/Cliente.php");

$cliente = new Cliente();

$body = json_decode(file_get_contents("php://input"), true);

switch ($_GET["op"]) {

    case "ObtenerTodos":
        $datos = $cliente->obtener_clientes();
        echo json_encode($datos);
        break;

    case "ObtenerPorCedula":
        $datos = $cliente->obtener_cliente_por_cedula($body["cedula"]);
        echo json_encode($datos);
        break;

    case "Insertar":
        $cliente->insertar_cliente(
            $body["cedula"],
            $body["nombre"],
            $body["telefono"]
        );
        echo json_encode(["Correcto" => "Cliente registrado"]);
        break;

    case "Actualizar":
        $cliente->actualizar_cliente(
            $body["cedula"],
            $body["nombre"],
            $body["telefono"]
        );
        echo json_encode(["Correcto" => "Cliente actualizado"]);
        break;

    case "Eliminar":
        $cliente->eliminar_cliente($body["cedula"]);
        echo json_encode(["Correcto" => "Cliente eliminado"]);
        break;
}
?>
