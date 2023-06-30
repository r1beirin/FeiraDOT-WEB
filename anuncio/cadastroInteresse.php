<?php

class RequestResponse
{
    public $success;
    public $detail;

    function __construct($success, $detail)
    {
        $this->success = $success;
        $this->detail = $detail;
    }
}

require "../connect/conexaoMysql.php";
$pdo = mysqlConnect();

$cod = $_POST["cod"] ?? "";
$mensagem = $_POST["mensagem"] ?? '';
$contato = $_POST["contato"] ?? '';

$sql = <<<SQL
    INSERT INTO interesse (mensagem, data_hora, contato, cod_anuncio)
    VALUES (?, NOW(), ?, ?)
    SQL;

try{
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$mensagem, $contato, $cod]);

    $response = new RequestResponse(true, 'Sucesso');
}
catch(Exception $e){
    $response = new RequestResponse(false, 'Erro');
    exit("Falha: " . $e->getMessage());
}

header('Content-type: application/json');
echo json_encode($response);

?>