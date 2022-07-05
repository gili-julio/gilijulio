<?php
    session_start();

    //IF PARA VER SE FOI SUBMITADO UM FORMULARIO COM DADOS DE USUARIO E DE SENHA
    if(isset($_POST['submit']) && !empty($_POST['usuario']) && !empty($_POST['senha']))
    {
        //PEGA O VALOR DOS DADOS DE USUARIO E SENHA 
        $usuario = strtolower($_POST['usuario']);
        $senha = strtolower($_POST['senha']);
    
        //IF DE TESTE PARA USUARIO EDITOR DE FOTOS
        if($usuario == 'vicunha' && $senha == 'vicunha')
        {
            $_SESSION['usuario'] = $usuario;
            $_SESSION['senha'] = $senha;
            $_SESSION['url'] = "fotos";
            header('Location: ../index.php');
        } else {
            //IF DE TESTE PARA USUARIO EDITOR GERAL
            if($usuario == 'v1cunh4atacadao' && $senha == 'v1cunh4atacadao')
            {
                $_SESSION['usuario'] = $usuario;
                $_SESSION['senha'] = $senha;
                $_SESSION['url'] = "geral";
                header('Location: ../index.php');
            } else {
                //IF DE TESTE PARA USUARIO AVARIA
                if($usuario == 'avaria' && $senha == 'avaria')
                {
                    $_SESSION['usuario'] = $usuario;
                    $_SESSION['senha'] = $senha;
                    $_SESSION['url'] = "avaria";
                    header('Location: ../index.php');
                } else {
                    $_SESSION['url'] = 'error';
                    header('Location: /editor/login.php');
                }
            }
            
        }
        
    } else {
        $_SESSION['url'] = 'error';
        header('Location: /editor/login.php');
    }




?>
