<?php
require 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validação básica de entrada
    $username = trim($_POST['username'] ?? ''); // CWE-20 validacao de entrada fraca
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {  // CWE-20 validacao de entrada fraca
        echo json_encode([
            'success' => false,
            'message' => 'Usuário e senha são obrigatórios.'
        ]);
        exit;
    }

    // CWE-327 evitado (criptografica fraca ou sem hash)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Evita SQL Injection com prepared statements (CWE-89)
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    if ($stmt === false) {
        echo json_encode([
            'success' => false,
            'message' => 'Erro interno. Tente novamente mais tarde.' // CWE-209 prevenido(Mensagens de erro genéricas (sem detalhes do banco))
        ]);
        exit;
    }

    $stmt->bind_param("ss", $username, $hashedPassword);     // Evita SQL Injection com prepared statements (CWE-89)

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Não foi possível cadastrar. Usuário já existe ou erro no banco.'
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método de requisição inválido.'
    ]);
}
?>
