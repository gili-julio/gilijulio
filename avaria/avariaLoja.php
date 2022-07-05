<?php
session_start();
include_once('../secundario/config.php');

//TEM QUE TER AS VARIAVEIS DE SESSÃO
if(!empty($_SESSION['usuario']) && !empty($_SESSION['senha'])){
//TESTAR LOGIN DE AVARIA
if(($_SESSION['usuario'] == 'avaria') && ($_SESSION['senha'] == 'avaria')){
    
    
    $sqlavaria = "SELECT * FROM avaria_loja";
    $resultavaria = $conexao->query($sqlavaria);
    
    //VARIAVEL PARA DETERMINAR EDIÇÃO DE ALGUM ITEM
        if(empty($_SESSION['editar'])){
            $_SESSION['editar'] = 'nao';
        }
        $editar = $_SESSION['editar'];
        if($editar == 'sim'){
            $barras = $_SESSION['barras_avaria'];
            $descricao = $_SESSION['descricao_avaria'];
            $quantidade = $_SESSION['qtd_avaria'];
            $motivo = $_SESSION['motivo_avaria'];
        } else {
            $_SESSION['barras_avaria'] = 'nada';
            $_SESSION['descricao_avaria'] = 'nada';
            $_SESSION['qtd_avaria'] = 'nada';
            $_SESSION['motivo_avaria'] = 'nada';
        }
        
        
    //AO CLICAR NO BOTÃO ADICIONAR
    if(isset($_POST['submit'])){
        //VERIFICA SE EXISTE CODIGO DE BARRAS E MOTIVO E QUANTIDADE
        if(!empty($_POST['codigobarras']) && !empty($_POST['motivo']) && !empty($_POST['quantidade'])){
            
            $valormotivo = $_POST['motivo'];
            $barras = str_pad($_POST['codigobarras'], 14, '0', STR_PAD_LEFT);
            $quantidade = $_POST['quantidade'];
            if($editar == 'sim'){
                $descricao = $_POST['descricao'];
                $_SESSION['editar'] = 'nao';
            } else {
                $sqlbuscar = "SELECT * FROM produtos_avaria WHERE codigo_barras = '$barras'";
                $resultbuscar = $conexao->query($sqlbuscar);
                $tabelabuscar = mysqli_fetch_assoc($resultbuscar);
                if(mysqli_num_rows($resultbuscar) == 0) {
                    $descricao = 'Produto não encontrado';
                } else {
                    $descricao = $tabelabuscar['descricao'];
                }
            }
            //TESTA SE HÁ UM MOTIVO VALIDO
            if($valormotivo == "999"){
                $_SESSION['erro'] = "error";
                unset($_POST);
                header('Location: /avariaLoja.php');
            } else {
                
                //MUDA O MOTIVO DE ACORDO COM O CASO
                switch($valormotivo){
                    case '1':
                        $motivo = 'Vencimento';
                        break;
                    case '2':
                        $motivo = 'Embalagem danificada';
                        break;
                    case '3':
                        $motivo = 'Má manipulação do Repositor';
                        break;
                    case '4':
                        $motivo = 'Mofado';
                        break;
                    case '5':
                        $motivo = 'Avaria do Cliente';
                        break;
                    case '6':
                        $motivo = 'Estragado antes do Vencimento';
                        break;
                    case '7':
                        $motivo = 'Roido por Gato/Rato';
                        break;
                    case '8':
                        $motivo = 'Outros';
                        break;
                }
                
                    
                    //TESTA SE HÁ ESSE CODIGO DE BARRAS NA TABELA AVARIA
                    $sqlteste = "SELECT * FROM avaria_loja WHERE codigo_barras = '$barras'";
                    $resultteste = $conexao->query($sqlteste);
                    if(mysqli_num_rows($resultteste) == 0){
                        $sqllimite = "SELECT * FROM avaria_padaria";
                        $resultlimite = $conexao->query($sqllimite);
                        if(mysqli_num_rows($resultlimite) >= 43){
                            $_SESSION['erro'] = "limite";
                            unset($_POST);
                        } else {
                            //ADICIONAR VALORES DA AVARIA NA TABELA
                            $sqladd = "INSERT INTO avaria_loja (codigo_barras, descricao, quantidade, motivo) VALUES ('$barras', '$descricao', '$quantidade', '$motivo')";
                            $resultadd = $conexao->query($sqladd);
                            $_SESSION['erro'] = "sucesso";
                            unset($_POST);
                        }
                        header('Location: /avariaLoja.php');
                    } else {
                        $_SESSION['erro'] = "existe";
                        unset($_POST);
                        header('Location: /avariaLoja.php');
                    }
                
            }
            
        } else {
            $_SESSION['erro'] = "error";
            unset($_POST);
            header('Location: /avariaLoja.php');
        }
        $erro = $_SESSION['erro'];
    } else {
        $erro = $_SESSION['erro'];
        $_SESSION['erro'] = 'corrigido';
    }
    
    
    
    //EVITAR ERROS
    if (empty($_SESSION['erro'])) {
        $_SESSION['erro'] = 'NENHUM';
        $erro = $_SESSION['erro'];
    }
    //SÓ PRA VERIFICAR SE VEM DE ALGUM ERRO
    unset($_SESSION['id']);
} else {
    header('Location: ../editor/login.php');
}
} else {
    header('Location: ../editor/login.php');
}

?>


<!doctype html>
<html lang="pt-br">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="../css.css" rel="stylesheet">
    <link rel="shortcut icon" type="imagex/png" href="../fotos/atacadaologo.png">
    <link href="../carregamento/carregar.css" rel="stylesheet">

    <title>Avaria > Loja</title>
</head>

<body class="cimainicio">
    
<!-- início do preloader -->
  <div id="preloader">
    <div class="inner">
       <!-- HTML DA ANIMAÇÃO MUITO LOUCA DO SEU PRELOADER! -->
       <div class="bolas">
          <div></div>
          <div></div>
          <div></div>                    
       </div>
    </div>
</div>
<!-- fim do preloader --> 
    
    <div class="w-100 container">
        <div class="d-inline-block col-12 col-lg-3">
            <a class="" href="../index.php">
                <img src="../fotos/atacadaologo.png" alt="logo do atacadão" width="250" height="120" class="">
            </a>
        </div>
        <div class='d-inline-block col-12 col-lg-3 offset-lg-9 text-white text-end' >
            <a href="../index.php" class="bred d-inline-block">
                Voltar
            </a>
        </div>
    </div>
    <br><br>
        <?php
        if ($erro == "error") {
            echo "<div class='alert alert-info col-4 offset-4 alert-dismissible fade show text-center' role='alert'>
                    <strong><h3>Erro ao adicionar</h3></strong>
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }
        if ($erro == "existe") {
            echo "<div class='alert alert-info col-4 offset-4 alert-dismissible fade show text-center' role='alert'>
                    <strong><h3>Código de barras já existe</h3></strong>
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }
        ?>
    <div class="text-center container">
        <table class="table text-start">
                    <tbody class="">
                        <tr class="borda">
                            <form action="avariaLoja.php" method="POST">
                                <?php
                                    if($editar == 'nao'){
                                        echo "<th colspan='2'>
                                            <input type='number' class='form-control text-center' name='codigobarras' autocomplete='off' placeholder='Código de Barras'>
                                        </th>
                                        <th>
                                            <input type='text' class='form-control text-center' name='quantidade' autocomplete='off' placeholder='Quantidade'>
                                        </th>
                                        <th>
                                            <select class='form-select text-start' id='Select' name='motivo' >
                                                    <option value='999'>Motivo da Avaria</option>
                                                    <option value='1'>Vencimento</option>
                                                    <option value='2'>Embalagem danificada</option>
                                                    <option value='3'>Má manipulação do Repositor</option>
                                                    <option value='4'>Mofado</option>
                                                    <option value='5'>Avaria do Cliente</option>
                                                    <option value='6'>Estragado antes do Vencimento</option>
                                                    <option value='7'>Roido por Gato/Rato</option>
                                                    <option value='8'>Outros</option>
                                            </select>
                                        </th>
                                        <th class='text-end'>
                                            <input class='bgreen' type='submit' name='submit' id='submit' value='Adicionar'>
                                        </th>";
                                    } else {
                                        echo "<th>
                                            <input type='number' class='form-control text-center' name='codigobarras' autocomplete='off' placeholder='Código de Barras' value='$barras'>
                                        </th>
                                        <th>
                                            <input type='text' class='form-control text-center' name='descricao' autocomplete='off' placeholder='Descrição' value='$descricao'>
                                        </th>
                                        <th>
                                            <input type='text' class='form-control text-center' name='quantidade' autocomplete='off' placeholder='Quantidade' value='$quantidade'>
                                        </th>
                                        <th>
                                            <select class='form-select text-start' id='Select' name='motivo'>
                                                    ";
                                                    switch($motivo){
                                                        case '1':
                                                            echo "  
                                                                    <option value='1'>Vencimento</option>
                                                                    <option value='2'>Embalagem danificada</option>
                                                                    <option value='3'>Má manipulação do Repositor</option>
                                                                    <option value='4'>Mofado</option>
                                                                    <option value='5'>Avaria do Cliente</option>
                                                                    <option value='6'>Estragado antes do Vencimento</option>
                                                                    <option value='7'>Roido por Gato/Rato</option>
                                                                    <option value='8'>Outros</option>";
                                                            break;
                                                        case '2':
                                                            echo "  
                                                                    <option value='2'>Embalagem danificada</option>
                                                                    <option value='1'>Vencimento</option>
                                                                    <option value='3'>Má manipulação do Repositor</option>
                                                                    <option value='4'>Mofado</option>
                                                                    <option value='5'>Avaria do Cliente</option>
                                                                    <option value='6'>Estragado antes do Vencimento</option>
                                                                    <option value='7'>Roido por Gato/Rato</option>
                                                                    <option value='8'>Outros</option>";
                                                            break;
                                                        case '3':
                                                            echo "  
                                                                    <option value='3'>Má manipulação do Repositor</option>
                                                                    <option value='1'>Vencimento</option>
                                                                    <option value='2'>Embalagem danificada</option>
                                                                    <option value='4'>Mofado</option>
                                                                    <option value='5'>Avaria do Cliente</option>
                                                                    <option value='6'>Estragado antes do Vencimento</option>
                                                                    <option value='7'>Roido por Gato/Rato</option>
                                                                    <option value='8'>Outros</option>";
                                                            break;
                                                        case '4':
                                                            echo "  
                                                                    <option value='4'>Mofado</option>
                                                                    <option value='1'>Vencimento</option>
                                                                    <option value='2'>Embalagem danificada</option>
                                                                    <option value='3'>Má manipulação do Repositor</option>
                                                                    <option value='5'>Avaria do Cliente</option>
                                                                    <option value='6'>Estragado antes do Vencimento</option>
                                                                    <option value='7'>Roido por Gato/Rato</option>
                                                                    <option value='8'>Outros</option>";
                                                            break;
                                                        case '5':
                                                            echo "  
                                                                    <option value='5'>Avaria do Cliente</option>
                                                                    <option value='1'>Vencimento</option>
                                                                    <option value='2'>Embalagem danificada</option>
                                                                    <option value='3'>Má manipulação do Repositor</option>
                                                                    <option value='4'>Mofado</option>
                                                                    <option value='6'>Estragado antes do Vencimento</option>
                                                                    <option value='7'>Roido por Gato/Rato</option>
                                                                    <option value='8'>Outros</option>";
                                                            break;
                                                        case '6':
                                                            echo "  
                                                                    <option value='6'>Estragado antes do Vencimento</option>
                                                                    <option value='1'>Vencimento</option>
                                                                    <option value='2'>Embalagem danificada</option>
                                                                    <option value='3'>Má manipulação do Repositor</option>
                                                                    <option value='4'>Mofado</option>
                                                                    <option value='5'>Avaria do Cliente</option>
                                                                    <option value='7'>Roido por Gato/Rato</option>
                                                                    <option value='8'>Outros</option>";
                                                            break;
                                                        case '7':
                                                            echo "  
                                                                    <option value='7'>Roido por Gato/Rato</option>
                                                                    <option value='1'>Vencimento</option>
                                                                    <option value='2'>Embalagem danificada</option>
                                                                    <option value='3'>Má manipulação do Repositor</option>
                                                                    <option value='4'>Mofado</option>
                                                                    <option value='5'>Avaria do Cliente</option>
                                                                    <option value='6'>Estragado antes do Vencimento</option>
                                                                    <option value='8'>Outros</option>";
                                                            break;
                                                        case '8':
                                                            echo "  
                                                                    <option value='8'>Outros</option>
                                                                    <option value='1'>Vencimento</option>
                                                                    <option value='2'>Embalagem danificada</option>
                                                                    <option value='3'>Má manipulação do Repositor</option>
                                                                    <option value='4'>Mofado</option>
                                                                    <option value='5'>Avaria do Cliente</option>
                                                                    <option value='6'>Estragado antes do Vencimento</option>
                                                                    <option value='7'>Roido por Gato/Rato</option>
                                                                    ";
                                                            break;
                                                    }
                                                    echo "
                                                    
                                            </select>
                                        </th>
                                        <th class='text-end'>
                                            <input class='bgreen' type='submit' name='submit' id='submit' value='Adicionar'>
                                        </th>";
                                    }
                                ?>
                            </form>
                        </tr>
                        <tr>
                            <?php 
                                if(mysqli_num_rows($resultavaria) == 0){
                                    echo "<tr><th class='text-center' colspan='5'> Nenhum produto adicionado ainda</th> </tr>";
                                } else {
                                    $linhas = mysqli_num_rows($resultavaria);
                                    echo "<tr class='text-center align-middle'>";
                                    echo "<th colspan='5'>".$linhas."/43 linhas adicionadas</th> ";
                                    echo "</tr>";
                                    echo "<tr class='text-center align-middle'>";
                                    echo "<th> <strong>Código</strong> </th>";
                                    echo "<th> <strong>Descrição</strong> </th>";
                                    echo "<th> <strong>Quantidade</strong> </th>";
                                    echo "<th> <strong>Motivo</strong> </th>";
                                    echo "<th> <a class='bred d-inline-block' href='excluirLoja.php'>Limpar</a></th>";
                                    echo "</tr>";
                                    while($tabelaavaria = mysqli_fetch_assoc($resultavaria)){
                                        $barras = $tabelaavaria['codigo_barras'];
                                        echo "<tr>";
                                        echo "<th>".$tabelaavaria['codigo_barras']."</th>";
                                        echo "<th>".$tabelaavaria['descricao']."</th>";
                                        echo "<th class='text-center'>".$tabelaavaria['quantidade']."</th>";
                                        echo "<th class='text-center'>".$tabelaavaria['motivo']."</th>";
                                        echo "<th class='text-center'>
                                        <a class='bblue d-inline-block' href='excluirLoja.php?editar=$barras'>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-fill' viewBox='0 0 16 16'>
                                          <path d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z'/>
                                        </svg>
                                        </a>
                                        <a class='bred d-inline-block' href='excluirLoja.php?id=$barras'>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>
                                          <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z'/>
                                        </svg>
                                        </a>
                                        </th>";
                                        echo "</tr>";
                                    }
                                }
                            ?>
                        </tr>
                    </tbody>
                </table>
    </div>
    <br><br>
    <?php
        if(mysqli_num_rows($resultavaria) != 0){
            echo "<div class='text-center container'>
                <a href='/excel/gerar.php' class='bgreen d-inline-block'>Gerar Arquivo Excel</a>
            </div>";
        }
    ?>
    <br><br>
    <br><br>


    <div class="fixed-bottom"><strong>Website por: Giliardo Júlio (
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
          <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
        </svg> gili.julio )</strong>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="../carregamento/preloader.js"></script>
    
</body>

</html>
