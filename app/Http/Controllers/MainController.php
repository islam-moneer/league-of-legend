<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Storage;
use Carbon\Carbon;

class MainController extends Controller
{
    //
    public function index(){
    	return view('welcome');
    }

    public function getname(Request $request){
    	$data_output = array();
    	// retrieve data from user
    	$data_requested = $request->all();
    	$summoner_name  = $data_requested['name'];


		// echo $res->getStatusCode(); // 200
		// $response =  $res;

		$client  = new Client(['base_uri' => 'https://eun1.api.riotgames.com/lol/']);
		$api_key = "RGAPI-467a9edb-bcf7-41a4-8a0c-cdcd29528858";
		
		// Get summoner id
		$summoner_data = $client->request('GET', "summoner/v3/summoners/by-name/$summoner_name?api_key=$api_key");
		// dd($summoner_data->getStatusCode());

		if ( $summoner_data->getStatusCode() != 200) {
			return view('/', ['error' => 'Wrong summoner name']);
		}

		$res 		  = $summoner_data->getBody();
		$summoner 	  = json_decode($res);

		// Get last 20 matches
		$match_object = $client->request('GET', "match/v3/matchlists/by-account/$summoner->accountId/recent?api_key=$api_key");
		$match_data   = $match_object->getBody();
		$match_data   = json_decode($match_data);

		// Get all Champions
		$json = Storage::disk('local')->get('lol_champions.json');
		$champ_list_data = json_decode($json, true);
		$champ_list = $champ_list_data['data'];

		// Get match details
		foreach ($match_data->matches as $match) {
			$champ_id = $match->champion;

			$champ_name   = $champ_list["$champ_id"];
			$epoch_time = $match->timestamp + 1;
			$test = substr($epoch_time, 0, 10);

			// dd(is_int($test));
			$data_output[]  = [
				"champ_name" => $champ_name['name'], 
				"champ_icon"=> "https://ddragon.leagueoflegends.com/cdn/7.17.2/img/champion/".$champ_name['key'].".png", 
				"date" => Carbon::createFromTimestamp($test),
				// "time" => date('Y-m-d H:i:s', $test),
				"lane" => $match->lane
			];

		}
    	return view('output', compact('summoner', 'data_output'));

    }
}
