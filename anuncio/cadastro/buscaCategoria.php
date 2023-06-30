<?php

require "../../connect/conexaoMysql.php";
$pdo = mysqlConnect();

try {
    $sql = <<<SQL
        SELECT codigo, nome
        FROM categoria
    SQL;

    $stmt = $pdo->query($sql);
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($categorias);
} catch (Exception $exception) {
    exit("Falha: " . $exception->getMessage());
}
