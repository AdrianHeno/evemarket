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
	
	function market_volume($region = "10000030", $item = "20211"){//Check the history of an item in a region to see how many items move in a week
		$last_week = strtotime("-1 week");
		$url = "https://crest-tq.eveonline.com/market/{$region}/history/?type=https://crest-tq.eveonline.com/inventory/types/$item/";
		$volume = $this->get_cres($url);
		$result = array();
		$i = 0;
		$s = 0;
		foreach($volume->items as $v){
			if(strtotime($v->date) > $last_week ){
				++ $i;
				$s = $s + $v->volume;
			}
			$result['total_volume'] = $s;
			$result['days_of_sale'] = $i;
		}
		return $result;
	}
	
	function region_trade(){//move through an array of market ids, look up each one in the 2 regions we are compairing against and gather the min sell in each
		set_time_limit(600);
		$items = array('12484', '12487', '20212', '25718', '20211', '12203', '12209', '12205', '12212', '12215', '12207', '10155', '15477', '9950',
					   '13234', '13260', '13261', '3265', '13216', '13225', '13166', '13223', '13218', '13245', '13226', '27186', '19202');
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
			$prices_array[$item]['margin'] = $prices_array[$item]['10000030'] - $prices_array[$item]['10000002'];//What is the total proffit per item
			$prices_array[$item]['percentage'] = (100 / $prices_array[$item]['10000002']) * $prices_array[$item]['margin']; //Proffit as a percentage of item value
			$prices_array[$item]['movement'] = $this->market_volume('10000030', $item);
		}
		
		$data['prices'] = $prices_array;
		$data['base_url'] = $this->config->item('base_url');
		$this->load->view('region_trading', $data);
	}
	
}
