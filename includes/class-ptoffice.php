<?php
/**
* PTO class for initiating necessary actions and core functions.
*/
/*
* Defining Namespace
*/
namespace ptofficesignup\classes;
class Ptoffice {
    /**
    * Constructor for iniation
    * @since    1.0.0
    * @access   public
    **/
    public function __construct() {
        $this->init();
    }
    /**
    * Initiating necessary functions 
    * @since    1.0.0
    * @access   public
    **/
    public function init() {
        /* script enq for admin end backend*/
        add_action( 'admin_enqueue_scripts', array( $this, 'pto_sign_up_admin_script_enq_all_js_css' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'pto_sign_up_script_enq_all_js_css' ) );     
    }
    /**
    * Include JS and CSS for forntend 
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_script_enq_all_js_css() {
        $dir = PTO_SIGN_UP_PLUGIN_DIR;       
        wp_enqueue_script("jquery-ui-core");
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_script( 'jquery-ui-tabs' );
        wp_enqueue_style( 'pto-custom-css-sign-ups',$dir . 'pto_frontend/assets/css/pto-sign-up-custom.css' );
        wp_enqueue_style( 'pto-jquery-ui-css-sign-ups',$dir . 'pto_frontend/assets/css/jquery-ui.css' );
        wp_enqueue_style( 'pto-jquery-fontawesome-sign-ups',$dir . 'pto_frontend/assets/css/fontawesome.css' );
          wp_enqueue_script('font-awesome-js', $dir .  'pto_frontend/assets/js/font-awesome.js', array(), '1.0.0', true);
        /**
        * Enqueue necessary scripts
        */  
        wp_enqueue_script( 'pto-custom-js-sign-up', $dir.'pto_frontend/assets/js/pto-custom.js', array(), '1.0.0', true );
        wp_localize_script( 'pto-custom-js-sign-up', 'pto_front_ajax_url', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce('ajax-nonce')
        ));
        
        
        wp_enqueue_script( 'pto-sweetalert-admin-custom-js-sign-up', plugin_dir_url( dirname(__FILE__) ) . '/assets/js/sweetalert.min.js', array('jquery') , null, true );
    }
    /**
    * Include JS and CSS for backend 
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_admin_script_enq_all_js_css() {
        $dir = PTO_SIGN_UP_PLUGIN_DIR;  
        wp_enqueue_script("jquery-ui-core");
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_script( 'jquery-ui-tabs' );
        
        $post_type = "";
        $get_type = "";
        $get_page = "";
        $post_type = get_post_type();
        if(isset($_GET['post_type'])){
            $get_type = sanitize_text_field($_GET['post_type']);
        }
        if(isset($_GET["page"])){
            $get_page = sanitize_text_field($_GET["page"]);
        }
        if ( "pto-signup" == $post_type || "tasks-signup" == $post_type || "pto-custom-fields" == $post_type || $get_type == "pto-signup" || $get_type == "tasks-signup" || $get_type == "pto-custom-fields" || $get_page == "managevolunteer" ) {     
            wp_enqueue_style('pto-admin-custom-css-sign-ups',$dir . 'assets/css/pto-sign-up-admin-custom.css');            
            /**
            * Enqueue necessary scripts
            */          
            wp_enqueue_script( 'jquery-spectrum-sign-up', $dir.'assets/js/spectrum.min.js', array(), '1.0.0', true );                    
            
            wp_enqueue_script( 'editor-js', $dir.'assets/js/editor.js', array(), '1.0.0', true );
            wp_enqueue_style( 'jquery-ui-css-sign-up', $dir.'assets/js/jquery-ui.css');
            wp_enqueue_script( 'font-awesome-js-sign-up', $dir.'assets/js/font-awesome.js', array(), '1.0.0', true );
            wp_enqueue_script( 'donetyping-js', $dir.'assets/js/donetyping.js', array('jquery'), '1.0.0', true );
            wp_enqueue_script( 'datatable-min-js', $dir.'assets/js/jquery.dataTables.min.js', array('jquery'), '1.0.0', true );
            wp_enqueue_script( 'datatable-buttons-min-js', $dir.'assets/js/dataTables.buttons.min.js', array('jquery'), '1.0.0', true );
            wp_enqueue_script( 'buttons-html5-min-js', $dir.'assets/js/buttons.html5.min.js', array('jquery'), '1.0.0', true );
            wp_enqueue_script( 'pto-admin-custom-js-sign-up', plugin_dir_url(dirname(__FILE__)) . 'assets/js/pto-admin-custom.js', array('jquery') , null, true );
            wp_enqueue_script( 'pto-sweetalert-admin-custom-js-sign-up', plugin_dir_url(dirname(__FILE__)) . 'assets/js/sweetalert.min.js', array('jquery') , null, true );
            wp_localize_script( 'pto-admin-custom-js-sign-up', 'pto_ajax_url', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce('ajax-nonce')
            ));
        }
    }
}