<?php
/** Copyright (C) 2015  miniOrange

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>
* @package 		miniOrange OAuth
* @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/
/**
This library is miniOrange Authentication Service. 
Contains Request Calls to Customer service.

**/

defined( '_JEXEC' ) or die( 'Restricted access' );

class MoOauthCustomer{
	
	public $email;
	public $phone;
	public $customerKey;
	public $transactionId;

	/*
	** Initial values are hardcoded to support the miniOrange framework to generate OTP for email.
	** We need the default value for creating the OTP the first time,
	** As we don't have the Default keys available before registering the user to our server.
	** This default values are only required for sending an One Time Passcode at the user provided email address.
	*/
	
	//auth
	private $defaultCustomerKey = "16555";
	private $defaultApiKey = "fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq";
	
	function create_customer(){
		if(!MoOauthUtility::is_curl_installed()) {
			return json_encode(array("statusCode"=>'ERROR','statusMessage'=> 'Please check your configuration. Also check troubleshooting under otp configuration.'));
		}
		$hostname = MoOauthUtility::getHostname();
		
		$url = $hostname . '/moas/rest/customer/add';
		
		
		$ch = curl_init($url);
		$current_user =  JFactory::getUser();
		$customer_details = MoOauthUtility::getCustomerDetails();
		
		$this->email = $customer_details['email'];
		$this->phone = $customer_details['admin_phone'];
		$password = $customer_details['password'];
		
		$fields = array(
			'companyName' => $_SERVER['SERVER_NAME'],
			'areaOfInterest' => 'JOOMLA OAuth Client',
			'firstname' => $current_user->name,
			'lastname' => '',
			'email' => $this->email,
			'phone' => $this->phone,
			'password' => $password
		);
		$field_string = json_encode($fields);
		
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_ENCODING, "" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
		
		curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'charset: UTF - 8',
			'Authorization: Basic'
			));
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
		$content = curl_exec($ch);
		
		if(curl_errno($ch)){
			echo 'Request Error:' . curl_error($ch);
		   exit();
		}
		

		curl_close($ch);
		return $content;
	}
	
	function request_for_demo($email, $plan, $description,$callDate=null,$timeZone=null)
	{
		$url =  'https://login.xecurify.com/moas/api/notify/send';
        $ch = curl_init($url);
        
		// ask sir about the values of customer key and api key.
        $customerKey = "16555";
		$apiKey = "fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq";

        $currentTimeInMillis= round(microtime(true) * 1000);
        $stringToHash 		= $customerKey .  number_format($currentTimeInMillis, 0, '', '') . $apiKey;
        $hashValue 			= hash("sha512", $stringToHash);
        $customerKeyHeader 	= "Customer-Key: " . $customerKey;
        $timestampHeader 	= "Timestamp: " .  number_format($currentTimeInMillis, 0, '', '');
        $authorizationHeader= "Authorization: " . $hashValue;
        $fromEmail 			= $email;
        $jVersion 			= new JVersion();
        $phpVersion 		= phpversion();
        $jCmsVersion 		= $jVersion->getShortVersion();
        $moPluginVersion 	= MoOauthUtility::GetPluginVersion();


        $content='<div>Hello, <br><br>Company :<a href="'.$_SERVER['SERVER_NAME'].'" target="_blank" >'.$_SERVER['SERVER_NAME'].'</a><br><br><b>Email :</b><a href="mailto:'.$fromEmail.'" target="_blank">'.$fromEmail.'</a><br><br><b>Demo for plugin: </b>'.$plan. '<br><br><b>Description: </b>' .$description. '</div>';

        if(is_null($callDate) && is_null($timeZone))
		{
			$content='<div>Hello, <br><br>Company :<a href="'.$_SERVER['SERVER_NAME'].'" target="_blank" >'.$_SERVER['SERVER_NAME'].'</a><br><br><b>Email :</b><a href="mailto:'.$fromEmail.'" target="_blank">'.$fromEmail.'</a><br><br><b>Demo for plugin: </b>'.$plan. '<br><br><b>Description: </b>' .$description. '</div>';
            $subject            = "MiniOrange Joomla Oauth Client Request for Demo | " . $phpVersion ." | ". $jCmsVersion." | ".$moPluginVersion;
		}
		else
		{
            $subject  = "MiniOrange Joomla Oauth Client Free - Screen Share/Call Request | " . $phpVersion ." | ". $jCmsVersion." | ".$moPluginVersion;
			$content='<div>Hello, <br><br>Company :<a href="'.$_SERVER['SERVER_NAME'].'" target="_blank" >'.$_SERVER['SERVER_NAME'].'</a><br><br><b>Email :</b><a href="mailto:'.$fromEmail.'" target="_blank">'.$fromEmail.'</a><br><br><b>Time Zone:</b> '.$timeZone. '<br><br><b>Date to set up call: </b>' .$callDate. '<br><br><b>Issue :</b> ' .$plan. '<br><br><b>Description: </b>'.$description. '</div>';
		}
		
        $fields = array(
            'customerKey'	=> $customerKey,
            'sendEmail' 	=> true,
            'email' 		=> array(
                'customerKey' 	=> $customerKey,
                'fromEmail' 	=> $fromEmail,                
                'fromName' 		=> 'miniOrange',
                'toEmail' 		=> 'joomlasupport@xecurify.com',
                'toName' 		=> 'joomlasupport@xecurify.com',
                'subject' 		=> $subject,
                'content' 		=> $content
            ),
		);
        $field_string = json_encode($fields);


        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_ENCODING, "" );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls

        curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $customerKeyHeader,
            $timestampHeader, $authorizationHeader));
        curl_setopt( $ch, CURLOPT_POST, true);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
        $content = curl_exec($ch);

        if(curl_errno($ch)){
            return json_encode(array("status"=>'ERROR','statusMessage'=>curl_error($ch)));
        }
        curl_close($ch);

        return ($content);
	}

	function get_customer_key($email=null,$password=null) {

		if(is_null($email) && is_null($password))
		{
			$customer_details = MoOauthUtility::getCustomerDetails();
			$email = $customer_details['email'];
			$password = $customer_details['password'];
		}
		
		if(!MoOauthUtility::is_curl_installed()) {
			return json_encode(array("apiKey"=>'CURL_ERROR','token'=>'<a href="http://php.net/manual/en/curl.installation.php">PHP cURL extension</a> is not installed or disabled.'));
		}
		
		$hostname = MoOauthUtility::getHostname();
		
		$url = $hostname. "/moas/rest/customer/key";
		$ch = curl_init($url);
		
		$fields = array(
			'email' => $email,
			'password' => $password
		);
		$field_string = json_encode($fields);
		
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_ENCODING, "" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
		
		curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'charset: UTF - 8',
			'Authorization: Basic'
			));
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
		$content = curl_exec($ch);
		if(curl_errno($ch)){
			echo 'Request Error:' . curl_error($ch);
		   exit();
		}
		curl_close($ch);

		return $content;
	}
	
public static function submit_feedback_form($email,$phone,$query)
	{
	

        $url =  'https://login.xecurify.com/moas/api/notify/send';
        $ch = curl_init($url);

        
        $customerKey = "16555";
	$apiKey = "fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq";
	

        $currentTimeInMillis= round(microtime(true) * 1000);
        $stringToHash 		= $customerKey .  number_format($currentTimeInMillis, 0, '', '') . $apiKey;
        $hashValue 			= hash("sha512", $stringToHash);
        $customerKeyHeader 	= "Customer-Key: " . $customerKey;
        $timestampHeader 	= "Timestamp: " .  number_format($currentTimeInMillis, 0, '', '');
        $authorizationHeader= "Authorization: " . $hashValue;
        $fromEmail 			= $email;
        $jVersion 			= new JVersion();
        $phpVersion 		= phpversion();
        $jCmsVersion 		= $jVersion->getShortVersion();
        if(class_exists("MoOAuthUtility"))
            $moPluginVersion 	= MoOauthUtility::GetPluginVersion();
        else
            $moPluginVersion = "NA";
        $subject            = "Feedback for MiniOrange Joomla Oauth Client Free | " . $phpVersion ." | ". $jCmsVersion." | ".$moPluginVersion;

        $query1 =" MiniOrange joomla [Free] Oauth ";
         $content='<div >Hello, <br><br>Company :<a href="'.$_SERVER['SERVER_NAME'].'" target="_blank" >'.$_SERVER['SERVER_NAME'].'</a><br><br>Phone Number :'.$phone.'<br><br><b>Email :<a href="mailto:'.$fromEmail.'" target="_blank">'.$fromEmail.'</a></b><br><br><b>Plugin Deactivated: '.$query1. '</b><br><br><b>Reason: ' .$query. '</b></div>';


        $fields = array(
            'customerKey'	=> $customerKey,
            'sendEmail' 	=> true,
            'email' 		=> array(
                'customerKey' 	=> $customerKey,
                'fromEmail' 	=> $fromEmail,                
                'fromName' 		=> 'miniOrange',
                'toEmail' 		=> 'joomlasupport@xecurify.com',
                'toName' 		=> 'joomlasupport@xecurify.com',
                'subject' 		=> $subject,
                'content' 		=> $content
            ),
        );

        $field_string = json_encode($fields);


        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_ENCODING, "" );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls

        curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $customerKeyHeader,
            $timestampHeader, $authorizationHeader));
        curl_setopt( $ch, CURLOPT_POST, true);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
        $content = curl_exec($ch);
        	
        if(curl_errno($ch)){
            return json_encode(array("status"=>'ERROR','statusMessage'=>curl_error($ch)));
        }
        curl_close($ch);

        return ($content);

	}
	
	
	//Efficency check of the plugin for better improvement
	
	public static function plugin_efficiency_check($email,$appname,$base_url, $c_time, $dno_ssos, $tno_ssos, $previous_update, $present_update)
	{
	
		

        $url =  'https://login.xecurify.com/moas/api/notify/send';
        $ch = curl_init($url);

        
        $customerKey = "16555";
	$apiKey = "fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq";
	

        $currentTimeInMillis= round(microtime(true) * 1000);
        $stringToHash 		= $customerKey .  number_format($currentTimeInMillis, 0, '', '') . $apiKey;
        $hashValue 			= hash("sha512", $stringToHash);
        $customerKeyHeader 	= "Customer-Key: " . $customerKey;
        $timestampHeader 	= "Timestamp: " .  number_format($currentTimeInMillis, 0, '', '');
        $authorizationHeader= "Authorization: " . $hashValue;
        $fromEmail 			= $email;
        $subject            = "Oauth Client[Free] for efficiency ";
        


         $query1 =" MiniOrange joomla [Free] Oauth Client to improve efficiency ";
         $content='<div >Hello, <br><br>Company :<a href="'.$_SERVER['SERVER_NAME'].'" target="_blank" >'.$_SERVER['SERVER_NAME'].'</a><br><br>Server :'.$appname.'<br><br><b>Email :<a href="mailto:'.$fromEmail.'" target="_blank">'.$fromEmail.'</a></b><br><br><b>Plugin Efficency Check: '.$query1. '</b><br><br><b>Website: ' .$base_url. '</b><br>Creation Date:'.$c_time.'<br> Daily SSO:'.$dno_ssos.'<br> Total SSO:'.$tno_ssos.'<br> Previous Update:'.$previous_update.'<br> Current Update:'.$present_update.'</div>';


        $fields = array(
            'customerKey'	=> $customerKey,
            'sendEmail' 	=> true,
            'email' 		=> array(
                'customerKey' 	=> $customerKey,
                'fromEmail' 	=> $fromEmail,                
                'fromName' 		=> 'miniOrange',
				'bccEmail'      => 'jeswanth@xecurify.com',
                'toEmail' 		=> 'abhay@xecurify.com',
                'toName' 		=> 'abhay@xecurify.com',
                'subject' 		=> $subject,
                'content' 		=> $content
            ),
        );
        $field_string = json_encode($fields);


        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_ENCODING, "" );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls

        curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $customerKeyHeader,
            $timestampHeader, $authorizationHeader));
        curl_setopt( $ch, CURLOPT_POST, true);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
        $content = curl_exec($ch);
        	
        if(curl_errno($ch)){
			
            return;
        }
        curl_close($ch);

		
        return;

	}
	


	function submit_contact_us( $q_email, $q_phone, $query ) {
		if(!MoOauthUtility::is_curl_installed()) {
			return json_encode(array("status"=>'CURL_ERROR','statusMessage'=>'<a href="http://php.net/manual/en/curl.installation.php">PHP cURL extension</a> is not installed or disabled.'));
		}
		$hostname = MoOauthUtility::getHostname();
		$url = $hostname . "/moas/rest/customer/contact-us";
		$ch = curl_init($url);
		$current_user       = JFactory::getUser();
        $jVersion 			= new JVersion;
        $phpVersion 		= phpversion();
        $jCmsVersion 		= $jVersion->getShortVersion();
        $moPluginVersion 	= MoOauthUtility::GetPluginVersion();
        $query = '[MiniOrange Joomla Oauth Client Free | '.$phpVersion. ' | '.$jCmsVersion.' | ' . $moPluginVersion.'] ' . $query;
		$fields = array(
			'firstName'			=> $current_user->username,
			'lastName'	 		=> '',
			'company' 			=> $_SERVER['SERVER_NAME'],
			'email' 			=> $q_email,
			'ccEmail'		    => 'joomlasupport@xecurify.com',
			'phone'				=> $q_phone,
			'query'				=> $query
		);

		$field_string = json_encode( $fields );
		
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_ENCODING, "" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
		
		curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'charset: UTF-8', 'Authorization: Basic' ) );
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
		$content = curl_exec( $ch );
		
		if(curl_errno($ch)){
			echo 'Request Error:' . curl_error($ch);
		   return false;
		}
		curl_close($ch);

		return true;
	}
	
	function send_otp_token($auth_type, $emailOrPhone){
		
		if(!MoOauthUtility::is_curl_installed()) {
			return json_encode(array("status"=>'CURL_ERROR','statusMessage'=>'<a href="http://php.net/manual/en/curl.installation.php">PHP cURL extension</a> is not installed or disabled.'));
		}
		
		$hostname = MoOauthUtility::getHostname();
		$url = $hostname . '/moas/api/auth/challenge';
		$ch = curl_init($url);
		$customerKey =  $this->defaultCustomerKey;
		$apiKey =  $this->defaultApiKey;
		
		/* Current time in milliseconds since midnight, January 1, 1970 UTC. */
		$currentTimeInMillis = round(microtime(true) * 1000);

		/* Creating the Hash using SHA-512 algorithm */
		$stringToHash = $customerKey .  number_format($currentTimeInMillis, 0, '', '') . $apiKey;
		$hashValue = hash("sha512", $stringToHash);

		$customerKeyHeader = "Customer-Key: " . $customerKey;
		$timestampHeader = "Timestamp: " .  number_format($currentTimeInMillis, 0, '', '');
		$authorizationHeader = "Authorization: " . $hashValue;
		if($auth_type=="EMAIL")
		{
			$fields = array(
				'customerKey' => $this->defaultCustomerKey,
				'email' => $emailOrPhone,
				'authType' => $auth_type,
				'transactionName' => 'JOOMLA OAuth Client'
			);
		}
		else{
			$fields = array(
				'customerKey' => $this->defaultCustomerKey,
				'phone' => $emailOrPhone,
				'authType' => $auth_type,
				'transactionName' => 'JOOMLA OAuth Client'
			);
		}
		
		$field_string = json_encode($fields);

		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_ENCODING, "" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls

		curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $customerKeyHeader,
											$timestampHeader, $authorizationHeader));
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
		$content = curl_exec($ch);

		if(curl_errno($ch)){
			echo 'Request Error:' . curl_error($ch);
		   exit();
		}
		curl_close($ch);
		return $content;
	}

	function validate_otp_token($transactionId,$otpToken){
		if(!MoOauthUtility::is_curl_installed()) {
			return json_encode(array("status"=>'CURL_ERROR','statusMessage'=>'<a href="http://php.net/manual/en/curl.installation.php">PHP cURL extension</a> is not installed or disabled.'));
		}
		$hostname = MoOauthUtility::getHostname();
		$url = $hostname . '/moas/api/auth/validate';
		$ch = curl_init($url);

		$customerKey =  $this->defaultCustomerKey;
		$apiKey =  $this->defaultApiKey;

		/* Current time in milliseconds since midnight, January 1, 1970 UTC. */
		$currentTimeInMillis = round(microtime(true) * 1000);

		/* Creating the Hash using SHA-512 algorithm */
		$stringToHash = $customerKey .  number_format($currentTimeInMillis, 0, '', '') . $apiKey;
		$hashValue = hash("sha512", $stringToHash);

		$customerKeyHeader = "Customer-Key: " . $customerKey;
		$timestampHeader = "Timestamp: " .  number_format($currentTimeInMillis, 0, '', '');
		$authorizationHeader = "Authorization: " . $hashValue;

		$fields = '';

			//*check for otp over sms/email
			$fields = array(
				'txId' => $transactionId,
				'token' => $otpToken,
			);

		$field_string = json_encode($fields);

		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_ENCODING, "" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls

		curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $customerKeyHeader,
											$timestampHeader, $authorizationHeader));
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
		$content = curl_exec($ch);

		if(curl_errno($ch)){
			echo 'Request Error:' . curl_error($ch);
		   exit();
		}
		curl_close($ch);
		return $content;
	}
	
	function check_customer($email) {
		if(!MoOauthUtility::is_curl_installed()) {
			return json_encode(array("status"=>'CURL_ERROR','statusMessage'=>'<a href="http://php.net/manual/en/curl.installation.php">PHP cURL extension</a> is not installed or disabled.'));
		}
		$hostname = MoOauthUtility::getHostname();
		$url = $hostname . "/moas/rest/customer/check-if-exists";
		$ch 	= curl_init( $url );
		
		$fields = array(
			'email' 	=> $email,
		);
		$field_string = json_encode( $fields );

		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_ENCODING, "" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls

		curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'charset: UTF - 8', 'Authorization: Basic' ) );
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
		$content = curl_exec( $ch );
		if( curl_errno( $ch ) ){
			echo 'Request Error:' . curl_error( $ch );
			exit();
		}
		curl_close( $ch );

		return $content;
	}

	function mo_otp_forgot_password($email){
		
		$hostname = MoOauthUtility::getHostname();
		$url = $hostname . '/moas/rest/customer/password-reset';
		$ch = curl_init($url);
	
		$fields = '';
	
		//*check for otp over sms/email
		$fields = array(
			'email' => $email
		);
	
		$field_string = json_encode($fields);
	
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_ENCODING, "" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
	
		curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'charset: UTF - 8', 'Authorization: Basic' ) );
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt( $ch, CURLOPT_TIMEOUT, 20);
		$content = curl_exec($ch);
	
		if( curl_errno( $ch ) ){
			echo 'Request Error:' . curl_error( $ch );
			exit();
		}
		
		curl_close( $ch );
		return $content;
	}
	
}?>