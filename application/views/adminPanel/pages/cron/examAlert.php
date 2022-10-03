<?php 

// alert students if today is exam


$todayExams = $this->db->query("SELECT e.exam_name, e.min_marks,e.max_marks,e.date_of_exam,e.id as examId, e.class_id,e.section_id, e.schoolUniqueCode,  c.className, sec.sectionName,sub.subjectName, tt.name as TeacherName FROM ".Table::examTable." e
INNER JOIN ".Table::classTable." c ON e.class_id = c.id AND c.schoolUniqueCode = e.schoolUniqueCode 
INNER JOIN ".Table::sectionTable." sec ON e.section_id = sec.id  AND sec.schoolUniqueCode = e.schoolUniqueCode 
INNER JOIN ".Table::subjectTable." sub ON e.subject_id = sub.id  AND sub.schoolUniqueCode = e.schoolUniqueCode 
INNER JOIN ".Table::teacherTable." tt ON e.login_user_id = tt.id  AND tt.schoolUniqueCode = e.schoolUniqueCode 
WHERE e.status = '1' AND DATE(e.date_of_exam) = DATE(NOW())")->result_array();


// HelperClass::prePrintR($todayExams);

    $totalExamCountToday = count($todayExams);

    for($i=0; $i < $totalExamCountToday; $i++)
    {
        $title = 'Hey 👋, Students Greetings From School 🏫, Are You Ready For Your Today Exam 📝. Check The App 📱 For Exam Details.';
        $notificationBody = "Today is Exam {$todayExams[$i]['subjectName']} Exam Created By {$todayExams[$i]['TeacherName']} Check Details into App Notification Exam Section. ";


         $dbBody = "Exam Id: {$todayExams[$i]['examId']}  </br>
         Exam Name: {$todayExams[$i]['exam_name']}  </br>
         Class & Section : {$todayExams[$i]['className']}   {$todayExams[$i]['sectionName']}</br>
         Exam Subject: {$todayExams[$i]['subjectName']}  </br>
         Exam Created By: {$todayExams[$i]['TeacherName']}  </br>
         Max Marks: {$todayExams[$i]['max_marks']}  </br>
         Min Marks: {$todayExams[$i]['min_marks']}  </br>";

        $image = null;
        $sound = null;

        $tokens =  $this->db->query("SELECT fcm_token FROM " . Table::studentTable . " WHERE schoolUniqueCode = '{$todayExams[$i]['schoolUniqueCode']}'  AND status = '1' AND class_id = '{$todayExams[$i]['class_id']}' AND section_id = '{$todayExams[$i]['section_id']}' ")->result_array();


        //stoken
        $totalTokens = count($tokens);
        $token = [];
        if(!empty( $tokens))
        {
        
            for($j=0; $j < $totalTokens; $j++)
            {
                if( $j > 499  && $j % 500 == '0')
                {
                    // if token is modules 500 then send push
                    $sendPushSMS= $this->CrudModel->sendFireBaseNotificationWithDeviceId($token, $title,$notificationBody,$image,$sound);
                    unset($token);
                    continue;
                }
                array_push($token,$tokens[$j]['fcm_token']);
            }
            
            
        }


        // insert the notification on db
        $insertNotification = $this->db->query($sql = "INSERT INTO " . Table::pushNotificationTable . " (schoolUniqueCode,title,body,device_type,for_what) VALUES ('{$todayExams[$i]['schoolUniqueCode']}','$title','$dbBody','CRON','ExamAlert')");
       

    }


      
    