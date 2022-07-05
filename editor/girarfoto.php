<?php

session_start();
include_once('../secundario/config.php');

require '../imagem/vendor/autoload.php';

use \App\Image\Girar;


    //VER SE HÁ LOGIN ATIVO
    if(empty($_SESSION['usuario']) && empty($_SESSION['senha']))
    {
        header('Location: /editor/login.php');
    } else {

            $barras = $_SESSION['id'];
            $sqlbusca = "SELECT * FROM produtos WHERE codigo_barras = '$barras'";
            $resultbusca = $conexao->query($sqlbusca);
            $tabelabusca = mysqli_fetch_assoc($resultbusca);
            $nomefoto = $tabelabusca['nome_foto'];
           
            //PARA REDIMENSIONAR PARA 480 pixels de largura (ficar mais leve o arquivo)
            $obResize = new Girar('../fotos/produtos/'.$nomefoto);
            $obResize->resize(480, -50);
            unlink('../fotos/produtos/'.$nomefoto);
            $obResize->save($nomefoto);
            
            $sqlfoto = "UPDATE produtos SET nome_foto = '$nomefoto' WHERE codigo_barras = '$barras'";
            $resultfoto = $conexao->query($sqlfoto);

        header('Location: ../secundario/dadosprodutocodigodebarras.php?id='.$barras);
        }



?>
