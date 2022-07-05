<?php

session_start();
include_once('../secundario/config.php');

require '../imagem/vendor/autoload.php';

use \App\Image\Resize;


    //VER SE HÁ LOGIN ATIVO
    if(empty($_SESSION['usuario']) && empty($_SESSION['senha']))
    {
        header('Location: /login.php');
    }

    //VERIFICAR SE HOUVE UM SUBMIT
    if(isset($_POST['update'])){
        $barras = $_SESSION['id'];

        //PARTE DE UPLOAD DA FOTO DO PRODUTO
        if(isset($_FILES['fotoproduto'])){
            $foto = $_FILES['fotoproduto'];
            move_uploaded_file($foto['tmp_name'],'../fotos/produtos/'.$foto['name']);
            $nomefoto = $foto['name'];
           
            //PARA REDIMENSIONAR PARA 480 pixels de largura (ficar mais leve o arquivo)
            $obResize = new Resize('../fotos/produtos/'.$foto['name']);
            $obResize->resize(480, -50);
            switch($foto['type']){
                case 'image/jpeg':
                    $obResize->save('../fotos/produtos/produto-'.$barras.'.jpeg');
                    $nomefoto = 'produto-'.$barras.'.jpeg';
                    $arquivo = '../fotos/produtos/'.$foto['name'];
                    unlink($arquivo);
                    break;
                
                case 'image/jpg':
                    $obResize->save('../fotos/produtos/produto-'.$barras.'.jpeg');
                    $nomefoto = 'produto-'.$barras.'.jpeg';
                    $arquivo = '../fotos/produtos/'.$foto['name'];
                    unlink($arquivo);
                    break;
                
                case 'image/gif':
                    $obResize->save('../fotos/produtos/produto-'.$barras.'.gif');
                    $nomefoto = 'produto-'.$barras.'.gif';
                    $arquivo = '../fotos/produtos/'.$foto['name'];
                    unlink($arquivo);
                    break;
                
                case 'image/png':
                    $obResize->save('../fotos/produtos/produto-'.$barras.'.jpeg');
                    $nomefoto = 'produto-'.$barras.'.jpeg';
                    $foto99 = str_replace( '.png' , '.jpeg' , $foto['name'] );
                    $arquivo = '../fotos/produtos/'.$foto99;
                    $arquivo2 = '../fotos/produtos/'.$foto['name'];
                    unlink($arquivo);
                    unlink($arquivo2);
                    break;
                    
            }
            
            $sqlfoto = "UPDATE produtos SET nome_foto = '$nomefoto' WHERE codigo_barras = '$barras'";
            $resultfoto = $conexao->query($sqlfoto);
        }

        header('Location: ../secundario/dadosprodutocodigodebarras.php?id='.$barras);
    } else {
        header('Location: ../secundario/dadosprodutocodigodebarras.php');
    }



?>
