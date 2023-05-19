<?php

require_once '../models/Vaga.php';

$obj = new Vaga;
$id = $_GET['id'];
$cargo = $_GET['novo-cargo'];
$empresa = $_GET['nova-empresa'];
$descricao = $_GET['nova-descricao'];
$cidade = $_GET['nova-cidade'];
$estado = $_GET['novo-estado'];
$contato = $_GET['novo-contato'];

$obj->edit($id, 'cargo', $cargo);
$obj->edit($id, 'empresa', $empresa);
$obj->edit($id, 'descricao', $descricao);
$obj->edit($id, 'cidade', $cidade);
$obj->edit($id, 'estado', $estado);
$obj->edit($id, 'contato', $contato);

session_start();
$_SESSION['admin-message'] = "<div class='text-success'>Vaga $id - \"$cargo\" editada com sucesso!</div>";
header("Location: ../admin/");
