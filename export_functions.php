<?php
/**
 * Database functions for a MySQL with PHP tutorial
 * 
 * @copyright Eran Galperin
 * @license MIT License
 * @see http://www.binpress.com/tutorial/using-php-with-mysql-the-right-way/17
 */

/**
 * Connect to the database
 * 
 * @return bool false on failure / mysqli MySQLi object instance on success
 */
function exportFreshPlanner($weekValue) 
{
$db = db_connect();

$closingText = "</body> </html>";

	echo "line 457 </br>";
	$inputContents = file_get_contents("template.html");
	echo "line 460 </br>";
	echo "using the new template file <br>";
	$myfile = fopen("export.html", "w") or die("Unable to open file!");
	fwrite($myfile, $inputContents);
	echo "line 463 </br>";

	echo "line 466 </br>";

	if($weekValue == "0" || $weekValue == "1" || $weekValue == "t" || $weekValue == "T")
	{
		fwrite($myfile, "<script>");
		$weekdayCharacters = 34;
		$weekendCharacters = 34;
		// if we didn't ask for the current or the next week assume that we want an empty planner, we start with Monday
		if($weekValue == "0")
		{
			$nextDay = date("Y-m-d", strtotime("last monday"));  // didn't work
			echo "setting nextDay in the IF clause: " . $nextDay . "<br>";
		}
		else if($weekValue == "1")
		{
			$nextDay = date("Y-m-d", strtotime("last monday"));  // didn't work
			echo "setting nextDay in the ELSE IF clause: " . $nextDay . "<br>";
		}
		else if($weekValue == "t" || $weekValue == "T")
		{
		    $today=date("N"); // N = 1(mon) .. 7(sun)
		    $startDateModifier = 1 - $today; // number of days to add to today to get to Monday
		    if(substr($startDateModifier, 0, 1) == "-")
		    {
				$mondayDate = date('M-j', strtotime(' - ' . substr($startDateModifier, 1, 1) . ' days'));
				$nextDay = date('Y/m/d', strtotime(' - ' . substr($startDateModifier, 1, 1) . ' days'));
		    }
		    else
		    {
				$mondayDate = date('M-j', strtotime(' + ' . $startDateModifier . ' days'));
				$nextDay = date('Y/m/d', strtotime(' + ' . $startDateModifier . ' days'));
			}

			echo "setting nextDay in the ELSE IF clause: " . $nextDay . "<br>";
		}
		echo "weekValue : " . $weekValue . ", nextDay : " . $nextDay . "<br>";

	    // MONDAY
		echo "line 504 </br>";
		$findWeeksTasks = "SELECT *  FROM `todoActions` WHERE `isOpen`=\"1\" AND `targetDate`=\"" . $nextDay . "\" ORDER BY `priority` DESC";
		$rows = mysqli_query($db, $findWeeksTasks);
		echo "line 507</br>";
		fwrite($myfile, "document.getElementById('monthYearDisplay').innerHTML = \" " . date("F Y", strtotime($nextDay)) . " \" \n");
		fwrite($myfile, "document.getElementById('weekInformation').innerHTML = \" " . "week : " . date("o", strtotime($nextDay)) . " , " . date("M dS", strtotime($nextDay)) . " - " . date("dS", strtotime($nextDay + 7)) . " \" \n");
		fwrite($myfile, "document.getElementById('mondayDate').innerHTML = \" " . date("M dS", strtotime($nextDay)) . " \" \n");
		echo "line 509 </br>";
			
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('mondayLineOne').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('mondayLineTwo').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('mondayLineThree').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('mondayLineFour').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('mondayLineFive').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 

	    // TUESDAY
	    $nextDay = date("Y-m-d", strtotime($nextDay . "+ 1 days"));
		$findWeeksTasks = "SELECT *  FROM `todoActions` WHERE `isOpen`=\"1\" AND `targetDate`=\"" . $nextDay . "\" ORDER BY `priority` DESC";
		$rows = mysqli_query($db, $findWeeksTasks);
		fwrite($myfile, "document.getElementById('tuesdayDate').innerHTML = \" " . date("M dS", strtotime($nextDay)) . " \" \n");
				
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('tuesdayLineOne').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('tuesdayLineTwo').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('tuesdayLineThree').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('tuesdayLineFour').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 		
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('tuesdayLineFive').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 		

	    // WEDNESDAY
	    $nextDay = date("Y-m-d", strtotime($nextDay."+ 1 days"));
		$findWeeksTasks = "SELECT *  FROM `todoActions` WHERE `isOpen`=\"1\" AND `targetDate`=\"" . $nextDay . "\" ORDER BY `priority` DESC";
		$rows = mysqli_query($db, $findWeeksTasks);
		fwrite($myfile, "document.getElementById('wednesdayDate').innerHTML = \" " . date("M dS", strtotime($nextDay)) . " \" \n");
				
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('wednesdayLineOne').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('wednesdayLineTwo').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('wednesdayLineThree').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('wednesdayLineFour').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('wednesdayLineFive').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 

	    // THURSDAY
	    $nextDay = date("Y-m-d", strtotime($nextDay."+ 1 days"));
		$findWeeksTasks = "SELECT *  FROM `todoActions` WHERE `isOpen`=\"1\" AND `targetDate`=\"" . $nextDay . "\" ORDER BY `priority` DESC";
		$rows = mysqli_query($db, $findWeeksTasks);
		fwrite($myfile, "document.getElementById('thursdayDate').innerHTML = \" " . date("M dS", strtotime($nextDay)) . " \" \n");
				
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('thursdayLineOne').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('thursdayLineTwo').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('thursdayLineThree').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('thursdayLineFour').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('thursdayLineFive').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 
	
	    // FRIDAY
	    $nextDay = date("Y-m-d", strtotime($nextDay."+ 1 days"));
		$findWeeksTasks = "SELECT *  FROM `todoActions` WHERE `isOpen`=\"1\" AND `targetDate`=\"" . $nextDay . "\" ORDER BY `priority` DESC";
		$rows = mysqli_query($db, $findWeeksTasks);
		fwrite($myfile, "document.getElementById('fridayDate').innerHTML = \" " . date("M dS", strtotime($nextDay)) . " \" \n");
				
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('fridayLineOne').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('fridayLineTwo').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('fridayLineThree').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('fridayLineFour').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('fridayLineFive').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
		} 
	
	    // SATURDAY
	    $nextDay = date("Y-m-d", strtotime($nextDay."+ 1 days"));
		$findWeeksTasks = "SELECT *  FROM `todoActions` WHERE `isOpen`=\"1\" AND `targetDate`=\"" . $nextDay . "\" ORDER BY `priority` DESC";
		$rows = mysqli_query($db, $findWeeksTasks);
		fwrite($myfile, "document.getElementById('saturdayDate').innerHTML = \" " . date("M dS", strtotime($nextDay)) . " \" \n");
				
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('saturdayLineOne').innerHTML = \" " . substr($row['taskDescription'], 0, $weekendCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('saturdayLineTwo').innerHTML = \" " . substr($row['taskDescription'], 0, $weekendCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('saturdayLineThree').innerHTML = \" " . substr($row['taskDescription'], 0, $weekendCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('saturdayLineFour').innerHTML = \" " . substr($row['taskDescription'], 0, $weekendCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('saturdayLineFive').innerHTML = \" " . substr($row['taskDescription'], 0, $weekendCharacters) . " \" \n");
		} 

		// SUNDAY
	    $nextDay = date("Y-m-d", strtotime($nextDay."+ 1 days"));
		$findWeeksTasks = "SELECT *  FROM `todoActions` WHERE `isOpen`=\"1\" AND `targetDate`=\"" . $nextDay . "\" ORDER BY `priority` DESC";
		$rows = mysqli_query($db, $findWeeksTasks);
		fwrite($myfile, "document.getElementById('sundayDate').innerHTML = \" " . date("M dS", strtotime($nextDay)) . " \" \n");
				
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('sundayLineOne').innerHTML = \" " . substr($row['taskDescription'], 0, $weekendCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('sundayLineTwo').innerHTML = \" " . substr($row['taskDescription'], 0, $weekendCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('sundayLineThree').innerHTML = \" " . substr($row['taskDescription'], 0, $weekendCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('sundayLineFour').innerHTML = \" " . substr($row['taskDescription'], 0, $weekendCharacters) . " \" \n");
		} 
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('sundayLineFive').innerHTML = \" " . substr($row['taskDescription'], 0, $weekendCharacters) . " \" \n");
		} 
		else
		{
			
		}
		fwrite($myfile, "</script>");
	}
	fwrite($myfile, $closingText);
	fclose($myfile);
}