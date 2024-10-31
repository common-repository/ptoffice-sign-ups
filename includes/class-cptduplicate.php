<?php
/**
 * MYPL class for initiating necessary actions and core functions.
 */
/*
* Defining Namespace
*/
namespace ptofficesignup\classes;
class Duplicators {
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
        $post_type = "";
        $get_type = "";
        $get_page = "";
        $post_type = get_post_type();
        if (isset($_GET['post_type'])) {
            $get_type = sanitize_text_field($_GET['post_type']);
        }
        if (isset($_GET["page"])) {
            $get_page = sanitize_text_field($_GET["page"]);
        }
        // for "page" post type
        if ("pto-signup" == $post_type || "tasks-signup" == $post_type || "pto-custom-fields" == $post_type || $get_type == "pto-signup" || $get_type == "tasks-signup" || $get_type == "pto-custom-fields" || $get_page == "managevolunteer") {
            add_filter('post_row_actions', array($this, 'pto_sign_up_duplicate_post_link'), 10, 2);
            add_filter('page_row_actions', array($this, 'pto_sign_up_duplicate_post_link'), 10, 2);
        }
        add_action( 'admin_action_pto_sign_up_duplicate_post_as_draft', array( $this, 'pto_sign_up_duplicate_post_as_draft' ) );
        add_action( 'admin_notices', array( $this, 'pto_sign_up_duplication_admin_notice' ) );
    }
    /**
    * Duplicate post link
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_duplicate_post_link( $actions, $post ) {
        if (!current_user_can('edit_posts')) {
            return $actions;
        }
        $url = wp_nonce_url( add_query_arg( array(
            'action' => 'pto_sign_up_duplicate_post_as_draft',
            'post' => $post->ID,
        ), 'admin.php'), basename(__FILE__), 'duplicate_nonce' );
        if ( $post->post_type == "pto-signup" ) {
            $actions['duplicate'] = '<a href="' . $url . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
        }
        return $actions;
    }
    /**
    * Duplicate post as draft
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_duplicate_post_as_draft() {
        
        global $wpdb;
        // check if post ID has been provided and action
        if (empty($_GET['post'])) {
            wp_die('No post to duplicate has been provided!');
        }
        // Get the original post id
        $post_id = absint( $_GET['post'] );
        // And all the original post data then
        $post = get_post( $post_id );
        /*
        * if you don't want current user to be the new post author,
        * then change next couple of lines to this: $new_post_author = $post->post_author;
        */
        $current_user = wp_get_current_user();
        $new_post_author = $current_user->ID;
        // if post data exists (I am sure it is, but just in a case), create the post duplicate
        if ($post) {
            // new post data array
            $args = array(
                'comment_status' => $post->comment_status,
                'ping_status' => $post->ping_status,
                'post_author' => $new_post_author,
                'post_content' => $post->post_content,
                'post_excerpt' => $post->post_excerpt,
                'post_name' => $post->post_name,
                'post_parent' => $post->post_parent,
                'post_password' => $post->post_password,
                'post_status' => 'publish',
                'post_title' => $post->post_title . " (Copy)",
                'post_type' => $post->post_type,
                'to_ping' => $post->to_ping,
                'menu_order' => $post->menu_order
            );
            // insert the post by wp_insert_post() function     
            $new_post_id = wp_insert_post( $args );
            /*
            * get all current post terms ad set them to the new post draft
            */
            $taxonomies = get_object_taxonomies(get_post_type( $post )); // returns array of taxonomy names for post type, ex array("category", "post_tag");
            if ($taxonomies) {
                foreach ($taxonomies as $taxonomy) {
                    $post_terms = wp_get_object_terms( $post_id, $taxonomy, array(
                        'fields' => 'slugs'
                    ));
                    wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
                }
            }
            // duplicate all post meta
            $post_meta = get_post_meta( $post_id );
            if ($post_meta) {
                foreach ($post_meta as $meta_key => $meta_values) {
                    if ('_wp_old_slug' == $meta_key) {
                        // do nothing for this meta key
                        continue;
                    }
                }
                $post_type = get_post_type($new_post_id);
                if ("pto-signup" == $post_type || "tasks-signup" == $post_type || "pto-custom-fields" == $post_type) {
                    $base_prefix = $wpdb->get_blog_prefix();
                    
                    $post_meta = $base_prefix . "postmeta";
                    
                    $result_attribute = $wpdb->get_results( "SELECT '' ," . intval( $new_post_id ) . ",meta_key, meta_value FROM " .  esc_sql( $post_meta ) . " WHERE `post_id` =" . intval( $post_id ) );
                    foreach ($result_attribute as $item_arr) {
                        
                        if ($item_arr->meta_key == "pto_sign_ups_time_set") {
                        } else if ($item_arr->meta_key == "pto_sign_up_address") {
                        } else if ($item_arr->meta_key == "pto_sign_up_occurrence") {
                        } else if ($item_arr->meta_key  == "pto_assign_user_administrator") {
                        } else if ($item_arr->meta_key  == "pto_signup_notified_users") {
                        } else if ($item_arr->meta_key  == "pto_task_recurreence") {
                        } else if ($item_arr->meta_key == "single_tasks_advance_options") {
                        } else if ($item_arr->meta_key == "selected_value_field") {
                        } else if ($item_arr->meta_key == "signup_task_availability") {  update_post_meta( $new_post_id, $item_arr->meta_key, 0 );
                        } else if ($item_arr->meta_key == "get_shift_time") { $shift_times = array(); update_post_meta( $new_post_id, $item_arr->meta_key, $shift_times );
                        } else if ( $item_arr->meta_key == "pto_signups_task_slots" ){
                            $sub_post_ids = get_post_meta( $post_id , "pto_signups_task_slots" ,true ); 
                            if( empty( $sub_post_ids ) ){
                                 $sub_post_ids = array();
                            }                            
                            $new_all_post = $this->pto_sign_up_sub_item_duplicate( $sub_post_ids );
                            $all_new_ids = array();
                            foreach($new_all_post as $idkey => $idval){
                                $all_new_ids[] = $idval;
                            }
                            update_post_meta( $new_post_id, "pto_signups_task_slots", $all_new_ids );
                        }
                        else if ( $item_arr->meta_key == "single_task_custom_fields_checkout" ){                            
                            $sub_post_ids = get_post_meta( $post_id , "single_task_custom_fields_checkout" ,true ); 
                            if( empty( $sub_post_ids ) ){
                                 $sub_post_ids = array();
                            }                            
                            $new_all_post = $this->pto_sign_up_sub_item_duplicate( $sub_post_ids );
                            $all_new_ids = array();
                            foreach($new_all_post as $idkey => $idval){
                                $all_new_ids[] = $idval;
                            }
                            update_post_meta( $new_post_id , "single_task_custom_fields_checkout" , $all_new_ids );
                        } 
                        else if( $item_arr->meta_key == "single_task_custom_fields" ){
                            $sub_post_ids = get_post_meta( $post_id, "single_task_custom_fields", true ); 
                            if( empty( $sub_post_ids ) ){
                                 $sub_post_ids = array();
                            }                            
                            $new_all_post = $this->pto_sign_up_task_sub_item_duplicate( $sub_post_ids );
                            $all_new_ids = array();
                            foreach($new_all_post as $idkey => $idval){
                                $all_new_ids[] = $idval;
                            }
                            update_post_meta( $new_post_id, "single_task_custom_fields", $all_new_ids );
                        }
                        else {                            
                            update_post_meta( $new_post_id, $item_arr->meta_key, $item_arr->meta_value );                                                        
                        }
                       
                    }
                    
                    $wpdb->query($wpdb->prepare("INSERT INTO " . esc_sql($post_meta) . " SELECT '' ," . intval($new_post_id) . ",meta_key, meta_value FROM " . esc_sql($post_meta) . " WHERE `post_id` =%d AND meta_key =%d",array($post_id , "pto_sign_ups_time_set")));
                    
                    $wpdb->query($wpdb->prepare("INSERT INTO " . esc_sql($post_meta) . " SELECT '' ," . intval($new_post_id) . ",meta_key, meta_value FROM " . esc_sql($post_meta) . " WHERE `post_id` =%d AND meta_key =%d",array($post_id , "pto_sign_up_address")));
                    
                    $wpdb->query($wpdb->prepare("INSERT INTO " . esc_sql($post_meta) . " SELECT '' ," . intval($new_post_id) . ",meta_key, meta_value FROM " . esc_sql($post_meta) . " WHERE `post_id` =%d AND meta_key =%d",array($post_id , "pto_sign_up_occurrence")));
                   
                    $wpdb->query($wpdb->prepare("INSERT INTO " . esc_sql($post_meta) . " SELECT '' ," . intval($new_post_id) . ",meta_key, meta_value FROM " . esc_sql($post_meta) . " WHERE `post_id` =%d AND meta_key =%d",array($post_id , "pto_assign_user_administrator")));
                    
                    $wpdb->query($wpdb->prepare("INSERT INTO " . esc_sql($post_meta) . " SELECT '' ," . intval($new_post_id) . ",meta_key, meta_value FROM " . esc_sql($post_meta) . " WHERE `post_id` =%d AND meta_key =%d",array($post_id , "pto_signup_notified_users")));
                    
                    $wpdb->query($wpdb->prepare("INSERT INTO " . esc_sql($post_meta) . " SELECT '' ," . intval($new_post_id) . ",meta_key, meta_value FROM " . esc_sql($post_meta) . " WHERE `post_id` =%d AND meta_key =%d",array($post_id , "pto_task_recurreence")));
                    
                    $wpdb->query($wpdb->prepare("INSERT INTO " . esc_sql($post_meta) . " SELECT '' ," . intval($new_post_id) . ",meta_key, meta_value FROM " . esc_sql($post_meta) . " WHERE `post_id` =%d AND meta_key =%d",array($post_id , "single_tasks_advance_options")));
                    
                    $wpdb->query($wpdb->prepare("INSERT INTO " . esc_sql($post_meta) . " SELECT '' ," . intval($new_post_id) . ",meta_key, meta_value FROM " . esc_sql($post_meta) . " WHERE `post_id` =%d AND meta_key =%d",array($post_id , "selected_value_field")));
                }
            }
            if ($post->post_type == "pto-custom-fields") {
                $div_id = "pto_sign_ups_custom_fileds_html";
            ?>
                <script type="text/javascript">
                    window.opener.task_cpt_add_fields("<?php esc_html_e($div_id); ?>", "<?php echo intval( $new_post_id ); ?>");
                    window.close();
                </script>
            <?php
            } else if ($post->post_type == "tasks-signup") {
            ?>
                <script type="text/javascript">
                    opener.pto_task_cpt_call("pto_sign_up_compelling_task_section_list", "<?php echo intval($new_post_id); ?>");
                    window.close();
                </script>
            <?php
            } else if ($post->post_type == "pto-signup") {
                wp_safe_redirect(add_query_arg(array(
                    'post_type' => ('pto-signup'),
                    'saved' => 'post_duplication_created'
                    // just a custom slug here
                ), admin_url('edit.php')));
                
            }
        }
        else 
        {
            wp_die('Post creation failed, could not find original post.');
        }
    }
    /**
    * Duplication admin notice
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_duplication_admin_notice() {
        // Get the current screen
        $screen = get_current_screen();
        if ('edit' !== $screen->base) {
            return;
        }
        $post_type = "";
        $get_type = "";
        $get_page = "";
        $post_type = get_post_type();
        if (isset($_GET['post_type'])) {
            $get_type = sanitize_text_field($_GET['post_type']);
        }
        if (isset($_GET["page"])) {
            $get_page = sanitize_text_field($_GET["page"]);
        }
        // for "page" post type
        if ("pto-signup" == $post_type || "tasks-signup" == $post_type || "pto-custom-fields" == $post_type || $get_type == "pto-signup" || $get_type == "tasks-signup" || $get_type == "pto-custom-fields" || $get_page == "managevolunteer") {
            //Checks if settings updated
            if (isset($_GET['saved']) && 'post_duplication_created' == $_GET['saved']) {
                ?>
                <div class="notice notice-success is-dismissible"><p>Post copy created.</p></div>
                <?php
            }
        }
    }
    /**
    * Duplicate sign up sub item 
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_sub_item_duplicate( $post_ids ) {
        global $wpdb;
        // check if post ID has been provided and action
        if (empty($_GET['post'])) {
            wp_die('No post to duplicate has been provided!');
        }
        $new_allpost_id = array();
        foreach( $post_ids as $id ){
            $post_id = absint($id);
            $post = get_post($post_id);
            $current_user = wp_get_current_user();
            $new_post_author = $current_user->ID;
            if ($post) {
                $args = array(
                    'comment_status' => "'" . $post->comment_status."'",
                    'ping_status' => "'" .$post->ping_status. "'" ,
                    'post_author' => $new_post_author,
                    'post_content' => $post->post_content ,
                    'post_excerpt' => $post->post_excerpt ,
                    'post_name' =>  $post->post_name ,
                    'post_parent' => "'" .$post->post_parent. "'" ,
                    'post_password' =>$post->post_password,
                    'post_status' => 'publish',
                    'post_title' => $post->post_title . " ( Copy)",
                    'post_type' => $post->post_type ,
                    'to_ping' => $post->to_ping,
                    'menu_order' => $post->menu_order
                );
                // insert the post by wp_insert_post( ) function
                $new_post_id = wp_insert_post($args);
                $taxonomies = get_object_taxonomies(get_post_type($post)); // returns array of taxonomy names for post type, ex array( "category", "post_tag");
                if ($taxonomies) {
                    foreach ($taxonomies as $taxonomy) {
                        $post_terms = wp_get_object_terms($post_id, $taxonomy, array(
                            'fields' => 'slugs'
                        ));
                        wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
                    }
                }
                // duplicate all post meta
                $post_meta = get_post_meta($post_id);
                if ($post_meta) {
                    foreach ($post_meta as $meta_key => $meta_values) {
                        if ('_wp_old_slug' == $meta_key) { // do nothing for this meta key
                            continue;
                        }
                    }
                    $post_type = get_post_type($new_post_id);
                    if ("pto-signup" == $post_type || "tasks-signup" == $post_type || "pto-custom-fields" == $post_type) {
                        $base_prefix = $wpdb->get_blog_prefix();
                        
                        $post_meta = $base_prefix . "postmeta";
                        
                        $result_attribute = $wpdb->get_results("SELECT '' ," . intval($new_post_id) . ",meta_key, meta_value FROM " .  esc_sql($post_meta) . " WHERE `post_id` =" . intval($post_id));
                        foreach ($result_attribute as $item_arr) {
                            if ($item_arr->meta_key == "single_tasks_advance_options") {
                            } else if ($item_arr->meta_key == "selected_value_field") {
                            } else if ($item_arr->meta_key == "signup_task_availability") { update_post_meta( $new_post_id, $item_arr->meta_key, 0 );
                            } else if ($item_arr->meta_key == "get_shift_time") { $shift_times = array(); update_post_meta( $new_post_id, $item_arr->meta_key, $shift_times );
                            } else if( $item_arr->meta_key == "single_task_custom_fields" ){
                                $sub_post_ids = get_post_meta( $post_id, "single_task_custom_fields", true ); 
                                if( empty( $sub_post_ids ) ){
                                     $sub_post_ids = array();
                                }                            
                                $new_all_post = $this->pto_sign_up_task_sub_item_duplicate( $sub_post_ids );
                                $all_new_ids = array();
                                foreach($new_all_post as $idkey => $idval){
                                    $all_new_ids[] = $idval;
                                }
                                update_post_meta( $new_post_id , "single_task_custom_fields" , $all_new_ids );
                            }else {
                                update_post_meta($new_post_id, $item_arr->meta_key, $item_arr->meta_value);
                            }
                        }
                        
                        $wpdb->query($wpdb->prepare("INSERT INTO " . esc_sql($post_meta) . " SELECT '' ," . intval($new_post_id) . ",meta_key, meta_value FROM " . esc_sql($post_meta) . " WHERE `post_id` =%d AND meta_key =%d",array($post_id , "single_tasks_advance_options")));
                        
                        $wpdb->query($wpdb->prepare("INSERT INTO " . esc_sql($post_meta) . " SELECT '' ," . intval($new_post_id) . ",meta_key, meta_value FROM " . esc_sql($post_meta) . " WHERE `post_id` =%d AND meta_key =%d",array($post_id , "selected_value_field")));
                    }
                $new_allpost_id[ $new_post_id ] = $new_post_id;
                }
            }else{
                
            }
        }
        return $new_allpost_id;
    }
    /**
    * Duplicate task sub item
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_task_sub_item_duplicate( $post_ids ) {
        global $wpdb;
        // check if post ID has been provided and action
        if (empty($_GET['post'])) {
            wp_die('No post to duplicate has been provided!');
        }
        $new_allpost_id = array();
        foreach( $post_ids as $id ){
            $post_id = absint($id);
            $post = get_post($post_id);
            $current_user = wp_get_current_user();
            $new_post_author = $current_user->ID;
            if ($post) {
                $args = array(
                    'comment_status' => "'" . $post->comment_status."'",
                    'ping_status' => "'" .$post->ping_status. "'" ,
                    'post_author' => $new_post_author,
                    'post_content' => $post->post_content ,
                    'post_excerpt' => $post->post_excerpt ,
                    'post_name' =>  $post->post_name ,
                    'post_parent' => "'" .$post->post_parent. "'" ,
                    'post_password' =>$post->post_password,
                    'post_status' => 'publish',
                    'post_title' => $post->post_title . " ( Copy)",
                    'post_type' => $post->post_type ,
                    'to_ping' => $post->to_ping,
                    'menu_order' => $post->menu_order
                );
                // insert the post by wp_insert_post( ) function
                $new_post_id = wp_insert_post($args);
                $taxonomies = get_object_taxonomies(get_post_type($post)); // returns array of taxonomy names for post type, ex array( "category", "post_tag");
                if ($taxonomies) {
                    foreach ($taxonomies as $taxonomy) {
                        $post_terms = wp_get_object_terms( $post_id, $taxonomy, array(
                            'fields' => 'slugs'
                        ));
                        wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
                    }
                }
                // duplicate all post meta
                $post_meta = get_post_meta($post_id);
                if ($post_meta) {
                    foreach ($post_meta as $meta_key => $meta_values) {
                        if ('_wp_old_slug' == $meta_key) { // do nothing for this meta key
                            continue;
                        }
                    }
                    $post_type = get_post_type($new_post_id);
                    if ("pto-signup" == $post_type || "tasks-signup" == $post_type || "pto-custom-fields" == $post_type) {
                        $base_prefix = $wpdb->get_blog_prefix();
                        //$post_meta = $base_prefix . "post_meta";
                        $post_meta = $base_prefix . "postmeta";
                        //$sql = "SELECT '' ," . $new_post_id . ",meta_key, meta_value FROM " . $base_prefix . "postmeta WHERE `post_id` = %d";
                        //$sql = $wpdb->prepare($sql, array($post_id));
                        //$result_attribute = $wpdb->get_results($sql);
                        $result_attribute = $wpdb->get_results("SELECT '' ," . intval($new_post_id) . ",meta_key, meta_value FROM " .  esc_sql($post_meta) . " WHERE `post_id` =" . intval($post_id));
                        foreach ($result_attribute as $item_arr) { 
                            if ($item_arr->meta_key == "selected_value_field") {
                            } else{
                                update_post_meta($new_post_id, $item_arr->meta_key, $item_arr->meta_value);
                            }                           
                        }
                        //$cpt_selected_data_query = $wpdb->prepare("INSERT INTO " . $base_prefix . "postmeta SELECT '' ," . $new_post_id . ",meta_key, meta_value FROM " . $base_prefix . "postmeta WHERE `post_id` =%d AND meta_key =%d",array($post_id , "selected_value_field"));
                        $wpdb->query($wpdb->prepare("INSERT INTO " . esc_sql($post_meta) . " SELECT '' ," . intval($new_post_id) . ",meta_key, meta_value FROM " . esc_sql($post_meta) . " WHERE `post_id` =%d AND meta_key =%d",array($post_id , "selected_value_field")));
                    }
                $new_allpost_id[$new_post_id]= $new_post_id;
                }
            }else{
                
            }
        }
        return $new_allpost_id;
    }
}