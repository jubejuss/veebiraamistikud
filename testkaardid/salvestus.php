<?php
    $mida="\n$_REQUEST[pohjalaius],$_REQUEST[idapikkus],$_REQUEST[tyyp],$_REQUEST[kirjeldus]";
    file_put_contents("asukohad.txt", $mida, FILE_APPEND);
    echo "salvestatud";