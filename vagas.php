<?php

require_once 'models/Vaga.php';

$resultsPerPage = 5;

if (!isset($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}

$start = ($page - 1) * $resultsPerPage;

// query total
$obj = new Vaga();
$total = $obj->showAll('vagas');
$totalResults = count($total);

// query da pagina
$stmt = $obj->pdo->prepare("SELECT id, cargo, descricao, empresa, contato, cidade, date_format(data, '%d/%m/%Y') as data FROM vagas ORDER BY data DESC, id DESC LIMIT $start, $resultsPerPage");
$stmt->execute();

$vagas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalPages = ceil($totalResults / $resultsPerPage);
$nextPage = $page + 1;
$previousPage = $page - 1;

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vagas - Bauru Empregos</title>
    <link rel="icon" href="https://www.bauruempregos.com.br/assets/logotipo-dedfa997568411eebcd01af3fd30baf8.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous" />
    <link rel="stylesheet" href="views/css/style.css" />
</head>

<body>
    <?php require_once 'views/nav.html' ?>
    <br>
    <main>
        <div class="container">
            <div class="row">
                <div id="vagas" class="col-md-8">
                    <h1 class="fs-2">Todas as vagas publicadas no Bauru Empregos</h1>
                    <p>Clique no título para visualizar os detalhes.</p>
                    <?php $i = 0;
                    foreach ($vagas as $vaga) {
                        $dataVagaAtual = $vaga['data'];
                        $dataVagaAnterior = @$vagas[$i - 1]['data'];
                        $dataProximaVaga = @$vagas[$i + 1]['data'];
                        if ($i == 0 || $dataVagaAtual < $dataVagaAnterior) {
                            echo "<ul class='list-group list-group-flush'><div class='fw-bold text-secondary mt-2'>$dataVagaAtual</div>";
                        } ?>
                        <li class="list-group-item">
                            <a href="vaga.php?id=<?php echo $vaga['id'] ?>" class="text-decoration-none"><?php echo $vaga['cargo'] ?></a>
                            <span class="float-end"><?php echo $vaga['cidade'] ?></span>
                        </li>
                    <?php if ($dataVagaAtual > $dataProximaVaga) {
                            echo "</ul>";
                        }
                        $i++;
                    } ?>
                </div>

                <div class="col-6 col-md-4 col-xl-3 mx-auto p-5 bg-secondary text-center d-none">Ad banner</div>
            </div>
        </div>
    </main>

    <div id="pagination">
        <div class="container">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php if ($page != 1) : ?>
                        <li class="page-item"><a href="?page=<?php echo $page - 1 ?>" class="page-link">Previous</a></li>
                    <?php endif ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="<?php if ($page == $i) : ?>disabled<?php endif ?> page-item"><a href="?page=<?php echo $i ?>" class="page-link"><?php echo $i ?></a></li>
                    <?php endfor ?>

                    <?php if ($page < $totalPages) : ?>
                        <li class="page-item"><a href="?page=<?php echo $page + 1 ?>" class="page-link" disabled>Next</a></li>
                    <?php endif ?>
                </ul>
            </nav>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
</body>

</html>