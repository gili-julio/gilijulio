<?php
    session_start();

    //IF PARA VER SE HÁ DADOS DE USUARIO E DE SENHA
    if(!empty($_SESSION['usuario']) && !empty($_SESSION['senha']))
    {
        if($_SESSION['url'] == "Desconectado"){
            unset($_SESSION['usuario']);
            unset($_SESSION['senha']);
            header('Location: ../index.php');
        }
        //TIRA O VALOR DOS DADOS DE USUARIO E SENHA PARA DESCONECTAR
        unset($_SESSION['usuario']);
        unset($_SESSION['senha']);
        $_SESSION['url'] = "Desconectado";
        header('Location: ../index.php');

    } else {
        header('Location: ../index.php');
    }




?>