<?php
require 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false, 
        'message' => 'Not logged in'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM posts WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $conn->query($sql);

$posts = [];
if ($result->num_rows > 0) {
    while($post = $result->fetch_assoc()) {
        // Sanitize the data for JSON
        $posts[] = [
            'title' => htmlspecialchars($post['title']),
            'content' => nl2br(htmlspecialchars($post['content'])), // evitar Cross-Site Scripting (CWE-79).
            'created_at' => $post['created_at']
        ];
    }
    echo json_encode([
        'success' => true,
        'posts' => $posts
    ]);
} else {
    echo json_encode([
        'success' => true,
        'posts' => []
    ]);
}
?>
