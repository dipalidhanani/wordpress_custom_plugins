<?php
class calculator_shortcode {
	static $defs = NULL;
	private $addscripts = false;

	function __construct() {
		///////////////////
		// Add shortcode //
		///////////////////
		add_shortcode( 'my-calculator', array( __CLASS__, 'handle_calc_shortcode' ) );
		
		/* Insert [my-calculator] shortcode*/

		add_filter('mce_external_plugins', array( __CLASS__, 'calc_tinyplugin_register' ));
		add_filter('mce_buttons', array( __CLASS__, 'calc_tinyplugin_add_button' ), 0);
		
			
	add_action('wp_enqueue_scripts', array( __CLASS__, 'calculator_include_front_scripts_styles' ));	
		
		/*add_action('wp_footer', array( __CLASS__, 'my_javascripts' ), 5);*/
	

	}
	function calculator_include_front_scripts_styles(){
		wp_enqueue_script( 'calc-jquery', CURR_PLUGIN_URL.'inc/js/jquery.js');
		wp_enqueue_script( 'calc-jquery-ui', CURR_PLUGIN_URL.'inc/js/jquery-ui.js', array( 'jquery' ));
		wp_enqueue_script( 'calc-default', CURR_PLUGIN_URL.'inc/js/default.js', array( 'jquery' ));
		wp_enqueue_style( 'calc-jquery-ui-style', CURR_PLUGIN_URL.'inc/css/jquery-ui.css' );
		wp_enqueue_style( 'calc-custom-style', CURR_PLUGIN_URL.'inc/css/custom_styles.css' );	
	}

	function calc_tinyplugin_add_button($buttons)
	{
		array_push($buttons, "separator", "tinyplugin");
		return $buttons;
	}
	
	function calc_tinyplugin_register($plugin_array)
	{
		$url = plugins_url( 'js/editor_plugin.js', __FILE__ );
	
		$plugin_array['tinyplugin'] = $url;
		return $plugin_array;
	}
	
	function handle_calc_shortcode( $atts ) { 	
 
	global $ttso;
	
	//get post meta for custom sliders, if set, we show them, if not we show lightbox.
	global $post;
	$post_id            = $post->ID;
	
	if(!empty($slider_shortcode) || !empty($slider_cu3er)):
	get_template_part( 'template-part-page-slider', 'childtheme' );
	
	else: 
	?>
<section class="banner">
  <div class="center-wrap">
    <div class="home-lightbox-banner-content"> <?php echo html_entity_decode( $home_lightbox_banner_content, ENT_QUOTES ); ?> </div>
    <!-- end .home-lightbox-banner-content -->
    

      <?php
	$version = '1';
	if($_SESSION['version'] != $version || (mktime() > $_SESSION['expiredatetime'] )){
	   if (ini_get("session.use_cookies")) {
		  $params = session_get_cookie_params();
		  setcookie(session_name(), '', time() - 42000,
			 $params["path"], $params["domain"],
			 $params["secure"], $params["httponly"]
		  );
	   }
	   session_regenerate_id(true); 
	   $_SESSION['version']          = $version;
	   $_SESSION['expiredatetime']   = mktime(date("H"),date("i"), date("s"), date("n"), (date("j")+2), date("Y"));
	   $_SESSION['page']             = 'index';
	}
	$dir = ( $_SERVER['SERVER_NAME']=='dev.levver.be') ? "dev/" : "";
	
	include("/home/levver01/domains/levver.be/public_html/".$dir."code/admin/include/db.php");
	include("/home/levver01/domains/levver.be/public_html/".$dir."code/admin/include/functions.php");
	
	$_SESSION['index'] = 'Y';
	//print_r($_SESSION);
	if(empty($_SESSION['gaslicht']) && (!empty($_SESSION['no_gas']) || !empty($_SESSION['no_electricity']))){
	   $_SESSION['gaslicht'] = (!empty($_SESSION['no_gas']) ? "stroom" : "gas");
	} elseif (empty($_SESSION['gaslicht']) && ($_SESSION['no_gas']== "0" && $_SESSION['no_electricity'] == "0")) {
	   $_SESSION['gaslicht'] = "stroomgas";
	}
	if($_SESSION['meter_type'] == 'single')
	   $_SESSION['srch_electricity2'] = "";
	if($_SESSION['night'] != '1')
	   $_SESSION['srch_night'] = "";
	$zip = !empty($_SESSION["person_value"]) ? $_SESSION["zip_code"] : "1000";
	?>
      <div id="main"> 
        <!-- header -->
        <div style="width:990px; position: relative;" > 
          <!-- header --> 
          <!-- Banner -->
          
          <form id="search_form" name="search_form" method="get" style="margin:0px" action="../energieleveranciers" target="_parent" onSubmit="return validation();" >
            <div style="100%" align="left" >
            <div>
              
              <div style="width:415px; float:left;position: absolute;z-index: 1;display:none;" id="overview" name="overview">
                <div class="hbox">
                  <div class="hboxt">Uw gegevens <a href='javascript:aanpassen();' >aanpassen</a></div>
                  <div class="overbox overboxt1">
                    <div class="obtt"><?php echo $_SESSION['zip_code'];?>
                      <div style="clear:both;"></div>
                      <span>Uw postcode</span></div>
                  </div>
                  <div class="overbox overboxt2">
                    <div class="obtt"><?php echo $_SESSION['person_value'];?>
                      <div style="clear:both;"></div>
                      <span>aantal personen</span></div>
                  </div>
                  <div class="overbox overboxt3">
                    <div class="obtt"><?php echo (!empty($_SESSION['no_electricity']) ? "-" :implode(",",array_filter(array($_SESSION['srch_electricity'],$_SESSION['srch_electricity2'],$_SESSION['srch_night']))));?>
                      <div style="clear:both;"></div>
                      <span>kWh</span></div>
                  </div>
                  <div class="overbox overboxt4">
                    <div class="obtt"><?php echo (!empty($_SESSION['no_gas']) ? "-" : $_SESSION['srch_gas']);?>
                      <div style="clear:both;"></div>
                      <span>kWh</span></div>
                  </div>
                  <div style="clear:both;"></div>
                  <div class="obttgrey">Op basis van uw gegevens berekenen we de
                    beste aanbiedingen voor u.</div>
                  <div class="obttbutnc">
                    <input type='submit' value="Bekijk uw aanbiedingen">
                  </div>
                  <div style="clear:both;"></div>
                </div>
              </div>
              <div style="width:415px; float:left;position: absolute;z-index: 1;display:none;" id="hh" name="hh">
                <h2 class="demoHeaders">Bereken uw besparing op energieprijzen</h2>
                <div class="hbox">
                  <div id="notaccordion">
                    <h3>
                      <div id='b1' name='b1' class="number">1</div>
                      Wat is uw postcode</h3>
                    <div>
                      <input type='text' id='zip_code' name='zip_code' value='<?php echo $_SESSION['zip_code'];?>'>
                      <div name='city' id='city' style="color: #777"></div>
                      <div style="clear:both;"></div>
                      <div class="fieltex">Hiermee bepalen we de hoogte van uw nettarieven.</div>
                    </div>
                    <h3>
                      <div  id='b2' name='b2' class="number">2</div>
                      Wilt u stroom, gas of beide vergelijken?</h3>
                    <div>
                      <div class="fthree"> <?php echo radio_sel('gaslicht','stroom',    $_SESSION['gaslicht'],"sg('stroom');","Alleen stroom","search_form");?> <br />
                        <?php echo radio_sel('gaslicht','gas',       $_SESSION['gaslicht'],"sg('gas');","Alleen gas","search_form");?> <br />
                        <?php echo radio_sel('gaslicht','stroomgas', $_SESSION['gaslicht'],"sg('stroomgas');","Stroom & gas","search_form");?> <br />
                      </div>
                    </div>
                    <h3>
                      <div id='b3' name='b3' class="number">3</div>
                      Hoe groot is uw huishouden begin dit jaar</h3>
                    <div>
                      <div class="ffour"> <?php echo radio_sel('person_value','1', $_SESSION['person_value'],"calcPers('1');","1 Persoon (alleen uzelf)","search_form");?> <br />
                        <?php echo radio_sel('person_value','2', $_SESSION['person_value'],"calcPers('2');","2 Personen","search_form");?> <br />
                        <?php echo radio_sel('person_value','3', $_SESSION['person_value'],"calcPers('3');","3 Personen","search_form");?> <br />
                        <?php echo radio_sel('person_value','4', $_SESSION['person_value'],"calcPers('4');","4 Personen","search_form");?> <br />
                        <?php echo radio_sel('person_value','5', $_SESSION['person_value'],"calcPers('5');","5+ Personen","search_form");?> <br />
                        <!--       <?php echo radio_sel('person_value','5', $_SESSION['person_value'],"calcPers('5');","anders, namelijk:","search_form");?>  <select name='pers' id='pers'><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option></select> Personen<br />-->
                        <div style="width:100%; height:40px; margin-top:20px; float:left; font-size:14px; font-weight:400; color:#666666;">Hiermee bepalen we hoeveel gratis elektriciteit u krijgt en geven we een inschatting van uw verbruik.</div>
                      </div>
                    </div>
                    <h3>
                      <div id='b4' name='b4' class="number">4</div>
                      Wat is ongeveer uw verbruik per jaar</h3>
                    <div class="ffive" style="padding:0;">
                      <div id='elec'>
                        <h4>4a <b>Elektriciteit:</b> welke stroommeter heeft u?</h4>
                        <div style="color: #777"> <?php echo radio_sel('meter_type','single', $_SESSION['meter_type'],"single_dual();","Enkelvoudige meter (1 tarief voor uw gebruik)","search_form");?> <br />
                          <?php echo radio_sel('meter_type','dual', $_SESSION['meter_type'],"single_dual();","Tweevoudige meter (apart dag- en nachttarief)","search_form");?> <br />
                          <br />
                          <?php echo checkbox_sel('night',"1",$_SESSION['night'],"nn()","Ik heb een uitsluitende nachtmeter","search_form");?> <br />
                          <div id='elec2' name='elec2'> <span class="slt">Bij spaarboilers of accumulatieverwarming</span>
                            <div style="clear:both;"></div>
                            <h4 style="height:72px; margin-top:12px;">4b <b>Elektriciteit:</b> wat is uw jaarverbruik?
                              <div class="gasd" id="person_elec_txt"></div>
                            </h4>
                            <div class='flb flb1'>Dagmeter:
                              <input type=text name='srch_electricity' id='srch_electricity' value="<?php echo $_SESSION['srch_electricity'];?>">
                              kWh/jaar</div>
                            <div id='nachtmeter'>
                              <div class='flb flb2'>Nachtmeter:
                                <input type=text name='srch_electricity2' id='srch_electricity2' value="<?php echo $_SESSION['srch_electricity2'];?>">
                                kWh/jaar</div>
                            </div>
                            <div id='exnachtmeter'>
                              <div class='flb flb3'>Uitsluitende nachtmeter
                                <input type=text name='srch_night' id='srch_night' value="<?php echo $_SESSION['srch_night'];?>">
                                kWh/jaar</div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div id='gas'>
                        <div style="clear:both;"></div>
                        <h4><b>Gas:</b> wat is uw jaarverbruik
                          <div class="gasd" id='person_gas_txt' name='person_gas_txt'></div>
                        </h4>
                        <input type=text name='srch_gas' id='srch_gas' value="<?php echo $_SESSION['srch_gas'];?>">
                        kWh/jaar </div>
                      <br/>
                      <div class="obttbutnck">
                        <input type='submit' value='Vergelijk nu' style="color:#FFFFFF; height:40px;">
                      </div>
                    </div>
                    <input type="hidden" id="include_discount" name="include_discount" value="1" />
                    <input type="hidden" id="no_electricity" name="no_electricity" value="1" />
                    <input type="hidden" id="no_gas" name="no_gas" value="1" />
                    <input type="hidden" id="one_year" name="one_year" value="1" />
                    <input type="hidden" id="two_year" name="two_year" value="1" />
                    <input type="hidden" id="three_year" name="three_year" value="1" />
                    <input type="hidden" id="vast" name="vast" value="1" />
                    <input type="hidden" id="variabel" name="variabel" value="1" />
                    <input type="hidden" id="screenD" name="screenD" value="-1" />
                    <div style="clear:both;"></div>
                  </div>
                </div>
                <div style="clear:both" ></div>
              </div>
            </div>
            <input type="hidden" id="cust_abb_type">
          </form>
          <!-- Banner --> 
        </div>
      </div>
      
      <!-- end .hero-wrap --> 
    </div>
    <!-- end .center-wrap -->
    <div class="shadow top"></div>
    <div class="shadow bottom"></div>
    <div class="tt-overlay"></div>
  </div>

</section>
<?php endif; ?>
<!--<script src="/code/scripts/external/jquery/jquery.js"></script>-->
<script type="text/javascript">
	<?php
	//if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false){
	   echo "document.search_form.screenD.value           = '-1';";
	   echo "document.search_form.srch_night.value        = '".$_SESSION['srch_night']."';\n";
	   echo "document.search_form.srch_electricity.value  = '".$_SESSION['srch_electricity']."';\n";
	   echo "document.search_form.srch_electricity2.value = '".$_SESSION['srch_electricity2']."';\n";
	   echo "document.search_form.zip_code.value          = '".$_SESSION['zip_code']."';\n";
	   echo "document.search_form.srch_gas.value          = '".$_SESSION['srch_gas']."';\n";
	   if(!empty($_SESSION['meter_type'])){
		  echo "document.getElementById(\"meter_type_".$_SESSION['meter_type']."\").checked = true;\n";
		  echo "document.getElementById(\"person_value_".$_SESSION['person_value']."\").checked = true;\n";
		  echo "document.getElementById(\"gaslicht_".$_SESSION['gaslicht']."\").checked = true;\n";
		  echo "document.getElementById(\"night\").checked = ".($_SESSION['night'] == '1' ? "true" : "false").";\n";
		  echo "document.search_form.no_electricity.value = '".$_SESSION['no_electricity']."';\n";
		  echo "document.search_form.no_gas.value = '".$_SESSION['no_gas']."';\n";
		  echo "document.getElementById(\"night\").checked = ".($_SESSION['night'] == '1' ? "true" : "false").";\n";
		  echo "document.getElementById(\"night\").checked = ".($_SESSION['night'] == '1' ? "true" : "false").";\n";
		  echo "document.getElementById(\"night\").checked = ".($_SESSION['night'] == '1' ? "true" : "false").";\n";
	   }
	//}
	
	?>
	function isNumeric(n) {
	  return !isNaN(parseFloat(n)) && isFinite(n);
	}
	jQuery(document).keypress(function (e) {
	  if(e.which == 13) return false;
	});
	function scrollIntoView(element, container) {
	  var containerTop = jQuery(container).scrollTop(); 
	  var containerBottom = containerTop + jQuery(container).height(); 
	  var elemTop = element.offsetTop;
	  var elemBottom = elemTop + jQuery(element).height(); 
	  if (elemTop < containerTop) {
		jQuery(container).scrollTop(elemTop);
	  } else if (elemBottom > containerBottom) {
		jQuery(container).scrollTop(elemBottom - jQuery(container).height());
	  }
	}
	function changeDiv(id){
	   var d = document.search_form;
	   if(d.screenD.value < id){
		  
		  jQuery( "h3:eq("+id+")").toggleClass("ui-accordion-header-active ui-state-active ui-state-default ui-corner-bottom").find("> .ui-icon").toggleClass("ui-icon-triangle-1-e ui-icon-triangle-1-s").end().next().slideToggle();
		  d.screenD.value = id;
		  jQuery("#b"+id).removeClass( "number" ).addClass("checked").text("");
		  id += 1;
	//      document.getElementById( 'b'+id ).scrollIntoView();
	   }
	}
	function nn(){
	   if(jQuery("#night").is(':checked')) {
		   jQuery("#exnachtmeter").show();
	   } else {
		   jQuery("#exnachtmeter").hide();
	   }
	}
	function calcPers(pers,overwrite){
	   var d = document.search_form;
	   if(pers == undefined)
		  pers = document.getElementById("person_value").value;
	   var gas = new Array();
	   var elec = new Array();
	   gas[1]   = "8000";
	   gas[2]   = "12000";
	   gas[3]   = "16000";
	   gas[4]   = "20000";
	   gas[5]   = "24000";
	   elec[1]  = "600";
	   elec[2]  = "1200";
	   elec[3]  = "3500";
	   elec[4]  = "4500";
	   elec[5]  = "7500";
	   
	   document.getElementById("srch_gas").value = gas[pers];
	   document.getElementById("srch_electricity").value = elec[pers];
	   jQuery("#person_gas_txt").text("Het gemiddeld elektriciteitsverbruik voor een "+pers+"-persoons-\nhuishouden is "+gas[pers]+" kWh (1m3 is ongeveer 10 kWh).");
	   changeDiv(3);
	   single_dual();
	}
	function aanpassen(){
	   changeDiv(0);
	   changeDiv(1);
	   changeDiv(2);
	   changeDiv(3);
	   jQuery("#overview").hide();
	   jQuery("#hh").show();
	   single_dual2();
	   nn();
	   sg();
	   calcPers('<?php echo $_SESSION['person_value'];?>');
	}
	function single_dual2(){
	   if(jQuery('input[name=meter_type]:checked', '#search_form').val()=='single' || jQuery('input[name=meter_type]:checked', '#search_form').val()=='dual'){
		  jQuery("#elec2").show();
	   }
	   var txt = "Het gemiddeld elektriciteitsverbruik voor een "+jQuery('input[name=person_value]:checked', '#search_form').val()+"-persoons\nhuishouden is "+document.getElementById("srch_electricity").value+" kWh overdag";
	   if(jQuery('input[name=meter_type]:checked', '#search_form').val()=='dual')
		  txt += " en "+document.getElementById("srch_electricity2").value+" kWh 's nachts.";
	   jQuery("#person_elec_txt").text(txt);
	}
	function single_dual(){
	   if(jQuery('input[name=meter_type]:checked', '#search_form').val()=='single'){
		  if(jQuery.isNumeric(document.getElementById("srch_electricity").value)&&jQuery.isNumeric(document.getElementById("srch_electricity2").value)){
			 document.getElementById("srch_electricity").value = parseInt(document.getElementById("srch_electricity").value)+parseInt(document.getElementById("srch_electricity2").value);
		  }
		  document.getElementById("srch_electricity2").value = '';
		  jQuery("#nachtmeter").hide();
	   } else if(jQuery('input[name=meter_type]:checked', '#search_form').val()=='dual') {
		  if(jQuery.isNumeric(document.getElementById("srch_electricity").value)){
			 document.getElementById("srch_electricity").value = document.getElementById("srch_electricity").value/2;
			 document.getElementById("srch_electricity2").value = document.getElementById("srch_electricity").value;
		  } else {
			 document.getElementById("srch_electricity").value = '';
			 document.getElementById("srch_electricity2").value = '';
		  }
		  jQuery("#nachtmeter").show();
	   }
	   single_dual2();
	}
	function validation(){
		var message = '';
		if(document.search_form.no_electricity.value == 1){
		  document.getElementById("meter_type_single").checked = true;
		}
		if(document.getElementById("zip_code").value == ''){
			message = '- Vermeld svp uw postcode\n';
		}
		if(message != ''){
			alert(message);
			return false;
		}
	}
	function sg(sg){
	   var d = document.search_form;
	   if (sg == undefined)
		  sg = jQuery('input[name=gaslicht]:checked', '#search_form').val();
	   if(sg =='stroom'){
		  d.no_electricity.value = '0';
		  d.no_gas.value  = '1';
		  jQuery("#elec").show();
		  jQuery("#gas").hide();
	   } else if(sg == 'stroomgas') {
		  d.no_electricity.value   = '0';
		  d.no_gas.value    = '0';
		  jQuery("#elec").show();
		  jQuery("#gas").show();
	   } else {
		  d.no_electricity.value = '1';
		  d.no_gas.value  = '0';
		  jQuery("#elec").hide();
		  jQuery("#gas").show();
	   }
	   changeDiv(2);
	}
	var inputBox = document.getElementById('zip_code');
	
	jQuery("#zip_code").keyup(function(){
	   
	   if(document.search_form.zip_code.value.length==4){
		  jQuery.get( "/code/admin/region.php?zip="+document.search_form.zip_code.value, function( data ) {
			 var city = jQuery.parseJSON(data);
			 jQuery( "#city" ).html( city.city );
			 changeDiv(1);
		  });
	   }
	})
	
	
	$.fn.togglepanels = function(){
	  return this.each(function(){
		jQuery(this).addClass("ui-accordion ui-accordion-icons ui-widget ui-helper-reset")
	  .find("h3")
		.addClass("ui-accordion-header ui-helper-reset ui-state-default ui-corner-top ui-corner-bottom")
		.hover(function() { jQuery(this).toggleClass("ui-state-hover"); })
		.click(function() {
		  return false;
		})
		.next()
		  .addClass("ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom")
		  .hide();
	  });
	};
	
	jQuery("#notaccordion").togglepanels();
	jQuery(function() {
	<?php
	   if(!empty($_SESSION['zip_code'])){
		  echo 'jQuery("#hh").hide();';
		  echo 'jQuery("#overview").show();';
		  if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false)
			 echo 'setTimeout(function() {window.scrollTo(0, 0);},1000)';
		  else
			 echo 'window.scrollTo(0,0);';
	   } else {
		  echo 'changeDiv(0);';
		  echo 'jQuery("#elec2").hide();';
		  echo 'jQuery("#nachtmeter").hide();';
		  echo 'jQuery("#exnachtmeter").hide();';
		  echo 'jQuery("#overview").hide();';
		  echo 'jQuery("#hh").show();';
		  echo 'jQuery("#ch1").hide()';
	   }
	?>
	}
	
	);
	window.scrollTo(0,0);
	
	</script>
<?php
	}
}
$calcshortcode = new calculator_shortcode();
?>