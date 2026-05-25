<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Holiday_model extends CI_Model
{

   public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }
public function registerholiday($data)

    { 
     $this->db->insert('sma_holiday_info',$data);
     if($this->db->affected_rows()>0){

	 	return true;
      }
     else{

			return false;
		 }

	}

	  public function delete_holiday($id)

    		{

          $this->db->delete('sma_holiday_info', array('id' => $id));

        }
         public function getholidayinfo($data=array())

	         {
              $this->db->where('id',$data);

		      return $this->db->get('sma_holiday_info');

		 }
		   public function updateholiday($id1,$datanew=array())

	         {
              $this->db->where('id',$id1);

		 $this->db->update('sma_holiday_info',$datanew);

		 if($this->db->affected_rows()>=0){

		 	$data=array(

                     'table_name'=>'sma_holiday_info',

                     'update_time'=>date('Y-m-d H:i:s') 

					);

					$this->db->insert('sma_updatetime',$data);

				return true;

			}else{

				return false;

				}

		 }
		 function getholiday($data=array())
		 {
		 	 $this->db->where('id',$data);

		return $this->db->get('sma_holiday_info');
		 }

    }