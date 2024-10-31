<?php 
global $post;
$pto_field_type = "";
$pto_field_type = get_post_meta( $post->ID, "pto_field_type", true );
$store_data = get_post_meta( $post->ID, "selected_value_field", true ); 
?>  
<input type="hidden" name="signup" value="signup" />
<select name="pto_field_type" id="pto_field_type">
    <option value="text" <?php if ( $pto_field_type == "text" ) { esc_html_e("selected"); } ?>>Text</option>
    <option value="text-area" <?php if ( $pto_field_type == "text-area" ) { esc_html_e("selected"); } ?>>Textarea</option>
    <option value="number" <?php if ( $pto_field_type == "number" ) { esc_html_e("selected"); } ?>>Number</option>
    <option value="checkbox" <?php if ( $pto_field_type == "checkbox" ) { esc_html_e("selected"); } ?>>Checkbox</option>
    <option value="radio"  <?php if ( $pto_field_type == "radio" ) { esc_html_e("selected"); } ?>>Radio</option>
    <option value="drop-down"  <?php if ( $pto_field_type == "drop-down" ) { esc_html_e("selected"); } ?>>Drop Down</option>
</select>
<div class="pto_add_multipale_field">
    <input type="hidden" name="selected_value_field" id="selected_value_field" value="<?php esc_html_e( $pto_field_type ); ?>">
    <div id="multiple_append_field" style="<?php  if ( $pto_field_type == "checkbox" || $pto_field_type == "radio" || $pto_field_type == "drop-down" ) { echo "display: block"; } else { echo "display: none"; } ?>" >
        <?php
        foreach ( $store_data as $key => $value ) {
            $i=0;
            foreach( $value as $key2 => $value2 ) {
                ?>
                <div class="pto_multipalfiled">                      
                    <input type="text" value="<?php esc_html_e( $value2 ); ?>" required="" name="custom-filed-value[]" placeholder="Enter value" />
                    <input type="button" name="remove_filed" class="button button-danger" id="remove_filed" value="remove" onclick="jQuery(this).parent().remove();remove_last_one();" />
                </div>
                <?php
                $i++;
            }
        }
        ?>
    </div>
    <div id="multiples_button_add" style="<?php if ( $pto_field_type == "checkbox" || $pto_field_type == "radio" || $pto_field_type == "drop-down" ) { esc_html_e("display: block"); } else { esc_html_e("display: none"); } ?>">
        <input type="button" name="add_button_fields" id="add_button_fields" class="button button-primary" value="Add" onclick="custom_filed_add_in_field()">
    </div>
</div>
<?php 
wp_reset_postdata();