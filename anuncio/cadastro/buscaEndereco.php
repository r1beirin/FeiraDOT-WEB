<?php

class Endereco
{
  public $bairro;
  public $cidade;
  public $estado;

  function __construct($bairro, $cidade, $estado)
  {
    $this->bairro = $bairro;
    $this->cidade = $cidade;
    $this->estado = $estado;
  }
}

require "../../connect/conexaoMysql.php";
$pdo = mysqlConnect();

// Carrega a string JSON da requisição
// php://input retorna todos os dados que vem depois
$stringJSON = file_get_contents('php://input');
// converte a string JSON em objeto PHP
$dados = json_decode($stringJSON);
$cep = $dados->cep;

try {
  $sql = <<<SQL
        SELECT bairro, cidade, estado
        FROM base_endereco_ajax
        WHERE cep = ?
    SQL;

  $stmt = $pdo->prepare($sql);
  $stmt->execute([$cep]);

  while ($row = $stmt->fetch()) {
    $bairro = $row['bairro'];
    $cidade = $row['cidade'];
    $estado = $row['estado'];
  }
} catch (Exception $exception) {
  exit("Falha: " . $exception->getMessage());
}

if ($cep)
  $endereco = new Endereco($bairro, $cidade, $estado);
else {
  $endereco = new Endereco('', '', '');
}

header('Content-type: application/json');
echo json_encode($endereco);
