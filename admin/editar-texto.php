<?php

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
    setFlash('error', 'Não foi possível carregar os textos configuráveis.');
}

renderAdminHeader('Textos do Site', 'textos');
?>

<section class="card">
  <h2>Editar textos do site</h2>
  <p class="text-muted">Estrutura preparada para edição de conteúdo simples da landing page.</p>

  <?php if (empty($textos)): ?>
    <p>Nenhum texto cadastrado. Importe o arquivo <code>database.sql</code>.</p>
  <?php else: ?>
    <div class="grid" style="margin-top:12px;">
      <?php foreach ($textos as $texto): ?>
        <article id="texto-<?= (int) $texto['id'] ?>" class="card" style="padding:14px;">
          <form method="post" action="salvar-texto.php">
            <input type="hidden" name="csrf_token" value="<?= e(gerarCsrfToken()) ?>">
            <input type="hidden" name="id" value="<?= (int) $texto['id'] ?>">

            <div class="form-group">
              <label>Título</label>
              <input type="text" value="<?= e((string) $texto['titulo']) ?>" readonly>
            </div>

            <div class="form-group">
              <label>Chave</label>
              <input type="text" value="<?= e((string) $texto['chave']) ?>" readonly>
            </div>

            <div class="form-group">
              <label>Conteúdo</label>
              <?php if ((string) $texto['tipo'] === 'input'): ?>
                <input type="text" name="conteudo" value="<?= e((string) $texto['conteudo']) ?>" required>
              <?php else: ?>
                <textarea name="conteudo" required><?= e((string) $texto['conteudo']) ?></textarea>
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

