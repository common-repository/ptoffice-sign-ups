<?php
$dir = PTO_SIGN_UP_PLUGIN_DIR;
wp_enqueue_script( 'editor-js', $dir.'assets/js/editor.js', array(), '1.0.0', true );
?>
<div class="stepwizard col-md-offset-3">
   <div class="stepwizard-row setup-panel steps-row">
      <div class="stepwizard-step steps step-one step-active">
         <a href="#step-1" type="button" class="btn btn-default1 btn-primary" style="display: none;">1</a>
         <p class="step-1 btn-circle"><?php esc_html_e( 'Basics', PTO_SIGN_UP_TEXTDOMAIN ); ?></p>
      </div>
      <div class="stepwizard-step step-two steps">
         <a href="#step-2" type="button" class="btn btn-default1" disabled="disabled" style="display: none;">2</a>
         <p class="step-2"><?php esc_html_e( 'Tasks/Slots', PTO_SIGN_UP_TEXTDOMAIN ); ?></p>
      </div>
      <div class="stepwizard-step step-three steps">
         <a href="#step-3" type="button" class="btn btn-default1" disabled="disabled" style="display: none;">3</a>
         <p class="step-3"><?php esc_html_e( 'Advanced Options', PTO_SIGN_UP_TEXTDOMAIN ); ?></p>
      </div>
   </div>
</div>
<div class=" col-md-offset-12" >
   <div class="row setup-content" id="step-1" style="display: block;">
      <div class="col-md-12" >
         <?php include "pto_sign_ups_tabs_one.php"; ?>
      </div>
      <div class="col-md-12 text-center mt-15px">
         <button class="saveBtn button button-primary" type="button"><?php esc_html_e( 'Save', PTO_SIGN_UP_TEXTDOMAIN ); ?></button>
         <button class="nextBtn button button-primary" type="button"><?php esc_html_e( 'Next', PTO_SIGN_UP_TEXTDOMAIN ); ?></button>
      </div>
   </div>
   <div class="row setup-content" id="step-2" style="display: none;">
      <div class="col-md-12">
         <div class="col-md-12">
            <?php include "pto_sign_ups_tabs_two.php"; ?>
         </div>
         <div class="col-md-12 text-center mt-15px">
            <button id="prev" class="prev button button-primary" type="button"><?php esc_html_e( 'Prev', PTO_SIGN_UP_TEXTDOMAIN ); ?></button>
            <button class="saveBtn button button-primary" type="button"><?php esc_html_e( 'Save', PTO_SIGN_UP_TEXTDOMAIN ); ?></button>
            <button id="next" class="nextBtn button button-primary" type="button"><?php esc_html_e( 'Next', PTO_SIGN_UP_TEXTDOMAIN ); ?></button>
         </div>
      </div>
   </div>
   <div class="row setup-content" id="step-3" style="display: none;">
      <div class="col-md-6">
         <div class="col-md-12">
            <?php include "pto_sign_ups_tabs_three.php"; ?>
         </div>
         <div class="col-md-12 text-center mt-15px">
            <button id="prev" class="prev button button-primary" type="button"><?php esc_html_e( 'Prev', PTO_SIGN_UP_TEXTDOMAIN ); ?></button>
            <button class="saveBtn button button-primary" type="button"><?php esc_html_e( 'Save', PTO_SIGN_UP_TEXTDOMAIN ); ?></button>
         </div>
      </div>
   </div>
</div>