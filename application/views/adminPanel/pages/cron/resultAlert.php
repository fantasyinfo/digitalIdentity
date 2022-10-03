<?php 

// alert students if today is exam


$todayResults = $this->db->query($sqlA = "SELECT e.exam_name, e.min_marks,e.max_marks,e.date_of_exam,e.id as examId, e.class_id,e.section_id, e.schoolUniqueCode,  c.className, sec.sectionName,sub.subjectName, tt.name as TeacherName, ttr.name as ResultPublishedTeacherName,
r.marks, r.remarks,IF(r.resultStatus = '1','Pass','Fail') as resultStatus,r.result_date
 FROM ".Table::resultTable." r
INNER JOIN ".Table::examTable." e ON e.id = r.exam_id AND e.schoolUniqueCode = r.schoolUniqueCode 
INNER JOIN ".Table::classTable." c ON e.class_id = c.id AND c.schoolUniqueCode = e.schoolUniqueCode 
INNER JOIN ".Table::sectionTable." sec ON e.section_id = sec.id  AND sec.schoolUniqueCode = e.schoolUniqueCode 
INNER JOIN ".Table::subjectTable." sub ON e.subject_id = sub.id  AND sub.schoolUniqueCode = e.schoolUniqueCode 
INNER JOIN ".Table::teacherTable." tt ON e.login_user_id = tt.id  AND tt.schoolUniqueCode = e.schoolUniqueCode 
INNER JOIN ".Table::teacherTable." ttr ON r.login_user_id = ttr.id  AND ttr.schoolUniqueCode = e.schoolUniqueCode 
WHERE r.status = '1' AND DATE(r.result_date) = DATE(NOW())")->result_array();


//   HelperClass::prePrintR($todayResults);

    $totalExamCountToday = count($todayResults);

    for($i=0; $i < $totalExamCountToday; $i++)
    {
        $title = 'Hey 👋, Students Greetings From School 🏫, Your Exam 📝 Result Has Been Published Today. Check The App 📱 For Result Status.';
        $notificationBody = "{$todayResults[$i]['exam_name']} Exam Result Has Been Published By {$todayResults[$i]['ResultPublishedTeacherName']} Check Yoru Result in Result Section into APP. ";


         $dbBody = "Exam Id: {$todayResults[$i]['examId']}  </br>
         Exam Name: {$todayResults[$i]['exam_name']}  </br>
         Class & Section : {$todayResults[$i]['className']}   {$todayResults[$i]['sectionName']}</br>
         Exam Subject: {$todayResults[$i]['subjectName']}  </br>
         Exam Created By: {$todayResults[$i]['TeacherName']}  </br>
         Marks Obtained: {$todayResults[$i]['marks']}  </br>
         Result Status: {$todayResults[$i]['resultStatus']}  </br>";

        $image = null;
        $sound = null;

        $tokens =  $this->db->query("SELECT fcm_token FROM " . Table::studentTable . " WHERE schoolUniqueCode = '{$todayResults[$i]['schoolUniqueCode']}'  AND status = '1' AND class_id = '{$todayResults[$i]['class_id']}' AND section_id = '{$todayResults[$i]['section_id']}' ")->result_array();


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
        $insertNotification = $this->db->query($sql = "INSERT INTO " . Table::pushNotificationTable . " (schoolUniqueCode,title,body,device_type,for_what) VALUES ('{$todayResults[$i]['schoolUniqueCode']}','$title','$dbBody','CRON','ResultAlert')");
       

    }


      
    