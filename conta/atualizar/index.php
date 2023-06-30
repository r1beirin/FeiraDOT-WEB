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
    <title>Alteração dos dados de cadastro</title>
</head>
<body>
    <header>
        <nav class="navbar sticky-top navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand center" href="/"><span id="emoji_solo_logo">&#129309; Feira.</span></a>
                <div class="gap-2 mb-2">
                    <a href="/home/" class="btn btn-outline-light">Home</a>
                    <a href="/anuncio/cadastro/" class="btn btn-outline-light">Novo Anuncio</a>
                    <a href="/interesses/" class="btn btn-outline-light">Interesses</a>
                    <a href="/logout.php" class="btn btn-outline-light">Logout</a>
                </div>
            </div>
        </nav>
    </header>
    
    <main>
        <div class="container-md modify">
            <h1 id="title-atualiza">Alteração dos dados de cadastro</h1>
            <form action="cadastroAtualiza.php" method="post" id="form-atualiza">
                <div class="row g-3 align-items-center justify-content-center">
                    <div class="col-md-6 p-2">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" name="nome" id="nome" class="form-control" placeholder="">
                    </div>
                    <div class="col-md-6 p-2">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" disabled placeholder="" <?php echo "value=" . $_SESSION['email']?>>
                        <input type=hidden name="email" <?php echo "value=" . $_SESSION['email']?> >
                    </div>
                    <div class="col-md-6 p-2">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" name="cpf" id="cpf" class="form-control" placeholder="">
                    </div>
                    <div class="col-md-6 p-2">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" name="telefone" id="telefone" class="form-control" placeholder="">
                    </div>
                    <div class="col-md-6 p-2">
                        <label for="senhaAntiga" class="form-label">Digite a senha antiga</label>
                        <input type="password" name="senhaAntiga" id="senhaAntiga" class="form-control" placeholder="">
                    </div>
                    <div class="col-md-6 p-2">
                        <label for="senhaNova" class="form-label">Digite a senha nova</label>
                        <input type="password" name="senhaNova" id="senhaNova" class="form-control" placeholder="">
                    </div>
                </div>

                <div>
                    <p id="att-fail">Senha incorreta. Tente novamente.</p>
                </div>
                <button type="submit" class="btn btn-success">Atualizar cadastro</button>
            </form>
        </div>
    </main>

    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>