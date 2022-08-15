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
    const fullPathQR = HelperClass::qrcodeUrl . "?stuid=" . HelperClass::schoolPrefix;
    const userType = [
        'Student' => '1',
        'Teacher' => '2',
        'Staff' => '3',
        'Principal' => '4',
    ];

    public static function APIresponse($status = 200, $msg = '',$data = '')
    {
        $sendArr = [];
        $sendArr['statusCode'] = $status;
        $sendArr['message'] = $msg;
        if(!empty($data))
        {
            $sendArr['data'] = $data;
        }
        echo json_encode($sendArr);die();
    }

    public static function generateRandomToken()
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ".rand(000000000,999999999)),1,40) ;
    }
}
