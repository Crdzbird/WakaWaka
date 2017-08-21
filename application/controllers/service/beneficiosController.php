<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;

class beneficiosController extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('BeneficiosModel');
	}

	function index(){
    //$this->load->view('welcome_message');
  }

	function beneficiosEstados(){
		$estado = filter_var ($_GET['estados'], FILTER_VALIDATE_BOOLEAN);
		//echo ($_POST['estados']);
		//echo $estado;
		header('Content-Type: application/json');
    $result = $this->BeneficiosModel->getBeneficiosEstado($estado);
		//$this->load->view('welcome_message');
		echo $result;
	}

	function listBeneficios(){
		header('Content-Type: application/json');
		echo ($this->BeneficiosModel->getAll());
	}

	function versionBeneficios(){
		//if($_SERVER["REQUEST_METHOD"]=="POST"){
		header('Content-Type: application/json');
			$Id = filter_var($_REQUEST['IdRequest'], FILTER_SANITIZE_STRING);
		  $lastId = filter_var($Id, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9]+$/")));
		$result = $this->BeneficiosModel->getVersionBeneficio($lastId);
		echo $result;
	//}
	}

	function isFilterNumber($number){
		$expr = '/^[1-9][0-9]*$/';
		if (preg_match($expr, $number) && filter_var($number, FILTER_VALIDATE_INT)) {
		    return true;
		} else {
		    return false;
		}
	}

	function getAndroidRegister(){
		$IdBeneficio = filter_var($_REQUEST['IDBENEFICIO'], FILTER_SANITIZE_STRING);
		$beneficioID = filter_var($IdBeneficio, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9]+$/")));

		$Idimei = filter_var($_REQUEST['IMEI'], FILTER_SANITIZE_STRING);
		$imeiID = filter_var($Idimei, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9]+$/")));

		$Idimsi = filter_var($_REQUEST['IMSI'], FILTER_SANITIZE_STRING);
		$imsiID = filter_var($Idimsi, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9]+$/")));

		$IdSim = filter_var($_REQUEST['SIM'], FILTER_SANITIZE_STRING);
		$simID = filter_var($IdSim, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9]+$/")));

		$IdAndroid = filter_var($_REQUEST['ANDROIDID'], FILTER_SANITIZE_STRING);
		$androidID = filter_var($IdAndroid, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9]+$/")));

		//if ($this->isFilterNumber($idBeneficio) && $this->isFilterNumber($iMEI)  &&
		//$this->isFilterNumber($iMSI) && $this->isFilterNumber($serialSIM)) {

			$data=array('IdBeneficio'=>$beneficioID,'IMEI'=>$imeiID,'IMSI'=>$imsiID,'serialSIM'=>$simID,'androidID'=>$androidID);
			echo ($this->BeneficiosModel->insertBeneficioEmpleado($data));

		//} else {
		//  echo (json_encode(array("estado"=>false)));
		//}
	}

	function chatBot(){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method == 'POST'){
			$inputJSON = file_get_contents('php://input');
			$json = json_decode($inputJSON, TRUE);

//			$textos = $json->result->parameters->text;
var_dump($json);
			switch ($json) {
				case 'bye':
					$speech = "Hola, gusto en conocerte";
					break;

				case 'control de tiendas':
					$speech = "Honduras, guatemala";
					break;

				case 'anything':
					$speech = "Yes, you can type anything here.";
					break;

				default:
					$speech = "Sorry, I didnt get that. Please ask me something else.";
					break;
			}

			$response = new \stdClass();
			$response->speech = $speech;
			$response->displayText = $speech;
			$response->source = "webhook";
			echo json_encode($response);
		}
		else
		{
			echo "Method not allowed";
		}
	}

	function sendNotification(){
		$server_key = "AIzaSyBHVOKjusxTJcp1Xao_s28uNgEGmmndogA";
		$topic_adress = "/topics/BeneficiosCLARO";
		$fcm_server_url = "https://fcm.googleapis.com/fcm/send";
		$title = utf8_encode("Nuevos Beneficios");
		$content_text = utf8_encode("Disfruta de mas y mejores Beneficios.");
		$httpheader = array('Content-Type:application/json', 'Authorization:key='.$server_key);
		$post_content = array('to' => $topic_adress, 'data' => array('title' => $title, 'content-text' => $content_text));
		$curl_connection = curl_init();
		curl_setopt($curl_connection, CURLOPT_URL, $fcm_server_url);
		curl_setopt($curl_connection, CURLOPT_POST, true);
		curl_setopt($curl_connection, CURLOPT_HTTPHEADER, $httpheader);
		curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_connection, CURLOPT_POSTFIELDS, json_encode($post_content));
		$answerFromServer = curl_exec($curl_connection);
		curl_close($curl_connection);
		echo "Crdzbird Server<br/>".$answerFromServer;
	}

	function generateQR(){
		if ($this->isFilterNumber($_REQUEST['IDBENEFICIO'],999999,1)) {
			$result = $this->BeneficiosModel->getBeneficioId($_REQUEST['IDBENEFICIO']);

			$qrCode = new QrCode($result);
			$qrCode->setSize(300);

			$ruta = '/xampp/htdocs/BeneficiosColaboradores/application/assets/image/logo.png';
			$qrCode
			    ->setWriterByName('png')
			    ->setMargin(5)
			    ->setEncoding('UTF-8')
			    ->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH)
			    ->setForegroundColor(['r' => 255, 'g' => 0, 'b' => 0])
			    ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255])
			   // ->setLabel('Scan the code')
					->setLogoPath($ruta)
			    ->setLogoWidth(100)
			    ->setValidateResult(false)
					->writeFile('qr' . $_REQUEST['IDBENEFICIO'] . '.png');

			header('Content-Type: '.$qrCode->getContentType());
			echo $qrCode->writeString();
		}else{
			echo 'Beneficio no encontrado';
		}
	}
}

 ?>
