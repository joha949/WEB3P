<?php
header("Content-Type: application/json");

// -----------------------------------------------------
//   IMPORTAR DEPENDENCIAS
// -----------------------------------------------------
require_once("../configuracion/conexion.php");
require_once("../modelos/Autor.php");
require_once("../modelos/Usuarios.php");

// -----------------------------------------------------
//   VALIDAR CABECERA 'cedula'
// -----------------------------------------------------
$encabezados = getallheaders();
if (!isset($encabezados['cedula'])) {
    echo json_encode(["error" => "Acceso no autorizado: cabecera 'cedula' requerida"]);
    exit();
}

// -----------------------------------------------------
//   OBTENER DATOS DEL USUARIO Y LLAVE SECRETA
// -----------------------------------------------------
$usuario = new Usuarios();
$cedula = $encabezados['cedula'];

$usuario_db = $usuario->obtener_por_cedula($cedula);

if (!$usuario_db || !isset($usuario_db['llave'])) {
    echo json_encode(["error" => "Acceso no autorizado: cédula no encontrada o sin llave"]);
    exit();
}

$clave_secreta_usuario = $usuario_db['llave'];

// -----------------------------------------------------
//   FUNCION PARA DESENCRIPTAR EL BODY
// -----------------------------------------------------
function Desencriptar_BODY($JSON, $clave)
{
    $cifrado = "aes-256-ecb";

    if (empty($JSON) || empty($clave)) return false;

    $decoded = base64_decode($JSON, true);
    if ($decoded === false) return false;

    return openssl_decrypt($decoded, $cifrado, $clave, OPENSSL_RAW_DATA);
}

// -----------------------------------------------------
//   PROCESAR EL BODY ENCRIPTADO SI EXISTE
// -----------------------------------------------------
$body_encriptado = file_get_contents("php://input");

if (!empty($body_encriptado)) {
    $desencriptado = Desencriptar_BODY($body_encriptado, $clave_secreta_usuario);
    $body = json_decode($desencriptado, true);

    if ($body === null) {
        echo json_encode(["Error" => "Error al desencriptar los datos del body"]);
        exit();
    }

} else {
    $body = []; // Body vacío para operaciones GET
}

// -----------------------------------------------------
//   VALIDAR 'op' EN LA URL
// -----------------------------------------------------
if (!isset($_GET["op"])) {
    echo json_encode([
        "error" => "Operación no válida: falta parámetro 'op'",
        "debug_url" => $_SERVER['REQUEST_URI']
    ]);
    exit();
}

$op = trim($_GET["op"]);

// -----------------------------------------------------
//   MODELO AUTOR
// -----------------------------------------------------
$autor = new Autor();

// -----------------------------------------------------
//   SWITCH DE OPERACIONES
// -----------------------------------------------------
switch ($op) {

    case "ObtenerTodos":
        $datos = $autor->obtener_autores();
        echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        break;

    case "ObtenerPorId":
        if (!isset($body["id"])) {
            echo json_encode(["error" => "Falta parámetro 'id'"]);
            exit();
        }
        $datos = $autor->obtener_autor_por_id($body["id"]);
        echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        break;

    case "Insertar":
        if (!isset($body["nombre"]) || !isset($body["nacionalidad"])) {
            echo json_encode(["error" => "Datos incompletos para insertar"]);
            exit();
        }
        $autor->insertar_autor($body["nombre"], $body["nacionalidad"]);
        echo json_encode(["Correcto" => "Autor insertado correctamente"]);
        break;

    case "Actualizar":
        if (!isset($body["id"]) || !isset($body["nombre"]) || !isset($body["nacionalidad"])) {
            echo json_encode(["error" => "Datos incompletos para actualizar"]);
            exit();
        }
        $autor->actualizar_autor($body["id"], $body["nombre"], $body["nacionalidad"]);
        echo json_encode(["Correcto" => "Autor actualizado correctamente"]);
        break;

    case "Eliminar":
        if (!isset($body["id"])) {
            echo json_encode(["error" => "Falta parámetro 'id'"]);
            exit();
        }
        $autor->eliminar_autor($body["id"]);
        echo json_encode(["Correcto" => "Autor eliminado correctamente"]);
        break;

    default:
        echo json_encode([
            "error" => "Operación no válida",
            "valor_op" => $op
        ]);
        break;
}
?>
