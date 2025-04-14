<?php

header('Content-Type: application/json'); // Tell client we're sending JSON
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

// Authorization check
if (!isset($headers['Authorization']) || $headers['Authorization'] !== "Bearer $API_TOKEN") {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    $pdo = new PDO('mysql:host=db;dbname=eventsdb', 'user', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        // READ all events
        $stmt = $pdo->query("SELECT id, title, date, location FROM events ORDER BY date ASC");
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($events);
        exit;
    }

    elseif ($method === 'POST') {
        // CREATE new event
        $input = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("INSERT INTO events (title, date, location) VALUES (?, ?, ?)");
        $stmt->execute([$input['title'], $input['date'], $input['location']]);
        echo json_encode(['message' => 'Event created']);
        exit;
    }

    elseif ($method === 'PUT') {
        // UPDATE existing event
        $input = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("UPDATE events SET title = ?, date = ?, location = ? WHERE id = ?");
        $stmt->execute([$input['title'], $input['date'], $input['location'], $input['id']]);
        echo json_encode(['message' => 'Event updated']);
        exit;
    }

    elseif ($method === 'DELETE') {
        // DELETE event
        $input = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
        $stmt->execute([$input['id']]);
        echo json_encode(['message' => 'Event deleted']);
        exit;
    }

    else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        exit;
    }

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
