<?php
/**
* Plugin Name: PT Sign Ups - Beautiful volunteer sign ups and management made easy
* Description: PT Sign Ups makes creating sign ups, organizing group events, and finding volunteers for your school, church, or any organization as simple as ever. An alternative to external sites like SignUp Genius, WP Volunteer Sign Up uses a simple interface to create and manage powerful volunteer sheets all within one powerful plugin.
* Version: 1.0.5
* Author: MJS Software 
* Author URI: https://mjssoftware.com
**/
/* 
* Defining Namespace
*/
namespace ptofficesignup;
/* 
* If this file is called directly or plugin is already defined, abort. 
*/
if ( !defined( 'WPINC' ) ) {
    die;
}

define( 'PTO_SIGN_UP_DIR', plugin_dir_path( __FILE__ ) );
define( "PTO_SIGN_UP_PLUGIN_DIR", plugin_dir_url( __FILE__ ) );
//define( 'SIGNUP_PLUGIN_PATHS', PTO_SIGN_UP_PLUGIN_DIR_path( __FILE__ ) );
define( 'PTO_SIGN_UP_PLUGIN_WITH_CLASSES__FILE__', __FILE__ );
include( 'includes/pto_plugin_activate.php' );
/* 
* Include constant file
*/
include_once( 'constant.php' );
/* 
* Include main class ptoffice
*/
include_once( 'includes/class-ptoffice.php' );
include_once( 'includes/pto-setting-tab.php' );
include_once( 'includes/pto_signups_cpts/cpt_sign_ups_cpt.php' );
include_once( 'includes/pto_task_slots_cpts/pto-task-slots-cpt.php' );
include_once( 'includes/pto_advance_custom_filed/pto_advanced_custom_fileds_cpts.php' );
include_once( 'includes/pto-signup-cron-plugin.php' );
include_once( 'includes/class-cptduplicate.php' );
include_once( 'includes/class-archivecpt.php' );
include_once( 'pto_frontend/pto-shortcode.php' );
include_once( 'pto_frontend/pto-frontend-signup.php' );
/* 
* Declare Classes
*/
use ptofficesignup\classes\Ptoffice;
use ptofficesignup\classes\Duplicators;
use ptofficesignup\classes\PtoSignupSetting;
use ptofficesignup\classes\PtoSignUp;
use ptofficesignup\classes\PtOTaskSlots;
use ptofficesignup\classes\PtoAdvancedfieldCpt;
use ptofficesignup\classes\PtoSingUpPlugin;
use ptofficesignup\classes\PtoCptarchive;
use ptofficesignup\classes\PtoSignupShortcode;
use ptofficesignup\classes\PtoFrontendSignup;
use ptofficesignup\classes\PtoSingUpPluginCron;
/* 
* Check Existance of Class
*/
if(class_exists('ptofficesignup\classes\PtoSignupShortcode')){ 
    new PtoSignupShortcode();
}
if(class_exists('ptofficesignup\classes\Ptoffice')){ 
    new Ptoffice();
}
if(class_exists('ptofficesignup\classes\Ptoffice')){ 
    new Ptoffice();
}
if(class_exists('ptofficesignup\classes\Duplicators')){ 
    new Duplicators();
}
if(class_exists('ptofficesignup\classes\PtoSignupSetting')){ 
    new PtoSignupSetting();
}
if(class_exists('ptofficesignup\classes\PtoSignUp')){ 
    new PtoSignUp();
}
if(class_exists('ptofficesignup\classes\PtOTaskSlots')){ 
    new PtOTaskSlots();
}
if(class_exists('ptofficesignup\classes\PtoAdvancedfieldCpt')){ 
    new PtoAdvancedfieldCpt();
}
if(class_exists('ptofficesignup\classes\PtoSingUpPlugin')){ 
    new PtoSingUpPlugin();
}
if(class_exists('ptofficesignup\classes\PtoCptarchive')){ 
    new PtoCptarchive();
}
if(class_exists('ptofficesignup\classes\PtoSignupShortcode')){ 
    new PtoSignupShortcode();
}
if(class_exists('ptofficesignup\classes\PtoFrontendSignup')){ 
    new PtoFrontendSignup();
}
if(class_exists('ptofficesignup\classes\PtoSingUpPluginCron')){ 
    new PtoSingUpPluginCron();
}