<?php
	/*
	* liteSMS PHP Class
	* Copyright 2011, Andres Hermosilla
	* Dual licensed under the MIT or GPL Version 2 licenses. (in other words, use freely)
 	
	------------------
	For more carriers
	http://www.mydigitallife.info/send-free-text-message-with-hundreds-of-email-to-sms-gateway-or-internet-web-sms-from-operator/
	http://en.wikipedia.org/wiki/List_of_SMS_gateways

	*/

	class liteSMS{
		
		// setup with your email
		public $sender = 'andres@ahermosilla.com';
		
		// List of main US carriers
		public $carriers = 
			'{
			"ATT" : { "sms" : "txt.att.net" , "mms" : "mms.att.net" },
			"Boost Mobile" :{ "mms" : "myboostmobile.com" },
			"Cricket" : { "sms" : "sms.mycricket.com" , "mms" : "mms.mycricket.com" },
			"Sprint" : { "sms" : "page.nextel.com" , "mms" : "messaging.nextel.com" }, 
			"T-Mobile" : { "mms" : "tmomail.net" },
			"Verizon" : { "sms" : "vtext.com" , "mms" : "vzwpix.com" },
			"Virgin Mobile" : { "sms" : "vmobl.com" , "mms" : "vmpix.com" },
			"MetroPCS" : { 	"sms" : "mymetropcs.com" },
			"TracFone" : { "sms" : "mmst5.tracfone.com" }
			}';
	
		function __construct(){
			// takes carriers json and converts to object
			$this->carriers = json_decode($this->carriers);
		}
		
		function add_carrier($carrier = '', $details = ''){
				//add_carrier('MyMobile',array('sms'=>'mymobile.com'));
				if($details != ''){
					foreach	($details as $key => $det){
						$this->carriers->$carrier->$key = $det;	
					}
				}
			
		}
		
		function send($number, $carrier,$message ='',$type = 'sms'){
			// check if carrier has type listed
			if(!isset($this->carriers->$carrier->$type)){
				// if not listed as type set type value to other type ie. set sms same as mms or vice versa
				$type == 'mms' ? $this->carriers->$carrier->$type = $this->carriers->$carrier->sms : $this->carriers->$carrier->$type = $this->carriers->$carrier->mms;
			}
			
			$to      = $number.'@'.$this->carriers->$carrier->$type;
			$headers = 'From: <'. $this->sender . "> \r\n" .
    				   'Reply-To: <'. $this->sender  . "> \r\n" .
					   'X-Mailer: PHP/' . phpversion();
			// subject is not neccessary, it justs adds it to the body
			mail($to, '', $message, $headers);
				
			
		}
	
	}

	// quick test
	$message = "Don't forget to check out my latests posts!";
	$sms = new liteSMS;
	$sms->send('15593029917','T-Mobile',$message);
	
	// add new carrier to instance
	$new = array('sms'=>'mymobile.com','mms'=>'media.mymobile.com');
	$sms->add_carrier('MyMobile',$new);
	
	echo '<pre>';
	print_r($sms->carriers);
	
	

?>