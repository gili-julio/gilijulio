<?php

require __DIR__.'/vendor/autoload.php';

use \App\Image\Resize;

$obResize = new Resize(__DIR__.'/yes.png');

$obResize->resize(480, -1);

$obResize->save(__DIR__.'/yes2.jpeg');

$arquivo = __DIR__.'/yes.jpeg';
unlink($arquivo);
$obResize2 = new Resize(__DIR__.'/yes2.jpeg');
$obResize2->print();

?>