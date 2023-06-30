<?php

require "../../sessionVerification.php";

session_start();
exitWhenNotLoggedIn();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Cadastro de anuncio</title>
</head>
<body>
    <nav class="navbar sticky-top navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand center" href="/"><span id="emoji_solo_logo">&#129309; Feira.</span></a>
            <div class="gap-2 mb-2">
                <a href="/home/" class="btn btn-outline-light">Home</a>
                <a href="/interesses/" class="btn btn-outline-light">Interesses</a>
                <a href="/conta/atualizar/" class="btn btn-outline-light">Atualizar dados</a>
                <a href="/logout.php" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>
    
    <main>
        <div class="container-md">
            <h1 id="title-cadastro">Cadastro de anuncio</h1>
            <form action="cadastroAnuncio.php" method="post" enctype="multipart/form-data" id="form-cadastro">
                <div class="row">
                    <div class="col-md-10 p-2">
                        <div class="form-floating">
                            <input type="text" name="titulo" id="titulo" class="form-control" placeholder="">
                            <label for="titulo">Título</label>
                        </div> 
                    </div>
                    <div class="col-md-2 p-2">
                        <div class="form-floating">
                            <input type="number" name="preco" id="preco" class="form-control" step="any" placeholder="">
                            <label for="preco">Preço</label>
                        </div>
                    </div>
                    <div class="col-md-12 p-2">
                        <div class="form-floating">
                            <input type="text" name="descricao" id="descricao" class="form-control" placeholder="">
                            <label for="descricao">Descrição</label>
                        </div>
                    </div>
                    <div class="col-md-3 p-2">
                        <div class="form-floating">
                            <input type="cep" name="cep" id="cep" class="form-control" placeholder="">
                            <label for="cep">CEP</label>
                        </div>
                    </div>
                    <div class="col-md-3 p-2">
                        <div class="form-floating">
                            <input type="datetime-local" name="datahora" id="datahora" class="form-control" placeholder="">
                            <label for="datahora">Data e hora</label>
                        </div>
                    </div>
                    <div class="col-md-3 p-2">
                        <div class="form-floating">
                            <input type="file" name="foto[]" id="foto" class="form-control" placeholder="" multiple accept="image/*">
                            <label for="foto">Foto</label>
                        </div>
                    </div>
                    <div class="col-md-3 p-2">
                        <div class="form-floating">
                            <select name="categoria" id="categoria" class="form-select">
                                <option selected>Selecione</option>
                            </select>
                            <label for="categoria">Categoria</label>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-floating">
                            <input type="text" name="bairro" id="bairro" class="form-control" placeholder="">
                            <label for="bairro">Bairro</label>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-floating">
                            <input type="text" name="cidade" id="cidade" class="form-control" placeholder="">
                            <label for="cidade">Cidade</label>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-floating">
                            <input type="text" name="estado" id="estado" class="form-control" placeholder="">
                            <label for="estado">Estado</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Cadastrar anuncio</button>
            </form>
        </div>
    </main>

    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>