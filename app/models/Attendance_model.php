<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_model extends CI_Model
{
    public function __construct()
   {
        parent::__construct();
       $this->load->database();

    }
    public function attendance_add($data,$datanew)
      {
       //print_r($data);
       //print_r($datanew);
       //die();
     $this->db->save_queries=true;
          $this->db->insert('sma_attendence',$data);
        
          if($this->db->affected_rows()>0)
            {
              $this->db->insert('sma_staff_attendance',$datanew);
              
              return true;
              
            }
          else
          {
            return false;
          }
        }
   public function delete_assignrfid($id)

    		{
        
         $this->db->delete('sma_attendance_rfid_new',array('id'=>$id));
       

           }
            public function del_atten_record($id)

    		    {
        
         $this->db->delete('sma_staff_attendance',array('id'=>$id));
       

           }
  public function del_atten_report($id)

            {
        
          $this->db->delete('sma_attendence',array('id'=>$id));
         // $this->db->last_query();
       

           }

           public function staffattendance($data)
           {

              //$query  =$this->db->query('SELECT * FROM sma_staff_attendance WHERE attendance_time >= UNIX_TIMESTAMP(MONTH(CURDATE())) AND staff_id='.$data.'');
               $query=$this->db->query("SELECT staff_id,staff_name,device_type ,attendance_time,attendance_id,update_time FROM sma_staff_attendance where staff_id=$data AND MONTH(attendance_time) = MONTH(CURRENT_DATE())
                 AND YEAR(attendance_time) = YEAR(CURRENT_DATE()) GROUP BY `staff_id` , DATE( attendance_time )");
               if($query->num_rows() > 0)
               {
                 return $query->result();
               }
               else
                {
                 return false;
                }
              }

public function staffattendancerange($data=array()){

  $query  =$this->db->query("SELECT staff_id,staff_name,device_type ,attendance_time,attendance_id,update_time FROM sma_staff_attendance WHERE attendance_time >= ' ".$data['from_date'].".00:00:00' AND attendance_time<='".$data['to_date'].".23:59:59' AND staff_id=".$data['id']."");

    /*$query =$this->db->query(SELECT * FROM  sma_staff_attendance WHERE attendance_time >='$from_date' AND  attendance_time <= '$to_date' AND  staff_id='.$data.'');*/

    //echo $this->db->last_query();

    //die();

     if($query->num_rows() > 0){

          return $query->result();
          }
       else
        {

         return false;

        }
     }





 public function staffattendancereport($data)
           {

              //$query  =$this->db->query('SELECT * FROM sma_staff_attendance WHERE attendance_time >= UNIX_TIMESTAMP(MONTH(CURDATE())) AND staff_id='.$data.'');
               $query=$this->db->query("SELECT staff_name,user_id,in_time,out_time ,in_out_time,work_hours,reserve_previous_time,is_late FROM sma_attendence join sma_staff_attendance on sma_staff_attendance.staff_id=sma_attendence.user_id where user_id=$data AND MONTH(in_time) = MONTH(CURRENT_DATE())
                 AND YEAR(in_time) = YEAR(CURRENT_DATE()) GROUP BY `user_id` , DATE( in_time )");
               //$this->db->last_query();
              // die();
               if($query->num_rows() > 0)
               {
                 return $query->result();
               }
               else
                {
                 return false;
                }
              }

     public function attendancereportrange($data=array()){

  $query  =$this->db->query("SELECT staff_name,user_id,in_time,out_time ,in_out_time,work_hours,reserve_previous_time,is_late FROM sma_attendence join sma_staff_attendance on sma_staff_attendance.staff_id=sma_attendence.user_id  WHERE in_time >=' ".$data['from_date'].".00:00:00 ' AND in_time<='".$data['to_date'].".23:59:59'AND user_id=".$data['id']." GROUP BY user_id");

    /*$query =$this->db->query(SELECT * FROM  sma_staff_attendance WHERE attendance_time >='$from_date' AND  attendance_time <= '$to_date' AND  staff_id='.$data.'');*/

    //echo $this->db->last_query();

    //die();

     if($query->num_rows() > 0){

          return $query->result();
          }
       else
        {

         return false;

        }
     }  
     public function getupdate_time($id)

      {
        $this->db->select('sma_attendence.id,first_name,last_name,user_id,in_time,out_time');
        $this->db->from('sma_attendence');
        $this->db->join('sma_users','sma_users.id=sma_attendence.user_id');
        $this->db->where('sma_attendence.id',$id);
        return $this->db->get();

       }
        public function update_ModalTime($id,$data,$work_hournew)

      {
        //$this->db->save_queries=true;
        $this->db->set(array('out_time'=>$data,'work_hours'=>$work_hournew));
        $this->db->where('id',$id);
        //$query=$this->db->update('sma_attendence');
      //  echo $this->db->last_query();die();
         return $query=$this->db->update('sma_attendence');
      }
      public function getuser($id)
      {
        $this->db->select('first_name,last_name');
        $this->db->from('sma_users');
      
        $this->db->where('sma_users.id',$id);
         return $this->db->get();
      }
       public function getrfid($id)
      {
        $this->db->select('attendance_id');
        $this->db->from('sma_staff_info');
     
        $this->db->where('sma_staff_info.user_id',$id);
         return $this->db->get();
      }
      public function check_attn_date($id,$intime,$outime)
        {
          $this->db->save_queries=true;
          $this->db->select('*');
           $this->db->from('sma_attendence');
          $this->db->where(array('sma_attendence.user_id'=>$id,'date(sma_attendence.in_time)'=>$intime,'date(sma_attendence.out_time)'=>$outime));
          $query=$this->db->get();
          
          if($query->num_rows() > 0){
           return $query->result();
          }
            else{
              return false;
            }
         
      }
      public function update_Attendance($id,$user_id,$data,$datanew)
      {
        //$this->db->save_queries=true;
        $this->db->where('id',$id);
        $this->db->update('sma_attendence',$data);
         if($this->db->affected_rows()>0){
          //$this->db->save_queries=true;
         $this->db->where('staff_id',$user_id);
          $this->db->update('sma_staff_attendance',$datanew);
       //$this->db->last_query();
         //die();
          return true;

        }
        else
        {
          return false;
        }
        //echo $this->db->last_query(); 
        //die();
      }
      public function user_Get_Attdance($uid,$strt_date,$end)
      {
          $this->db->select('*');
          $this->db->from('sma_users');
          $this->db->join('sma_attendence','sma_attendence.user_id=sma_users.id');
          $this->db->where('sma_attendence.user_id',$uid);
          $this->db->where('sma_attendence.date_entry>=',$strt_date);
          $this->db->where('sma_attendence.date_entry<=',$end);
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
    public function leave_list($id)
    {
      $this->db->select("*");
      $this->db->from('sma_leave');
      $this->db->where('user_id',$id);
      $this->db->where('status','approve');
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
      public function holiday_list()
      {
      $this->db->save_queries=true;
      $this->db->select('*');
      $this->db->from('sma_holiday_info');
      $query=$this->db->get();
   //echo $this->db->last_query();
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
     public function work_form_home($id)
      {
     $this->db->select('*');
        $this->db->from('sma_attendence as sa');
        $this->db->join('sma_staff_attendance as ssa1','ssa1.staff_id=sa.user_id');
        $this->db->join('sma_staff_attendance as ssa2','ssa2.attendance_time=sa.in_time');
        
        $this->db->where('sa.user_id',$id);
        
      $query=$this->db->get();
   //echo $this->db->last_query();
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
    public function get_info($id)
     {
        $this->db->select('*');
        $this->db->from('sma_users');
        $this->db->where('id',$id);
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
      public function get_attn_record($id,$start,$end)
      {
        $this->db->select('*');
        $this->db->from('sma_attendence');
        $this->db->where('user_id',$id);
        $this->db->where('is_late>=',0);
        $this->db->where('date_entry>=',$start);
        $this->db->where('date_entry<=',$end);
        $query=$this->db->get();
        if($query->num_rows() >0)
        {
            return $query->num_rows();
        }
        else{
           return false;
        }
      }
      public function getdatelist($id,$start,$end)
      {
        $this->db->select('*');
        $this->db->from('sma_attendence');
        $this->db->where('user_id',$id);
         $this->db->where('date_entry>=',$start);
        $this->db->where('date_entry<=',$end);
        $query=$this->db->get();
        if($query->num_rows()> 0)
        {
         return $query->result();
        }
        else
        {
          return false;
        }

      }
       public function getalldatelist($start,$end)
      {
        //$this->db->save_queries = true;
        $this->db->select('*');
        $this->db->from('sma_attendence');
        $this->db->join('sma_users','sma_users.id=sma_attendence.user_id');
         $this->db->where('date_entry>=',$start);
        $this->db->where('date_entry<=',$end);
        $query=$this->db->get();
        if($query->num_rows()> 0)
        {

         return $query->result();
        }
        else
        {
          return false;
        }

      }
      public function getleavelist($id)
      {
        //$this->db->save_queries = true;
        $str_date=date('d-M-Y',strtotime($start));
       
        $end_date=date('d-M-Y',strtotime($end));
       //die();
        $this->db->select('*');
        $this->db->from('sma_leave');
        $this->db->where('user_id',$id);
       /* $this->db->where('STR_TO_DATE("leave_date","%d-%b-%Y")>=',$str_date);
        $this->db->where('STR_TO_DATE("leave_date","%d-%b-%Y")<=',$end_date);*/
        $query=$this->db->get();
        //$this->db->get();
        //echo $this->db->last_query();
        //die();
        if($query->num_rows()> 0)
        {
          return $query->result();
        }
        else
        {
          return false;
        }
      }

}
