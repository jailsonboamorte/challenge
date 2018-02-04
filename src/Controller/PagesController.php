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

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

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


        echo json_encode($data);
    }

}
