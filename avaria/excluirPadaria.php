<?php

session_start();
include_once('../secundario/config.php');

    //VER SE HÁ LOGIN ATIVO
    if(empty($_SESSION['usuario']) && empty($_SESSION['senha']))
    {
    } else {

        //TESTA SE É PRA "EDITAR" ALGUM ITEM DA TABELA AVARIA
        if(isset($_GET['editar'])){
            $barras = $_GET['editar'];
            $sqlbuscar = "SELECT * FROM avaria_padaria WHERE codigo_barras = '$barras'";
            $resultbuscar = $conexao->query($sqlbuscar);
            $tabelabuscar = mysqli_fetch_assoc($resultbuscar);
            $_SESSION['barras_avaria'] = $barras;
            $_SESSION['descricao_avaria'] = $tabelabuscar['descricao'];
            $_SESSION['qtd_avaria'] = $tabelabuscar['quantidade'];
                switch($tabelabuscar['destino']){
                    case 'Padaria':
                        $_SESSION['destino_avaria'] = '1';
                        break;
                    case 'Restaurante':
                        $_SESSION['destino_avaria'] = '2';
                        break;
                    case 'Açougue':
                        $_SESSION['destino_avaria'] = '3';
                        break;
                }
            $_SESSION['editar'] = 'sim';
            $sqlexcluir = "DELETE FROM avaria_padaria WHERE (codigo_barras = '$barras')";
            $resultexcluir = $conexao->query($sqlexcluir);
        } else {
            //TESTA SE HÁ GET (EXCLUIR 1 OU TODOS)
            if(isset($_GET['id'])){
                
                //EXCLUINDO REGISTRO DA TABELA
                    $barras = $_GET['id'];
                    $sqlexcluir = "DELETE FROM avaria_padaria WHERE (codigo_barras = '$barras')";
                    $resultexcluir = $conexao->query($sqlexcluir);
                
            } else {
                $sqlbusca = "SELECT * FROM avaria_padaria GROUP BY codigo_barras";
                $resultbusca = $conexao->query($sqlbusca);
                while($tabela = mysqli_fetch_assoc($resultbusca)){
                    $barras = $tabela['codigo_barras'];
                    $sqlexcluir = "DELETE FROM avaria_padaria WHERE (codigo_barras = '$barras')";
                    $resultexcluir = $conexao->query($sqlexcluir);
                }
            }
        }
    
    }
        header('Location: /avaria/avariaPadaria.php');



?>
