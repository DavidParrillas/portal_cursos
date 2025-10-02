<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar que el usuario sea administrador
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

try {
    $pdo = Database::getInstance();
    
    switch ($accion) {
        case 'crear':
            crearUsuario($pdo);
            break;
            
        case 'editar':
            editarUsuario($pdo);
            break;
            
        case 'eliminar':
            eliminarUsuario($pdo);
            break;
            
        case 'obtener':
            obtenerUsuario($pdo);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}

function crearUsuario($pdo) {
    // Validar datos requeridos
    if (empty($_POST['nombre_completo']) || empty($_POST['correo']) || 
        empty($_POST['contrasena']) || empty($_POST['id_rol'])) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        return;
    }
    
    $nombre = trim($_POST['nombre_completo']);
    $correo = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];
    $idRol = intval($_POST['id_rol']);
    
    // Validar email
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Correo electrónico inválido']);
        return;
    }
    
    // Validar longitud de contraseña
    if (strlen($contrasena) < 8) {
        echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 8 caracteres']);
        return;
    }
    
    // Verificar que el correo no exista
    $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'El correo electrónico ya está registrado']);
        return;
    }
    
    // Verificar que el rol exista
    $stmt = $pdo->prepare("SELECT id_rol FROM roles WHERE id_rol = ?");
    $stmt->execute([$idRol]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Rol inválido']);
        return;
    }
    
    try {
        $pdo->beginTransaction();
        
        // Hash de la contraseña
        $hashContrasena = password_hash($contrasena, PASSWORD_BCRYPT);
        
        // Insertar usuario
        $stmt = $pdo->prepare("
            INSERT INTO usuarios (nombre_completo, correo, contrasena, fecha_registro) 
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->execute([$nombre, $correo, $hashContrasena]);
        $idUsuario = $pdo->lastInsertId();
        
        // Asignar rol al usuario
        $stmt = $pdo->prepare("
            INSERT INTO usuarios_roles (id_usuario, id_rol) 
            VALUES (?, ?)
        ");
        $stmt->execute([$idUsuario, $idRol]);
        
        $pdo->commit();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Usuario creado exitosamente',
            'id_usuario' => $idUsuario
        ]);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error al crear usuario: ' . $e->getMessage()]);
    }
}

function editarUsuario($pdo) {
    // Validar datos requeridos
    if (empty($_POST['id_usuario']) || empty($_POST['nombre_completo']) || 
        empty($_POST['correo']) || empty($_POST['id_rol'])) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        return;
    }
    
    $idUsuario = intval($_POST['id_usuario']);
    $nombre = trim($_POST['nombre_completo']);
    $correo = trim($_POST['correo']);
    $idRol = intval($_POST['id_rol']);
    $contrasena = $_POST['contrasena'] ?? '';
    
    // Validar email
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Correo electrónico inválido']);
        return;
    }
    
    // Validar contraseña si se proporciona
    if (!empty($contrasena) && strlen($contrasena) < 8) {
        echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 8 caracteres']);
        return;
    }
    
    // Verificar que el usuario exista
    $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$idUsuario]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        return;
    }
    
    // Verificar que el correo no esté usado por otro usuario
    $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE correo = ? AND id_usuario != ?");
    $stmt->execute([$correo, $idUsuario]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'El correo electrónico ya está registrado por otro usuario']);
        return;
    }
    
    // Verificar que el rol exista
    $stmt = $pdo->prepare("SELECT id_rol FROM roles WHERE id_rol = ?");
    $stmt->execute([$idRol]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Rol inválido']);
        return;
    }
    
    try {
        $pdo->beginTransaction();
        
        // Actualizar datos del usuario
        if (!empty($contrasena)) {
            // Actualizar con nueva contraseña
            $hashContrasena = password_hash($contrasena, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("
                UPDATE usuarios 
                SET nombre_completo = ?, correo = ?, contrasena = ?
                WHERE id_usuario = ?
            ");
            $stmt->execute([$nombre, $correo, $hashContrasena, $idUsuario]);
        } else {
            // Actualizar sin cambiar contraseña
            $stmt = $pdo->prepare("
                UPDATE usuarios 
                SET nombre_completo = ?, correo = ?
                WHERE id_usuario = ?
            ");
            $stmt->execute([$nombre, $correo, $idUsuario]);
        }
        
        // Actualizar rol del usuario
        $stmt = $pdo->prepare("
            UPDATE usuarios_roles 
            SET id_rol = ?
            WHERE id_usuario = ?
        ");
        $stmt->execute([$idRol, $idUsuario]);
        
        $pdo->commit();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Usuario actualizado exitosamente'
        ]);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error al actualizar usuario: ' . $e->getMessage()]);
    }
}

function eliminarUsuario($pdo) {
    if (empty($_POST['id_usuario'])) {
        echo json_encode(['success' => false, 'message' => 'ID de usuario no proporcionado']);
        return;
    }
    
    $idUsuario = intval($_POST['id_usuario']);
    
    // Verificar que no sea el propio usuario
    if ($idUsuario == $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'message' => 'No puedes eliminar tu propia cuenta']);
        return;
    }
    
    // Verificar que el usuario exista
    $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$idUsuario]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        return;
    }
    
    try {
        $pdo->beginTransaction();
        
        // Eliminar relación usuario-rol
        $stmt = $pdo->prepare("DELETE FROM usuarios_roles WHERE id_usuario = ?");
        $stmt->execute([$idUsuario]);
        
        // Eliminar usuario
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$idUsuario]);
        
        $pdo->commit();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Usuario eliminado exitosamente'
        ]);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error al eliminar usuario: ' . $e->getMessage()]);
    }
}

function obtenerUsuario($pdo) {
    if (empty($_GET['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
        return;
    }
    
    $idUsuario = intval($_GET['id']);
    
    $stmt = $pdo->prepare("
        SELECT 
            u.id_usuario, 
            u.nombre_completo, 
            u.correo, 
            ur.id_rol,
            r.nombre as rol_nombre
        FROM usuarios u
        JOIN usuarios_roles ur ON u.id_usuario = ur.id_usuario
        JOIN roles r ON ur.id_rol = r.id_rol
        WHERE u.id_usuario = ?
    ");
    $stmt->execute([$idUsuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario) {
        echo json_encode([
            'success' => true, 
            'usuario' => $usuario
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
    }
}