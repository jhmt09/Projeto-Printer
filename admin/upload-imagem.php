<?php

require_once __DIR__ . '/_init.php';

protegerPagina();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    setFlash('error', 'Método inválido para upload de imagem.');
    redirecionar('dashboard.php#imagens');
}

$token = $_POST['csrf_token'] ?? null;
if (!validarCsrfToken(is_string($token) ? $token : null)) {
    setFlash('error', 'Token de segurança inválido.');
    redirecionar('dashboard.php#imagens');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    setFlash('error', 'Identificador de imagem inválido.');
    redirecionar('dashboard.php#imagens');
}

$altText = limparTexto((string) ($_POST['alt_text'] ?? ''), 255);

try {
    $pdo = getPDOConnection();
    $stmt = $pdo->prepare('SELECT * FROM site_imagens WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);
    $imagem = $stmt->fetch();

    if (!is_array($imagem)) {
        setFlash('error', 'Imagem não encontrada para atualização.');
        redirecionar('dashboard.php#imagens');
    }

    $novoCaminho = (string) ($imagem['caminho'] ?? '');
    $arquivo = $_FILES['arquivo'] ?? null;

    $temUpload = is_array($arquivo) && isset($arquivo['error']) && (int) $arquivo['error'] !== UPLOAD_ERR_NO_FILE;

    if ($temUpload) {
        if ((int) $arquivo['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Falha no envio do arquivo. Código: ' . (int) $arquivo['error']);
        }

        $maxMb = (float) ($imagem['tamanho_max_mb'] ?? 2);
        $maxBytes = (int) max(1, $maxMb * 1024 * 1024);

        if ((int) $arquivo['size'] > $maxBytes) {
            throw new RuntimeException('Arquivo maior que o limite permitido (' . $maxMb . ' MB).');
        }

        $nomeOriginal = (string) ($arquivo['name'] ?? '');
        $ext = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));

        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $extensoesBloqueadas = ['php', 'phtml', 'js', 'html', 'exe', 'sh'];

        if (in_array($ext, $extensoesBloqueadas, true) || !in_array($ext, $extensoesPermitidas, true)) {
            throw new RuntimeException('Extensão de arquivo não permitida. Use jpg, jpeg, png ou webp.');
        }

        $tmpPath = (string) ($arquivo['tmp_name'] ?? '');
        if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
            throw new RuntimeException('Arquivo temporário inválido.');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = (string) $finfo->file($tmpPath);

        $mimePermitido = [
            'image/jpeg' => ['jpg', 'jpeg'],
            'image/png' => ['png'],
            'image/webp' => ['webp'],
        ];

        if (!isset($mimePermitido[$mime]) || !in_array($ext, $mimePermitido[$mime], true)) {
            throw new RuntimeException('Tipo de imagem inválido.');
        }

        $uploadsDir = realpath(__DIR__ . '/../uploads');
        if ($uploadsDir === false) {
            throw new RuntimeException('Pasta /uploads não encontrada.');
        }

        $siteDir = $uploadsDir . DIRECTORY_SEPARATOR . 'site';
        if (!is_dir($siteDir) && !mkdir($siteDir, 0755, true)) {
            throw new RuntimeException('Não foi possível criar a pasta de uploads.');
        }

        $chave = preg_replace('/[^a-z0-9\-]/i', '-', (string) ($imagem['chave'] ?? 'imagem'));
        $chave = trim((string) $chave, '-');
        if ($chave === '') {
            $chave = 'imagem';
        }

        $novoNome = $chave . '-' . date('YmdHis') . '-' . bin2hex(random_bytes(6)) . '.' . $ext;
        $destinoAbs = $siteDir . DIRECTORY_SEPARATOR . $novoNome;

        if (!move_uploaded_file($tmpPath, $destinoAbs)) {
            throw new RuntimeException('Falha ao mover a imagem enviada.');
        }

        $novoCaminho = 'uploads/site/' . $novoNome;

        $caminhoAntigo = (string) ($imagem['caminho'] ?? '');
        if ($caminhoAntigo !== '' && !preg_match('~^https?://~i', $caminhoAntigo)) {
            $antigoAbs = realpath(__DIR__ . '/../' . ltrim($caminhoAntigo, '/'));
            $siteDirReal = realpath($siteDir);

            if ($antigoAbs !== false && $siteDirReal !== false) {
                $prefix = rtrim($siteDirReal, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
                if (str_starts_with($antigoAbs, $prefix) && is_file($antigoAbs)) {
                    @unlink($antigoAbs);
                }
            }
        }
    }

    $update = $pdo->prepare('UPDATE site_imagens SET caminho = :caminho, alt_text = :alt_text, atualizado_em = NOW() WHERE id = :id LIMIT 1');
    $update->execute([
        ':caminho' => $novoCaminho,
        ':alt_text' => $altText,
        ':id' => $id,
    ]);

    setFlash('success', 'Imagem atualizada com sucesso.');
    redirecionar('editar-imagem.php?id=' . $id);
} catch (Throwable $e) {
    setFlash('error', 'Erro ao atualizar imagem: ' . $e->getMessage());
    redirecionar('editar-imagem.php?id=' . $id);
}

