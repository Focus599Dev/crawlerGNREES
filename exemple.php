<?php 

include ('crawler.php');

include ('vendor/autoload.php');		

$data = array(
	"cnpj" 			=> '10464223000163',
	"tiporecolhimento" => '1376N',
	"uf_fav" 		=> 'SP',
	"incricao_est" 	=> '',
	"nome" 			=> '',
	"endereco" 		=> '',
	"municipio" 	=> '56014#001',
	"cep" 			=> '',
	"email" 		=> 'marlon.academi@gail.com',
	"nfe" 			=> '',
	'cnpj_rem' 		=> '',
	'inf_comp' 		=> 'Teste de PDF',
	'data_venc' 	=> '01/10/2018',
	'data_ref' 		=> '09/2018',
	'valor_prin'	=> '2,00',
	'juros'			=> '0,00',
	'multa' 		=> '0,00',
	'atua_monet'	=> '0,00',
	'total'			=> '2,00',
);


$cw = new Focus599Dev\Crawler\Crawler($data);

$cw->getBoleto();

?>