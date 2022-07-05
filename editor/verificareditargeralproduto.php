<?php

session_start();
include_once('../secundario/config.php');

    //VER SE HÁ LOGIN ATIVO
    if(empty($_SESSION['usuario']) && empty($_SESSION['senha']))
    {
        header('Location: login.php');
    }

    //VERIFICAR SE HOUVE UM SUBMIT
    if(isset($_POST['update'])){
        $barras = $_SESSION['id'];
        $codbarras = str_pad($_POST['codigobarras'], 14, '0', STR_PAD_LEFT);
        $codproduto = $_POST['cod_produto'];
        $descricao = $_POST['descricao'];
        $embalagem = $_POST['embalagem'];
        $codfornecedor = $_POST['cod_fornecedor'];
        $estoque = $_POST['estoque'];
        $departamento = $_POST['departamento'];
        $rua = $_POST['rua'];
        $apartamento = $_POST['apartamento'];

        //Se não houver valor no campo codproduto ele se manterá o mesmo
        if(!empty($codproduto)){
            $sqlcodproduto = "UPDATE produtos SET cod_produto = '$codproduto' WHERE codigo_barras = '$barras'";
            $resultcodproduto = $conexao->query($sqlcodproduto);
        }
        
        //Se não houver valor no campo embalagem ele se manterá o mesmo
        if(!empty($embalagem)){
            $sqlembalagem = "UPDATE produtos SET embalagem = '$embalagem' WHERE codigo_barras = '$barras'";
            $resultembalagem = $conexao->query($sqlembalagem);
        }

        //Se não houver valor no campo codfornecedor ele se manterá o mesmo
        if(!empty($codfornecedor)){
            $sqlcodfornecedor = "UPDATE produtos SET cod_fornecedor = '$codfornecedor' WHERE codigo_barras = '$barras'";
            $resultcodfornecedor = $conexao->query($sqlcodfornecedor);
        }

        //Se não houver valor no campo estoque ele se manterá o mesmo
        if(!empty($estoque)){
            $sqlestoque = "UPDATE produtos SET estoque = '$estoque' WHERE codigo_barras = '$barras'";
            $resultestoque = $conexao->query($sqlestoque);
        }

        //Se não houver valor no campo departamento ele se manterá o mesmo
        if(!empty($departamento)){
            $sqldepartamento = "UPDATE produtos SET departamento = '$departamento' WHERE codigo_barras = '$barras'";
            $resultdepartamento = $conexao->query($sqldepartamento);
        }

        //Se não houver valor no campo rua ele se manterá o mesmo
        if(!empty($rua)){
            $sqlrua = "UPDATE produtos SET rua = '$rua' WHERE codigo_barras = '$barras'";
            $resultrua = $conexao->query($sqlrua);
        }

        //Se não houver valor no campo apartamento ele se manterá o mesmo
        if(!empty($apartamento)){
            $sqlapartamento = "UPDATE produtos SET apartamento = '$apartamento' WHERE codigo_barras = '$barras'";
            $resultapartamento = $conexao->query($sqlapartamento);
        }

        //Se não houver valor no campo codbarras ele se manterá o mesmo
        if(!empty($codbarras)){
            $sqlcodbarras = "UPDATE produtos SET codigo_barras = '$codbarras' WHERE codigo_barras = '$barras'";
            $resultcodbarras = $conexao->query($sqlcodbarras);
        }

        //PARTE DE UPLOAD DA FOTO DO PRODUTO
        if(isset($_FILES['fotoproduto'])){
            $foto = $_FILES['fotoproduto'];
            $nomefoto = $foto['name'];
            echo $foto['name'];
            move_uploaded_file($foto['tmp_name'],'../fotos/produtos/'.$foto['name']);
            $sqlfoto = "UPDATE produtos SET nome_foto = '$nomefoto' WHERE codigo_barras = '$barras'";
            $resultfoto = $conexao->query($sqlfoto);
        }

        $_SESSION['id'] = $codbarras;
        header('Location: editargeralproduto.php');
    } else {
        header('Location: editargeralproduto.php');
    }



?>
