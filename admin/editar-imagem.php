<?php

declare(strict_types=1);

require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/_layout.php';

protegerPagina();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    setFlash('error', 'Imagem invalida para edicao.');
    redirecionar('dashboard.php#imagens');
}

$imagem = null;

try {
    $pdo = getPDOConnection();
    $stmt = $pdo->prepare('SELECT * FROM site_imagens WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);
    $imagem = $stmt->fetch();
} catch (Throwable $e) {
    setFlash('error', 'Erro ao carregar imagem para edicao.');
    redirecionar('dashboard.php#imagens');
}

if (!is_array($imagem)) {
    setFlash('error', 'Imagem nao encontrada.');
    redirecionar('dashboard.php#imagens');
}

$path = (string) ($imagem['caminho'] ?? '');
$src = preg_match('~^https?://~i', $path) ? $path : '../' . ltrim($path, '/');
$meta = getAdminImageMeta((string) ($imagem['chave'] ?? ''));

renderAdminHeader('Editar Imagem', 'imagens');
?>

<section class="card intro-card">
  <h2>Edicao de imagem guiada</h2>
  <p class="text-muted">Nesta tela voce altera somente a imagem e o texto alternativo (alt). Nao existe campo para editar codigo.</p>
</section>

<section class="card" style="margin-top:16px;">
  <h2><?= e((string) $imagem['titulo']) ?></h2>
  <p class="text-muted"><?= e((string) ($imagem['descricao'] ?? '')) ?></p>

  <div class="info-chip-row" style="margin-top:10px;">
    <span class="info-chip"><strong>Secao:</strong> <?= e((string) ($meta['secao'] ?? 'Secao')) ?></span>
    <span class="info-chip"><strong>Onde altera:</strong> <?= e((string) ($meta['onde'] ?? 'Landing page')) ?></span>
  </div>

  <div class="grid grid-2" style="margin-top:14px; align-items:start;">
    <div>
      <img class="thumb" style="width:100%;max-width:460px;height:260px;" src="<?= e($src) ?>" alt="Preview atual">
      <p class="text-muted" style="margin-top:8px;">Arquivo atual: <code><?= e($path) ?></code></p>
    </div>

    <div>
      <form method="post" action="upload-imagem.php" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= e(gerarCsrfToken()) ?>">
        <input type="hidden" name="id" value="<?= (int) $imagem['id'] ?>">

        <div class="form-group">
          <label>Chave tecnica</label>
          <input type="text" value="<?= e((string) $imagem['chave']) ?>" readonly>
        </div>

        <div class="form-group">
          <label>Tamanho recomendado</label>
          <input type="text" value="<?= (int) ($imagem['largura_recomendada'] ?? 0) ?> x <?= (int) ($imagem['altura_recomendada'] ?? 0) ?> px | Max <?= e((string) ($imagem['tamanho_max_mb'] ?? '2')) ?> MB" readonly>
        </div>

        <div class="form-group">
          <label for="alt_text">Texto alternativo (alt)</label>
          <input type="text" id="alt_text" name="alt_text" maxlength="255" value="<?= e((string) ($imagem['alt_text'] ?? '')) ?>">
          <p class="text-muted" style="margin-top:6px;">Use texto simples (sem HTML).</p>
        </div>

        <div class="form-group">
          <label for="arquivo">Nova imagem (jpg, jpeg, png, webp)</label>
          <input type="file" id="arquivo" name="arquivo" accept=".jpg,.jpeg,.png,.webp">
          <p class="text-muted" style="margin-top:6px;">Voce pode salvar apenas o alt sem enviar novo arquivo.</p>
        </div>

        <div style="display:flex; gap:10px;">
          <button type="submit" class="btn btn-primary">Salvar alteracoes</button>
          <a href="dashboard.php#imagens" class="btn btn-secondary">Voltar</a>
        </div>
      </form>
    </div>
  </div>
</section>

<?php renderAdminFooter(); ?>