<?php
	include_once "../../database/dbconnect.php";	
	$database = new Database();
	$db = $database->getConnection();
	$mysqli = $db;

	if(isset($_POST['hasTimes']) && isset($_POST['minDays']) && isset($_POST['maxDays']) && isset($_POST['startTime']) && isset($_POST['endTime'])){

		$daysofWeek = array('Monday', 'Tuesday', 'Wednsday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
		$daysAvail = array();
	 	for($i=0; $i<7; $i++){
	 		if(isset($_POST["$daysofWeek[$i]"])){
	 			$daysAvail["$daysofWeek[$i]"] = "1";
	 		}else{
	 			$daysAvail["$daysofWeek[$i]"] = "0";
	 		}
	 	}
	 		 	
		$hasTimes = $_POST['hasTimes'];
		$minDays = $_POST['minDays'];
		$maxDays = $_POST['maxDays'];
		$startTime = $_POST['startTime'];
		$endTime = $_POST['endTime']; 
		
		if(strtotime($startTime) == strtotime('00:00:00')){
			print 'Η αρχική ώρα είναι στις 12 το ΒΡΑΔΥ';
			print '<script> setTimeout(function() { window.location = "index.php?p=TimeslotSettings"}, 2000);</script> <br>';
		}else{
			if($hasTimes == 0){
				$insertTime = "INSERT INTO `Times`(`minDays`, `maxDays`, `startTime`, `endTime`, `onMonday`, `onTuesday`, `onWednsday`, `onThursday`, `onFriday`, `onSaturday`, `onSunday`) VALUES (".$minDays.",".$maxDays.",'".$startTime."','".$endTime."',".$daysAvail["$daysofWeek[0]"].",".$daysAvail["$daysofWeek[1]"].",".$daysAvail["$daysofWeek[2]"].",".$daysAvail["$daysofWeek[3]"].",".$daysAvail["$daysofWeek[4]"].",".$daysAvail["$daysofWeek[5]"].",".$daysAvail["$daysofWeek[6]"].")";
				if($mysqli->query($insertTime) === false){
					print "something went wrong. ".$mysqli->error;
				}else{
					echo "Οι ρυθμίσεις για της διαθέσιμες Ημέρες και Ώρες ενημερώθηκαν επιτυχώς";
				}
			}else{
				$updateTime = "UPDATE `Times` SET `minDays`=".$minDays.",`maxDays`=".$maxDays.",`startTime`='".$startTime."',`endTime`='".$endTime."',`onMonday`=".$daysAvail["$daysofWeek[0]"].",`onTuesday`=".$daysAvail["$daysofWeek[1]"].",`onWednsday`=".$daysAvail["$daysofWeek[2]"].",`onThursday`=".$daysAvail["$daysofWeek[3]"].",`onFriday`=".$daysAvail["$daysofWeek[4]"].",`onSaturday`=".$daysAvail["$daysofWeek[5]"].",`onSunday`=".$daysAvail["$daysofWeek[6]"];
				if($mysqli->query($updateTime) === false){
					echo "something went wrong. ".$mysqli->error;
				}else{
					echo  "Οι ρυθμίσεις για της διαθέσιμες Ημέρες και Ώρες ενημερώθηκαν επιτυχώς";
				}
			}
		}		
	}

?>