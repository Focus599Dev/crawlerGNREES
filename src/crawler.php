<?php 

namespace Focus599Dev\CrawlerES;

use Mpdf\Mpdf;
use DOMDocument;
use DomXpath;

class Crawler{

	protected $urls = array(
		'http://e-dua.sefaz.es.gov.br/aplicacoes/icms.asp',
		'http://e-dua.sefaz.es.gov.br/aplicacoes/icms_2.asp',
		'http://e-dua.sefaz.es.gov.br/aplicacoes/verifica_dados.asp',
		'http://e-dua.sefaz.es.gov.br/aplicacoes/dua.asp'
	);

	protected $fase = 0;

	protected $text_html = '';

	protected $html;

	protected $data = array();

	protected $filePDF;

	protected $endFase = 3;

	protected $header = array();

	function __construct($data){

		set_time_limit(0);

		error_reporting(1);

		ini_set("display_errors","On");


		$this->clearSessionCurl();

		$this->data = $data;

		if (session_status() == PHP_SESSION_NONE)
            session_start();
	}

	public function fase_0(){

		$html = $this->execCurl($this->urls[$this->fase], 'GET', null);

		$data = array(
			'TXT_PAGAMENTO' => '01/01/1900',
			'TXT_CPFCNPJ' => preg_replace('/\D/', '', $this->data['cnpj']),
			'TXT_REFERENCIA' => $this->data['data_ref'],
			'TXT_VENCIMENTO' => $this->data['data_venc'],
			'TXT_IMPOSTO' => $this->data['total'],
			'Submit' => 'Ok',
			'TXT_VLCRD' => '',
			'txt_funcao' => 'ICMS',
			'txt_aplicacao' => 'IC'
		);

		$html = $this->execCurl($this->urls[$this->fase], 'POST', $data, null, false);

		$this->text_html = $html;

	}

	public function fase_1(){

		$data = array(
			'txt_cdseqmunic' => '',
			'txt_tipo' => '',
			'txt_tipo' => '',
			'txt_sigla' => '',
			'txt_cpfcnpj' => '',
			'txt_dsrazaosc' => '',
			'txt_funcao' => '',
			'txt_aplicacao' => '',
			'txt_REFERENCIA' => '',
			'txt_VENCIMENTO' => '',
			'txt_PAGAMENTO' => '',
			'txt_IMPOSTO' => '',
			'txt_FUNRES' => '',
			'txt_VLCRD' => '',
			'txt_VLFUN' => '',
			'txt_VLMULTA' => '',
			'txt_VLJUROS' => '',
			'txt_VLCOR' => '',
			'txt_VLTOTAL' => '',
			'txt_QTMES' => '0',
			'image2.x' => '',
			'image2.y' => '',
		);

		$this->html = new DOMDocument();

		$this->html->loadHTML($this->text_html);

		$data = $this->fillPost($data);

		$data['txt_cdseqmunic'] = $this->data['municipio'];

		$html = $this->execCurl($this->urls[$this->fase], 'POST', $data, null, false);

		$this->text_html = $html;

	}

	public function fase_2(){	

		$data = array(
			'TXT_RBITM' => '',
			'TXT_RBTIPO' => '',
			'TXT_RBORG' => '',
			'TXT_RBSRV' => '',
			'TXT_TIPO' => '',
			'TXT_SIGLA' => '',
			'TXT_CPFCNPJ' => '',
			'TXT_DSRAZAOSC' => '',
			'TXT_CDMUNICIP' => '',
			'TXT_SEQMUNIC' => '',
			'TXT_PAGAMENTO' => '',
			'TXT_VENCIMENTO' => '',
			'TXT_REFERENCIA' => '',
			'TXT_VLTRIB' => '',
			'TXT_VLMULTA' => '',
			'TXT_VLJUROS' => '',
			'TXT_VLCOR' => '',
			'TXT_VLCRD' => '',
			'TXT_VLTOTAL' => '',
			'TXT_VLFUN' => '',
			'TXT_CDREC_ICMS' => '',
			'txt_FUNRES' => '',
			'txt_QTMES' => '',
			'TXT_APLICACAO' => '',
		);

		$this->html = new DOMDocument();

		$this->html->loadHTML($this->text_html);

		$data = $this->fillPost($data);

		$data['TXT_RBITM'] = $this->data['tiporecolhimento'];

		$data['TXT_CDREC_ICMS'] = substr($this->data['tiporecolhimento'], 0,4);

		$html = $this->execCurl($this->urls[$this->fase], 'POST', $data, null, false);

		$this->text_html = $html;

	}

	public function fase_3(){

		$data = array(
			'txt_desc_orgao' => '',
			'TXT_ORGAO' => '',
			'txt_desc_area' => '',
			'TXT_AREA' => '',
			'txt_desc_servico' => '',
			'TXT_SERVICO' => '',
			'txt_hdcdrec' => '',
			'txt_cdmunicip' => '',
			'txt_seqmunic' => '',
			'TXT_NRDOCDEB' => '',
			'txt_DTEMISSAO' => '',
			'txt_HREMISSAO' => '',
			'TXT_REFERENCIA' => '',
			'TXT_INFO_COMPLEMENTARES' => '',
			'TXT_VENCIMENTO' => '',
			'TXT_VLTRIB' => '',
			'TXT_VLMULTA' => '',
			'TXT_VLJUROS' => '',
			'TXT_VLCOR' => '',
			'TXT_VLCRD' => '',
			'TXT_VLTOTAL' => '',
			'txt_sigla' => '',
			'txt_cpfcnpj' => '',
			'txt_dsrazaosc' => '',
			'txt_TPTIPDUA' => '',
			'txt_APLICACAO' => '',
			'txt_NRCONTROLE' => '',
			'txt_NRCODDIGITAVEL' => '',
			'txt_NRCODBARRA' => '',
			'txt_REIMPRESSAO' => '',
			'txt_TPORIGEMDB' => '',
			'txt_PAGAMENTO' => '',
			'msgRefis' => '',
			'txt_tipdua' => '',
			'txt_conversor' => '',
			'txt_tipo' => '',
			'image.x' => '',
			'image.y' => '',
		);

		$this->html = new DOMDocument();

		$this->html->loadHTML($this->text_html);

		$data = $this->fillPost($data);

		$data['TXT_INFO_COMPLEMENTARES'] = $this->inf_comp;

		$html = $this->execCurl($this->urls[$this->fase], 'GET', $data, null, false);

		$this->text_html = $html;

		preg_match('~/imagens/2.gif~', $this->text_html, $tagExist);

		if (count($tagExist)){
			
			$this->replaceImagesToBase64();

			$this->savePDF();
		}

	}

	private function replaceImagesToBase64(){

		$linhaP = base64_encode(file_get_contents('images/2.gif'));

		$linhaB = base64_encode(file_get_contents('images/1.gif'));

		$logoES = base64_encode(file_get_contents('images/governo_peq.gif'));

		$tesoura = base64_encode(file_get_contents('images/tesoura2.gif'));

		$this->text_html = preg_replace('/\/imagens\/2.gif/', 'data:image/gif;base64,' . $linhaP , $this->text_html); 

		$this->text_html = preg_replace('/\/imagens\/1.gif/', 'data:image/gif;base64,' . $linhaB , $this->text_html); 
		
		$this->text_html = preg_replace('/\/imagens\/governo_peq.gif/', 'data:image/gif;base64,' . $logoES , $this->text_html); 

		$this->text_html = preg_replace('/\.\.\/imagens\/tesoura2.gif/', 'data:image/gif;base64,' . $tesoura , $this->text_html); 
		
		$this->text_html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $this->text_html);
		
		$this->text_html = preg_replace('#<html(.*?)>(.*?)<body(.*?)>#is', '', $this->text_html);

		$this->text_html = preg_replace('#</body(.*?)>(.*?)</html(.*?)>#is', '', $this->text_html);
		
		$this->text_html = preg_replace('/\n/', '', $this->text_html);
		
	}

	public function getBoleto(){

		try{
			
			$this->{"fase_" . $this->fase}();

			if ($this->endFase != $this->fase){
				
				$this->fase  = $this->fase + 1;

				$this->getBoleto();

			}

			return $this->isPDF();

		} catch (\Exception $e){

	        $this->logError($e->getMessage() . ' ' . $e->getLine());

			return false;

		}
	}


	private function execCurl($url, $method, $data, $certificado = null, $fallowLocation = true){
		
		$httpcode = null;

		$response = null;

		try{

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);

			if ($method == 'POST')
				curl_setopt($ch, CURLOPT_POST, true);

			if ($data)
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

			if ($fallowLocation)
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);

			curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
			
			curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt"); //saved cookies

			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	        
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

	        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			$response = curl_exec($ch);

			curl_close($ch);

		} catch (\Exception $e){

            throw $e; 
            
		}
		
		return $response;
	}

	private function logError($message){
		return file_put_contents('log/log.txt', date('d/m/Y H:i:s') . ' ' . $message . PHP_EOL, FILE_APPEND);
	}

	private function fillPost ($post){
		
		$xpath = new DomXpath($this->html);

		foreach ($post as $key => $post_value) {

			foreach ($xpath->query('//input[@name="' . $key . '"]') as $rowNode) {
				
				if($rowNode->getAttribute('value'))
			    	$post[$key] = $rowNode->getAttribute('value');
			}
		}

		return $post;
	}
	
	private function savePDF(){
		
		$file = $this->makeRandomString() . '.pdf';

		$folder = 'pdf/';

		$dom = new DOMDocument;

	    $dom->preserveWhiteSpace = false;
	    
	    $dom->validateOnParse = false;
	    
	    $dom->standalone=true;
	    
	    $dom->strictErrorChecking=false;
	    
	    $dom->substituteEntities=true;
	    
	    $dom->recover=true;
	    
	    $dom->formatOutput=false;

	    $dom->loadHTML( $this->text_html );

		$mpdf = new Mpdf();

		$mpdf->SetDisplayMode('fullpage');

		$mpdf->WriteHTML($dom->saveHTML());

		$this->filePDF = $folder . $file;

		$mpdf->Output($folder . $file, 'F');

	}

	private function makeRandomString($max=6) {
	    
	    $i = 0;
	    
	    $possible_keys = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	    
	    $keys_length = strlen($possible_keys);
	    
	    $str = "";
	    
	    while( $i < $max) {
	        
	        $rand = mt_rand(1,$keys_length-1);
	        
	        $str.= $possible_keys[$rand];
	        
	        $i++;
	    }
	    
	    return $str;
	}

	private function clearSessionCurl(){
		unlink('cookie.txt');
	}

	public function isPDF(){
		return is_file($this->filePDF);
	}

	public function copyFilePDF($pathTo){

		try {

			if (is_file($this->filePDF)){
				
				copy($this->filePDF, $pathTo);

				unlink($this->filePDF);

				return $pathTo;

			}

			return false;

		} catch (\Exception $e){

			$this->logError($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());

			return false;
		}

		return false;

	}
}

?>