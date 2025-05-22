<?php
require 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validação básica de entrada - CWE-20: Validação de Entrada
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Por favor, preencha usuário e senha.'
        ]);
        exit;
    }

    // CWE-89: Uso de Prepared Statements para evitar SQL Injection
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    if (!$stmt) {
        // CWE-209: Não revelar detalhes de erros internos (Information Exposure)
        echo json_encode([
            'success' => false,
            'message' => 'Erro interno. Tente novamente mais tarde.'
        ]);
        exit;
    }

    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // CWE-200: Mensagens genéricas para evitar Enumeração de Usuários (Information Disclosure)
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // CWE-307: Uso correto de password_verify para verificar senha segura (Autenticação Forte)
        if (password_verify($password, $user['password'])) {
            // CWE-640: Controle de sessão adequado ao guardar user_id após login
            $_SESSION['user_id'] = $user['id'];
            echo json_encode(['success' => true]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Credenciais inválidas.' // Mensagem genérica
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Credenciais inválidas.' // Mensagem genérica
        ]);
    }

    $stmt->close();
} else {
    // CWE-693: Restrição ao método HTTP permitido (Somente POST)
    echo json_encode([
        'success' => false,
        'message' => 'Método de requisição inválido.'
    ]);
}
?>
