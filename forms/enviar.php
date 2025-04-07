<?php
header('Content-Type: application/json');

// Verifica se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

// Validação dos campos obrigatórios
$required_fields = ['name', 'email', 'subject', 'message'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['error' => "O campo $field é obrigatório."]);
        exit;
    }
}

// Validação de e-mail
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['error' => 'E-mail inválido.']);
    exit;
}

// Captura os dados do formulário com sanitização
$name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$subject = filter_var(trim($_POST['subject']), FILTER_SANITIZE_STRING);
$message = filter_var(trim($_POST['message']), FILTER_SANITIZE_STRING);

// Verifica se os campos sanitizados estão vazios
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode(['error' => 'Todos os campos devem ser preenchidos corretamente.']);
    exit;
}

// Configuração do e-mail
$to = "ruibulhosa@infosolucoes.pt"; // Substitua pelo seu e-mail
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$email_body = "Nome: $name\n";
$email_body .= "E-mail: $email\n";
$email_body .= "Assunto: $subject\n\n";
$email_body .= "Mensagem:\n$message";

// Envia o e-mail
if (mail($to, $subject, $email_body, $headers)) {
    echo json_encode(['success' => 'Mensagem enviada com sucesso!']);
} else {
    echo json_encode(['error' => 'Erro ao enviar a mensagem. Tente novamente mais tarde.']);
}
?>