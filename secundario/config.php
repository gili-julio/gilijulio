<?php

$url = parse_url(getenv("mysql://be1920eb11f798:4ca96342@us-cdbr-east-06.cleardb.net/heroku_0975b1a2f8429b3?reconnect=true"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

$conexao = new mysqli($server, $username, $password, $db);

    if($conexao->connect_errno){
    echo "Erro";
    } else {
    echo "Tudo certo";
    }

?>
