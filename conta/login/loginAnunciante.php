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
$pdo = mysqlConnect();

$email = $_POST["email"] ?? "";
$senha = $_POST["senha"] ?? "";

function checkLogin($pdo, $email, $senha){
    $sql = <<<SQL
        SELECT hash_senha
        FROM anunciante
        WHERE email = ?
        SQL;

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        //  Declara em $row a tupla que foi identificada pela consulta
        $row = $stmt->fetch();
        //  Se a consulta não existir (email não existor), retorna false
        if (!$row) return false;

        return password_verify($senha, $row['hash_senha']);
    } catch (Exception $e) {
        exit('Falha: ' . $e->getMessage());
    }
}

/**
 * Função que pega o codigo do anunciante a partir do email.
 * Usa fetchColumn para retornar um unico resultado possível.
 */
function getAnuncianteID($pdo, $email){
    $sql = <<<SQL
        SELECT codigo
        from anunciante
        where email = ?
        SQL;

    try{
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        return $cod = $stmt->fetchColumn();
        return $cod;
    }
    catch(Exception $e){
        exit("Erro: " . $e->getMessage());
    }
}

/**
 * Ao fazer login, salva as informações utilizando sessões do PHP.
 */
if (checkLogin($pdo, $email, $senha)) {
    session_start();
    $_SESSION["loggedIn"] = true;
    $_SESSION["email"] = $email;
    $_SESSION["codAnunciante"] = getAnuncianteID($pdo, $email);

    $response = new RequestResponse(true, "/home/");
} else {
    $response = new RequestResponse(false, '');
}

header('Content-type: application/json');
echo json_encode($response);
