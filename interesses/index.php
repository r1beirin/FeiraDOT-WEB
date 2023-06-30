<?php
require "../connect/conexaoMysql.php";
$pdo = mysqlConnect();

require "../sessionVerification.php";

session_start();
exitWhenNotLoggedIn();

$codAnunciante = $_SESSION["codAnunciante"];

try {
  $sql = <<<SQL
    SELECT codigo, mensagem, data_hora, contato , cod_anuncio
    FROM interesse
    WHERE cod_anuncio IN (SELECT codigo FROM anuncio WHERE cod_anunciante = $codAnunciante)
    ORDER BY data_hora asc;
    
    SQL;

  // Neste exemplo não é necessário utilizar prepared statements
  // porque não há possibilidade de injeção de SQL, 
  // pois nenhum parâmetro é utilizado na query SQL
  $stmt = $pdo->query($sql);
} catch (Exception $e) {
  exit('Ocorreu uma falha: ' . $e->getMessage());
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css">
  <title>Home - Interesses</title>
</head>

<body>
  <nav class="navbar sticky-top navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand center" href="/"><span id="emoji_solo_logo">&#129309; Feira.</span></a>
      <div class="gap-2 mb-2">
        <a href="/home/" class="btn btn-outline-light btnNav">Home</a>
        <a href="/anuncio/cadastro/" class="btn btn-outline-light btnNav">Novo Anuncio</a>
        <a href="/conta/atualizar/" class="btn btn-outline-light btnNav">Atualizar dados</a>
        <a href="/logout.php" class="btn btn-outline-light btnNav">Logout</a>
      </div>
    </div>
  </nav>

  <main class="container">
    <h1 id="title-interesse">Interesses</h1>
    <table class="table table-hover table-responsive">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Anuncio</th>
          <th scope="col">Mensagem</th>
          <th scope="col">Data</th>
          <th scope="col">Contato</th>
          <th scope="col">Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($row = $stmt->fetch()) {
          // Limpa os dados produzidos pelo usuário
          // com possibilidade de ataque XSS
          $codigo = htmlspecialchars($row['codigo']);
          $cod_anuncio = htmlspecialchars($row['cod_anuncio']);
          $mensagem  = htmlspecialchars($row['mensagem']);
          $data_hora  = htmlspecialchars($row['data_hora']);
          $contato = htmlspecialchars($row['contato']);

          echo <<<HTML
            <tr>

                <th scope="row">$codigo</th>
                <td>$cod_anuncio</td>
                <td>$mensagem</td>
                <td>$data_hora</td> 
                <td>$contato</td>

                <td>   
                  <a type="button" class="btn btn-primary" href="mailto:$contato">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-at" viewBox="0 0 16 16">
                      <path d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2H2Zm3.708 6.208L1 11.105V5.383l4.708 2.825ZM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2-7-4.2Z"/>
                      <path d="M14.247 14.269c1.01 0 1.587-.857 1.587-2.025v-.21C15.834 10.43 14.64 9 12.52 9h-.035C10.42 9 9 10.36 9 12.432v.214C9 14.82 10.438 16 12.358 16h.044c.594 0 1.018-.074 1.237-.175v-.73c-.245.11-.673.18-1.18.18h-.044c-1.334 0-2.571-.788-2.571-2.655v-.157c0-1.657 1.058-2.724 2.64-2.724h.04c1.535 0 2.484 1.05 2.484 2.326v.118c0 .975-.324 1.39-.639 1.39-.232 0-.41-.148-.41-.42v-2.19h-.906v.569h-.03c-.084-.298-.368-.63-.954-.63-.778 0-1.259.555-1.259 1.4v.528c0 .892.49 1.434 1.26 1.434.471 0 .896-.227 1.014-.643h.043c.118.42.617.648 1.12.648Zm-2.453-1.588v-.227c0-.546.227-.791.573-.791.297 0 .572.192.572.708v.367c0 .573-.253.744-.564.744-.354 0-.581-.215-.581-.8Z"/>
                    </svg>
                  </a>
                  <a type="button" class="btn btn-danger" href="exclui-interesse.php?codigo=$codigo">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                      </svg>
                  </a>    
                </td>
            </tr>
        HTML;
        }
        ?>
      </tbody>
    </table>
  </main>

  <footer>
    <div class="footer">
      <div class="footer-sub">
        <h4>Contato</h4>
        <address>
          <span>
            <a href="https://goo.gl/maps/AsoiCwjMHGVVVEsd8">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path d="M23.961 8.429c-.831.982-1.614 1.918-1.961 3.775v6.683l-4 2.479v-9.161c-.206-1.104-.566-1.885-1-2.539v11.475l-4-2.885v-13.069l1.577 1.138c-.339-.701-.577-1.518-.577-2.524l.019-.345-2.019-1.456-5.545 4-6.455-4v18l6.455 4 5.545-4 5.545 4 6.455-4v-11.618l-.039.047zm-17.961 12.936l-4-2.479v-13.294l4 2.479v13.294zm5-3.11l-4 2.885v-13.067l4-2.886v13.068zm9-18.255c-2.1 0-4 1.702-4 3.801 0 3.121 3.188 3.451 4 8.199.812-4.748 4-5.078 4-8.199 0-2.099-1.9-3.801-4-3.801zm0 5.5c-.828 0-1.5-.671-1.5-1.5s.672-1.5 1.5-1.5 1.5.671 1.5 1.5-.672 1.5-1.5 1.5z" />
              </svg>
            </a>
          </span>
          <span>
            <a href="mailto:feiradot@hotmail.com">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path d="M0 3v18h24v-18h-24zm6.623 7.929l-4.623 5.712v-9.458l4.623 3.746zm-4.141-5.929h19.035l-9.517 7.713-9.518-7.713zm5.694 7.188l3.824 3.099 3.83-3.104 5.612 6.817h-18.779l5.513-6.812zm9.208-1.264l4.616-3.741v9.348l-4.616-5.607z" />
              </svg>

            </a>
          </span>
          <span>
            <a href="tel:349999999999">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
              </svg>
            </a>
          </span>
        </address>
      </div>

      <div class="footer-sub">
        <h4>Trabalhe aqui</h4>

        <span>
          <a href="https://linkedin.com/in/feiradot">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z" />
            </svg>
          </a>
        </span>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>