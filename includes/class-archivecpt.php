<?php
/**
* PTO class for initiating necessary actions and core functions.
*/
/*
* Defining Namespace
*/
namespace ptofficesignup\classes;
class PtoCptarchive {
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
    function init() {
        /* archive functionality add in custom cpt */
        add_filter( 'post_row_actions', array( $this, 'pto_sign_up_custom_post_action_links' ), 10, 2 );
        add_action( 'admin_action_pto_archive_project', array( $this, 'pto_sign_up_archive_signup' ) );
        add_action( 'admin_footer-edit.php', array( $this, 'pto_sign_up_status_into_inline_edit' ) );
        add_action( 'init', array( $this, 'pto_sign_up_my_custom_status_creation' ) );
        add_action( 'post_submitbox_misc_actions', array( $this, 'pto_sign_up_add_to_post_status_dropdown' ) );
        add_filter( 'display_post_states', array( $this, 'pto_sign_up_custom_display_post_states' ), 10, 2 );        
    }
    /**
    * Archive display post status 
    * @since    1.0.0
    * @access   public
    **/
    function pto_sign_up_custom_display_post_states( $states, $post ) {
        /* Receive the post status object by post status name */
        $post_status_object = get_post_status_object( $post->post_status );
        
        /* Checks if the label exists */
        if ( in_array( $post_status_object->label, $states, true ) ) {
            return $states;
        }    
        /* Adds the label of the current post status */
        if($post_status_object->label == "Archive")
        {
            $states[ $post_status_object->name ] ="Archived";
        }else{
            $states[ $post_status_object->name ] = $post_status_object->label;
        }        
        return $states;
    }
    
    /**
    * Register post status 
    * @since    1.0.0
    * @access   public
    **/
    function pto_sign_up_my_custom_status_creation() {
        register_post_status('Archive', array(
            'label' => _x('Archive', 'post') ,
            'label_count' => _n_noop('Archive <span class="count">(%s)</span>', 'Archive <span class="count">(%s)</span>') ,
            'public' => true,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true
        ));
    }
    /**
    * Add to post status dropdown
    * @since    1.0.0
    * @access   public
    **/
    function pto_sign_up_add_to_post_status_dropdown() {
        global $post;
        $selected = "";
        if( $post->post_status == 'archive' ){
            $selected = 'selected';
        }
        $status =   ( $post->post_status == 'archive') ? "jQuery( '#post-status-display' ).text( 'Archive' ); jQuery( 
        'select[name=\"post_status\"]' ).val('archive');" : '';
        ?>
        <script>
        jQuery(document).ready( function() {
            jQuery( 'select[name="post_status"]' ).append( '<option value="archive" "<?php echo esc_attr( $selected ); ?>">Archive</option>' );
            "<?php echo sanitize_text_field( $status ); ?> "
            });
            </script>
        <?php
       
        }
    /**
    * Add status to inline edit 
    * @since    1.0.0
    * @access   public
    **/
    function pto_sign_up_status_into_inline_edit() { 
        // ultra-simple example
        global $post;
        $selected = ""; 
        
        if(!empty($post)){
            if( $post->post_status == 'archive' ){
                $selected = 'selected';
                
            }
            
            echo "<script>
            
            jQuery(document).ready( function() {
                
                jQuery( 'select[name=\"_status\"]' ).append( '<option value=\"archive\" ".esc_attr( $selected ).">Archive</option>' );
                
                });
                
                </script>";
            }
        }
        
    /**
    * Action links
    * @since    1.0.0
    * @access   public
    **/
    function pto_sign_up_custom_post_action_links( $actions, $post ) {
        /* check for post type. Here we can also add for any custom post type. */            
        $url = wp_nonce_url(add_query_arg(array(
            'action' => 'pto_archive_project',
            'post' => $post->ID,
        ) , 'admin.php') , basename(__FILE__) , 'duplicate_nonce');
        if($post->post_status == "archive")
        {
            $actions['Unarchive'] = '<a href="' . $url . '&st=1" title="unarchive" rel="permalink">Unarchive</a>';
        }else{
            $actions['archive'] = '<a href="' . $url . '" title="Archive" rel="permalink">Archive</a>';
        }
        return $actions;
    }
    
    /**
    * Archive sign up
    * @since    1.0.0
    * @access   public
    **/
    function pto_sign_up_archive_signup() {
        $post_id  = intval($_GET['post']);
        if ( isset( $_GET['st'] ) ) {            
            wp_update_post(array(
                'ID'    =>  $post_id,
                'post_status'   =>  'publish'
            ));
            $post = get_post( $post_id  );
            // let's get a post title by ID
            $type = sanitize_text_field( $post->post_type ); 
        }else{
            wp_update_post(array(
                'ID'    =>  $post_id,
                'post_status'   =>  'Archive'
            ));
            $post = get_post( $post_id  );
            // let's get a post title by ID
            $type = sanitize_text_field($post->post_type);   
        }
        
        if(isset($_GET['projetct']))
        {
            ?>
            <script type="text/javascript">
                window.opener.reload_cpt(<?php esc_html_e("'" .$type ."'" ) ; ?>);
                window.close();
            </script>
            <?php
        }else{
            $url = "edit.php?post_type=$type";
            
            ?>
            <script type="text/javascript">
                window.location.href = '<?php echo esc_url( $url ); ?>'
            </script>
            <?php
        }
    }
    /* end  archive function */
}