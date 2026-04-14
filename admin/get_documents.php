<?php
require_once "../config/db.php";
require_once "../config/session.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$applicationId = $_GET['application_id'] ?? 0;

try {
    $stmt = $pdo->prepare("SELECT * FROM documents WHERE application_id = ? ORDER BY document_type");
    $stmt->execute([$applicationId]);
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($documents);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
?>
