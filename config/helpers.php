<?php

declare(strict_types=1);

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/auth.php';

function redirecionar(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function limparTexto(string $texto, int $maxLen = 0): string
{
    $valor = trim($texto);
    $valor = str_replace(["\r\n", "\r"], "\n", $valor);

    if ($maxLen > 0) {
        $valor = mb_substr($valor, 0, $maxLen, 'UTF-8');
    }

    return $valor;
}

function e(?string $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function setFlash(string $tipo, string $mensagem): void
{
    $_SESSION['flash_messages'][] = [
        'tipo' => $tipo,
        'mensagem' => $mensagem,
    ];
}

/**
 * @return array<int, array{tipo:string,mensagem:string}>
 */
function getFlashMessages(): array
{
    $messages = $_SESSION['flash_messages'] ?? [];
    unset($_SESSION['flash_messages']);

    return is_array($messages) ? $messages : [];
}

function getPDOConnectionOrNull(): ?PDO
{
    static $pdo = null;
    static $falhou = false;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    if ($falhou) {
        return null;
    }

    try {
        $pdo = getPDOConnection();
        return $pdo;
    } catch (Throwable $e) {
        $falhou = true;
        return null;
    }
}

/**
 * @return array<int, array<string, mixed>>
 */
function getDefaultSiteImages(): array
{
    return [
        [
            'chave' => 'logo_site',
            'titulo' => 'Logo do Site',
            'descricao' => 'Logo principal exibida no cabecalho e rodape.',
            'caminho' => 'images/logo.png',
            'alt_text' => 'Logo da Printer Goiania',
            'largura_recomendada' => 600,
            'altura_recomendada' => 220,
            'tamanho_max_mb' => 1.00,
            'ativo' => 1,
        ],
        [
            'chave' => 'banner_principal',
            'titulo' => 'Banner Principal (Fallback)',
            'descricao' => 'Imagem de fallback para o topo da landing page.',
            'caminho' => 'images/banner/01-banner.jpg',
            'alt_text' => 'Banner principal da empresa',
            'largura_recomendada' => 1920,
            'altura_recomendada' => 900,
            'tamanho_max_mb' => 2.00,
            'ativo' => 1,
        ],
        [
            'chave' => 'carousel_1',
            'titulo' => 'Carrossel 1',
            'descricao' => 'Slide 1 do carrossel principal.',
            'caminho' => 'images/banner/01-banner.jpg',
            'alt_text' => 'Slide 1 do carrossel',
            'largura_recomendada' => 1920,
            'altura_recomendada' => 900,
            'tamanho_max_mb' => 2.00,
            'ativo' => 1,
        ],
        [
            'chave' => 'carousel_2',
            'titulo' => 'Carrossel 2',
            'descricao' => 'Slide 2 do carrossel principal.',
            'caminho' => 'images/banner/02-banner.jpg',
            'alt_text' => 'Slide 2 do carrossel',
            'largura_recomendada' => 1920,
            'altura_recomendada' => 900,
            'tamanho_max_mb' => 2.00,
            'ativo' => 1,
        ],
        [
            'chave' => 'carousel_3',
            'titulo' => 'Carrossel 3',
            'descricao' => 'Slide 3 do carrossel principal.',
            'caminho' => 'images/banner/03-banner.jpg',
            'alt_text' => 'Slide 3 do carrossel',
            'largura_recomendada' => 1920,
            'altura_recomendada' => 900,
            'tamanho_max_mb' => 2.00,
            'ativo' => 1,
        ],
        [
            'chave' => 'carousel_4',
            'titulo' => 'Carrossel 4',
            'descricao' => 'Slide 4 do carrossel principal.',
            'caminho' => 'images/banner/04-banner.jpg',
            'alt_text' => 'Slide 4 do carrossel',
            'largura_recomendada' => 1920,
            'altura_recomendada' => 900,
            'tamanho_max_mb' => 2.00,
            'ativo' => 1,
        ],
        [
            'chave' => 'imagem_sobre',
            'titulo' => 'Imagem da Secao Foto',
            'descricao' => 'Imagem principal do bloco institucional/foto.',
            'caminho' => 'images/banner/02-banner.jpg',
            'alt_text' => 'Imagem institucional da Printer Goiania',
            'largura_recomendada' => 1200,
            'altura_recomendada' => 800,
            'tamanho_max_mb' => 2.00,
            'ativo' => 1,
        ],
        [
            'chave' => 'servico_1',
            'titulo' => 'Imagem do Servico 1',
            'descricao' => 'Imagem exibida no card de produto 1.',
            'caminho' => 'images/banner/01-banner.jpg',
            'alt_text' => 'Imagem do servico 1',
            'largura_recomendada' => 1200,
            'altura_recomendada' => 800,
            'tamanho_max_mb' => 2.00,
            'ativo' => 1,
        ],
        [
            'chave' => 'servico_2',
            'titulo' => 'Imagem do Servico 2',
            'descricao' => 'Imagem exibida no card de produto 2.',
            'caminho' => 'images/banner/03-banner.jpg',
            'alt_text' => 'Imagem do servico 2',
            'largura_recomendada' => 1200,
            'altura_recomendada' => 800,
            'tamanho_max_mb' => 2.00,
            'ativo' => 1,
        ],
        [
            'chave' => 'servico_3',
            'titulo' => 'Imagem do Servico 3',
            'descricao' => 'Imagem exibida no card de produto 3.',
            'caminho' => 'images/banner/04-banner.jpg',
            'alt_text' => 'Imagem do servico 3',
            'largura_recomendada' => 1200,
            'altura_recomendada' => 800,
            'tamanho_max_mb' => 2.00,
            'ativo' => 1,
        ],
        [
            'chave' => 'servico_4',
            'titulo' => 'Imagem do Servico 4',
            'descricao' => 'Imagem exibida no card de produto 4.',
            'caminho' => 'images/banner/02-banner.jpg',
            'alt_text' => 'Imagem do servico 4',
            'largura_recomendada' => 1200,
            'altura_recomendada' => 800,
            'tamanho_max_mb' => 2.00,
            'ativo' => 1,
        ],
        [
            'chave' => 'promocional_1',
            'titulo' => 'Imagem Promocional 1',
            'descricao' => 'Imagem da secao promocional de trabalhos realizados.',
            'caminho' => 'images/banner/01-banner.jpg',
            'alt_text' => 'Imagem promocional 1',
            'largura_recomendada' => 1400,
            'altura_recomendada' => 900,
            'tamanho_max_mb' => 2.00,
            'ativo' => 1,
        ],
        [
            'chave' => 'promocional_2',
            'titulo' => 'Imagem Promocional 2',
            'descricao' => 'Imagem da secao promocional de trabalhos realizados.',
            'caminho' => 'images/banner/02-banner.jpg',
            'alt_text' => 'Imagem promocional 2',
            'largura_recomendada' => 1400,
            'altura_recomendada' => 900,
            'tamanho_max_mb' => 2.00,
            'ativo' => 1,
        ],
        [
            'chave' => 'promocional_3',
            'titulo' => 'Imagem Promocional 3',
            'descricao' => 'Imagem da secao promocional de trabalhos realizados.',
            'caminho' => 'images/banner/03-banner.jpg',
            'alt_text' => 'Imagem promocional 3',
            'largura_recomendada' => 1400,
            'altura_recomendada' => 900,
            'tamanho_max_mb' => 2.00,
            'ativo' => 1,
        ],
        [
            'chave' => 'promocional_4',
            'titulo' => 'Imagem Promocional 4',
            'descricao' => 'Imagem da secao promocional de trabalhos realizados.',
            'caminho' => 'images/banner/04-banner.jpg',
            'alt_text' => 'Imagem promocional 4',
            'largura_recomendada' => 1400,
            'altura_recomendada' => 900,
            'tamanho_max_mb' => 2.00,
            'ativo' => 1,
        ],
        [
            'chave' => 'promocional_5',
            'titulo' => 'Imagem Promocional 5',
            'descricao' => 'Imagem da secao promocional de trabalhos realizados.',
            'caminho' => 'images/banner/01-banner.jpg',
            'alt_text' => 'Imagem promocional 5',
            'largura_recomendada' => 1400,
            'altura_recomendada' => 900,
            'tamanho_max_mb' => 2.00,
            'ativo' => 1,
        ],
        [
            'chave' => 'promocional_6',
            'titulo' => 'Imagem Promocional 6',
            'descricao' => 'Imagem da secao promocional de trabalhos realizados.',
            'caminho' => 'images/banner/02-banner.jpg',
            'alt_text' => 'Imagem promocional 6',
            'largura_recomendada' => 1400,
            'altura_recomendada' => 900,
            'tamanho_max_mb' => 2.00,
            'ativo' => 1,
        ],
    ];
}

/**
 * @return array<int, array<string, mixed>>
 */
function getDefaultSiteTexts(): array
{
    return [
        ['chave' => 'site_titulo', 'titulo' => 'Titulo da pagina', 'conteudo' => 'Printer Goiania - Venda e Assistencia de Datadores Inkjet', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'site_descricao', 'titulo' => 'Descricao da pagina', 'conteudo' => 'Printer Goiania: venda e assistencia tecnica de datadores inkjet com atendimento em Goiania, Brasilia e regiao.', 'tipo' => 'textarea', 'ativo' => 1],

        ['chave' => 'contato_telefone', 'titulo' => 'Telefone principal', 'conteudo' => '(62) 9 9999-9999', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'contato_whatsapp_numero', 'titulo' => 'WhatsApp (somente numeros)', 'conteudo' => '5562999999999', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'contato_whatsapp_texto', 'titulo' => 'Mensagem padrao WhatsApp', 'conteudo' => 'Ola Printer Goiania, gostaria de mais informacoes.', 'tipo' => 'textarea', 'ativo' => 1],
        ['chave' => 'topbar_whatsapp_label', 'titulo' => 'Texto topbar WhatsApp', 'conteudo' => 'WhatsApp Comercial', 'tipo' => 'input', 'ativo' => 1],

        ['chave' => 'social_facebook_url', 'titulo' => 'URL Facebook', 'conteudo' => '#', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'social_instagram_url', 'titulo' => 'URL Instagram', 'conteudo' => '#', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'social_youtube_url', 'titulo' => 'URL YouTube', 'conteudo' => '#', 'tipo' => 'input', 'ativo' => 1],

        ['chave' => 'menu_venda', 'titulo' => 'Menu: Venda', 'conteudo' => 'Venda', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'menu_assistencia', 'titulo' => 'Menu: Assistencia tecnica', 'conteudo' => 'Assistencia Tecnica', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'menu_promocionais', 'titulo' => 'Menu: Promocoes', 'conteudo' => 'Promocoes', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'menu_vantagens', 'titulo' => 'Menu: Vantagens', 'conteudo' => 'Vantagens', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'menu_depoimentos', 'titulo' => 'Menu: Depoimentos', 'conteudo' => 'Depoimentos', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'menu_orcamento', 'titulo' => 'Menu: Orcamento', 'conteudo' => 'Orcamento', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'menu_contato', 'titulo' => 'Menu: Contato', 'conteudo' => 'Contato', 'tipo' => 'input', 'ativo' => 1],

        ['chave' => 'hero_badge', 'titulo' => 'Hero: badge', 'conteudo' => 'Datadores Inkjet para Industria', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'hero_titulo', 'titulo' => 'Hero: titulo', 'conteudo' => 'Venda e Assistencia de Datadores para elevar sua producao.', 'tipo' => 'textarea', 'ativo' => 1],
        ['chave' => 'hero_subtitulo', 'titulo' => 'Hero: subtitulo', 'conteudo' => 'Estrutura tecnica para atendimento rapido em Goiania, Brasilia e regiao, com foco em performance, confiabilidade e suporte continuo.', 'tipo' => 'textarea', 'ativo' => 1],
        ['chave' => 'hero_btn_contato', 'titulo' => 'Hero: botao contato', 'conteudo' => 'Entre em contato', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'hero_btn_whatsapp', 'titulo' => 'Hero: botao WhatsApp', 'conteudo' => 'Falar no WhatsApp', 'tipo' => 'input', 'ativo' => 1],

        ['chave' => 'bloco_venda_titulo', 'titulo' => 'Bloco venda: titulo', 'conteudo' => 'Venda', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'bloco_venda_texto', 'titulo' => 'Bloco venda: texto', 'conteudo' => 'Trabalhamos com datadores inkjet para ampliar produtividade e reduzir custos operacionais, com orientacao tecnica na escolha do equipamento ideal.', 'tipo' => 'textarea', 'ativo' => 1],
        ['chave' => 'bloco_venda_btn', 'titulo' => 'Bloco venda: botao', 'conteudo' => 'Mais informacoes', 'tipo' => 'input', 'ativo' => 1],

        ['chave' => 'bloco_assistencia_titulo', 'titulo' => 'Bloco assistencia: titulo', 'conteudo' => 'Assistencia Tecnica', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'bloco_assistencia_texto', 'titulo' => 'Bloco assistencia: texto', 'conteudo' => 'Cobertura tecnica com prioridade para chamados emergenciais e manutencao corretiva/preventiva em toda a regiao de atendimento da Printer Goiania.', 'tipo' => 'textarea', 'ativo' => 1],
        ['chave' => 'bloco_assistencia_btn', 'titulo' => 'Bloco assistencia: botao', 'conteudo' => 'Mais informacoes', 'tipo' => 'input', 'ativo' => 1],

        ['chave' => 'produtos_titulo', 'titulo' => 'Produtos: titulo', 'conteudo' => 'Produtos a Venda', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'produto_1_texto', 'titulo' => 'Produto 1: texto', 'conteudo' => 'Datador Inkjet industrial com instalacao rapida e excelente qualidade de impressao.', 'tipo' => 'textarea', 'ativo' => 1],
        ['chave' => 'produto_2_texto', 'titulo' => 'Produto 2: texto', 'conteudo' => 'Esteiras e acessorios para fluxo continuo de codificacao em linha de producao.', 'tipo' => 'textarea', 'ativo' => 1],
        ['chave' => 'produto_3_texto', 'titulo' => 'Produto 3: texto', 'conteudo' => 'Rotuladoras semiautomaticas para rotulagem e codificacao com alta produtividade.', 'tipo' => 'textarea', 'ativo' => 1],
        ['chave' => 'produto_4_texto', 'titulo' => 'Produto 4: texto', 'conteudo' => 'Rebobinadoras para preparar rotulos datados com agilidade e padrao de qualidade.', 'tipo' => 'textarea', 'ativo' => 1],

        ['chave' => 'promocionais_titulo', 'titulo' => 'Promocionais: titulo', 'conteudo' => 'Imagens Promocionais do Trabalho', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'promocionais_subtitulo', 'titulo' => 'Promocionais: subtitulo', 'conteudo' => 'Registros reais de instalacoes, atendimentos e operacoes em clientes atendidos pela Printer Goiania.', 'tipo' => 'textarea', 'ativo' => 1],
        ['chave' => 'promocional_1_legenda', 'titulo' => 'Promocional 1: legenda', 'conteudo' => 'Aplicacao em linha de producao.', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'promocional_2_legenda', 'titulo' => 'Promocional 2: legenda', 'conteudo' => 'Codificacao com alta nitidez.', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'promocional_3_legenda', 'titulo' => 'Promocional 3: legenda', 'conteudo' => 'Equipe tecnica especializada.', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'promocional_4_legenda', 'titulo' => 'Promocional 4: legenda', 'conteudo' => 'Instalacao e suporte continuo.', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'promocional_5_legenda', 'titulo' => 'Promocional 5: legenda', 'conteudo' => 'Projetos para diversas industrias.', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'promocional_6_legenda', 'titulo' => 'Promocional 6: legenda', 'conteudo' => 'Resultados reais no dia a dia.', 'tipo' => 'input', 'ativo' => 1],

        ['chave' => 'depoimentos_titulo', 'titulo' => 'Depoimentos: titulo', 'conteudo' => 'Depoimentos', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'depoimento_1_nome', 'titulo' => 'Depoimento 1: nome', 'conteudo' => 'Cliente Industrial', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'depoimento_1_texto', 'titulo' => 'Depoimento 1: texto', 'conteudo' => 'Atendimento tecnico agil, suporte claro e excelente desempenho dos datadores na operacao diaria.', 'tipo' => 'textarea', 'ativo' => 1],
        ['chave' => 'depoimento_2_nome', 'titulo' => 'Depoimento 2: nome', 'conteudo' => 'Gestor de Producao', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'depoimento_2_texto', 'titulo' => 'Depoimento 2: texto', 'conteudo' => 'Conseguimos reduzir paradas na linha com manutencao preventiva e suporte rapido da equipe Printer Goiania.', 'tipo' => 'textarea', 'ativo' => 1],
        ['chave' => 'depoimento_3_nome', 'titulo' => 'Depoimento 3: nome', 'conteudo' => 'Coordenador Tecnico', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'depoimento_3_texto', 'titulo' => 'Depoimento 3: texto', 'conteudo' => 'Equipe confiavel e consultiva. Resolveram nossa demanda de codificacao com otima relacao custo-beneficio.', 'tipo' => 'textarea', 'ativo' => 1],

        ['chave' => 'vantagem_1_titulo', 'titulo' => 'Vantagem 1: titulo', 'conteudo' => 'Rapida distribuicao', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'vantagem_1_texto', 'titulo' => 'Vantagem 1: texto', 'conteudo' => 'Infraestrutura para entrega e instalacao dentro de prazos curtos.', 'tipo' => 'textarea', 'ativo' => 1],
        ['chave' => 'vantagem_2_titulo', 'titulo' => 'Vantagem 2: titulo', 'conteudo' => 'Confiabilidade', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'vantagem_2_texto', 'titulo' => 'Vantagem 2: texto', 'conteudo' => 'Equipamentos revisados e suporte continuo para operacao estavel.', 'tipo' => 'textarea', 'ativo' => 1],
        ['chave' => 'vantagem_3_titulo', 'titulo' => 'Vantagem 3: titulo', 'conteudo' => 'Custo-beneficio', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'vantagem_3_texto', 'titulo' => 'Vantagem 3: texto', 'conteudo' => 'Solucoes tecnicas com excelente retorno para operacao industrial.', 'tipo' => 'textarea', 'ativo' => 1],
        ['chave' => 'vantagem_4_titulo', 'titulo' => 'Vantagem 4: titulo', 'conteudo' => 'Assistencia especializada', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'vantagem_4_texto', 'titulo' => 'Vantagem 4: texto', 'conteudo' => 'Equipe credenciada para manutencao corretiva e preventiva.', 'tipo' => 'textarea', 'ativo' => 1],

        ['chave' => 'sobre_titulo', 'titulo' => 'Sobre: titulo', 'conteudo' => 'Sobre a Printer Goiania', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'sobre_texto', 'titulo' => 'Sobre: texto', 'conteudo' => 'A Printer Goiania atua com venda e assistencia tecnica de datadores inkjet para industrias e empresas que precisam de codificacao eficiente em embalagens e rotulos. Nossa equipe oferece suporte consultivo e atendimento tecnico com agilidade.', 'tipo' => 'textarea', 'ativo' => 1],
        ['chave' => 'sobre_card_1', 'titulo' => 'Sobre: card 1', 'conteudo' => 'Garantia de qualidade', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'sobre_card_2', 'titulo' => 'Sobre: card 2', 'conteudo' => '100% de satisfacao', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'sobre_card_3', 'titulo' => 'Sobre: card 3', 'conteudo' => 'Assistencia personalizada', 'tipo' => 'input', 'ativo' => 1],

        ['chave' => 'orcamento_exibir', 'titulo' => 'Orcamento: exibir secao?', 'conteudo' => '1', 'tipo' => 'boolean', 'ativo' => 1],
        ['chave' => 'orcamento_titulo', 'titulo' => 'Orcamento: titulo', 'conteudo' => 'Solicite um orcamento', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'orcamento_texto', 'titulo' => 'Orcamento: texto', 'conteudo' => 'Preencha os dados e envie sua solicitacao para nossa equipe comercial.', 'tipo' => 'textarea', 'ativo' => 1],
        ['chave' => 'orcamento_campo_nome', 'titulo' => 'Orcamento: campo nome', 'conteudo' => 'Nome', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'orcamento_campo_email', 'titulo' => 'Orcamento: campo e-mail', 'conteudo' => 'E-mail', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'orcamento_campo_telefone', 'titulo' => 'Orcamento: campo telefone', 'conteudo' => 'Telefone', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'orcamento_campo_mensagem', 'titulo' => 'Orcamento: campo mensagem', 'conteudo' => 'Mensagem', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'orcamento_btn', 'titulo' => 'Orcamento: botao', 'conteudo' => 'Enviar orcamento', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'orcamento_nota', 'titulo' => 'Orcamento: nota', 'conteudo' => 'Ao enviar, abriremos seu WhatsApp com a mensagem pronta.', 'tipo' => 'textarea', 'ativo' => 1],

        ['chave' => 'segmentos_titulo', 'titulo' => 'Segmentos: titulo', 'conteudo' => 'Segmentos de Aplicacao', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'segmentos_texto', 'titulo' => 'Segmentos: texto', 'conteudo' => 'O datador inkjet pode ser utilizado em cosmeticos, alimentos, farmaceutico, quimica, logistica, bebidas e diversas linhas de embalagem.', 'tipo' => 'textarea', 'ativo' => 1],
        ['chave' => 'segmento_1', 'titulo' => 'Segmento 1', 'conteudo' => 'Alimentos', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'segmento_2', 'titulo' => 'Segmento 2', 'conteudo' => 'Bebidas', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'segmento_3', 'titulo' => 'Segmento 3', 'conteudo' => 'Farmaceutico', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'segmento_4', 'titulo' => 'Segmento 4', 'conteudo' => 'Cosmeticos', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'segmento_5', 'titulo' => 'Segmento 5', 'conteudo' => 'Quimico', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'segmento_6', 'titulo' => 'Segmento 6', 'conteudo' => 'Logistica', 'tipo' => 'input', 'ativo' => 1],

        ['chave' => 'footer_titulo', 'titulo' => 'Rodape: titulo', 'conteudo' => 'Printer Goiania', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'footer_endereco', 'titulo' => 'Rodape: endereco', 'conteudo' => 'R. Jequitiba, 543 - Jardim Mariliza, Goiania - GO', 'tipo' => 'textarea', 'ativo' => 1],
        ['chave' => 'footer_telefone', 'titulo' => 'Rodape: telefone', 'conteudo' => '+55 (62) 9 9999-9999', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'footer_email', 'titulo' => 'Rodape: e-mail', 'conteudo' => 'contato@printergoiania.com.br', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'footer_menu_titulo', 'titulo' => 'Rodape: titulo menu', 'conteudo' => 'Acesso Rapido', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'footer_news_titulo', 'titulo' => 'Rodape: titulo newsletter', 'conteudo' => 'Newsletter', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'footer_news_texto', 'titulo' => 'Rodape: texto newsletter', 'conteudo' => 'Receba novidades e conteudos tecnicos por e-mail.', 'tipo' => 'textarea', 'ativo' => 1],
        ['chave' => 'footer_news_placeholder', 'titulo' => 'Rodape: placeholder newsletter', 'conteudo' => 'Seu e-mail', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'footer_news_btn', 'titulo' => 'Rodape: botao newsletter', 'conteudo' => 'Enviar', 'tipo' => 'input', 'ativo' => 1],
        ['chave' => 'footer_copyright', 'titulo' => 'Rodape: copyright', 'conteudo' => 'Copyright (c) 2026 Printer Goiania. Todos os direitos reservados.', 'tipo' => 'input', 'ativo' => 1],
    ];
}

function sincronizarConfiguracoesSite(): void
{
    static $executado = false;

    if ($executado) {
        return;
    }

    $executado = true;

    try {
        $pdo = getPDOConnectionOrNull();
        if (!$pdo instanceof PDO) {
            return;
        }

        $sqlImagem = 'INSERT INTO site_imagens (chave, titulo, descricao, caminho, alt_text, largura_recomendada, altura_recomendada, tamanho_max_mb, ativo) VALUES (:chave, :titulo, :descricao, :caminho, :alt_text, :largura_recomendada, :altura_recomendada, :tamanho_max_mb, :ativo) ON DUPLICATE KEY UPDATE titulo = VALUES(titulo), descricao = VALUES(descricao), largura_recomendada = VALUES(largura_recomendada), altura_recomendada = VALUES(altura_recomendada), tamanho_max_mb = VALUES(tamanho_max_mb), ativo = VALUES(ativo)';
        $stmtImagem = $pdo->prepare($sqlImagem);

        foreach (getDefaultSiteImages() as $img) {
            $stmtImagem->execute([
                ':chave' => $img['chave'],
                ':titulo' => $img['titulo'],
                ':descricao' => $img['descricao'],
                ':caminho' => $img['caminho'],
                ':alt_text' => $img['alt_text'],
                ':largura_recomendada' => $img['largura_recomendada'],
                ':altura_recomendada' => $img['altura_recomendada'],
                ':tamanho_max_mb' => $img['tamanho_max_mb'],
                ':ativo' => $img['ativo'],
            ]);
        }

        $sqlTexto = 'INSERT INTO site_textos (chave, titulo, conteudo, tipo, ativo) VALUES (:chave, :titulo, :conteudo, :tipo, :ativo) ON DUPLICATE KEY UPDATE titulo = VALUES(titulo), tipo = VALUES(tipo), ativo = VALUES(ativo)';
        $stmtTexto = $pdo->prepare($sqlTexto);

        foreach (getDefaultSiteTexts() as $txt) {
            $stmtTexto->execute([
                ':chave' => $txt['chave'],
                ':titulo' => $txt['titulo'],
                ':conteudo' => $txt['conteudo'],
                ':tipo' => $txt['tipo'],
                ':ativo' => $txt['ativo'],
            ]);
        }
    } catch (Throwable $e) {
        // Nao interrompe a landing se o banco estiver indisponivel.
    }
}

/**
 * @return array<string, mixed>|null
 */
function getImagemRegistro(string $chave): ?array
{
    static $cache = [];

    if (array_key_exists($chave, $cache)) {
        return $cache[$chave];
    }

    try {
        $pdo = getPDOConnectionOrNull();
        if (!$pdo instanceof PDO) {
            $cache[$chave] = null;
            return null;
        }
        $stmt = $pdo->prepare('SELECT * FROM site_imagens WHERE chave = :chave AND ativo = 1 LIMIT 1');
        $stmt->execute([':chave' => $chave]);
        $registro = $stmt->fetch();

        if (!is_array($registro)) {
            $cache[$chave] = null;
            return null;
        }

        $cache[$chave] = $registro;
        return $registro;
    } catch (Throwable $e) {
        $cache[$chave] = null;
        return null;
    }
}

function getImagem(string $chave, string $fallback): string
{
    $registro = getImagemRegistro($chave);
    if (!is_array($registro)) {
        return $fallback;
    }

    $caminho = trim((string) ($registro['caminho'] ?? ''));
    return $caminho === '' ? $fallback : $caminho;
}

function getAltImagem(string $chave, string $fallback): string
{
    $registro = getImagemRegistro($chave);
    if (!is_array($registro)) {
        return $fallback;
    }

    $alt = trim((string) ($registro['alt_text'] ?? ''));
    return $alt === '' ? $fallback : $alt;
}

function getTextoSite(string $chave, string $fallback): string
{
    static $cache = [];

    if (array_key_exists($chave, $cache)) {
        return $cache[$chave] === '' ? $fallback : $cache[$chave];
    }

    try {
        $pdo = getPDOConnectionOrNull();
        if (!$pdo instanceof PDO) {
            $cache[$chave] = '';
            return $fallback;
        }
        $stmt = $pdo->prepare('SELECT conteudo FROM site_textos WHERE chave = :chave AND ativo = 1 LIMIT 1');
        $stmt->execute([':chave' => $chave]);
        $registro = $stmt->fetch();

        if (!is_array($registro)) {
            $cache[$chave] = '';
            return $fallback;
        }

        $conteudo = trim((string) ($registro['conteudo'] ?? ''));
        $cache[$chave] = $conteudo;
        return $conteudo === '' ? $fallback : $conteudo;
    } catch (Throwable $e) {
        $cache[$chave] = '';
        return $fallback;
    }
}

function getTextoBoolean(string $chave, bool $fallback = false): bool
{
    $fallbackText = $fallback ? '1' : '0';
    $valor = strtolower(trim(getTextoSite($chave, $fallbackText)));

    return in_array($valor, ['1', 'true', 'sim', 'yes', 'on'], true);
}

/**
 * @return array{secao:string,bloco:string,onde:string}
 */
function getAdminTextMeta(string $chave): array
{
    $mapa = [
        'site_titulo' => ['secao' => 'Configuracoes Gerais', 'bloco' => 'Titulo da pagina', 'onde' => 'Aba do navegador e SEO basico'],
        'site_descricao' => ['secao' => 'Configuracoes Gerais', 'bloco' => 'Descricao da pagina', 'onde' => 'Descricao de busca (SEO)'],

        'contato_telefone' => ['secao' => 'Topo e Contato', 'bloco' => 'Telefone principal', 'onde' => 'Barra superior e contato'],
        'contato_whatsapp_numero' => ['secao' => 'Topo e Contato', 'bloco' => 'Numero do WhatsApp', 'onde' => 'Links dos botoes de WhatsApp'],
        'contato_whatsapp_texto' => ['secao' => 'Topo e Contato', 'bloco' => 'Mensagem padrao WhatsApp', 'onde' => 'Mensagem automatica enviada ao abrir WhatsApp'],
        'topbar_whatsapp_label' => ['secao' => 'Topo e Contato', 'bloco' => 'Rotulo do WhatsApp', 'onde' => 'Barra superior'],

        'social_facebook_url' => ['secao' => 'Topo e Redes Sociais', 'bloco' => 'Link Facebook', 'onde' => 'Icones sociais do topo e rodape'],
        'social_instagram_url' => ['secao' => 'Topo e Redes Sociais', 'bloco' => 'Link Instagram', 'onde' => 'Icones sociais do topo e rodape'],
        'social_youtube_url' => ['secao' => 'Topo e Redes Sociais', 'bloco' => 'Link YouTube', 'onde' => 'Icones sociais do topo e rodape'],

        'menu_venda' => ['secao' => 'Menu do Site', 'bloco' => 'Menu Venda', 'onde' => 'Menu principal e rodape'],
        'menu_assistencia' => ['secao' => 'Menu do Site', 'bloco' => 'Menu Assistencia Tecnica', 'onde' => 'Menu principal e rodape'],
        'menu_promocionais' => ['secao' => 'Menu do Site', 'bloco' => 'Menu Promocoes', 'onde' => 'Menu principal e rodape'],
        'menu_vantagens' => ['secao' => 'Menu do Site', 'bloco' => 'Menu Vantagens', 'onde' => 'Menu principal e rodape'],
        'menu_depoimentos' => ['secao' => 'Menu do Site', 'bloco' => 'Menu Depoimentos', 'onde' => 'Menu principal e rodape'],
        'menu_orcamento' => ['secao' => 'Menu do Site', 'bloco' => 'Menu Orcamento', 'onde' => 'Menu principal e rodape'],
        'menu_contato' => ['secao' => 'Menu do Site', 'bloco' => 'Menu Contato', 'onde' => 'Menu principal e rodape'],

        'hero_badge' => ['secao' => 'Carrossel Principal', 'bloco' => 'Selo superior', 'onde' => 'Topo do banner principal'],
        'hero_titulo' => ['secao' => 'Carrossel Principal', 'bloco' => 'Titulo principal', 'onde' => 'Texto grande no banner principal'],
        'hero_subtitulo' => ['secao' => 'Carrossel Principal', 'bloco' => 'Subtitulo principal', 'onde' => 'Texto complementar no banner principal'],
        'hero_btn_contato' => ['secao' => 'Carrossel Principal', 'bloco' => 'Botao Contato', 'onde' => 'Botao de acao no banner'],
        'hero_btn_whatsapp' => ['secao' => 'Carrossel Principal', 'bloco' => 'Botao WhatsApp', 'onde' => 'Botao de acao no banner'],

        'bloco_venda_titulo' => ['secao' => 'Cards de Destaque', 'bloco' => 'Card Venda - titulo', 'onde' => 'Card logo abaixo do banner'],
        'bloco_venda_texto' => ['secao' => 'Cards de Destaque', 'bloco' => 'Card Venda - texto', 'onde' => 'Card logo abaixo do banner'],
        'bloco_venda_btn' => ['secao' => 'Cards de Destaque', 'bloco' => 'Card Venda - botao', 'onde' => 'Card logo abaixo do banner'],
        'bloco_assistencia_titulo' => ['secao' => 'Cards de Destaque', 'bloco' => 'Card Assistencia - titulo', 'onde' => 'Card logo abaixo do banner'],
        'bloco_assistencia_texto' => ['secao' => 'Cards de Destaque', 'bloco' => 'Card Assistencia - texto', 'onde' => 'Card logo abaixo do banner'],
        'bloco_assistencia_btn' => ['secao' => 'Cards de Destaque', 'bloco' => 'Card Assistencia - botao', 'onde' => 'Card logo abaixo do banner'],

        'produtos_titulo' => ['secao' => 'Secao Produtos', 'bloco' => 'Titulo da secao', 'onde' => 'Cabecalho da secao Produtos'],
        'produto_1_texto' => ['secao' => 'Secao Produtos', 'bloco' => 'Produto 1 - descricao', 'onde' => 'Card de produto 1'],
        'produto_2_texto' => ['secao' => 'Secao Produtos', 'bloco' => 'Produto 2 - descricao', 'onde' => 'Card de produto 2'],
        'produto_3_texto' => ['secao' => 'Secao Produtos', 'bloco' => 'Produto 3 - descricao', 'onde' => 'Card de produto 3'],
        'produto_4_texto' => ['secao' => 'Secao Produtos', 'bloco' => 'Produto 4 - descricao', 'onde' => 'Card de produto 4'],

        'promocionais_titulo' => ['secao' => 'Secao Promocionais', 'bloco' => 'Titulo da secao', 'onde' => 'Cabecalho da galeria promocional'],
        'promocionais_subtitulo' => ['secao' => 'Secao Promocionais', 'bloco' => 'Subtitulo da secao', 'onde' => 'Texto abaixo do titulo da galeria'],
        'promocional_1_legenda' => ['secao' => 'Secao Promocionais', 'bloco' => 'Legenda promocional 1', 'onde' => 'Legenda da imagem promocional 1'],
        'promocional_2_legenda' => ['secao' => 'Secao Promocionais', 'bloco' => 'Legenda promocional 2', 'onde' => 'Legenda da imagem promocional 2'],
        'promocional_3_legenda' => ['secao' => 'Secao Promocionais', 'bloco' => 'Legenda promocional 3', 'onde' => 'Legenda da imagem promocional 3'],
        'promocional_4_legenda' => ['secao' => 'Secao Promocionais', 'bloco' => 'Legenda promocional 4', 'onde' => 'Legenda da imagem promocional 4'],
        'promocional_5_legenda' => ['secao' => 'Secao Promocionais', 'bloco' => 'Legenda promocional 5', 'onde' => 'Legenda da imagem promocional 5'],
        'promocional_6_legenda' => ['secao' => 'Secao Promocionais', 'bloco' => 'Legenda promocional 6', 'onde' => 'Legenda da imagem promocional 6'],

        'depoimentos_titulo' => ['secao' => 'Secao Depoimentos', 'bloco' => 'Titulo da secao', 'onde' => 'Cabecalho da secao Depoimentos'],
        'depoimento_1_nome' => ['secao' => 'Secao Depoimentos', 'bloco' => 'Depoimento 1 - nome', 'onde' => 'Card de depoimento 1'],
        'depoimento_1_texto' => ['secao' => 'Secao Depoimentos', 'bloco' => 'Depoimento 1 - texto', 'onde' => 'Card de depoimento 1'],
        'depoimento_2_nome' => ['secao' => 'Secao Depoimentos', 'bloco' => 'Depoimento 2 - nome', 'onde' => 'Card de depoimento 2'],
        'depoimento_2_texto' => ['secao' => 'Secao Depoimentos', 'bloco' => 'Depoimento 2 - texto', 'onde' => 'Card de depoimento 2'],
        'depoimento_3_nome' => ['secao' => 'Secao Depoimentos', 'bloco' => 'Depoimento 3 - nome', 'onde' => 'Card de depoimento 3'],
        'depoimento_3_texto' => ['secao' => 'Secao Depoimentos', 'bloco' => 'Depoimento 3 - texto', 'onde' => 'Card de depoimento 3'],

        'vantagem_1_titulo' => ['secao' => 'Secao Vantagens', 'bloco' => 'Vantagem 1 - titulo', 'onde' => 'Coluna esquerda da secao Vantagens'],
        'vantagem_1_texto' => ['secao' => 'Secao Vantagens', 'bloco' => 'Vantagem 1 - texto', 'onde' => 'Coluna esquerda da secao Vantagens'],
        'vantagem_2_titulo' => ['secao' => 'Secao Vantagens', 'bloco' => 'Vantagem 2 - titulo', 'onde' => 'Coluna esquerda da secao Vantagens'],
        'vantagem_2_texto' => ['secao' => 'Secao Vantagens', 'bloco' => 'Vantagem 2 - texto', 'onde' => 'Coluna esquerda da secao Vantagens'],
        'vantagem_3_titulo' => ['secao' => 'Secao Vantagens', 'bloco' => 'Vantagem 3 - titulo', 'onde' => 'Coluna direita da secao Vantagens'],
        'vantagem_3_texto' => ['secao' => 'Secao Vantagens', 'bloco' => 'Vantagem 3 - texto', 'onde' => 'Coluna direita da secao Vantagens'],
        'vantagem_4_titulo' => ['secao' => 'Secao Vantagens', 'bloco' => 'Vantagem 4 - titulo', 'onde' => 'Coluna direita da secao Vantagens'],
        'vantagem_4_texto' => ['secao' => 'Secao Vantagens', 'bloco' => 'Vantagem 4 - texto', 'onde' => 'Coluna direita da secao Vantagens'],

        'sobre_titulo' => ['secao' => 'Secao Sobre', 'bloco' => 'Titulo da secao', 'onde' => 'Bloco sobre a empresa'],
        'sobre_texto' => ['secao' => 'Secao Sobre', 'bloco' => 'Texto da secao', 'onde' => 'Bloco sobre a empresa'],
        'sobre_card_1' => ['secao' => 'Secao Sobre', 'bloco' => 'Card Sobre 1', 'onde' => 'Cards de apoio na secao Sobre'],
        'sobre_card_2' => ['secao' => 'Secao Sobre', 'bloco' => 'Card Sobre 2', 'onde' => 'Cards de apoio na secao Sobre'],
        'sobre_card_3' => ['secao' => 'Secao Sobre', 'bloco' => 'Card Sobre 3', 'onde' => 'Cards de apoio na secao Sobre'],

        'orcamento_exibir' => ['secao' => 'Secao Orcamento', 'bloco' => 'Exibir secao', 'onde' => 'Liga/desliga o bloco de formulario'],
        'orcamento_titulo' => ['secao' => 'Secao Orcamento', 'bloco' => 'Titulo da secao', 'onde' => 'Cabecalho do formulario de orcamento'],
        'orcamento_texto' => ['secao' => 'Secao Orcamento', 'bloco' => 'Texto da secao', 'onde' => 'Subtitulo do formulario de orcamento'],
        'orcamento_campo_nome' => ['secao' => 'Secao Orcamento', 'bloco' => 'Campo Nome', 'onde' => 'Placeholder do formulario de orcamento'],
        'orcamento_campo_email' => ['secao' => 'Secao Orcamento', 'bloco' => 'Campo E-mail', 'onde' => 'Placeholder do formulario de orcamento'],
        'orcamento_campo_telefone' => ['secao' => 'Secao Orcamento', 'bloco' => 'Campo Telefone', 'onde' => 'Placeholder do formulario de orcamento'],
        'orcamento_campo_mensagem' => ['secao' => 'Secao Orcamento', 'bloco' => 'Campo Mensagem', 'onde' => 'Placeholder do formulario de orcamento'],
        'orcamento_btn' => ['secao' => 'Secao Orcamento', 'bloco' => 'Botao enviar', 'onde' => 'Botao do formulario de orcamento'],
        'orcamento_nota' => ['secao' => 'Secao Orcamento', 'bloco' => 'Texto de orientacao', 'onde' => 'Rodape do formulario de orcamento'],

        'segmentos_titulo' => ['secao' => 'Secao Segmentos', 'bloco' => 'Titulo da secao', 'onde' => 'Cabecalho da secao Segmentos'],
        'segmentos_texto' => ['secao' => 'Secao Segmentos', 'bloco' => 'Texto da secao', 'onde' => 'Texto explicativo da secao Segmentos'],
        'segmento_1' => ['secao' => 'Secao Segmentos', 'bloco' => 'Segmento 1', 'onde' => 'Card da grade de segmentos'],
        'segmento_2' => ['secao' => 'Secao Segmentos', 'bloco' => 'Segmento 2', 'onde' => 'Card da grade de segmentos'],
        'segmento_3' => ['secao' => 'Secao Segmentos', 'bloco' => 'Segmento 3', 'onde' => 'Card da grade de segmentos'],
        'segmento_4' => ['secao' => 'Secao Segmentos', 'bloco' => 'Segmento 4', 'onde' => 'Card da grade de segmentos'],
        'segmento_5' => ['secao' => 'Secao Segmentos', 'bloco' => 'Segmento 5', 'onde' => 'Card da grade de segmentos'],
        'segmento_6' => ['secao' => 'Secao Segmentos', 'bloco' => 'Segmento 6', 'onde' => 'Card da grade de segmentos'],

        'footer_titulo' => ['secao' => 'Rodape', 'bloco' => 'Titulo principal', 'onde' => 'Coluna principal do rodape'],
        'footer_endereco' => ['secao' => 'Rodape', 'bloco' => 'Endereco', 'onde' => 'Coluna principal do rodape'],
        'footer_telefone' => ['secao' => 'Rodape', 'bloco' => 'Telefone', 'onde' => 'Coluna principal do rodape'],
        'footer_email' => ['secao' => 'Rodape', 'bloco' => 'E-mail', 'onde' => 'Coluna principal do rodape'],
        'footer_menu_titulo' => ['secao' => 'Rodape', 'bloco' => 'Titulo menu rapido', 'onde' => 'Coluna de links do rodape'],
        'footer_news_titulo' => ['secao' => 'Rodape', 'bloco' => 'Titulo newsletter', 'onde' => 'Coluna newsletter do rodape'],
        'footer_news_texto' => ['secao' => 'Rodape', 'bloco' => 'Texto newsletter', 'onde' => 'Coluna newsletter do rodape'],
        'footer_news_placeholder' => ['secao' => 'Rodape', 'bloco' => 'Placeholder newsletter', 'onde' => 'Campo de e-mail no rodape'],
        'footer_news_btn' => ['secao' => 'Rodape', 'bloco' => 'Botao newsletter', 'onde' => 'Botao de envio no rodape'],
        'footer_copyright' => ['secao' => 'Rodape', 'bloco' => 'Copyright', 'onde' => 'Faixa final do rodape'],
    ];

    return $mapa[$chave] ?? [
        'secao' => 'Outros Campos',
        'bloco' => $chave,
        'onde' => 'Campo adicional da landing page',
    ];
}

/**
 * @return array{secao:string,onde:string}
 */
function getAdminImageMeta(string $chave): array
{
    $mapa = [
        'logo_site' => ['secao' => 'Cabecalho e Rodape', 'onde' => 'Logo principal do topo e do final da pagina'],
        'banner_principal' => ['secao' => 'Carrossel Principal', 'onde' => 'Imagem fallback do primeiro slide'],
        'carousel_1' => ['secao' => 'Carrossel Principal', 'onde' => 'Slide 1 do carrossel'],
        'carousel_2' => ['secao' => 'Carrossel Principal', 'onde' => 'Slide 2 do carrossel'],
        'carousel_3' => ['secao' => 'Carrossel Principal', 'onde' => 'Slide 3 do carrossel'],
        'carousel_4' => ['secao' => 'Carrossel Principal', 'onde' => 'Slide 4 do carrossel'],
        'imagem_sobre' => ['secao' => 'Secao Vantagens/Sobre', 'onde' => 'Imagem central da secao institucional'],
        'servico_1' => ['secao' => 'Secao Produtos', 'onde' => 'Imagem do produto 1'],
        'servico_2' => ['secao' => 'Secao Produtos', 'onde' => 'Imagem do produto 2'],
        'servico_3' => ['secao' => 'Secao Produtos', 'onde' => 'Imagem do produto 3'],
        'servico_4' => ['secao' => 'Secao Produtos', 'onde' => 'Imagem do produto 4'],
        'promocional_1' => ['secao' => 'Secao Promocionais', 'onde' => 'Imagem promocional 1'],
        'promocional_2' => ['secao' => 'Secao Promocionais', 'onde' => 'Imagem promocional 2'],
        'promocional_3' => ['secao' => 'Secao Promocionais', 'onde' => 'Imagem promocional 3'],
        'promocional_4' => ['secao' => 'Secao Promocionais', 'onde' => 'Imagem promocional 4'],
        'promocional_5' => ['secao' => 'Secao Promocionais', 'onde' => 'Imagem promocional 5'],
        'promocional_6' => ['secao' => 'Secao Promocionais', 'onde' => 'Imagem promocional 6'],
    ];

    return $mapa[$chave] ?? [
        'secao' => 'Outras Imagens',
        'onde' => 'Imagem adicional da landing page',
    ];
}
