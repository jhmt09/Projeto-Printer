<?php

declare(strict_types=1);

require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/_layout.php';

protegerPagina();

$imagens = [];
$totalImagens = 0;
$totalTextos = 0;

try {
    $pdo = getPDOConnection();

    $stmtImagens = $pdo->prepare('SELECT * FROM site_imagens ORDER BY id ASC');
    $stmtImagens->execute();
    $imagens = $stmtImagens->fetchAll();
    $totalImagens = is_array($imagens) ? count($imagens) : 0;

    $stmtTextos = $pdo->prepare('SELECT COUNT(*) AS total FROM site_textos WHERE ativo = 1');
    $stmtTextos->execute();
    $totalTextos = (int) ($stmtTextos->fetch()['total'] ?? 0);
} catch (Throwable $e) {
    setFlash('error', 'Nao foi possivel carregar os dados do painel. Verifique a conexao com o banco.');
}

renderAdminHeader('Dashboard', 'dashboard');
?>

<section class="card intro-card">
  <h2>Painel administrativo guiado</h2>
  <p class="text-muted">Aqui voce pode alterar apenas conteudos de texto e imagens da landing page.</p>
  <p class="text-muted">Nao existe edicao de HTML, CSS, JavaScript ou codigo PHP pelo painel.</p>
</section>

<section class="grid grid-3" style="margin-top:16px;">
  <article class="card">
    <h2>Imagens configuradas</h2>
    <p class="kpi"><?= (int) $totalImagens ?></p>
    <p class="text-muted">Total de imagens cadastradas para edicao.</p>
  </article>

  <article class="card">
    <h2>Textos ativos</h2>
    <p class="kpi"><?= (int) $totalTextos ?></p>
    <p class="text-muted">Campos de titulo, subtitulo e textos configuraveis.</p>
  </article>

  <article class="card">
    <h2>Status do painel</h2>
    <p class="kpi">Seguro</p>
    <p class="text-muted">Sessao protegida, CSRF ativo e upload validado.</p>
  </article>
</section>

<section id="imagens" class="card" style="margin-top: 16px;">
  <h2>Imagens do site</h2>
  <p class="text-muted">Cada linha informa em qual secao a imagem aparece. Clique em "Alterar imagem" para trocar o arquivo.</p>

  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>Imagem</th>
          <th>Secao</th>
          <th>Onde altera</th>
          <th>Preview</th>
          <th>Recomendacao</th>
          <th>Acao</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!empty($imagens)): ?>
        <?php foreach ($imagens as $imagem): ?>
          <?php
            $path = (string) ($imagem['caminho'] ?? '');
            $src = preg_match('~^https?://~i', $path) ? $path : '../' . ltrim($path, '/');
            $largura = (int) ($imagem['largura_recomendada'] ?? 0);
            $altura = (int) ($imagem['altura_recomendada'] ?? 0);
            $maxMb = (string) ($imagem['tamanho_max_mb'] ?? '2');
            $meta = getAdminImageMeta((string) ($imagem['chave'] ?? ''));
          ?>
          <tr>
            <td>
              <strong><?= e((string) $imagem['titulo']) ?></strong><br>
              <span class="text-muted"><code><?= e((string) $imagem['chave']) ?></code></span>
            </td>
            <td><?= e((string) ($meta['secao'] ?? 'Secao')) ?></td>
            <td><?= e((string) ($meta['onde'] ?? 'Landing page')) ?></td>
            <td><img class="thumb" src="<?= e($src) ?>" alt="Preview"></td>
            <td><?= $largura ?>x<?= $altura ?> px<br><span class="text-muted">Max <?= e($maxMb) ?> MB</span></td>
            <td><a class="btn btn-primary" href="editar-imagem.php?id=<?= (int) $imagem['id'] ?>">Alterar imagem</a></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="6">Nenhuma imagem cadastrada. Importe o <code>database.sql</code>.</td>
        </tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<?php renderAdminFooter(); ?>