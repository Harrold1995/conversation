<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 3.0+
 * @link      https://codeat.co
 */
namespace Glossary\Internals;

use  Glossary\Engine ;
/**
 * Post Types and Taxonomies
 */
class PostTypes extends Engine\Base
{
    /**
     * Tax and Post Types labels.
     *
     * @var array
     */
    private  $labels = array() ;
    /**
     * Initialize the class.
     *
     * @return bool
     */
    public function initialize()
    {
        parent::initialize();
        $this->generate_labels();
        \add_action( 'init', array( $this, 'load_cpts' ) );
        \add_action( 'init', array( $this, 'load_taxs' ) );
        if ( !\is_admin() ) {
            \add_filter( 'pre_get_posts', array( $this, 'filter_search' ) );
        }
        \add_filter(
            'posts_orderby',
            array( $this, 'orderby_whitespace' ),
            9999,
            2
        );
        return true;
    }
    
    /**
     * Change the orderby for the glossary auto link system to add priority based on number of the spaces
     *
     * @param string $orderby How to oder the query.
     * @param object $object  The object.
     * @global object $wpdb
     * @return string
     */
    public function orderby_whitespace( string $orderby, $object )
    {
        
        if ( isset( $object->query['glossary_auto_link'] ) ) {
            global  $wpdb ;
            $orderby = '(LENGTH(' . $wpdb->prefix . 'posts.post_title) - LENGTH(REPLACE(' . $wpdb->prefix . "posts.post_title, ' ', ''))+1) DESC";
        }
        
        return $orderby;
    }
    
    /**
     * Add support for custom CPT on the search box
     *
     * @param object $query Wp_Query.
     * @return object
     */
    public function filter_search( $query )
    {
        $post_type = $query->get( 'post_type' );
        
        if ( $query->is_search && 'post' === $post_type ) {
            $post_type = array( $post_type );
            $query->set( 'post_type', \array_push( $post_type, array( 'glossary' ) ) );
        }
        
        return $query;
    }
    
    /**
     * Define the labels of the Glossary post type
     *
     * @return void
     */
    public function generate_labels()
    {
        $single = \__( 'Glossary Term', GT_TEXTDOMAIN );
        $multi = \__( 'Glossary', GT_TEXTDOMAIN );
        $this->labels = array(
            'singular' => $single,
            'plural'   => $multi,
        );
        if ( empty($this->settings['slug']) ) {
            return;
        }
        $this->labels['slug'] = $this->settings['slug'];
    }
    
    /**
     * Generate the parameters for the post type
     *
     * @return array
     */
    public function generate_cpt_parameters()
    {
        $glossary_cpt = array(
            'slug'               => 'glossary',
            'show_in_rest'       => true,
            'menu_icon'          => 'dashicons-book-alt',
            'dashboard_activity' => true,
            'capability_type'    => array( 'glossary', 'glossaries' ),
            'supports'           => array(
            'thumbnail',
            'author',
            'editor',
            'title',
            'genesis-seo',
            'genesis-layouts',
            'genesis-cpt-archive-settings',
            'revisions'
        ),
            'admin_cols'         => array( 'title', 'glossary-cat' => array(
            'taxonomy' => 'glossary-cat',
        ), 'date' => array(
            'title'   => \__( 'Date', GT_TEXTDOMAIN ),
            'default' => 'ASC',
        ) ),
            'admin_filters'      => array(
            'glossary-cat' => array(
            'taxonomy' => 'glossary-cat',
        ),
        ),
        );
        if ( isset( $this->settings['post_type_hide'] ) ) {
            $glossary_cpt['publicly_queryable'] = false;
        }
        if ( isset( $this->settings['archive'] ) ) {
            $glossary_cpt['has_archive'] = false;
        }
        return $glossary_cpt;
    }
    
    /**
     * Initialize the post type
     *
     * @return void
     */
    public function load_cpts()
    {
        $glossary_cpt = $this->generate_cpt_parameters();
        $posttype = \register_extended_post_type( 'glossary', $glossary_cpt, $this->labels );
        $posttype->add_taxonomy( 'glossary-cat', array(
            'hierarchical' => false,
            'show_ui'      => false,
        ) );
    }
    
    /**
     * Load Taxonomies on WordPress
     *
     * @return void
     */
    public function load_taxs()
    {
        $glossary_tax = $this->labels;
        $glossary_tax['plural'] = \__( 'Categories' );
        if ( !empty($this->settings['slug_cat']) ) {
            $glossary_tax['slug'] = $this->settings['slug_cat'];
        }
        \register_extended_taxonomy(
            'glossary-cat',
            'glossary',
            array(
            'public'           => true,
            'dashboard_glance' => true,
            'slug'             => 'glossary-cat',
            'show_in_rest'     => true,
            'capabilities'     => array(
            'manage_terms' => 'manage_glossaries',
            'edit_terms'   => 'manage_glossaries',
            'delete_terms' => 'manage_glossaries',
            'assign_terms' => 'read_glossary',
        ),
        ),
            $glossary_tax
        );
    }

}