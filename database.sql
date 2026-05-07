-- -----------------------------------------------------
-- Banco de dados para painel administrativo da landing
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS usuarios_admin (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  senha_hash VARCHAR(255) NOT NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_usuarios_admin_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS site_imagens (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  chave VARCHAR(80) NOT NULL,
  titulo VARCHAR(150) NOT NULL,
  descricao VARCHAR(255) DEFAULT NULL,
  caminho VARCHAR(255) NOT NULL,
  alt_text VARCHAR(255) DEFAULT NULL,
  largura_recomendada INT UNSIGNED DEFAULT NULL,
  altura_recomendada INT UNSIGNED DEFAULT NULL,
  tamanho_max_mb DECIMAL(5,2) NOT NULL DEFAULT 2.00,
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_site_imagens_chave (chave)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS site_textos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  chave VARCHAR(80) NOT NULL,
  titulo VARCHAR(150) NOT NULL,
  conteudo TEXT NOT NULL,
  tipo VARCHAR(30) NOT NULL DEFAULT 'textarea',
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_site_textos_chave (chave)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS site_promocionais (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(150) NOT NULL,
  legenda VARCHAR(255) DEFAULT NULL,
  caminho VARCHAR(255) NOT NULL,
  alt_text VARCHAR(255) DEFAULT NULL,
  ordem INT UNSIGNED NOT NULL DEFAULT 0,
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO site_imagens
  (chave, titulo, descricao, caminho, alt_text, largura_recomendada, altura_recomendada, tamanho_max_mb, ativo)
VALUES
  ('logo_site', 'Logo do Site', 'Logo principal exibida no cabecalho e rodape.', 'images/logo.png', 'Logo da Printer Goiania', 600, 220, 1.00, 1),
  ('banner_principal', 'Banner Principal (Fallback)', 'Imagem de fallback para o topo da landing page.', 'images/banner/01-banner.jpg', 'Banner principal da empresa', 1920, 900, 2.00, 1),
  ('carousel_1', 'Carrossel 1', 'Slide 1 do carrossel principal.', 'images/banner/01-banner.jpg', 'Slide 1 do carrossel', 1920, 900, 2.00, 1),
  ('carousel_2', 'Carrossel 2', 'Slide 2 do carrossel principal.', 'images/banner/02-banner.jpg', 'Slide 2 do carrossel', 1920, 900, 2.00, 1),
  ('carousel_3', 'Carrossel 3', 'Slide 3 do carrossel principal.', 'images/banner/03-banner.jpg', 'Slide 3 do carrossel', 1920, 900, 2.00, 1),
  ('carousel_4', 'Carrossel 4', 'Slide 4 do carrossel principal.', 'images/banner/04-banner.jpg', 'Slide 4 do carrossel', 1920, 900, 2.00, 1),
  ('imagem_sobre', 'Imagem da Secao Foto', 'Imagem principal do bloco institucional/foto.', 'images/banner/02-banner.jpg', 'Imagem institucional da Printer Goiania', 1200, 800, 2.00, 1),
  ('servico_1', 'Imagem do Servico 1', 'Imagem exibida no card de produto 1.', 'images/banner/01-banner.jpg', 'Imagem do servico 1', 1200, 800, 2.00, 1),
  ('servico_2', 'Imagem do Servico 2', 'Imagem exibida no card de produto 2.', 'images/banner/03-banner.jpg', 'Imagem do servico 2', 1200, 800, 2.00, 1),
  ('servico_3', 'Imagem do Servico 3', 'Imagem exibida no card de produto 3.', 'images/banner/04-banner.jpg', 'Imagem do servico 3', 1200, 800, 2.00, 1),
  ('servico_4', 'Imagem do Servico 4', 'Imagem exibida no card de produto 4.', 'images/banner/02-banner.jpg', 'Imagem do servico 4', 1200, 800, 2.00, 1),
  ('promocional_1', 'Imagem Promocional 1', 'Imagem da secao promocional de trabalhos realizados.', 'images/banner/01-banner.jpg', 'Imagem promocional 1', 1400, 900, 2.00, 1),
  ('promocional_2', 'Imagem Promocional 2', 'Imagem da secao promocional de trabalhos realizados.', 'images/banner/02-banner.jpg', 'Imagem promocional 2', 1400, 900, 2.00, 1),
  ('promocional_3', 'Imagem Promocional 3', 'Imagem da secao promocional de trabalhos realizados.', 'images/banner/03-banner.jpg', 'Imagem promocional 3', 1400, 900, 2.00, 1),
  ('promocional_4', 'Imagem Promocional 4', 'Imagem da secao promocional de trabalhos realizados.', 'images/banner/04-banner.jpg', 'Imagem promocional 4', 1400, 900, 2.00, 1),
  ('promocional_5', 'Imagem Promocional 5', 'Imagem da secao promocional de trabalhos realizados.', 'images/banner/01-banner.jpg', 'Imagem promocional 5', 1400, 900, 2.00, 1),
  ('promocional_6', 'Imagem Promocional 6', 'Imagem da secao promocional de trabalhos realizados.', 'images/banner/02-banner.jpg', 'Imagem promocional 6', 1400, 900, 2.00, 1)
ON DUPLICATE KEY UPDATE
  titulo = VALUES(titulo),
  descricao = VALUES(descricao),
  caminho = VALUES(caminho),
  alt_text = VALUES(alt_text),
  largura_recomendada = VALUES(largura_recomendada),
  altura_recomendada = VALUES(altura_recomendada),
  tamanho_max_mb = VALUES(tamanho_max_mb),
  ativo = VALUES(ativo);

INSERT INTO site_textos (chave, titulo, conteudo, tipo, ativo)
VALUES
  ('site_titulo', 'Titulo da pagina', 'Printer Goiania - Venda e Assistencia de Datadores Inkjet', 'input', 1),
  ('site_descricao', 'Descricao da pagina', 'Printer Goiania: venda e assistencia tecnica de datadores inkjet com atendimento em Goiania, Brasilia e regiao.', 'textarea', 1),
  ('contato_telefone', 'Telefone principal', '(62) 9 9999-9999', 'input', 1),
  ('contato_whatsapp_numero', 'WhatsApp (somente numeros)', '5562999999999', 'input', 1),
  ('contato_whatsapp_texto', 'Mensagem padrao WhatsApp', 'Ola Printer Goiania, gostaria de mais informacoes.', 'textarea', 1),
  ('topbar_whatsapp_label', 'Texto topbar WhatsApp', 'WhatsApp Comercial', 'input', 1),
  ('social_facebook_url', 'URL Facebook', '#', 'input', 1),
  ('social_instagram_url', 'URL Instagram', '#', 'input', 1),
  ('social_youtube_url', 'URL YouTube', '#', 'input', 1),
  ('menu_venda', 'Menu: Venda', 'Venda', 'input', 1),
  ('menu_assistencia', 'Menu: Assistencia tecnica', 'Assistencia Tecnica', 'input', 1),
  ('menu_promocionais', 'Menu: Promocoes', 'Promocoes', 'input', 1),
  ('menu_vantagens', 'Menu: Vantagens', 'Vantagens', 'input', 1),
  ('menu_depoimentos', 'Menu: Depoimentos', 'Depoimentos', 'input', 1),
  ('menu_orcamento', 'Menu: Orcamento', 'Orcamento', 'input', 1),
  ('menu_contato', 'Menu: Contato', 'Contato', 'input', 1),
  ('hero_badge', 'Hero: badge', 'Datadores Inkjet para Industria', 'input', 1),
  ('hero_titulo', 'Hero: titulo', 'Venda e Assistencia de Datadores para elevar sua producao.', 'textarea', 1),
  ('hero_subtitulo', 'Hero: subtitulo', 'Estrutura tecnica para atendimento rapido em Goiania, Brasilia e regiao, com foco em performance, confiabilidade e suporte continuo.', 'textarea', 1),
  ('hero_btn_contato', 'Hero: botao contato', 'Entre em contato', 'input', 1),
  ('hero_btn_whatsapp', 'Hero: botao WhatsApp', 'Falar no WhatsApp', 'input', 1),
  ('bloco_venda_titulo', 'Bloco venda: titulo', 'Venda', 'input', 1),
  ('bloco_venda_texto', 'Bloco venda: texto', 'Trabalhamos com datadores inkjet para ampliar produtividade e reduzir custos operacionais, com orientacao tecnica na escolha do equipamento ideal.', 'textarea', 1),
  ('bloco_venda_btn', 'Bloco venda: botao', 'Mais informacoes', 'input', 1),
  ('bloco_assistencia_titulo', 'Bloco assistencia: titulo', 'Assistencia Tecnica', 'input', 1),
  ('bloco_assistencia_texto', 'Bloco assistencia: texto', 'Cobertura tecnica com prioridade para chamados emergenciais e manutencao corretiva/preventiva em toda a regiao de atendimento da Printer Goiania.', 'textarea', 1),
  ('bloco_assistencia_btn', 'Bloco assistencia: botao', 'Mais informacoes', 'input', 1),
  ('produtos_titulo', 'Produtos: titulo', 'Produtos a Venda', 'input', 1),
  ('produto_1_texto', 'Produto 1: texto', 'Datador Inkjet industrial com instalacao rapida e excelente qualidade de impressao.', 'textarea', 1),
  ('produto_2_texto', 'Produto 2: texto', 'Esteiras e acessorios para fluxo continuo de codificacao em linha de producao.', 'textarea', 1),
  ('produto_3_texto', 'Produto 3: texto', 'Rotuladoras semiautomaticas para rotulagem e codificacao com alta produtividade.', 'textarea', 1),
  ('produto_4_texto', 'Produto 4: texto', 'Rebobinadoras para preparar rotulos datados com agilidade e padrao de qualidade.', 'textarea', 1),
  ('promocionais_titulo', 'Promocionais: titulo', 'Imagens Promocionais do Trabalho', 'input', 1),
  ('promocionais_subtitulo', 'Promocionais: subtitulo', 'Registros reais de instalacoes, atendimentos e operacoes em clientes atendidos pela Printer Goiania.', 'textarea', 1),
  ('promocional_1_legenda', 'Promocional 1: legenda', 'Aplicacao em linha de producao.', 'input', 1),
  ('promocional_2_legenda', 'Promocional 2: legenda', 'Codificacao com alta nitidez.', 'input', 1),
  ('promocional_3_legenda', 'Promocional 3: legenda', 'Equipe tecnica especializada.', 'input', 1),
  ('promocional_4_legenda', 'Promocional 4: legenda', 'Instalacao e suporte continuo.', 'input', 1),
  ('promocional_5_legenda', 'Promocional 5: legenda', 'Projetos para diversas industrias.', 'input', 1),
  ('promocional_6_legenda', 'Promocional 6: legenda', 'Resultados reais no dia a dia.', 'input', 1),
  ('depoimentos_titulo', 'Depoimentos: titulo', 'Depoimentos', 'input', 1),
  ('depoimento_1_nome', 'Depoimento 1: nome', 'Cliente Industrial', 'input', 1),
  ('depoimento_1_texto', 'Depoimento 1: texto', 'Atendimento tecnico agil, suporte claro e excelente desempenho dos datadores na operacao diaria.', 'textarea', 1),
  ('depoimento_2_nome', 'Depoimento 2: nome', 'Gestor de Producao', 'input', 1),
  ('depoimento_2_texto', 'Depoimento 2: texto', 'Conseguimos reduzir paradas na linha com manutencao preventiva e suporte rapido da equipe Printer Goiania.', 'textarea', 1),
  ('depoimento_3_nome', 'Depoimento 3: nome', 'Coordenador Tecnico', 'input', 1),
  ('depoimento_3_texto', 'Depoimento 3: texto', 'Equipe confiavel e consultiva. Resolveram nossa demanda de codificacao com otima relacao custo-beneficio.', 'textarea', 1),
  ('vantagem_1_titulo', 'Vantagem 1: titulo', 'Rapida distribuicao', 'input', 1),
  ('vantagem_1_texto', 'Vantagem 1: texto', 'Infraestrutura para entrega e instalacao dentro de prazos curtos.', 'textarea', 1),
  ('vantagem_2_titulo', 'Vantagem 2: titulo', 'Confiabilidade', 'input', 1),
  ('vantagem_2_texto', 'Vantagem 2: texto', 'Equipamentos revisados e suporte continuo para operacao estavel.', 'textarea', 1),
  ('vantagem_3_titulo', 'Vantagem 3: titulo', 'Custo-beneficio', 'input', 1),
  ('vantagem_3_texto', 'Vantagem 3: texto', 'Solucoes tecnicas com excelente retorno para operacao industrial.', 'textarea', 1),
  ('vantagem_4_titulo', 'Vantagem 4: titulo', 'Assistencia especializada', 'input', 1),
  ('vantagem_4_texto', 'Vantagem 4: texto', 'Equipe credenciada para manutencao corretiva e preventiva.', 'textarea', 1),
  ('sobre_titulo', 'Sobre: titulo', 'Sobre a Printer Goiania', 'input', 1),
  ('sobre_texto', 'Sobre: texto', 'A Printer Goiania atua com venda e assistencia tecnica de datadores inkjet para industrias e empresas que precisam de codificacao eficiente em embalagens e rotulos. Nossa equipe oferece suporte consultivo e atendimento tecnico com agilidade.', 'textarea', 1),
  ('sobre_card_1', 'Sobre: card 1', 'Garantia de qualidade', 'input', 1),
  ('sobre_card_2', 'Sobre: card 2', '100% de satisfacao', 'input', 1),
  ('sobre_card_3', 'Sobre: card 3', 'Assistencia personalizada', 'input', 1),
  ('orcamento_exibir', 'Orcamento: exibir secao?', '1', 'boolean', 1),
  ('orcamento_titulo', 'Orcamento: titulo', 'Solicite um orcamento', 'input', 1),
  ('orcamento_texto', 'Orcamento: texto', 'Preencha os dados e envie sua solicitacao para nossa equipe comercial.', 'textarea', 1),
  ('orcamento_campo_nome', 'Orcamento: campo nome', 'Nome', 'input', 1),
  ('orcamento_campo_email', 'Orcamento: campo e-mail', 'E-mail', 'input', 1),
  ('orcamento_campo_telefone', 'Orcamento: campo telefone', 'Telefone', 'input', 1),
  ('orcamento_campo_mensagem', 'Orcamento: campo mensagem', 'Mensagem', 'input', 1),
  ('orcamento_btn', 'Orcamento: botao', 'Enviar orcamento', 'input', 1),
  ('orcamento_nota', 'Orcamento: nota', 'Ao enviar, abriremos seu WhatsApp com a mensagem pronta.', 'textarea', 1),
  ('segmentos_titulo', 'Segmentos: titulo', 'Segmentos de Aplicacao', 'input', 1),
  ('segmentos_texto', 'Segmentos: texto', 'O datador inkjet pode ser utilizado em cosmeticos, alimentos, farmaceutico, quimica, logistica, bebidas e diversas linhas de embalagem.', 'textarea', 1),
  ('segmento_1', 'Segmento 1', 'Alimentos', 'input', 1),
  ('segmento_2', 'Segmento 2', 'Bebidas', 'input', 1),
  ('segmento_3', 'Segmento 3', 'Farmaceutico', 'input', 1),
  ('segmento_4', 'Segmento 4', 'Cosmeticos', 'input', 1),
  ('segmento_5', 'Segmento 5', 'Quimico', 'input', 1),
  ('segmento_6', 'Segmento 6', 'Logistica', 'input', 1),
  ('footer_titulo', 'Rodape: titulo', 'Printer Goiania', 'input', 1),
  ('footer_endereco', 'Rodape: endereco', 'R. Jequitiba, 543 - Jardim Mariliza, Goiania - GO', 'textarea', 1),
  ('footer_telefone', 'Rodape: telefone', '+55 (62) 9 9999-9999', 'input', 1),
  ('footer_email', 'Rodape: e-mail', 'contato@printergoiania.com.br', 'input', 1),
  ('footer_menu_titulo', 'Rodape: titulo menu', 'Acesso Rapido', 'input', 1),
  ('footer_news_titulo', 'Rodape: titulo newsletter', 'Newsletter', 'input', 1),
  ('footer_news_texto', 'Rodape: texto newsletter', 'Receba novidades e conteudos tecnicos por e-mail.', 'textarea', 1),
  ('footer_news_placeholder', 'Rodape: placeholder newsletter', 'Seu e-mail', 'input', 1),
  ('footer_news_btn', 'Rodape: botao newsletter', 'Enviar', 'input', 1),
  ('footer_copyright', 'Rodape: copyright', 'Copyright (c) 2026 Printer Goiania. Todos os direitos reservados.', 'input', 1)
ON DUPLICATE KEY UPDATE
  titulo = VALUES(titulo),
  conteudo = VALUES(conteudo),
  tipo = VALUES(tipo),
  ativo = VALUES(ativo);

INSERT INTO site_promocionais (id, titulo, legenda, caminho, alt_text, ordem, ativo)
VALUES
  (1, 'Imagem Promocional 1', 'Aplicacao em linha de producao.', 'images/banner/01-banner.jpg', 'Imagem promocional 1', 1, 1),
  (2, 'Imagem Promocional 2', 'Codificacao com alta nitidez.', 'images/banner/02-banner.jpg', 'Imagem promocional 2', 2, 1),
  (3, 'Imagem Promocional 3', 'Equipe tecnica especializada.', 'images/banner/03-banner.jpg', 'Imagem promocional 3', 3, 1),
  (4, 'Imagem Promocional 4', 'Instalacao e suporte continuo.', 'images/banner/04-banner.jpg', 'Imagem promocional 4', 4, 1),
  (5, 'Imagem Promocional 5', 'Projetos para diversas industrias.', 'images/banner/01-banner.jpg', 'Imagem promocional 5', 5, 1),
  (6, 'Imagem Promocional 6', 'Resultados reais no dia a dia.', 'images/banner/02-banner.jpg', 'Imagem promocional 6', 6, 1)
ON DUPLICATE KEY UPDATE
  titulo = VALUES(titulo),
  legenda = VALUES(legenda),
  caminho = VALUES(caminho),
  alt_text = VALUES(alt_text),
  ordem = VALUES(ordem),
  ativo = VALUES(ativo);
