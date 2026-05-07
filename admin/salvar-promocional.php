<?php

declare(strict_types=1);

require_once __DIR__ . '/_init.php';

protegerPagina();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    setFlash('error', 'Metodo invalido para salvar foto promocional.');
    redirecionar('promocionais.php');
}

$token = $_POST['csrf_token'] ?? null;
if (!validarCsrfToken(is_string($token) ? $token : null)) {
    setFlash('error', 'Token de seguranca invalido.');
    redirecionar('promocionais.php');
}

$acao = (string) ($_POST['acao'] ?? '');
if ($acao !== 'criar' && $acao !== 'editar') {
    setFlash('error', 'Acao invalida.');
    redirecionar('promocionais.php');
}

$titulo = limparTexto((string) ($_POST['titulo'] ?? ''), 150);
$titulo = str_replace(['<?', '?>'], '', strip_tags($titulo));
if ($titulo === '') {
    setFlash('error', 'Informe o titulo da foto promocional.');
    redirecionar('promocionais.php');
}

$legenda = limparTexto((string) ($_POST['legenda'] ?? ''), 255);
$legenda = str_replace(['<?', '?>'], '', strip_tags($legenda));

$altText = limparTexto((string) ($_POST['alt_text'] ?? ''), 255);
$altText = str_replace(['<?', '?>'], '', strip_tags($altText));

$ordemInput = filter_input(INPUT_POST, 'ordem', FILTER_VALIDATE_INT);
$ordem = is_int($ordemInput) && $ordemInput >= 0 ? $ordemInput : 0;

$arquivo = $_FILES['arquivo'] ?? null;
$temUpload = is_array($arquivo) && isset($arquivo['error']) && (int) $arquivo['error'] !== UPLOAD_ERR_NO_FILE;

$id = null;
if ($acao === 'editar') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if (!$id) {
        setFlash('error', 'Foto promocional invalida para edicao.');
        redirecionar('promocionais.php');
    }
}

if ($acao === 'criar' && !$temUpload) {
    setFlash('error', 'Envie um arquivo de imagem para criar a foto promocional.');
    redirecionar('promocionais.php');
}

try {
    $pdo = getPDOConnection();
    garantirTabelaPromocionais($pdo);

    $caminhoAtual = '';
    if ($acao === 'editar' && is_int($id)) {
        $stmtAtual = $pdo->prepare('SELECT caminho FROM site_promocionais WHERE id = :id LIMIT 1');
        $stmtAtual->execute([':id' => $id]);
        $registroAtual = $stmtAtual->fetch();

        if (!is_array($registroAtual)) {
            setFlash('error', 'Foto promocional nao encontrada.');
            redirecionar('promocionais.php');
        }

        $caminhoAtual = (string) ($registroAtual['caminho'] ?? '');
    }

    $novoCaminho = $caminhoAtual;
    if ($temUpload) {
        if ((int) $arquivo['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Falha no envio do arquivo. Codigo: ' . (int) $arquivo['error']);
        }

        $maxBytes = 3 * 1024 * 1024;
        if ((int) $arquivo['size'] > $maxBytes) {
            throw new RuntimeException('Arquivo maior que o limite permitido (3 MB).');
        }

        $nomeOriginal = (string) ($arquivo['name'] ?? '');
        $ext = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));

        $permitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $bloqueadas = ['php', 'phtml', 'js', 'html', 'exe', 'sh'];

        if (in_array($ext, $bloqueadas, true) || !in_array($ext, $permitidas, true)) {
            throw new RuntimeException('Extensao nao permitida. Use jpg, jpeg, png ou webp.');
        }

        $tmpPath = (string) ($arquivo['tmp_name'] ?? '');
        if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
            throw new RuntimeException('Arquivo temporario invalido.');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = (string) $finfo->file($tmpPath);
        $mimePermitido = [
            'image/jpeg' => ['jpg', 'jpeg'],
            'image/png' => ['png'],
            'image/webp' => ['webp'],
        ];

        if (!isset($mimePermitido[$mime]) || !in_array($ext, $mimePermitido[$mime], true)) {
            throw new RuntimeException('Tipo de imagem invalido.');
        }

        $uploadsDir = __DIR__ . '/../uploads';
        if (!is_dir($uploadsDir) && !mkdir($uploadsDir, 0755, true)) {
            throw new RuntimeException('Nao foi possivel criar a pasta /uploads.');
        }
        $uploadsDirReal = realpath($uploadsDir);
        if ($uploadsDirReal === false) {
            throw new RuntimeException('Pasta /uploads invalida.');
        }

        $siteDir = $uploadsDirReal . DIRECTORY_SEPARATOR . 'site';
        if (!is_dir($siteDir) && !mkdir($siteDir, 0755, true)) {
            throw new RuntimeException('Nao foi possivel criar a pasta de uploads.');
        }

        $slugTitulo = preg_replace('/[^a-z0-9\-]/i', '-', strtolower($titulo));
        $slugTitulo = trim((string) $slugTitulo, '-');
        if ($slugTitulo === '') {
            $slugTitulo = 'promocional';
        }

        $novoNome = 'promo-' . $slugTitulo . '-' . date('YmdHis') . '-' . bin2hex(random_bytes(6)) . '.' . $ext;
        $destinoAbs = $siteDir . DIRECTORY_SEPARATOR . $novoNome;

        if (!move_uploaded_file($tmpPath, $destinoAbs)) {
            throw new RuntimeException('Falha ao mover a imagem enviada.');
        }

        $novoCaminho = 'uploads/site/' . $novoNome;

        if ($acao === 'editar' && $caminhoAtual !== '') {
            $antigoAbs = realpath(__DIR__ . '/../' . ltrim($caminhoAtual, '/'));
            $siteDirReal = realpath($siteDir);
            if ($antigoAbs !== false && $siteDirReal !== false) {
                $prefix = rtrim($siteDirReal, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
                if (str_starts_with($antigoAbs, $prefix) && is_file($antigoAbs)) {
                    @unlink($antigoAbs);
                }
            }
        }
    }

    if ($acao === 'criar') {
        if ($ordem === 0) {
            $stmtMax = $pdo->query('SELECT COALESCE(MAX(ordem), 0) + 1 AS proxima_ordem FROM site_promocionais');
            $ordem = (int) (($stmtMax->fetch()['proxima_ordem'] ?? 1));
        }

        $insert = $pdo->prepare('INSERT INTO site_promocionais (titulo, legenda, caminho, alt_text, ordem, ativo) VALUES (:titulo, :legenda, :caminho, :alt_text, :ordem, 1)');
        $insert->execute([
            ':titulo' => $titulo,
            ':legenda' => $legenda,
            ':caminho' => $novoCaminho,
            ':alt_text' => $altText,
            ':ordem' => $ordem,
        ]);

        setFlash('success', 'Foto promocional adicionada com sucesso.');
        redirecionar('promocionais.php');
    }

    if (!is_int($id)) {
        throw new RuntimeException('Identificador invalido para edicao.');
    }

    $ativo = ((string) ($_POST['ativo'] ?? '1')) === '0' ? 0 : 1;
    $update = $pdo->prepare('UPDATE site_promocionais SET titulo = :titulo, legenda = :legenda, caminho = :caminho, alt_text = :alt_text, ordem = :ordem, ativo = :ativo, atualizado_em = NOW() WHERE id = :id LIMIT 1');
    $update->execute([
        ':titulo' => $titulo,
        ':legenda' => $legenda,
        ':caminho' => $novoCaminho,
        ':alt_text' => $altText,
        ':ordem' => $ordem,
        ':ativo' => $ativo,
        ':id' => $id,
    ]);

    setFlash('success', 'Foto promocional atualizada com sucesso.');
    redirecionar('promocionais.php#promo-' . $id);
} catch (Throwable $e) {
    setFlash('error', 'Erro ao salvar foto promocional: ' . $e->getMessage());
    redirecionar('promocionais.php');
}
