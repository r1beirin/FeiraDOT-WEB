CREATE TABLE anunciante(
    codigo int PRIMARY KEY auto_increment,
    nome varchar(50),
    cpf char(14) UNIQUE,
    email varchar(50) UNIQUE,
    hash_senha varchar(255),
    telefone varchar(30)
) ENGINE=InnoDB;

CREATE TABLE categoria(
    codigo int PRIMARY KEY auto_increment,
    nome varchar(50),
    descricao varchar(255)
) ENGINE=InnoDB;

CREATE TABLE anuncio(
    codigo int PRIMARY KEY auto_increment,
    cod_categoria int not null,
    cod_anunciante int not null,
    titulo varchar(50),
    descricao varchar(10000),
    preco float,
    data_hora datetime,
    cep char(10),
    bairro varchar(50),
    cidade varchar(50),
    estado varchar(50),
    FOREIGN KEY (cod_categoria) REFERENCES categoria(codigo) ON DELETE CASCADE,
    FOREIGN KEY (cod_anunciante) REFERENCES anunciante(codigo) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE interesse(
    codigo int PRIMARY KEY auto_increment,
    mensagem varchar(255),
    data_hora datetime,
    contato varchar(50),
    cod_anuncio int not null,
    FOREIGN KEY (cod_anuncio) REFERENCES anuncio(codigo) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE foto(
    cod_anuncio int not null,
    nome_arquivo_foto varchar(255),
    FOREIGN KEY (cod_anuncio) REFERENCES anuncio(codigo) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE base_endereco_ajax(
    cep char(10),
    bairro varchar(50),
    cidade varchar(50),
    estado varchar(50)
) ENGINE=InnoDB;

INSERT INTO categoria VALUES (default, "Veículo", "A categoria de veículo é uma classificação ampla que engloba diversos tipos de meios de transporte motorizados. Esses veículos podem ser utilizados para transportar pessoas, bens, mercadorias, materiais e equipamentos de um lugar para outro.");
INSERT INTO categoria VALUES (default, "Eletroeletrônico", "A categoria de eletroeletrônicos engloba uma ampla gama de dispositivos eletrônicos que são alimentados por energia elétrica. Esses dispositivos incluem desde aparelhos domésticos, como televisores, geladeiras e aspiradores de pó.");
INSERT INTO categoria VALUES (default, "Imóvel", "A categoria de imóvel engloba uma ampla variedade de propriedades, incluindo residenciais, comerciais, industriais e terrenos.");
INSERT INTO categoria VALUES (default, "Vestuário", "A categoria de vestuário engloba todas as peças de roupa, calçados e acessórios que são usados para cobrir e proteger o corpo.");
INSERT INTO categoria VALUES (default, "Outros", "A categoria 'outros' é uma classificação ampla que engloba uma ampla variedade de itens que não se enquadram em outras categorias mais específicas.");

INSERT INTO base_endereco_ajax VALUES ("38400-100", "Avenida Floriano Peixoto", "Centro", "Uberlândia");
