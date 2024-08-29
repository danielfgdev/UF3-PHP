<?php

$resultado = "";

for ($i = 100; $i <= 200; $i++) {
    $resultado .= $i;

    if (($i - 100 + 1) % 10 !== 0) {
        $resultado .= ", ";
    }

    if (($i - 100 + 1) % 10 === 0) {
        $resultado .= "<br>";
    }
}

echo $resultado;
