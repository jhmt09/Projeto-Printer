<?php

function adminMenuItems(): array
{
    return [
        'dashboard' => ['label' => 'Dashboard', 'href' => 'dashboard.php'],
        'imagens' => ['label' => 'Imagens do Site', 'href' => 'dashboard.php#imagens'],
        'textos' => ['label' => 'Textos do Site', 'href' => 'editar-texto.php'],
        'sair' => ['label' => 'Sair', 'href' => 'logout.php'],
    ];
}

function renderAdminHeader(string $title, string $active = 'dashboard'): void
{
    $menu = adminMenuItems();
    $messages = getFlashMessages();
    ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title) ?> - Painel Admin</title>
  <link rel="stylesheet" href="assets/admin.css">
</head>
<body>
  <div class="admin-wrap">
    <aside class="admin-sidebar">
      <div class="brand">Printer Admin</div>
      <nav>
        <ul>
          <?php foreach ($menu as $key => $item): ?>
            <li>
              <a class="<?= $active === $key ? 'active' : '' ?>" href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a>
            </li>
          <?php endforeach; ?>
        </ul>
      </nav>
    </aside>

    <main class="admin-main">
      <header class="admin-topbar">
        <h1><?= e($title) ?></h1>
        <?php if (usuarioLogado()): ?>
          <span class="user">Olá, <?= e((string) ($_SESSION['admin_user_nome'] ?? 'Administrador')) ?></span>
        <?php endif; ?>
      </header>

      <?php foreach ($messages as $msg): ?>
        <div class="alert <?= $msg['tipo'] === 'success' ? 'alert-success' : 'alert-error' ?>">
          <?= e($msg['mensagem']) ?>
        </div>
      <?php endforeach; ?>
<?php
}

function renderAdminFooter(): void
{
    ?>
    </main>
  </div>
</body>
</html>
<?php
}

