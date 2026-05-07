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

$ordemSecoes = [
    'Configuracoes Gerais',
    'Topo e Contato',
    'Topo e Redes Sociais',
    'Menu do Site',
    'Carrossel Principal',
    'Cards de Destaque',
    'Secao Produtos',
    'Secao Promocionais',
    'Secao Vantagens',
    'Secao Sobre',
    'Secao Depoimentos',
    'Secao Orcamento',
    'Secao Segmentos',
    'Rodape',
    'Outros Campos',
];

$descricaoSecao = [
    'Configuracoes Gerais' => 'Campos globais da pagina e informacoes de busca.',
    'Topo e Contato' => 'Informacoes exibidas na barra superior e botoes de contato.',
    'Topo e Redes Sociais' => 'Links dos icones sociais no topo e no rodape.',
    'Menu do Site' => 'Titulos dos itens de navegacao do menu.',
    'Carrossel Principal' => 'Textos do banner principal da home.',
    'Cards de Destaque' => 'Cards logo abaixo do banner principal.',
    'Secao Produtos' => 'Titulos e descricoes da area de produtos.',
    'Secao Promocionais' => 'Textos e legendas da galeria promocional.',
    'Secao Vantagens' => 'Titulos e textos dos blocos de vantagens.',
    'Secao Sobre' => 'Conteudo institucional da empresa.',
    'Secao Depoimentos' => 'Titulos, nomes e textos dos depoimentos.',
    'Secao Orcamento' => 'Formulario de orcamento e controle de exibicao da secao.',
    'Secao Segmentos' => 'Conteudo da grade de segmentos atendidos.',
    'Rodape' => 'Informacoes e textos exibidos no rodape.',
    'Outros Campos' => 'Campos adicionais configuraveis.',
];

$textosPorSecao = [];
foreach ($textos as $texto) {
    $chave = (string) ($texto['chave'] ?? '');
    $meta = getAdminTextMeta($chave);
    $secao = $meta['secao'];

    if (!isset($textosPorSecao[$secao])) {
        $textosPorSecao[$secao] = [];
    }

    $texto['meta'] = $meta;
    $textosPorSecao[$secao][] = $texto;
}

$ordemMap = array_flip($ordemSecoes);
uksort($textosPorSecao, static function (string $a, string $b) use ($ordemMap): int {
    $posA = $ordemMap[$a] ?? 9999;
    $posB = $ordemMap[$b] ?? 9999;

    if ($posA === $posB) {
        return strcmp($a, $b);
    }

    return $posA <=> $posB;
});

renderAdminHeader('Textos do Site', 'textos');
?>

<section class="card intro-card">
  <h2>Edicao guiada de textos</h2>
  <p class="text-muted">Este painel permite apenas alterar textos do site. Nao existe campo para editar HTML, codigo ou estrutura da pagina.</p>
  <p class="text-muted">Cada campo abaixo informa o bloco e o local exato onde a alteracao aparece na landing page.</p>
</section>

<?php if (empty($textosPorSecao)): ?>
  <section class="card" style="margin-top:16px;">
    <p>Nenhum texto cadastrado. Importe o arquivo <code>database.sql</code>.</p>
  </section>
<?php else: ?>
  <?php foreach ($textosPorSecao as $secao => $campos): ?>
    <section class="card section-card" style="margin-top:16px;">
      <h2><?= e($secao) ?></h2>
      <p class="text-muted"><?= e($descricaoSecao[$secao] ?? 'Campos de configuracao desta area.') ?></p>

      <div class="grid text-grid" style="margin-top:12px;">
        <?php foreach ($campos as $texto): ?>
          <?php
            $tipoCampo = (string) ($texto['tipo'] ?? 'textarea');
            $conteudoAtual = (string) ($texto['conteudo'] ?? '');
            $meta = is_array($texto['meta'] ?? null) ? $texto['meta'] : getAdminTextMeta((string) ($texto['chave'] ?? ''));
          ?>
          <article id="texto-<?= (int) $texto['id'] ?>" class="text-item card" style="padding:14px;">
            <p class="meta-line"><strong>Bloco:</strong> <?= e((string) ($meta['bloco'] ?? 'Campo')) ?></p>
            <p class="meta-line"><strong>Onde altera:</strong> <?= e((string) ($meta['onde'] ?? 'Landing page')) ?></p>
            <p class="meta-line"><strong>Chave tecnica:</strong> <code><?= e((string) ($texto['chave'] ?? '')) ?></code></p>

            <form method="post" action="salvar-texto.php" style="margin-top:10px;">
              <input type="hidden" name="csrf_token" value="<?= e(gerarCsrfToken()) ?>">
              <input type="hidden" name="id" value="<?= (int) $texto['id'] ?>">

              <div class="form-group">
                <label>Valor editavel</label>
                <?php if ($tipoCampo === 'input'): ?>
                  <input type="text" name="conteudo" maxlength="1000" value="<?= e($conteudoAtual) ?>" required>
                <?php elseif ($tipoCampo === 'boolean'): ?>
                  <select name="conteudo" required>
                    <option value="1" <?= $conteudoAtual === '1' ? 'selected' : '' ?>>Exibir</option>
                    <option value="0" <?= $conteudoAtual === '0' ? 'selected' : '' ?>>Ocultar</option>
                  </select>
                  <p class="text-muted" style="margin-top:6px;">Use para ligar ou desligar a exibicao deste bloco.</p>
                <?php else: ?>
                  <textarea name="conteudo" maxlength="10000" required><?= e($conteudoAtual) ?></textarea>
                <?php endif; ?>
              </div>

              <button class="btn btn-primary" type="submit">Salvar texto</button>
            </form>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endforeach; ?>
<?php endif; ?>

<?php renderAdminFooter(); ?>