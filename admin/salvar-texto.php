<?php

require_once __DIR__ . '/_init.php';

protegerPagina();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    setFlash('error', 'Método inválido para salvar texto.');
    redirecionar('editar-texto.php');
}

$token = $_POST['csrf_token'] ?? null;
if (!validarCsrfToken(is_string($token) ? $token : null)) {
    setFlash('error', 'Token de segurança inválido.');
    redirecionar('editar-texto.php');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    setFlash('error', 'Identificador de texto inválido.');
    redirecionar('editar-texto.php');
}

$conteudo = limparTexto((string) ($_POST['conteudo'] ?? ''), 10000);
if ($conteudo === '') {
    setFlash('error', 'O conteúdo não pode ficar vazio.');
    redirecionar('editar-texto.php#texto-' . $id);
}

try {
    $pdo = getPDOConnection();
    $stmt = $pdo->prepare('UPDATE site_textos SET conteudo = :conteudo, atualizado_em = NOW() WHERE id = :id LIMIT 1');
    $stmt->execute([
        ':conteudo' => $conteudo,
        ':id' => $id,
    ]);

    setFlash('success', 'Texto atualizado com sucesso.');
    redirecionar('editar-texto.php#texto-' . $id);
} catch (Throwable $e) {
    setFlash('error', 'Erro ao salvar texto.');
    redirecionar('editar-texto.php#texto-' . $id);
}

