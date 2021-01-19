<?php
declare(strict_types = 1);

namespace App;

use Jajo\JSONDB;

class Manager
{
    const API_URL = 'https://editionscedille.fr/wp-json/wp/v2/digiconf';
    public $json_db;

    

    public function __construct()
    {
        $this->json_db =  new \Jajo\JSONDB(__DIR__);
        $this->data = $this->data();
    }
    public function get_api()
    {
        $response = wp_remote_get(self::API_URL);
        $responseBody = wp_remote_retrieve_body($response);
        $result = json_decode($responseBody);
        return $result;
    }

    /**
     * @return array | json
     */
    public function data()
    {
        $result = $this->get_api();

        if (is_array($result) && ! is_wp_error($result)) {
            foreach ($result as $key => $value):
                 $value;
            endforeach;
           
            return  $value;
        }
    }

    /**
     * insertion des données dans le fichier json
     *
     * @return void
     */
    public function insert_all($data)
    {

        $json_db = $this->json_db;
        if (is_array($data)):
        for ($i= 0; $i < count($data); $i++):
        $json_db->insert(
            'digiconf.json',
            [
                'id' =>  $data[$i]->id,
                'title' => $data[$i]->title->rendered,
                'content' => $data[$i]->content->rendered,
                'event_date_time'=>$data[$i]->acf->event_date_time,
                'heures'=>$data[$i]->acf->heures,
                'url' =>$data[$i]->acf->url,
                'theme'=>$data[$i]->acf->theme,
                'societe'=>$data[$i]->acf->societe,
                'image' =>$data[$i]->digiconf_image_src
            ]
        );
        endfor;
        endif;
    }

    public function update_all($data)
    {
        $json_db = $this->json_db;
       // $data = $this->data();

        for ($i= 0; $i < count($data); $i++):
            $json_db->update(
                [
                    'id' =>  $data[$i]->id,
                    'title' => $data[$i]->title->rendered,
                    'content' => $data[$i]->content->rendered,
                    'event_date_time'=> $data[$i]->acf->event_date_time,
                    'heures'=> $data[$i]->acf->heures,
                    'url' => $data[$i]->acf->url,
                    'theme'=>$data[$i]->acf->theme,
                    'societe'=>$data[$i]->acf->societe,
                    'image' =>$data[$i]->digiconf_image_src
                ]
            )->from('digiconf.json')->where([ 'id' => $data[$i]->id ])->trigger();
        endfor;
    }

    public function select_all_by_id()
    {
        $json_db = $this->json_db;
        return  $json_db->select('id')->from('digiconf.json')->get();
    }

    public function select_by_id($i)
    {
        $json_db = $this->json_db;
        return  $json_db->select($id)->from('digiconf.json')->get();
    }

    public function order_by_date()
    {
        $json_db = $this->json_db;
        // $result= $this->get_api();
        
        // for ($i= 0; $i < count($result); $i++):
        $myPost = $json_db->select('*')
                ->from('digiconf.json')
                ->order_by('event_date_time', JSONDB::ASC)
                ->get();
        // endfor;

        return $myPost;
    }
}
