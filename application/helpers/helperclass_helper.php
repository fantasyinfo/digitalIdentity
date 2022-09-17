<?php

class HelperClass
{
    const brandName = 'Digitalfied';
    const brandUrl = 'https://www.digitalfied.com';

    const prefix = 'stu0000';
    const tecPrefix = 'tec0000';
    const schoolIDPrefix = 'sch0000';
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
        'School' => '5',
        'Parent' => '6'
    ];
    const userTypeForPanel = [
        'Admin' => '1',
        'Staff' => '2',
        'Principal' => '3',
    ];


    const actionType = [
        'Attendence' => '1',
        'Departure' => '2',
        'Result' => '3',

    ];

    // reverse order
    const userTypeR = [
        '1' => 'Student',
        '2' => 'Teacher',
        '3' => 'Staff',
        '4' => 'Principal',
        '5' => 'School',
        '6' => 'Parent'
    ];
    const userTypeForPanelR = [
        '1'  => 'Admin',
        '2'  => 'Staff',
        '3'  => 'Principal',
    ];


    const actionTypeR = [
        '1'  => 'Attendence',
        '2'  => 'Departure',
        '3'  => 'Result',

    ];

    const reviewType =[
        '1' => 'Result Published'
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


    const giftStatus = [
        '1' => 'Pending',
        '2' => 'Sent',
        '3' => 'On the Way',
        '4' => 'Deliverd'
    ];

    const sessionYears = [
        '2022',
        '2023',
        '2024',
        '2025'
    ];



const invoicePrefix = 'INVOICE-00';



    const experience = ['Fresher', '1 to 3 Years', '3 to 5 Years', '5 to 10 Years', '10 to 15 Years', '15 Years +'
    ];
    public static function uniqueI()
    {
        $str = '01234567890123456789012345678901234567890123456789';
        return substr(str_shuffle($str), 0, 1);
    }
    public static function APIresponse($status = 200, $msg = '', $data = '',$extraKey = [])
    {
        $sendArr = [];
        $sendArr['statusCode'] = $status;
        $sendArr['message'] = $msg;
        if (!empty($data)) {
            $sendArr['data'] = $data;
        }
        if (!empty($extraKey)) {
            foreach($extraKey as $key => $value)
            {
                $sendArr[$key] = $value;
            }  
        }
        echo json_encode($sendArr);
        die();
    }

    public static function generateRandomToken()
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" . rand(000000000, 999999999)), 1, 40);
    }

    public static function makeRandomPassword()
    {
       return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ".rand(000000000,999999999)),0,6) ;
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



    public static function sendEmail($to, $subject, $msg)
    {
        include 'assets/smtp/PHPMailerAutoload.php';
        $mail = new PHPMailer();
        $mail->SMTPDebug = 3;
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = "smtp.gmail.com";
        $mail->Port = "587";
        $mail->IsHTML(true);
        //$mail->addAttachment('sample.pdf');
        $mail->CharSet = 'UTF-8';
        $mail->Username = "digitalfied@gmail.com";
        $mail->Password = 'fmyvsyeieegzroqc';
        $mail->SetFrom("EMAIL");
        $mail->Subject = $subject;
        $mail->Body = $msg;
        $mail->AddAddress($to);
        $mail->SMTPOptions = array('ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => false
        ));
        $mail->Send();

        // if (!$mail->Send()) {
        // echo $mail->ErrorInfo;
        // } else {
        //    // echo 'Sent';
        // }
    }



    public static function checkIfItsACEOAccount()
    {
        if ($_SESSION['name'] != 'Super CEO Account' && $_SESSION['email'] != 'superCEO@digitalfied.in' && $_SESSION['user_type'] != 'SuperCEO' && $_SESSION['schoolUniqueCode'] != '963852') {
            return false;
        } else {
            return true;
        }
    }




    public static function swalSuccess($msg = 'Changes have been saved')
    { ?>
        <script>
            Swal.fire(
                'Good job!',
                '<?= $msg; ?>',
                'success'
            )
        </script>
    <?php }


    public static function swalError($msg = 'Changes not saved')
    { ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong! <?= $msg; ?>',
            })
        </script>
<?php }
}
