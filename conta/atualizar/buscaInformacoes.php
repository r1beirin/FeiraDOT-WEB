<?php

require "../../connect/conexaoMysql.php";
$pdo = mysqlConnect();
$email = $_GET["email"];

try {
    $sql = <<<SQL
        SELECT nome, cpf, email, telefone
        FROM anunciante
        WHERE email = ?;
    SQL;

    /**
     * Busca todas as informações a partir do email.
     */
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    $informacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($informacoes);
} catch (Exception $exception) {
    exit("Falha: " . $exception->getMessage());
}
