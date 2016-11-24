<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Trading_model extends CI_Model
{

    public $table = 'trading';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get all
    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }
	
	function get_all_min_days()
    {
		$this->db->where('days >', 1);
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }
	
	function get_by_itemid($itemID)
    {
        $this->db->where('item_id', $itemID);
        return $this->db->get($this->table)->result();
    }
	
	function get_item_in_region_pair($itemID, $from_region, $to_region)
    {
        $this->db->where('item_id', $itemID);
		$this->db->where('from_region', $from_region);
		$this->db->where('to_region', $to_region);
        return $this->db->get($this->table)->row();
    }
    
    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id', $q);
	$this->db->or_like('from_region', $q);
	$this->db->or_like('to_region', $q);
	$this->db->or_like('item_id', $q);
	$this->db->or_like('name', $q);
	$this->db->or_like('from_price', $q);
	$this->db->or_like('to_price', $q);
	$this->db->or_like('margin', $q);
	$this->db->or_like('percentage', $q);
	$this->db->or_like('volume', $q);
	$this->db->or_like('days', $q);
	$this->db->or_like('last_modified', $q);
	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
	$this->db->or_like('from_region', $q);
	$this->db->or_like('to_region', $q);
	$this->db->or_like('item_id', $q);
	$this->db->or_like('name', $q);
	$this->db->or_like('from_price', $q);
	$this->db->or_like('to_price', $q);
	$this->db->or_like('margin', $q);
	$this->db->or_like('percentage', $q);
	$this->db->or_like('volume', $q);
	$this->db->or_like('days', $q);
	$this->db->or_like('last_modified', $q);
	$this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    // update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

}

/* End of file Trading_model.php */
/* Location: ./application/models/Trading_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-11-18 04:22:11 */
/* http://harviacode.com */