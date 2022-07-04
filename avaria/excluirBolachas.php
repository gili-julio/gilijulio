<?php

session_start();
include_once('../secundario/config.php');

    //VER SE HÁ LOGIN ATIVO
    if(empty($_SESSION['usuario']) && empty($_SESSION['senha']))
    {
        header('Location: ../editor/login.php');
        
    } else {

        //TESTA SE É PRA "EDITAR" ALGUM FORNECEDOR
        if(isset($_GET['editarfornecedor'])){
            $fornecedor = $_GET['editarfornecedor'];
            $sqlbuscar = "SELECT * FROM avaria_bolachas WHERE nome = '$fornecedor'";
            $resultbuscar = $conexao->query($sqlbuscar);
            $tabelabuscar = mysqli_fetch_assoc($resultbuscar);
            $_SESSION['fornecedor_avaria'] = $fornecedor;
                switch($tabelabuscar['cor']){
                    case 'Vermelho':
                        $_SESSION['cor_avaria'] = '1';
                        break;
                    case 'Amarelo':
                        $_SESSION['cor_avaria'] = '2';
                        break;
                    case 'Verde':
                        $_SESSION['cor_avaria'] = '3';
                        break;
                    case 'Azul':
                        $_SESSION['cor_avaria'] = '4';
                        break;
                    case 'Preto':
                        $_SESSION['cor_avaria'] = '5';
                        break;
                }
            $_SESSION['editar'] = 'sim';
            header('Location: avariaBolachas.php');
        } else {
            //EXCLUIR UM FORNECEDOR
            if(isset($_GET['excluirfornecedor'])){
                
                //EXCLUINDO REGISTRO DA TABELA
                    $fornecedor = $_GET['excluirfornecedor'];
                    $sqlexcluir = "DELETE FROM avaria_bolachas WHERE (identificador = '$fornecedor')";
                    $resultexcluir = $conexao->query($sqlexcluir);
                    header('Location: avariaBolachas.php');
                    
                
            } else {
                //EXCLUIR TODOS OS FORNECEDORES
                if(isset($_GET['excluirtudo'])){
                    $sqlbusca = "SELECT * FROM avaria_bolachas GROUP BY identificador";
                    $resultbusca = $conexao->query($sqlbusca);
                    while($tabela = mysqli_fetch_assoc($resultbusca)){
                        $fornecedor = $tabela['identificador'];
                        $sqlexcluir = "DELETE FROM avaria_padaria WHERE (identificador = '$fornecedor')";
                        $resultexcluir = $conexao->query($sqlexcluir);
                    }
                    header('Location: avariaBolachas.php');
                    
                } else {
                    header('Location: avariaBolachas.php');
                    
                }
            }
        }
    
    }



?>