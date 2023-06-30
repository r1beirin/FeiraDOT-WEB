<?php

require "connect/conexaoMysql.php";
$pdo = mysqlConnect();

class Anuncio{
  public $codigo;
  public $nome;
  public $descricao;
  public $preco;
  public $imagem;

  function __construct($codigo, $nome, $descricao, $preco, $imagem){
    $this->codigo = $codigo;
    $this->nome = $nome;
    $this->descricao = $descricao;
    $this->preco = $preco;
    $this->imagem = $imagem;
  }
}

$texto = $_POST["search"] ?? "";
$opcao = $_POST["opcao"] ?? "";
$precoMin = $_POST["priceMin"] ?? "";
$precoMax = $_POST["priceMax"] ?? "";
$opcaoCategoria = $_POST["categoria"];

/**
 * Configurações para a busca usando rolagem.
 *  Stack overflow topic: https://bit.ly/42iPsix
 */
$pagina = $_POST["pagina"] ?? 1;
$itensPorPagina = 6;
$offset = ($pagina - 1) * $itensPorPagina;

/**
 * Separa a string usando espaço como delimitador com o explode.
 * E por fim, obtem as 5 primeiras strings.
 */
$palavras = explode(" ", $texto);
$palavrasChave = array_slice($palavras, 0, 5);

/**
 * Verifico se existe os parametro de preço minimo e máximo recebidos do cliente.
 * Se não existir, seta os preços entre 0 e 9999999999999.
 */
 if($precoMin == "") $precoMin = 0;
 if($precoMax == "") $precoMax = 999999999;

/**
 * Faz a busca usando os filtros para título.
 * Com opção de categoria.
 */
function buscaTitulo($pdo, $palavrasChave, $precoMin, $precoMax, $opcaoCategoria, $itensPorPagina, $offset){
  try {
    $anuncios = array();

    $sqlCategoria = <<<SQL
        SELECT codigo
        FROM categoria
        WHERE nome = ?
        SQL;
    $stmtCategoria = $pdo->prepare($sqlCategoria);
    $stmtCategoria->execute([$opcaoCategoria]);
    $cod_categoria = $stmtCategoria->fetch(PDO::FETCH_COLUMN);

    $sqlFotos = <<<SQL
        SELECT nome_arquivo_foto
        FROM foto
        WHERE cod_anuncio = ?
        SQL;
      
    $sqlAnuncio = <<<SQL
        SELECT codigo, titulo, descricao, preco
        FROM anuncio
        WHERE (
          titulo LIKE ?
          OR titulo LIKE ?
          OR titulo LIKE ?
          OR titulo LIKE ?
          OR titulo LIKE ?
        )
        AND preco BETWEEN ? AND ?
        AND cod_categoria = ?
        LIMIT ? OFFSET ?
    SQL;

    $palavraChaveUm = '%'.$palavrasChave[0].'%';
    $palavraChaveDois = '%'.$palavrasChave[1].'%';
    $palavraChaveTres = '%'.$palavrasChave[2].'%';
    $palavraChaveQuatro = '%'.$palavrasChave[3].'%';
    $palavraChaveCinco = '%'.$palavrasChave[4].'%';

    $stmtAnuncio = $pdo->prepare($sqlAnuncio);
    $stmtAnuncio->execute([$palavraChaveUm, $palavraChaveDois, $palavraChaveTres, $palavraChaveQuatro, $palavraChaveCinco, $precoMin, $precoMax, $cod_categoria, $itensPorPagina, $offset]);

    /**
     * Percorre todas as linhas, uma por uma e atribui valores as variáveis.
     * Para cada linha, é feito uma query na tabela de nome de fotos. Pega o array de fotos
     * respectivo ao anuncio e pega a primeira ocorrencia do array (será a primeira foto).
     * 
     * Por fim, adiciona ao vetor $anuncios, um objeto respectivo contendo as informações.
     */
    while($row = $stmtAnuncio->fetch()){
      $codigo = $row["codigo"];
      $titulo = $row["titulo"];
      $descricao = $row["descricao"];
      $preco = $row["preco"];
    
      $stmtFotos = $pdo->prepare($sqlFotos);
      $stmtFotos->execute([$codigo]);
      $fotos = $stmtFotos->fetchAll(PDO::FETCH_COLUMN);
      $fotoInicial = $fotos[0];
      
      $anuncios[] = new Anuncio($codigo, $titulo, $descricao, $preco, "/anuncio/img/" . $fotoInicial);
    }

    header("Content-type: application/json");
    echo json_encode($anuncios);
    exit();
  }
  catch (Exception $exception) {
    exit("Falha: " . $exception->getMessage());
  }
}

/**
 * Faz a busca usando os filtros para descrição.
 * Com opção de categoria.
 */
function buscaDescricao($pdo, $palavrasChave, $precoMin, $precoMax, $opcaoCategoria, $itensPorPagina, $offset){
  try {
    $anuncios = array();

    $sqlCategoria = <<<SQL
        SELECT codigo
        FROM categoria
        WHERE nome = ?
        SQL;
    $stmtCategoria = $pdo->prepare($sqlCategoria);
    $stmtCategoria->execute([$opcaoCategoria]);
    $cod_categoria = $stmtCategoria->fetch(PDO::FETCH_COLUMN);

    $sqlFotos = <<<SQL
        SELECT nome_arquivo_foto
        FROM foto
        WHERE cod_anuncio = ?
        SQL;
      
    $sqlAnuncio = <<<SQL
        SELECT codigo, titulo, descricao, preco
        FROM anuncio
        WHERE (
          descricao LIKE ?
          OR descricao LIKE ?
          OR descricao LIKE ?
          OR descricao LIKE ?
          OR descricao LIKE ?
        )
        AND preco BETWEEN ? AND ?
        AND cod_categoria = ?
        LIMIT ? OFFSET ?
    SQL;

    $palavraChaveUm = '%'.$palavrasChave[0].'%';
    $palavraChaveDois = '%'.$palavrasChave[1].'%';
    $palavraChaveTres = '%'.$palavrasChave[2].'%';
    $palavraChaveQuatro = '%'.$palavrasChave[3].'%';
    $palavraChaveCinco = '%'.$palavrasChave[4].'%';

    $stmtAnuncio = $pdo->prepare($sqlAnuncio);
    $stmtAnuncio->execute([$palavraChaveUm, $palavraChaveDois, $palavraChaveTres, $palavraChaveQuatro, $palavraChaveCinco, $precoMin, $precoMax, $cod_categoria, $itensPorPagina, $offset]);

    /**
     * Percorre todas as linhas, uma por uma e atribui valores as variáveis.
     * Para cada linha, é feito uma query na tabela de nome de fotos. Pega o array de fotos
     * respectivo ao anuncio e pega a primeira ocorrencia do array (será a primeira foto).
     * 
     * Por fim, adiciona ao vetor $anuncios, um objeto respectivo contendo as informações.
     */
    while($row = $stmtAnuncio->fetch()){
      $codigo = $row["codigo"];
      $titulo = $row["titulo"];
      $descricao = $row["descricao"];
      $preco = $row["preco"];
    
      $stmtFotos = $pdo->prepare($sqlFotos);
      $stmtFotos->execute([$codigo]);
      $fotos = $stmtFotos->fetchAll(PDO::FETCH_COLUMN);
      $fotoInicial = $fotos[0];
      
      $anuncios[] = new Anuncio($codigo, $titulo, $descricao, $preco, "/anuncio/img/" . $fotoInicial);
    }

    header("Content-type: application/json");
    echo json_encode($anuncios);
    exit();
  }
  catch (Exception $exception) {
    exit("Falha: " . $exception->getMessage());
  }
}

/**
 * Faz a busca usando titulos somente.
 * Sem opção de categoria.
 */
function buscaTituloTodos($pdo, $palavrasChave, $itensPorPagina, $offset){
  try {
    $anuncios = array();

    $sqlFotos = <<<SQL
        SELECT nome_arquivo_foto
        FROM foto
        WHERE cod_anuncio = ?
        SQL;
      
    $sqlAnuncio = <<<SQL
        SELECT codigo, titulo, descricao, preco
        FROM anuncio
        WHERE (
          titulo LIKE ?
          OR titulo LIKE ?
          OR titulo LIKE ?
          OR titulo LIKE ?
          OR titulo LIKE ?
        )
        LIMIT ? OFFSET ?
    SQL;

    $palavraChaveUm = '%'.$palavrasChave[0].'%';
    $palavraChaveDois = '%'.$palavrasChave[1].'%';
    $palavraChaveTres = '%'.$palavrasChave[2].'%';
    $palavraChaveQuatro = '%'.$palavrasChave[3].'%';
    $palavraChaveCinco = '%'.$palavrasChave[4].'%';

    $stmtAnuncio = $pdo->prepare($sqlAnuncio);
    $stmtAnuncio->execute([$palavraChaveUm, $palavraChaveDois, $palavraChaveTres, $palavraChaveQuatro, $palavraChaveCinco, $itensPorPagina, $offset]);

    /**
     * Percorre todas as linhas, uma por uma e atribui valores as variáveis.
     * Para cada linha, é feito uma query na tabela de nome de fotos. Pega o array de fotos
     * respectivo ao anuncio e pega a primeira ocorrencia do array (será a primeira foto).
     * 
     * Por fim, adiciona ao vetor $anuncios, um objeto respectivo contendo as informações.
     */
    while($row = $stmtAnuncio->fetch()){
      $codigo = $row["codigo"];
      $titulo = $row["titulo"];
      $descricao = $row["descricao"];
      $preco = $row["preco"];
    
      $stmtFotos = $pdo->prepare($sqlFotos);
      $stmtFotos->execute([$codigo]);
      $fotos = $stmtFotos->fetchAll(PDO::FETCH_COLUMN);
      $fotoInicial = $fotos[0];
      
      $anuncios[] = new Anuncio($codigo, $titulo, $descricao, $preco, "/anuncio/img/" . $fotoInicial);
    }

    header("Content-type: application/json");
    echo json_encode($anuncios);
    exit();
  }
  catch (Exception $exception) {
    exit("Falha: " . $exception->getMessage());
  }
}

/**
 * Faz a busca usando descrição somente.
 * Sem opção de categoria.
 */
function buscaDescricaoTodos($pdo, $palavrasChave, $itensPorPagina, $offset){
  try {
    $anuncios = array();

    $sqlFotos = <<<SQL
        SELECT nome_arquivo_foto
        FROM foto
        WHERE cod_anuncio = ?
        SQL;
      
    $sqlAnuncio = <<<SQL
        SELECT codigo, titulo, descricao, preco
        FROM anuncio
        WHERE (
          descricao LIKE ?
          OR descricao LIKE ?
          OR descricao LIKE ?
          OR descricao LIKE ?
          OR descricao LIKE ?
        )
        LIMIT ? OFFSET ?
    SQL;

    $palavraChaveUm = '%'.$palavrasChave[0].'%';
    $palavraChaveDois = '%'.$palavrasChave[1].'%';
    $palavraChaveTres = '%'.$palavrasChave[2].'%';
    $palavraChaveQuatro = '%'.$palavrasChave[3].'%';
    $palavraChaveCinco = '%'.$palavrasChave[4].'%';

    $stmtAnuncio = $pdo->prepare($sqlAnuncio);
    $stmtAnuncio->execute([$palavraChaveUm, $palavraChaveDois, $palavraChaveTres, $palavraChaveQuatro, $palavraChaveCinco, $itensPorPagina, $offset]);

    /**
     * Percorre todas as linhas, uma por uma e atribui valores as variáveis.
     * Para cada linha, é feito uma query na tabela de nome de fotos. Pega o array de fotos
     * respectivo ao anuncio e pega a primeira ocorrencia do array (será a primeira foto).
     * 
     * Por fim, adiciona ao vetor $anuncios, um objeto respectivo contendo as informações.
     */
    while($row = $stmtAnuncio->fetch()){
      $codigo = $row["codigo"];
      $titulo = $row["titulo"];
      $descricao = $row["descricao"];
      $preco = $row["preco"];
    
      $stmtFotos = $pdo->prepare($sqlFotos);
      $stmtFotos->execute([$codigo]);
      $fotos = $stmtFotos->fetchAll(PDO::FETCH_COLUMN);
      $fotoInicial = $fotos[0];
      
      $anuncios[] = new Anuncio($codigo, $titulo, $descricao, $preco, "/anuncio/img/" . $fotoInicial);
    }

    header("Content-type: application/json");
    echo json_encode($anuncios);
    exit();
  }
  catch (Exception $exception) {
    exit("Falha: " . $exception->getMessage());
  }
}

/**
 * Faz a busca usando titulo ou descrição.
 */
function buscaTodos($pdo, $palavrasChave, $itensPorPagina, $offset){
  try {
    $anuncios = array();

    $sqlFotos = <<<SQL
        SELECT nome_arquivo_foto
        FROM foto
        WHERE cod_anuncio = ?
        SQL;
      
    $sqlAnuncio = <<<SQL
        SELECT codigo, titulo, descricao, preco
        FROM anuncio
        WHERE (
          titulo LIKE ?
          OR titulo LIKE ?
          OR titulo LIKE ?
          OR titulo LIKE ?
          OR titulo LIKE ?
          OR descricao LIKE ?
          OR descricao LIKE ?
          OR descricao LIKE ?
          OR descricao LIKE ?
          OR descricao LIKE ?
        )
        LIMIT ? OFFSET ?
    SQL;

    $palavraChaveUm = '%'.$palavrasChave[0].'%';
    $palavraChaveDois = '%'.$palavrasChave[1].'%';
    $palavraChaveTres = '%'.$palavrasChave[2].'%';
    $palavraChaveQuatro = '%'.$palavrasChave[3].'%';
    $palavraChaveCinco = '%'.$palavrasChave[4].'%';

    $stmtAnuncio = $pdo->prepare($sqlAnuncio);
    $stmtAnuncio->execute([$palavraChaveUm, $palavraChaveDois, $palavraChaveTres, $palavraChaveQuatro, $palavraChaveCinco, $palavraChaveUm, $palavraChaveDois, $palavraChaveTres, $palavraChaveQuatro, $palavraChaveCinco, $itensPorPagina, $offset]);

    /**
     * Percorre todas as linhas, uma por uma e atribui valores as variáveis.
     * Para cada linha, é feito uma query na tabela de nome de fotos. Pega o array de fotos
     * respectivo ao anuncio e pega a primeira ocorrencia do array (será a primeira foto).
     * 
     * Por fim, adiciona ao vetor $anuncios, um objeto respectivo contendo as informações.
     */
    while($row = $stmtAnuncio->fetch()){
      $codigo = $row["codigo"];
      $titulo = $row["titulo"];
      $descricao = $row["descricao"];
      $preco = $row["preco"];
    
      $stmtFotos = $pdo->prepare($sqlFotos);
      $stmtFotos->execute([$codigo]);
      $fotos = $stmtFotos->fetchAll(PDO::FETCH_COLUMN);
      $fotoInicial = $fotos[0];
      
      $anuncios[] = new Anuncio($codigo, $titulo, $descricao, $preco, "/anuncio/img/" . $fotoInicial);
    }

    header("Content-type: application/json");
    echo json_encode($anuncios);
    exit();
  }
  catch (Exception $exception) {
    exit("Falha: " . $exception->getMessage());
  }
}

// Busca por titulo, preços e categoria
if($opcao == "titulo" && $opcaoCategoria != ""){
  buscaTitulo($pdo, $palavrasChave, $precoMin, $precoMax, $opcaoCategoria, $itensPorPagina, $offset);
}
// Busca por descrição, preços e categoria
elseif($opcao == "descricao" && $opcaoCategoria != ""){
  buscaDescricao($pdo, $palavrasChave, $precoMin, $precoMax, $opcaoCategoria, $itensPorPagina, $offset);
}
else{
  // Busca por titulo
  if($opcao == "titulo"){
    buscaTituloTodos($pdo, $palavrasChave, $itensPorPagina, $offset);
  }
  // Busca por descrição
  elseif($opcao == "descricao"){
    buscaDescricaoTodos($pdo, $palavrasChave, $itensPorPagina, $offset);
  }
  // Busca por titlo ou descrição
  else{
    buscaTodos($pdo, $palavrasChave, $itensPorPagina, $offset);
  }
}