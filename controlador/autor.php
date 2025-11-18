<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, cedula, Authorization, X-Requested-With");

// Respuesta rápida a solicitudes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

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

$cedula = $encabezados['cedula'];

$usuario = new Usuarios();
$usuario_db = $usuario->obtener_por_cedula($cedula);

if (!$usuario_db || !isset($usuario_db['llave'])) {
    echo json_encode(["error" => "Acceso no autorizado: cédula no encontrada o sin llave"]);
    exit();
}

$clave_secreta_usuario = $usuario_db['llave'];

// -----------------------------------------------------
//   FUNCION PARA DESENCRIPTAR EL BODY (AES-256-ECB)
// -----------------------------------------------------
function Desencriptar_BODY($JSON, $clave)
{
    if (empty($JSON)) return null;

    $decoded = base64_decode($JSON, true);
    if ($decoded === false) return null;

    return openssl_decrypt(
        $decoded,
        "aes-256-ecb",
        $clave,
        OPENSSL_RAW_DATA
    );
}

// -----------------------------------------------------
//   PROCESAR BODY
// -----------------------------------------------------
$body_encriptado = file_get_contents("php://input");

if (!empty($body_encriptado)) {
    $desencriptado = Desencriptar_BODY($body_encriptado, $clave_secreta_usuario);

    if ($desencriptado === false || $desencriptado === null) {
        echo json_encode(["error" => "Error al desencriptar el body"]);
        exit();
    }

    $body = json_decode($desencriptado, true);

    if (!is_array($body)) {
        echo json_encode(["error" => "JSON inválido tras desencriptación"]);
        exit();
    }
} else {
    $body = [];
}

// -----------------------------------------------------
//   VALIDAR 'op'
// -----------------------------------------------------
if (!isset($_GET["op"])) {
    echo json_encode([
        "error" => "Operación no válida: falta parámetro 'op'",
        "debug_url" => $_SERVER['REQUEST_URI']
    ]);
    exit();
}

$op = trim($_GET["op"]);
$autor = new Autor();

// -----------------------------------------------------
//   SWITCH PRINCIPAL
// -----------------------------------------------------
switch ($op) {

    case "ObtenerTodos":
        echo json_encode($autor->obtener_autores(), JSON_UNESCAPED_UNICODE);
        break;

    case "ObtenerPorId":
        if (!isset($body["id"])) {
            echo json_encode(["error" => "Falta parámetro 'id'"]);
            exit();
        }
        echo json_encode($autor->obtener_autor_por_id($body["id"]), JSON_UNESCAPED_UNICODE);
        break;

    case "Insertar":
        if (!isset($body["nombre"]) || !isset($body["nacionalidad"])) {
            echo json_encode(["error" => "Datos incompletos"]);
            exit();
        }
        $autor->insertar_autor($body["nombre"], $body["nacionalidad"]);
        echo json_encode(["Correcto" => "Autor insertado correctamente"]);
        break;

    case "Actualizar":
        if (!isset($body["id"]) || !isset($body["nombre"]) || !isset($body["nacionalidad"])) {
            echo json_encode(["error" => "Datos incompletos"]);
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
