<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/InscripcionController.php';

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

// Configuración de PayPal
$apiContext = new ApiContext(
    new OAuthTokenCredential(
        'AYSq3S-S-CSd3Xh2rXNCaDqIsdJc', // ClientID
        'EILU-iT5e_G2E03d3Ff_GqJgGq9a'  // ClientSecret
    )
);

$apiContext->setConfig([
    'mode' => 'sandbox', // o 'live' en producción
    'log.LogEnabled' => true,
    'log.FileName' => __DIR__ . '/../logs/paypal.log',
    'log.LogLevel' => 'DEBUG',
]);

if (!isset($_GET['paymentId'], $_GET['PayerID'], $_SESSION['paypal_payment_id'])) {
    $_SESSION['mensaje'] = 'No se pudo procesar el pago de PayPal. Faltan parámetros.';
    $_SESSION['mensaje_tipo'] = 'danger';
    header('Location: /portal_cursos/index.php');
    exit;
}

// Verificar que el paymentId de la sesión coincida con el de la URL
if ($_GET['paymentId'] !== $_SESSION['paypal_payment_id']) {
    $_SESSION['mensaje'] = 'Error de validación del pago.';
    $_SESSION['mensaje_tipo'] = 'danger';
    header('Location: /portal_cursos/index.php');
    exit;
}

$paymentId = $_GET['paymentId'];
$payerId = $_GET['PayerID'];

$payment = Payment::get($paymentId, $apiContext);
$execution = new PaymentExecution();
$execution->setPayerId($payerId);

$idCurso = $_SESSION['id_curso_paypal'] ?? null;
$idUsuario = $_SESSION['user_id'] ?? null;

if (!$idCurso || !$idUsuario) {
    $_SESSION['mensaje'] = 'Tu sesión ha expirado. No se pudo completar la inscripción.';
    $_SESSION['mensaje_tipo'] = 'danger';
    header('Location: /portal_cursos/index.php');
    exit;
}

try {
    // Ejecutar el pago
    $result = $payment->execute($execution, $apiContext);

    if ($result->getState() == 'approved') {
        // El pago fue aprobado. Ahora inscribimos al usuario.
        $pdo = Database::getInstance();
        $inscripcionController = new InscripcionController($pdo);
        
        // Llamar a la lógica de inscripción post-pago
        $inscripcionController->inscribirUsuarioTrasPago($idUsuario, $idCurso, $result);

    } else {
        $_SESSION['mensaje'] = 'El pago no fue aprobado por PayPal. Por favor, intenta de nuevo.';
        $_SESSION['mensaje_tipo'] = 'warning';
        header('Location: /portal_cursos/views/courses/detalleCurso.php?id=' . $idCurso);
        exit;
    }
} catch (Exception $e) {
    error_log("Error al capturar pago de PayPal: " . $e->getMessage());
    $_SESSION['mensaje'] = 'Ocurrió un error al finalizar tu pago. Por favor, contacta a soporte.';
    $_SESSION['mensaje_tipo'] = 'danger';
    header('Location: /portal_cursos/views/courses/detalleCurso.php?id=' . $idCurso);
    exit;
} finally {
    // Limpiar variables de sesión de PayPal
    unset($_SESSION['paypal_payment_id']);
    unset($_SESSION['id_curso_paypal']);
}