<?php
$siteUrl='https://www.eratoz.com'; // same website need to add panel first
$subjectUrl='https://www.eratoz.com/'; // set url in mail subject
$goBackUrl=$subjectUrl.'get-a-quote.html'; // If some problem set back to form url 
$thankYou='thanks.html'; // Set thank you page 
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_POST['uCaptcha']) && !empty($_POST['uCaptcha'] ) ) {
	$inputCaptcha = $_POST['uCaptcha'];
	$sessCaptcha = $_SESSION['captchaCode'];
	$_SESSION['captchaCode']='';
	if ($inputCaptcha == $sessCaptcha) {
		if(isset($_POST['v_email']) && !empty($_POST['v_email'] ) ) 
		{
			$param=$_POST;
			if(isset($param['vco_name'])){
				$tableArr['companyName'] = $param['vco_name'];
				unset($param['vco_name']);
			}
			if(isset($param['v_contact'])){
				$tableArr['custName'] = $param['v_contact'];
				unset($param['v_contact']);
			}
			if(isset($param['v_phone'])){
				$tableArr['custPhone'] = $param['v_phone'];
				unset($param['v_phone']);
			}
			if(isset($param['v_email'])){
				$tableArr['custEmail'] = $param['v_email'];
				unset($param['v_email']);
			}
			if(isset($param['v_inquiry'])){
				$tableArr['comments'] = $param['v_inquiry'];
				unset($param['v_inquiry']);
			}
			if(isset($param['v_address'])){
				$tableArr['address'] = $param['v_address'];
				unset($param['v_address']);
			}
			if(isset($param['zip_code'])){
				$tableArr['zip'] = $param['zip_code'];
				unset($param['zip_code']);
			}
			if(isset($param['v_city'])){
				$tableArr['city'] = $param['v_city'];
				unset($param['v_city']);
			}
			if(isset($param['v_country'])){
				$tableArr['country'] = $param['v_country'];
				unset($param['v_country']);
			}
			if(isset($param['Submit'])){
				unset($param['Submit']);    
			}
			if(isset($param['uCaptcha'])){
				unset($param['uCaptcha']);    
			} 
			if(count($param)>0){
				$tableArr['otherFields'] =json_encode($param);
			}
			if (getenv("HTTP_X_FORWARDED_FOR")) {
				$my_ip = getenv("HTTP_X_FORWARDED_FOR");
			} else {
				$my_ip = getenv("REMOTE_ADDR");
			}
			$tableArr['userIp'] = $my_ip;
			if($_SERVER["HTTP_REFERER"]){
				$enqueryUrl=$_SERVER["HTTP_REFERER"];
			}else{
				$enqueryUrl=$subjectUrl;
			}
			$tableArr['enqueryUrl'] = $enqueryUrl;
			$tableArr['websiteUrl'] = $siteUrl;
			$tableArr['subjectUrl'] = $subjectUrl;
			$tableArr = array_filter($tableArr);    
			$curl = curl_init();
			curl_setopt_array($curl, array(
			CURLOPT_URL => "https://www.seoahmedabad.co.in/admin/generat-enquiry",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $tableArr,
			//CURLOPT_POSTFIELDS => array('companyName' => '123456','custName' => '32322222222','custPhone' => '2222222222','custEmail' => 'savaniravi123@gmail.com','Submit' => 'Submit','websiteId' => '20','test ravi' => 'other data can sed from website'),
			));
			$response = curl_exec($curl);
			curl_close($curl);
			if($response==true){
				$redirect = "location: ".$thankYou;
				header($redirect);
				exit;		
			} else {
				$error_page_title = "Error - Missed Fields";
				$error_page_text = "Please use your browser's back button to return to the form and complete the required fields.";
				echo "<p>$error_page_title</p>";
				echo "<p>$error_page_text</p>";
				echo '<br>';
				echo '<a href="'.$goBackUrl.'" target="_parent">Go Back </a>';
				exit;
			}
		}else{
				$error_page_title = "Error - Missed Fields";
				$error_page_text = "Email required, please try again.";
				echo "<p>$error_page_title</p>";
				echo "<p>$error_page_text</p>";
				echo '<br>';
				echo '<a href="'.$goBackUrl.'" target="_parent">Go Back </a>';
				exit;
		}
	}else{
		$error_page_title = "Error - Captcha";
		$error_page_text = "Captcha code does not match, please try again.";
		echo "<p>$error_page_title</p>";
		echo "<p>$error_page_text</p>";
		echo '<br>';
		echo '<a href="'.$goBackUrl.'" target="_parent">Go Back </a>';
		exit;
	}
}else{
	$num1 = rand(1, 5);
	$num2 = rand(6, 0);
	$sum = intval($num1) + intval($num2);
	$_SESSION['captchaCode']= $sum;
	$captchaImg = $num1 . ' + ' . $num2;
	exit($captchaImg);
}
exit;

?>