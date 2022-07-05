<?php

session_start();
include_once('../secundario/config.php');

    //VER SE HÁ LOGIN ATIVO
    if(empty($_SESSION['usuario']) && empty($_SESSION['senha']))
    {
        header('Location: /login.php');
    }

        $barras = $_SESSION['id'];
        $sql = "SELECT * FROM produtos WHERE codigo_barras = '$barras'";
        $resultsql = $conexao->query($sql);
        $tabela = mysqli_fetch_assoc($resultsql);
        $nomefoto = $tabela['nome_foto'];

        //"EXCLUINDO" A FOTO DO PRODUTO
        if($nomefoto != null){
            $sqlfoto = "UPDATE produtos SET nome_foto = null WHERE codigo_barras = '$barras'";
            $resultfoto = $conexao->query($sqlfoto);
            $arquivo = "../fotos/produtos/".$nomefoto;
            $resultado = unlink($arquivo);
        }

        header('Location: ../secundario/dadosprodutocodigodebarras.php');



?>
