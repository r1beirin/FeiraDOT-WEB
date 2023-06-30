<?php

class RequestResponse
{
    public $success;
    public $detail;
    public $email;

    function __construct($success, $detail, $email)
    {
        $this->success = $success;
        $this->detail = $detail;
        $this->email = $email;
    }
}

require "../../connect/conexaoMysql.php";
$pdo = mysqlConnect();

$nome = $_POST["nome"] ?? '';
$email = $_POST["email"] ?? '';
$cpf = $_POST["cpf"] ?? '';
$telefone = $_POST["telefone"] ?? '';
$senha_antiga = $_POST["senhaAntiga"] ?? '';
$senha_nova = $_POST["senhaNova"] ?? '';

function checkPass($pdo, $email, $senha)
{
    $sql = <<<SQL
        SELECT hash_senha
        FROM anunciante
        WHERE email = ?
        SQL;

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $senhaHash = $stmt->fetchColumn();

        //  Email não encontrado
        if (!$senhaHash) return false;

        // Senha incorreta
        if (!password_verify($senha, $senhaHash)) return false;
        
        return true;
    } catch (Exception $e) {
        exit('Falha: ' . $e->getMessage());
    }
}

/**
 * Verifica se as senhas batem.
 * Se bater, temos duas opções:
 * 1ª - Atualizar todos os campos sem modificar a senha
 * 2ª - Atualizar todos os campos modificando senha (usando a função empty)
 */
if (checkPass($pdo, $email, $senha_antiga)) {

    $sqlUpdate = <<<SQL
        UPDATE anunciante
        SET nome = ?, cpf = ?, hash_senha = ?, telefone = ?
        where email = ?
        SQL;

    if(!empty($senha_nova)){
        $hash_senha = password_hash($senha_nova, PASSWORD_DEFAULT);
    }
    else{
        $hash_senha = password_hash($senha_antiga, PASSWORD_DEFAULT);
    }

    $stmtUpdate = $pdo->prepare($sqlUpdate);
    $stmtUpdate->execute([$nome, $cpf, $hash_senha, $telefone, $email]);

    $response = new RequestResponse(true, '/conta/atualizar/', $email);
} else {
    $response = new RequestResponse(false, '', $email);
}

header('Content-type: application/json');
echo json_encode($response);
