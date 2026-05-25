<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Leave_model extends CI_Model
{
	public function __construct()
	{
      parent::__construct();

		$this->load->database();

	 }
   public function register($data)
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
    public function registernew($data)
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
       public function getfullleavelist()
       {
       	//$this->save_queries=true;
       	$this->db->select('first_name,last_name,user_id,leave_date,leave_type,payment_type,status,subject');
       	$this->db->from('sma_users');
       	$this->db->join('sma_leave','sma_leave.user_id=sma_users.id');
	    $this->db->where('sma_leave.status','Approve');
	   
	   
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
    public function getlist($id,$leaveyears,$status_list=array())
     { 
     	if(!empty($status_list) && count($status_list)==1) {
     		$this->db->select("user_id,leave_date,leave_type,payment_type,status,subject");
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
     		$this->db->select("user_id,leave_date,leave_type,payment_type,status,subject");
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
     		$this->db->select("user_id,leave_date,leave_type,payment_type,status,subject");
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

 public function updatestatuslist($data=array())
 {
 	
 	$this->db->where(array('user_id'=>$data['user_id'],'leave_date'=>$data['leave_date']));
 	$this->db->set('status',$data['status']);
 	 $this->db->update('sma_leave');
 	  if($this->db->affected_rows()>0)
	       {
	       	return true;
	       }
	       else{
	       	    return false;
	       }
 }
  		public function count_leave($data=array())
 			{
 		//$this->db->save_queries = true;
 			  $query1=$this->db->query("Select count('leave_type') as count_cl from sma_leave where user_id=".$data['user_id']. " and status='Approve' and leave_type='CL'");
 				$query2=$this->db->query("Select count('leave_type') as count_ml from sma_leave where user_id=".$data['user_id']." and status='Approve' and leave_type='ML'");
 	 	        $data_array=array($query1->result_array(),$query2->result_array());
	       	    return $data_array;
	      }
	       public function getusermail($id,$leave_date,$status)
 			{
 			//$this->db->save_queries = true;	
             $query= $this->db->query("SELECT first_name,last_name,email,leave_type,leave_date,status FROM `sma_users` inner join sma_leave on sma_users.id=sma_leave.user_id where status='$status' AND leave_date='$leave_date' ");
             	//echo $this->db->last_query();
           	//die();
              if($query->num_rows()>0)
              {
                return $query->result();   
              }
              else
              {
              	return false;
              }
 			}
 			public function updateleavelist($data=array())
 			{
 		$this->db->where(array('user_id'=>$data['user_id'],'leave_date'=>$data['leave_date']));
 				$this->db->set('payment_type',$data['payment_type']);
 				$this->db->update('sma_leave');
 				 if($this->db->affected_rows()>0)
	       		{
	       	      return true;
	            }
	          else
	          {
	       	    return false;
	           }
 			}
 			  public function getuser()
      			{
			        $this->db->select('first_name,last_name');
			        $this->db->from('sma_users');
			         return $this->db->get();
                 }
                 public function getall_leave($id,$year)
      			{
					$this->db->select("user_id,leave_date,leave_type,payment_type,status,subject");
					       /* $this->db->where(" (leave_date LIKE '%" . $leaveyears . "%' )");*/
					       $this->db->where('YEAR(leave_date)',$year);
					        $this->db->where('user_id',$id);
					        $this->db->where('status','Approve');
					        $query=$this->db->get('sma_leave');
					         if($query->num_rows()>0)
				              {
				                return $query->result();   
				              }
				              else
				              {
				              	return false;
				              }
	      
                 }
                 public function getuserinfo($id){
                 	$this->db->select('*');
                 	$this->db->from('sma_users');
                 	$this->db->where('id',$id);
                 	$query=$this->db->get();
                 	if($query->num_rows() >0)
             	       {
             		    return $query->row();
                 	   }
                 	   else{
                 		     return false;
                 	       }
                 }

  }