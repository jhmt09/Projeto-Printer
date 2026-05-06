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

INSERT INTO site_imagens
  (chave, titulo, descricao, caminho, alt_text, largura_recomendada, altura_recomendada, tamanho_max_mb, ativo)
VALUES
  ('logo_site', 'Logo do Site', 'Logo principal exibida no cabeçalho e rodapé.', 'images/logo.png', 'Logo da Printer Goiânia', 600, 220, 1.00, 1),
  ('banner_principal', 'Banner Principal', 'Imagem grande do topo da landing page.', 'images/banner/01-banner.jpg', 'Banner principal da empresa', 1920, 900, 2.00, 1),
  ('imagem_sobre', 'Imagem da Seção Sobre', 'Imagem exibida no bloco institucional.', 'images/banner/02-banner.jpg', 'Imagem institucional da Printer Goiânia', 1200, 800, 2.00, 1),
  ('servico_1', 'Imagem do Serviço 1', 'Imagem exibida no card de serviço 1.', 'images/banner/01-banner.jpg', 'Imagem do serviço 1', 1200, 800, 2.00, 1),
  ('servico_2', 'Imagem do Serviço 2', 'Imagem exibida no card de serviço 2.', 'images/banner/03-banner.jpg', 'Imagem do serviço 2', 1200, 800, 2.00, 1),
  ('servico_3', 'Imagem do Serviço 3', 'Imagem exibida no card de serviço 3.', 'images/banner/04-banner.jpg', 'Imagem do serviço 3', 1200, 800, 2.00, 1),
  ('servico_4', 'Imagem do Serviço 4', 'Imagem adicional configurável de serviço/produto.', 'images/banner/02-banner.jpg', 'Imagem do serviço 4', 1200, 800, 2.00, 1)
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
  ('hero_titulo', 'Título do Hero', 'Venda e Assistência de Datadores para elevar sua produção.', 'input', 1),
  ('hero_subtitulo', 'Subtítulo do Hero', 'Estrutura técnica para atendimento rápido em Goiânia, Brasília e região, com foco em performance, confiabilidade e suporte contínuo.', 'textarea', 1),
  ('sobre_texto', 'Texto da Seção Sobre', 'A Printer Goiânia atua com venda e assistência técnica de datadores inkjet para indústrias e empresas que precisam de codificação eficiente em embalagens e rótulos.', 'textarea', 1)
ON DUPLICATE KEY UPDATE
  titulo = VALUES(titulo),
  conteudo = VALUES(conteudo),
  tipo = VALUES(tipo),
  ativo = VALUES(ativo);
