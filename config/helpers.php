<?php

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/auth.php';

function redirecionar(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function limparTexto(string $texto, int $maxLen = 0): string
{
    $valor = trim($texto);
    $valor = str_replace(["\r\n", "\r"], "\n", $valor);

    if ($maxLen > 0) {
        $valor = mb_substr($valor, 0, $maxLen, 'UTF-8');
    }

    return $valor;
}

function e(?string $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function setFlash(string $tipo, string $mensagem): void
{
    $_SESSION['flash_messages'][] = [
        'tipo' => $tipo,
        'mensagem' => $mensagem,
    ];
}

/**
 * @return array<int, array{tipo:string,mensagem:string}>
 */
function getFlashMessages(): array
{
    $messages = $_SESSION['flash_messages'] ?? [];
    unset($_SESSION['flash_messages']);

    if (!is_array($messages)) {
        return [];
    }

    return $messages;
}

/**
 * Busca e cacheia registro de imagem por chave.
 *
 * @return array<string, mixed>|null
 */
function getImagemRegistro(string $chave): ?array
{
    static $cache = [];

    if (array_key_exists($chave, $cache)) {
        return $cache[$chave];
    }

    try {
        $pdo = getPDOConnection();
        $stmt = $pdo->prepare('SELECT * FROM site_imagens WHERE chave = :chave AND ativo = 1 LIMIT 1');
        $stmt->execute([':chave' => $chave]);
        $registro = $stmt->fetch();

        if (!is_array($registro)) {
            $cache[$chave] = null;
            return null;
        }

        $cache[$chave] = $registro;
        return $registro;
    } catch (Throwable $e) {
        $cache[$chave] = null;
        return null;
    }
}

function getImagem(string $chave, string $fallback): string
{
    $registro = getImagemRegistro($chave);

    if (!is_array($registro)) {
        return $fallback;
    }

    $caminho = trim((string) ($registro['caminho'] ?? ''));
    if ($caminho === '') {
        return $fallback;
    }

    return $caminho;
}

function getAltImagem(string $chave, string $fallback): string
{
    $registro = getImagemRegistro($chave);

    if (!is_array($registro)) {
        return $fallback;
    }

    $alt = trim((string) ($registro['alt_text'] ?? ''));
    if ($alt === '') {
        return $fallback;
    }

    return $alt;
}

function getTextoSite(string $chave, string $fallback): string
{
    static $cache = [];

    if (array_key_exists($chave, $cache)) {
        return $cache[$chave] === '' ? $fallback : $cache[$chave];
    }

    try {
        $pdo = getPDOConnection();
        $stmt = $pdo->prepare('SELECT conteudo FROM site_textos WHERE chave = :chave AND ativo = 1 LIMIT 1');
        $stmt->execute([':chave' => $chave]);
        $registro = $stmt->fetch();

        if (!is_array($registro)) {
            $cache[$chave] = '';
            return $fallback;
        }

        $conteudo = trim((string) ($registro['conteudo'] ?? ''));
        $cache[$chave] = $conteudo;
        return $conteudo === '' ? $fallback : $conteudo;
    } catch (Throwable $e) {
        $cache[$chave] = '';
        return $fallback;
    }
}

