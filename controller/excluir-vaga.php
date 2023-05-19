<?php

require_once '../models/Vaga.php';

if (isset($_SERVER['HTTP_REFERER'])) {
    $referringFile = $_SERVER['HTTP_REFERER'];
    $dir = explode("Bauru%20Empregos%20Clone/", $referringFile);
    $lastDir = $dir[count($dir) - 1]; // conta diretórios a partir de root
}

$obj = new Vaga();
session_start();

if ($lastDir == 'admin/?vagas=cadastradas') {
    $id = $_POST['id'];

    $vaga = $obj->findById('vagas', $id);
    $cargo = $vaga['cargo'];

    $obj->delete('vagas', $id);

    $_SESSION['admin-message'] = "<div class='text-danger'>Vaga $id - \"$cargo\" excluída com sucesso!</div>";
    header("Location: ../admin/");
}

if ($lastDir == 'admin/?vagas=pendentes') {
    $id = $_POST['id'];

    $vaga = $obj->findById('vagas_pendentes', $id);
    $cargo = $vaga['cargo'];

    $obj->delete('vagas_pendentes', $id);

    $_SESSION['admin-message'] = "<div class='text-danger'>Vaga $id - \"$cargo\" excluída com sucesso!</div>";
    header("Location: ../admin/");
}
