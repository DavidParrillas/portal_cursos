<?php
session_start();
require_once __DIR__ . '/../config/database.php';

class PagoController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        require __DIR__ . '/../views/pagos/index.php';
        exit;
    }

    public function paypalForm() {
        require __DIR__ . '/../views/pagos/paypal.php';
        exit;
    }

    public function selectPaymentMethod() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'mensaje' => 'Método no permitido'
            ]);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        $method = $data['method'] ?? null;
        if (!$method) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'mensaje' => 'No se especificó el método de pago'
            ]);
            exit;
        }

        switch ($method) {
            case 'paypal':
                $redirect = '/controllers/PaymentController.php?action=paypalForm';
                break;

            case 'card':
                $redirect = '/controllers/PaymentController.php?action=index';
                break;

            default:
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'mensaje' => 'Método de pago no reconocido'
                ]);
                exit;
        }

        echo json_encode([
            'success' => true,
            'redirect' => $redirect
        ]);
        exit;
    }
    
    public function procesarPagoPaypal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /controllers/PaymentController.php?action=paypalForm');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $amount = floatval($_POST['amount'] ?? 0);
        $description = trim($_POST['description'] ?? '');

        if ($name === '' || $email === '' || $amount <= 0) {
            header('Location: /controllers/PaymentController.php?action=paypalForm');
            exit;
        }

        header('Location: /controllers/PaymentController.php?action=index');
        exit;
    }
}

$action = $_GET['action'] ?? 'index';

$pdo = Database::getInstance();
$controller = new PaymentController($pdo);

if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'mensaje' => 'Acción no válida']);
}
