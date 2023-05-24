<?php

require '../models/Vaga.php';

if (isset($_SERVER['HTTP_REFERER'])) {
    $referringFile = $_SERVER['HTTP_REFERER'];
    $dir = explode("Bauru%20Empregos%20Clone/", $referringFile);
    $lastDir = $dir[count($dir) - 1]; // conta diretórios a partir de root
}

$obj = new Vaga();

if ($lastDir == 'cadastrar-vaga.html') {
    session_start();

    try {
        $cargo = filter_input(INPUT_POST, 'cargo', FILTER_SANITIZE_STRING);
        $empresa = filter_input(INPUT_POST, 'empresa', FILTER_SANITIZE_STRING);
        $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
        $cidade = filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_STRING);
        $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_STRING);
        $contato = filter_input(INPUT_POST, 'contato', FILTER_SANITIZE_STRING);

        $obj->add('vagas_pendentes', $cargo, $empresa, $descricao, $cidade, $estado, $contato);

        // enviando email de confirmação
        if (preg_match('/^[a-z0-9]+@[a-z]+\.[a-z]{2,5}$/', $obj->getContato())) {
            require '../config/email.php';

            $mail->addAddress($obj->getContato());
            $mail->isHTML(true);

            $mail->Subject = 'Vaga enviada com sucesso!';
            $mail->Body = 'Muito obrigado por utilizar o Bauru Empregos! Sua vaga já foi enviada e está em revisão. Não se preocupe, te retornaremos aqui por e-mail assim que ela for aprovada e publicada.';
            $mail->send();


            $_SESSION['message'] = "<div class='alert alert-success'>Muito obrigado por utilizar o Bauru Empregos! Sua vaga já foi enviada e está em revisão. Não se preocupe, te retornaremos por e-mail assim que ela for aprovada e publicada.</div>";
        } else {
            $_SESSION['message'] = "<div class='alert alert-success'>Muito obrigado por utilizar o Bauru Empregos! Sua vaga já foi enviada e está em revisão. Não se preocupe, te enviaremos um SMS assim que estiver tudo pronto.</div>";
        }

        header(("Location: ../"));
    } catch (Exception $err) {
        $_SESSION['message'] = "<div class='alert alert-danger'>Contato inválido!</div>";
        header("Location: ../");
    }
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

    // enviando email de confirmação
    if (preg_match('/^[a-z0-9]+@[a-z]+\.[a-z]{2,5}$/', $vagaPendente['contato'])) {
        require '../config/email.php';

        $mail->addAddress($vagaPendente['contato']);
        $mail->isHTML(true);

        $mail->Subject = 'Vaga publicada!';
        $mail->Body = 'Muito obrigado por utilizar o Bauru Empregos, temos boas notícias para você! Sua vaga foi revisada e publicada com sucesso e já está disponível no Bauru Empregos. Esperamos que tenha uma boa experiência em nossa plataforma e que logo possa encontrar o colaborador que tanto procura.';
        $mail->send();
    }

    session_start();
    $_SESSION['admin-message'] = "<div class='text-success'>Vaga $id - \"$cargo\" publicada com sucesso!</div>";
    header("Location: ../admin/");
}
