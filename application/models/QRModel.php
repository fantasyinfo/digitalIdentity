<?php

class QRModel extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
    $this->load->model('CrudModel');
	}

    public function listQR($post)
    {
      if(isset($post))
      {
        $data = $post;
            if(!empty($data))
            {
                $condition = '';
    
                if(isset($data['studentClass']) || isset($data['studentSection']))
                {
                    if(!empty($data['studentClass']))
                    {
                        $condition .= " AND cl.className LIKE '%{$data['studentClass']}%' ";
                    }
                    if(!empty($data['studentSection']))
                    {
                        $condition .= " AND se.sectionName LIKE '%{$data['studentSection']}%' ";
                    }
                }
          
                    $d = $this->db->query("SELECT qr.qrcodeUrl,
                    CONCAT(qr.uniqueValue, '|',cl.className, '-',se.sectionName, '|', st.roll_no ) as qrName
                    FROM ".Table::qrcodeTable." qr 
                    JOIN ".Table::studentTable." st ON st.user_id = qr.uniqueValue
                    JOIN ".Table::classTable." cl ON cl.id = st.class_id
                    JOIN ".Table::sectionTable." se ON se.id = st.section_id
                    WHERE qr.status != 0 $condition ORDER BY qr.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();
    
                    $countSql = "SELECT count(qr.id) as count  
                    FROM ".Table::qrcodeTable." qr 
                    JOIN ".Table::studentTable." st ON st.user_id = qr.uniqueValue
                    JOIN ".Table::classTable." cl ON cl.id = st.class_id
                    JOIN ".Table::sectionTable." se ON se.id = st.section_id
                    WHERE qr.status != 0 $condition ORDER BY qr.id DESC";
                }else
                {
                    $d = $this->db->query("SELECT qr.qrcodeUrl,
                    CONCAT(qr.uniqueValue, '|',cl.className, '-',se.sectionName, '|', st.roll_no ) as qrName
                    FROM ".Table::qrcodeTable." qr 
                    JOIN ".Table::studentTable." st ON st.user_id = qr.uniqueValue
                    JOIN ".Table::classTable." cl ON cl.id = st.class_id
                    JOIN ".Table::sectionTable." se ON se.id = st.section_id
                    WHERE qr.status != 0 ORDER BY qr.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();
    
                    $countSql = "SELECT count(qr.id) as count 
                    FROM ".Table::qrcodeTable." qr 
                    JOIN ".Table::studentTable." st ON st.user_id = qr.uniqueValue
                    JOIN ".Table::classTable." cl ON cl.id = st.class_id
                    JOIN ".Table::sectionTable." se ON se.id = st.section_id
                    WHERE qr.status != 0 ORDER BY qr.id DESC";
                }
    
    
                $tCount = $this->db->query($countSql)->result_array();
    
                $sendArr = [];
                for($i=0;$i<count($d);$i++)
                {
                    $subArr = [];
                    // $subArr[] = ($j = $i + 1);
                    $subArr[] = $d[$i]['qrcodeUrl'];
                    $subArr[] = $d[$i]['qrName'];
                    // $subArr[] = $d[$i]['mobile'];
                    // $subArr[] = $d[$i]['className']. " - ".$d[$i]['sectionName'];
                    // $subArr[] = $d[$i]['stateName']. " - ".$d[$i]['cityName'] . " - " . $d[$i]['pincode'];
    
                    //   if($d[$i]['status'] == 'Active')
                    //     {
                    //         $subArr[] = '<span class="badge badge-success">'.$d[$i]['status'].'</span>';
                    //     }else{
                    //         $subArr[] = '<span class="badge badge-success">'.$d[$i]['status'].'</span>';
                    //     };
    
                    // $subArr[] = date('d-m-Y', strtotime($d[$i]['dob']));
                    // $subArr[] = '
                    // <a href="viewStudent/'.$d[$i]['id'].'" class="btn btn-primary" ><i class="fas fa-eye"></i></a>  
                    // <a href="editStudent/'.$d[$i]['id'].'" class="btn btn-warning" ><i class="fas fa-edit"></i></a>  
                    // <a href="deleteStudent/'.$d[$i]['id'].'" class="btn btn-danger" 
                    // onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fas fa-trash"></i></a>';
    
                    $sendArr[] = $subArr;
                }
    
            $dataTableArr = [
                "draw"=> $data['draw'],
                "recordsTotal"=> $tCount[0]['count'],
                "recordsFiltered"=> $tCount[0]['count'],
                "data"=>$sendArr
            ];
    
            echo json_encode($dataTableArr);
            
        
      }
    }

}