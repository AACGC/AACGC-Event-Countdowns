<?php

/*
#######################################
#     AACGC Event Countdowns          #                
#     by M@CH!N3                      #
#     http://www.AACGC.com            #
#######################################
*/

global $tp;	

require_once("../../class2.php");
require_once(HEADERF);

if($pref['ecds_theme'] == "1"){
$themea = "forumheader3";
$themeb = "indent";}
else
{$themea = "";
$themeb = "";}
include_lan(e_PLUGIN."aacgc_eventcountdowns/languages/".e_LANGUAGE.".php");

//-------------------------# Menu Title #--------------------------------------------------------------------+
$title .= $pref['ecds_pagetitle'];
//-----------------------------------------------------------------------------------------------------------+

$historylink = "<a href='".e_PLUGIN."aacgc_eventcountdowns/Event_History.php'><img width='16px' height='16px' src='".e_PLUGIN."aacgc_eventcountdowns/images/history.png' align='right' /></a>";
if(ADMIN){
$adminadd = "<a href='".e_PLUGIN."aacgc_eventcountdowns/admin_events.php'><img width='16px' height='16px' src='".e_PLUGIN."aacgc_eventcountdowns/images/add.png' align='left' /></a>";
}
	
//----------------# gather events #---------------+		
$now = time();

$text .= "<table style='width:100%' class='".$themea."'>";

$text .= "
	<tr>
		<td style='text-align:center;' class='' colspan='2'>
			".$tp -> toHTML($pref['ecds_header'], TRUE)."<br/><br/>
		</td>
	</tr>
	<tr>
		<td style='text-align:center;' class='' colspan='2'>
		".$adminadd." ".$historylink."
		</td>
	</tr>
";

$sql->db_Select("aacgc_eventcountdowns", "*", "ecds_date > ".$now." order by ecds_date asc limit 0,1");
$row = $sql->db_Fetch();
	
	$nexteventid = $row['ecds_id'];
	$nexteventtimestamp = $row['ecds_date'];
	$nextdateyear = "Y";
	$nextdateyearshow = date($nextdateyear, $nexteventtimestamp);
	$nextdatedayhour = "j,H";
	$nextdatedayhourshow = date($nextdatedayhour, $nexteventtimestamp);
	$nextdatemonth = "n";
	$nextdatemonthshow = date($nextdatemonth, $nexteventtimestamp);
	$nextdatemonthfixed = $nextdatemonthshow - 1;
	$nextshowcounter = "".$nextdateyearshow.",".$nextdatemonthfixed.",".$nextdatedayhourshow."";


$text .= '
<script type="text/javascript">
function GetCount$nexteventid(){
	
	dateEnd = new Date('.$nextshowcounter.');
	dateEndNow = new Date();					
	amount = dateEnd.getTime() - dateEndNow.getTime();		
	delete dateEndNow;
	
		days=0;hours=0;mins=0;secs=0;out="";

		amount = Math.floor(amount/1000);//kill the "milliseconds" so just secs

		days=Math.floor(amount/86400);//days
		amount=amount%86400;

		hours=Math.floor(amount/3600);//hours
		amount=amount%3600;

		mins=Math.floor(amount/60);//minutes
		amount=amount%60;

		secs=Math.floor(amount);//seconds

		if(days != 0){out += days +" day"+((days!=1)?"s":"")+", ";}
		if(days != 0 || hours != 0){out += hours +" hour"+((hours!=1)?"s":"")+", ";}
		if(days != 0 || hours != 0 || mins != 0){out += mins +" minute"+((mins!=1)?"s":"")+", ";}
		out += secs +" seconds";
		document.getElementById("currcountbox'.$nexteventid.'").innerHTML=out;
		setTimeout("GetCount$nexteventid()", 1000);
	
}

window.onload=GetCount$nexteventid;

</script>		
';

//----------------# show events and countdowns #-----------------+

if($pref['ecds_countercolor'] == "black"){$color = "#000000";}
if($pref['ecds_countercolor'] == "white"){$color = "#ffffff";}
if($pref['ecds_countercolor'] == "red"){$color = "#ff0000";}
if($pref['ecds_countercolor'] == "yellow"){$color = "#ffff00";}
if($pref['ecds_countercolor'] == "green"){$color = "#00ff00";}
if($pref['ecds_countercolor'] == "blue"){$color = "#0000ff";}

$text .= "
	<tr>
		<td style='text-align:center;' class='".$themea."' colspan='2'>
			<a href='".e_PLUGIN."aacgc_eventcountdowns/Event_Details.php?".$row['ecds_id']."'>".$tp -> toHTML($row['ecds_title'], TRUE)."</a>
		</td>
	</tr>
	<tr>
		<td style='text-align:center;' class='".$themeb."' colspan='2'>
			".date($pref['ecds_dateformat'], $row['ecds_date'])." ".$row['ecds_tzone']."
			<br/>			
			<div id='currcountbox".$nexteventid."' style='width:95%; text-align:center; color:".$color."; font-size:".$pref['ecds_countersize']."px' class='' align='center'></div>
		</td>
	</tr>";
	
if($pref['ecds_showfuturepage'] == "1"){
	
$text .= "<tr>
		<td style='text-align:center;' class='".$themea."' colspan='2'><b>".ECDS_01."</b></td>
	</tr>
";

$sql2 = new db;
$sql2->db_Select("aacgc_eventcountdowns", "*", "ecds_date > ".$now." order by ecds_date asc");
while($row2 = $sql2->db_Fetch()){
	
$text .= "
	<tr>
		<td style='text-align:center; width:50%;' class='".$themeb."'><a href='".e_PLUGIN."aacgc_eventcountdowns/Event_Details.php?".$row2['ecds_id']."'>".$tp -> toHTML($row2['ecds_title'], TRUE)."</a></td>
		<td style='text-align:center; width:50%;' class='".$themeb."'>".date($pref['ecds_dateformat'], $row2['ecds_date'])." ".$row2['ecds_tzone']."</td>
	</tr>
";	
	
}
}

$text .= "</table>";


//----#AACGC Plugin Copyright&reg; - DO NOT REMOVE BELOW THIS LINE! - #-------+
require(e_PLUGIN . 'aacgc_eventcountdowns/plugin.php');
$text .= "<br/><br/><br/><br/><br/><br/><br/>
<a href='http://www.aacgc.com' target='_blank'><font color='808080' size='1'>".$eplug_name." V".$eplug_version."  &reg;</font></a>";
//------------------------------------------------------------------------+
$ns -> tablerender($title, $text);
require_once(FOOTERF);
?>