<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Resource_model extends CI_Model

{



    public function __construct()

    {

        parent::__construct();

    }
	
	
	public function insert_resource($data=array()){
		$this->db->insert('sma_resource',$data);
		return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false ;
	}
	
	public function update_image($id, $data){
		$this->db->where('id',$id);
		$this->db->update('sma_resource',$data);
	}
	
	public function update_bill($id, $data){
		$this->db->where('id',$id);
		$this->db->update('sma_resource',$data);
	}
	
	public function insert_invoice_item($data=array()){
		$this->db->insert('sma_invoice_item',$data);
		return ($this->db->affected_rows()>0) ? true : false ;
	}
	
	
	public function getInvoicedetailByID($id)

    {
        $q = $this->db->get_where('sma_invoice', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }else{
			return false;
		}

    }
	
	public function getinvoiceitems($id){
		$this->db->select('id as product_id, product_description, hour, amount, price');
		$this->db->from('sma_invoice_item');
		$this->db->where('invoice_id',$id);
		$query=$this->db->get();
		if ($query->num_rows() > 0) {
            return $query->result();
        }else{
			return false;
		}
				
		}
		
	public function deleteinvoice($id)
    {
		$query=$this->db->delete('sma_invoice', array('id' => $id)); 
        if ($this->db->affected_rows() > 0) {
            return true;
        }else{
			return false;
		}

    }
	public function deleteresource($data)
	{
		$this->db->delete('sma_resource', array('id' => $data)); 
        if ($this->db->affected_rows() > 0) {
			$this->db->delete('sma_resource_activity', array('RID' =>$data)); 
            return true;
        }else{
			return false;
		}
		}
	
	public function deleteinvoiceitems($id){
		$query=$this->db->delete('sma_invoice_item', array('invoice_id' => $id)); 
        if ($this->db->affected_rows() > 0) {
            return true;
        }else{
			return false;
		}
	}
	
	public function update_invoice($id='',$data=array()){
		$this->db->where('id',$id);
		$this->db->update('sma_invoice',$data);
		 if ($this->db->affected_rows() >= 0) {
            return true;
        }else{
			return false;
		}
	}
	
	public function duplicate_invoice($id=''){
		$this->db->where('id', $id); 
		$query = $this->db->get('sma_invoice');
		
		foreach ($query->result() as $row){   
		   foreach($row as $key=>$val){        
			  if($key != 'id'){ 
			  $this->db->set($key, $val);               
			  }             
		   }
		}
		return ($this->db->insert('sma_invoice')) ? $this->db->insert_id() : false; 
	}
	/*Added on 28.12.2017 by abhinaba getresourceinfo*/
	  public function getresourceinfo($data2)  
	 {
	 	$this->db->select('sma_resource.id,sma_resource.purchase_date,sma_resource.resource,sma_resource.name as resource_name,sma_resource.model,sma_resource.serial_no,sma_resource.warranty,sma_resource.damage,sma_resource.assign,sma_resource.image,sma_resource.bill,sma_resource_activity.RID,sma_resource_activity.user_id,sma_resource_activity.name,sma_resource_activity.modified_date,sma_resource_activity.status');
	 	$this->db->from('sma_resource');
	 	$this->db->join('sma_resource_activity','sma_resource.id=sma_resource_activity.RID','left');
	 	$this->db->where('sma_resource.id',$data2);
     	return $this->db->get();;
		 }
		  public function getresourceinfo11($data2)  
	 	   {
			 $this->db->where(array('RID'=>$data2,'status'=>'Assigned'));
			 return $this->db->get('sma_resource_activity');
		    }
		     public function getresourceinfobyid($data2)  
	 	   		{
			 		$this->db->where('id',$data2);
			 		$q=$this->db->get('sma_resource');
			 		  if ($q->num_rows() > 0) {

            foreach (($q->result()) as $row) {

                $data[] = $row;

            }

            return $data;

        }

        return FALSE;
			  
		    }
		     public function getresourceinfobyid1($data2)  
	 	   		{
			 		$this->db->where('RID',$data2);
			 		$q=$this->db->get('sma_resource_activity');
			 		  if ($q->num_rows() > 0) {

            foreach (($q->result()) as $row) {

                $data[] = $row;

            }

            return $data;

        }

        return FALSE;
			  
		    }


       public function getresourceinfo1()  
	 {
	 	//die('true');
	 		$this->db->select('*');
	 		$this->db->from('sma_resource');
	 		$this->db->join('sma_resource_activity','sma_resource.id=sma_resource_activity.RID');
	 		$this->db->where('sma_resource_activity.status="assigned"');
	 		return $this->db->get();
    		
		
		 }

		 public function getresourceassigneduser($id=null){
		 	//echo $id;die();
		 	$this->db->select('*');
	 		$this->db->from('sma_resource_activity');
	 		$this->db->where(array('RID'=>$id,'status'=>'Assigned'));
	 		$query=$this->db->get();
	 		if($query->num_rows()>0){return $query->result();}
	 		else{return false;}
		 }

		   /* public function getresourceinfo12()  
	 		{
	 	
	 	$this->db->get('sma_resource');
		 if ($this->db->affected_rows() >= 0) {
            return true;
        }else{
			return false;
		}
		
		 }*/
		 

		 
		 	/*Added on 28.12.2017 by abhinaba update_resource*/
			
		  public function update_resource($id1,$data2) 
	 	{
		 
		 	$this->db->where('id',$id1);
		 	$this->db->update('sma_resource',$data2);
			if ($this->db->affected_rows() > 0) {
			
            	return true;
        	}
        	else
        	{
				return false;
			}
		 }
  public function update_resource_activity($id1,$data2) 
	 	{
		 
		 	$this->db->where('id',$id1);
		 	$this->db->where('status','UnAssined');
		 	$this->db->update('sma_resource_activity',$data2);
			if ($this->db->affected_rows() > 0) {
			
            	return true;
        	}
        	else
        	{
				return false;
			}
		 }
          
		  public function insert_resource_activity($data2) 
	 {
		 
		 
	 $this->db->insert('sma_resource_activity',$data2); 
	if($this->db->affected_rows()>0)
		 {
		return $this->db->insert_id();
		 }
		 else
		 {
		 	return false;
		 }
}
		    public function getresourceactivity($data2)  
	 {
	 	//die('true');
	 	
		 $this->db->where(array('RID'=>$data2));
		return $this->db->get('sma_resource_activity');
    		
		
		 }

		 public function insert_new_resource_activity($id,$data3){
		 $this->db->set('status','Unassigned');
		  $this->db->where(array('RID'=>$id));
		  $this->db->update('sma_resource_activity');
		 if($this->db->affected_rows()>=0)
		 {
		  $this->db->insert('sma_resource_activity',$data3); 
		return $this->db->insert_id();
		 }
		 else
		 {
		 	return false;
		 }


		 }

 		public function delete_resource1($id1)
 		{
         $this->db->where(array('RID'=>$id1));
         $this->db->delete('sma_resource_activity');
         if($this->db->affected_rows()>0)
         {
         	return true;
         } 
         else
         {
         	return false;
         }
  		}


	}