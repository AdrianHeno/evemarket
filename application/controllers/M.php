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
		$a = 0;
		foreach($volume->items as $v){//Work out total days of sale and total volume in the last week (6 days really due to time zones)
			if(strtotime($v->date) > $last_week ){
				++ $i;
				$s = $s + $v->volume;
			}
		}
		
		$result['total_volume'] = $s;
		$result['days_of_sale'] = $i;
		return $result;
		//var_dump($result);
	}
	
	function get_items($groupID){
		$this->load->model('Invtypes_model');
		foreach($groupID as $id){
			$result = $this->Invtypes_model->get_by_groupid($id);
			foreach($result as $r){
				if(!empty($r)){
					$itemIDs[$r->typeID] = $r->typeName;
				}	
			}
		}
		return $itemIDs;
	}
	
	function store_region_trades($from_region = 10000002, $to_region = 10000030){//For each group get all items, calculate region trading values and store them into the DB
		set_time_limit(60000);
		$this->load->model('Trading_model');
		$items = $this->get_items(array(109,
83,84,85,86,87,88,89,90,372,373,374,375,376,377,384,385,386,387,394,395,396,425,476,479,482,492,497,498,500,548,648,
653,654,655,656,657,663,772,863,864,892,907,908,909,910,911,916,972,976,1010,1019,728,729,730,731,979,361,97,100,101,
299,470,544,545,549,639,640,641,1023,300,303,304,721,738,739,740,741,742,743,744,745,746,747,748,749,750,751,783,935,
17,18,20,422,423,427,428,429,712,754,886,966,967,974,38,39,40,41,43,46,47,48,49,52,53,54,55,56,57,59,60,61,62,63,65,
67,68,71,72,74,76,77,78,80,82,96,98,201,202,203,205,208,209,210,211,212,213,225,285,289,290,291,295,302,308,309,315,
316,317,321,325,326,328,329,330,338,339,341,353,357,367,378,379,405,406,407,464,472,475,481,483,499,501,506,507,508,
509,510,511,512,514,515,518,524,538,546,585,586,588,589,590,638,642,644,645,646,647,650,658,660,737,753,762,763,764,
765,766,767,768,769,770,771,773,774,775,776,777,778,779,780,781,782,786,815,842,862,878,896,899,901,904,905,661,662,
881,882,977,25,26,27,28,29,30,31,237,324,358,380,381,419,420,463,485,513,540,541,543,547,659,830,831,832,833,834,883,
893,894,898,900,902,906,941,963,1022,255,256,257,258,266,268,269,270,271,272,273,274,275,278,505,989,954,955,956,957,
958));
		
		foreach($items as $item => $value){
			$stored_market_data = $this->Trading_model->get_item_in_region_pair($item, $from_region, $to_region);//Check if we already have an entry for this item and region combination

			if($stored_market_data == NULL OR  $stored_market_data->last_modified < (time() - 172800)){//Does it exist and is its value less than today - 2 day
				if(isset($stored_market_data->id) && $stored_market_data->last_modified < (time() - 172800)){//If it already exists and its time stamp is more than a day old delete the entry
					$this->Trading_model->delete($stored_market_data->id);
				}
				$volume_check = $this->market_volume($to_region, $item);
				
				//if($volume_check['days_of_sale'] > 2){
					$first = $this->market_region($from_region, $item, 'sell');//find min price for item in the forge
					$f_price = array();
					foreach($first->items as $f){
						$f_price[] = $f->price;
					}
					$prices_array[$item]['name'] = $f->type->name;
					$prices_array[$item][$from_region] = min($f_price);
					
					$second = $this->market_region($to_region, $item, 'sell');//find min price for item in heimatar
					$s_price = array();
					foreach($second->items as $s){
						$s_price[] = $s->price;
					}
					$prices_array[$item][$to_region] = min($s_price);
					
					$prices_array[$item]['margin'] = $prices_array[$item][$to_region] - $prices_array[$item][$from_region];//What is the total proffit per item
					$prices_array[$item]['percentage'] = (100 / $prices_array[$item][$to_region]) * $prices_array[$item]['margin']; //Proffit as a percentage of item value
					$prices_array[$item]['movement'] = $volume_check;
					
					$data = array(//Build insertion array
						'from_region' => $from_region,
						'to_region' => $to_region,
						'item_id' => $item,
						'name' => $prices_array[$item]['name'],
						'from_price' => $prices_array[$item][$from_region],
						'to_price' => $prices_array[$item][$to_region],
						'margin' => $prices_array[$item]['margin'],
						'percentage' => $prices_array[$item]['percentage'],
						'volume' => $prices_array[$item]['movement']['total_volume'],
						'days' => $prices_array[$item]['movement']['days_of_sale'],
						'last_modified' => time(),
					);
				if(isset($data['name']) && isset($data['from_price']) && isset($data['to_price'])){
					$this->Trading_model->insert($data);//Insert to DB
//TODO: Add a check to see if the entry already exists in the data base to switch between insert and update
					//$this->Trading_model->insert($data);//Insert to DB
				}
				//}
			}		
		}
	}
	
	function region_trade(){//move through an array of market ids, look up each one in the 2 regions we are compairing against and gather the min sell in each
		set_time_limit(6000);
		$min_margin = 1000000;//Set minimum proffit value per item
		$min_percentage = 20; //Set minimum proffit percentage
		//$items = array(11277 => "asd", 30056 => "ads", 31614 => "zc");
		$items = $this->get_items(array(109,
83,84,85,86,87,88,89,90,372,373,374,375,376,377,384,385,386,387,394,395,396,425,476,479,482,492,497,498,500,548,648,
653,654,655,656,657,663,772,863,864,892,907,908,909,910,911,916,972,976,1010,1019,728,729,730,731,979,361,97,100,101,
299,470,544,545,549,639,640,641,1023,300,303,304,721,738,739,740,741,742,743,744,745,746,747,748,749,750,751,783,935,
17,18,20,422,423,427,428,429,712,754,886,966,967,974,38,39,40,41,43,46,47,48,49,52,53,54,55,56,57,59,60,61,62,63,65,
67,68,71,72,74,76,77,78,80,82,96,98,201,202,203,205,208,209,210,211,212,213,225,285,289,290,291,295,302,308,309,315,
316,317,321,325,326,328,329,330,338,339,341,353,357,367,378,379,405,406,407,464,472,475,481,483,499,501,506,507,508,
509,510,511,512,514,515,518,524,538,546,585,586,588,589,590,638,642,644,645,646,647,650,658,660,737,753,762,763,764,
765,766,767,768,769,770,771,773,774,775,776,777,778,779,780,781,782,786,815,842,862,878,896,899,901,904,905,661,662,
881,882,977,25,26,27,28,29,30,31,237,324,358,380,381,419,420,463,485,513,540,541,543,547,659,830,831,832,833,834,883,
893,894,898,900,902,906,941,963,1022,255,256,257,258,266,268,269,270,271,272,273,274,275,278,505,989,954,955,956,957,
958));
		
		$prices_array = array();
		
		foreach($items as $item => $value){
			$volume_check = $this->market_volume('10000030', $item);

			if($volume_check['days_of_sale'] > 3){//Check if the item has enough movement for us to bother checking prices
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
				$prices_array[$item]['movement'] = $volume_check;
				
				if($prices_array[$item]['margin'] < $min_margin || $prices_array[$item]['percentage'] < $min_percentage){
					unset($prices_array[$item]);
				}
			}
			
		}
		
		$data['prices'] = $prices_array;
		$data['base_url'] = $this->config->item('base_url');
		$this->load->view('region_trading', $data);
	}
	
}
