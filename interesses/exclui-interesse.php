<?php
require "../connect/conexaoMysql.php";
$pdo = mysqlConnect();

$codigo = $_GET["codigo"] ?? "";

try {
    $sql = <<<SQL
        DELETE FROM interesse
        WHERE codigo = ?
        LIMIT 1
    SQL;

    // Neste exemplo não é necessário utilizar prepared statements
    // porque não há possibilidade de injeção de SQL, 
    // pois nenhum parâmetro é utilizado na query SQL
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$codigo]);

    header("location: index.php");
    exit();
} catch (Exception $e) {
    exit('Ocorreu uma falha: ' . $e->getMessage());
}
