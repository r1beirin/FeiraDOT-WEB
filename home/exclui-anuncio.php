<?php
require "../connect/conexaoMysql.php";
$pdo = mysqlConnect();

$codigo = $_GET["codigo"] ?? "";

try {
    $sqlFotos = <<<SQL
        SELECT nome_arquivo_foto
        FROM foto
        WHERE cod_anuncio = ?
        SQL;
    
    /**
     * Faz a consulta retornando um array com o nome das fotos
     * respectiva do código do anuncio.
     * Além disso, faz um foreach usando a função unlink passando o caminho da foto.
     */
    $stmtFotos = $pdo->prepare($sqlFotos);
    $stmtFotos->execute([$codigo]);
    $fotos = $stmtFotos->fetchAll(PDO::FETCH_COLUMN);

    foreach($fotos as $foto){
        unlink("../anuncio/img/" . $foto);
    }

    $sql = <<<SQL
        DELETE FROM anuncio
        WHERE codigo = ?
        LIMIT 1
    SQL;
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$codigo]);

    header("location: index.php");
    exit();
} catch (Exception $e) {
    exit('Ocorreu uma falha: ' . $e->getMessage());
}
