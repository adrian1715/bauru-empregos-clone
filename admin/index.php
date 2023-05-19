<?php

require_once '../models/Vaga.php';

$obj = new Vaga;
if (isset($_GET['vagas'])) {
    $vagasValue = $_GET['vagas'];

    if ($_GET['vagas'] == 'cadastradas') {
        $vagas = $obj->showAll('vagas');
    }
    if ($_GET['vagas'] == 'pendentes') {
        $vagas = $obj->showAll('vagas_pendentes');
    }
}

session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-primary">
    <div class="container mt-5 border rounded px-4 py-3 bg-light">
        <h1>Admin Panel</h1>
        <?php
        if (isset($_SESSION['admin-message'])) {
            echo $_SESSION['admin-message'] . "<br>";
            unset($_SESSION['admin-message']);
        } ?>
        <form action="?vagas=<?php echo @$_GET['vagas'] ?>" class="<?php if (isset($_GET['vagas'])) : ?>d-none<?php endif ?>">
            <ul class="list-unstyled d-flex">
                <li class="p-2"><a href=""><button name="vagas" value="cadastradas" class="btn btn-dark">Vagas cadastradas</button></a></li>
                <li class="p-2"><a href=""><button name="vagas" value="pendentes" class="btn btn-dark">Vagas pendentes</button></a></li>
            </ul>
        </form>
        <?php if (isset($_GET['vagas'])) :
            if ($vagas) { ?>
                <div id="vagas">
                    <h2 class="fs-3">Todas as vagas <?php echo $vagasValue ?> no Bauru Empregos</h2>
                    <p>Clique no título para visualizar os detalhes.</p>
                    <?php $i = 0;
                    foreach ($vagas as $vaga) :
                        $dataVagaAtual = $vaga['data'];
                        $dataVagaAnterior = @$vagas[$i - 1]['data'];
                        $dataProximaVaga = @$vagas[$i + 1]['data'];
                        if ($i == 0 || $dataVagaAtual < $dataVagaAnterior) {
                            echo "<ul class='list-group list-group-flush d-block'><div class='fw-bold text-secondary mt-2'>$dataVagaAtual</div>";
                        } ?>
                        <li class="list-group-item d-inline-block col-md-11">
                            <a href="../vaga.php?id=<?php echo $vaga['id'] ?>" class="text-decoration-none"><?php echo $vaga['cargo'] ?></a>
                            <span class="float-end ms-2"><?php echo " " . $vaga['data'] ?></span>
                            <span class="float-end"><?php echo $vaga['cidade'] ?></span>
                        </li>
                        <?php if ($vagasValue == 'pendentes') : ?>
                            <form action="../controller/cadastrar-vaga.php" method="POST" class="d-inline-block">
                                <button class="btn btn-success ms-2 py-0 px-2" name="id" value="<?php echo $vaga['id'] ?>"> ✓</button>
                            </form>
                        <?php endif ?>
                        <form action="../controller/excluir-vaga.php" method="POST" class="d-inline-block">
                            <button class="btn btn-danger ms-2 py-0 px-2" name="id" value="<?php echo $vaga['id'] ?>">X</button>
                        </form>
                    <?php if ($dataVagaAtual > $dataProximaVaga) {
                            echo "</ul>";
                        }
                        $i++;
                    endforeach; ?>
                </div>
            <?php } else { ?>
                <h2 class="mt-3 h4">Sem vagas <?php echo $vagasValue ?> no momento!</h2>
            <?php } ?>
            <a href="../admin/"><button class="btn btn-primary mt-3">Voltar</button></a>
        <?php endif ?>
    </div>
</body>

</html>