<?php

declare(strict_types=1);

require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/_layout.php';

protegerPagina();

$textos = [];

try {
    $pdo = getPDOConnection();
    $stmt = $pdo->prepare('SELECT * FROM site_textos ORDER BY id ASC');
    $stmt->execute();
    $textos = $stmt->fetchAll();
} catch (Throwable $e) {
    setFlash('error', 'Nao foi possivel carregar os textos configuraveis.');
}

renderAdminHeader('Textos do Site', 'textos');
?>

<section class="card">
  <h2>Editar textos do site</h2>
  <p class="text-muted">Todos os titulos e textos da landing podem ser alterados aqui.</p>

  <?php if (empty($textos)): ?>
    <p>Nenhum texto cadastrado. Importe o arquivo <code>database.sql</code>.</p>
  <?php else: ?>
    <div class="grid" style="margin-top:12px;">
      <?php foreach ($textos as $texto): ?>
        <?php
          $tipoCampo = (string) ($texto['tipo'] ?? 'textarea');
          $conteudoAtual = (string) ($texto['conteudo'] ?? '');
        ?>
        <article id="texto-<?= (int) $texto['id'] ?>" class="card" style="padding:14px;">
          <form method="post" action="salvar-texto.php">
            <input type="hidden" name="csrf_token" value="<?= e(gerarCsrfToken()) ?>">
            <input type="hidden" name="id" value="<?= (int) $texto['id'] ?>">

            <div class="form-group">
              <label>Titulo</label>
              <input type="text" value="<?= e((string) $texto['titulo']) ?>" readonly>
            </div>

            <div class="form-group">
              <label>Chave</label>
              <input type="text" value="<?= e((string) $texto['chave']) ?>" readonly>
            </div>

            <div class="form-group">
              <label>Conteudo</label>
              <?php if ($tipoCampo === 'input'): ?>
                <input type="text" name="conteudo" value="<?= e($conteudoAtual) ?>" required>
              <?php elseif ($tipoCampo === 'boolean'): ?>
                <select name="conteudo" required>
                  <option value="1" <?= $conteudoAtual === '1' ? 'selected' : '' ?>>Exibir</option>
                  <option value="0" <?= $conteudoAtual === '0' ? 'selected' : '' ?>>Ocultar</option>
                </select>
                <p class="text-muted" style="margin-top:6px;">Use este campo para ativar/desativar blocos da landing page.</p>
              <?php else: ?>
                <textarea name="conteudo" required><?= e($conteudoAtual) ?></textarea>
              <?php endif; ?>
            </div>

            <button class="btn btn-primary" type="submit">Salvar texto</button>
          </form>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<?php renderAdminFooter(); ?>