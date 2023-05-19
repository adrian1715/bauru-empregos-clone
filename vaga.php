<?php

require_once 'models/Vaga.php';
$obj = new Vaga();
$vaga = $obj->findById('vagas', $_GET['id']);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $vaga['titulo'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="views/css/style.css">
</head>

<body>
    <?php require_once 'views/nav.html' ?>
    <br>
    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h1><?php echo $vaga['cargo'] ?></h1>
                    <p>Cadastrado em: <?php echo $vaga['data'] ?></p>
                    <p style="white-space: pre-line;"><?php echo $vaga['descricao'] ?></p>
                    <p>Contato: <?php echo $vaga['contato'] ?></p>
                    <p>Vaga em <?php echo $vaga['cidade'] ?></p>
                    <br>
                    <a href="vagas.php" class="text-decoration-none">Voltar para a lista de vagas</a>
                </div>

                <div class="col-6 col-md-4 col-xl-3 mx-auto p-5 bg-secondary text-center d-none">Ad banner</div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
</body>

</html>