<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Trading extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Trading_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $trading = $this->Trading_model->get_all();

        $data = array(
            'trading_data' => $trading
        );

        $this->load->view('trading_list', $data);
    }

    public function read($id) 
    {
        $row = $this->Trading_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'from_region' => $row->from_region,
		'to_region' => $row->to_region,
		'item_id' => $row->item_id,
		'name' => $row->name,
		'from_price' => $row->from_price,
		'to_price' => $row->to_price,
		'margin' => $row->margin,
		'percentage' => $row->percentage,
		'volume' => $row->volume,
		'days' => $row->days,
		'last_modified' => $row->last_modified,
	    );
            $this->load->view('trading_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('trading'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('trading/create_action'),
	    'id' => set_value('id'),
	    'from_region' => set_value('from_region'),
	    'to_region' => set_value('to_region'),
	    'item_id' => set_value('item_id'),
	    'name' => set_value('name'),
	    'from_price' => set_value('from_price'),
	    'to_price' => set_value('to_price'),
	    'margin' => set_value('margin'),
	    'percentage' => set_value('percentage'),
	    'volume' => set_value('volume'),
	    'days' => set_value('days'),
	    'last_modified' => set_value('last_modified'),
	);
        $this->load->view('trading_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'from_region' => $this->input->post('from_region',TRUE),
		'to_region' => $this->input->post('to_region',TRUE),
		'item_id' => $this->input->post('item_id',TRUE),
		'name' => $this->input->post('name',TRUE),
		'from_price' => $this->input->post('from_price',TRUE),
		'to_price' => $this->input->post('to_price',TRUE),
		'margin' => $this->input->post('margin',TRUE),
		'percentage' => $this->input->post('percentage',TRUE),
		'volume' => $this->input->post('volume',TRUE),
		'days' => $this->input->post('days',TRUE),
		'last_modified' => $this->input->post('last_modified',TRUE),
	    );

            $this->Trading_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('trading'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Trading_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('trading/update_action'),
		'id' => set_value('id', $row->id),
		'from_region' => set_value('from_region', $row->from_region),
		'to_region' => set_value('to_region', $row->to_region),
		'item_id' => set_value('item_id', $row->item_id),
		'name' => set_value('name', $row->name),
		'from_price' => set_value('from_price', $row->from_price),
		'to_price' => set_value('to_price', $row->to_price),
		'margin' => set_value('margin', $row->margin),
		'percentage' => set_value('percentage', $row->percentage),
		'volume' => set_value('volume', $row->volume),
		'days' => set_value('days', $row->days),
		'last_modified' => set_value('last_modified', $row->last_modified),
	    );
            $this->load->view('trading_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('trading'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'from_region' => $this->input->post('from_region',TRUE),
		'to_region' => $this->input->post('to_region',TRUE),
		'item_id' => $this->input->post('item_id',TRUE),
		'name' => $this->input->post('name',TRUE),
		'from_price' => $this->input->post('from_price',TRUE),
		'to_price' => $this->input->post('to_price',TRUE),
		'margin' => $this->input->post('margin',TRUE),
		'percentage' => $this->input->post('percentage',TRUE),
		'volume' => $this->input->post('volume',TRUE),
		'days' => $this->input->post('days',TRUE),
		'last_modified' => $this->input->post('last_modified',TRUE),
	    );

            $this->Trading_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('trading'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Trading_model->get_by_id($id);

        if ($row) {
            $this->Trading_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('trading'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('trading'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('from_region', 'from region', 'trim|required');
	$this->form_validation->set_rules('to_region', 'to region', 'trim|required');
	$this->form_validation->set_rules('item_id', 'item id', 'trim|required');
	$this->form_validation->set_rules('name', 'name', 'trim|required');
	$this->form_validation->set_rules('from_price', 'from price', 'trim|required|numeric');
	$this->form_validation->set_rules('to_price', 'to price', 'trim|required|numeric');
	$this->form_validation->set_rules('margin', 'margin', 'trim|required|numeric');
	$this->form_validation->set_rules('percentage', 'percentage', 'trim|required|numeric');
	$this->form_validation->set_rules('volume', 'volume', 'trim|required');
	$this->form_validation->set_rules('days', 'days', 'trim|required');
	$this->form_validation->set_rules('last_modified', 'last modified', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Trading.php */
/* Location: ./application/controllers/Trading.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-11-18 04:22:11 */
/* http://harviacode.com */