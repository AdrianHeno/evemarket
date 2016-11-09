<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M extends CI_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	private function get_cres($url){//Connect to CRES and execute the supplied GET string
		$curl = curl_init();
		
		//curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . base64_encode("$username:$password")));
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_VERBOSE, true);
		
		$verbose = fopen('php://temp', 'rw+');
		curl_setopt($curl, CURLOPT_STDERR, $verbose);
		
		$data = (curl_exec($curl));
		
		return json_decode($data);
	}
	
	private function market_region($region, $item, $type = "sell"){//Build a GET string to select all orders buy/sell for an item in a region
		$url = "https://crest-tq.eveonline.com/market/{$region}/orders/{$type}/?type=https://crest-tq.eveonline.com/inventory/types/{$item}/";
		return $this->get_cres($url);
	}
	
	function region_trade(){
		set_time_limit(120);
		$items = array('12484', '12487', '20212', '25718', '20211', '12203', '12209', '12205', '12212', '12215', '12207');
		//$items = array('12484', '12487');
		
		$prices_array = array();
		foreach($items as $item){
			$first = $this->market_region('10000002', $item, 'sell');//find min price for item in the forge
			$f_price = array();
			foreach($first->items as $f){
				$f_price[] = $f->price;
			}
			$prices_array[$item]['name'] = $f->type->name;
			$prices_array[$item]['10000002'] = min($f_price);
			
			$second = $this->market_region('10000030', $item, 'sell');//find min price for item in heimatar
			$s_price = array();
			foreach($second->items as $s){
				$s_price[] = $s->price;
			}
			
			$prices_array[$item]['10000030'] = min($s_price);
			$prices_array[$item]['margin'] = $prices_array[$item]['10000030'] - $prices_array[$item]['10000002'];
			$prices_array[$item]['percentage'] = (100 / $prices_array[$item]['10000002']) * $prices_array[$item]['margin'];
		}
		
		$data['prices'] = $prices_array;
		$data['base_url'] = $this->config->item('base_url');
		$this->load->view('region_trading', $data);
	}
	
}
