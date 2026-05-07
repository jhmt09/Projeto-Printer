<?php

declare(strict_types=1);

require_once __DIR__ . '/config/helpers.php';

sincronizarConfiguracoesSite();

$t = static fn(string $chave, string $fallback): string => getTextoSite($chave, $fallback);

$siteTitulo = $t('site_titulo', 'Printer Goiania - Venda e Assistencia de Datadores Inkjet');
$siteDescricao = $t('site_descricao', 'Printer Goiania: venda e assistencia tecnica de datadores inkjet com atendimento em Goiania, Brasilia e regiao.');

$telefoneTopo = $t('contato_telefone', '(62) 9 9999-9999');
$telefoneTopoNumeros = preg_replace('/\D+/', '', $telefoneTopo);
if (!is_string($telefoneTopoNumeros) || $telefoneTopoNumeros === '') {
    $telefoneTopoNumeros = '5562999999999';
}
$whatsNumero = preg_replace('/\D+/', '', $t('contato_whatsapp_numero', '5562999999999'));
if (!is_string($whatsNumero) || $whatsNumero === '') {
    $whatsNumero = '5562999999999';
}
$whatsTextoPadrao = $t('contato_whatsapp_texto', 'Ola Printer Goiania, gostaria de mais informacoes.');
$whatsLabelTopo = $t('topbar_whatsapp_label', 'WhatsApp Comercial');
$whatsHref = 'https://wa.me/' . $whatsNumero . '?text=' . rawurlencode($whatsTextoPadrao);

$normalizarUrl = static function (string $url): string {
    $url = trim($url);
    if ($url === '' || $url === '#') {
        return '#';
    }

    if (!preg_match('~^https?://~i', $url)) {
        return '#';
    }

    return $url;
};

$socialFacebook = $normalizarUrl($t('social_facebook_url', '#'));
$socialInstagram = $normalizarUrl($t('social_instagram_url', '#'));
$socialYoutube = $normalizarUrl($t('social_youtube_url', '#'));

$logoSrc = getImagem('logo_site', 'images/logo.png');
$logoAlt = getAltImagem('logo_site', 'Logo da Printer Goiania');
$sobreSrc = getImagem('imagem_sobre', 'images/banner/02-banner.jpg');
$sobreAlt = getAltImagem('imagem_sobre', 'Imagem institucional da Printer Goiania');

$servico1Src = getImagem('servico_1', 'images/banner/01-banner.jpg');
$servico1Alt = getAltImagem('servico_1', 'Imagem do servico 1');
$servico2Src = getImagem('servico_2', 'images/banner/03-banner.jpg');
$servico2Alt = getAltImagem('servico_2', 'Imagem do servico 2');
$servico3Src = getImagem('servico_3', 'images/banner/04-banner.jpg');
$servico3Alt = getAltImagem('servico_3', 'Imagem do servico 3');
$servico4Src = getImagem('servico_4', 'images/banner/02-banner.jpg');
$servico4Alt = getAltImagem('servico_4', 'Imagem do servico 4');

$carouselSlides = [
    ['src' => getImagem('carousel_1', getImagem('banner_principal', 'images/banner/01-banner.jpg')), 'alt' => getAltImagem('carousel_1', 'Slide 1 do carrossel')],
    ['src' => getImagem('carousel_2', 'images/banner/02-banner.jpg'), 'alt' => getAltImagem('carousel_2', 'Slide 2 do carrossel')],
    ['src' => getImagem('carousel_3', 'images/banner/03-banner.jpg'), 'alt' => getAltImagem('carousel_3', 'Slide 3 do carrossel')],
    ['src' => getImagem('carousel_4', 'images/banner/04-banner.jpg'), 'alt' => getAltImagem('carousel_4', 'Slide 4 do carrossel')],
];
$promocionais = getPromocionaisAtivos();

$mostrarOrcamento = getTextoBoolean('orcamento_exibir', true);

$footerTelefone = $t('footer_telefone', '+55 (62) 9 9999-9999');
$footerEmail = $t('footer_email', 'contato@printergoiania.com.br');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($siteTitulo) ?></title>
  <meta name="description" content="<?= e($siteDescricao) ?>">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            roboto: ['Roboto', 'sans-serif']
          }
        }
      }
    };
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="font-roboto text-slate-900">
  <div class="min-h-screen bg-[#f4f6fb]">
    <section class="bg-[#083061] text-white">
      <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-2 text-sm md:flex-row md:items-center md:justify-between">
        <ul class="flex flex-wrap items-center gap-4">
          <li><a href="tel:+<?= e($telefoneTopoNumeros) ?>" class="hover:text-[#ffddd8]"><i class="fa fa-phone"></i> <?= e($telefoneTopo) ?></a></li>
          <li><a href="<?= e($whatsHref) ?>" target="_blank" rel="noopener" class="hover:text-[#ffddd8]"><i class="fa fa-whatsapp"></i> <?= e($whatsLabelTopo) ?></a></li>
        </ul>
        <ul class="flex items-center gap-3 text-base">
          <li><a href="<?= e($socialFacebook) ?>" target="_blank" rel="noopener" aria-label="Facebook" class="hover:text-[#ffddd8]"><i class="fa fa-facebook"></i></a></li>
          <li><a href="<?= e($socialInstagram) ?>" target="_blank" rel="noopener" aria-label="Instagram" class="hover:text-[#ffddd8]"><i class="fa fa-instagram"></i></a></li>
          <li><a href="<?= e($socialYoutube) ?>" target="_blank" rel="noopener" aria-label="YouTube" class="hover:text-[#ffddd8]"><i class="fa fa-youtube"></i></a></li>
        </ul>
      </div>
    </section>

    <header class="bg-white">
      <div class="mx-auto max-w-7xl px-4 py-6 text-center">
        <a href="index.php#home" class="inline-flex items-center justify-center">
          <img src="<?= e($logoSrc) ?>" alt="<?= e($logoAlt) ?>" class="h-32 w-auto object-contain md:h-36" loading="eager">
        </a>
      </div>
    </header>

    <nav class="sticky top-0 z-50 border-y border-slate-200 bg-white/95 shadow-sm backdrop-blur">
      <div class="mx-auto max-w-7xl px-4">
        <ul class="flex flex-wrap items-center justify-center gap-x-8 gap-y-3 py-4 text-sm font-semibold uppercase tracking-[0.05em] text-slate-800">
          <li><a href="#produtos" class="hover:text-[#c64d41]"><?= e($t('menu_venda', 'Venda')) ?></a></li>
          <li><a href="#assistencia" class="hover:text-[#c64d41]"><?= e($t('menu_assistencia', 'Assistencia Tecnica')) ?></a></li>
          <li><a href="#promocionais" class="hover:text-[#c64d41]"><?= e($t('menu_promocionais', 'Promocoes')) ?></a></li>
          <li><a href="#vantagens" class="hover:text-[#c64d41]"><?= e($t('menu_vantagens', 'Vantagens')) ?></a></li>
          <li><a href="#depoimentos" class="hover:text-[#c64d41]"><?= e($t('menu_depoimentos', 'Depoimentos')) ?></a></li>
          <?php if ($mostrarOrcamento): ?>
            <li><a href="#orcamento" class="hover:text-[#c64d41]"><?= e($t('menu_orcamento', 'Orcamento')) ?></a></li>
          <?php endif; ?>
          <li><a href="#contato" class="hover:text-[#c64d41]"><?= e($t('menu_contato', 'Contato')) ?></a></li>
        </ul>
      </div>
    </nav>

    <main id="home">
      <section class="hero-section relative overflow-hidden" data-carousel data-interval="5500">
        <div class="hero-carousel-track">
          <?php foreach ($carouselSlides as $index => $slide): ?>
            <article class="hero-slide<?= $index === 0 ? ' is-active' : '' ?>" data-carousel-slide>
              <img src="<?= e($slide['src']) ?>" alt="<?= e($slide['alt']) ?>" class="hero-slide-image" loading="<?= $index === 0 ? 'eager' : 'lazy' ?>">
            </article>
          <?php endforeach; ?>
        </div>

        <div class="hero-overlay absolute inset-0 z-10"></div>
        <div class="hero-accent hero-accent-a" aria-hidden="true"></div>
        <div class="hero-accent hero-accent-b" aria-hidden="true"></div>

        <div class="absolute inset-0 z-20 mx-auto grid w-full max-w-7xl items-center gap-6 px-4 py-16 lg:grid-cols-[1fr_auto]">
          <div class="hero-content max-w-2xl">
            <p class="hero-badge inline-flex rounded px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em]"><?= e($t('hero_badge', 'Datadores Inkjet para Industria')) ?></p>
            <h1 class="hero-title mt-5 text-4xl font-black leading-tight md:text-6xl"><?= e($t('hero_titulo', 'Venda e Assistencia de Datadores para elevar sua producao.')) ?></h1>
            <p class="hero-subtitle mt-5 max-w-xl text-base leading-8"><?= e($t('hero_subtitulo', 'Estrutura tecnica para atendimento rapido em Goiania, Brasilia e regiao, com foco em performance, confiabilidade e suporte continuo.')) ?></p>
            <div class="mt-8 flex flex-wrap gap-4">
              <a href="#contato" class="hero-btn hero-btn-primary inline-flex items-center rounded-full px-7 py-3 text-sm font-bold uppercase tracking-[0.06em] text-white transition"><?= e($t('hero_btn_contato', 'Entre em contato')) ?></a>
              <a href="<?= e($whatsHref) ?>" target="_blank" rel="noopener" class="hero-btn hero-btn-secondary inline-flex items-center rounded-full px-7 py-3 text-sm font-bold uppercase tracking-[0.06em] text-white transition"><i class="fa fa-whatsapp mr-2"></i><?= e($t('hero_btn_whatsapp', 'Falar no WhatsApp')) ?></a>
            </div>
          </div>
        </div>

        <button type="button" class="hero-control hero-control-prev" data-carousel-prev aria-label="Slide anterior"><i class="fa fa-angle-left"></i></button>
        <button type="button" class="hero-control hero-control-next" data-carousel-next aria-label="Proximo slide"><i class="fa fa-angle-right"></i></button>

        <div class="hero-dots" aria-label="Indicadores do carrossel">
          <?php foreach ($carouselSlides as $index => $slide): ?>
            <button type="button" class="hero-dot<?= $index === 0 ? ' is-active' : '' ?>" data-carousel-dot="<?= (int) $index ?>" aria-label="Ir para slide <?= (int) ($index + 1) ?>"></button>
          <?php endforeach; ?>
        </div>
      </section>

      <section id="assistencia" class="relative z-20 -mt-16 px-4">
        <div class="mx-auto grid max-w-7xl gap-6 md:grid-cols-2">
          <article class="rounded-lg border border-slate-200 bg-white p-8 shadow-lg shadow-slate-200/70">
            <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded bg-[#083061]/10 text-2xl text-[#083061]"><i class="fa fa-shopping-cart"></i></div>
            <h2 class="text-2xl font-bold text-[#083061]"><?= e($t('bloco_venda_titulo', 'Venda')) ?></h2>
            <p class="mt-3 text-slate-600"><?= e($t('bloco_venda_texto', 'Trabalhamos com datadores inkjet para ampliar produtividade e reduzir custos operacionais, com orientacao tecnica na escolha do equipamento ideal.')) ?></p>
            <a href="#produtos" class="mt-5 inline-block text-sm font-bold uppercase tracking-[0.06em] text-[#c64d41] hover:text-[#a34e4c]"><?= e($t('bloco_venda_btn', 'Mais informacoes')) ?></a>
          </article>

          <article class="rounded-lg border border-slate-200 bg-white p-8 shadow-lg shadow-slate-200/70">
            <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded bg-[#c64d41]/10 text-2xl text-[#c64d41]"><i class="fa fa-wrench"></i></div>
            <h2 class="text-2xl font-bold text-[#083061]"><?= e($t('bloco_assistencia_titulo', 'Assistencia Tecnica')) ?></h2>
            <p class="mt-3 text-slate-600"><?= e($t('bloco_assistencia_texto', 'Cobertura tecnica com prioridade para chamados emergenciais e manutencao corretiva/preventiva em toda a regiao de atendimento da Printer Goiania.')) ?></p>
            <a href="#contato" class="mt-5 inline-block text-sm font-bold uppercase tracking-[0.06em] text-[#c64d41] hover:text-[#a34e4c]"><?= e($t('bloco_assistencia_btn', 'Mais informacoes')) ?></a>
          </article>
        </div>
      </section>

      <section id="produtos" class="px-4 pb-20 pt-20">
        <div class="mx-auto max-w-7xl">
          <div class="text-center">
            <h2 class="text-4xl font-black text-[#083061]"><?= e($t('produtos_titulo', 'Produtos a Venda')) ?></h2>
          </div>
          <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <article class="rounded-lg border border-slate-200 bg-white p-4 shadow-md shadow-slate-200/70">
              <img src="<?= e($servico1Src) ?>" alt="<?= e($servico1Alt) ?>" class="h-52 w-full rounded object-cover" loading="lazy">
              <h3 class="mt-4 text-center text-base font-semibold text-[#083061]"><?= e($t('produto_1_texto', 'Datador Inkjet industrial com instalacao rapida e excelente qualidade de impressao.')) ?></h3>
            </article>
            <article class="rounded-lg border border-slate-200 bg-white p-4 shadow-md shadow-slate-200/70">
              <img src="<?= e($servico2Src) ?>" alt="<?= e($servico2Alt) ?>" class="h-52 w-full rounded object-cover" loading="lazy">
              <h3 class="mt-4 text-center text-base font-semibold text-[#083061]"><?= e($t('produto_2_texto', 'Esteiras e acessorios para fluxo continuo de codificacao em linha de producao.')) ?></h3>
            </article>
            <article class="rounded-lg border border-slate-200 bg-white p-4 shadow-md shadow-slate-200/70">
              <img src="<?= e($servico3Src) ?>" alt="<?= e($servico3Alt) ?>" class="h-52 w-full rounded object-cover" loading="lazy">
              <h3 class="mt-4 text-center text-base font-semibold text-[#083061]"><?= e($t('produto_3_texto', 'Rotuladoras semiautomaticas para rotulagem e codificacao com alta produtividade.')) ?></h3>
            </article>
            <article class="rounded-lg border border-slate-200 bg-white p-4 shadow-md shadow-slate-200/70">
              <img src="<?= e($servico4Src) ?>" alt="<?= e($servico4Alt) ?>" class="h-52 w-full rounded object-cover" loading="lazy">
              <h3 class="mt-4 text-center text-base font-semibold text-[#083061]"><?= e($t('produto_4_texto', 'Rebobinadoras para preparar rotulos datados com agilidade e padrao de qualidade.')) ?></h3>
            </article>
          </div>
        </div>
      </section>

      <section id="promocionais" class="bg-white px-4 py-20">
        <div class="mx-auto max-w-7xl">
          <div class="text-center">
            <h2 class="text-4xl font-black text-[#083061]"><?= e($t('promocionais_titulo', 'Imagens Promocionais do Trabalho')) ?></h2>
            <p class="mx-auto mt-4 max-w-3xl text-slate-600"><?= e($t('promocionais_subtitulo', 'Registros reais de instalacoes, atendimentos e operacoes em clientes atendidos pela Printer Goiania.')) ?></p>
          </div>

          <?php if (!empty($promocionais)): ?>
            <div class="promo-gallery mt-10">
              <?php foreach ($promocionais as $item): ?>
                <?php
                  $imgSrc = (string) ($item['caminho'] ?? '');
                  $imgAlt = (string) ($item['alt_text'] ?? '');
                  $imgLegenda = (string) ($item['legenda'] ?? '');
                  if ($imgSrc === '') {
                      continue;
                  }
                ?>
                <article class="promo-card">
                  <img src="<?= e($imgSrc) ?>" alt="<?= e($imgAlt !== '' ? $imgAlt : 'Imagem promocional') ?>" class="promo-image" loading="lazy">
                  <div class="promo-caption">
                    <p><?= e($imgLegenda !== '' ? $imgLegenda : 'Trabalho promocional da Printer Goiania.') ?></p>
                  </div>
                </article>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p class="mt-10 text-center text-slate-500">Nenhuma foto promocional cadastrada no momento.</p>
          <?php endif; ?>
        </div>
      </section>

      <section id="vantagens" class="px-4 py-20">
        <div class="mx-auto grid max-w-7xl gap-8 lg:grid-cols-[1fr_1.2fr_1fr]">
          <div class="space-y-5">
            <article class="rounded-lg border border-slate-200 bg-white p-6 shadow-md">
              <h3 class="text-xl font-bold text-[#083061]"><?= e($t('vantagem_1_titulo', 'Rapida distribuicao')) ?></h3>
              <p class="mt-2 text-slate-600"><?= e($t('vantagem_1_texto', 'Infraestrutura para entrega e instalacao dentro de prazos curtos.')) ?></p>
            </article>
            <article class="rounded-lg border border-slate-200 bg-white p-6 shadow-md">
              <h3 class="text-xl font-bold text-[#083061]"><?= e($t('vantagem_2_titulo', 'Confiabilidade')) ?></h3>
              <p class="mt-2 text-slate-600"><?= e($t('vantagem_2_texto', 'Equipamentos revisados e suporte continuo para operacao estavel.')) ?></p>
            </article>
          </div>

          <div class="overflow-hidden rounded-xl border border-slate-200 bg-white p-4 shadow-lg">
            <img src="<?= e($sobreSrc) ?>" alt="<?= e($sobreAlt) ?>" class="h-full min-h-[280px] w-full rounded-lg object-cover" loading="lazy">
          </div>

          <div class="space-y-5">
            <article class="rounded-lg border border-slate-200 bg-white p-6 shadow-md">
              <h3 class="text-xl font-bold text-[#083061]"><?= e($t('vantagem_3_titulo', 'Custo-beneficio')) ?></h3>
              <p class="mt-2 text-slate-600"><?= e($t('vantagem_3_texto', 'Solucoes tecnicas com excelente retorno para operacao industrial.')) ?></p>
            </article>
            <article class="rounded-lg border border-slate-200 bg-white p-6 shadow-md">
              <h3 class="text-xl font-bold text-[#083061]"><?= e($t('vantagem_4_titulo', 'Assistencia especializada')) ?></h3>
              <p class="mt-2 text-slate-600"><?= e($t('vantagem_4_texto', 'Equipe credenciada para manutencao corretiva e preventiva.')) ?></p>
            </article>
          </div>
        </div>
      </section>

      <section class="bg-white px-4 py-20">
        <div class="mx-auto grid max-w-7xl gap-10 lg:grid-cols-2">
          <div>
            <h2 class="text-4xl font-black text-[#083061]"><?= e($t('sobre_titulo', 'Sobre a Printer Goiania')) ?></h2>
            <p class="mt-5 text-slate-600 leading-8"><?= e($t('sobre_texto', 'A Printer Goiania atua com venda e assistencia tecnica de datadores inkjet para industrias e empresas que precisam de codificacao eficiente em embalagens e rotulos.')) ?></p>
          </div>
          <div class="grid gap-4 sm:grid-cols-2">
            <div class="rounded-lg border border-slate-200 bg-[#f8fbff] p-6 text-center shadow-sm">
              <i class="fa fa-diamond text-3xl text-[#c64d41]"></i>
              <h3 class="mt-4 text-lg font-bold text-[#083061]"><?= e($t('sobre_card_1', 'Garantia de qualidade')) ?></h3>
            </div>
            <div class="rounded-lg border border-slate-200 bg-[#f8fbff] p-6 text-center shadow-sm">
              <i class="fa fa-thumbs-up text-3xl text-[#c64d41]"></i>
              <h3 class="mt-4 text-lg font-bold text-[#083061]"><?= e($t('sobre_card_2', '100% de satisfacao')) ?></h3>
            </div>
            <div class="rounded-lg border border-slate-200 bg-[#f8fbff] p-6 text-center shadow-sm sm:col-span-2">
              <i class="fa fa-cogs text-3xl text-[#c64d41]"></i>
              <h3 class="mt-4 text-lg font-bold text-[#083061]"><?= e($t('sobre_card_3', 'Assistencia personalizada')) ?></h3>
            </div>
          </div>
        </div>
      </section>

      <section id="depoimentos" class="bg-white px-4 py-20">
        <div class="mx-auto max-w-7xl">
          <div class="text-center"><span class="text-sm font-bold uppercase tracking-[0.2em] text-[#c64d41]"><?= e($t('depoimentos_titulo', 'Depoimentos')) ?></span></div>
          <div class="mt-10 grid gap-6 md:grid-cols-3">
            <article class="rounded-lg border border-slate-200 bg-[#f8fbff] p-8 text-center shadow-md">
              <h3 class="mt-4 text-xl font-bold text-[#083061]"><?= e($t('depoimento_1_nome', 'Cliente Industrial')) ?></h3>
              <p class="mt-3 text-slate-600"><?= e($t('depoimento_1_texto', 'Atendimento tecnico agil, suporte claro e excelente desempenho dos datadores na operacao diaria.')) ?></p>
            </article>
            <article class="rounded-lg border border-slate-200 bg-[#f8fbff] p-8 text-center shadow-md">
              <h3 class="mt-4 text-xl font-bold text-[#083061]"><?= e($t('depoimento_2_nome', 'Gestor de Producao')) ?></h3>
              <p class="mt-3 text-slate-600"><?= e($t('depoimento_2_texto', 'Conseguimos reduzir paradas na linha com manutencao preventiva e suporte rapido da equipe Printer Goiania.')) ?></p>
            </article>
            <article class="rounded-lg border border-slate-200 bg-[#f8fbff] p-8 text-center shadow-md">
              <h3 class="mt-4 text-xl font-bold text-[#083061]"><?= e($t('depoimento_3_nome', 'Coordenador Tecnico')) ?></h3>
              <p class="mt-3 text-slate-600"><?= e($t('depoimento_3_texto', 'Equipe confiavel e consultiva. Resolveram nossa demanda de codificacao com otima relacao custo-beneficio.')) ?></p>
            </article>
          </div>
        </div>
      </section>

      <?php if ($mostrarOrcamento): ?>
        <section id="orcamento" class="px-4 py-20">
          <div class="mx-auto max-w-4xl rounded-2xl border border-slate-200 bg-white p-8 shadow-lg shadow-slate-200/70">
            <h2 class="text-3xl font-black text-[#083061]"><?= e($t('orcamento_titulo', 'Solicite um orcamento')) ?></h2>
            <p class="mt-3 text-slate-600"><?= e($t('orcamento_texto', 'Preencha os dados e envie sua solicitacao para nossa equipe comercial.')) ?></p>

            <form id="formOrcamento" class="mt-8 grid gap-4" data-whatsapp-number="<?= e($whatsNumero) ?>" action="#" method="post" onsubmit="return false;">
              <div class="grid gap-4 md:grid-cols-2">
                <input id="orcamentoNome" type="text" required placeholder="<?= e($t('orcamento_campo_nome', 'Nome')) ?>" class="rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 outline-none focus:border-[#083061]">
                <input id="orcamentoEmail" type="email" required placeholder="<?= e($t('orcamento_campo_email', 'E-mail')) ?>" class="rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 outline-none focus:border-[#083061]">
              </div>
              <input id="orcamentoTelefone" type="text" required placeholder="<?= e($t('orcamento_campo_telefone', 'Telefone')) ?>" class="rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 outline-none focus:border-[#083061]">
              <textarea id="orcamentoMensagem" required rows="5" placeholder="<?= e($t('orcamento_campo_mensagem', 'Mensagem')) ?>" class="rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 outline-none focus:border-[#083061]"></textarea>
              <button type="submit" class="inline-flex w-full items-center justify-center rounded bg-[#083061] px-7 py-3 text-sm font-bold uppercase tracking-[0.06em] text-white transition hover:bg-[#0f4588]"><?= e($t('orcamento_btn', 'Enviar orcamento')) ?></button>
            </form>
            <p class="mt-3 text-xs text-slate-500"><?= e($t('orcamento_nota', 'Ao enviar, abriremos seu WhatsApp com a mensagem pronta.')) ?></p>
          </div>
        </section>
      <?php endif; ?>

      <section class="px-4 py-20">
        <div class="mx-auto max-w-7xl text-center">
          <h2 class="text-4xl font-black text-[#083061]"><?= e($t('segmentos_titulo', 'Segmentos de Aplicacao')) ?></h2>
          <p class="mx-auto mt-4 max-w-3xl text-slate-600"><?= e($t('segmentos_texto', 'O datador inkjet pode ser utilizado em cosmeticos, alimentos, farmaceutico, quimica, logistica, bebidas e diversas linhas de embalagem.')) ?></p>
          <div class="mt-10 grid gap-4 sm:grid-cols-3 lg:grid-cols-6">
            <div class="rounded-lg border border-slate-200 bg-white p-4 text-sm font-semibold text-[#083061]"><?= e($t('segmento_1', 'Alimentos')) ?></div>
            <div class="rounded-lg border border-slate-200 bg-white p-4 text-sm font-semibold text-[#083061]"><?= e($t('segmento_2', 'Bebidas')) ?></div>
            <div class="rounded-lg border border-slate-200 bg-white p-4 text-sm font-semibold text-[#083061]"><?= e($t('segmento_3', 'Farmaceutico')) ?></div>
            <div class="rounded-lg border border-slate-200 bg-white p-4 text-sm font-semibold text-[#083061]"><?= e($t('segmento_4', 'Cosmeticos')) ?></div>
            <div class="rounded-lg border border-slate-200 bg-white p-4 text-sm font-semibold text-[#083061]"><?= e($t('segmento_5', 'Quimico')) ?></div>
            <div class="rounded-lg border border-slate-200 bg-white p-4 text-sm font-semibold text-[#083061]"><?= e($t('segmento_6', 'Logistica')) ?></div>
          </div>
        </div>
      </section>
    </main>

    <footer id="contato" class="bg-[#0b1d34] text-slate-100">
      <div class="mx-auto grid max-w-7xl gap-10 px-4 py-16 md:grid-cols-[1.5fr_1fr_1fr]">
        <div>
          <h3 class="text-2xl font-bold"><?= e($t('footer_titulo', 'Printer Goiania')) ?></h3>
          <ul class="mt-5 space-y-3 text-sm text-slate-300">
            <li><i class="fa fa-map-marker text-[#c64d41]"></i> <?= e($t('footer_endereco', 'R. Jequitiba, 543 - Jardim Mariliza, Goiania - GO')) ?></li>
            <li><i class="fa fa-phone text-[#c64d41]"></i> <?= e($footerTelefone) ?></li>
            <li><i class="fa fa-envelope text-[#c64d41]"></i> <?= e($footerEmail) ?></li>
          </ul>
        </div>

        <div>
          <h3 class="text-xl font-bold"><?= e($t('footer_menu_titulo', 'Acesso Rapido')) ?></h3>
          <ul class="mt-5 space-y-2 text-sm text-slate-300">
            <li><a href="#produtos" class="hover:text-white"><i class="fa fa-angle-double-right"></i> <?= e($t('menu_venda', 'Venda')) ?></a></li>
            <li><a href="#assistencia" class="hover:text-white"><i class="fa fa-angle-double-right"></i> <?= e($t('menu_assistencia', 'Assistencia Tecnica')) ?></a></li>
            <li><a href="#promocionais" class="hover:text-white"><i class="fa fa-angle-double-right"></i> <?= e($t('menu_promocionais', 'Promocoes')) ?></a></li>
            <li><a href="#vantagens" class="hover:text-white"><i class="fa fa-angle-double-right"></i> <?= e($t('menu_vantagens', 'Vantagens')) ?></a></li>
            <li><a href="#depoimentos" class="hover:text-white"><i class="fa fa-angle-double-right"></i> <?= e($t('menu_depoimentos', 'Depoimentos')) ?></a></li>
            <?php if ($mostrarOrcamento): ?>
              <li><a href="#orcamento" class="hover:text-white"><i class="fa fa-angle-double-right"></i> <?= e($t('menu_orcamento', 'Orcamento')) ?></a></li>
            <?php endif; ?>
            <li><a href="#contato" class="hover:text-white"><i class="fa fa-angle-double-right"></i> <?= e($t('menu_contato', 'Contato')) ?></a></li>
          </ul>
        </div>

        <div>
          <h3 class="text-xl font-bold"><?= e($t('footer_news_titulo', 'Newsletter')) ?></h3>
          <p class="mt-5 text-sm text-slate-300"><?= e($t('footer_news_texto', 'Receba novidades e conteudos tecnicos por e-mail.')) ?></p>
          <form class="mt-4 flex gap-2" action="#" method="post" onsubmit="return false;">
            <input type="email" placeholder="<?= e($t('footer_news_placeholder', 'Seu e-mail')) ?>" class="w-full rounded border border-slate-500 bg-[#102845] px-4 py-2 text-sm text-white outline-none placeholder:text-slate-400">
            <button type="button" class="rounded bg-[#c64d41] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#a34e4c]"><?= e($t('footer_news_btn', 'Enviar')) ?></button>
          </form>
          <div class="mt-5 flex gap-3 text-lg">
            <a href="<?= e($socialFacebook) ?>" target="_blank" rel="noopener" class="text-slate-300 transition hover:text-white" aria-label="Facebook"><i class="fa fa-facebook"></i></a>
            <a href="<?= e($socialInstagram) ?>" target="_blank" rel="noopener" class="text-slate-300 transition hover:text-white" aria-label="Instagram"><i class="fa fa-instagram"></i></a>
            <a href="<?= e($socialYoutube) ?>" target="_blank" rel="noopener" class="text-slate-300 transition hover:text-white" aria-label="YouTube"><i class="fa fa-youtube"></i></a>
          </div>
        </div>
      </div>

      <div class="border-t border-slate-700/70 py-8">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-center gap-4 px-4 text-center">
          <img src="<?= e($logoSrc) ?>" alt="<?= e($logoAlt) ?>" class="h-14 w-auto object-contain">
          <p class="text-sm text-slate-400"><?= e($t('footer_copyright', 'Copyright (c) 2026 Printer Goiania. Todos os direitos reservados.')) ?></p>
        </div>
      </div>
    </footer>

    <a href="<?= e($whatsHref) ?>" target="_blank" rel="noopener" class="fixed bottom-5 right-5 inline-flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-3xl text-white shadow-xl hover:scale-105" aria-label="WhatsApp">
      <i class="fa fa-whatsapp"></i>
    </a>
  </div>
  <script src="assets/js/main.js"></script>
</body>
</html>
