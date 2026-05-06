<?php

require_once __DIR__ . '/_init.php';

if (usuarioLogado()) {
    redirecionar('dashboard.php');
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? null;

    if (!validarCsrfToken(is_string($token) ? $token : null)) {
        $erro = 'Token de segurança inválido. Atualize a página e tente novamente.';
    } else {
        $email = filter_var(trim((string) ($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
        $senha = (string) ($_POST['senha'] ?? '');

        if ($email === false || $senha === '') {
            $erro = 'Informe e-mail e senha válidos.';
        } else {
            try {
                $pdo = getPDOConnection();
                $stmt = $pdo->prepare('SELECT id, nome, email, senha_hash FROM usuarios_admin WHERE email = :email LIMIT 1');
                $stmt->execute([':email' => $email]);
                $usuario = $stmt->fetch();

                if (is_array($usuario) && password_verify($senha, (string) $usuario['senha_hash'])) {
                    session_regenerate_id(true);
                    $_SESSION['admin_user_id'] = (int) $usuario['id'];
                    $_SESSION['admin_user_nome'] = (string) $usuario['nome'];
                    $_SESSION['admin_user_email'] = (string) $usuario['email'];
                    redirecionar('dashboard.php');
                }

                $erro = 'Credenciais inválidas.';
            } catch (Throwable $e) {
                $erro = 'Não foi possível autenticar no momento. Verifique o banco de dados.';
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
  <title>Login - Painel Admin</title>
  <link rel="stylesheet" href="assets/admin.css">
</head>
<body>
  <div class="login-wrapper">
    <div class="login-card">
      <h1>Painel Administrativo</h1>
      <p class="note">Acesse com suas credenciais para gerenciar imagens e textos do site.</p>

      <?php if ($erro !== ''): ?>
        <div class="alert alert-error"><?= e($erro) ?></div>
      <?php endif; ?>

      <?php foreach (getFlashMessages() as $msg): ?>
        <div class="alert <?= $msg['tipo'] === 'success' ? 'alert-success' : 'alert-error' ?>">
          <?= e($msg['mensagem']) ?>
        </div>
      <?php endforeach; ?>

      <form method="post" autocomplete="off">
        <input type="hidden" name="csrf_token" value="<?= e(gerarCsrfToken()) ?>">

        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
          <label for="senha">Senha</label>
          <input type="password" id="senha" name="senha" required>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;">Entrar</button>
      </form>
    </div>
  </div>
</body>
</html>

