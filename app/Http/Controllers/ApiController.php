<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function postAddCity(Request $request) {
        $add_this_to_json = $request -> getContent();
        $json_string = file_get_contents("data.json");
    
        $add_this_to_json = json_decode($add_this_to_json, true);
        $json_code = json_decode($json_string, true);
        
        $json_code["citys"][$add_this_to_json["name"]] = ["id" => $add_this_to_json["id"],"sights" => []];
        $json_code_encoded = json_encode($json_code, JSON_PRETTY_PRINT);
    
        file_put_contents("data.json", $json_code_encoded);
    }

    public function postAddSight($cityID, Request $request) {
        $add_this_to_json = $request -> getContent();

        $json_string = file_get_contents("data.json");

        $add_this_to_json = json_decode($add_this_to_json, true);
        $json_code = json_decode($json_string, true);

        $json_code["citys"][$cityID]["sights"][$add_this_to_json["name"]] = [
            "id" => $add_this_to_json["id"] ?? null,
            "bildURL" => $add_this_to_json["bildURL"] ?? null,
            "name" => $add_this_to_json["name"] ?? null,
            "beschreibung" => $add_this_to_json["beschreibung"] ?? null
        ];

        $json_code_encoded = json_encode($json_code, JSON_PRETTY_PRINT);

        file_put_contents("data.json", $json_code_encoded);
    }

    public function getCity($cityID) {
        $json_string = file_get_contents("data.json", false);
        $json_code = json_decode($json_string, true);
        $city = $json_code["citys"][$cityID];
        $returnDataCity = [];
        foreach ($city["sights"] as $name => $sight_data) {
            $returnDataCity[] = $name;
        }
    
        header("Content-Type: Application/pdf");
    
        return $returnDataCity;
    }

    public function getSight($cityID, $sightID) {
        $json_string = file_get_contents("data.json", false);
        $json_code = json_decode($json_string, true);
        $sight = $json_code["citys"][$cityID]["sights"][$sightID];
        
        header("Content-Type: Application/pdf");

        return $sight;
    }
}