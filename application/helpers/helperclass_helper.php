<?php

class HelperClass
{
    const brandName = 'Digitalfied';
    const brandUrl = 'https://www.digitalfied.com';

    const prefix = 'stu0000';
    const tecPrefix = 'tec0000';
    const imgPrefix = 'img-';
    const uploadImgDir = 'assets/uploads/';
    const qrcodeUrl = 'https://qverify.in';
    const schoolPrefix = 'dvm-'; // Deep Vidya Mandir
    const schoolName = 'Baraut Public School';
    const schoolLogoImg = 'https://media.istockphoto.com/vectors/education-book-logo-vector-design-vector-id1221128440?k=20&m=1221128440&s=612x612&w=0&h=Fuv907LF6eO9AGc7gRgg2a0MljpzYFoUsazjstEAOWg=';
    const fullPathQR = HelperClass::qrcodeUrl . "?stuid=" . HelperClass::schoolPrefix;
    const userType = [
        'Student' => '1',
        'Teacher' => '2',
        'Staff' => '3',
        'Principal' => '4',
    ];
    const userTypeForPanel = [
        'Admin' => '1',
        'Staff' => '2',
        'Principal' => '3',
    ];



    const colorClassType =
    [
        'primary',
        'secondary',
        'info',
        'warning',
        'danger',
        'primary',
        'secondary',
        'info',
        'warning',
        'danger',
        'primary',
        'secondary',
        'info',
        'warning',
        'danger',
        'primary',
        'secondary',
        'info',
        'warning',
        'danger',
    ];


    public static function uniqueI()
    {
        $str = '01234567890123456789012345678901234567890123456789';
        return substr(str_shuffle($str), 0, 1);
    }
    public static function APIresponse($status = 200, $msg = '', $data = '')
    {
        $sendArr = [];
        $sendArr['statusCode'] = $status;
        $sendArr['message'] = $msg;
        if (!empty($data)) {
            $sendArr['data'] = $data;
        }
        echo json_encode($sendArr);
        die();
    }

    public static function generateRandomToken()
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" . rand(000000000, 999999999)), 1, 40);
    }

    public static function prePrintR($arr)
    {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
        die();
    }


    public  static function encode($string, $salt = false)
    {
        $encrypt_method = "AES-256-CBC";

        $secret_key = $salt;

        $secret_iv = 'jaduBabujadu';
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }

    public  static function decode($string, $salt = false)
    {
        $encrypt_method = "AES-256-CBC";
        $secret_key = $salt;

        $secret_iv = 'jaduBabujadu';
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        return $output;
    }


    public static function sanitizeInput($d)
    {
        return strip_tags(trim($d));
    }
}
