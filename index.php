<?php

/**
 * Class to send SMS to phones via the MMS email service 
 * provided by service providers.
 * 
 * @author Andres Hermosilla, Iain Cambridge
 * @copyright Copyright 2011, Andres Hermosilla
 * @license Dual licensed under the MIT or GPL Version 2 licenses. 
 * @see http://www.mydigitallife.info/send-free-text-message-with-hundreds-of-email-to-sms-gateway-or-internet-web-sms-from-operator/
 * @see http://en.wikipedia.org/wiki/List_of_SMS_gateways
 * @version 0.2
 */

// Change log
// Changed class name to suit PEAR naming schemes.
// Changed from email variable name to suit PEAR naming schemes.
// Changed access level on from email variable.
// Changed carriers to an array
// Added method chaining to add_carriers
// Changed message from being an optional argument for send to a required.
// Changed xmailer to lite sms

class Lite_SMS{
		
		CONST VERSION = "0.2";
	
		/**
		 * The email the messages are to be sent from.
		 * 
		 * @var string
		 * @since 0.2
		 */
		private $fromEmail;
		/**
		 * The carriers which we are able to send to.
		 * 
		 * @var array
		 * @since 0.2
		 */
		private $carriers = array("sms" => array(), "mms" => array() );
	
		
		public function __construct(){
			
			$this->add_carrier("att", "txt.att.net")
				 ->add_carrier("att", "mms.att.net","mms");
			
		}
		
		public function setFromEmail($email){
			
			if ( !preg_match('~^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$~',$email) ){
				$this->fromEmail = $email;
			}
			
			return $this;			
			
		}
		
		public function addCarrier($carrier, $domain, $type = 'sms'){	
			
			list($carrier,$type) = $this->_validateVariables($carrier, $type);
			
			if ( !preg_match("~^[a-z0-9\-\.]+\.[a-z]+$~isU", $domain ) ){
				throw new Exception("Domain is an invalid domain name.");
			}
			
			$this->carriers[$type][$carrier] = $domain;
			
			return $this;
		}
		
		
		
		public function send($number, $carrier,$message ,$type = 'sms'){

			list($carrier,$type) = $this->_validateVariables($carrier, $type);
		
			if ( !preg_match("~^\+?\d+$~isU",$number) ){
				throw new Exception("Invalid number to send message to.");
			}
			
			if ( !array_key_exists($carrier, $this->carriers[$type]) ){
				throw new Exception("'".$carrier."' is an unknown carrier");
			}
			
			if ( empty($this->fromEmail) ){
				throw new Exception("From email is required.");
			} 
			
			if ( empty($message) ){
				throw new Exception("Message can't be empty");
			}
			
			$domain = $carrier[$type][$carrier];
			
			$to      = $number.'@'.$domain;
			$headers = 'From: <'. $this->fromEmail . ">" .PHP_EOL.
					   'X-Mailer: Lite_SMS/' . self::VERSION;
			// subject is not neccessary, it justs adds it to the body
			mail($to, '', $message, $headers);
				
			
		}
	
		protected function _validateVariables( $carrier, $type ){
			
			$type = strtolower($type);
			$carrier = strtolower($carrier);

			if ( empty($carrier) ){
				throw new Exception("Carrier name has to be a non empty value!");
			}
			
			if ( $type != "sms" && $type != "mms" ){
				throw new Exception("Message type can only be sms or mms");
			}
			
			// Probably not the best idea, but I would rather that 
			// than repeat myself and strtolower type and carrier twice.
			return array($carrier,$type);
			
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