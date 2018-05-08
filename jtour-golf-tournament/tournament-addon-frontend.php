<?php 
//[tournament_list]
function func_tournament_list( $atts ){ global $wpdb;
?>
	<table width="100%" class="adminlist">
		<thead>
			<tr>
				<th width="40%" style="text-align: left !important;">Tournament</th>
				<th width="30%">Date</th>
				<th width="10%">Purse</th>
				<th width="20%">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
				<?php $tournaments_results = $wpdb->get_results("select * from {$wpdb->prefix}jtour_tournament"); 				
				foreach($tournaments_results as $tournaments_result)
				{				
				?>			
				<tr class="row0">
        			<td width="40%" align="left">
                        <h1 style="font-size: 15px !important;margin:0 !important"><?php echo $tournaments_result->tournament_name; ?></h1>
                    </td>
                    <td width="30%" align="center"><?php echo date("M d", strtotime($tournaments_result->tournament_startdate))." - ".date("M d, Y", strtotime($tournaments_result->tournament_enddate)); ?></td>
        			<td width="10%" align="right">
        				$<?php echo $tournaments_result->tournament_purse; ?></td>
                    <td width="20%" align="center">
        				<a href="leaderboard/?tournament_id=<?php echo $tournaments_result->tournament_id; ?>">Leaderboard</a>
                    </td>        			
    			</tr>
                <?php } ?>
			
					</tbody>
	</table>
<?php }
add_shortcode( 'tournament_list', 'func_tournament_list' );
//[tournament_leaderboard]
function func_tournament_leaderboard( $atts ){ 
global $wpdb;
$tournament_name = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_tournament
WHERE tournament_id = '".$_GET['tournament_id']."'");
?>
<script type="text/javascript" language="javascript">
	// <![CDATA[
		function showHide(key) 
		{
		    var ele = document.getElementById("showHideDiv"+key);
		    var ele2 = document.getElementById("btn_"+key);
		    if(ele.style.display == "block") 
		    	{
		            ele.style.display = "none";
		            ele2.src = 'http://cdn.bluegolf.com/images/smallbtn/expand.png';
		      }
		    else 
		    	{
		        	ele.style.display = "block";
		        	ele2.src = 'http://cdn.bluegolf.com/images/smallbtn/contract.png';
		    	}
		}
	// ]]>
	</script>
<div align="center">
<h1><?php echo $tournament_name->tournament_name; ?></h1>
<p><?php echo  date("M d", strtotime($tournament_name->tournament_startdate))." - ".date("M d, Y", strtotime($tournament_name->tournament_enddate)); ?></p>
</div>
<table class="adminlist">
                	<thead>
						<tr>
							<th rowspan="2">Pos</th>
							<th width="200px" rowspan="2">Player</th>
                            <th colspan="3">Scoring To Par</th>							
							<th colspan="3">Rounds</th>
                            <th style="width:5em;" rowspan="2">Total</th>							
							<th style="width:6em;" rowspan="2">Purse</th>
						</tr>
						<tr class="sub">
							<th>Total</th>
                            <th>Thru</th>
                            <th>Current</th>
							<th>1</th>
							<th>2</th>  
                           <?php  if($tournament_name->no_of_rounds == '3'){    ?>
                           <th>3</th>  
                           <?php } ?>                    
						</tr>
					</thead>
					<tbody>
                    <?php 
					$scorecard_rows = $wpdb->get_results("select * from {$wpdb->prefix}jtour_scores
					INNER JOIN {$wpdb->prefix}jtour_players ON {$wpdb->prefix}jtour_players.players_id = {$wpdb->prefix}jtour_scores.player_master_id
					where tournament_master_id = '".$_GET['tournament_id']."' order by total_scores asc");
					
				
					$inc=1;
					//$data = (array)$scorecard_rows;
					$all_totalscores = array();
					for($i=0;$i<count($scorecard_rows);$i++){
$data = $scorecard_rows[$i];
$all_totalscores[] = $data->total_scores;
	
					  }

$count_all_totalscores = array_count_values($all_totalscores);
					
					
					foreach($scorecard_rows as $scorecard_row)
					{	
					
					if (in_array($scorecard_row->total_scores, $scorecard_row1)) {echo "G";}
					 $scorecard_round_rows = $wpdb->get_row("select * from {$wpdb->prefix}jtour_scores_rounds
					 INNER JOIN {$wpdb->prefix}jtour_scores ON {$wpdb->prefix}jtour_scores.scores_id = {$wpdb->prefix}jtour_scores_rounds.scores_master_id
					where scores_master_id = '".$scorecard_row->scores_id."' and scores_round_number = '1'"); 		
					
					$total_round1 = $scorecard_round_rows->hole1+$scorecard_round_rows->hole2+$scorecard_round_rows->hole3+$scorecard_round_rows->hole4+$scorecard_round_rows->hole5+$scorecard_round_rows->hole6+$scorecard_round_rows->hole7+$scorecard_round_rows->hole8+$scorecard_round_rows->hole9+$scorecard_round_rows->hole10+$scorecard_round_rows->hole11+$scorecard_round_rows->hole12+$scorecard_round_rows->hole13+$scorecard_round_rows->hole14+$scorecard_round_rows->hole15+$scorecard_round_rows->hole16+$scorecard_round_rows->hole17+$scorecard_round_rows->hole18;
					
					$scorecard_round_rows2 = $wpdb->get_row("select * from {$wpdb->prefix}jtour_scores_rounds
					INNER JOIN {$wpdb->prefix}jtour_scores ON {$wpdb->prefix}jtour_scores.scores_id = {$wpdb->prefix}jtour_scores_rounds.scores_master_id
					where scores_master_id = '".$scorecard_row->scores_id."' and scores_round_number = '2'"); 
					
					$total_round2 = $scorecard_round_rows2->hole1+$scorecard_round_rows2->hole2+$scorecard_round_rows2->hole3+$scorecard_round_rows2->hole4+$scorecard_round_rows2->hole5+$scorecard_round_rows2->hole6+$scorecard_round_rows2->hole7+$scorecard_round_rows2->hole8+$scorecard_round_rows2->hole9+$scorecard_round_rows2->hole10+$scorecard_round_rows2->hole11+$scorecard_round_rows2->hole12+$scorecard_round_rows2->hole13+$scorecard_round_rows2->hole14+$scorecard_round_rows2->hole15+$scorecard_round_rows2->hole16+$scorecard_round_rows2->hole17+$scorecard_round_rows2->hole18;
					
					$scorecard_round_rows3 = $wpdb->get_row("select * from {$wpdb->prefix}jtour_scores_rounds
					INNER JOIN {$wpdb->prefix}jtour_scores ON {$wpdb->prefix}jtour_scores.scores_id = {$wpdb->prefix}jtour_scores_rounds.scores_master_id
					where scores_master_id = '".$scorecard_row->scores_id."' and scores_round_number = '3'"); 
					
					$total_round3 = $scorecard_round_rows3->hole1+$scorecard_round_rows3->hole2+$scorecard_round_rows3->hole3+$scorecard_round_rows3->hole4+$scorecard_round_rows3->hole5+$scorecard_round_rows3->hole6+$scorecard_round_rows3->hole7+$scorecard_round_rows3->hole8+$scorecard_round_rows3->hole9+$scorecard_round_rows3->hole10+$scorecard_round_rows3->hole11+$scorecard_round_rows3->hole12+$scorecard_round_rows3->hole13+$scorecard_round_rows3->hole14+$scorecard_round_rows3->hole15+$scorecard_round_rows3->hole16+$scorecard_round_rows3->hole17+$scorecard_round_rows3->hole18;	
					
					$tournament_name = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_tournament
WHERE tournament_id = '".$scorecard_round_rows->tournament_master_id."'");
					
					 $scorecard_round_par = $wpdb->get_row("SELECT * FROM  {$wpdb->prefix}jtour_course_rounds
WHERE course_master_id = '".$tournament_name->course_master_id."' and course_round_number = '1'");

 $course_name = $wpdb->get_row("SELECT * FROM   {$wpdb->prefix}jtour_course
WHERE course_id = '".$tournament_name->course_master_id."'");
if($total_round2 != 0){
$current =  $total_round2 - $course_name->par_of_course;
}
else if($total_round2 == 0){
	$current =  $total_round1 - $course_name->par_of_course;
}

$round1_par =  $total_round1 - $course_name->par_of_course;

if($total_round2 != 0){
$total_par = $current + $round1_par;	
}
else if($total_round2 == 0){
$total_par = $current;
}
 


if($current < 0){$color_par = 'red'; $sym = '';} else {$color_par = 'black'; $sym = '+';}	

if($total_par < 0){$color_par1 = 'red'; $sym1 = '';} else {$color_par1 = 'black'; $sym1 = '+';}

if($tournament_name->no_of_rounds == '3'){
if(($total_round1 != 0) && ($total_round2 != 0) && ($total_round3 != 0)){
$rounds_completed = 'F';
}
else if(($total_round1 != 0) && ($total_round2 == 0) && ($total_round3 == 0)){$rounds_completed = '1';}
else if(($total_round1 != 0) && ($total_round2 != 0) && ($total_round3 == 0)){$rounds_completed = '2';}
}
else if($tournament_name->no_of_rounds == '2'){
if(($total_round1 != 0) && ($total_round2 != 0)){
$rounds_completed = 'F';
}
else if(($total_round1 != 0) && ($total_round2 == 0)){$rounds_completed = '1';}
}

/************/

$totalscores = $scorecard_row->total_scores;
if($totalscores != $sametotal){$abc = $inc;}
if($totalscores == $sametotal){$pos = $abc;}else{$pos = $inc;}
$sametotal = $totalscores;
?>
						<tr class="row0">
								<td><?php  if($count_all_totalscores[$totalscores] > 1) {   echo "T";} echo $pos; //if(!$m.contains($scorecard_rows)){echo "T";}echo $pos; ?></td>
								<td id="expand" class="left">
											<a style="cursor:hand !important;" onclick="return showHide(<?php echo $scorecard_row->scores_id; ?>);">
												<img border="0" src="http://cdn.bluegolf.com/images/smallbtn/expand.png" alt="expand" id="btn_<?php echo $scorecard_row->scores_id; ?>">
												 <?php echo $scorecard_row->players_first_name." ".$scorecard_row->players_last_name; ?>
                                             </a>
								</td>
                                <td align="center">
                                <font color="<?php echo $color_par1; ?>"><?php  if($total_par == $course_name->par_of_course){echo 'E';}else{echo $sym1.$total_par;} ?></font>
                                </td>
                                <td align="center"><?php echo $rounds_completed; ?></td>
                                <td align="center">
                                <font color="<?php echo $color_par; ?>"><?php if($total_round2 == $course_name->par_of_course){echo 'E';}else{echo $sym.$current;} ?></font>
                                </td>								
								<td><?php echo $total_round1; ?></td>
								<td><?php echo $total_round2; ?></td>
                               <?php if($tournament_name->no_of_rounds == '3'){  ?>
                               <td><?php echo $total_round3; ?></td>
                               <?php } ?>						
								<td align="center"><?php echo $scorecard_row->total_scores; ?></td>								
								<td align="right"> $<?php echo $scorecard_row->money_earned; ?></td>
						</tr>
						<tr>
							<td colspan="11">
								<div style="display:none;" id="showHideDiv<?php echo $scorecard_row->scores_id; ?>">
									<table cellspacing="0px" cellpadding="0px" align="left" class="adminlist">
										<tbody>
											<tr align="left">
												<td width="90px" align="center" class="noborder" id="tdImg_3791">&nbsp;													
												</td>
												<td class="noborder" id="exp_3791">
													<table width="100%" class="leadersub">
														<tbody>
															<tr>
																<td class="noborder">
																	<a href="<?php echo get_site_url(); ?>/tournament-list/scoreboard?scores_id=<?php echo $scorecard_row->scores_id; ?>">Full Scorecard</a> | 
																	<a href="<?php echo get_site_url(); ?>/tournament-list/profile?player_id=<?php echo $scorecard_row->player_master_id; ?>">Profile</a>
																</td>
															</tr>
														</tbody>
													</table>
													<div id="exp_scorecard_3791">
														<table cellspacing="0px" cellpadding="0px" class="adminlist">
															<thead>
																<tr valign="top" align="center" class="scorecardlight">
																	<th align="left" class="title">Hole</th>
                                                                    <th>1</th>
                                                                    <th>2</th>
                                                                    <th>3</th>
                                                                    <th>4</th>
                                                                    <th>5</th>
                                                                    <th>6</th>
                                                                    <th>7</th>
                                                                    <th>8</th>
                                                                    <th>9</th>
                                                                    <th>10</th>
                                                                    <th>11</th>
                                                                    <th>12</th>
                                                                    <th>13</th>
                                                                    <th>14</th>
                                                                    <th>15</th>
                                                                    <th>16</th>
                                                                    <th>17</th>
                                                                    <th>18</th>
																</tr>
															</thead>
															<tbody>	
                                                             <tr valign="top" align="center" class="row0">
                                                                <td align="left" class="title">Par for each Hole</td>
                                                                    <td class="birdie"><?php echo $scorecard_round_par->par_for_hole1;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_par->par_for_hole2;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_par->par_for_hole3;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_par->par_for_hole4;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_par->par_for_hole5;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_par->par_for_hole6;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_par->par_for_hole7;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_par->par_for_hole8;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_par->par_for_hole9;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_par->par_for_hole10;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_par->par_for_hole11;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_par->par_for_hole12;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_par->par_for_hole13;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_par->par_for_hole14;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_par->par_for_hole15;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_par->par_for_hole16;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_par->par_for_hole17;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_par->par_for_hole18;  ?></td>
                                                                    
                                                              </tr>																					
																<tr valign="top" align="center" class="row0">
																	<td align="left" class="title">Score</td>                   
                                                                    <td class="birdie"><?php echo $scorecard_round_rows->hole1;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_rows->hole2;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_rows->hole3;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_rows->hole4;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_rows->hole5;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_rows->hole6;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_rows->hole7;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_rows->hole8;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_rows->hole9;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_rows->hole10;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_rows->hole11;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_rows->hole12;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_rows->hole13;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_rows->hole14;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_rows->hole15;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_rows->hole16;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_rows->hole17;  ?></td>
                                                                    <td class="birdie"><?php echo $scorecard_round_rows->hole18;  ?></td>
																</tr>
															</tbody>
														</table>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</td>
						</tr>
                        
                 	<?php $inc++; } ?>
						
			</tbody>
					<thead><tr><th colspan="11">&nbsp;</th></tr></thead>
				</table>
<?php
}
add_shortcode( 'tournament_leaderboard', 'func_tournament_leaderboard' );

//[tournament_scoreboard]
function func_tournament_scoreboard( $atts ){ 
global $wpdb;
$scorecard_full_rows1 = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_scores_rounds
INNER JOIN {$wpdb->prefix}jtour_scores ON {$wpdb->prefix}jtour_scores.scores_id = {$wpdb->prefix}jtour_scores_rounds.scores_master_id
WHERE scores_master_id = '".$_GET['scores_id']."' and scores_round_number = '1'"); 

$total_round1 = $scorecard_full_rows1->hole1+$scorecard_full_rows1->hole2+$scorecard_full_rows1->hole3+$scorecard_full_rows1->hole4+$scorecard_full_rows1->hole5+$scorecard_full_rows1->hole6+$scorecard_full_rows1->hole7+$scorecard_full_rows1->hole8+$scorecard_full_rows1->hole9+$scorecard_full_rows1->hole10+$scorecard_full_rows1->hole11+$scorecard_full_rows1->hole12+$scorecard_full_rows1->hole13+$scorecard_full_rows1->hole14+$scorecard_full_rows1->hole15+$scorecard_full_rows1->hole16+$scorecard_full_rows1->hole17+$scorecard_full_rows1->hole18;

$scorecard_full_rows2 = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_scores_rounds
INNER JOIN {$wpdb->prefix}jtour_scores ON {$wpdb->prefix}jtour_scores.scores_id = {$wpdb->prefix}jtour_scores_rounds.scores_master_id
WHERE scores_master_id = '".$_GET['scores_id']."' and scores_round_number = '2'");

$total_round2 = $scorecard_full_rows2->hole1+$scorecard_full_rows2->hole2+$scorecard_full_rows2->hole3+$scorecard_full_rows2->hole4+$scorecard_full_rows2->hole5+$scorecard_full_rows2->hole6+$scorecard_full_rows2->hole7+$scorecard_full_rows2->hole8+$scorecard_full_rows2->hole9+$scorecard_full_rows2->hole10+$scorecard_full_rows2->hole11+$scorecard_full_rows2->hole12+$scorecard_full_rows2->hole13+$scorecard_full_rows2->hole14+$scorecard_full_rows2->hole15+$scorecard_full_rows2->hole16+$scorecard_full_rows2->hole17+$scorecard_full_rows2->hole18;

$scorecard_full_rows3 = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_scores_rounds
INNER JOIN {$wpdb->prefix}jtour_scores ON {$wpdb->prefix}jtour_scores.scores_id = {$wpdb->prefix}jtour_scores_rounds.scores_master_id
WHERE scores_master_id = '".$_GET['scores_id']."' and scores_round_number = '3'");

$total_round3 = $scorecard_full_rows3->hole1+$scorecard_full_rows3->hole2+$scorecard_full_rows3->hole3+$scorecard_full_rows3->hole4+$scorecard_full_rows3->hole5+$scorecard_full_rows3->hole6+$scorecard_full_rows3->hole7+$scorecard_full_rows3->hole8+$scorecard_full_rows3->hole9+$scorecard_full_rows3->hole10+$scorecard_full_rows3->hole11+$scorecard_full_rows3->hole12+$scorecard_full_rows3->hole13+$scorecard_full_rows3->hole14+$scorecard_full_rows3->hole15+$scorecard_full_rows3->hole16+$scorecard_full_rows3->hole17+$scorecard_full_rows3->hole18;

$tournament_name = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}jtour_tournament
WHERE tournament_id = '".$scorecard_full_rows1->tournament_master_id."'");

$player_name = $wpdb->get_row("SELECT * FROM  {$wpdb->prefix}jtour_players
WHERE players_id = '".$scorecard_full_rows1->player_master_id."'");

$course_name = $wpdb->get_row("SELECT * FROM  {$wpdb->prefix}jtour_course
WHERE course_id = '".$tournament_name->course_master_id."'");

 $course_round1 = $wpdb->get_row("SELECT * FROM  {$wpdb->prefix}jtour_course_rounds
WHERE course_master_id = '".$tournament_name->course_master_id."' and course_round_number = '1'");

$total_course_round1 =  $course_round1->par_for_hole1+$course_round1->par_for_hole2+$course_round1->par_for_hole3+$course_round1->par_for_hole4+$course_round1->par_for_hole5+$course_round1->par_for_hole6+$course_round1->par_for_hole7+$course_round1->par_for_hole8+$course_round1->par_for_hole9+$course_round1->par_for_hole10+$course_round1->par_for_hole11+$course_round1->par_for_hole12+$course_round1->par_for_hole13+$course_round1->par_for_hole14+$course_round1->par_for_hole15+$course_round1->par_for_hole16+$course_round1->par_for_hole17+$course_round1->par_for_hole18;

 /*$course_round2 = $wpdb->get_row("SELECT * FROM  {$wpdb->prefix}jtour_course_rounds
WHERE course_master_id = '".$tournament_name->course_master_id."' and course_round_number = '2'");

$total_course_round2 =  $course_round2->par_for_hole1+$course_round2->par_for_hole2+$course_round2->par_for_hole3+$course_round2->par_for_hole4+$course_round2->par_for_hole5+$course_round2->par_for_hole6+$course_round2->par_for_hole7+$course_round2->par_for_hole8+$course_round2->par_for_hole9+$course_round2->par_for_hole10+$course_round2->par_for_hole11+$course_round2->par_for_hole12+$course_round2->par_for_hole13+$course_round2->par_for_hole14+$course_round2->par_for_hole15+$course_round2->par_for_hole16+$course_round2->par_for_hole17+$course_round2->par_for_hole18;*/


?>
<div id="maincontent-block">
                												
<div id="system-message-container">
</div>
    <div align="center">
		<h1><?php echo $tournament_name->tournament_name; ?></h1>
		<p><?php echo  date("M d", strtotime($tournament_name->tournament_startdate))." - ".date("M d, Y", strtotime($tournament_name->tournament_enddate)); ?></p>		
	</div>
	<h2><?php echo $player_name->players_first_name." ".$player_name->players_last_name; ?></h2>
	<a href="<?php echo get_site_url(); ?>/tournament-list/profile?player_id=<?php echo $scorecard_full_rows1->player_master_id; ?>">View Profile</a>
		<div id="element-box">
			<div class="m">
		        <table cellspacing="0px" cellpadding="0px" class="adminlist">
					<thead>
						<tr>
							<th colspan="20">Round 1 - <?php echo $tournament_name->tournament_name; ?> - <?php echo $course_name->course_name; ?></th>
						</tr>
						<tr>
							<th align="left" class="title">Hole</th>
														<th>1</th>
														<th>2</th>
														<th>3</th>
														<th>4</th>
														<th>5</th>
														<th>6</th>
														<th>7</th>
														<th>8</th>
														<th>9</th>
														<th>10</th>
														<th>11</th>
														<th>12</th>
														<th>13</th>
														<th>14</th>
														<th>15</th>
														<th>16</th>
														<th>17</th>
														<th>18</th>
														<th align="left" class="title">Total</th>
						</tr>
					</thead>
					<tbody>
                   	<tr valign="top" align="center" class="row0">
							<td align="left" class="title">Par</td>
                              <td class="birdie"><?php echo $course_round1->par_for_hole1;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole2;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole3;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole4;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole5;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole6;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole7;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole8;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole9;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole10;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole11;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole12;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole13;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole14;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole15;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole16;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole17;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole18;  ?></td>
                                <td class="birdie"><?php echo $total_course_round1;  ?></td>
                                
						</tr>
                        																						
						<tr valign="top" align="center" class="row0">
							<td align="left" class="title">Score</td>
                                <td class="birdie"><?php echo $scorecard_full_rows1->hole1;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows1->hole2;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows1->hole3;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows1->hole4;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows1->hole5;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows1->hole6;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows1->hole7;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows1->hole8;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows1->hole9;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows1->hole10;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows1->hole11;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows1->hole12;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows1->hole13;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows1->hole14;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows1->hole15;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows1->hole16;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows1->hole17;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows1->hole18;  ?></td>
                                 <td class="birdie"><?php echo $total_round1;  ?></td>
                                
						</tr>
					</tbody>
				</table>
		     </div>
		 </div>
        
		 <div id="element-box">
			<div class="m">
		        <table cellspacing="0px" cellpadding="0px" class="adminlist">
					<thead>
						<tr>
							<th colspan="20">Round 2 - <?php echo $tournament_name->tournament_name; ?> - <?php echo $course_name->course_name; ?></th>
						</tr>
						<tr>
							<th align="left" class="title">Hole</th>
														<th>1</th>
														<th>2</th>
														<th>3</th>
														<th>4</th>
														<th>5</th>
														<th>6</th>
														<th>7</th>
														<th>8</th>
														<th>9</th>
														<th>10</th>
														<th>11</th>
														<th>12</th>
														<th>13</th>
														<th>14</th>
														<th>15</th>
														<th>16</th>
														<th>17</th>
														<th>18</th>
														<th align="left" class="title">Total</th>
						</tr>
					</thead>
					<tbody>
                    	<tr valign="top" align="center" class="row0">
							<td align="left" class="title">Par</td>
                              <td class="birdie"><?php echo $course_round1->par_for_hole1;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole2;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole3;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole4;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole5;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole6;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole7;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole8;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole9;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole10;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole11;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole12;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole13;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole14;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole15;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole16;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole17;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole18;  ?></td>
                                <td class="birdie"><?php echo $total_course_round1;  ?></td>
                                
						</tr>																						
						<tr valign="top" align="center" class="row0">
							<td align="left" class="title">Score</td>
								<td class="birdie"><?php echo $scorecard_full_rows2->hole1;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows2->hole2;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows2->hole3;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows2->hole4;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows2->hole5;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows2->hole6;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows2->hole7;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows2->hole8;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows2->hole9;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows2->hole10;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows2->hole11;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows2->hole12;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows2->hole13;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows2->hole14;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows2->hole15;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows2->hole16;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows2->hole17;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows2->hole18;  ?></td>
                                <td class="birdie"><?php echo $total_round2;  ?></td>
						</tr>
					</tbody>
				</table>
		     </div>
		 </div>
          <?php if($tournament_name->no_of_rounds == '3'){  ?>
         <div id="element-box">
			<div class="m">
		        <table cellspacing="0px" cellpadding="0px" class="adminlist">
					<thead>
						<tr>
							<th colspan="20">Round 3 - <?php echo $tournament_name->tournament_name; ?> - <?php echo $course_name->course_name; ?></th>
						</tr>
						<tr>
							<th align="left" class="title">Hole</th>
														<th>1</th>
														<th>2</th>
														<th>3</th>
														<th>4</th>
														<th>5</th>
														<th>6</th>
														<th>7</th>
														<th>8</th>
														<th>9</th>
														<th>10</th>
														<th>11</th>
														<th>12</th>
														<th>13</th>
														<th>14</th>
														<th>15</th>
														<th>16</th>
														<th>17</th>
														<th>18</th>
														<th align="left" class="title">Total</th>
						</tr>
					</thead>
					<tbody>
                    	<tr valign="top" align="center" class="row0">
							<td align="left" class="title">Par</td>
                              <td class="birdie"><?php echo $course_round1->par_for_hole1;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole2;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole3;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole4;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole5;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole6;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole7;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole8;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole9;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole10;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole11;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole12;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole13;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole14;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole15;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole16;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole17;  ?></td>
                                <td class="birdie"><?php echo $course_round1->par_for_hole18;  ?></td>
                                <td class="birdie"><?php echo $total_course_round1;  ?></td>
                                
						</tr>																						
						<tr valign="top" align="center" class="row0">
							<td align="left" class="title">Score</td>
								<td class="birdie"><?php echo $scorecard_full_rows3->hole1;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows3->hole2;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows3->hole3;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows3->hole4;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows3->hole5;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows3->hole6;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows3->hole7;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows3->hole8;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows3->hole9;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows3->hole10;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows3->hole11;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows3->hole12;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows3->hole13;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows3->hole14;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows3->hole15;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows3->hole16;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows3->hole17;  ?></td>
                                <td class="birdie"><?php echo $scorecard_full_rows3->hole18;  ?></td>
                                <td class="birdie"><?php echo $total_round3;  ?></td>
						</tr>
					</tbody>
				</table>
		     </div>
		 </div>
         <?php }  ?>  
   </div>
<?php 
}
add_shortcode( 'tournament_scoreboard', 'func_tournament_scoreboard' );

//[player_profile]
function func_player_profile( $atts ){ 
global $wpdb;
$player_name = $wpdb->get_row("SELECT * FROM  {$wpdb->prefix}jtour_players
WHERE players_id = '".$_GET['player_id']."'");

$all_tournaments1 = $wpdb->get_results("SELECT * FROM  {$wpdb->prefix}jtour_scores
						INNER JOIN {$wpdb->prefix}jtour_tournament ON {$wpdb->prefix}jtour_tournament.tournament_id = {$wpdb->prefix}jtour_scores.tournament_master_id WHERE player_master_id = '".$_GET['player_id']."'");
?>
<div id="maincontent-block">
                												
<div id="system-message-container">
</div>
    <h2><?php echo $player_name->players_first_name." ".$player_name->players_last_name; ?></h2>
	<table cellspacing="2" cellpadding="5">
		<tbody>       
		<tr>
            <td><b>City</b></td>
            <td>:</td>
            <td><?php echo $player_name->players_city; ?></td>
        </tr>
        <tr>
            <td><b>State</b></td>
            <td>:</td>
            <td><?php echo $player_name->players_state; ?></td>
        </tr>
	</tbody></table>
    <table style="width:80% !important;margin-bottom:20px" class="adminlist">
					<thead>
						<tr>							
							<th>&nbsp;Earnings&nbsp;</th>
							<th>&nbsp;Events Played&nbsp;</th>
							<th nowrap="nowrap">&nbsp;Cuts Made&nbsp;</th>
						</tr>
					</thead>
					<tbody>
							<tr class="row0">
								
								<td align="center"> $<?php $total_earned = 0; foreach($all_tournaments1 as $all_tournament1){ $total_earned += $all_tournament1->money_earned;} 
								echo $total_earned;
								?></td>
								<td align="center"> <?php echo count($all_tournaments1); ?> </td>
								<td align="center">&nbsp;</td>
							</tr>
		            </tbody>
		            <thead>
						<tr>
							<th colspan="4">&nbsp;</th>
						</tr>
					</thead>
		        </table>
		   
		<div id="element-box">
			<div class="m">
		        <table class="adminlist">
		        	<thead>
		        		<tr>
			            	<th width="1%">&nbsp;Date&nbsp;</th>
			                <th align="left">&nbsp;Tournament</th>
							<th align="left">&nbsp;Course</th>																
							<th>Earnings</th>
						</tr>
					</thead>
					<tbody>					
						<?php
						 foreach($all_tournaments1 as $all_tournament){						
						 $course_name = $wpdb->get_row("SELECT * FROM  {$wpdb->prefix}jtour_course WHERE course_id = '".$all_tournament->course_master_id."'");
						 ?>
                         <tr class="row0">
								<td nowrap="nowrap" align="left">
									<a href="<?php echo get_site_url(); ?>/tournament-list/leaderboard/?tournament_id=<?php echo $all_tournament->tournament_id; ?>">
                                     <?php echo date("M d", strtotime($all_tournament->tournament_startdate))." - ".date("M d, Y", strtotime($all_tournament->tournament_enddate)); ?>
                                    </a>
								</td>
								<td nowrap="nowrap" align="left">
									<a href="<?php echo get_site_url(); ?>/tournament-list/leaderboard/?tournament_id=<?php echo $all_tournament->tournament_id; ?>"><?php echo $all_tournament->tournament_name; ?></a>
								</td>
								<td class="row0"><?php echo $course_name->course_name; ?></td>																							
								<td align="right" class="row0">$<?php echo $all_tournament->money_earned; ?></td>
						</tr>
                        <?php } ?>
						</tbody>
		        </table>
		     </div>
		 </div>
 </div>
<?php
}
add_shortcode( 'player_profile', 'func_player_profile' );

//[money_list_all]
function func_money_list_all( $atts ){ 
global $wpdb;
$player_details = $wpdb->get_results("SELECT * FROM  {$wpdb->prefix}jtour_players");
$inc2=1;
?>
<table width="100%" class="adminlist">
		<thead>
			<tr>
				<th width="10%">Place</th>
                <th width="50%">Player</th>
                <th width="20%">Events</th>
				<th width="20%" style="text-align:right">Total</th>
			</tr>
		</thead>
		<tbody>	
        <?php foreach($player_details as $player_detail){ 
		
		$tournament_players = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jtour_tournament_players 
		INNER JOIN {$wpdb->prefix}jtour_tournament ON {$wpdb->prefix}jtour_tournament.tournament_id = {$wpdb->prefix}jtour_tournament_players.tournament_master_id
		where player_master_id = '".$player_detail->players_id."'
		");
		
		$total_tournaments = count($tournament_players);
		$total_tournament_earnings = 0;
		foreach($tournament_players as $tournament_player){ 
		$total_tournament_earnings += $tournament_player->tournament_purse;
		}
		?>	
				<tr class="row0">
        			<td width="10%" align="center"><?php echo $inc2; ?></td>
        			<td align="left">
        				<a href="/statistics/2013-money-list/profile/30"><?php echo $player_detail->players_first_name." ".$player_detail->players_last_name; ?></a>
                    </td>
        			<td align="center"><?php echo $total_tournaments; ?></td>
        			<td align="right">$<?php echo $total_tournament_earnings; ?> </td>
    			</tr>
        <?php $inc2++; } ?>
		</tbody>
	</table>
<?php
}
add_shortcode( 'money_list_all', 'func_money_list_all' );

//[four_tornament_money_list]
function func_four_tornament_money_list( $atts ){ 
global $wpdb;
$player_details = $wpdb->get_results("SELECT * FROM  {$wpdb->prefix}jtour_players");
$inc2=1;
?>
<table width="100%" class="adminlist">
		<thead>
			<tr>
				<th width="10%">Place</th>
                <th width="50%">Player</th>
                <th width="20%">Events</th>
				<th width="20%" style="text-align:right">Total</th>
			</tr>
		</thead>
		<tbody>	
        <?php foreach($player_details as $player_detail){ 
		
		$tournament_players = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jtour_tournament_players 
		INNER JOIN {$wpdb->prefix}jtour_tournament ON {$wpdb->prefix}jtour_tournament.tournament_id = {$wpdb->prefix}jtour_tournament_players.tournament_master_id
		where player_master_id = '".$player_detail->players_id." limit 4'
		");
		
		$total_tournaments = count($tournament_players);
		$total_tournament_earnings = 0;
		foreach($tournament_players as $tournament_player){ 
		$total_tournament_earnings += $tournament_player->tournament_purse;
		}
		?>	
				<tr class="row0">
        			<td width="10%" align="center"><?php echo $inc2; ?></td>
        			<td align="left">
        				<a href="<?php echo get_site_url(); ?>/tournament-list/profile?player_id=<?php echo $player_detail->players_id; ?>"><?php echo $player_detail->players_first_name." ".$player_detail->players_last_name; ?></a>
                    </td>
        			<td align="center"><?php echo $total_tournaments; ?></td>
        			<td align="right">$<?php echo $total_tournament_earnings; ?> </td>
    			</tr>
        <?php $inc2++; } ?>
		</tbody>
	</table>
<?php
}
add_shortcode( 'four_tornament_money_list', 'func_four_tornament_money_list' );
?>