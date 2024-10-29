<?php
/*
Plugin Name: Beautiful Login Page
Plugin URL: http://interactivemonkey.com.br
Description: Turn on your login page. Leave it with a more modern face using the Beautiful Login Page. Simple and easy to use.
Version: 3.0.1
Author: Interactive MOnkey
Author URI: http://interactivemonkey.com.br
Contributors: Interactive MOnkey
Text Domain: beautiful-login-page
Domain Path: /languages
*/
if(!defined('BLP_PLUGIN_URL')) {
	define('BLP_PLUGIN_URL', plugin_dir_url( __FILE__ ));
}

register_activation_hook( __FILE__, 'blp_activate_plugin' );

add_action( 'activated_plugin', 'blp_active_plugin' );
function blp_active_plugin( $plugin ) {  
  if( $plugin == plugin_basename( __FILE__ ) ) {
     $redir = wp_redirect( admin_url( 'options-general.php?page=beautiful-login-page.php' ) );
     exit( $redir );
  }
}

function blp_activate_plugin(){
  register_uninstall_hook( __FILE__, 'blp_deactive_plugin' );
} 
function blp_deactive_plugin(){  
  $blp = get_option( 'blp_meta_data' );
  if(isset($blp['apagar_dados']) && !empty($blp['apagar_dados']) && $blp['apagar_dados'] == 'on') {
    delete_option("blp_meta_data");
  }
}
add_action( 'plugins_loaded', 'blp_textdomain' );  
function blp_textdomain() {
    load_plugin_textdomain( 'beautiful-login-page', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'admin_enqueue_scripts', 'admin_blp_style' );
function admin_blp_style() {                 
  wp_enqueue_style('thickbox');
  wp_enqueue_script('thickbox');  
  wp_enqueue_style( 'wp-color-picker');
  wp_enqueue_script( 'wp-color-picker');        
  wp_enqueue_script( 'js_main', plugins_url('js/main.js',  __FILE__ ) , array('jquery'), '1.0.0', true );
  wp_enqueue_script( 'js_main' );  
  wp_enqueue_script('media-upload'); 
  wp_enqueue_media();                  
}

add_action( 'login_enqueue_scripts', 'blp_login_style' );
function blp_login_style() {     
  
  $blp = get_option( 'blp_meta_data' );    
  
  $tipo_fonte = $blp['tipo_fonte'];
  if(!isset($tipo_fonte) || empty($tipo_fonte)){
    $tipo_fonte = 'Indie+Flower';
  }
  wp_register_style( 'body_css', 'https://fonts.googleapis.com/css?family='.$tipo_fonte , false, '1.0.0');
  wp_enqueue_style( 'body_css' );      
  wp_register_style( 'login_css', plugins_url('/css/login.css', __FILE__) , false, '1.0.0' );
  wp_enqueue_style( 'login_css' );            
  wp_enqueue_script("jquery");
  
  $tema = $blp['blp_tema'];
  if(!isset($tema)){
    $tema = 1;
  } 
  if($tema == 3){
    wp_register_script( 'js_carousel', plugin_dir_url( __FILE__ ) . 'js/carousel.js', array('jquery'), false );
    wp_enqueue_script( 'js_carousel' );
  }      
                          
}
add_filter( 'login_headertext', 'blp_url_title' );
function blp_url_title() {
    return get_bloginfo('name') ;
}
add_filter('login_headerurl', 'blp_login_url');
function blp_login_url() {
        return get_bloginfo('url');
}
add_action( 'login_header', 'blp_header_css', 20 );  
function blp_header_css() {     
  $blp = get_option( 'blp_meta_data' );    
  $tema = $blp['blp_tema'];
  if(!isset($tema)){
    $tema = 1;
  } 
  $tipo_fonte = $blp['tipo_fonte'];
  if(!isset($tipo_fonte)){
    $tipo_fonte = 'PT+Sans+Narrow';
  }  
  $bg = $blp['img_box_login'];
  if(!isset($bg)){
    $bg = plugin_dir_url( __FILE__ ).'img/blp-login.png';
  }
  $bg_color = $blp['bg_color'];
  if(!isset($bg_color)){
     $bg_color = '#000000';
  }      
  $text_color = $blp['text_color'];
  if(!isset($text_color)){
     $text_color = '#ffffff';
  }                     
 
?>
<style>
  body{
    background:#ffffff !important;
    font-family:<?php echo str_replace("+"," ",$tipo_fonte); ?>, arial; 
  }  
  .left-div{
    background-color:<?php echo $bg_color; ?>;
    background-size:cover;
    background-position:center center;
    background-repeat:no-repeat;
    overflow:hidden;
  }
  .left-div h1,
  .left-div h3{
    color: <?php echo $text_color; ?> !important;
  }  
  .login #user_login,
  .login #user_pass{
    border-bottom:3px solid <?php echo $bg_color; ?>; 
    background:#ffffff;
    border-left:0;
    border-top:0;
    border-right:0;
    font-size:16px;
    font-weight:normal;
    padding:4px 10px; 
    width:89.5%;        
  }
  .dashicons{
    background: <?php echo $bg_color; ?>;
    color: <?php echo $text_color; ?>;
    padding-top: 6px;
    height: 28px;
    margin-top:2px;
  }
  #login h1 a, 
  .login h1 a{
    background-image:url(<?php echo $bg; ?>) !important;
    background-repeat: no-repeat; 
    background-size: contain !important;
    width:250px !important;    
    height:100px !important;
    margin-bottom:45px;
    margin-top:50px;
  }
  .wp-core-ui .button-primary,
  .wp-core-ui .button-primary:active,
  .wp-core-ui .button-primary:focus,
  .wp-core-ui .button-primary:visited {
    background: <?php echo $bg_color; ?> !important;
    border:1px solid #fff;    
    box-shadow:0 0 0 0;
    text-shadow: 0 0 0;
  }
  .wp-core-ui .button-primary:hover{
   color:<?php echo $bg_color; ?> !important;
   border:1px solid <?php echo $bg_color; ?> !important;
  background: #ffffff !important;
   box-shadow:0 0 0 0; 
  }  
  .login #nav a:hover,
  .login #backtoblog a:hover{
    color: <?php echo $bg_color; ?>
  }
  #img_div_login{
    max-width:100%;
    <?php if($tema == 2) { ?>
    margin-top:51%;  
    <?php } ?>
  } 
</style> 
<?php
} 
add_action( 'login_footer', 'blp_header_js', 20 );
function blp_header_js() {              
  $blp        = get_option( 'blp_meta_data' );
  
  $tema      = $blp['blp_tema'];  
  if(!isset($tema)){
    $tema = 1;
  }
  $bg        = $blp['img_box_login'];
  if(!isset($bg)){
    $bg = plugin_dir_url( __FILE__ ).'img/blp-login.png';
  }  
  $img       = $blp['img_div_login'];         
  if(!isset($img)){
     $img = plugin_dir_url( __FILE__ ).'img/blp-logo-central.png';
  }
  if($tema != 3){ 
    $titulo    = $blp['titulo_login'];
    if(!isset($titulo)){
      $titulo = _e( 'Beautiful Login Page', 'beautiful-login-page' );
    } 
    $descricao = $blp['descricao_login'];
    if(!isset($descricao)){
      $descricao = _e( 'Awasome Plugin!!!', 'beautiful-login-page' );;
    }
  } else {
    $titulo = ''; $descricao = '';
  }   
  //// TEMA 2
  $fundo_box = $blp['img_fundo_box'];
  if(!isset($fundo_box)){
    $fundo_box = plugin_dir_url( __FILE__ ).'img/blp-fundo.jpg';
  } 
  //// TEMA 3
  if($tema == 3){
    $gallery = $blp['gallery'];
    $imgs_slide = explode(",",$gallery);
    $slides = '';
    if( count($imgs_slide) > 0 ){
      foreach($imgs_slide as $img_slide){
        if($img_slide == ''){
          continue;
        }
        $attachment = get_post( $img_slide );
        $slides .= '<li class="outerHeight" style="background:url('.wp_get_attachment_image_src($img_slide, 'full')[0].') center center;"><h1 class="h1">'.$attachment->post_title.'</h1><h3 class="h3">'.$attachment->post_content.'</h3></li>';
      }    
    } else {
       $slides = '<li></li>';
    }
  }                                         
?>
  <script type="text/javascript">            
    jQuery(document).ready(function() {                                       
      var content, width, height, divLeft, imgWidth, imgHeight, img_h, img_w;      
      height     = jQuery(window).outerHeight();    
      <?php if($tema == 2){ ?>
        content = '<div class="left-div" id="login-left" style="background-image:url(<?php echo $fundo_box; ?>);"><div class="div-tema-2" id="img_div_login"></div><h1><?php echo $titulo; ?></h1><h3><?php echo $descricao; ?></h3></div>';
      <?php } else if($tema == 3){ ?>
        content = '<div class="left-div" id="login-left"><div class="carousel"><span class="prev dashicons dashicons-arrow-left-alt2"></span><span class="next dashicons dashicons-arrow-right-alt2"></span><ul><?php echo $slides; ?></ul></div></div>';
      <?php } else { ?>
        content = '<div class="left-div" id="login-left"><div id="img_div_login"><img id="img" class="img" src="<?php echo $img; ?>"></div><h1><?php echo $titulo; ?></h1><h3><?php echo $descricao; ?></h3></div>';
      <?php } ?>                           
      
      jQuery('body').prepend(content); 
      jQuery('#login').attr('id', 'right-div login').addClass('right-div'); 
      jQuery('.outerHeight').css({'height':height+'px'}); 
           
      divLeft    = jQuery('#login-left').outerHeight();  
      imgHeight  = jQuery('#img_div_login').height();
      imgWidth   = jQuery('#img').width();
      
      var img = new Image();
      img.src = '<?php echo $img; ?>';
      img.onload = function() {
        height     = jQuery(window).outerHeight();    
        img_h = parseInt(((height-this.height+60)/2));             
        if(img_h < 0 ){
          img_h   = (img_h*-1);
        }
        jQuery('#img').css({'margin-top':img_h+'px'});        
      }
      
      jQuery('#login-left').css({'min-height':height+'px', 'height':height+'px', 'max-height':height+'px', });
      jQuery('#login-right').css({'min-height':height+'px', 'height':height+'px', 'max-height':height+'px', });
     
      jQuery('#user_login').before('<span class="dashicons dashicons-businessman"></span>');
      jQuery('#user_pass').before('<span class="dashicons dashicons-lock"></span>');      
      <?php if($tema == 3) { ?>
        jQuery("body .carousel").jCarouselLite({
            btnNext: ".next",
            btnPrev: ".prev",
            visible: 1,
            speed: 1800,
            auto: 2500
        });        
      <?php } ?>            
    });            
  </script>
<?php
}

/// FUNÇÔES WP-ADMIN/
add_action( 'admin_init', 'register_blp_settings' );
function blp_options() {

  $blp = get_option( 'blp_meta_data' ); 
  
  if(isset($blp['exibir_menu']) && !empty($blp['exibir_menu']) && $blp['exibir_menu'] == 'on'){
  	add_options_page( 
  		__( 'Beautiful Login Page', 'beautiful-login-page' ),
          'BLP',
  		'manage_options',
  		'beautiful-login-page.php',
      'blp_menu',        
  		'dashicons-admin-network',
      6
	 );
  } else {  
    add_menu_page( 
  		__( 'Beautiful Login Page', 'beautiful-login-page' ),
          'BLP',
  		'manage_options',
  		'beautiful-login-page.php',
      'blp_menu',        
  		'dashicons-admin-network',
      6
  	);      
  }    
}
add_action('admin_menu', 'blp_options');

function blp_menu() {    
  blp_main();     
}

function register_blp_settings() { // whitelist options
  register_setting( 'blp_grupo', 'blp_meta_data' );  
}


function blp_main() { 
?> 
<div id="#wpbody-content" aria-label="Conteúdo principal" tabindex=0>
  <div class="wrap">
  <h1><?php _e( 'Login Configure', 'beautiful-login-page' ); ?> :: <a href="<?php echo wp_login_url(); ?>" target="_blank"><small><?php _e( 'Preview Login Page', 'beautiful-login-page' ); ?></small></a></h1> 
    <form method="post" action="options.php">
    <div class="postbox-container">
      <div id="poststuff">
      <div class="blp-box postbox">
      <?php
        settings_fields( 'blp_grupo' ); 
        do_settings_sections( 'blp_grupo' );   
        $blp = get_option( 'blp_meta_data' ); 
        
        $tema       = $blp['blp_tema'];
        if(!isset($tema)){
          $tema = 1; 
        }
        $tipo_fonte = $blp['tipo_fonte'];
        if(!isset($tipo_fonte)){
          $tipo_fonte = 'PT+Sans+Narrow';
        }  
        $bg = $blp['img_box_login'];
        if(!isset($bg)){
          $bg = plugin_dir_url( __FILE__ ).'img/blp-login.png';
        }
        $bg_color = $blp['bg_color'];
        if(!isset($bg_color)){
           $bg_color = '#000000';
        }      
        $text_color = $blp['text_color'];
        if(!isset($text_color)){
           $text_color = '#ffffff';
        }                        
                  
        $img = $blp['img_div_login'];
        if(!isset($img)){
            $img = plugin_dir_url( __FILE__ ).'img/blp-logo-central.png';
        }       
        ////// TEMA 2
        $fundo_box = $blp['img_fundo_box'];
        if(!isset($fundo_box)){
          $fundo_box = plugin_dir_url( __FILE__ ).'img/blp-fundo.jpg';
        }
        $titulo     = $blp['titulo_login']; 
        if(!isset($titulo)){
          $titulo = 'Beautiful Login Page';
        }
        $descricao  = $blp['descricao_login'];   
        if(!isset($descricao)){
          $descricao = 'Awasome Plugin !!!';
        }
        ////// TEMA 3
        //// SLIDE 1
        $gallery = '';
        if(isset($blp['gallery'])){
          $gallery = $blp['gallery'];
        }                                      
       ?>                
      	<style type="text/css">      	  
          .blp-hide{
            display:none;
          }
          .blp-box{
            width:65%;
            padding:15px; 
            float:left;          
          }
          .box-preview-image,
          #img_div_p{
            border:2px solid #ffffff;
            box-shadow:0px 0px 5px rgba(200,200,200,1);
            margin-right:10px;
          }
          #img_box, #img_div, #slide_1, #slide_2, #slide_3{
            width:1px !important; 
          }
          #img_box,
          #img_div,
          #slide_1,
          #slide_2,
          #slide_3,
          #fundo_box{
            visibility:hidden;
          }
          table .inp{
            width:65%;
            padding:8px 6px;
            border-radius:6px;
          }
          .blp-msg{
            float:right;
            position:relative;
            margin-left:3px;
            width:27%;
            min-height:150px;
            height:auto;
            background:#fff;
            border:1px solid #e5e5e5;
            padding:5px 10px;
          }
          .hover{
            position:relative;
            font-size:10px;
          }
          .donate{
            margin-top:50px;
          }
          
          .delImg{
            font-size:11px;
          }
          #gallery{
            width:100%;
            display:table;
            position:relative;
          }
          #gallery ul li{
            position:relative;
            float:left;
          }
          #gallery ul li .delIMG{
            position:absolute;
            padding:3px 8px 5px 10px;
            background:rgba(255,255,255,1);
            border-radius:50%;
            text-decoration:none;
            font-weight:bold;
            cursor:pointer;
          }
          .show-theme img{
            width:100%;
            max-width:100%;
            box-shadow:1px 1px 1px #000000;
          }
      	</style>      
      	<table id="form-tema" class="form-table">
          <input type="hidden" id="base_plugin" value="<?php echo plugin_dir_url( __FILE__ );?>">
           <tr>
      			<th>
      				<label for="tema">Tema</label><br>      				            
      			</th>      
      			<td>							
          		<select id="tema" class="form-control" name='blp_meta_data[blp_tema]' data-value="<?php echo $tema; ?>">                
                <option value="1"><?php _e( 'Theme 1 - Background solid color and Logo in center', 'beautiful-login-page' ); ?></option>                
                <option value="2"><?php _e( 'Theme 2 - Background image', 'beautiful-login-page' ); ?></option>
                <option value="3"><?php _e( 'Theme 3 - Slideshow', 'beautiful-login-page' ); ?></option>                
              </select><br>
              <div id="msg"></div>
      			</td>
      		</tr> 
          
          <tr>
      			<th>
      				<label for="image"><?php _e( 'Font Style', 'beautiful-login-page' ); ?></label><br>      				            
      			</th>      
      			<td>							
          		<select id="fonte" class="form-control" name='blp_meta_data[tipo_fonte]' data-value="<?php echo $tipo_fonte; ?>">                
                <option value="Quicksand" selected>Quicksand</option>                
                <option value="Fjalla+One">Fjalla One</option>
                <option value="Alegreya">Alegreya</option>
                <option value="Risque">Risque</option>
                <option value="Slabo">Slabo</option>
                <option value="Spectral+SC">Spectral SC</option>
                <option value="Indie+Flower">Indie Flower</option>
                <option value="Oswald">Oswald</option>
                <option value="Pangolin">Pangolin</option>
                <option value="Playfair+Display">Playfair Display</option>
                <option value="PT+Sans+Narrow">PT Sans Narrow</option>
              </select>
      			</td>
      		</tr>    
          
          <tr id="tit_login">
      			<th>
      				<label for="image"><?php _e( 'Login Title', 'beautiful-login-page' ); ?></label><br>      				            
      			</th>      
      			<td>	                         						
          		<input type='text' class="form-control inp" name='blp_meta_data[titulo_login]' value='<?php echo $titulo; ?>'>
      			</td>
      		</tr>        
          <tr id="desc_login">
      			<th>
      				<label for="image"><?php _e( 'Login Description', 'beautiful-login-page' ); ?></label><br>      				            
      			</th>      
      			<td>							
          		<input type='text' class="form-control inp" name='blp_meta_data[descricao_login]' value='<?php echo $descricao; ?>'>
      			</td>
      		</tr>          
                    
          <tr>
      			<th>
      				<label for="image"><?php _e( 'Background color', 'beautiful-login-page' ); ?></label><br>      				            
      			</th>      
      			<td>							
          		<input type='text' id="bg_color" class="form-control" name='blp_meta_data[bg_color]' value='<?php echo $bg_color; ?>'>
      			</td>                                  
      		</tr>          
        
          <tr>
            <th>
      				<label for="image"><?php _e( 'Text color', 'beautiful-login-page' ); ?></label><br>      				            
      			</th>      
      			<td>							
          		<input type='text' id="text_color" class="form-control" name='blp_meta_data[text_color]' value='<?php echo $text_color; ?>'>
      			</td>
          </tr>           
       
          <tr>
      			<th>
      				<label for="image"><?php _e( 'Header Image Login', 'beautiful-login-page' ); ?></label><br>
      				<img style="width:100%" id="img_box_p" class="box-preview-image" src="<?php echo esc_attr( $bg ); ?>" alt="">
              <div class="hover"><a href="#" id="btnUp" data-id="img_box_login">Edit</a> | <a href="#" id="del_login">Delete</a></div>  
      			</th>      
      			<td>							
              <input type='button' class="button-primary" value="Escolher Imagem" id="btnUp" data-id="img_box_login"/><br />
      				<input type="text" name="blp_meta_data[img_box_login]" id="img_box" value="<?php echo esc_attr( $bg ); ?>" class="regular-text" />      								
      				<span class="description"><?php _e( 'Send image with 256px x 100px proporcion.', 'beautiful-login-page' ); ?></span>
      			</td>
      		</tr> 
           
        </table>
        <input type="hidden" id="tipo-img" value="">
        
        <table id="tema-1" class="blp-hide form-table">      		  		
      		<tr>
      			<th>
      				<label for="image"><?php _e( 'Central Image', 'beautiful-login-page' ); ?></label><br>
      				<img style="width:100%" id="img_div_p" class="div-preview-image" src="<?php echo esc_attr( $img ); ?>">
              <div class="hover"><a href="#" id="btnUp" id="btnUp" data-id="img_div_login">Edit</a> | <a href="#" id="del_central">Delete</a></div>
      			</th>      
      			<td>				
              <input type='button' class="button-primary" id="btnUp" data-id="img_div_login" value="Escolher Imagem" /><br />
      				<input type="hidden" name="blp_meta_data[img_div_login]"  id="img_div" value="<?php echo esc_attr( $img); ?>" class="regular-text" />      								
      				<span class="description"><?php _e( 'Select your Central image.', 'beautiful-login-page' ); ?></span>
      			</td>
      		</tr>            	
      	</table>
                
        <table id="tema-2" class="blp-hide form-table fh-profile-upload-options">      		                                                               
          <tr>
      			<th>
      				<label for="image"><?php _e( 'Background Image', 'beautiful-login-page' ); ?></label><br>
      				<img style="width:100%" id="fundo_box_p" class="box-preview-image" src="<?php echo esc_attr( $fundo_box ); ?>">
              <div class="hover"><a href="#" id="btnUp" data-id="btn_fundo_box">Edit</a> | <a href="#" id="del_bg">Delete</a></div>
      			</th>      
      			<td>							
              <input type='button' class="button-primary" value="Escolher Imagem" id="btnUp" data-id="btn_fundo_box"/><br />
      				<input type="hidden" name="blp_meta_data[img_fundo_box]" id="fundo_box" value="<?php echo esc_attr( $fundo_box ); ?>" class="regular-text" />      								
      				<span class="description"><?php _e( 'Select your Background image.', 'beautiful-login-page' ); ?></span>
      			</td>
      		</tr>              		      	        
      	</table>                
        
        <table id="tema-3" class="theme blp-hide form-table fh-profile-upload-options">      		                                                              
          <tr>
      			<th>
      				<label for="image"><?php _e( 'Slideshow Images', 'beautiful-login-page' ); ?></label><br>
              <span class="description"><?php _e( 'To Add more then one Image press CTRL.', 'beautiful-login-page' ); ?></span><br>             
      				 <input type="hidden" name="blp_meta_data[gallery]" id="gallery" value="<?php echo esc_attr( $gallery ); ?>" class="regular-text" /> <br>     								
              <div id="gallery">
                <ul>
                  <?php
                    $imgs = explode(",",$gallery);
                    if( count($imgs) > 0 ){
                      foreach($imgs as $img){
                        if($img == ''){
                          continue;
                        }
                        echo '<li><a class="delIMG" id="del_IMG" data-id="'.$img.'">x</a><img class="box-preview-image" id="'.$img.'" src="'.wp_get_attachment_image_src($img)[0].'" style="width:100px"></li>';
                      }    
                    }       
                  ?> 
                </u>
              </div> 
              <input type='button' class="button-primary" value="Add media" id="btn_add_media" /><br /> 
      			</th>           			
      		</tr>              		      	         	
      	</table>                
          <?php submit_button(); ?>
          </div>
          
          <div class="blp-msg">
              <h3><?php _e( 'Plugin Configure', 'beautiful-login-page' ); ?></h3>  
              <input type='checkbox' class="form-control" name='blp_meta_data[exibir_menu]' <?php if(isset($blp['exibir_menu']) && !empty($blp['exibir_menu']) && $blp['exibir_menu'] == 'on') { echo 'checked'; } ?>> <?php _e( 'Hide Menu', 'beautiful-login-page' ); ?> <br>
              <?php if(isset($blp['exibir_menu']) && !empty($blp['exibir_menu']) && $blp['exibir_menu'] == 'on') { echo '<span style="color:#c00;font-size:11px;">* Access: <b>Configurations</b> &raquo; <b>BLP</b></span><br>'; } ?>
              <input type='checkbox' class="form-control" name='blp_meta_data[apagar_dados]' <?php if(isset($blp['apagar_dados']) && !empty($blp['apagar_dados']) && $blp['apagar_dados'] == 'on') { echo 'checked'; } ?>> <?php _e( 'Delete data to uninstal.', 'beautiful-login-page' ); ?> <br>
              <?php if(isset($blp['apagar_dados']) && !empty($blp['apagar_dados']) && $blp['apagar_dados'] == 'on') { echo '<span style="color:#c00;font-size:11px;"><b>* When uninstal your configurations be lost.</b></span><br>'; } ?>
              <?php submit_button(); ?>
              
              <div id="show-tema" class="show-theme">
                <div id="tema-1" class="blp-hide">
                  <h3><?php _e( 'Position Theme elements.', 'beautiful-login-page' ); ?> - Theme 1</h3>
                  <a href="<?php echo plugin_dir_url( __FILE__ );?>img/tema-1.jpg" target="_blank">
                    <img src="<?php echo plugin_dir_url( __FILE__ );?>img/tema-1.jpg">
                  </a>
                 </div> 
                 <div id="tema-2" class="blp-hide">
                  <h3><?php _e( 'Position Theme elements.', 'beautiful-login-page' ); ?> - Theme 2</span></h3>
                  <a href="<?php echo plugin_dir_url( __FILE__ );?>/img/tema-2.jpg" target="_blank">
                    <img src="<?php echo plugin_dir_url( __FILE__ );?>/img/tema-2.jpg">
                  </a>
                 </div> 
                 <div id="tema-3" class="blp-hide">
                  <h3><?php _e( 'Position Theme elements.', 'beautiful-login-page' ); ?> - Theme 3</h3>
                  <a href="<?php echo plugin_dir_url( __FILE__ );?>img/tema-3.jpg" target="_blank">
                    <img src="<?php echo plugin_dir_url( __FILE__ );?>img/tema-3.jpg">
                  </a>
                 </div> 
              </div>
              
              
              <div class="donate">
                <h3><?php _e( 'Contribute and continue this project.', 'beautiful-login-page' ); ?></h3>
                <a href="https://pagseguro.uol.com.br/checkout/v2/donation.html?receiverEmail=flavioferreir@hotmail.com&currency=BRL" target="_blank">
                  <img src="https://stc.pagseguro.uol.com.br/public/img/botoes/doacoes/205x30-doar.gif" style="width:100%" alt="Doe com PagSeguro - é rápido e seguro!" />
                </a>
              </div>
          </div>  
          
          
        </div>  
      </div> 
    </form> 
          
   </div>
 </div>  
<?php  } 
?>