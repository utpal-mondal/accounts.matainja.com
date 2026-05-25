<?php defined('BASEPATH') OR exit('No direct script access allowed');

class App_model extends CI_Model

{
 public function __construct()
 {
   parent::__construct();
    $this->load->model('auth_model');
 }
 public function check_user($email,$app_password) //match email and app pasword.
 {

  $this->save_queries=true;
     $this->db->select('*');
      $this->db->from('sma_users');
      $this->db->join('sma_staff_info','sma_staff_info.user_id=sma_users.id');
 		$this->db->where(array('sma_users.email'=>$email));
 		 $query=$this->db->get();
 		//echo $this->db->last_query();
  //die('2');
 	 	if($query->num_rows() >0)
 	 	{

 	 	   return $query->row();
 	 	}
 	 	else
 	 		{
 	 		return false;
 	 		}
 		}
 		public function get_attendance() //get attendance list.
 		  {
 			$this->db->select('*');
 			$this->db->from('sma_users');
 			$this->db->join('sma_attendence','sma_attendence.user_id=sma_users.id');
 				$query=$this->db->get();
           		if($query->num_rows() >0)
           		{
              	return $query->result();
           		}
           		else
           		{
           		return false;
           		}
 		}
 		public function attn_this_month($id) //get attendance current month wise.
 		  {
 		  	$this->save_queries=true;
 			$this->db->select('*');
 			$this->db->from('sma_users');
 			$this->db->join('sma_attendence','sma_attendence.user_id=sma_users.id');
 			$this->db->where('sma_attendence.user_id',$id);
 			
 		  //echo $query=$this->db->last_query();
 		  //die();
 			$this->db->where('month(in_time)',date('m'));
 			$this->db->where('year(in_time)',date('Y'));
 			$query=$this->db->get();
 				
           		if($query->num_rows() >0)
           		{
              	return $query->result();
           		}
           		else
           		{
           		return false;
           		}
 		}
      public function attn_prev_month($id) //get attendance previous month wise
      {
        //$this->save_queries=true;
      $this->db->select('*');
      $this->db->from('sma_users');
      $this->db->join('sma_attendence','sma_attendence.user_id=sma_users.id');
      $this->db->where('sma_attendence.user_id',$id);
      
      //echo $query=$this->db->last_query();
      //die();
      $this->db->where('month(in_time)',date('m',strtotime("-1 month")));
      $this->db->where('year(in_time)',date('Y',strtotime("-1 month")));
      $query=$this->db->get();
        
              if($query->num_rows() >0)
              {
                return $query->result();
              }
              else
              {
              return false;
              }
    }
      	public function attn_month_Range($id,$from,$to) //get attendance month range wise
 		  {
 		  	$this->save_queries=true;
 			$this->db->select('*');
 			$this->db->from('sma_users');
 			$this->db->join('sma_attendence','sma_attendence.user_id=sma_users.id');
 			$this->db->where('sma_attendence.user_id',$id);
 			
 		  //echo $query=$this->db->last_query();
 		  //die();
 			$this->db->where('in_time>',$from);
 			$this->db->where('in_time<',$to);
 			$query=$this->db->get();
 				
           		if($query->num_rows() >0)
           		{
              	return $query->result();
           		}
           		else
           		{
           		return false;
           		}
 		}
 		 public function register($data) //add leave apply more than one date.
    { 
	  $this->db->insert('sma_leave',$data);
 	  if($this->db->affected_rows()>0)
 	  {
 		return true;
	   }
	 else
		{
		return false;

		}
    }
    public function registernew($data) // add leave apply only single date
      { 
	     $this->db->insert('sma_leave',$data);
 	     if($this->db->affected_rows()>0)
 	     {
 		    return true;
	     }
	    else
		{
		   return false;
         }
       }
       public function getlist($id,$leaveyears,$status_list=array())
     { 
     	if(!empty($status_list) && count($status_list)==1) {
     		$this->db->select("*");
	        $this->db->where(" (leave_date LIKE '%" . $leaveyears . "%' )");
	      
	       $query=$this->db->get_where('sma_leave',array('user_id'=>$id,'status'=>$status_list[0]));
	       //echo $this->db->last_query();
	       if($query->num_rows()>0)
	       {
	       	return $query->result();
	       }
	       else{
	       	    return false;
	       }
     	}else if(!empty($status_list) && count($status_list)==2){
     		$this->db->select("*");
	        $this->db->where(array('user_id'=>$id,'status'=>$status_list[0],'leave_date LIKE'=>"%" . $leaveyears . "%" ));
	      $this->db->or_where(array('status'=>$status_list[1]));
	      $this->db->where(array('user_id'=>$id,'leave_date LIKE'=>"%" . $leaveyears . "%" ), FALSE);
	       $query=$this->db->get('sma_leave');
	       if($query->num_rows()>0)
	       {
	       	return $query->result();
	       }
	       else{
	       	    return false;
	       }
     	}else{
     		$this->db->select("*");
	        $this->db->where(" (leave_date LIKE '%" . $leaveyears . "%' )");
	      
	       $query=$this->db->get_where('sma_leave',array('user_id'=>$id));
	       //echo $this->db->last_query();
	       if($query->num_rows()>0)
	       {
	       	return $query->result();
	       }
	       else{
	       	    return false;
	       }
     	}	
     }
     public function get_userinfo($id) 
     {
     	//$this->save_queries=true;
		$this->db->select('*');
     	$this->db->from('sma_users');
     	$this->db->where('id',$id);
     	  return $this->db->get();
      }
         public function getusermail($id,$start_date,$end_date)
 			{
 			/*$this->db->save_queries = true;	*/
             $query= $this->db->query("SELECT first_name,last_name,email,leave_type,leave_date FROM `sma_users` inner join sma_leave on sma_users.id=sma_leave.user_id where sma_leave.user_id='$id' AND leave_date >='".date('Y-m-d', strtotime($start_date))."' AND leave_date<='".date('Y-m-d', strtotime($end_date))."'");
           /* echo $this->db->last_query();
           	die();*/
              if($query->num_rows()>0)
              {
                return $query->result();   
              }
              else
              {
              	return false;
              }
 			}
      public function get_attendancesheet($id,$month,$year)
      {
        $this->db->select('date_entry');
        $this->db->from('sma_attendence');
        $this->db->where(array('user_id'=>$id,
                               'month(date_entry)'=>date('m',strtotime($month)),
                               'year(date_entry)'=>date('Y',strtotime($year))
                             ));
        $query=$this->db->get();
        if($query->num_rows() >0)
        {
         return $query->result();
        }
        else
        {
         return false; 
        }

      }
      public function get_user_info($id)
      {

       // $this->save_queries=true;
        $this->db->select('*');
        $this->db->from('sma_users');
        $this->db->join('sma_staff_info','sma_staff_info.user_id=sma_users.id');
        $this->db->where('sma_users.id',$id);
          $query=$this->db->get();

        // echo $this->db->last_query();
       
        if($query->num_rows() >0)
        {
        
         return $query->result();
        }
        else
        {
         return false; 
        }
      }
      public function get_Leave_Details($id,$leave_year) //get all leave details 
      {
          //$this->save_queries=true;
            $this->db->select("leave_date,leave_type,status,subject,description");
          $this->db->where(" (leave_date LIKE '%" . $leave_year . "%' )");
        
         $query=$this->db->get_where('sma_leave',array('user_id'=>$id));
       
         //die();
        if($query->num_rows() >0)
         {
           return $query->result();
         }
         else
         {
           return false; 
         }
       
      }
       public function get_Leaveinfo($id,$leave_duration,$leave_type,$status)//get leave details for specific user based on their leave_type,status and user_id respectively.
      {
       
          //$this->save_queries=true;
         
         //echo "<br>";
         
            $this->db->select("leave_date,leave_type,status,subject,description");
          //$this->db->where(" (leave_date LIKE '%" . $leave_year . "%' )");
        //$this->db->where('leave_date<',Now());
        //$this->db->where('leave_date>',DATE_ADD(Now(),INTERVAL- 6 MONTH));
         $this->db->where('leave_type',$leave_type);
    
         $this->db->where('status',$status);
          $this->db->where(array('user_id'=>$id));
        $query= $this->db->get('sma_leave');
        // echo  $this->db->last_query();
      //die();
        
        if($query->num_rows() >0)
         {
           return $query->result();
         }
         else
         {
           return false; 
         }
  }
  public function getleavetypelist($id,$leave_type)
  {
        $this->db->select('*');
        $this->db->where('leave_type',$leave_type);
        $this->db->where('status','pending');
         $query=$this->db->get('sma_leave');
         if($query->num_rows() >0)
         {
           return $query->num_rows;
         }
         else
         {
         return false;
         }
         
   }
     public function match_attendancedate()
     {
          $this->db->select('*');
          $this->db->from('sma_users');
          $this->db->join('sma_attendence','sma_attendence.user_id=sma_users.id','left');
          //$this->db->where('sma_attendence.date_entry',date('Y-m-d'));
          $this->db->order_by('sma_attendence.in_time');
          $query=$this->db->get();
          if($query->num_rows() > 0)
          {
          return $query->result();
          }
          else
          {
          return false;
          }
      }
// test dailystaffattendancereport 	  
	  public function attendancelist($uid,$daterange) {
      $attendanceids = array();
      $activeusers=array();
         if(!$uid>0)
         {
         echo json_encode($activeusers); exit(0); 
         }

       $userGroup = $this->auth_model->getUsers_group($uid);

       if(empty($userGroup))
       {
        echo json_encode($activeusers); exit(0); 
       }

       $groupName = strtoupper($userGroup[0]->name);
			
			$present=0;
			$absent=0;
     // echo date('Y-m-d');
			$this->db->save_queries = true;
			$this->db->select('user_id,is_late,out_time,in_time');
			$this->db->from('sma_attendence');
      if($groupName=="STAFF")
      {
       $this->db->where('sma_attendence.user_id', $uid); 

      }
      if(isset($daterange))
      {
   $this->db->where('sma_attendence.date_entry', $daterange);
      }
      else
			$this->db->where('sma_attendence.date_entry', date('Y-m-d')); 


			$query_1 = $this->db->get();
			$attendance = $query_1->result();
			if($query_1->num_rows() > 0){
				foreach($attendance as $adetail){
					$attendanceids[$adetail->user_id]['is_late'] = $adetail->is_late;
					$attendanceids[$adetail->user_id]['in_time'] = $adetail->in_time;
					$attendanceids[$adetail->user_id]['out_time'] = $adetail->out_time;
				}
			}
			
			$this->db->select('sma_users.id, CONCAT(sma_users.first_name, " ", sma_users.last_name) AS u_name, sma_users.active AS status, sma_users.phone AS phone_number,sma_staff_info.upload AS image');
			$this->db->join('sma_staff_info', 'sma_staff_info.user_id = sma_users.id');
      if($groupName=="STAFF")
      {
       $this->db->where('sma_users.id', $uid); 

      }
			$this->db->from('sma_users');
			$this->db->where('active', 1);
			$query_2 = $this->db->get();
			$activeuser = $query_2->result();
			if($query_2->num_rows() > 0){
				foreach($activeuser as $auser){
					if(isset($attendanceids) && !empty($attendanceids) && is_array($attendanceids) && array_key_exists($auser->id, $attendanceids)){
					     $present++;
						if($attendanceids[$auser->id]['is_late']){
							
							$auser->attendance = 'Late';
							$auser->status = 'Active' ;
							$auser->success = 0;
							$auser->out_time = $attendanceids[$auser->id]['out_time'];
							$auser->in_time = $attendanceids[$auser->id]['in_time'];
							$auser->image=base_url().''.$auser->image;
						}else{
							
							$auser->attendance = 'Present';
							$auser->status = 'Active' ;
							$auser->success = 1;
							$auser->out_time = $attendanceids[$auser->id]['out_time'];
							$auser->in_time = $attendanceids[$auser->id]['in_time'];
					    	$auser->image=base_url().''.$auser->image;
						}
					}else{
						
						$absent++;
						$auser->attendance = 'Absent';
						$auser->status = 'Active' ;
						$auser->success = 2;
						$auser->out_time = '';
						$auser->in_time = '';
                        $auser->image=base_url().''.$auser->image;					}
					
					
				}
				$activeusers['staff']=$activeuser;
				$activeusers['total_present']=$present;
				$activeusers['total_absent']=$absent;
				$activeusers['total_staff']=$present + $absent;

				
			}
		
		echo json_encode($activeusers); exit(0);

}
//get_staff
 
	 public function get_staff($id)
      {
        $this->db->select('*');
        $this->db->from('sma_users');
        $this->db->join('sma_staff_info','sma_staff_info.user_id=sma_users.id');
        $this->db->where('sma_users.id',$id);
        $query=$this->db->get();
        if($query->num_rows() >0)
        {
         return $query->result();
        }
        else
        {
         return false; 
        }
      }
//project_manager_name
public function get_project_manager($project_manager_id)
      {
        $this->db->select('CONCAT(sma_users.first_name," ",sma_users.last_name) AS name');
        $this->db->from('sma_users');
        $this->db->where('sma_users.id',$project_manager_id);
        $query=$this->db->get();
        if($query->num_rows() >0)
        {
         return $query->row();
        }
        else
        {
         return false; 
        }
      }	  
}