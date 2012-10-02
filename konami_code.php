<?php
/*
Plugin Name: Customizable Konami Code
Plugin URI: http://www.guravehaato.info/blog/tenha-o-konami-code-no-seu-blog
Description: Insere o Konami Code no seu blog
Author: GraveHeart
Version: 1.0

*/ 

function instalar_o_trem() {
	$konami_code = "Bem-vindo!";
	add_option('konami_code_string', $konami_code);
	add_option('konami_code_type', 'text');
	add_option('konami_code_code', '');
}

//incluindo funções no menu
function cria_menu_pra_bagaca() {
      add_options_page(
                       'Konami Code',         //Title
                       'Konami Code',         //Sub-menu title
                       'manage_options', //Security
                       __FILE__,         //File to open
                       'konami_code_options'  //Function to call
                      );  
}


function konami_code_options() {
      echo '<div class="wrap"><h2>Konami Code</h2>';
	if ($_REQUEST['submit']) {
		update_konami_options();
	        }
		print_konami_form();
     echo '</div>';
}


function print_konami_form () {
      $konami_string = get_option('konami_code_string');   
      $konami_type = get_option('konami_code_type');   
      $konami_code = get_option('konami_code_code');   

	echo "<form method=\"post\">";
	echo "<label>Efeito:</label><input type=\"radio\" name=\"konami_code_type\" value=\"text\"> Texto <input type=\"radio\" name=\"konami_code_type\" value=\"load\" > Carregar outra p&aacute;gina <input type=\"radio\" name=\"konami_code_type\" value=\"bacon\" > BACON!<br /><br />";
	echo "<label>Insira sua mensagem / p&aacute;gina: <input type=\"text\" name=\"konami_code_string\" value=\"$konami_string\" /></label><br />";
	echo "<small>Nota: caso voc&ecirc; opte por \"bacon\", n&atilde;o &eacute; necess&oacute;rio digitar uma mensagem</small><br /><br />";
	echo "<label>Usar c&oacute;digo personalizado: <input type=\"text\" name=\"konami_code_code\" value=\"$konami_code\" /></label><br />";
	echo "<small>Nota: deixe vazio para o c&aacute;digo padr&atilde;o. Veja <a href=\"http://www.cambiaresearch.com/c4/702b8cd1-e5b0-42e6-83ac-25f0306e3e25/Javascript-Char-Codes-Key-Codes.aspx\">aqui uma tabela de KeyCodes</a> para saber como utilizar</small>";
	echo "<br /> <br />  <input type=\"submit\" name=\"submit\" value=\"Submit\" />  </form>";

 }

 function update_konami_options() {
      $updated = false;
      if ($_REQUEST['konami_code_string'] && $_REQUEST['konami_code_type'] != 'bacon') {
           update_option('konami_code_string', $_REQUEST['konami_code_string']);
           update_option('konami_code_type', $_REQUEST['konami_code_type']);
           update_option('konami_code_code', $_REQUEST['konami_code_code']);
           $updated = true;
      } elseif ($_REQUEST['konami_code_type'] == 'bacon') {
           update_option('konami_code_string', '');
           update_option('konami_code_type', $_REQUEST['konami_code_type']);
           update_option('konami_code_code', $_REQUEST['konami_code_code']);
           $updated = true;
      }
      if ($updated) {
            echo '<div id="message" class="updated fade">';
            echo '<p>Atualizado</p>';
            echo '</div>';
       } else {
            echo '<div id="message" class="error fade">';
            echo '<p>deu pau!</p>';
            echo '</div>';
       }
  }

function konami_code($content) {
	$konami_string = get_option('konami_code_string');   
        $konami_type = get_option('konami_code_type');   
        $konami_code = get_option('konami_code_code');   
	$konami_url = get_option('siteurl');

	?>
		<script type="text/javascript" src="http://konami-js.googlecode.com/svn/trunk/konami.js"></script>
<script type="text/javascript">
<?
	if ($konami_code != '') {
	echo "konami.pattern = \"$konami_code\"";
	}
?>
<? if ($konami_type == 'text') { ?>
	konami.code = function() {
		alert("<? echo $konami_string; ?>")
		}
	konami.load()
<? } else {
	if ($konami_type == 'bacon') { echo "konami.load(\"http://bacolicio.us/" . $konami_url . "\")"; }
	if ($konami_type == 'load') { echo "konami.load(\"$konami_string\")"; }
}
	echo "\n </script>";
}

register_activation_hook( __FILE__, 'instalar_o_trem' );
add_action('admin_menu','cria_menu_pra_bagaca');
add_action('wp_head', 'konami_code');
?>