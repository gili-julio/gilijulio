<?php
session_start();
//VER SE HÁ LOGIN ATIVO
if (empty($_SESSION['usuario']) && empty($_SESSION['senha'])) {
    header('Location: login.php');
}

//VERIFICA SE HÁ UM ID PARA A PAGINA
if (!empty($_SESSION['id'])) {
    //INCLUI O CÓDIGO DE CONEXÃO COM O BD
    include_once('../secundario/config.php');

    //PEGA AS INFORMAÇÕES DO PRODUTO DENTRO DO BANCO DE DADOS
    $barras = $_SESSION['id'];
    $_SESSION['barras'] = $barras;
    $sql = "SELECT * FROM produtos WHERE codigo_barras = '$barras'";
    $result = $conexao->query($sql);

    if (mysqli_num_rows($result) == 0) {
        $_SESSION['url'] = "error";
        header('Location: editorgeral.php');
    } else {
        $_SESSION['url'] = "sucesso";
    }

    /* Transformar o result em uma tabela */
    $produto_data = mysqli_fetch_assoc($result);

    //TESTA SE O PRODUTO POSSUI FOTO
    if ($produto_data['nome_foto'] == null) {

        $foto = "semfoto.jpg";
    } else {

        //PEGA A NOME DA FOTO DO PRODUTO
        $foto = $produto_data['nome_foto'];
    }

    /* if(mysqli_num_rows($result) < 1)
        {
            unset($_SESSION['email']);
            unset($_SESSION['senha']);
            header('Location: ../login.php');
        } else {
            $_SESSION['email'] = $email;
            $_SESSION['senha'] = $senha;
            $_SESSION['url'] = "../loginfeito/iniciologado.php";
            header('Location: ../loginfeito/iniciologado.php');
        } */
} else {
    header('Location: editorgeral.php');
}




?>



<!doctype html>
<html lang="pt-br">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Editor Geral</title>
    <style>
        .cimainicio {
            background: rgb(255, 255, 255, 0);
            font-family: Arial, Helvetica, sans-serif;
        }

        .blogin {
            text-decoration: none;
            color: white;
            border: 2px solid black;
            border-radius: 7px;
            padding: 3px;
            background-color: green;
            font-size: xx-large;
        }

        .blogin:hover {
            background-color: black;
            color: white;
        }

        .blogin2 {
            text-decoration: none;
            color: white;
            border: 2px solid black;
            border-radius: 7px;
            padding: 3px;
            background: red;
            font-size: x-large;
        }

        .blogin2:hover {
            background-color: black;
            color: white;
        }

        .blogin3 {
            text-decoration: none;
            color: white;
            border: 2px solid black;
            border-radius: 7px;
            padding: 3px;
            padding-top: 0px;
            padding-right: 7px;
            padding-left: 7px;
            background: blue;
            font-size: x-large;
        }

        .blogin3:hover {
            background-color: black;
            color: white;
        }

        .bg {
            color: white;
            border: 2px solid black;
            padding: 15px;
            padding-bottom: 10px;
            border-radius: 15px;
            background-color: rgb(0, 0, 0, 0.6);
        }

        .alert-warning {
            border-radius: 15px;
        }

        .parteperfil {
            color: black;
        }

        .parteperfil:enabled {
            background-color: white;
            color: black;
        }

        .borda {
            text-decoration: none;
            border: 2px solid black;
            background-color: rgb(255, 255, 255, 0.9);
        }

        .homi {
            vertical-align: middle;
            white-space: inherit;
            text-align: center;
        }
           div.disclaimer{
            display: none;
        }

    </style>
</head>

<body class="cimainicio">
    <div class="">
        <br>
        <div class="container logo text-end col-12 d-flex">
            <a class="d-inline-block" href="../inicio.php">
                <img src="../fotos/atacadaologo.png" alt="logo do atacadão" width="250" height="120" class="">
            </a>
            <div class="d-inline-block col-9">
                <br><br>
                <br>
                <a href="editargeralproduto.php" class="blogin2 d-inline-block">
                    Cancelar
                </a>
            </div>
        </div>
        <br>
    </div>
    
    <div class="bg text-center col-10 offset-1">
        <form action="verificareditargeralproduto.php" method="POST" enctype="multipart/form-data">
            <div class='d-inline-block col-4 homi'>
            
            <label for=""><strong>Imagem do Produto</strong></label>
            <br>
                <br>
                <?php
                echo "<img src='../fotos/produtos/$foto' class='' width='300' height='width'>";
                ?>
                <br><br>
                <label for="fotoproduto" class="labelfoto"><strong>Enviar Imagem</strong></label>
                <input class="" type="file" id="fotoproduto" name="fotoproduto" accept=".jpg,.gif,.png">
            </div>
            <div class="d-inline-block col-6 offset-1 homi">
                <table class="table text-start">
                    <tbody class="">
                        <tr class="borda">
                            <th colspan='3' class="text-center">
                                <h3>Informações do Produto</h3>
                            </th>
                        </tr>
                        <tr class="borda">
                            <th>Código de Barras:</th>
                            <th>
                                <input type="number" class="form-control" name="codigobarras" autocomplete="off" value="<?php echo $produto_data['codigo_barras'] ?>">
                            </th>
                        </tr>
                        <tr class="borda">
                            <th>Código do Produto:</th>
                            <th>
                                <input type="number" class="form-control" name="cod_produto" autocomplete="off" value="<?php echo $produto_data['cod_produto']; ?>">
                            </th>
                        </tr>
                        <tr class="borda">
                            <th>Descrição:</th>
                            <td rows="3">
                                <textarea class="form-control" id="descricao" name="descricao" rows="3" autocomplete="off"><?php echo $produto_data['descricao']; ?></textarea>
                            </td>
                        </tr>
                        <tr class="borda">
                            <th>Embalagem:</th>
                            <th>
                                <input type="text" class="form-control" name="embalagem" autocomplete="off" value="<?php echo $produto_data['embalagem'] ?>">
                            </th>
                        </tr>
                        <tr class="borda">
                            <th>Código do fornecedor:</th>
                            <th>
                                <input type="number" class="form-control" name="cod_fornecedor" autocomplete="off" value="<?php echo $produto_data['cod_fornecedor'] ?>">
                            </th>
                        </tr>
                        <tr class="borda">
                            <th>Estoque:</th>
                            <th>
                                <input type="number" class="form-control" name="estoque" autocomplete="off" value="<?php echo $produto_data['estoque'] ?>">
                            </th>
                        </tr>
                        <tr class="borda">
                            <th colspan='3' class="text-center">
                                <h3>Localização</h3>
                            </th>
                        </tr>
                        <tr class="borda">
                            <th>Departamento:</th>
                            <th>
                                <input type="number" class="form-control" name="departamento" autocomplete="off" value="<?php echo $produto_data['departamento'] ?>">
                            </th>
                        </tr>
                        <tr class="borda">
                            <th>Rua:</th>
                            <th>
                                <input type="number" class="form-control" name="rua" autocomplete="off" value="<?php echo $produto_data['rua'] ?>">
                            </th>
                        </tr>
                        <tr class="borda">
                            <th>Apartamento:</th>
                            <th>
                                <input type="number" class="form-control" name="apartamento" autocomplete="off" value="<?php echo $produto_data['apartamento'] ?>">
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <input class="blogin" type="submit" name="update" id="update" value="Confirmar">
        </form>
    </div>


    <div class="fixed-bottom"><strong>Website por: Giliardo Júlio (
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
          <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
        </svg> gili.julio )</strong>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>

</html>