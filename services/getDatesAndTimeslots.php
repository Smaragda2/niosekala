<?php
	$mysql= '';
	if(isset($_SESSION['dbconnect'])){
		$mysqli = $_SESSION['dbconnect'];
	}else{
		include_once "../database/dbconnect.php";
		$database = new Database();
		$mysqli = $database->getConnection();
		$_SESSION['dbconnect'] = $mysqli;
	}
	
	$response = array();
	
	$selectTimesSetup = 'SELECT * FROM Times';
	$results = $mysqli->query($selectTimesSetup);
	$timeSettings = $results->fetch_assoc();
		
	$minDays = $timeSettings['minDays'];
	$maxDays = $timeSettings['maxDays'];
	$startTime = $timeSettings['startTime'];
	$endTime = $timeSettings['endTime'];
	$onMonday = $timeSettings['onMonday'];
	$onTuesday = $timeSettings['onTuesday'];
	$onWednsday = $timeSettings['onWednsday'];
	$onThursday = $timeSettings['onThursday'];
	$onFriday = $timeSettings['onFriday'];
	$onSaturday = $timeSettings['onSaturday'];
	$onSunday = $timeSettings['onSunday'];
	
	$daysofWeek = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
	$onDaysArray = array($onMonday, $onTuesday, $onWednsday, $onThursday, $onFriday, $onSaturday, $onSunday);

	$firstDate = date('d-m-Y', strtotime('now + '.$minDays.'day'));
	$selectHasBooked = 'SELECT count(*) as hasBooked FROM Booked where onDate>='.$firstDate;
	$selectBookedDatesAndTimes = 'SELECT onDate,atTime FROM Booked where onDate>='.$firstDate;
	
	$stmt = $mysqli->prepare($selectHasBooked );
	$stmt->execute();
	$results = $stmt->get_result();

	$stmt1 = $mysqli->prepare($selectBookedDatesAndTimes );
	$stmt1->execute();
	$results1 = $stmt1->get_result();

	$bookedDays = array();
	$hasBook = false;
	
	while ($datesResults = $results->fetch_assoc()){
		$hasBooked = $datesResults['hasBooked'];
		if(strcmp($hasBooked,"0")==0)
			$hasBook = false;
		else if(strcmp($hasBooked,"0")!==0)
			$hasBook = true;
	}
	if($hasBook){
		$bookk=array();
		while ($datesResults = $results1->fetch_assoc()){
			$onDate = date('d-m-Y',strtotime($datesResults['onDate'])); 
			$atTime = $datesResults['atTime'];
			
			if(array_key_exists($onDate,$bookedDays)){
				array_push($bookedDays["$onDate"],$atTime);
			}else{
				$bookedDays["$onDate"]= array();
				array_push($bookedDays["$onDate"],$atTime);
			}
		}
	}	
	
	
	$dates = getDates($minDays,$maxDays);
	if(sizeof($dates)>0){
		$validatedDates = validateDates($dates, $onDaysArray, $daysofWeek);
		$validatedTimes = getTimes($startTime,$endTime,$validatedDates);

		if($hasBook){	
			$response = removeBooked($validatedTimes,$bookedDays);
		}else{
			$response = $validatedTimes;
		}
	}
	echo json_encode($response);
			
	function getTimes($startTime, $endTime, $dates){
		$times = array();
		$timeSplit = explode(':',$startTime);
		$start = $timeSplit[0];
		$endSplit = explode(':',$endTime);
		$end = $endSplit[0];
		
		while($start < $end){
			$newTime = $start.':'.$timeSplit[1];
			array_push($times, $newTime);
			$start++;
			if(strlen((string)$start)==1){
				$start= str_pad($start, 2, '0', STR_PAD_LEFT);
			}
		}
		$lastTime = (((int)$end*60)+(int)$endSplit[1]);
		$latest = (((int)$start*60)+(int)$timeSplit[1]);
		
		if(($lastTime - $latest)>=0){
			$newTime = $start.':'.$timeSplit[1];		
			array_push($times,$newTime);
		}else print ($lastTime - $latest);
		
		$datesTime = setDateTimes($times,$dates);

		return $datesTime;
	}
	
	function getDates($minDays, $maxDays){
		$i = $minDays;
		$dates = array();
		if($minDays < 0 || $maxDays <0)
			return $dates;

		while($i < $maxDays+1){
			$date = date('d-m-Y', strtotime('now + '.$i.'day'));
			array_push($dates, $date);
			$i++;
		}
		return $dates;
	}
	
	function validateDates($dates, $onDaysArray, $daysofWeek){
		if(count($onDaysArray) == 0 ){
			return $dates;
		}
		$datesToDelete = array();
		foreach($dates as $key=>$date){
			$timestamp = strtotime($date);
			$day = date("l", $timestamp);
			$dayKey = array_search($day, $daysofWeek);
			if ($onDaysArray[$dayKey] == "0"){
				array_push($datesToDelete ,$date);
			}
		}
		return array_diff($dates,$datesToDelete);
	}
	
	function setDateTimes($times,$dates){
		$datesAndTimes = array();
		foreach($dates as $k=>$date){
			$datesAndTimes[$date]=$times;
		}
		return $datesAndTimes;
	}
	
	function removeBooked($datesAndTimes,$bookedDates){
		$temp = $datesAndTimes;
		foreach($bookedDates as $key=>$value){
			foreach($value as $k=>$valueTime){
				$splited=explode(':',$valueTime);
				$val = $splited[0].":".$splited[1];

				$searched=array_search($val, $temp[$key]);
				unset($temp[$key][$searched]);
			}
		}
		return $temp;
	}
	
	
?>


