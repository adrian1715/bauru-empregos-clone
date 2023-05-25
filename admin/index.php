<?php

require_once '../models/Vaga.php';

$obj = new Vaga;
if (isset($_GET['vagas'])) {
    $vagasValue = $_GET['vagas'];

    if ($_GET['vagas'] == 'cadastradas') {
        $tabela = 'vagas';
    }

    if ($_GET['vagas'] == 'pendentes') {
        $tabela = 'vagas_pendentes';
    }

    $resultsPerPage = 5;

    if (!isset($_GET['page'])) {
        $page = 1;
    } else {
        $page = $_GET['page'];
    }

    $start = ($page - 1) * $resultsPerPage;

    // query total
    $total = $obj->showAll($tabela);
    $totalResults = count($total);

    // query da pagina
    $stmt = $obj->pdo->query("SELECT id, cargo, descricao, empresa, contato, cidade, date_format(data, '%d/%m/%Y') as data FROM $tabela ORDER BY data DESC, id DESC LIMIT $start, $resultsPerPage");
    $stmt->execute();

    $vagas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalPages = ceil($totalResults / $resultsPerPage);
    $nextPage = $page + 1;
    $previousPage = $page - 1;
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

<body class="bg-secondary">
    <div class="container mt-5 border rounded px-4 pt-3 pb-4 bg-light">
        <h1>Admin Panel</h1>
        <?php
        if (isset($_SESSION['admin-message'])) {
            echo $_SESSION['admin-message'] . "<br>";
            unset($_SESSION['admin-message']);
        } ?>
        <form action="?vagas=<?php echo @$_GET['vagas'] ?>" class="<?php if (isset($_GET['vagas']) || isset($_GET['edit-id'])) : ?>d-none<?php endif ?>">
            <ul class="list-unstyled d-flex">
                <li class="p-2"><a href=""><button name="vagas" value="cadastradas" class="btn btn-dark">Vagas cadastradas</button></a></li>
                <li class="p-2"><a href=""><button name="vagas" value="pendentes" class="btn btn-dark">Vagas pendentes</button></a></li>
            </ul>
        </form>
        <?php if (isset($_GET['vagas'])) :
            if ($vagas) { ?>
                <div id="vagas">
                    <!-- exibindo as vagas -->
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

                        <!-- botões de editar e excluir -->
                        <?php if ($vagasValue == 'cadastradas') : ?>
                            <form action="?edit-id=<?php echo $vaga['id'] ?>" method="POST" class="d-inline-block">
                                <button class="btn btn-primary ms-2 py-0 px-1" name="id" value="<?php echo $vaga['id'] ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 18">
                                        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                                    </svg>
                                </button>
                            </form>
                        <?php endif ?>
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
        <?php endif ?>

        <!-- edit form interface -->
        <?php if (isset($_GET['edit-id'])) :
            $id = $_GET['edit-id'];
            $vaga = $obj->findById('vagas', $id); ?>
            <h2 class="fs-3 mt-2">Editar vaga</h2>
            <form action="../controller/editar-vaga.php" class="col-md-6">
                <input type="hidden" name="id" value="<?php echo $vaga['id'] ?>">
                <div class="mb-2 d-flex">
                    <label for="" class="form-label col-2 ps-4 me-2">Cargo</label>
                    <input type="text" class="form-control" name="novo-cargo" value="<?php echo $vaga['cargo'] ?>">
                </div>
                <div class="mb-2 d-flex">
                    <label for="" class="form-label col-2 ps-4 me-2">Empresa</label>
                    <input type="text" class="form-control" name="nova-empresa" value="<?php echo $vaga['empresa'] ?>">
                </div>
                <div class="mb-2 d-flex d-flex align-items">
                    <label for="" class="form-label col-2 ps-4 me-2">Descrição</label><textarea type="text" class="form-control" name="nova-descricao" value=""><?php echo $vaga['descricao'] ?></textarea>
                </div>
                <div class="mb-2 d-flex">
                    <label for="" class="form-label col-2 ps-4 me-2">Cidade</label>
                    <input type="text" class="form-control" name="nova-cidade" value="<?php echo $vaga['cidade'] ?>">
                    <label for="" class="form-label ps-4 ms-3 me-2">Estado</label>
                    <select name="novo-estado" id="" class="form-select" required>
                        <option value="<?php echo $vaga['estado'] ?>" selected hidden><?php echo $vaga['estado'] ?></option>
                        <option value="AC">Acre</option>
                        <option value="AL">Alagoas</option>
                        <option value="AP">Amapá</option>
                        <option value="AM">Amazonas</option>
                        <option value="BA">Bahia</option>
                        <option value="CE">Ceará</option>
                        <option value="DF">Distrito Federal</option>
                        <option value="ES">Espírito Santo</option>
                        <option value="GO">Goiás</option>
                        <option value="MA">Maranhão</option>
                        <option value="MT">Mato Grosso</option>
                        <option value="MS">Mato Grosso do Sul</option>
                        <option value="MG">Minas Gerais</option>
                        <option value="PA">Pará</option>
                        <option value="PB">Paraíba</option>
                        <option value="PR">Paraná</option>
                        <option value="PE">Pernambuco</option>
                        <option value="PI">Piauí</option>
                        <option value="RJ">Rio de Janeiro</option>
                        <option value="RN">Rio Grande do Norte</option>
                        <option value="RS">Rio Grande do Sul</option>
                        <option value="RO">Rondônia</option>
                        <option value="RR">Roraima</option>
                        <option value="SC">Santa Catarina</option>
                        <option value="SP">São Paulo</option>
                        <option value="SE">Sergipe</option>
                        <option value="TO">Tocantins</option>
                    </select>
                </div>
                <div class="mb-2 d-flex">
                    <label for="" class="form-label col-2 ps-4 me-2">Contato</label>
                    <input type="text" class="form-control" name="novo-contato" value="<?php echo $vaga['contato'] ?>">
                </div>
                <br>
                <button type="submit" class="btn btn-success float-end">Confirmar alterações</button>
            </form>
            <a href="?vagas=cadastradas"><button class="btn btn-dark">Voltar</button></a>
        <?php endif ?>
        <?php if ($_GET) : ?>
            <br <?php if (isset($_GET['edit-id'])) echo "class='d-none'" ?>>
            <a href="../admin/"><button class="btn btn-primary">Voltar ao menu</button></a>
        <?php endif ?>

        <!-- pagination -->
        <?php if (isset($_GET['vagas'])) : ?>
            <div id="pagination" class="d-inline-block mt-3">
                <div class="container">
                    <nav aria-label="Page navigation">
                        <ul class="pagination m-0">
                            <?php if ($page != 1) : ?>
                                <li class="page-item"><a href="?vagas=<?php echo $vagasValue ?>&page=<?php echo $page - 1 ?>" class="page-link">Previous</a></li>
                            <?php endif ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                <li class="<?php if ($page == $i) : ?>disabled<?php endif ?> page-item"><a href="?vagas=<?php echo $vagasValue ?>&page=<?php echo $i ?>" class="page-link"><?php echo $i ?></a></li>
                            <?php endfor ?>

                            <?php if ($page < $totalPages) : ?>
                                <li class="page-item"><a href="?vagas=<?php echo $vagasValue ?>&page=<?php echo $page + 1 ?>" class="page-link" disabled>Next</a></li>
                            <?php endif ?>
                        </ul>
                    </nav>
                </div>
            </div>
        <?php endif ?>
    </div>
</body>

</html>