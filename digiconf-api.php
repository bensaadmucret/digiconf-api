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


function digiconf_api()
{
    $labels = array(
        'name'               => esc_html__('digiconfs', 'mzb'),
        'singular_name'      => esc_html__('digiconf', 'mzb'),
        'add_new'            => esc_html__('Add new', 'mzb'),
        'add_new_item'       => esc_html__('Add new digiconf', 'mzb'),
        'edit_item'          => esc_html__('Edit digiconf', 'mzb'),
        'new_item'           => esc_html__('New digiconf', 'mzb'),
        'all_items'          => esc_html__('All digiconfs', 'mzb'),
        'view_item'          => esc_html__('View digiconf', 'mzb'),
        'search_items'       => esc_html__('Search digiconfs', 'mzb'),
        'not_found'          => esc_html__('No digiconfs found', 'mzb'),
        'not_found_in_trash' => esc_html__('No digiconfs found in trash', 'mzb'),
        'parent_item_colon'  => '',
        'menu_name'          => esc_html__('digiconfs', 'mzb')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'digiconf','with_front' => false ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'       	=> true,
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'rest_base'             => 'digiconf',
    );

    register_post_type('digiconf', $args);

    register_taxonomy('digiconf-category', 'digiconf', array(
        'hierarchical' => true,
        'labels' => array(
            'name' 				=> esc_html__('Category', 'mzb'),
            'singular_name' 	=> esc_html__('Category', 'mzb'),
            'search_items' 		=> esc_html__('Search category', 'mzb'),
            'all_items' 		=> esc_html__('All categories', 'mzb'),
            'parent_item' 		=> esc_html__('Parent category', 'mzb'),
            'parent_item_colon' => esc_html__('Parent category', 'mzb'),
            'edit_item' 		=> esc_html__('Edit category', 'mzb'),
            'update_item' 		=> esc_html__('Update category', 'mzb'),
            'add_new_item' 		=> esc_html__('Add new category', 'mzb'),
            'new_item_name' 	=> esc_html__('New category', 'mzb'),
            'menu_name' 		=> esc_html__('Categories', 'mzb'),
        ),
        'rewrite' => array(
            'slug' 		   => 'digiconf-category',
            'with_front'   => true,
            'hierarchical' => true
        ),
        'show_in_nav_menus' => true,
        'show_tagcloud'     => true,
        'show_admin_column' => true,
        'show_in_rest'       	=> true,
        'rest_controller_class' => 'WP_REST_Terms_Controller',
        'rest_base'             => 'digiconf_category',
    ));

    register_taxonomy('digiconf-tag', 'digiconf', array(
        'hierarchical' => false,
        'labels' => array(
            'name' 				=> esc_html__('digiconfs tags', 'mzb'),
            'singular_name' 	=> esc_html__('digiconfs tag', 'mzb'),
            'search_items' 		=> esc_html__('Search digiconf tags', 'mzb'),
            'all_items' 		=> esc_html__('All digiconf tags', 'mzb'),
            'parent_item' 		=> esc_html__('Parent digiconf tags', 'mzb'),
            'parent_item_colon' => esc_html__('Parent digiconf tag:', 'mzb'),
            'edit_item' 		=> esc_html__('Edit digiconf tag', 'mzb'),
            'update_item' 		=> esc_html__('Update digiconf tag', 'mzb'),
            'add_new_item'	    => esc_html__('Add new digiconf tag', 'mzb'),
            'new_item_name' 	=> esc_html__('New digiconf tag', 'mzb'),
            'menu_name' 		=> esc_html__('Tags', 'mzb'),
        ),
        'rewrite' 		   => array(
            'slug' 		   => 'digiconf-tag',
            'with_front'   => true,
            'hierarchical' => false
        ),
        'show_in_rest'       	=> true,
        'rest_controller_class' => 'WP_REST_Terms_Controller',
        'rest_base'             => 'digiconf_tag',
    ));
}

add_action('init', 'digiconf_api');

function mzb_register_rest_fields()
{
    register_rest_field(
        'digiconf',
        'digiconf_category_attr',
        array(
            'get_callback'    => 'digiconf_api_categories',
            'update_callback' => null,
            'schema'          => null
        )
    );

    register_rest_field(
        'digiconf',
        'digiconf_tag_attr',
        array(
            'get_callback'    => 'digiconf_api_tags',
            'update_callback' => null,
            'schema'          => null
        )
    );

    register_rest_field(
        'digiconf',
        'digiconf_image_src',
        array(
            'get_callback'    => 'digiconf_api_image',
            'update_callback' => null,
            'schema'          => null
        )
    );
}
add_action('rest_api_init', 'mzb_register_rest_fields');



//'https://editionscedille.fr/wp-json/wp/v2/digiconf';

function get_api()
{
    $apiUrl = 'https://editionscedille.fr/wp-json/wp/v2/digiconf';
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
            $titreProduct = $value;
        echo '<pre>';
        //var_dump($titreProduct->title->rendered);
        $id_post = $titreProduct->id;
        echo '</pre>';
        $ids = select_digiconf_id();
        
        if (empty($ids)):
            $json_db->insert(
                'digiconf.json',
                [
                    'id'   => $titreProduct->id,
                    'content'=>$titreProduct->content->rendered,
                    'title' =>$titreProduct->title->rendered,
                
                ]
            );

        endif;
       
      
        

        endforeach;
    }
}

 insert_digiconf();

function select_digiconf_id()
{
    global $json_db;

    $digiconfs = $json_db->select('id')
                ->from('digiconf.json')
                ->get();
    return  $digiconfs;
}
  

/*************************************
 *
 */
function digiconf_api_categories($object, $field_name, $request)
{
    $terms_result = array();

    $terms =  wp_get_post_terms($object['id'], 'digiconf-category');

    foreach ($terms as $term) {
        $terms_result[$term->term_id] = array($term->name,get_term_link($term->term_id));
    }

    return $terms_result;
}

function digiconf_api_tags($object, $field_name, $request)
{
    $terms_result = array();

    $terms =  wp_get_post_terms($object['id'], 'digiconf-tag');

    foreach ($terms as $term) {
        $terms_result[$term->term_id] = array($term->name,get_term_link($term->term_id));
    }

    return $terms_result;
}

function digiconf_api_image($object, $field_name, $request)
{
    $img = wp_get_attachment_image_src($object['featured_media'], 'full');
    
    return $img[0];
}

function mzb_styles_scripts()
{
    wp_enqueue_style('tuts', plugins_url('/css/digiconf.css', __FILE__));
    wp_register_script('tuts', plugins_url('/js/digiconf.js', __FILE__), array('jquery'), '', true);

    wp_localize_script(
        'tuts',
        'tuts_opt',
        array('jsonUrl' => rest_url('acf/v3/digiconf'))
    );
}
add_action('wp_enqueue_scripts', 'mzb_styles_scripts');

function digiconf_api_shortcode_callback($atts, $content = null)
{
    extract(
        shortcode_atts(
            array(
            'layout'     => 'grid', // grid / list
            'per_page'   => '3', 	// int number
            'start_cat'  => '', 	// starting category ID
        ),
            $atts
        )
    );

    global $post;

    $query_options = array(
        'post_type'           => 'digiconf',
        'post_status'         => 'publish',
        'ignore_sticky_posts' => 1,
        'orderby'             => 'date',
        'order'               => 'DESC',
        'posts_per_page' 	  => absint($per_page)
    );

    if (isset($start_cat) & !empty($start_cat)) {
        $tax_query_array = array(
            'tax_query' => array(
            array(
                'taxonomy' => 'digiconf-category',
                'field'    => 'ID',
                'terms'    => $start_cat,
                'operator' => 'IN'
            ))
        );

        $query_options = array_merge($query_options, $tax_query_array);
    }

    $tuts = new WP_Query($query_options);

    if ($tuts->have_posts()) {
        wp_enqueue_script('tuts');

        $output = '';
        $class  = array();

        $class[] = 'recent-tuts';
        $class[] = esc_attr($layout);

        $output .= '<div class="recent-tuts-wrapper">';

        $args = array(
                'orderby'           => 'name',
                'order'             => 'ASC',
                'fields'            => 'all',
                'child_of'          => 0,
                'parent'            => 0,
                'hide_empty'        => true,
                'hierarchical'      => false,
                'pad_counts'        => false,
            );

        $terms = get_terms('digiconf-category', $args);


        if (count($terms) != 0) {
            $output .= '<div class="term-filter" data-per-page="'.absint($per_page).'">';

            if (empty($start_cat)) {
                $output .= '<a href="'.esc_url(get_post_type_archive_link('digiconf')).'" class="active">'.esc_html__('All', 'mzb').'</a>';
            }

            foreach ($terms as $term) {
                $term_class = (isset($start_cat) && !empty($start_cat) && $start_cat == $term->term_id) ? $term->slug.' active' : $term->slug;
                $term_data  = array();

                $term_data[] = 'data-filter="'.$term->slug.'"';
                $term_data[] = 'data-filter-id="'.$term->term_id.'"';

                $output .= '<a href="'.esc_url(get_term_link($term->term_id, 'digiconf-category')).'" class="'.esc_attr($term_class).'" '.implode(' ', $term_data).'>'.$term->name.'</a>';
            }

            $output .= '</div>';
        }

        $output .= '<div class="'.implode(' ', $class).'">';
        while ($tuts->have_posts()) {
            $tuts->the_post();

            $IMAGE = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full', false);

            $output .= '<div class="container-digiconf">';

            $output .= '<img src="'.esc_url($IMAGE[0]).'" alt="'.esc_attr(get_the_title()).'" />';

           

            $output .=' <div class="card-top" id="company-top">';
            $output .= '<h3>' . get_the_term_list(get_the_ID(), 'digiconf-category', '', ', ', '').  '</h3> ';
            $output .='</div>';

            $output .='<div class="card-content">';

            if ('' != get_the_title()) {
                $output .='<h4 class="digiconf-title entry-title">';
                $output .= '<a href="'.get_the_permalink().'" title="'.get_the_title().'" rel="bookmark">';
                $output .= get_the_title();
                $output .= '</a>';
                $output .='</h4>';
            }

            if ('' != get_the_excerpt() && $layout == 'grid') {
                $output .='<div class="digiconf-excerpt">';
                $output .= get_the_excerpt();
                $output .='</div>';
            }

            $output .='<div class="digiconf-tag">';
            $output .= get_the_term_list(get_the_ID(), 'digiconf-tag', '', ' ', '');
            $output .='</div>';

            $output .='</div>';

            $output .= '</div>';
        }
        wp_reset_postdata();
        $output .= '</div>';
        
        $output .= '</div>';

   

        return $output;
    }
}
add_shortcode('digiconf', 'digiconf_api_shortcode_callback');
