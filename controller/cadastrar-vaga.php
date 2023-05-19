<?php

require_once '../models/Vaga.php';

if (isset($_SERVER['HTTP_REFERER'])) {
    $referringFile = $_SERVER['HTTP_REFERER'];
    $dir = explode("Bauru%20Empregos%20Clone/", $referringFile);
    $lastDir = $dir[count($dir) - 1]; // conta diretórios a partir de root
}

$obj = new Vaga();

if ($lastDir == 'cadastrar-vaga.html') {
    $cargo = filter_input(INPUT_POST, 'cargo', FILTER_SANITIZE_STRING);
    $empresa = filter_input(INPUT_POST, 'empresa', FILTER_SANITIZE_STRING);
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
    $cidade = filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_STRING);
    $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_STRING);
    $contato = filter_input(INPUT_POST, 'contato', FILTER_SANITIZE_STRING);

    $obj->add('vagas_pendentes', $cargo, $empresa, $descricao, $cidade, $estado, $contato);

    session_start();
    $_SESSION['message'] = "<div class='alert alert-success'>Muito obrigado por utilizar o Bauru Empregos! Sua vaga já foi enviada e está em revisão. Não se preocupe, te retornaremos por e-mail assim que ela for aprovada e publicada.</div>";

    header(("Location: ../"));
}

if ($lastDir == 'admin/?vagas=pendentes') {
    $id = $_POST['id'];
    $vagaPendente = $obj->findById('vagas_pendentes', $id);

    $cargo = $vagaPendente['cargo'];
    $empresa = $vagaPendente['empresa'];
    $descricao = $vagaPendente['descricao'];
    $cidade = $vagaPendente['cidade'];
    $estado = $vagaPendente['estado'];
    $contato = $vagaPendente['contato'];

    $obj->add('vagas', $cargo, $empresa, $descricao, $cidade, $estado, $contato);
    $obj->delete('vagas_pendentes', $id);

    session_start();
    $_SESSION['admin-message'] = "<div class='text-success'>Vaga $id - \"$cargo\" publicada com sucesso!</div>";
    header("Location: ../admin/");
}
