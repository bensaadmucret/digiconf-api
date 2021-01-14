<?php
/*
    Plugin Name: Digiconfs API Get v2
    Plugin URI: https://fr.linkedin.com/in/mohammed-bensaad-developpeur

    Description: Api digiconfs
    Author: Mohammed Bensaad
    Version: 1.0
    Author URI: https://fr.linkedin.com/in/mohammed-bensaad-developpeur
*/
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

  if (!function_exists('wp_insert_post')) {
      require_once ABSPATH . WPINC . '/post.php';
  }

  require "vendor/autoload.php";
  use Jajo\JSONDB;

  $json_db = new JSONDB(__DIR__);







//'https://editionscedille.fr/wp-json/wp/v2/digiconf';
//https://dev.editionscedille.fr/wp-json/wp/v2/digiconf


function get_api()
{
    $apiUrl = 'https://dev.editionscedille.fr/wp-json/wp/v2/digiconf';
    $response = wp_remote_get($apiUrl);
    $responseBody = wp_remote_retrieve_body($response);
    $result = json_decode($responseBody);
    return $result;
}

function insert_digiconf()
{
    global $json_db;
    $result =  get_api();
    if (is_array($result) && ! is_wp_error($result)) {
        foreach ($result as $key => $value):
            $titreProduct[] = $value;
        echo '<pre>';
        //var_dump($titreProduct[0]->acf->event_date_time);
        echo '</pre>';
        endforeach;
        //print_r(array_values($id_post));
        

       
       
        $ids = select_digiconf_id_all();
        if (empty($ids)):
        for ($i= 0; $i < count($titreProduct); $i++):
        $json_db->insert(
            'digiconf.json',
            [
                'id' =>  $titreProduct[$i]->id,
                'title' => $titreProduct[$i]->title->rendered,
                'content' => $titreProduct[$i]->content->rendered,
                'event_date_time'=>$titreProduct[$i]->acf->event_date_time,
                'heures'=>$titreProduct[$i]->acf->heures,
                'url' =>$titreProduct[$i]->acf->url
            ]
        );
        
        endfor;
        endif;
        if (!empty($ids)):
        for ($i= 0; $i < count($titreProduct); $i++):
            $json_db->update(
                [
                 'id' =>  $titreProduct[$i]->id,
                'title' => $titreProduct[$i]->title->rendered,
                'content' => $titreProduct[$i]->content->rendered,
                'event_date_time'=>$titreProduct[$i]->acf->event_date_time,
                'heures'=>$titreProduct[$i]->acf->heures,
                'url' =>$titreProduct[$i]->acf->url
                ]
            )
            ->from('digiconf.json')
            ->where([ 'id' => $titreProduct[$i]->id ])
            ->trigger();
        
        endfor;
        endif;
    }
}

 insert_digiconf();

function select_digiconf_id_all()
{
    global $json_db;

    $digiconfs = $json_db->select('id')
                ->from('digiconf.json')
                ->get();
    return  $digiconfs;
}
  
function order_by_date()
{
    global $json_db,  $titreProduct;
    $apiUrl = 'https://dev.editionscedille.fr/wp-json/wp/v2/digiconf';
    $response = wp_remote_get($apiUrl);
    $responseBody = wp_remote_retrieve_body($response);
    $result = json_decode($responseBody);
    for ($i= 0; $i < count($result); $i++):
    $myPost = $json_db->select('*')
    ->from('digiconf.json')
    ->order_by('event_date_time', JSONDB::ASC)
    ->get();
    endfor;
    return $myPost;
}



function digiconf_api_shortcode_callback()
{
    $data = order_by_date();
    // var_dump($data);
    
    // var_dump($post);
   
    ob_start();
    foreach ($data as $post):
        //var_dump($post);?>
    <div class="container-digiconf">
  <!--Card begin-->
    <div class="card">
        <!--Top row-->
        <div class="card-top" id="company-top">
        <h3><?php echo $post["title"]; ?></h3>     
        </div>
        <!--Content-->
        <div class="card-content">      
        <h4><?php echo $post["title"]; ?></h4> 
        <p><?php echo $post["content"]; ?></p>
        <span><?php echo $post["event_date_time"]; ?></span>
        <span><?php echo $post["heures"]; ?></span>
         <span><?php echo $post["url"]; ?></span>
      </div>
      <!--bottom-->
      <div class="card-bottom"> 
       
      </div>
     </div>
     <!--Card end-->
    </div>
   <?php
    endforeach;
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}
add_shortcode('digiconf', 'digiconf_api_shortcode_callback');
