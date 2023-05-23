<?php

require_once 'config/config.php';

$stmt = $pdo->prepare("SELECT id, cargo, descricao, empresa, contato, cidade, date_format(data, '%d/%m/%Y') as data FROM vagas ORDER BY data DESC, id DESC LIMIT 10");
$stmt->execute();
$vagas = $stmt->fetchAll();

session_start();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home - Desempregados</title>
  <link rel="icon" href="https://www.bauruempregos.com.br/assets/logotipo-dedfa997568411eebcd01af3fd30baf8.png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous" />
  <link rel="stylesheet" href="views/css/style.css" />
</head>

<body>
  <?php require_once 'views/nav.html' ?>
  <br>
  <main>
    <div class="container">
      <?php if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
      } ?>
      <section id="sec-1">
        <h1 class="fs-4">Vagas de emprego em Bauru e região</h1>
        <p>O Bauru Empregos divulga GRATUITAMENTE vagas de emprego em Bauru e região.</p>
        <p>Quer publicar um anúncio? Clique aqui e saiba como.</p>
        <p>Quer ver as vagas publicadas? Veja abaixo os anúncios recentes ou clique aqui para ver todos os anúncios.
        </p>
      </section>
      <br>
      <section id="sec-2" class="row">
        <div class="col-6 col-md-8">
          <?php if ($vagas) { ?>
            <h1 class="mb-3 fs-4">Últimas vagas publicadas no Bauru Empregos</h1>
            <ul class="list-unstyled">
              <?php $i = 0;
              foreach ($vagas as $vaga) : ?>
                <?php
                $dataVagaAtual = $vaga['data'];
                $dataVagaAnterior = @$vagas[$i - 1]['data'];
                $dataProximaVaga = @$vagas[$i + 1]['data'];

                if ($dataVagaAtual < $dataVagaAnterior || $i == 0) {
                  echo "<div class='mt-2 fw-bold text-secondary'>" . $dataVagaAtual . "</div>";
                }
                ?>
                <li>
                  <a href="vaga.php?id=<?php echo $vaga['id'] ?>">
                    <?php echo $vaga['cargo']; ?>
                  </a>
                  <span class="float-end">
                    <?php
                    $cidade = explode('-', $vaga['cidade'])[0];
                    echo $cidade;
                    $i++;
                    ?>
                  </span>
                </li>
                <?php if ($dataProximaVaga == $dataVagaAtual) { ?>
                  <hr class="my-1">
              <?php }
              endforeach ?>
              <br>
              <li><a href="vagas.php">Ver todas as vagas</a></li>
            </ul>
        </div>
      <?php } else {
            echo "<h1 class='fs-4'>Sem vagas disponíveis no momento!</h1>";
            echo "<div>Cadastre sua vaga <a href='cadastrar-vaga.html'>aqui</a>!</div>";
          } ?>
      <div class="col-6 col-md-4 col-xl-3 mx-auto p-5 bg-secondary text-center d-none">Ad banner</div>
      </section>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
</body>

</html>