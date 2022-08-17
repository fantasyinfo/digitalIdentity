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
