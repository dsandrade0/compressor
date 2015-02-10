<?php

/**
 * Compressor - Script php para comprimir arquivos JS e CSS
 * @autor Diego Andrade
 */

$arquivo = $_GET['f'];
$download = isset($_GET['download']) ? true : false;

$tipos_conteudo = array('css' => 'text/css', 'js' => 'text/javascript');

function compressor($arquivo, $tipo_conteudo = 'js') {
  $conteudo_arq = @file_get_contents($arquivo);
  // remove comentarios de blocos
  $conteudo_arq = preg_replace('#/\*.*?\*/#s', '', $conteudo_arq);
  // remove espaços em branco
  $conteudo_arq = preg_replace('#\s+#', '', $conteudo_arq);
  // remove comentarios de linha
  $conteudo_arq = preg_replace('#//(.*)#', '', $conteudo_arq);

  //removendo espaços desnecessários
  $conteudo_arq = str_replace(array(' {', '{ '), '{', $conteudo_arq);
  $conteudo_arq = str_replace(array(' }', '} '), '}', $conteudo_arq);
  $conteudo_arq = str_replace(array(' ;', '; '), ';', $conteudo_arq);
  $conteudo_arq = str_replace(array(' ||', '|| '), '|', $conteudo_arq);
  $conteudo_arq = str_replace(array(' !', '! '), '!', $conteudo_arq);
  $conteudo_arq = str_replace(array(' :', ': '), ':', $conteudo_arq);
  $conteudo_arq = str_replace(array(' ,', ', '), ',', $conteudo_arq);
  $conteudo_arq = str_replace(array('( ', ' ('), '(', $conteudo_arq);
  $conteudo_arq = str_replace(array(') ', ' )'), ')', $conteudo_arq);
  $conteudo_arq = str_replace(array('= ', ' ='), '=', $conteudo_arq);

  if ($tipo_conteudo === 'js') {
    $conteudo_arq = str_replace(array('+ ', ' +'), '+', $conteudo_arq);
    $conteudo_arq = str_replace(array('+= ', ' +='), '+=', $conteudo_arq);
    $conteudo_arq = str_replace(array('-= ', ' -='), '-=', $conteudo_arq);
    $conteudo_arq = str_replace(array('- ', ' -'), '-', $conteudo_arq);
    $conteudo_arq = str_replace(array('* ', ' *'), '*', $conteudo_arq);
    $conteudo_arq = str_replace(array('/ ', ' /'), '/', $conteudo_arq);
  }

  return $conteudo_arq;
}

$info = pathinfo($arquivo);
$tipo_conteudo = $tipos_conteudo[$info['extension']];

header('Cache-Control: public');
header("Content-type: {$tipo_conteudo}; charset=utf-8");

if ($download) {
  header("Content-disposition: attachment; filename=compressor.{$info['extension']}");
}

echo compressor($arquivo, $info['extension']);
