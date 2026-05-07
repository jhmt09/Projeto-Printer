<?php

declare(strict_types=1);

require_once __DIR__ . '/_init.php';

protegerPagina();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    setFlash('error', 'Metodo invalido para excluir foto promocional.');
    redirecionar('promocionais.php');
}

$token = $_POST['csrf_token'] ?? null;
if (!validarCsrfToken(is_string($token) ? $token : null)) {
    setFlash('error', 'Token de seguranca invalido.');
    redirecionar('promocionais.php');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    setFlash('error', 'Foto promocional invalida para exclusao.');
    redirecionar('promocionais.php');
}

try {
    $pdo = getPDOConnection();
    garantirTabelaPromocionais($pdo);

    $stmt = $pdo->prepare('SELECT caminho FROM site_promocionais WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);
    $registro = $stmt->fetch();

    if (!is_array($registro)) {
        setFlash('error', 'Foto promocional nao encontrada.');
        redirecionar('promocionais.php');
    }

    $caminho = (string) ($registro['caminho'] ?? '');

    $delete = $pdo->prepare('DELETE FROM site_promocionais WHERE id = :id LIMIT 1');
    $delete->execute([':id' => $id]);

    if ($caminho !== '' && !preg_match('~^https?://~i', $caminho)) {
        $uploadsSiteDir = realpath(__DIR__ . '/../uploads/site');
        $arquivoAbs = realpath(__DIR__ . '/../' . ltrim($caminho, '/'));

        if ($uploadsSiteDir !== false && $arquivoAbs !== false) {
            $prefix = rtrim($uploadsSiteDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            if (str_starts_with($arquivoAbs, $prefix) && is_file($arquivoAbs)) {
                @unlink($arquivoAbs);
            }
        }
    }

    setFlash('success', 'Foto promocional excluida com sucesso.');
    redirecionar('promocionais.php');
} catch (Throwable $e) {
    setFlash('error', 'Erro ao excluir foto promocional.');
    redirecionar('promocionais.php');
}