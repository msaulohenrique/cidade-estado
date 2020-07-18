<?php
$path = './api';
    $filename = 'estados.json';
    $filePath = $path .'/'. $filename;

    if(file_exists($filePath)) {
        $content = json_decode(file_get_contents($filePath), true);
        return $content;
    }

    $somenteEssesEstados = ['TO', 'SP'];

    $estados = json_decode(file_get_contents('https://servicodados.ibge.gov.br/api/v1/localidades/estados'));

    $estados = array_filter($estados, function($estado) use ($somenteEssesEstados) {
        return $somenteEssesEstados ? in_array($estado->sigla, $somenteEssesEstados) : $estado;
    });

    $estados = array_map(function($estado) {
        $estado->cidades = json_decode(file_get_contents("https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$estado->id}/microrregioes"));
        return $estado;
    }, $estados);

    if(!file_exists($path))
        mkdir($path);

    if(!file_exists($filePath)) {
        file_put_contents($filePath, json_encode($estados));
    }
        
    return $estados;