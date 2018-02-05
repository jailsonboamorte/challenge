<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
//use FB;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

    public $dataUber = [];
    public $tokenUber = "jDZqcMzzOVZZaEFs4xK9fBARi1u_oN1zrSebAtQx";

    public function home() {
        
    }

    public function saveRequest() {
        $this->autoRender = false;
        $data['ok'] = true;
        $data = $this->request->data;
        $this->loadModel('Places');
        $qPla = $this->Places->find('all', ['conditions' => ['Places.code_map' => $data['place_id']]])->first();
        if (isset($qPla->id)) { // dont find 
            $idPlace = $qPla->id;
        } else {
            $place = $this->Places->newEntity();
            $place->code_map = $data['place_id'];
            $place->name = $data['place_name'];
            if ($this->Places->save($place)) {
                $idPlace = $place->id;
            }
        }


        $this->loadModel('Searches');
        $search = $this->Searches->newEntity();
        $search->place_id = $idPlace;
        $search->latitude = $data['lat'];
        $search->longitude = $data['lon'];
        $search->radius = $data['radius'];
        $search->browser = $data['browser'];
        $search->device = $_SERVER['HTTP_USER_AGENT'];

        $this->Searches->save($search);

        $this->dataUber = ['lat_origem' => $data['lat'], 'lon_origem' => $data['lon'], 'lat_destino' => $data['lat_destino'], 'lon_destino' => $data['lon_destino']];
        $prices = $this->getPriceUber();        

        echo json_encode($prices);
    }

    public function getPriceUber() {

        $token = $this->tokenUber;

        $header = ["Authorization: Token $token", "Content-Type: application/json", "Accept-Language: pt_BR"];

// CALCULATE FAIR

        $url = "https://api.uber.com/v1.2/estimates/price?start_latitude={$this->dataUber['lat_origem']}&start_longitude={$this->dataUber['lon_origem']}&end_latitude={$this->dataUber['lat_destino']}&end_longitude={$this->dataUber['lon_destino']}";


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_USERPWD, $token);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output1 = curl_exec($curl);
        $res = json_decode($output1);
        $prices = [];

        foreach ($res->prices as $price) {
            $prices[$price->product_id]['type'] = $price->display_name;
            $prices[$price->product_id]['estimate'] = $price->estimate;
            $prices[$price->product_id]['distance'] = $price->distance;
            $price->lat_origem = $this->dataUber['lat_origem'];
            $price->lon_origem = $this->dataUber['lon_origem'];
            $price->lat_destino = $this->dataUber['lat_destino'];
            $price->lon_destino = $this->dataUber['lon_destino'];
            $prices[$price->product_id]['data'] = json_encode($price);
        }

        return $prices;
    }

    public function rideUber() {
        $response['msg'] = 'ok';
        $this->autoRender = false;
        $data = $this->request->data;

        $token = $this->tokenUber;
        $header = ["Authorization: Bearer $token", "Content-Type: application/json", "Accept-Language: pt_BR"];
//        $fare_id='d30e732b8bba22c9cdc10513ee86380087cb4a6f89e37ad21ba2a39f3a1ba960';
// CALCULATE FAIR
        $dataFare['product_id'] = $data['product_id'];
        $dataFare['start_latitude'] = $data['lat_origem'];
        $dataFare['start_longitude'] = $data['lon_origem'];
        $dataFare['end_latitude'] = $data['lat_destino'];
        $dataFare['end_longitude'] = $data['lon_destino'];
        $data_string = json_encode($dataFare);

        $url = "https://api.uber.com/v1.2/requests/estimate";


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_USERPWD, $token);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output1 = curl_exec($curl);
        $res = json_decode($output1);
//        $prices = [];
//        foreach ($res->prices as $price) {
//            $prices[$price->product_id]['type'] = $price->display_name;
//            $prices[$price->product_id]['estimate'] = $price->estimate;
//            $prices[$price->product_id]['distance'] = $price->distance;
//            $prices[$price->product_id]['data'] = json_encode($price);
//        }

        echo json_encode($response);
    }

}
