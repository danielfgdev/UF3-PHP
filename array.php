<pre>
<?php
$arreglo = array(
    'Andalucía' => 'Sevilla',
    'Aragón' =>
        'Zaragoza',
    'Principado de Asturias' => 'Oviedo'
);
print_r($arreglo); //Imprime todo el array
$arreglo['Cantabria'] = 'Santander';
print_r($arreglo); //Imprime todo el array
?>
</pre>

<?php
$variable = array(
    'a' => '1',
    'b' => '2',
    'c' => 3,
    'nombres' => array(
        '1' => 'Ana',
        '2' => 'María',
        '3' => 'Covadonga'
    )
);
echo $variable['a'] . "<br>"; //Imprime 1
echo $variable['nombres']['3']; //Imprime Covadonga
?>