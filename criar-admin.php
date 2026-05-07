<?php

require_once __DIR__ . '/config/helpers.php';
enviarHeadersNoCache();

$mensagem = '';
$tipo = 'success';

$totalAdmins = 0;
try {
    $pdo = getPDOConnection();
    $stmtCount = $pdo->prepare('SELECT COUNT(*) AS total FROM usuarios_admin');
    $stmtCount->execute();
    $totalAdmins = (int) ($stmtCount->fetch()['total'] ?? 0);
} catch (Throwable $e) {
    $tipo = 'error';
    $mensagem = 'Não foi possível acessar o banco. Configure o arquivo config/database.php.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $mensagem === '') {
    $token = $_POST['csrf_token'] ?? null;

    if (!validarCsrfToken(is_string($token) ? $token : null)) {
        $tipo = 'error';
        $mensagem = 'Token de segurança inválido. Recarregue a página (Ctrl+F5) e tente novamente. Se persistir, limpe cookies do domínio.';
    } else {
        $nome = limparTexto((string) ($_POST['nome'] ?? ''), 120);
        $email = filter_var(trim((string) ($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
        $senha = (string) ($_POST['senha'] ?? '');

        if ($nome === '' || $email === false || mb_strlen($senha, 'UTF-8') < 6) {
            $tipo = 'error';
            $mensagem = 'Preencha nome, e-mail válido e senha com no mínimo 6 caracteres.';
        } else {
            try {
                $pdo = getPDOConnection();

                $stmtEmail = $pdo->prepare('SELECT id FROM usuarios_admin WHERE email = :email LIMIT 1');
                $stmtEmail->execute([':email' => $email]);
                if ($stmtEmail->fetch()) {
                    $tipo = 'error';
                    $mensagem = 'Já existe um administrador com este e-mail.';
                } else {
                    $hash = password_hash($senha, PASSWORD_DEFAULT);

                    $stmtInsert = $pdo->prepare('INSERT INTO usuarios_admin (nome, email, senha_hash, criado_em, atualizado_em) VALUES (:nome, :email, :senha_hash, NOW(), NOW())');
                    $stmtInsert->execute([
                        ':nome' => $nome,
                        ':email' => $email,
                        ':senha_hash' => $hash,
                    ]);

                    $tipo = 'success';
                    $mensagem = 'Administrador criado com sucesso. APAGUE este arquivo (criar-admin.php) imediatamente.';
                    $totalAdmins++;
                }
            } catch (Throwable $e) {
                $tipo = 'error';
                $mensagem = 'Erro ao criar administrador. Verifique se o database.sql foi importado.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Criar Administrador</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f3f5f9; margin:0; padding:20px; }
    .wrap { max-width: 640px; margin: 0 auto; background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:24px; }
    .warn { background:#fff7ed; border:1px solid #fed7aa; color:#9a3412; padding:12px; border-radius:8px; margin-bottom:16px; }
    .ok { background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; padding:12px; border-radius:8px; margin-bottom:16px; }
    .err { background:#fef2f2; border:1px solid #fecaca; color:#991b1b; padding:12px; border-radius:8px; margin-bottom:16px; }
    label { display:block; margin-bottom:6px; font-weight:bold; }
    input { width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px; margin-bottom:12px; }
    button { background:#1d4ed8; color:#fff; border:0; border-radius:8px; padding:10px 16px; font-weight:bold; cursor:pointer; }
    .meta { color:#6b7280; font-size:13px; margin-bottom:14px; }
  </style>
</head>
<body>
  <div class="wrap">
    <h1>Criar Administrador Inicial</h1>
    <div class="warn">
      <strong>APAGUE ESTE ARQUIVO APÓS CRIAR O ADMINISTRADOR.</strong><br>
      Este arquivo existe apenas para configuração inicial.
    </div>

    <p class="meta">Administradores cadastrados atualmente: <strong><?= (int) $totalAdmins ?></strong></p>

    <?php if ($mensagem !== ''): ?>
      <div class="<?= $tipo === 'success' ? 'ok' : 'err' ?>"><?= e($mensagem) ?></div>
    <?php endif; ?>

    <form method="post" autocomplete="off">
      <input type="hidden" name="csrf_token" value="<?= e(gerarCsrfToken()) ?>">

      <label for="nome">Nome</label>
      <input id="nome" name="nome" type="text" maxlength="120" required>

      <label for="email">E-mail</label>
      <input id="email" name="email" type="email" maxlength="190" required>

      <label for="senha">Senha (mínimo 6 caracteres)</label>
      <input id="senha" name="senha" type="password" minlength="6" required>

      <button type="submit">Criar administrador</button>
    </form>

    <p class="meta" style="margin-top:16px;">Depois de criar, acesse <code>/admin/login.php</code> para entrar no painel.</p>
  </div>
</body>
</html>

