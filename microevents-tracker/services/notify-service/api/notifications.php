<?php
header('Content-Type: application/json');
// Allow cross-origin requests
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight (OPTIONS) request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$API_TOKEN = 'supersecrettoken';
$headers = getallheaders();

if (!isset($headers['Authorization']) || $headers['Authorization'] !== "Bearer $API_TOKEN") {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    $pdo = new PDO('mysql:host=db;dbname=eventsdb', 'user', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'POST') {
        // CREATE
        $input = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("INSERT INTO notifications (message, time) VALUES (?, ?)");
        $stmt->execute([$input['message'], $input['time']]);
        echo json_encode(['message' => 'Notification added']);
        exit;
    }

    if ($method === 'GET') {
        // READ
        $stmt = $pdo->query("SELECT id, message, time FROM notifications ORDER BY time DESC");
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($notifications);
        exit;
    }

    if ($method === 'PUT') {
        // UPDATE
        $input = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("UPDATE notifications SET message = ?, time = ? WHERE id = ?");
        $stmt->execute([$input['message'], $input['time'], $input['id']]);
        echo json_encode(['message' => 'Notification updated']);
        exit;
    }

    if ($method === 'DELETE') {
        // DELETE
        $input = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("DELETE FROM notifications WHERE id = ?");
        $stmt->execute([$input['id']]);
        echo json_encode(['message' => 'Notification deleted']);
        exit;
    }

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
