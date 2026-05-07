<?php

declare(strict_types=1);

require_once __DIR__ . '/_init.php';

protegerPagina();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    setFlash('error', 'Metodo invalido para salvar texto.');
    redirecionar('editar-texto.php');
}

$token = $_POST['csrf_token'] ?? null;
if (!validarCsrfToken(is_string($token) ? $token : null)) {
    setFlash('error', 'Token de seguranca invalido.');
    redirecionar('editar-texto.php');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    setFlash('error', 'Identificador de texto invalido.');
    redirecionar('editar-texto.php');
}

try {
    $pdo = getPDOConnection();

    $stmtTipo = $pdo->prepare('SELECT tipo FROM site_textos WHERE id = :id LIMIT 1');
    $stmtTipo->execute([':id' => $id]);
    $registro = $stmtTipo->fetch();

    if (!is_array($registro)) {
        setFlash('error', 'Registro de texto nao encontrado.');
        redirecionar('editar-texto.php');
    }

    $tipo = (string) ($registro['tipo'] ?? 'textarea');
    $conteudo = (string) ($_POST['conteudo'] ?? '');

    if ($tipo === 'boolean') {
        $conteudo = $conteudo === '1' ? '1' : '0';
    } else {
        $conteudo = limparTexto($conteudo, 10000);
        $conteudo = strip_tags($conteudo);
        $conteudo = str_replace(['<?', '?>'], '', $conteudo);
        if ($conteudo === '') {
            setFlash('error', 'O conteudo nao pode ficar vazio.');
            redirecionar('editar-texto.php#texto-' . $id);
        }
    }

    $stmtUpdate = $pdo->prepare('UPDATE site_textos SET conteudo = :conteudo, atualizado_em = NOW() WHERE id = :id LIMIT 1');
    $stmtUpdate->execute([
        ':conteudo' => $conteudo,
        ':id' => $id,
    ]);

    setFlash('success', 'Texto atualizado com sucesso.');
    redirecionar('editar-texto.php#texto-' . $id);
} catch (Throwable $e) {
    setFlash('error', 'Erro ao salvar texto.');
    redirecionar('editar-texto.php#texto-' . $id);
}
