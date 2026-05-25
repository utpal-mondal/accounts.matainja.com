<?php defined('BASEPATH') OR exit('No direct script access allowed');







class Staff_model extends CI_Model



{

    

public function __construct()



 {



  parent::__construct();

  

  $this->load->database();

		  }



 public function register($data)

    { 



	   $this->db->insert('sma_staff_info',$data);



	 if($this->db->affected_rows()>0){

	 	//die('hello');

			return $this->db->insert_id();

	   }

	   else{

			return false;

		  }

	}

	public function getpasswordById($data)

    { 
    	$this->db->select('*');
        $this->db->from('sma_users');
 	$this->db->where('id',$data);
  	return $this->db->get();
 	
    }
    public function work_home_ById($data)

    { 
    	$this->db->select('*');
        $this->db->from('sma_work_home');
 	$this->db->where('user_id',$data);
  	return $this->db->get();
 	
    }
	public function registerstff($data1)

    { 

	   $this->db->insert('sma_users',$data1);

	   if($this->db->affected_rows()>0){

			return $this->db->insert_id();

	   }

	   else{

			return false;

		  }

	

        }

		public function uploadfile($data2,$id1)

      { 

	  	 $this->db->where('id',$id1);

	   	$this->db->update('sma_staff_info',$data2);

	 }
	 public function update_ModalPswd($id,$data)

      {
       // $this->db->save_queries=true;
        $this->db->set(array('app_password'=>md5($data)));
        $this->db->where('id',$id);
     
         return $query=$this->db->update('sma_users');
      }
       public function add_Modalincrement($data)//salary increment table updated 

      {
          $this->db->insert('sma_increment_history',$data);
		 if($this->db->affected_rows()>0)
	   	 	{
			return true;
			}
      	 else
         {
           return false;
         }
     
       }
        public function del_salary_details($id)
         {
         	//$this->save_queries=true;

            $this->db->delete('sma_increment_history',array('id'=>$id));

             //echo $this->db->last_query();

              if ($this->db->affected_rows()>0)
               {
               	return FALSE;
      		  }

         }
		

		   public function delete_staff($id)

    		{

		

       $this->db->delete('sma_users', array('id' => $id));



       if ($this->db->affected_rows()>0) {

		     $this->db->delete('sma_staff_info', array('user_id' => $id));

            return FALSE;

      		  }

		

				}

				

     public function getstaffinfo($data2)

	 {

		

		 $this->db->where('user_id',$data2);

		return $this->db->get('sma_staff_info');

		 }




		    public function updatestaff1($id1,$data2) //staff background data update//

			 {

				$this->db->where('id',$id1);

		 		$this->db->update('sma_users',$data2);

				if($this->db->affected_rows()>=0){

					$data=array(

                     'table_name'=>'sma_users',

                     'update_time'=>date('Y-m-d H:i:s') 

					);

					$this->db->insert('sma_updatetime',$data);



					return true;

					}else{

						return false;

					}

		 		}

		  public function updatestaff2($id1,$data2)  //staff frontend data update//

	 {

		 $this->db->where('user_id',$id1);

		 $this->db->update('sma_staff_info',$data2);

		 if($this->db->affected_rows()>=0){

		 	$data=array(

                     'table_name'=>'sma_staff_info',

                     'update_time'=>date('Y-m-d H:i:s') 

					);

					$this->db->insert('sma_updatetime',$data);

				return true;

			}else{

				return false;

				}

		 }

		 public function getStaffSuggestions($term, $limit = 10)

    {

        $this->db->select("id, concat(first_name, ' ', last_name) as text", FALSE);

        $this->db->from('sma_users');

        $this->db->where(" (id LIKE '%" . $term . "%' OR first_name LIKE '%" . $term . "%' OR last_name LIKE '%" . $term . "%' OR email LIKE '%" . $term . "%' OR phone LIKE '%" . $term . "%') ");

        //$q = $this->db->get_where('sma_users', array('is_staff' => 1), $limit);

        $this->db->limit($limit);

        $q = $this->db->get();

        if ($q->num_rows() > 0) {

            foreach (($q->result()) as $row) {

                $data[] = $row;

            }



            return $data;

        }

    }

   public function getStaffRfid($term, $limit = 10)

    {

		if($term==1){

			$this->db->select("id, rfid as text", FALSE);

			$this->db->from('sma_attendance_rfid_new');

			$this->db->where('status',0);

			$this->db->limit($limit);

			$q = $this->db->get();

			

			

			if($q->num_rows() > 0) {

				foreach (($q->result()) as $row) {

					$data[] = $row;

				}

			

				return $data;

			}

		}else{

			$this->db->select("id, rfid as text", FALSE);

			$this->db->from('sma_attendance_rfid_new');

			$this->db->where(" (id LIKE '%" . $term . "%' OR rfid LIKE '%" . $term . "%') ");

			$this->db->where('status',0);

			$this->db->limit($limit);

			$q = $this->db->get();

			

			

			if($q->num_rows() > 0) {

				foreach (($q->result()) as $row) {

					$data[] = $row;

				}

			

				return $data;

			}

		}

    }

     public function getrfidfetch($data)

    {

    	$this->db->where('rfid',$data);

  		return $this->db->get('sma_attendance_rfid_new');

     }

         public function getproject_id($data)

    {

    	$this->db->where('id',$data);

  		return $this->db->get('sma_users');

     }

 public function getStaffpm($term, $limit = 10)

    {



       $this->db->select("id, concat(first_name, ' ', last_name) as text", FALSE);

        $this->db->where(" (id LIKE '%" . $term . "%'  OR first_name LIKE '%" . $term . "%' OR last_name LIKE '%" . $term . "%' ) ");

        $q = $this->db->get_where('sma_users', array('group_id' => 7), $limit);

      

        if($q->num_rows() > 0) {

            foreach (($q->result()) as $row) {

                $data[] = $row;

            }



            return $data;

        }

    }

public function getphoto($data1)

{

	$this->db->select('upload');

	$this->db->where('user_id',$data1);

	return $this->db->get('sma_staff_info')->result();

	}



   public function checkrfid($data1)

	{

	

	$this->db->where('id',$data1);

	$this->db->or_where('rfid',$data1);

	 $query=$this->db->get('sma_attendance_rfid_new');

	 if($query->num_rows()>0){



				return $query->result();

			}else{

				return false;

				}

	}

	public function updaterfidstatus($data1)

	{ 

		$this->db->set('status',1);

		$this->db->where(array('id'=>$data1,'rfid!='=>123));

		 return $this->db->update('sma_attendance_rfid_new');

	}

	

	public function releaserfidstatus($data1)

	{ 

		$this->db->set('status',0);

		$this->db->where('rfid',$data1);

		 return $this->db->update('sma_attendance_rfid_new');

	}


    /* data2 : Integer - User Id for fetch a one particular user */
    
	public function get_staff_info($data2)

	 {

		 $this->db->select('sma_users.*,sma_staff_info.*');

		 $this->db->from('sma_users');

		 $this->db->join('sma_staff_info','sma_users.id=sma_staff_info.user_id');

		 $this->db->where('user_id',$data2);

		return $this->db->get();

		 }  



	public function insert_attendance($data=array()){

		$this->db->insert('sma_staff_attendance',$data);
		

		if($this->db->affected_rows()>0){

				$id = $this->db->insert_id();

				return $id;	

			}else{

				return false;

				}

	}

	public function AddImgAttendence($data=array()){

		$this->db->insert('sma_attendence_img',$data);
		
		if($this->db->affected_rows()>0){

				$id = $this->db->insert_id();

				return $id;	

			}else{

				return false;

				}

	}

		public function staffattendance($data){

			//$query  =$this->db->query('SELECT * FROM sma_staff_attendance WHERE attendance_time >= UNIX_TIMESTAMP(MONTH(CURDATE())) AND staff_id='.$data.'');





			$query  =$this->db->query("SELECT staff_id,device_type ,MIN( `attendance_time` ) AS InStamp, MAX( `attendance_time` ) AS OutStamp, TIMEDIFF(MAX( `attendance_time` ) , MIN( `attendance_time` ) ) AS Hours FROM sma_staff_attendance where staff_id=$data AND MONTH(attendance_time) = MONTH(CURRENT_DATE())

AND YEAR(attendance_time) = YEAR(CURRENT_DATE()) GROUP BY `staff_id` , DATE( attendance_time )");



				if($query->num_rows() > 0){

				return $query->result();

			}else{

				return false;

				}

	}



	public function staffattendancerange($data=array()){

	//$query  =$this->db->query("SELECT * FROM sma_staff_attendance WHERE attendance_time BETWEEN ' ".$data['from_date']." ' AND '".$data['to_date']."' AND staff_id=".$data['id']."");

		/*$query =$this->db->query(SELECT * FROM  sma_staff_attendance WHERE attendance_time >='$from_date' AND  attendance_time <= '$to_date' AND  staff_id='.$data.'');*/

		//echo $this->db->last_query();

		//die();

		$query  =$this->db->query("SELECT staff_id,device_type ,MIN( `attendance_time` ) AS InStamp, 



MAX( `attendance_time` ) AS OutStamp, TIMEDIFF(MAX( `attendance_time` ) , MIN( `attendance_time` ) ) AS 



Hours FROM sma_staff_attendance where staff_id=".$data['id']." AND attendance_time BETWEEN ' ".$data['from_date']." ' AND '".$data['to_date']."' GROUP BY `staff_id` , DATE( attendance_time )");

		if($query->num_rows() > 0){

			return $query->result();

			}

			else

			{

			return false;

			}

      

	}

	public function updateouttime($data){



		

		//$this->db->set('out_time',$outime);

		//$this->db->get('sma_staff_attendance');

		//$this->db->where('staff_id',$staffid);

		 $this->db->where(array('staff_id'=>$data['staff_id'],

                                 'attendance_time'=>$data['attendance_time']));

   		 $this->db->update('sma_staff_attendance', array('out_time' => $data['out_time']));

    	 return true;

		

        }

    public function get_staff_attendance($id){

    	$this->db->select('*');

    	$this->db->from('sma_staff_attendance');

    	//$this->db->where(array('staff_id'=>$id,

    		//'attendance_time'=>date('Y-m-d')

    	//));

    	$this->db->where('staff_id',$id);

    	$query=$this->db->get();

    	if($query->num_rows()>0){

    		return $query->result();

    	}else{

    		return false;

    	}

    }

	 public function get_staff_attendancenew($id){

    	$this->db->select('*');

    	$this->db->from('sma_staff_attendance');

    	$this->db->where(array('staff_id'=>$id,

    		'date(attendance_time)'=>date('Y-m-d')

    	));
         $this->db->order_by("attendance_time", "asc");
    	

    	$query=$this->db->get();

    	if($query->num_rows()>0){

    		return $query->result();

    	}else{

    		return false;

    	}

    }

     public function resourcelist($data){

     		

		//$this->db->where('user_id',$data);

  		//return $this->db->get('sma_resource_activity');
  $this->save_queries=true;
  		$this->db->select('sma_resource.*, sma_resource_activity.RID, sma_resource_activity.user_id, sma_resource_activity.name as staff_name, sma_resource_activity.modified_date, sma_resource_activity.status');

	 		$this->db->from('sma_resource');

	 		$this->db->join('sma_resource_activity','sma_resource.id=sma_resource_activity.RID');

	 		$this->db->where('sma_resource_activity.user_id',$data);

	 	return $this->db->get();
	 	/* echo $this->db->last_query();
	 	 die;*/
     }

      public function getstaff()

      {



        $this->db->select('*');

        $this->db->from('sma_users');

        $this->db->join('sma_staff_info','sma_users.id=sma_staff_info.user_id');

      $query=$this->db->get();

      if($query->num_rows()>0){



    		return $query->result();

    	}else{

    		return false;

    	}







	   }


	   public function getstaffById($userID)

      {

	         $pmInfo =array();

	        $this->db->select('*');

	        $this->db->from('sma_users');

	        $this->db->join('sma_staff_info','sma_users.id=sma_staff_info.user_id');

	      

	        $this->db->where('sma_staff_info.user_id',$userID);
	      

	        $query= $this->db->get();

	        if($query->num_rows()>0)
	        {

	        	$pmInfo = $query->row();



	        }




           return $pmInfo;


	   }



       public function getupdatetime()

      {

        $this->db->select('*');

        $this->db->from('sma_updatetime');

        $query= $this->db->get();

        if($query->num_rows()>0){

    		return $query->result();

    	}else{

    		return false;

    	}



	   }

	public function NewrfId($data)

    { 

        $this->db->select('*');

        $this->db->from('sma_attendance_rfid_new');
        $this->db->where('sma_attendance_rfid_new.rfid',$data['rfid']);


        $query= $this->db->get();
        $this->db->insert('sma_attendence_rf_record',$data);

        if($query->num_rows()==0){
        	//echo "error1";
        	$this->db->insert('sma_attendance_rfid_new',$data);
        	

    		if($this->db->affected_rows()>0){

	 	//die('hello');

			return $this->db->insert_id();

	   }

	   else{
	   //echo "error2";

			return false;

		  }

    	}else{
    			//echo "error3";

    		return false;

    	}

	   



	 

	}

	public function AttedenceRecord($user_data=array(),$attendenceRec=array(),$postData=array())

    { 
       

       $date_attd = date("Y-m-d",strtotime($postData['attendance_time']));
       $user_id = $postData['user_id'];
       $intim=$postData['attendance_time'];

        $this->db->select('*');

        $this->db->from('sma_attendence');
        $this->db->where('sma_attendence.user_id',$user_id);
        $this->db->where('sma_attendence.date_entry',$date_attd);

        $query= $this->db->get();

        if($query->num_rows()==0){

        	$data = array(
								'user_id'=>$user_id,
								'date_entry'=>$date_attd,
								'in_time'=>$intim,
								'out_time'=>$intim,
								'input_time'=> $intim,
								'in_out_time'=> 1,
								'is_late'=>$postData['is_late']

								 );


        	 $this->db->insert('sma_attendence',$data);



        }
        else
        {

             $attendecPre = $query->row();

             $old_hours = $attendecPre->work_hours;

             $start_time = $attendecPre->input_time;
             $attendence_count = $attendecPre->attendence_count;


             if(strtotime($intim) == strtotime($start_time))
             	return;
            

              // Add hourse 
             if($attendecPre->in_out_time == 1)
             {

             	 
                     $end_time = $postData['attendance_time'];

             	$timediff = $this->GetDiffHours($start_time,$end_time);

             	$time_span=$timediff['hours'].":".$timediff['minutes'].":".$timediff['seconds'];

             	

             	$totalHours = $this->sum_the_time($old_hours,$time_span);

             	$dataRec = array(
								
								
								'out_time'=>$intim,
								'input_time'=> $intim,
								'in_out_time'=> 0,
								'work_hours'=>$totalHours,
								'attendence_count'=> $attendence_count+1

								 );

                       //'work_hours'=>



             }
             else
             {

               $dataRec = array(
								
								
								'out_time'=>$intim,
								'input_time'=> $intim,
								'in_out_time'=>1,
								'attendence_count'=> $attendence_count+1,
								'reserve_previous_time'=>$start_time
								

								 );

             }

             

            



                $this->db->update('sma_attendence',$dataRec, array('user_id' => $user_id,'date_entry'=>$date_attd));

        	 

        }

      
       
	  



	 if($this->db->affected_rows()>0){

	 	//die('hello');

			return $this->db->insert_id();

	   }

	   else{

			return false;

		  }

	}

	    function GetDiffHours($start, $end)
			{

		       

				$uts['start']      =    strtotime( $start );
				        $uts['end']        =    strtotime( $end );
				        if( $uts['start']!==-1 && $uts['end']!==-1 )
				        {
				            if( $uts['end'] >= $uts['start'] )
				            {
				                $diff    =    $uts['end'] - $uts['start'];
				                if( $years=intval((floor($diff/31104000))) )
				                    $diff = $diff % 31104000;
				                if( $months=intval((floor($diff/2592000))) )
				                    $diff = $diff % 2592000;
				                if( $days=intval((floor($diff/86400))) )
				                    $diff = $diff % 86400;
				                if( $hours=intval((floor($diff/3600))) )
				                    $diff = $diff % 3600;
				                if( $minutes=intval((floor($diff/60))) )
				                    $diff = $diff % 60;
				                $diff    =    intval( $diff );
				                return( array('years'=>$years,'months'=>$months,'days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
				            }
				            else
				            {
				                echo "Ending date/time is earlier than the start date/time";
				            }
				        }
				        else
				        {
				            echo "Invalid date/time data detected";
				        }
	        }
			

			

			function sum_the_time($time1, $time2) {

		      $times = array($time1, $time2);
		      $seconds = 0;
		      foreach ($times as $time)
		      {
		        list($hour,$minute,$second) = explode(':', $time);
		        $seconds += $hour*3600;
		        $seconds += $minute*60;
		        $seconds += $second;
		      }
		      $hours = floor($seconds/3600);
		      $seconds -= $hours*3600;
		      $minutes  = floor($seconds/60);
		      $seconds -= $minutes*60;
		      if($seconds < 9)
		      {
		      $seconds = "0".$seconds;
		      }
		      if($minutes < 9)
		      {
		      $minutes = "0".$minutes;
		      }
		        if($hours < 9)
		      {
		      $hours = "0".$hours;
		      }
		      return "{$hours}:{$minutes}:{$seconds}";
    }


    function Getattendence($user_id,$date_attd)
    {
    	$attendecPre =array();

    	 $this->db->select('*');

        $this->db->from('sma_attendence');
        $this->db->where('sma_attendence.user_id',$user_id);
        $this->db->where('sma_attendence.date_entry',$date_attd);

        $query= $this->db->get();

        if($query->num_rows()>0){

        	$attendecPre = $query->row();



        }
        return $attendecPre;
    }
    public function home_date_register($data)
    {
       
	   $this->db->insert('sma_work_home',$data);
	 if($this->db->affected_rows()>0)
	    {
			return $this->db->insert_id();
		}
      else
      {
         return false;
      }
   }
   public function salary_details($id)
   {
   	$this->db->select('*');
   	$this->db->from('sma_increment_history');
   $this->db->where('user_id',$id);
   	 return $query=$this->db->get();
   	}

public function getsalary_report()
{
	$this->db->select('*');
	$this->db->from('sma_users');
	$this->db->join('sma_increment_history','sma_increment_history.user_id=sma_users.id');
	$this->db->where('sma_users.active',1);
	$query=$this->db->get();
	if($query->num_rows >0)
	{
		return $query->result();
	}
	else
	{
		return true;
	}
}


   public function get_home_info($id,$start_date,$end_date)
   {
   	//die('error1');
   	//echo date('Y-m-d',strtotime($start_date));
   	//echo date('Y-m-d',strtotime($end_date));
   	//die();
   	$this->save_queries=true;
   	$this->db->select('*');
   	//$this->db->from('sma_work_home');
   	$this->db->where('user_id',$id);
   	$this->db->where('work_date>=',date('Y-m-d',strtotime($start_date)));
   	$this->db->where('work_date<=',date('Y-m-d',strtotime($end_date)));
   	$query=$this->db->get('sma_work_home');
   		//$this->db->get('sma_work_home');
   		//echo $this->db->last_query();
   //	die('123');
   	if($query->num_rows() >0)
   	{
       return true;
   	}
   	else
   	{
   		return false;
   	}

   }

}