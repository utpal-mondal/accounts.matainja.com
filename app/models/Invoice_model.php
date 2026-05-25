<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Invoice_model extends CI_Model

{



    public function __construct()

    {

        parent::__construct();

    }
	
	
	public function insert_invoice($data=array()){
		$this->db->insert('invoice',$data);
		return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false ;
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
		   $row->date=date('Y-m-d H:i:s');
		   $row->reference_no=$this->site->getReference('inv');
		   foreach($row as $key=>$val){        
			  if($key != 'id'){ 
			  $this->db->set($key, $val);               
			  }             
		   }
		}
		return ($this->db->insert('sma_invoice')) ? $this->db->insert_id() : false; 
	}
}