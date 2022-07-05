<?php 

include_once('secundario/config.php');

$sql = "CREATE TABLE heroku_0975b1a2f8429b3.produtos (
  codigo_barras TEXT NOT NULL,
  cod_produto TEXT NULL,
  descricao TEXT NULL,
  embalagem TEXT NULL,
  cod_fornecedor TEXT NULL,
  estoque TEXT NULL,
  departamento TEXT NULL,
  rua TEXT NULL,
  apartamento TEXT NULL,
  nome_foto TEXT NULL,
  PRIMARY KEY (codigo_barras))";
$result = $conexao->query($sql);


?>
