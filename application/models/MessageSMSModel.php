<?php

class MessageSMSModel extends CI_Model
{

  const PE_ID = 1201167324676141497;
  const API_KEY = '463BFBECAC90D9';
  const HEADER_SENDER_ID = 'DGFIED';

	public function __construct()
	{
		$this->load->database();
	}

  // welcome sms
    public static function welcomeSMS($contact,$companyName, $comapnyOwner)
    {
      $templateId = 1207167349789524536;
      $message = "Hello! Welcome to $companyName . Please find school login details in your registerd email address. Thanks - $comapnyOwner - Team Digitalfied";
      return self::sendSMS($contact,$message,$templateId);
    }


    // app login details
    public static function appLoginDetailsForParents($contact,$appLink, $studentName, $mobile, $password, $schoolCode, $schoolName)
    {
      $templateId = 1207167349760145144;
      $message = "Hey Dear Parents, Download our school app $appLink Please find login details for $studentName Mobile $mobile Password $password School Code $schoolCode Thanks. PRINCIPAL, $schoolName - Team Digitalfied";
      return self::sendSMS($contact,$message,$templateId);
    }


    // holidays 
    public static function holidayNotice($contact,$fromDate, $toDate, $occasion, $schoolName)
    {
      $templateId = 1207167349716682751;
      $message = "Dear Parent, school will remain closed from $fromDate to $toDate due to $occasion Thanks. PRINCIPAL, $schoolName - Team Digitalfied";
      return self::sendSMS($contact,$message,$templateId);
    }

    // fees due 
    public static function feesDueNotice($contact,$studentName, $monthAndYear, $schoolName)
    {
      $templateId = 1207167349804640013;
      $message = "Dear parent of $studentName, school fee needs to be paid for the month of $monthAndYear . Please contact administrator. Thanks. PRINCIPAL, $schoolName - Team Digitalfied";
      return self::sendSMS($contact,$message,$templateId);
    }



    public static function sendSMS($contact, $message, $templateId){
      $api_key = self::API_KEY;
      $contacts = $contact;
      $from = self::HEADER_SENDER_ID;
      $sms_text = urlencode($message);

      //Submit to server

      $ch = curl_init();
      curl_setopt($ch,CURLOPT_URL, "http://webmsg.smsbharti.com/app/smsapi/index.php");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, "key=".$api_key."&campaign=0&routeid=9&type=text&contacts=".$contacts."&senderid=".$from."&msg=".$sms_text."&template_id=".$templateId);
      $response = curl_exec($ch);
      curl_close($ch);
      //echo $response;
    }
}