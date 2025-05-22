<?php
require 'config.php';

header('Content-Type: application/json');

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false, 
        'message' => 'Você precisa estar logado para publicar um post.'
    ]);
    exit;
}

// ✅ CWE-693: Proteção contra métodos HTTP indevidos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ✅ CWE-20: Validação de entrada para evitar valores inválidos
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $user_id = $_SESSION['user_id'];

    if (empty($title) || empty($content)) {
        echo json_encode([
            'success' => false,
            'message' => 'Título e conteúdo são obrigatórios.'
        ]);
        exit;
    }

    // ✅ CWE-89: Prevenção contra SQL Injection com prepared statements
    $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
    
    if (!$stmt) {
        // ✅ CWE-209: Não expor erros internos diretamente ao usuário
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao preparar SQL.'
        ]);
        exit;
    }

    // ✅ Uso seguro de parâmetros (evita injeção de código malicioso)
    $stmt->bind_param("iss", $user_id, $title, $content);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        // ✅ CWE-209 novamente: mensagem de erro genérica
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao cadastrar post.'
        ]);
    }

    $stmt->close();

} else {
    // ✅ CWE-693 novamente: só aceita POST
    echo json_encode([
        'success' => false,
        'message' => 'Método de requisição inválido.'
    ]);
}
?>
