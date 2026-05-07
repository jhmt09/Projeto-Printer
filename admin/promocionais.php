<?php

declare(strict_types=1);

require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/_layout.php';

protegerPagina();

$promocionais = [];

try {
    $pdo = getPDOConnection();
    garantirTabelaPromocionais($pdo);

    $stmt = $pdo->query('SELECT * FROM site_promocionais ORDER BY ordem ASC, id ASC');
    $promocionais = $stmt->fetchAll();
} catch (Throwable $e) {
    setFlash('error', 'Nao foi possivel carregar as fotos promocionais.');
}

renderAdminHeader('Fotos Promocionais', 'promocionais');
?>

<section class="card intro-card">
  <h2>Galeria promocional ilimitada</h2>
  <p class="text-muted">Nesta area voce pode adicionar quantas fotos promocionais quiser para a secao "Imagens Promocionais do Trabalho".</p>
  <p class="text-muted">Voce altera somente titulo, legenda, imagem, alt e ordem de exibicao. Nao ha edicao de codigo.</p>
</section>

<section class="card" style="margin-top:16px;">
  <h2>Adicionar nova foto promocional</h2>
  <form method="post" action="salvar-promocional.php" enctype="multipart/form-data" style="margin-top:10px;">
    <input type="hidden" name="csrf_token" value="<?= e(gerarCsrfToken()) ?>">
    <input type="hidden" name="acao" value="criar">

    <div class="grid grid-2">
      <div class="form-group">
        <label for="novo_titulo">Titulo da foto</label>
        <input id="novo_titulo" type="text" name="titulo" maxlength="150" required>
      </div>
      <div class="form-group">
        <label for="novo_ordem">Ordem de exibicao</label>
        <input id="novo_ordem" type="number" name="ordem" min="0" value="0">
      </div>
    </div>

    <div class="form-group">
      <label for="nova_legenda">Legenda</label>
      <input id="nova_legenda" type="text" name="legenda" maxlength="255" placeholder="Texto curto que aparece sobre a foto">
    </div>

    <div class="form-group">
      <label for="novo_alt">Texto alternativo (alt)</label>
      <input id="novo_alt" type="text" name="alt_text" maxlength="255" placeholder="Descricao da imagem para acessibilidade">
    </div>

    <div class="form-group">
      <label for="novo_arquivo">Arquivo da foto (jpg, jpeg, png, webp)</label>
      <input id="novo_arquivo" type="file" name="arquivo" accept=".jpg,.jpeg,.png,.webp" required>
      <p class="text-muted" style="margin-top:6px;">Recomendado: 1400x900 px, maximo 3 MB.</p>
    </div>

    <button class="btn btn-primary" type="submit">Adicionar foto</button>
  </form>
</section>

<section class="card" style="margin-top:16px;">
  <h2>Fotos cadastradas</h2>
  <p class="text-muted">Edite os campos abaixo para ajustar conteudo e ordem. Voce pode substituir a imagem quando quiser.</p>

  <?php if (empty($promocionais)): ?>
    <p style="margin-top:10px;">Nenhuma foto promocional cadastrada.</p>
  <?php else: ?>
    <div class="grid" style="margin-top:12px;">
      <?php foreach ($promocionais as $item): ?>
        <?php
          $id = (int) ($item['id'] ?? 0);
          $path = (string) ($item['caminho'] ?? '');
          $src = preg_match('~^https?://~i', $path) ? $path : '../' . ltrim($path, '/');
        ?>
        <article class="card text-item" id="promo-<?= $id ?>" style="padding:14px;">
          <div class="grid grid-2" style="align-items:start;">
            <div>
              <img class="thumb" style="width:100%;max-width:460px;height:260px;" src="<?= e($src) ?>" alt="Preview">
              <p class="text-muted" style="margin-top:8px;">Arquivo atual: <code><?= e($path) ?></code></p>
            </div>

            <div>
              <form method="post" action="salvar-promocional.php" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= e(gerarCsrfToken()) ?>">
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" name="id" value="<?= $id ?>">

                <div class="grid grid-2">
                  <div class="form-group">
                    <label>Titulo</label>
                    <input type="text" name="titulo" maxlength="150" value="<?= e((string) ($item['titulo'] ?? '')) ?>" required>
                  </div>
                  <div class="form-group">
                    <label>Ordem</label>
                    <input type="number" name="ordem" min="0" value="<?= (int) ($item['ordem'] ?? 0) ?>">
                  </div>
                </div>

                <div class="form-group">
                  <label>Legenda</label>
                  <input type="text" name="legenda" maxlength="255" value="<?= e((string) ($item['legenda'] ?? '')) ?>">
                </div>

                <div class="form-group">
                  <label>Texto alternativo (alt)</label>
                  <input type="text" name="alt_text" maxlength="255" value="<?= e((string) ($item['alt_text'] ?? '')) ?>">
                </div>

                <div class="form-group">
                  <label>Status</label>
                  <?php $ativo = (int) ($item['ativo'] ?? 1); ?>
                  <select name="ativo">
                    <option value="1" <?= $ativo === 1 ? 'selected' : '' ?>>Exibir</option>
                    <option value="0" <?= $ativo === 0 ? 'selected' : '' ?>>Ocultar</option>
                  </select>
                </div>

                <div class="form-group">
                  <label>Substituir imagem (opcional)</label>
                  <input type="file" name="arquivo" accept=".jpg,.jpeg,.png,.webp">
                </div>

                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                  <button class="btn btn-primary" type="submit">Salvar</button>
                </div>
              </form>

              <form method="post" action="excluir-promocional.php" onsubmit="return confirm('Deseja excluir esta foto promocional?');" style="margin-top:10px;">
                <input type="hidden" name="csrf_token" value="<?= e(gerarCsrfToken()) ?>">
                <input type="hidden" name="id" value="<?= $id ?>">
                <button class="btn btn-danger" type="submit">Excluir foto</button>
              </form>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<?php renderAdminFooter(); ?>