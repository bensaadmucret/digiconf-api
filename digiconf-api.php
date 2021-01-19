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

// Define Our Constants
define('DIGICONF_CORE_INC', dirname(__FILE__).'/assets/inc/');
define('DIGICONF_CORE_IMG', plugins_url('assets/img/', __FILE__));
define('DIGICONF_CORE_CSS', plugins_url('assets/css/', __FILE__));
define('DIGICONF_CORE_JS', plugins_url('assets/js/', __FILE__));
/*
*
*  Register CSS
*
*/
function digiconf_register_core_css()
{
    wp_enqueue_style('digiconf-core', DIGICONF_CORE_CSS . 'digiconf.css', null, time(), 'all');
};
add_action('wp_enqueue_scripts', 'digiconf_register_core_css');
/*
*
*  Register JS/Jquery Ready
*
*/
function digiconf_register_core_js()
{
    // Register Core Plugin JS
    wp_enqueue_script('digiconf-core', DIGICONF_CORE_JS . 'digiconf.js', 'jquery', time(), true);
};
add_action('wp_enqueue_scripts', 'digiconf_register_core_js');


  if (!function_exists('wp_insert_post')) {
      require_once ABSPATH . WPINC . '/post.php';
  }

 require_once __DIR__ . '/vendor/autoload.php';
  //use Jajo\JSONDB;
use App\Manager;

$manager = new Manager();

$result = $manager->get_api();

if (is_array($result) && ! is_wp_error($result)) {
    foreach ($result as $key => $value):
        $data[] = $value;
    endforeach;
    $ids = $manager->select_all_by_id();
    if (empty($ids)):

            $manager->insert_all($data);

    endif;
    if (!empty($ids)):

            $manager->update_all($data);

    endif;


}


/**
 * template | shortcode : digiconf
 *
 * @return html
 *
 */
 

function digiconf_api_shortcode_callback()
{
    global $manager;
    $data = $manager->order_by_date();
  // var_dump($data);
    ob_start(); ?>


<div style="text-align:center;margin-top:25px;font-weight:bold;texxxt-decoration:none;"></div>
<div class="muck-up">
  <div class="overlay"></div>
  <div class="top">
    <div class="nav"></div>
    <div class="user-profile">

      <div class="user-details">
        <h4>DIGI'CONFS by Cédille</h4>
          <hr style="border-style: none;
            border-bottom: var(--separator--height) solid #f1f1f1;
            clear: both;
            margin-top: 10px;
            margin-bottom: 10px;
            margin-left: auto;
            margin-right: auto;
            width: 30%;">
        <p>NOS PROCHAINES DATES</p>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>
  <div class="bottom">
    <div class="title">
      <h3></h3>
      <small></small>
    </div>
    <ul class="tasks">
    <?php foreach ($data as $post): ?>
      <li class="one red">
          <div class="user-profile ">
              <img  src="<?php echo $post["image"]; ?>">
              <span class="task-theme"><?php echo $post["theme"]; ?>
              <?php echo $post["societe"]; ?></span>
          </div>
        <span class="task-title"><?php echo $post["title"]; ?></span>
          <span class="task-cat"><a class="myButton" href="<?php echo $post["url"]; ?>"> Je m'inscris !</a> </span>
        <span class="task-cat"><?php echo $post["event_date_time"]; ?></span>
          <span class="task-cat"><?php echo $post["heures"]; ?></span>
      </li>
        <div class="clearfix"></div>
    <?php  endforeach; ?>
    </ul>
  </div>
</div>


   <?php

    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}
add_shortcode('digiconf', 'digiconf_api_shortcode_callback');
