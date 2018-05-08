<?php
/**
 * Plugin Name: jTour
 * Plugin URI: http://aghadiinfotech.com/
 * Description: Wordpress plugin for golf tournament
 * Version: 1.0
 * Author: Aghadi Infotech
 * Author URI: http://aghadiinfotech.com/
 */
error_reporting(0);
register_activation_hook(__FILE__,'jal_install');
function jal_install() {
  global $wpdb, $dental_prosthetic_db_version;
		//$wpdb->hide_errors();		
		/*
		 * We'll set the default character set and collation for this table.
		 * If we don't do this, some characters could end up being converted 
		 * to just ?'s when saved in our table.
		 */
		$charset_collate = '';
	
		if ( ! empty( $wpdb->charset ) ) {
		  $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
		}
	
		if ( ! empty( $wpdb->collate ) ) {
		  $charset_collate .= " COLLATE {$wpdb->collate}";
		}
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
	$jtour_course = "CREATE TABLE {$wpdb->prefix}jtour_course (
      course_id int(11) NOT NULL AUTO_INCREMENT,
      course_name varchar(255),
	  par_of_course varchar(255),
      PRIMARY KEY ( `course_id` )
    ) $charset_collate ;";
    dbDelta($jtour_course);
    
    $jtour_course_rounds = "CREATE TABLE {$wpdb->prefix}jtour_course_rounds (
      course_rounds_id int(11) NOT NULL AUTO_INCREMENT,
      course_master_id int(11),
	  course_round_number int(11),
	  par_for_hole1 int(11),
	  par_for_hole2 int(11),
	  par_for_hole3 int(11),
	  par_for_hole4 int(11),
	  par_for_hole5 int(11),
	  par_for_hole6 int(11),
	  par_for_hole7 int(11),
	  par_for_hole8 int(11),
	  par_for_hole9 int(11),
	  par_for_hole10 int(11),
	  par_for_hole11 int(11),
	  par_for_hole12 int(11),
	  par_for_hole13 int(11),
	  par_for_hole14 int(11),
	  par_for_hole15 int(11),
	  par_for_hole16 int(11),
	  par_for_hole17 int(11),
	  par_for_hole18 int(11),
      PRIMARY KEY ( `course_rounds_id` )
    ) $charset_collate;";
    dbDelta($jtour_course_rounds);
    
    $jtour_tour = "CREATE TABLE {$wpdb->prefix}jtour_tour (
      tour_id int(11) NOT NULL AUTO_INCREMENT,
      tour_name varchar(255),
      PRIMARY KEY ( `tour_id` )
    ) $charset_collate ;";
    dbDelta($jtour_tour);
    
    $jtour_tournament = "CREATE TABLE {$wpdb->prefix}jtour_tournament (
      tournament_id int(11) NOT NULL AUTO_INCREMENT,
      tournament_name varchar(255),
	  tour_master_id int(11),
	  course_master_id int(11),
	  tournament_startdate date,
	  tournament_enddate date,
	  no_of_rounds varchar(100),
	  tournament_purse varchar(100),
      PRIMARY KEY ( `tournament_id` )
    ) $charset_collate ;";
    dbDelta($jtour_tournament);
    
    $jtour_players = "CREATE TABLE {$wpdb->prefix}jtour_players (
      players_id int(11) NOT NULL AUTO_INCREMENT,
      players_first_name varchar(255),
	  players_middle_name varchar(255),
	  players_last_name varchar(255),
	  players_street_address longtext,
	  players_city varchar(255),
	  players_state varchar(255),
	  players_zip varchar(255),
	  players_emergency_contact_name varchar(255),
      PRIMARY KEY ( `players_id` )
    ) $charset_collate ;";
    dbDelta($jtour_players);
    
    $jtour_tournament_players = "CREATE TABLE {$wpdb->prefix}jtour_tournament_players (
      tournament_players_id int(11) NOT NULL AUTO_INCREMENT,
      player_master_id int(11),
	  tournament_master_id int(11),
      PRIMARY KEY ( `tournament_players_id` )
    ) $charset_collate ;";
    dbDelta($jtour_tournament_players);
    
    $jtour_scores = "CREATE TABLE {$wpdb->prefix}jtour_scores (
      scores_id int(11) NOT NULL AUTO_INCREMENT,
      tour_master_id int(11),
      tournament_master_id int(11),
      player_master_id int(11),
      money_earned varchar(100),
      disqualified tinyint(4),
      withdrawal tinyint(4),
	  total_scores int(11), 
      PRIMARY KEY ( `scores_id` )
    ) $charset_collate ;";
    dbDelta($jtour_scores);
    
    $jtour_scores_rounds = "CREATE TABLE {$wpdb->prefix}jtour_scores_rounds (
      scores_rounds_id int(11) NOT NULL AUTO_INCREMENT,
      scores_master_id int(11),
	  scores_round_number int(11),
	  hole1 int(11),
	  hole2 int(11),
	  hole3 int(11),
	  hole4 int(11),
	  hole5 int(11),
	  hole6 int(11),
	  hole7 int(11),
	  hole8 int(11),
	  hole9 int(11),
	  hole10 int(11),
	  hole11 int(11),
	  hole12 int(11),
	  hole13 int(11),
	  hole14 int(11),
	  hole15 int(11),
	  hole16 int(11),
	  hole17 int(11),
	  hole18 int(11),
      PRIMARY KEY ( `scores_rounds_id` )
    ) $charset_collate ;";
  	dbDelta($jtour_scores_rounds);
   
   // create page
		$pages = array(
			'tournament_list' => array(
				'name'    => _x( 'Tournament List', 'Page slug', 'tournament_list' ),
				'title'   => _x( 'Tournament List', 'Page title', 'tournament_list' ),							
				'content' => '[tournament_list]'
			),
			'leaderboard' => array(
				'name'    => _x( 'Leaderboard', 'Page slug', 'leaderboard' ),
				'title'   => _x( 'Leaderboard', 'Page title', 'leaderboard' ),
				'parent'  => 'tournament-list',
				'content' => '[tournament_leaderboard]'
			),
			'profile' => array(
				'name'    => _x( 'Profile', 'Page slug', 'profile' ),
				'title'   => _x( 'Profile', 'Page title', 'profile' ),
				'parent'  => 'tournament-list',
				'content' => '[player_profile]'
			),
			'scoreboard' => array(
				'name'    => _x( 'Scoreboard', 'Page slug', 'scoreboard' ),
				'title'   => _x( 'Scoreboard', 'Page title', 'scoreboard' ),
				'parent'  => 'tournament-list',
				'content' => '[tournament_scoreboard]'
			),
			'money_list' => array(
				'name'    => _x( 'Money List', 'Page slug', 'money_list' ),
				'title'   => _x( 'Money List', 'Page title', 'money_list' ),									
				'content' => '[money_list_all]'
			),
			'four_tournament_money_list' => array(
				'name'    => _x( '4 Tournament Money List', 'Page slug', 'four_tournament_money_list' ),
				'title'   => _x( '4 Tournament Money List', 'Page title', 'four_tournament_money_list' ),				
				'content' => '[four_tornament_money_list]'
			)			
		);

		foreach ( $pages as $key => $page ) {
			
			$slug = $page['name'];
			$page_title = $page['title'];
			$page_content = $page['content'];
			$post_parent = $page['parent'];
			
			
			$my_id = $wpdb->get_row("SELECT ID FROM $wpdb->posts WHERE post_name = '".$post_parent."'");
		
			if($post_parent != '') {
				$parentid = $my_id->ID;
			} else {
				$parentid = '';
			}
			
			
			$option = 'jtour_'.$key.'_page_id';
			
			$option_value = get_option( $option );

			if ( $option_value > 0 && get_post( $option_value ) )
				return -1;
			
			$page_data = array(
				'post_status'       => 'publish',
				'post_type'         => 'page',
				'post_author'       => 1,
				'post_name'         => esc_sql( $page['name'] ),
				'post_title'        => $page_title,
				'post_content'      => $page_content,
				'post_parent'       => $parentid,
				'comment_status'    => 'closed'
			);		
			$page_id = wp_insert_post( $page_data );
			
			if ( $option )
				update_option( $option, $page_id );

		}
	
}

add_action( 'admin_menu', 'jp_create_admin_pages' );

function jp_create_admin_pages() {
	global $wpdb;
	
    add_menu_page('jTour','jTour Dashboard','manage_options','test-plugin','test_init');
    add_submenu_page('test-plugin','Course', 'Course', 'manage_options', 'jtour_course_management', 'jtour_course_fn');
	add_submenu_page('test-plugin','Tour', 'Tour', 'manage_options', 'jtour_tour_management', 'jtour_tour_fn');
	add_submenu_page('test-plugin','Tournament', 'Tournament', 'manage_options', 'jtour_tournament_management', 'jtour_tournament_fn');
	add_submenu_page('test-plugin','Players', 'Players', 'manage_options', 'jtour_players_management', 'jtour_players_fn');	
	add_submenu_page('test-plugin','Scores', 'Scores', 'manage_options', 'jtour_scores_management', 'jtour_scores_fn');
		
    wp_enqueue_style( 'style1', plugins_url( 'css/styles.css' , __FILE__ ) );
}
     
function test_init(){
?>

<h1>Golf Manager(Dashboard)</h1>
<div id="mc-component">
  <div class="float-left width50" id="cpanel">
    <div class="icon"> <a href="admin.php?page=jtour_course_management"> <img alt="Manage Courses" src="<?php echo plugins_url( 'images/course.png', __FILE__ ); ?>"> <span>Manage Courses</span> </a> </div>
    <div class="icon"> <a href="admin.php?page=jtour_tour_management"> <img alt="Manage Tours" src="<?php echo plugins_url( 'images/tour.png', __FILE__ ); ?>"> <span>Manage Tours</span> </a> </div>
    <div class="icon"> <a href="admin.php?page=jtour_tournament_management"> <img alt="Manage Tournaments" src="<?php echo plugins_url( 'images/tournament.png', __FILE__ ); ?>"> <span>Manage Tournaments</span> </a> </div>
    <div class="icon"> <a href="admin.php?page=jtour_players_management"> <img alt="Manage Players" src="<?php echo plugins_url( 'images/tournament.png', __FILE__ ); ?>"> <span>Manage Players</span> </a> </div>
    <div class="icon"> <a href="admin.php?page=jtour_scores_management"> <img alt="Manage Scores" src="<?php echo plugins_url( 'images/tournament.png', __FILE__ ); ?>"> <span>Manage Scores</span> </a> </div>
  </div>
</div>
<?php
}

function jtour_course_fn(){
global $wpdb;
if( @$_REQUEST['page'] == 'jtour_course_management'){
	if( @$_GET['action'] == 'delete'){
		$successdelete = $wpdb->query("delete from {$wpdb->prefix}jtour_course where course_id = '".$_GET['course_id']."'");
	}
	
	if( @$_GET['action'] == ""){ 
	?>
<div class="wrap">
  <h2>Course Management <a class="add-new-h2" href="admin.php?page=jtour_course_management&action=add">Add New</a> </h2>
</div>
<table class="wp-list-table widefat fixed posts">
  <thead>
    <tr>
      <th style="" class="manage-column column-cb check-column" id="cb" scope="col"></th>
      <th style="" class="manage-column column-title sortable desc" id="course_name" scope="col"><span>Course Name</span></th>
      <th style="" class="manage-column column-title sortable desc" id="par_of_course" scope="col"><span>Par for the Course</span></th>
      <th><span>Action</span></th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <th style="" class="manage-column column-cb check-column" scope="col"></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Course Name</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Par for the Course</span></th>
      <th><span>Action</span></th>
    </tr>
  </tfoot>
  <tbody id="the-list">
    <?php $rows_course = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jtour_course");
			if(count($rows_course) > 0){
       			 foreach ( $rows_course as $row_course ) { ?>
    <tr class="post-78 type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0" id="post-78">
      <th class="check-column" scope="row"> <div class="locked-indicator"></div>
      </th>
      <td class="post-title page-title column-title"><a title="" href="admin.php?page=jtour_course_management&amp;action=edit&amp;course_id=<?php echo $row_course->course_id; ?>" class="row-title"> <?php echo $row_course->course_name; ?></a></td>
      <td class="post-title page-title column-title"><?php echo $row_course->par_of_course; ?></td>
      <td><a title="edit" href="admin.php?page=jtour_course_management&amp;action=edit&amp;course_id=<?php echo $row_course->course_id; ?>" > Edit</a> | <a title="delete" href="admin.php?page=jtour_course_management&amp;action=delete&amp;course_id=<?php echo $row_course->course_id; ?>"> Delete</a></td>
    </tr>
    <?php } } else {?>
    <tr class="no-items">
      <td class="colspanchange" colspan="3">No Record found</td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<br />
<?php } else if(( @$_GET['action'] == "add") || ( @$_GET['action'] == 'edit')){
	if(( @$_REQUEST['action']=='add') && ( @$_REQUEST['addcourse'] != '')){		
		
		if($_POST['course_name'] != ''){
			// $wpdb->insert will return true or false based on if the query was successful.	
			$success = $wpdb->insert( $wpdb->prefix.'jtour_course',array(
				"course_name" => mysql_real_escape_string($_POST['course_name']),
				"par_of_course" => mysql_real_escape_string($_POST['par_of_course'])
			));
			$course_master_id = $wpdb->insert_id;
			
			
			$success_par_course = $wpdb->insert($wpdb->prefix.'jtour_course_rounds',array(
				"course_master_id" => $course_master_id,
				"course_round_number" => '1',
				"par_for_hole1" => $_POST['par_for_hole1_r1'],
				"par_for_hole2" => $_POST['par_for_hole2_r1'],
				"par_for_hole3" => $_POST['par_for_hole3_r1'],
				"par_for_hole4" => $_POST['par_for_hole4_r1'],
				"par_for_hole5" => $_POST['par_for_hole5_r1'],
				"par_for_hole6" => $_POST['par_for_hole6_r1'],
				"par_for_hole7" => $_POST['par_for_hole7_r1'],
				"par_for_hole8" => $_POST['par_for_hole8_r1'],
				"par_for_hole9" => $_POST['par_for_hole9_r1'],
				"par_for_hole10" => $_POST['par_for_hole10_r1'],
				"par_for_hole11" => $_POST['par_for_hole11_r1'],
				"par_for_hole12" => $_POST['par_for_hole12_r1'],
				"par_for_hole13" => $_POST['par_for_hole13_r1'],
				"par_for_hole14" => $_POST['par_for_hole14_r1'],
				"par_for_hole15" => $_POST['par_for_hole15_r1'],
				"par_for_hole16" => $_POST['par_for_hole16_r1'],
				"par_for_hole17" => $_POST['par_for_hole17_r1'],
				"par_for_hole18" => $_POST['par_for_hole18_r1']
			));
			
			/*$success_par_course2 = $wpdb->insert($wpdb->prefix.'jtour_course_rounds',array(
				"course_master_id" => $course_master_id,
				"course_round_number" => '2',
				"par_for_hole1" => $_POST['par_for_hole1_r2'],
				"par_for_hole2" => $_POST['par_for_hole2_r2'],
				"par_for_hole3" => $_POST['par_for_hole3_r2'],
				"par_for_hole4" => $_POST['par_for_hole4_r2'],
				"par_for_hole5" => $_POST['par_for_hole5_r2'],
				"par_for_hole6" => $_POST['par_for_hole6_r2'],
				"par_for_hole7" => $_POST['par_for_hole7_r2'],
				"par_for_hole8" => $_POST['par_for_hole8_r2'],
				"par_for_hole9" => $_POST['par_for_hole9_r2'],
				"par_for_hole10" => $_POST['par_for_hole10_r2'],
				"par_for_hole11" => $_POST['par_for_hole11_r2'],
				"par_for_hole12" => $_POST['par_for_hole12_r2'],
				"par_for_hole13" => $_POST['par_for_hole13_r2'],
				"par_for_hole14" => $_POST['par_for_hole14_r2'],
				"par_for_hole15" => $_POST['par_for_hole15_r2'],
				"par_for_hole16" => $_POST['par_for_hole16_r2'],
				"par_for_hole17" => $_POST['par_for_hole17_r2'],
				"par_for_hole18" => $_POST['par_for_hole18_r2']
			));*/
			
				
		}
	}

	if(($_GET['action'] == 'edit') && ($_POST['editcourse'] != '')){
		
		if($_POST['course_name'] != ''){
			$success = $wpdb->update($wpdb->prefix.'jtour_course',array(
				"course_name" => mysql_real_escape_string($_POST['course_name']),
				"par_of_course" => mysql_real_escape_string($_POST['par_of_course'])
			), array('course_id' => $_GET['course_id']));
			
			
			$success_par_course1 = $wpdb->update($wpdb->prefix.'jtour_course_rounds',array(				
				"par_for_hole1" => $_POST['par_for_hole1_r1'],
				"par_for_hole2" => $_POST['par_for_hole2_r1'],
				"par_for_hole3" => $_POST['par_for_hole3_r1'],
				"par_for_hole4" => $_POST['par_for_hole4_r1'],
				"par_for_hole5" => $_POST['par_for_hole5_r1'],
				"par_for_hole6" => $_POST['par_for_hole6_r1'],
				"par_for_hole7" => $_POST['par_for_hole7_r1'],
				"par_for_hole8" => $_POST['par_for_hole8_r1'],
				"par_for_hole9" => $_POST['par_for_hole9_r1'],
				"par_for_hole10" => $_POST['par_for_hole10_r1'],
				"par_for_hole11" => $_POST['par_for_hole11_r1'],
				"par_for_hole12" => $_POST['par_for_hole12_r1'],
				"par_for_hole13" => $_POST['par_for_hole13_r1'],
				"par_for_hole14" => $_POST['par_for_hole14_r1'],
				"par_for_hole15" => $_POST['par_for_hole15_r1'],
				"par_for_hole16" => $_POST['par_for_hole16_r1'],
				"par_for_hole17" => $_POST['par_for_hole17_r1'],
				"par_for_hole18" => $_POST['par_for_hole18_r1']
			), array('course_master_id' => $_GET['course_id'],'course_round_number' => '1'));
		/*	$success_par_course2 = $wpdb->update($wpdb->prefix.'jtour_course_rounds',array(				
				"par_for_hole1" => $_POST['par_for_hole1_r2'],
				"par_for_hole2" => $_POST['par_for_hole2_r2'],
				"par_for_hole3" => $_POST['par_for_hole3_r2'],
				"par_for_hole4" => $_POST['par_for_hole4_r2'],
				"par_for_hole5" => $_POST['par_for_hole5_r2'],
				"par_for_hole6" => $_POST['par_for_hole6_r2'],
				"par_for_hole7" => $_POST['par_for_hole7_r2'],
				"par_for_hole8" => $_POST['par_for_hole8_r2'],
				"par_for_hole9" => $_POST['par_for_hole9_r2'],
				"par_for_hole10" => $_POST['par_for_hole10_r2'],
				"par_for_hole11" => $_POST['par_for_hole11_r2'],
				"par_for_hole12" => $_POST['par_for_hole12_r2'],
				"par_for_hole13" => $_POST['par_for_hole13_r2'],
				"par_for_hole14" => $_POST['par_for_hole14_r2'],
				"par_for_hole15" => $_POST['par_for_hole15_r2'],
				"par_for_hole16" => $_POST['par_for_hole16_r2'],
				"par_for_hole17" => $_POST['par_for_hole17_r2'],
				"par_for_hole18" => $_POST['par_for_hole18_r2']
			), array('course_master_id' => $_GET['course_id'],'course_round_number' => '2'));*/
				
		}
	}
	
	$single_course = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_course where course_id = '". @$_GET['course_id']."'");
	$course_name = '';
	$par_of_course = '';
	if( !empty($single_course) ){
		$course_name = $single_course->course_name;
		$par_of_course = $single_course->par_of_course;
	}
	
	$single_course_rounds1 = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_course_rounds where course_master_id = '". @$_GET['course_id']."' and course_round_number = '1'");
	//$single_course_rounds2 = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_course_rounds where course_master_id = '".$_GET['course_id']."' and course_round_number = '2'");

?>
<div class="wrap">
  <?php if($_GET['action'] == "add"){ ?>
  <h2>Add New Course</h2>
  <?php } else if($_GET['action'] == "edit"){ ?>
  <h2>Edit Course</h2>
  <?php } ?>
  <form name="frm" method="post" action="">
    <table cellpadding="0" cellspacing="5">
      <tr>
        <td><strong>Course Name :</strong></td>
      </tr>
      <tr>
        <td><input type="text" name="course_name" value="<?php echo $course_name; ?>"></td>
      </tr>
      <tr>
        <td><strong> Par for the Course :</strong></td>
      </tr>
      <tr>
        <td><input type="text" name="par_of_course" value="<?php echo $par_of_course; ?>"></td>
      </tr>
      <tr>
        <td><legend class="par_course">Round 1</legend>
          <table width="100%" class="admintable">
            <tbody>
              <tr>
                <td><b>Par for Hole 1 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_course_rounds1->par_for_hole1; ?>" name="par_for_hole1_r1" ></td>
                <td><b>Par for Hole 2 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_course_rounds1->par_for_hole2; ?>" name="par_for_hole2_r1"></td>
                <td><b>Par for Hole 3 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_course_rounds1->par_for_hole3; ?>" name="par_for_hole3_r1"></td>
                <td><b>Par for Hole 4 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_course_rounds1->par_for_hole4; ?>" name="par_for_hole4_r1"></td>
                <td><b>Par for Hole 5 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_course_rounds1->par_for_hole5; ?>" name="par_for_hole5_r1"></td>
                <td><b>Par for Hole 6 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_course_rounds1->par_for_hole6; ?>" name="par_for_hole6_r1"></td>
              </tr>
              <tr>
                <td><b>Par for Hole 7 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_course_rounds1->par_for_hole7; ?>" name="par_for_hole7_r1"></td>
                <td><b>Par for Hole 8 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_course_rounds1->par_for_hole8; ?>" name="par_for_hole8_r1"></td>
                <td><b>Par for Hole 9 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_course_rounds1->par_for_hole9; ?>" name="par_for_hole9_r1"></td>
                <td><b>Par for Hole 10 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_course_rounds1->par_for_hole10; ?>" name="par_for_hole10_r1"></td>
                <td><b>Par for Hole 11 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_course_rounds1->par_for_hole11; ?>" name="par_for_hole11_r1"></td>
                <td><b>Par for Hole 12 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_course_rounds1->par_for_hole12; ?>" name="par_for_hole12_r1"></td>
              </tr>
              <tr>
                <td><b>Par for Hole 13 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_course_rounds1->par_for_hole13; ?>" name="par_for_hole13_r1"></td>
                <td><b>Par for Hole 14 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_course_rounds1->par_for_hole14; ?>" name="par_for_hole14_r1"></td>
                <td><b>Par for Hole 15 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_course_rounds1->par_for_hole15; ?>" name="par_for_hole15_r1"></td>
                <td><b>Par for Hole 16 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_course_rounds1->par_for_hole16; ?>" name="par_for_hole16_r1"></td>
                <td><b>Par for Hole 17 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_course_rounds1->par_for_hole17; ?>" name="par_for_hole17_r1"></td>
                <td><b>Par for Hole 18 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_course_rounds1->par_for_hole18; ?>" name="par_for_hole18_r1"></td>
              </tr>
            </tbody>
          </table></td>
      </tr>
      <tr>
        <td><?php if( @$_GET['action'] == "add"){ ?>
          <input type="hidden" name="addcourse" value="1">
          <?php } else if( @$_GET['action'] == "edit"){ ?>
          <input type="hidden" name="editcourse" value="1">
          <?php } ?>
          <input type="submit" name="submit" value="Submit" class="button button-primary button-large"></td>
      </tr>
    </table>
  </form>
  <br />
</div>
<?php
  }
			if( @$successdelete ){
				wp_redirect('admin.php?page=jtour_course_management');
			}		
			else if((( @$_POST['addcourse'] != '') || ( @$_POST['editcourse'] != '')) && ( @$_POST['course_name'] == '')){
				echo '<div style="color:red;">Please enter course name</div>';
			}
			else if( @$success == 1){
				wp_redirect('admin.php?page=jtour_course_management');
			}
		
}
}

/* Scores Management */

function jtour_scores_fn(){
global $wpdb;
if( @$_REQUEST['page'] =='jtour_scores_management'){
	if( @$_GET['action'] == 'delete'){
		$successdelete = $wpdb->query("delete from {$wpdb->prefix}jtour_scores where scores_id = '".$_GET['scores_id']."'");
	}
	
	if( @$_GET['action'] == ""){ 
	?>
<div class="wrap">
  <h2>Scores Management <a class="add-new-h2" href="admin.php?page=jtour_scores_management&action=add">Add New</a> </h2>
</div>
<table class="wp-list-table widefat fixed posts">
  <thead>
    <tr>
      <th style="" class="manage-column column-cb check-column" id="cb" scope="col"></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Player</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Tournament</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Tour Year</span></th>
      <th><span>Action</span></th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <th style="" class="manage-column column-cb check-column" scope="col"></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Player</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Tournament</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Tour Year</span></th>
      <th><span>Action</span></th>
    </tr>
  </tfoot>
  <tbody id="the-list">
    <?php $rows_scores = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jtour_scores");
			if(count($rows_scores) > 0){
       			 foreach ( $rows_scores as $row_scores ) { ?>
    <tr class="post-78 type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0" id="post-78">
      <th class="check-column" scope="row"> <div class="locked-indicator"></div>
      </th>
      <td class="post-title page-title column-title"><a title="" href="admin.php?page=jtour_scores_management&amp;action=edit&amp;scores_id=<?php echo $row_scores->scores_id; ?>" class="row-title">
        <?php 
                    $single_scores_payers = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_players where players_id = '".$row_scores->player_master_id."'");
                    echo $single_scores_payers->players_first_name." ".$single_scores_payers->players_last_name; ?>
        </a></td>
      <td class="post-title page-title column-title"><?php $single_scores_tournament = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_tournament where tournament_id = '".$row_scores->tournament_master_id."'");
                    echo $single_scores_tournament->tournament_name; ?></td>
      <td class="post-title page-title column-title"><?php $single_scores_tour = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_tour where tour_id = '".$row_scores->tour_master_id."'");
                    echo $single_scores_tour->tour_name; ?></td>
      <td><a title="edit" href="admin.php?page=jtour_scores_management&amp;action=edit&amp;scores_id=<?php echo $row_scores->scores_id; ?>" > Edit</a> | <a title="delete" href="admin.php?page=jtour_scores_management&amp;action=delete&amp;scores_id=<?php echo $row_scores->scores_id; ?>"> Delete</a></td>
    </tr>
    <?php } } else {?>
    <tr class="no-items">
      <td class="colspanchange" colspan="3">No Record found</td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<br />
<?php } else if(( @$_GET['action'] == "add") || ( @$_GET['action'] == 'edit')){ 
	if(( @$_REQUEST['action']=='add') && ( @$_REQUEST['addscores'] != '')){		
		
		if( @$_POST['player_master_id'] != ''){
			// $wpdb->insert will return true or false based on if the query was successful.	
			$success_scores = $wpdb->insert($wpdb->prefix.'jtour_scores',array(
				"player_master_id" => mysql_real_escape_string($_POST['player_master_id']),
				"tournament_master_id" => mysql_real_escape_string($_POST['tournament_master_id']),
				"tour_master_id" => mysql_real_escape_string($_POST['tour_master_id']),				
				"money_earned" => mysql_real_escape_string($_POST['money_earned']),
				"disqualified" => mysql_real_escape_string($_POST['disqualified']),
				"withdrawal" => mysql_real_escape_string($_POST['withdrawal'])
			));
			
			$scores_master_id = $wpdb->insert_id;
			
			
			$success_par_scores = $wpdb->insert($wpdb->prefix.'jtour_scores_rounds',array(
				"scores_master_id" => $scores_master_id,
				"scores_round_number" => '1',
				"hole1" => $_POST['hole1_r1'],
				"hole2" => $_POST['hole2_r1'],
				"hole3" => $_POST['hole3_r1'],
				"hole4" => $_POST['hole4_r1'],
				"hole5" => $_POST['hole5_r1'],
				"hole6" => $_POST['hole6_r1'],
				"hole7" => $_POST['hole7_r1'],
				"hole8" => $_POST['hole8_r1'],
				"hole9" => $_POST['hole9_r1'],
				"hole10" => $_POST['hole10_r1'],
				"hole11" => $_POST['hole11_r1'],
				"hole12" => $_POST['hole12_r1'],
				"hole13" => $_POST['hole13_r1'],
				"hole14" => $_POST['hole14_r1'],
				"hole15" => $_POST['hole15_r1'],
				"hole16" => $_POST['hole16_r1'],
				"hole17" => $_POST['hole17_r1'],
				"hole18" => $_POST['hole18_r1']
			));
			
			$success_par_course2 = $wpdb->insert($wpdb->prefix.'jtour_scores_rounds',array(
				"scores_master_id" => $scores_master_id,
				"scores_round_number" => '2',
				"hole1" => $_POST['hole1_r2'],
				"hole2" => $_POST['hole2_r2'],
				"hole3" => $_POST['hole3_r2'],
				"hole4" => $_POST['hole4_r2'],
				"hole5" => $_POST['hole5_r2'],
				"hole6" => $_POST['hole6_r2'],
				"hole7" => $_POST['hole7_r2'],
				"hole8" => $_POST['hole8_r2'],
				"hole9" => $_POST['hole9_r2'],
				"hole10" => $_POST['hole10_r2'],
				"hole11" => $_POST['hole11_r2'],
				"hole12" => $_POST['hole12_r2'],
				"hole13" => $_POST['hole13_r2'],
				"hole14" => $_POST['hole14_r2'],
				"hole15" => $_POST['hole15_r2'],
				"hole16" => $_POST['hole16_r2'],
				"hole17" => $_POST['hole17_r2'],
				"hole18" => $_POST['hole18_r2']
			));
			
			$success_par_course3 = $wpdb->insert($wpdb->prefix.'jtour_scores_rounds',array(
				"scores_master_id" => $scores_master_id,
				"scores_round_number" => '3',
				"hole1" => $_POST['hole1_r3'],
				"hole2" => $_POST['hole2_r3'],
				"hole3" => $_POST['hole3_r3'],
				"hole4" => $_POST['hole4_r3'],
				"hole5" => $_POST['hole5_r3'],
				"hole6" => $_POST['hole6_r3'],
				"hole7" => $_POST['hole7_r3'],
				"hole8" => $_POST['hole8_r3'],
				"hole9" => $_POST['hole9_r3'],
				"hole10" => $_POST['hole10_r3'],
				"hole11" => $_POST['hole11_r3'],
				"hole12" => $_POST['hole12_r3'],
				"hole13" => $_POST['hole13_r3'],
				"hole14" => $_POST['hole14_r3'],
				"hole15" => $_POST['hole15_r3'],
				"hole16" => $_POST['hole16_r3'],
				"hole17" => $_POST['hole17_r3'],
				"hole18" => $_POST['hole18_r3']
			));
			
			$total_scores_r1 = $_POST['hole1_r1'] + $_POST['hole2_r1'] + $_POST['hole3_r1']+ $_POST['hole4_r1']+ $_POST['hole5_r1']+ $_POST['hole6_r1']+ $_POST['hole7_r1']+ $_POST['hole8_r1']+ $_POST['hole9_r1']+ $_POST['hole10_r1']+ $_POST['hole11_r1']+ $_POST['hole12_r1']+ $_POST['hole13_r1']+ $_POST['hole14_r1']+ $_POST['hole15_r1']+ $_POST['hole16_r1']+ $_POST['hole17_r1'] + $_POST['hole18_r1'];
			$total_scores_r2 = $_POST['hole1_r2'] + $_POST['hole2_r2']+ $_POST['hole3_r2']+ $_POST['hole4_r2']+ $_POST['hole5_r2']+ $_POST['hole6_r2']+ $_POST['hole7_r2']+ $_POST['hole8_r2']+ $_POST['hole9_r2']+ $_POST['hole10_r2']+ $_POST['hole11_r2']+ $_POST['hole12_r2']+ $_POST['hole13_r2']+ $_POST['hole14_r2']+ $_POST['hole15_r2']+ $_POST['hole16_r2']+ $_POST['hole17_r2'] + $_POST['hole18_r2'];
			
			$total_scores = $total_scores_r1 + $total_scores_r2;
			
			$wpdb->update($wpdb->prefix.'jtour_scores',array(				
				"total_scores" => $total_scores
			), array('scores_id' => $scores_master_id));
				
		}
	}

	if(($_GET['action'] == 'edit') && ($_POST['editscores'] != '')){
		
		if($_POST['player_master_id'] != ''){
			$success = $wpdb->update($wpdb->prefix.'jtour_scores',array(
				"player_master_id" => mysql_real_escape_string($_POST['player_master_id']),
				"tournament_master_id" => mysql_real_escape_string($_POST['tournament_master_id']),
				"tour_master_id" => mysql_real_escape_string($_POST['tour_master_id']),				
				"money_earned" => mysql_real_escape_string($_POST['money_earned']),
				"disqualified" => mysql_real_escape_string($_POST['disqualified']),
				"withdrawal" => mysql_real_escape_string($_POST['withdrawal'])
			), array('scores_id' => $_GET['scores_id']));			
			
			$success_par_course1 = $wpdb->update($wpdb->prefix.'jtour_scores_rounds',array(				
				"hole1" => $_POST['hole1_r1'],
				"hole2" => $_POST['hole2_r1'],
				"hole3" => $_POST['hole3_r1'],
				"hole4" => $_POST['hole4_r1'],
				"hole5" => $_POST['hole5_r1'],
				"hole6" => $_POST['hole6_r1'],
				"hole7" => $_POST['hole7_r1'],
				"hole8" => $_POST['hole8_r1'],
				"hole9" => $_POST['hole9_r1'],
				"hole10" => $_POST['hole10_r1'],
				"hole11" => $_POST['hole11_r1'],
				"hole12" => $_POST['hole12_r1'],
				"hole13" => $_POST['hole13_r1'],
				"hole14" => $_POST['hole14_r1'],
				"hole15" => $_POST['hole15_r1'],
				"hole16" => $_POST['hole16_r1'],
				"hole17" => $_POST['hole17_r1'],
				"hole18" => $_POST['hole18_r1']
			), array('scores_master_id' => $_GET['scores_id'],'scores_round_number' => '1'));
			$success_par_course2 = $wpdb->update($wpdb->prefix.'jtour_scores_rounds',array(				
				"hole1" => $_POST['hole1_r2'],
				"hole2" => $_POST['hole2_r2'],
				"hole3" => $_POST['hole3_r2'],
				"hole4" => $_POST['hole4_r2'],
				"hole5" => $_POST['hole5_r2'],
				"hole6" => $_POST['hole6_r2'],
				"hole7" => $_POST['hole7_r2'],
				"hole8" => $_POST['hole8_r2'],
				"hole9" => $_POST['hole9_r2'],
				"hole10" => $_POST['hole10_r2'],
				"hole11" => $_POST['hole11_r2'],
				"hole12" => $_POST['hole12_r2'],
				"hole13" => $_POST['hole13_r2'],
				"hole14" => $_POST['hole14_r2'],
				"hole15" => $_POST['hole15_r2'],
				"hole16" => $_POST['hole16_r2'],
				"hole17" => $_POST['hole17_r2'],
				"hole18" => $_POST['hole18_r2']
			), array('scores_master_id' => $_GET['scores_id'],'scores_round_number' => '2'));
			
			$success_par_course3 = $wpdb->update($wpdb->prefix.'jtour_scores_rounds',array(				
				"hole1" => $_POST['hole1_r3'],
				"hole2" => $_POST['hole2_r3'],
				"hole3" => $_POST['hole3_r3'],
				"hole4" => $_POST['hole4_r3'],
				"hole5" => $_POST['hole5_r3'],
				"hole6" => $_POST['hole6_r3'],
				"hole7" => $_POST['hole7_r3'],
				"hole8" => $_POST['hole8_r3'],
				"hole9" => $_POST['hole9_r3'],
				"hole10" => $_POST['hole10_r3'],
				"hole11" => $_POST['hole11_r3'],
				"hole12" => $_POST['hole12_r3'],
				"hole13" => $_POST['hole13_r3'],
				"hole14" => $_POST['hole14_r3'],
				"hole15" => $_POST['hole15_r3'],
				"hole16" => $_POST['hole16_r3'],
				"hole17" => $_POST['hole17_r3'],
				"hole18" => $_POST['hole18_r3']
			), array('scores_master_id' => $_GET['scores_id'],'scores_round_number' => '3'));
			
			$total_scores_r1 = $_POST['hole1_r1'] + $_POST['hole2_r1']+ $_POST['hole3_r1']+ $_POST['hole4_r1']+ $_POST['hole5_r1']+ $_POST['hole6_r1']+ $_POST['hole7_r1']+ $_POST['hole8_r1']+ $_POST['hole9_r1']+ $_POST['hole10_r1']+ $_POST['hole11_r1']+ $_POST['hole12_r1']+ $_POST['hole13_r1']+ $_POST['hole14_r1']+ $_POST['hole15_r1']+ $_POST['hole16_r1']+ $_POST['hole17_r1'] + $_POST['hole18_r1'];
			$total_scores_r2 = $_POST['hole1_r2'] + $_POST['hole2_r2']+ $_POST['hole3_r2']+ $_POST['hole4_r2']+ $_POST['hole5_r2']+ $_POST['hole6_r2']+ $_POST['hole7_r2']+ $_POST['hole8_r2']+ $_POST['hole9_r2']+ $_POST['hole10_r2']+ $_POST['hole11_r2']+ $_POST['hole12_r2']+ $_POST['hole13_r2']+ $_POST['hole14_r2']+ $_POST['hole15_r2']+ $_POST['hole16_r2']+ $_POST['hole17_r2'] + $_POST['hole18_r2'];
			
			
			 $total_scores = $total_scores_r1 + $total_scores_r2;
		
			
			$wpdb->update($wpdb->prefix.'jtour_scores',array(				
				"total_scores" => $total_scores
			), array('scores_id' => $_GET['scores_id']));
				
		}
	}
	
		$single_scores = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_scores where scores_id = '". @$_GET['scores_id']."'");
	$player_master_id = @$single_scores->player_master_id;
	$tournament_master_id = @$single_scores->tournament_master_id;
	$tour_master_id = @$single_scores->tour_master_id;	
	$money_earned = @$single_scores->money_earned;
	$disqualified = @$single_scores->disqualified;
	$withdrawal = @$single_scores->withdrawal;
	
	$single_scores_rounds1 = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_scores_rounds where scores_master_id = '". @$_GET['scores_id']."' and scores_round_number = '1'");
	$single_scores_rounds2 = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_scores_rounds where scores_master_id = '". @$_GET['scores_id']."' and scores_round_number = '2'");
	$single_scores_rounds3 = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_scores_rounds where scores_master_id = '". @$_GET['scores_id']."' and scores_round_number = '3'");

?>
<div class="wrap">
  <?php if( @$_GET['action'] == "add"){ ?>
  <h2>Add New Scores</h2>
  <?php } else if( @$_GET['action'] == "edit"){ ?>
  <h2>Edit Scores</h2>
  <?php } ?>
  <form name="frm" method="post" action="">
    <table cellpadding="0" cellspacing="5">
      <tr>
        <td><strong>Player :</strong></td>
      </tr>
      <tr>
        <td><select name="player_master_id">
            <option value="">--select--</option>
            <?php $rows_player_id = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jtour_players");
	foreach ( $rows_player_id as $rows_player_id ) {
	?>
            <option value="<?php echo $rows_player_id->players_id; ?>" <?php if($rows_player_id->players_id == $player_master_id){echo "selected";} ?>><?php echo $rows_player_id->players_first_name." ".$rows_player_id->players_last_name; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><strong>Tournament :</strong></td>
      </tr>
      <tr>
        <td><select name="tournament_master_id">
            <option value="">--select--</option>
            <?php $rows_tournament_id = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jtour_tournament");
	foreach ( $rows_tournament_id as $rows_tournament_id ) {
	 ?>
            <option value="<?php echo $rows_tournament_id->tournament_id; ?>" <?php if($rows_tournament_id->tournament_id == $tournament_master_id){echo "selected";} ?>><?php echo $rows_tournament_id->tournament_name; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><strong>Tour Year :</strong></td>
      </tr>
      <tr>
        <td><select name="tour_master_id">
            <option value="">--select--</option>
            <?php $rows_tour_id = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jtour_tour");
	foreach ( $rows_tour_id as $rows_tour_id ) {
	?>
            <option value="<?php echo $rows_tour_id->tour_id; ?>" <?php if($rows_tour_id->tour_id == $tour_master_id){echo "selected";} ?>><?php echo $rows_tour_id->tour_name; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><strong>Money Earned :</strong></td>
      </tr>
      <tr>
        <td><input type="text" name="money_earned" id="money_earned" value="<?php echo $money_earned; ?>"></td>
      </tr>
      <tr>
        <td><strong>Disqualified?</strong></td>
      </tr>
      <tr>
        <td><input type="radio" name="disqualified" id="disqualified" value="1" <?php if($disqualified == '1'){echo "checked";} ?>>
          Yes &nbsp;
          <input type="radio" name="disqualified" id="disqualified" value="0" <?php if($disqualified == '0'){echo "checked";} ?>>
          No </td>
      </tr>
      <tr>
        <td><strong>Withdrawal?</strong></td>
      </tr>
      <tr>
        <td><input type="radio" name="withdrawal" id="withdrawal" value="1" <?php if($withdrawal == '1'){echo "checked";} ?>>
          Yes &nbsp;
          <input type="radio" name="withdrawal" id="withdrawal" value="0" <?php if($withdrawal == '0'){echo "checked";} ?>>
          No </td>
      </tr>
      <tr>
        <td><legend class="par_course">Round 1</legend>
          <table width="100%" class="admintable">
            <tbody>
              <tr>
                <td><b>Hole 1 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds1->hole1; ?>" name="hole1_r1" ></td>
                <td><b>Hole 2 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds1->hole2; ?>" name="hole2_r1"></td>
                <td><b>Hole 3 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds1->hole3; ?>" name="hole3_r1"></td>
                <td><b>Hole 4 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds1->hole4; ?>" name="hole4_r1"></td>
                <td><b>Hole 5 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds1->hole5; ?>" name="hole5_r1"></td>
                <td><b>Hole 6 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds1->hole6; ?>" name="hole6_r1"></td>
              </tr>
              <tr>
                <td><b>Hole 7 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds1->hole7; ?>" name="hole7_r1"></td>
                <td><b>Hole 8 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds1->hole8; ?>" name="hole8_r1"></td>
                <td><b>Hole 9 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds1->hole9; ?>" name="hole9_r1"></td>
                <td><b>Hole 10 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds1->hole10; ?>" name="hole10_r1"></td>
                <td><b>Hole 11 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds1->hole11; ?>" name="hole11_r1"></td>
                <td><b>Hole 12 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds1->hole12; ?>" name="hole12_r1"></td>
              </tr>
              <tr>
                <td><b>Hole 13 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds1->hole13; ?>" name="hole13_r1"></td>
                <td><b>Hole 14 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds1->hole14; ?>" name="hole14_r1"></td>
                <td><b>Hole 15 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds1->hole15; ?>" name="hole15_r1"></td>
                <td><b>Hole 16 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds1->hole16; ?>" name="hole16_r1"></td>
                <td><b>Hole 17 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds1->hole17; ?>" name="hole17_r1"></td>
                <td><b>Hole 18 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds1->hole18; ?>" name="hole18_r1"></td>
              </tr>
            </tbody>
          </table></td>
      </tr>
      <tr>
        <td><legend class="par_course">Round 2</legend>
          <table width="100%" class="admintable">
            <tbody>
              <tr>
                <td><b>Hole 1 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds2->hole1; ?>" name="hole1_r2" ></td>
                <td><b>Hole 2 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds2->hole2; ?>" name="hole2_r2"></td>
                <td><b>Hole 3 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds2->hole3; ?>" name="hole3_r2"></td>
                <td><b>Hole 4 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds2->hole4; ?>" name="hole4_r2"></td>
                <td><b>Hole 5 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds2->hole5; ?>" name="hole5_r2"></td>
                <td><b>Hole 6 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds2->hole6; ?>" name="hole6_r2"></td>
              </tr>
              <tr>
                <td><b>Hole 7 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds2->hole7; ?>" name="hole7_r2"></td>
                <td><b>Hole 8 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds2->hole8; ?>" name="hole8_r2"></td>
                <td><b>Hole 9 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds2->hole9; ?>" name="hole9_r2"></td>
                <td><b>Hole 10 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds2->hole10; ?>" name="hole10_r2"></td>
                <td><b>Hole 11 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds2->hole11; ?>" name="hole11_r2"></td>
                <td><b>Hole 12 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds2->hole12; ?>" name="hole12_r2"></td>
              </tr>
              <tr>
                <td><b>Hole 13 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds2->hole13; ?>" name="hole13_r2"></td>
                <td><b>Hole 14 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds2->hole14; ?>" name="hole14_r2"></td>
                <td><b>Hole 15 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds2->hole15; ?>" name="hole15_r2"></td>
                <td><b>Hole 16 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds2->hole16; ?>" name="hole16_r2"></td>
                <td><b>Hole 17 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds2->hole17; ?>" name="hole17_r2"></td>
                <td><b>Hole 18 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds2->hole18; ?>" name="hole18_r2"></td>
              </tr>
            </tbody>
          </table></td>
      </tr>
      <tr>
        <td><legend class="par_course">Round 3</legend>
          <table width="100%" class="admintable">
            <tbody>
              <tr>
                <td><b>Hole 1 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds3->hole1; ?>" name="hole1_r3" ></td>
                <td><b>Hole 2 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds3->hole2; ?>" name="hole2_r3"></td>
                <td><b>Hole 3 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds3->hole3; ?>" name="hole3_r3"></td>
                <td><b>Hole 4 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds3->hole4; ?>" name="hole4_r3"></td>
                <td><b>Hole 5 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds3->hole5; ?>" name="hole5_r3"></td>
                <td><b>Hole 6 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds3->hole6; ?>" name="hole6_r3"></td>
              </tr>
              <tr>
                <td><b>Hole 7 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds3->hole7; ?>" name="hole7_r3"></td>
                <td><b>Hole 8 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds3->hole8; ?>" name="hole8_r3"></td>
                <td><b>Hole 9 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds3->hole9; ?>" name="hole9_r3"></td>
                <td><b>Hole 10 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds3->hole10; ?>" name="hole10_r3"></td>
                <td><b>Hole 11 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds3->hole11; ?>" name="hole11_r3"></td>
                <td><b>Hole 12 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds3->hole12; ?>" name="hole12_r3"></td>
              </tr>
              <tr>
                <td><b>Hole 13 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds3->hole13; ?>" name="hole13_r3"></td>
                <td><b>Hole 14 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds3->hole14; ?>" name="hole14_r3"></td>
                <td><b>Hole 15 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds3->hole15; ?>" name="hole15_r3"></td>
                <td><b>Hole 16 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds3->hole16; ?>" name="hole16_r3"></td>
                <td><b>Hole 17 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds3->hole17; ?>" name="hole17_r3"></td>
                <td><b>Hole 18 </b></td>
                <td><input type="text" size="5" value="<?php echo @$single_scores_rounds3->hole18; ?>" name="hole18_r3"></td>
              </tr>
            </tbody>
          </table></td>
      </tr>
      <tr>
        <td><?php if( @$_GET['action'] == "add"){ ?>
          <input type="hidden" name="addscores" value="1">
          <?php } else if( @$_GET['action'] == "edit"){ ?>
          <input type="hidden" name="editscores" value="1">
          <?php } ?>
          <input type="submit" name="submit" value="Submit" class="button button-primary button-large"></td>
      </tr>
    </table>
  </form>
  <br />
</div>
<?php
  }
			if( @$successdelete){		
				wp_redirect('admin.php?page=jtour_scores_management');
			}
			else if( @$success_scores){
				wp_redirect('admin.php?page=jtour_scores_management');
			}
		
}
}

/****** Tour Management ******/

function jtour_tour_fn(){
global $wpdb;
if( @$_REQUEST['page']=='jtour_tour_management'){
	if( @$_GET['action'] == 'delete'){
		$successdelete_tour = $wpdb->query("delete from {$wpdb->prefix}jtour_tour where tour_id = '".$_GET['tour_id']."'");
	}
	
	if(@$_GET['action'] == ""){ 
	?>
<div class="wrap">
  <h2>Tour Management <a class="add-new-h2" href="admin.php?page=jtour_tour_management&action=add">Add New</a> </h2>
</div>
<table class="wp-list-table widefat fixed posts">
  <thead>
    <tr>
      <th style="" class="manage-column column-cb check-column" id="cb" scope="col"></th>
      <th style="" class="manage-column column-title sortable desc" id="tour_name" scope="col"><span>Tour Name</span></th>
      <th><span>Action</span></th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <th style="" class="manage-column column-cb check-column" scope="col"></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Tour Name</span></th>
      <th><span>Action</span></th>
    </tr>
  </tfoot>
  <tbody id="the-list">
    <?php $rows_tour = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jtour_tour");
			if(count($rows_tour) > 0){
       			 foreach ( $rows_tour as $row_tour ) { ?>
    <tr class="post-78 type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0" id="post-78">
      <th class="check-column" scope="row"> <div class="locked-indicator"></div>
      </th>
      <td class="post-title page-title column-title"><a title="" href="admin.php?page=jtour_tour_management&amp;action=edit&amp;tour_id=<?php echo $row_tour->tour_id; ?>" class="row-title"> <?php echo $row_tour->tour_name; ?></a></td>
      <td><a title="edit" href="admin.php?page=jtour_tour_management&amp;action=edit&amp;tour_id=<?php echo $row_tour->tour_id; ?>" > Edit</a> | <a title="delete" href="admin.php?page=jtour_tour_management&amp;action=delete&amp;tour_id=<?php echo $row_tour->tour_id; ?>"> Delete</a></td>
    </tr>
    <?php } } else {?>
    <tr class="no-items">
      <td class="colspanchange" colspan="3">No Record found</td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<br />
<?php } else if(( @$_GET['action'] == "add") || ( @$_GET['action'] == 'edit')){ 
	if(( @$_REQUEST['action']=='add') && ( @$_REQUEST['addtour'] != '')){		
		
		if( @$_POST['tour_name'] != ''){
			// $wpdb->insert will return true or false based on if the query was successful.	
			$success_tour = $wpdb->insert($wpdb->prefix.'jtour_tour',array(
				"tour_name" => mysql_real_escape_string($_POST['tour_name'])
			));	
		}
	}

	if(( @$_GET['action'] == 'edit') && ( @$_POST['edittour'] != '')){
		
		if( @$_POST['tour_name'] != ''){
			$success_tour = $wpdb->update($wpdb->prefix.'jtour_tour',array(
				"tour_name" => mysql_real_escape_string($_POST['tour_name'])
			), array('tour_id' => $_GET['tour_id']));	
		}
	}
	
	$single_tour = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_tour where tour_id = '". @$_GET['tour_id']."'");
	$tour_name = @$single_tour->tour_name;
	

?>
<div class="wrap">
  <?php if( @$_GET['action'] == "add"){ ?>
  <h2>Add New Tour</h2>
  <?php } else if( @$_GET['action'] == "edit"){ ?>
  <h2>Edit Tour</h2>
  <?php } ?>
  <form name="frm" method="post" action="">
    <table cellpadding="0" cellspacing="5">
      <tr>
        <td><strong>Tour Name :</strong></td>
      </tr>
      <tr>
        <td><input type="text" name="tour_name" value="<?php echo $tour_name; ?>"></td>
      </tr>
      <tr>
        <td><?php if( @$_GET['action'] == "add"){ ?>
          <input type="hidden" name="addtour" value="1">
          <?php } else if( @$_GET['action'] == "edit"){ ?>
          <input type="hidden" name="edittour" value="1">
          <?php } ?>
          <input type="submit" name="submit" value="Submit" class="button button-primary button-large"></td>
      </tr>
    </table>
  </form>
  <br />
</div>
<?php
  }
			if( @$successdelete_tour){		
			//echo '<div style="color:red;">Data deleted successfully</div>';
			  wp_redirect('admin.php?page=jtour_tour_management');
			}		
			else if((( @$_POST['addtour'] != '') || ( @$_POST['edittour'] != '')) && ( @$_POST['tour_name'] == '')){
			echo '<div style="color:red;">Please enter tour name</div>';
			}
			else if( @$success_tour){
			wp_redirect('admin.php?page=jtour_tour_management');
			//echo '<div style="color:red;">Data inserted successfully</div>';
			}
		
}
}

/**** Date picker *****/

function hkdc_admin_styles() {
	wp_enqueue_script('jquery-ui-datepicker');  
    wp_enqueue_style( 'jquery-ui-datepicker-style' , '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');  
}  
add_action('admin_print_styles', 'hkdc_admin_styles');  
function hkdc_admin_scripts() {  
    wp_enqueue_script( 'jquery-ui-datepicker' );  
}  
add_action('admin_enqueue_scripts', 'hkdc_admin_scripts'); 

/****** Tournament Management ******/

function jtour_tournament_fn(){
global $wpdb;
if( @$_REQUEST['page']=='jtour_tournament_management'){
	if( @$_GET['action'] == 'delete'){
		$successdelete_tournament = $wpdb->query("delete from {$wpdb->prefix}jtour_tournament where tournament_id = '".$_GET['tournament_id']."'");
	}
	
	if( @$_GET['action'] == ""){ 
	?>
<div class="wrap">
  <h2>Tournament Management <a class="add-new-h2" href="admin.php?page=jtour_tournament_management&action=add">Add New</a> </h2>
</div>
<table class="wp-list-table widefat fixed posts">
  <thead>
    <tr>
      <th style="" class="manage-column column-cb check-column" scope="col"></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Tournament Name</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Tour Year</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Course Name</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Start Date</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>End Date</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>No of Rounds</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Tournament Purse</span></th>
      <th><span>Action</span></th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <th style="" class="manage-column column-cb check-column" scope="col"></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Tournament Name</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Tour Year</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Course Name</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Start Date</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>End Date</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>No of Rounds</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Tournament Purse</span></th>
      <th><span>Action</span></th>
    </tr>
  </tfoot>
  <tbody id="the-list">
    <?php $rows_tournament = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jtour_tournament");
			if(count($rows_tournament) > 0){
       			 foreach ( $rows_tournament as $row_tournament ) { ?>
    <tr class="post-78 type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0" id="post-78">
      <th class="check-column" scope="row"> <div class="locked-indicator"></div>
      </th>
      <td class="post-title page-title column-title"><a title="" href="admin.php?page=jtour_tournament_management&amp;action=edit&amp;tournament_id=<?php echo $row_tournament->tournament_id; ?>" class="row-title"> <?php echo $row_tournament->tournament_name; ?></a></td>
      <td class="post-title page-title column-title"><?php 
					$single_tourid = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_tour where tour_id = '".$row_tournament->tour_master_id."'");
					echo $single_tourid->tour_name; ?></td>
      <td class="post-title page-title column-title"><?php 
					$single_courseid = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_course where course_id = '".$row_tournament->course_master_id."'");
					echo $single_courseid->course_name; ?></td>
      <td class="post-title page-title column-title"><?php echo $row_tournament->tournament_startdate; ?></td>
      <td class="post-title page-title column-title"><?php echo $row_tournament->tournament_enddate; ?></td>
      <td class="post-title page-title column-title"><?php echo $row_tournament->no_of_rounds; ?></td>
      <td class="post-title page-title column-title"><?php echo $row_tournament->tournament_purse; ?></td>
      <td><a title="edit" href="admin.php?page=jtour_tournament_management&amp;action=edit&amp;tournament_id=<?php echo $row_tournament->tournament_id; ?>" > Edit</a> | <a title="delete" href="admin.php?page=jtour_tournament_management&amp;action=delete&amp;tournament_id=<?php echo $row_tournament->tournament_id; ?>"> Delete</a></td>
    </tr>
    <?php } } else {?>
    <tr class="no-items">
      <td class="colspanchange" colspan="3">No Record found</td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<br />
<?php } else if(( @$_GET['action'] == "add") || ( @$_GET['action'] == 'edit')){ 
	if(( @$_REQUEST['action']=='add') && ( @$_REQUEST['addtournament'] != '')){		
		
		if( @$_POST['tournament_name'] != ''){
			// $wpdb->insert will return true or false based on if the query was successful.	
			$success_tournament = $wpdb->insert($wpdb->prefix.'jtour_tournament',array(
				"tournament_name" => mysql_real_escape_string($_POST['tournament_name']),
				"tour_master_id" => mysql_real_escape_string($_POST['tour_master_id']),
				"course_master_id" => mysql_real_escape_string($_POST['course_master_id']),
				"tournament_startdate" => mysql_real_escape_string($_POST['tournament_startdate']),
				"tournament_enddate" => mysql_real_escape_string($_POST['tournament_enddate']),
				"no_of_rounds" => mysql_real_escape_string($_POST['no_of_rounds']),
				"tournament_purse" => mysql_real_escape_string($_POST['tournament_purse'])
			));	
		}
	}

	if(( @$_GET['action'] == 'edit') && ( @$_POST['edittournament'] != '')){
		
		if( @$_POST['tournament_name'] != ''){
			$success_tournament = $wpdb->update($wpdb->prefix.'jtour_tournament',array(
				"tournament_name" => mysql_real_escape_string($_POST['tournament_name']),
				"tour_master_id" => mysql_real_escape_string($_POST['tour_master_id']),
				"course_master_id" => mysql_real_escape_string($_POST['course_master_id']),
				"tournament_startdate" => mysql_real_escape_string($_POST['tournament_startdate']),
				"tournament_enddate" => mysql_real_escape_string($_POST['tournament_enddate']),
				"no_of_rounds" => mysql_real_escape_string($_POST['no_of_rounds']),
				"tournament_purse" => mysql_real_escape_string($_POST['tournament_purse'])
			), array('tournament_id' => $_GET['tournament_id']));	
		}
	}
	
    $single_tournament = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_tournament where tournament_id = '". @$_GET['tournament_id']."'");
	$tournament_name = @$single_tournament->tournament_name;
	$tournament_startdate = @$single_tournament->tournament_startdate;
	$tournament_enddate = @$single_tournament->tournament_enddate;
	$no_of_rounds = @$single_tournament->no_of_rounds;
	$tournament_purse = @$single_tournament->tournament_purse;
	$tour_master_id = @$single_tournament->tour_master_id;
	$course_master_id = @$single_tournament->course_master_id;
	

?>
<div class="wrap">
  <?php if( @$_GET['action'] == "add"){ ?>
  <h2>Add New Tournament</h2>
  <?php } else if( @$_GET['action'] == "edit"){ ?>
  <h2>Edit Tournament</h2>
  <?php } ?>
  <form name="frm" method="post" action="">
    <table cellpadding="0" cellspacing="5">
      <tr>
        <td><strong>Tournament Name :</strong></td>
      </tr>
      <tr>
        <td><input type="text" name="tournament_name" value="<?php echo $tournament_name; ?>"></td>
      </tr>
      <tr>
        <td><strong>Tour Year :</strong></td>
      </tr>
      <tr>
        <td><select name="tour_master_id">
            <option value="">--select--</option>
            <?php $rows_tour_id = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jtour_tour");
	foreach ( $rows_tour_id as $rows_tour_id ) {
	 ?>
            <option value="<?php echo $rows_tour_id->tour_id; ?>" <?php if($tour_master_id == $rows_tour_id->tour_id){echo "selected";} ?>><?php echo $rows_tour_id->tour_name; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><strong>Course Name :</strong></td>
      </tr>
      <tr>
        <td><select name="course_master_id">
            <option value="">--select--</option>
            <?php $rows_course_id = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jtour_course");
	foreach ( $rows_course_id as $rows_course_id ) {
	?>
            <option value="<?php echo $rows_course_id->course_id; ?>" <?php if($course_master_id == $rows_course_id->course_id){echo "selected";} ?>><?php echo $rows_course_id->course_name; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><strong>Tournament Start Date :</strong></td>
      </tr>
      <tr>
        <td><script type="text/javascript">
        jQuery(document).ready(function($) {
        $('.custom_date').datepicker({
        dateFormat : 'yy-mm-dd'
        });
        });
       </script>
          <input type="text" id="tournament_startdate" name="tournament_startdate" value="<?php echo $tournament_startdate; ?>" class="custom_date"></td>
      </tr>
      <tr>
        <td><strong>Tournament End Date :</strong></td>
      </tr>
      <tr>
        <td><input type="text" id="tournament_enddate" name="tournament_enddate" value="<?php echo $tournament_enddate; ?>" class="custom_date"></td>
      </tr>
      <tr>
        <td><strong>No of Rounds :</strong></td>
      </tr>
      <tr>
        <td><input type="text" name="no_of_rounds" value="<?php echo $no_of_rounds; ?>"></td>
      </tr>
      <tr>
        <td><strong>Tournament Purse :</strong></td>
      </tr>
      <tr>
        <td><input type="text" name="tournament_purse" value="<?php echo $tournament_purse; ?>"></td>
      </tr>
      <tr>
        <td><?php if( @$_GET['action'] == "add"){ ?>
          <input type="hidden" name="addtournament" value="1">
          <?php } else if( @$_GET['action'] == "edit"){ ?>
          <input type="hidden" name="edittournament" value="1">
          <?php } ?>
          <input type="submit" name="submit" value="Submit" class="button button-primary button-large"></td>
      </tr>
    </table>
  </form>
  <br />
</div>
<?php
  }
			if( @$successdelete_tournament){		
			//echo '<div style="color:red;">Data deleted successfully</div>';
			  wp_redirect('admin.php?page=jtour_tournament_management');
			}		
			else if((( @$_POST['addtournament'] != '') || ( @$_POST['edittournament'] != '')) && ( @$_POST['tournament_name'] == '')){
			echo '<div style="color:red;">Please enter tournament name</div>';
			}
			else if( @$success_tournament){
			wp_redirect('admin.php?page=jtour_tournament_management');
			//echo '<div style="color:red;">Data inserted successfully</div>';
			}
		
}
}
/****** Players Management ******/

function jtour_players_fn(){
global $wpdb;
if( @$_REQUEST['page'] =='jtour_players_management'){
	if( @$_GET['action'] == 'delete'){
		$successdelete_players = $wpdb->query("delete from {$wpdb->prefix}jtour_players where players_id = '". @$_GET['players_id']."'");
		$successdelete_players_tournaments = $wpdb->query("delete from  {$wpdb->prefix}jtour_tournament_players where player_master_id = '". @$_GET['players_id']."'");
	}
	
	if( @$_GET['action'] == ""){ 
	?>
<div class="wrap">
  <h2>Players Management <a class="add-new-h2" href="admin.php?page=jtour_players_management&action=add">Add New</a> </h2>
</div>
<table class="wp-list-table widefat fixed posts">
  <thead>
    <tr>
      <th style="" class="manage-column column-cb check-column" scope="col"></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>First Name</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Middle Name</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Last Name</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Tournaments Entered </span></th>
      <th><span>Action</span></th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <th style="" class="manage-column column-cb check-column" scope="col"></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>First Name</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Middle Name</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Last Name</span></th>
      <th style="" class="manage-column column-title sortable desc" scope="col"><span>Tournaments Entered </span></th>
      <th><span>Action</span></th>
    </tr>
  </tfoot>
  <tbody id="the-list">
    <?php $rows_players = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jtour_players");
			if(count($rows_players) > 0){
       			 foreach ( $rows_players as $row_players ) { ?>
    <tr class="post-78 type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0" id="post-78">
      <th class="check-column" scope="row"> <div class="locked-indicator"></div>
      </th>
      <td class="post-title page-title column-title"><a title="" href="admin.php?page=jtour_players_management&amp;action=edit&amp;players_id=<?php echo $row_players->players_id; ?>" class="row-title"> <?php echo $row_players->players_first_name; ?></a></td>
      <td class="post-title page-title column-title"><?php echo $row_players->players_middle_name; ?></td>
      <td class="post-title page-title column-title"><?php echo $row_players->players_last_name; ?></td>
      <td class="post-title page-title column-title"><?php
					$rows_tournament_players = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jtour_tournament_players
					INNER JOIN {$wpdb->prefix}jtour_tournament ON {$wpdb->prefix}jtour_tournament.tournament_id = {$wpdb->prefix}jtour_tournament_players.tournament_master_id where player_master_id = '".$row_players->players_id."'");
					$ii = 1;
					foreach($rows_tournament_players as $row_tournament_players){
						if($ii != '1'){echo ",";}

						echo $row_tournament_players->tournament_name;
						
						$ii++;
						}
					 ?></td>
      <td><a title="edit" href="admin.php?page=jtour_players_management&amp;action=edit&amp;players_id=<?php echo $row_players->players_id; ?>" > Edit</a> | <a title="delete" href="admin.php?page=jtour_players_management&amp;action=delete&amp;players_id=<?php echo $row_players->players_id; ?>"> Delete</a></td>
    </tr>
    <?php } } else {?>
    <tr class="no-items">
      <td class="colspanchange" colspan="3">No Record found</td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<br />
<?php } else if(( @$_GET['action'] == "add") || ( @$_GET['action'] == 'edit')){ 
	if(( @$_REQUEST['action']=='add') && ( @$_REQUEST['addplayers'] != '')){		
		
		if( @$_POST['players_first_name'] != ''){
			// $wpdb->insert will return true or false based on if the query was successful.	
			$success_players = $wpdb->insert($wpdb->prefix.'jtour_players',array(
				"players_first_name" => mysql_real_escape_string($_POST['players_first_name']),
				"players_middle_name" => mysql_real_escape_string($_POST['players_middle_name']),
				"players_last_name" => mysql_real_escape_string($_POST['players_last_name']),
				"players_street_address" => mysql_real_escape_string($_POST['players_street_address']),
				"players_city" => mysql_real_escape_string($_POST['players_city']),
				"players_state" => mysql_real_escape_string($_POST['players_state']),
				"players_zip" => mysql_real_escape_string($_POST['players_zip']),
				"players_emergency_contact_name" => mysql_real_escape_string($_POST['players_emergency_contact_name'])
			));
			
			$player_master_id = $wpdb->insert_id;	
			
			if( @$_POST['tournament_master_id'] != ''){				
				foreach ( @$_POST['tournament_master_id'] as $tournament_master_id) {
				$success_tournament_players = $wpdb->insert($wpdb->prefix.'jtour_tournament_players',array(
				"player_master_id" => $player_master_id,
				"tournament_master_id" => $tournament_master_id
			));
					}
				
				}
		}
	}

	if(( @$_GET['action'] == 'edit') && ( @$_POST['editplayers'] != '')){
		
		if( @$_POST['players_first_name'] != ''){
			$success_players = $wpdb->update($wpdb->prefix.'jtour_players',array(
				"players_first_name" => mysql_real_escape_string($_POST['players_first_name']),
				"players_middle_name" => mysql_real_escape_string($_POST['players_middle_name']),
				"players_last_name" => mysql_real_escape_string($_POST['players_last_name']),
				"players_street_address" => mysql_real_escape_string($_POST['players_street_address']),
				"players_city" => mysql_real_escape_string($_POST['players_city']),
				"players_state" => mysql_real_escape_string($_POST['players_state']),
				"players_zip" => mysql_real_escape_string($_POST['players_zip']),
				"players_emergency_contact_name" => mysql_real_escape_string($_POST['players_emergency_contact_name'])
			), array('players_id' => $_GET['players_id']));	
			
			$successdelete_players_tournaments = $wpdb->query("delete from  {$wpdb->prefix}jtour_tournament_players where player_master_id = '". @$_GET['players_id']."'");
			if( @$_POST['tournament_master_id'] != ''){				
				foreach ($_POST['tournament_master_id'] as $tournament_master_id) {
				$success_tournament_players = $wpdb->insert($wpdb->prefix.'jtour_tournament_players',array(
				"player_master_id" => $_GET['players_id'],
				"tournament_master_id" => $tournament_master_id
			));
					}
				
				}
		}
	}
	
    $single_players = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_players where players_id = '". @$_GET['players_id']."'");
	$players_id = @$single_players->players_id;
	$players_first_name = @$single_players->players_first_name;
	$players_middle_name = @$single_players->players_middle_name;
	$players_last_name = @$single_players->players_last_name;
	$players_street_address = @$single_players->players_street_address;
	$players_city = @$single_players->players_city;
	$players_state = @$single_players->players_state;
	$players_zip = @$single_players->players_zip;
	$players_emergency_contact_name = @$single_players->players_emergency_contact_name;


?>
<div class="wrap">
  <?php if( @$_GET['action'] == "add"){ ?>
  <h2>Add New Players</h2>
  <?php } else if( @$_GET['action'] == "edit"){ ?>
  <h2>Edit Players</h2>
  <?php } ?>
  <form name="frm" method="post" action="">
    <table cellpadding="0" cellspacing="5">
      <tr>
        <td><strong>First Name :</strong></td>
      </tr>
      <tr>
        <td><input type="text" name="players_first_name" value="<?php echo $players_first_name; ?>"></td>
      </tr>
      <tr>
        <td><strong>Middle Name :</strong></td>
      </tr>
      <tr>
        <td><input type="text" name="players_middle_name" value="<?php echo $players_middle_name; ?>"></td>
      </tr>
      <tr>
        <td><strong>Last Name :</strong></td>
      </tr>
      <tr>
        <td><input type="text" name="players_last_name" value="<?php echo $players_last_name; ?>"></td>
      </tr>
      <tr>
        <td><strong>Street Address :</strong></td>
      </tr>
      <tr>
        <td><input type="text" name="players_street_address" value="<?php echo $players_street_address; ?>"></td>
      </tr>
      <tr>
        <td><strong>City :</strong></td>
      </tr>
      <tr>
        <td><input type="text" name="players_city" value="<?php echo $players_city; ?>"></td>
      </tr>
      <tr>
        <td><strong>State :</strong></td>
      </tr>
      <tr>
        <td><input type="text" name="players_state" value="<?php echo $players_state; ?>"></td>
      </tr>
      <tr>
        <td><strong>Zip :</strong></td>
      </tr>
      <tr>
        <td><input type="text" name="players_zip" value="<?php echo $players_zip; ?>"></td>
      </tr>
      <tr>
        <td><strong>Emergency Contact Name :</strong></td>
      </tr>
      <tr>
        <td><input type="text" name="players_emergency_contact_name" value="<?php echo $players_emergency_contact_name; ?>"></td>
      </tr>
      <tr>
        <td><strong>Tournaments Entered :</strong></td>
      </tr>
      <tr>
        <td><select name="tournament_master_id[]" multiple="multiple">
            <option value="">--select--</option>
            <?php $rows_tournament_id = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jtour_tournament");
	foreach ( $rows_tournament_id as $row_tournament_id ) {
		$single_tournament_player = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_tournament_players where tournament_master_id = '".$row_tournament_id->tournament_id."' and player_master_id = '".$players_id."'");				
	 ?>
            <option value="<?php echo $row_tournament_id->tournament_id; ?>" <?php if(count($single_tournament_player) > 0){echo "selected";} ?>><?php echo $row_tournament_id->tournament_name; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php if( @$_GET['action'] == "add"){ ?>
          <input type="hidden" name="addplayers" value="1">
          <?php } else if( @$_GET['action'] == "edit"){ ?>
          <input type="hidden" name="editplayers" value="1">
          <?php } ?>
          <input type="submit" name="submit" value="Submit" class="button button-primary button-large"></td>
      </tr>
    </table>
  </form>
  <br />
</div>
<?php
  }
			if( @$successdelete_players){
				wp_redirect('admin.php?page=jtour_players_management');
			}else if((( @$_POST['addplayers'] != '') || ( @$_POST['editplayers'] != '')) && ( @$_POST['players_first_name'] == '')){
				echo '<div style="color:red;">Please enter Players First Name</div>';
			}else if( @$success_players){
				wp_redirect('admin.php?page=jtour_players_management');
			}		
}
}
/*	Include plugin function file	*/
if( file_exists( plugin_dir_path(__FILE__).'tournament-addon-frontend.php' ) ){
	include_once( plugin_dir_path(__FILE__).'tournament-addon-frontend.php' );
}