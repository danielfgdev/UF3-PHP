<?php
$resultado = "";

for ($i = 0; $i <= 100; $i++) {
    $resultado .= $i;

    if ($i < 100) {
        $resultado .= ", ";
    }
}

$resultado .= ".";

echo $resultado;
