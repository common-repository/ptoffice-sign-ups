<?php
/**
* PTO class for initiating necessary actions and core functions.
*/
/*
* Defining Namespace
*/
namespace ptofficesignup\classes;
class PtoAdvancedfieldCpt {
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
        /* advanced custom management cpt */
        add_action( 'init', array( $this, 'pto_sign_up_advanced_Custom_field_add' ) );
        /* acf in all meta boxes */
        add_action( 'add_meta_boxes', array( $this, 'pto_sign_up_acf_meta_boxes' ) );
        /* task post data save */
        add_action( 'save_post_pto-custom-fields', array( $this, 'pto_sign_up_advanced_field_post_save' ), 20, 2 );
    }
    /**
    * Add advanced custom field
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_advanced_Custom_field_add() {
        $my_theme = get_option( 'stylesheet' );
        // Set UI labels for Custom Post Type
        $labels = array(
            'name' => _x( 'Custom Fields', 'Post Type General Name' ),
            'singular_name' => _x( 'pto-advance-field', 'Post Type Singular Name' ),
            'menu_name' => __( 'Custom Fields', $my_theme ),
            'parent_item_colon' => __( 'Parent Custom Fields', $my_theme ),
            'all_items' => __( 'All ACF Fields', $my_theme ),
            'view_item' => __( 'View Custom Fields', $my_theme ),
            'add_new_item' => __( 'Add New Custom Fields', $my_theme ),
            'add_new' => __( 'Add New', $my_theme ),
            'edit_item' => __( 'Edit Custom Fields', $my_theme ),
            'update_item' => __( 'Update Custom Fields', $my_theme ),
            'search_items' => __( 'Search Custom Fields', $my_theme ),
            'not_found' => __( 'Not Found', $my_theme ),
            'not_found_in_trash' => __( 'Not found in Trash', $my_theme ),
        );
        // Set other options for Custom Post Type
        $args = array(
            'label' => __( 'Custom Fields', $my_theme ),
            'description' => __( 'Custom Fields news and reviews', $my_theme ),
            'labels' => $labels,
            // Features this CPT supports in Post Editor
            'supports' => array(
                'title',
            ) ,
            // You can associate this CPT with a taxonomy or custom taxonomy.
            'taxonomies' => array(
                'genres'
            ) ,
            /* A hierarchical CPT is like Pages and can have
                * Parent and child items. A non-hierarchical CPT
                * is like Posts.
            */
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 5,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => true,
            'has_archive' => true,
        );
        // Registering your Custom Post Type
        register_post_type( 'pto-custom-fields', $args );
    }
    /**
    * Sign up add meta box
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_acf_meta_boxes() {
        add_meta_box(
            'pto-acf-Fields-title',
            __( 'Alternate Title', 'sitepoint' ),
           array( $this, 'pto_sign_up_acf_title_field' ), // $callback
           'pto-custom-fields'
       );
        add_meta_box(
            'pto-acf-Fields-instruction',
            __( 'Instruction', 'sitepoint' ),
           array( $this, 'pto_sign_up_acf_instruction_field' ), // $callback
           'pto-custom-fields'
       );
        add_meta_box(
            'pto-acf-Fields-type',
            __( 'Type', 'sitepoint' ),
           array( $this, 'pto_sign_up_acf_type_field' ), // $callback
           'pto-custom-fields'
       );
        add_meta_box(
            'pto-acf-Fields-required',
            __( 'Required', 'sitepoint' ),
           array( $this, 'pto_sign_up_acf_required_field' ), // $callback
           'pto-custom-fields'
       );
        add_meta_box(
            'opener_hdien_filed',
            __( 'opener hidden', 'sitepoint' ),
           array( $this, 'pto_sign_up_hiddden_openr' ), // $callback
           'page'
       );       
    }
    /**
    * Hidden opner
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_hiddden_openr() {
        if( isset( $_GET['pto-display-all-sing-ups'] ) || isset( $_GET['pto-volunteers-sign-ups'] ) || isset( $_GET['pto-checkout-sign-ups'] ) || isset( $_GET['pto-post-sign-thank-you'] ) ) { 
            ?>
            <input type="text" name="opener-window" value="1" />
        <?php } 
    }
    /**
    * Custom title field
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_acf_title_field() {
        global $post;
        $title = "";
        $title = get_post_meta( $post->ID, "pto_alternate_title", true );
        ?>
        <input type="text" name="pto_alternate_title" value="<?php esc_html_e( $title ); ?>" placeholder="Alternate Title" />
        <label><?php esc_html_e( 'For example, "How old are you" could be used instead of "Age?"', PTO_SIGN_UP_TEXTDOMAIN ); ?></label>
        <?php
        wp_reset_postdata();      
    }
    /**
    * Custom instruction field
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_acf_instruction_field() {
        global $post;
        $instruction = "";
        $instruction = get_post_meta( $post->ID, "instruction", true );
        ?>
        <textarea name="instruction" placeholder="instruction" ><?php esc_html_e( $instruction ); ?></textarea>
        <label><?php esc_html_e( 'Explain to members how to best fill out this field', PTO_SIGN_UP_TEXTDOMAIN ); ?></label>
        <?php 
        wp_reset_postdata();
    }
    /**
    * Custom type field
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_acf_type_field() {
        include "structure/pto_custom_fields.php";
    }
    /**
    * Custom required field
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_acf_required_field() {
        global $post;
        $pto_field_required = "";
        $pto_field_required = get_post_meta( $post->ID, "pto_field_required", true );
        ?>
        <select name="pto_field_required" id="pto_field_required">
            <option value="yes"><?php esc_html_e( 'Yes', PTO_SIGN_UP_TEXTDOMAIN ); ?></option>
            <option value="no" <?php if( $pto_field_required == "no" ) { esc_html_e("selected"); } ?>><?php esc_html_e( 'No', PTO_SIGN_UP_TEXTDOMAIN ); ?></option>
        </select>     
        <?php   
        wp_reset_postdata();     
    }
    /**
    * Custom field save post
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_advanced_field_post_save( $post_id, $post ) {
        if( $post->post_type == "pto-custom-fields" ) {
            if( $_POST ) {
                if( isset( $_POST['pto_alternate_title'] ) ) {
                    $pto_alternate_title = sanitize_text_field( $_POST['pto_alternate_title'] );
                    update_post_meta( $post_id, "pto_alternate_title", $pto_alternate_title );
                }
                if( isset( $_POST['instruction'] ) ) {
                    $instruction = sanitize_text_field( $_POST['instruction'] );   
                    update_post_meta( $post_id, "instruction", $instruction );
                }
                if( isset( $_POST['pto_field_type'] ) ) {
                    $pto_field_type = sanitize_text_field( $_POST['pto_field_type'] );
                    update_post_meta( $post_id, "pto_field_type", $pto_field_type );
                    if( $pto_field_type == "checkbox" || $pto_field_type == "radio" || $pto_field_type == "drop-down" ) {
                        $cnt = count( $_POST['custom-filed-value'] );
                        $temp_arr = array();
                        for( $i=0; $i<$cnt; $i++ ) {
                            $to_lower = strtolower( sanitize_text_field($_POST['custom-filed-value'][ $i ]) );
                            $custom_field_key = str_replace( ' ', '_', $to_lower );
                            $temp_arr[ $_POST['selected_value_field'] ][ $custom_field_key ] = sanitize_text_field($_POST['custom-filed-value'][ $i ]);                            
                        }
                        update_post_meta( $post_id, "selected_value_field", $temp_arr );                       
                    }
                }
                if( isset( $_POST['pto_field_required'] ) ) {
                    $pto_field_required = sanitize_text_field( $_POST['pto_field_required'] );
                    update_post_meta( $post_id, "pto_field_required", $pto_field_required );
                }                
                if( isset( $_POST['signup'] ) ) {
                    ?>                    
                    <script type="text/javascript">
                        opener.task_cpt_add_fields( "pto-sign-up-compelling-visibility-section-details", "<?php  echo intval( $post_id ); ?>" );
                        opener.task_cpt_add_fields( "pto_sign_ups_custom_fileds_html", "<?php echo intval( $post_id ); ?>" );
                        window.close();
                    </script>
                    <?php
                    die();                        
                }
                ?>
                <script type="text/javascript">                    
                    window.opener.task_cpt_add_fields( "pto_sign_ups_custom_fileds_html", "<?php echo intval( $post_id ); ?>" );
                    window.close();
                </script>
                <?php
                die();
            }
        }
    }
}