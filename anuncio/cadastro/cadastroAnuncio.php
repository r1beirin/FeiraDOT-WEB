<?php

class RequestResponse{
  public $success;
  public $detail;

  function __construct($success, $detail){
    $this->success = $success;
    $this->detail = $detail;
  }
}

require "../../connect/conexaoMysql.php";
require "../../sessionVerification.php";

session_start();

$pdo = mysqlConnect();

$titulo = $_POST["titulo"] ?? '';
$preco = $_POST["preco"] ?? '';
$descricao = $_POST["descricao"] ?? '';
$cep = $_POST["cep"] ?? '';
$datahora = $_POST["datahora"] ?? '';

$fotos = $_FILES["foto"] ?? [];

$categoria = $_POST["categoria"] ?? '';
$bairro = $_POST["bairro"] ?? '';
$cidade = $_POST["cidade"] ?? '';
$estado = $_POST["estado"] ?? '';

$codAnunciante = $_SESSION["codAnunciante"];

$sqlAnuncio = <<<SQL
    INSERT INTO anuncio (cod_categoria, cod_anunciante, titulo, descricao, preco, data_hora, cep, bairro, cidade, estado)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);
    SQL;

$sqlFoto = <<<SQL

    INSERT INTO foto (cod_anuncio, nome_arquivo_foto)
    VALUES (?, ?);
    SQL;

if (!isset($codAnunciante)) {
    $response = new RequestResponse("false", "/");
}
else{
    try {
        $pdo->beginTransaction();
    
        $stmtAnuncio = $pdo->prepare($sqlAnuncio);
        if (!$stmtAnuncio->execute([$categoria, $codAnunciante, $titulo, $descricao, $preco, $datahora, $cep, $bairro, $cidade, $estado])) {
            throw new Exception('Falha na operação de inserção do anuncio');
        }
    
        $ultimoIdInserido = $pdo->lastInsertId();
        $stmtFoto = $pdo->prepare($sqlFoto);
    
        for ($i = 0; $i < count($fotos["name"]); $i++) {
            //  $novo_nome vai ter a data e hora atual concatenando com o indice e por fim, pegando a extensão da imagem.
            $novo_nome = date("Y.m.d-H.i.s") . "-" . $i . strtolower(substr($fotos['name'][$i], -4)); // 2023.05.18-23.29.30 - 2 .png
            $destino = "../img/";
    
            //  Lança uma exceção ao tentar mover a pasta /tmp/. O segundo parametro será o destino + o novo nome (renomeia a foto temporária).
            if (!move_uploaded_file($fotos['tmp_name'][$i], $destino . $novo_nome)) {
                throw new Exception('Falha no upload do arquivo');
            }
    
            //  Insere no banco com o ultimo id inserio + o nome
            if (!$stmtFoto->execute([$ultimoIdInserido, $novo_nome])) {
                throw new Exception('Falha na operação de inserção do foto do anuncio no banco');
            }
        }

        $response = new RequestResponse("true", "/home/");
    
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        exit('Falha na transação: ' . $e->getMessage());
    }
}

echo json_encode($response);