<?php

require "../connect/conexaoMysql.php";
$pdo = mysqlConnect();

$codAnuncio = $_GET["cod"];

/**
 * Função que faz a verificação de existencia do anuncio passado pelo usuário.
 * Verificação ocorre a partir do número de linhas que existe na query resultada.
 * Se for igual a 0 (não existe anuncio com o ID), retorna false, se for maior (existe um anuncio), retorna true.
 */
function verifyAnuncio($pdo, $codAnuncio)
{
    $sql = <<<SQL
        SELECT codigo
        FROM anuncio
        WHERE codigo = ?
        SQL;

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$codAnuncio]);

        return $stmt->rowCount() > 0;
    } catch (Exception $e) {
        exit("Erro: " . $e->getMessage());
    }
}

// Se não existir anuncio, é redirecionado ao arquivo index na raiz.
if (!verifyAnuncio($pdo, $codAnuncio)) {
    header("Location: /");
    exit();
} else {
    $sqlFotos = <<<SQL
        SELECT nome_arquivo_foto
        FROM foto
        WHERE cod_anuncio = ?
        SQL;

    $sqlAnuncio = <<<SQL
        SELECT *
        FROM anuncio
        WHERE codigo = ?
        SQL;

    try {
        $stmtAnuncio = $pdo->prepare($sqlAnuncio);
        $stmtAnuncio->execute([$codAnuncio]);

        $stmtFotos = $pdo->prepare($sqlFotos);
        $stmtFotos->execute([$codAnuncio]);
    } catch (Exception $e) {
        exit("Erro: " . $e->getMessage());
    }

    // Array contendo todas as fotos respectivas do anuncio.
    $fotos = $stmtFotos->fetchAll(PDO::FETCH_COLUMN);
    // Pega a foto inicial para ser setada diretamente no HTML.
    $fotoInicial = $fotos[0];
    $fotos = json_encode($fotos);

    // Atribui cada resultado da coluna com a respectiva variavel.
    while ($row = $stmtAnuncio->fetch()) {
        $titulo = htmlspecialchars($row['titulo']);
        $descricao = htmlspecialchars($row['descricao']);
        $preco = htmlspecialchars($row['preco']);
    }

    /**
     * Mostra dinamicamente as informações do anuncio detalhado.
     * Utiliza atributo data em html para mandar o array $fotos.
     */
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="css/style.css">
            <title>Anuncio detalhado</title>
        </head>
        <body>
            <header>
                <nav id="menu-nav">
                    <a href="/"><span id="emoji">&#129309; Feira.</span></a>
                    <ul>
                        <li><a href="/home/">Home</a></li>
                        <li><a href="/conta/cadastro/">Cadastro</a></li>
                        <li><a href="/conta/login/">Login</a></li>
                    </ul>
                </nav>
            </header>

            <main>
                <div class="div-anuncio-detalhado">
                    <div class="div-anuncio-detalhado-foto">
                        <img src='/anuncio/img/$fotoInicial' class="img-div-anuncio" alt="Caneca personalizada">
                        <div id="fotos-data" data-fotos='$fotos'></div>
                        <button class="btn-div-anuncio">Proxima foto</button>
                    </div>
                    <div class="div-anuncio-detalhado-informacoes">
                        <h1>$titulo</h1>
                        <p>$descricao</p>
                        <p>Preço: $preco</p>
                    </div>
                </div>

                <div class="interesse">
                    <h1>Mensagem de interesse</h1>
                    <div>
                        <form action="cadastroInteresse.php" method="post" class="interesse-form">
                            <div>
                                <input type="text" name="cod" id="cod" value="$codAnuncio" hidden>
                            </div>
                            <div>
                                <label for="mensagem">Mensagem</label>
                                <input type="text" name="mensagem" id="mensagem">
                            </div>
                            <div>
                                <label for="contato">Email para contato</label>
                                <input type="email" name="contato" id="contato">
                            </div>

                            <button type="submit" id="btn-enviar">Enviar mensagem</button>
                        </form>
                    </div>
                </div>
            </main>

            <script src="js/script.js"></script>
        </body>
    </html>
    HTML;
}
