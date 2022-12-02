<?php

date_default_timezone_set('Asia/Kolkata');

include 'assets/smtp/src/Exception.php';
include 'assets/smtp/src/PHPMailer.php';
include 'assets/smtp/src/SMTP.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class HelperClass
{
    const brandName = 'Digitalfied';
    const brandUrl = 'https://www.digitalfied.com';

    const prefix = 'stu0000';
    const tecPrefix = 'tec0000';
    const driverPrefix = 'dri0000';
    const schoolIDPrefix = 'sch0000';
    const imgPrefix = 'img-';
    const uploadImgDir = 'assets/uploads/';
    const qrcodeUrl = 'https://qverify.in';
    const schoolPrefix = 'dvm-'; // Deep Vidya Mandir
    const schoolName = 'Baraut Public School';
    const schoolLogoImg = 'https://media.istockphoto.com/vectors/education-book-logo-vector-design-vector-id1221128440?k=20&m=1221128440&s=612x612&w=0&h=Fuv907LF6eO9AGc7gRgg2a0MljpzYFoUsazjstEAOWg=';
    const fullPathQR = HelperClass::qrcodeUrl . "?stuid=" . HelperClass::schoolPrefix;
    const fullPathQRTec = HelperClass::qrcodeUrl . "?tecid=" . HelperClass::schoolPrefix;

    const driverImagePath = HelperClass::uploadImgDir.'driver/';
    const giftBannerImagePath = HelperClass::uploadImgDir.'giftbanner/';
    const giftsImagePath = HelperClass::uploadImgDir.'gifts/';
    const homeworkImagePath = HelperClass::uploadImgDir.'homework/';
    const profileImagePath = HelperClass::uploadImgDir.'profile/';
    const schoolBannerImagePath = HelperClass::uploadImgDir.'schoolbanner/';
    const schoolLogoImagePath = HelperClass::uploadImgDir.'schoollogo/';
    const staffImagePath = HelperClass::uploadImgDir.'staff/';
    const studentImagePath = HelperClass::uploadImgDir.'student/';
    const teacherImagePath = HelperClass::uploadImgDir.'teacher/';
    const visitorEntryImagePath = HelperClass::uploadImgDir.'visitorentry/';
    const notificationsImagePath = HelperClass::uploadImgDir.'notifications/';
    const gatePassImagePath = HelperClass::uploadImgDir.'gatepass/';
    const barCodeFilePath = 'assets/barcode/vendor/autoload.php';

    const monthsForSchool = [
        'January' => '1',
        'February' => '2',
        'March' => '3',
        'April' => '4',
        'May' => '5',
        'June' => '6',
        'July' => '7',
        'August' => '8',
        'September' => '9',
        'October' => '10',
        'November' => '11',
        'December' => '12',
    ];
    const monthsForSchoolR = [
        '1' => 'January',
        '2' => 'February',
        '3' => 'March',
        '4' => 'April',
        '5' => 'May',
        '6' => 'June',
        '7' => 'July',
        '8' => 'August',
        '9' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December',
    ];





    const userType = [
        'Student' => '1',
        'Teacher' => '2',
        'Staff' => '3',
        'Principal' => '4',
        'School' => '5',
        'Parent' => '6',
        'Driver' => '7'
    ];
    const userTypeForPanel = [
        'Admin' => '1',
        'Staff' => '2',
        'Principal' => '3',
        'Vice Principal' => '4',
        'Accountant' =>'5',
        'Manager' =>'6',
        'ChairMan' => '7',
        'Director' => '8',
        'Coordinator' => '9'
    ];

    const durationType = [
        '1' => 'Monthly',
        '2' => 'Yearly'
      ];

    const actionType = [
        'Attendence' => '1',
        'Departure' => '2',
        'Result' => '3',
        'Welcome Bonus' => '0'

    ];

    // reverse order
    const userTypeR = [
        '1' => 'Student',
        '2' => 'Teacher',
        '3' => 'Staff',
        '4' => 'Principal',
        '5' => 'School',
        '6' => 'Parent',
        '7' => 'Driver'
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
        '0' => 'Welcome Bonus'

    ];

    const reviewType =[
        '1' => 'Result Published'
    ];



    const ratings = [
        1 => '1',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5'
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


    const casteCategory = [
        '1' =>'General',
        '2' => 'OBC',
        '3' => 'SC',
        '4' => 'ST'
    ];

    const sessionYears = [
        '2022',
        '2023',
        '2024',
        '2025',
        '2026',
        '2027'
    ];


    const vehicleType = [
        '1' => 'Bus',
        '2' => 'Rikshaw',
        '3' => 'Auto',
        '4' => 'Cab',
        '5' => 'Magic',
        '6' => 'Van'
    ];

    const srClass = [
        'Nur' => 'Nur',
        'LKG' => 'LKG',
        'UKG' => 'UKG',
        '1' => 'I',
        '2' => 'II',
        '3' => 'III',
        '4' => 'IV',
        '5' => 'V',
        '6' => 'VI',
        '7' => 'VII',
        '8' => 'VII',
        '9' => 'IX',
        '10' => 'X',
        '11' => 'XI',
        '12' => 'XII',
    ];

const invoicePrefix = 'INVOICE-00';



const setNotificationForWhat = [
    '1' => 'Attendance',
    '2' => 'Departure',
    '3' => 'School Bus Entry',
    '4' => 'School Gate Entry',
    '5' => 'Results Published',
    '6' => 'Fees Deposits',
    '7' => 'Complaint Submited',
    '8' => 'Complaint Action Taken',
    '9' => 'Gifts Redeem',
    '10' => 'Gifts Redeem Status Changed',
    '11' => 'Exam Alert',
    '12' => 'Result Alert',
    '13' => 'Fees Due Alert'

];


 const defaultNotifications = [
    '1' => [
        'title' => "Attendance Update ✅",
		'body' => "Hey 👋 Dear {parents}, Our 🏫 School Attendance Updated, Please Check The App Now!!"
    ],
    '2' => [
        'title' => "Departure Update ✅",
		'body' => "Hey 👋 Dear {parents}, Our 🏫 School Departure Updated, Please Check The App Now!!"
    ],
    '3' => [
        'title' => "{student} Entry On 🚌 Transport.",
		'body' => "Hey 👋 Dear {student}, We Welcome You On 🚌 Bus / Rikshaw. Parents Can Check 📍 Track Location On App 📱 Now!!"
    ],
    '4' => [
        'title' => "{student} Entry On 🏫 School.",
		'body' => "Hey 👋 Dear {student}, We Welcome You On 🏫 School, You Have Entered Into The 🏫 School, Entry Gate."
    ],
    '5' => [
        'title' => "Result Published ✅",
		'body' => "Hey 👋 Dear {student}, Result Has Been Published For Exam Id {examid}, Please Check Result In The App Now!!"
    ],
    '6' => [
        'title' => "Fees Submit Update ✅",
		'body' => "Hey 👋 Dear {student}, Fees Has Been Submited & Invoice is {invoice}, Please Check The App Now!!"
    ],
    '7' => [
        'title' => "Complaint Submit Update ✅",
		'body' => "Hey 👋 Dear {parents}, Complaint Register Successfully, And Your Complaint Id Is {complaintid}, Please Check The App Now!!"
    ],
    '8' => [
        'title' => "Complaint Action Taken Update ✅",
		'body' => "Hey 👋 Dear {parents}, Your Complaint id {complaintid} Status Has Been Updated. Please Check The App Now!!"
    ],
    '9' => [
        'title' => "🎁 Gift Redeem Successfully.",
		'body' => "Hey 👋 Dear {identity}, We Have Successfully Recived Your Gift Redeem Request You Will Get Your Gift Soon."
    ],
    '10' => [
        'title' => "🎁 Gifts Redeem Status Updated ✅",
		'body' => "Hey 👋 Dear {identity}, Your Gifts Redeem Status Has Been Changed, Please Check The App Now!!"
    ],
    '11' => [
        'title' => "Hey 👋, Students Greetings From School 🏫, Are You Ready For Your Today Exam 📝. Check The App 📱 For Exam Details.",
		'body' => "Today is Exam {subjectName} Exam Created By {teacherName} Check Details into App Notification Exam Section.  Please Check The App Now!!"
    ],
    '12' => [
        'title' => "Hey 👋, Students Greetings From School 🏫, Your Exam 📝 Result Has Been Published Today. Check The App 📱 For Result Status.",
		'body' => "{examName} Exam Result Has Been Published By {teacherName} Check Yoru Result in Result Section into APP. "
    ],
    '13' => [
        'title' => "Hey 👋, Parents Greetings From School 🏫, Fees Due Notification ✅.",
		'body' => "Hey 👋 Dear Parents, Please Pay Your Fee. Check The App Fees Section For Total Due."
    ]

];



    const defaultFeesTypes = [
        'January Fees',
        'February Fees',
        'March Fees',
        'April Fees',
        'May Fees',
        'June Fees',
        'July Fees',
        'August Fees',
        'September Fees',
        'October Fees',
        'November Fees',
        'December Fees',
        'Annaul Fees',
        'Id Card Fees',
        'Building Fees',
        'Transport Fees',
        'Tie Fees',
        'Belt Fees',
        'Dress Fees',
        'Tution Fees',
        'Exam Fees',
        'Board Fees',
        'Digital Software Fees'
    ];



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



    public static function getAllSundays($y,$m)
    {
        $date = "$y-$m-01";
        $first_day = date('N',strtotime($date));
        $first_day = 7 - $first_day + 1;
        $last_day =  date('t',strtotime($date));
        $total = 0;
        for($i=$first_day; $i<=$last_day; $i=$i+7 ){
            $total += 1;
        }
        return  $total;
    }



    public static function sendEmail($to, $subject, $msg)
    {
        
        $mail = new PHPMailer();
        $mail->SMTPDebug = 2;
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = "smtp.gmail.com";
        $mail->Port = "587";
        $mail->IsHTML(true);
        //$mail->addAttachment('sample.pdf');
        $mail->CharSet = 'UTF-8';
        $mail->Username = "digitalfied@gmail.com";
        $mail->Password = 'jovkhbrckpqcvjwg';
        $mail->SetFrom("digitalfied@gmail.com");
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
        <!-- <script>
            Swal.fire(
                'Good job!',
                '<?= $msg; ?>',
                'success'
            )
        </script> -->
    <?php }


    public static function swalError($msg = 'Changes not saved')
    { ?>
        <!-- <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Important Info:  <?= $msg; ?>',
            })
        </script> -->
<?php }
}
