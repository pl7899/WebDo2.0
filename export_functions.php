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

$mainBody = "<!DOCTYPE html>
<html>
<body>
<script src=\"export_graphics.js\"></script>
<style>
#overlay {
  position: absolute;
  display: block;
  width: 768px;
  height: 1024px;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0,0,0,0);
  z-index: 2;
  cursor: pointer;
}

.overlayDiv { 
		-webkit-transform: rotate(90deg); 
		-moz-transform: rotate(90deg); 
		-o-transform: rotate(90deg);
		transform: rotate(90deg);
		font-size:24px;
	}

</style>

<div id=\"canvas-wrap\">
<canvas id=\"pageOneCanvas\" width=\"768\" height=\"1024\"></canvas>
  <div id=\"overlay\">
	  <div id=\"infoLineOne\" class=\"overlayDiv\" style=\"position: absolute; top: 220px; left: 544px; width: 300px;\"></div>
	  <div id=\"infoLineTwo\" class=\"overlayDiv\" style=\"position: absolute; top: 220px; left: 500px; width: 300px;\"></div>

	  <div id=\"mondayLineOne\" class=\"overlayDiv\" style=\"position: absolute; top: 540px; left: 544px; width: 300px;\"></div>
	  <div id=\"mondayLineTwo\" class=\"overlayDiv\" style=\"position: absolute; top: 540px; left: 500px; width: 300px;\"></div>
	  <div id=\"mondayDate\" class=\"overlayDiv\" style=\"position: absolute; top: 655px; left: 682px; width: 100px; font-size: 16px;\"></div>

	  <div id=\"tuesdayLineOne\" class=\"overlayDiv\" style=\"position: absolute; top: 860px; left: 544px; width: 300px;\"></div>
	  <div id=\"tuesdayLineTwo\" class=\"overlayDiv\" style=\"position: absolute; top: 860px; left: 500px; width: 300px;\"></div>
	  <div id=\"tuesdayDate\" class=\"overlayDiv\" style=\"position: absolute; top: 980px; left: 682px; width: 100px; font-size: 16px;\"></div>

	  <div id=\"wednesdayLineOne\" class=\"overlayDiv\" style=\"position: absolute; top: 220px; left: 274px; width: 300px;\"></div>
	  <div id=\"wednesdayLineTwo\" class=\"overlayDiv\" style=\"position: absolute; top: 220px; left: 230px; width: 300px;\"></div>
	  <div id=\"wednesdayDate\" class=\"overlayDiv\" style=\"position: absolute; top: 347px; left: 416px; width: 100px; font-size: 16px;\"></div>

	  <div id=\"thursdayLineOne\" class=\"overlayDiv\" style=\"position: absolute; top: 540px; left: 274px; width: 300px;\"></div>
	  <div id=\"thursdayLineTwo\" class=\"overlayDiv\" style=\"position: absolute; top: 540px; left: 230px; width: 300px;\"></div>
	  <div id=\"thursdayDate\" class=\"overlayDiv\" style=\"position: absolute; top: 655px; left: 416px; width: 100px; font-size: 16px;\"></div>

	  <div id=\"fridayLineOne\" class=\"overlayDiv\" style=\"position: absolute; top: 860px; left: 274px; width: 300px;\"></div>
	  <div id=\"fridayLineTwo\" class=\"overlayDiv\" style=\"position: absolute; top: 860px; left: 230px; width: 300px;\"></div>
	  <div id=\"fridayDate\" class=\"overlayDiv\" style=\"position: absolute; top: 980px; left: 416px; width: 100px; font-size: 16px;\"></div>

	  <div id=\"saturdayLineOne\" class=\"overlayDiv\" style=\"position: absolute; top: 220px; left: 17px; width: 300px;\"></div>
	  <div id=\"saturdayLineTwo\" class=\"overlayDiv\" style=\"position: absolute; top: 220px; left: -27px; width: 300px;\"></div>
	  <div id=\"saturdayDate\" class=\"overlayDiv\" style=\"position: absolute; top: 505px; left: 143px; width: 100px; font-size: 16px;\"></div>

	  <div id=\"sundayLineOne\" class=\"overlayDiv\" style=\"position: absolute; top: 700px; left: 17px; width: 300px;\"></div>
	  <div id=\"sundayLineTwo\" class=\"overlayDiv\" style=\"position: absolute; top: 700px; left: -27px; width: 300px;\"></div>
	  <div id=\"sundayDate\" class=\"overlayDiv\" style=\"position: absolute; top: 980px; left: 143px; width: 100px; font-size: 16px;\"></div>
  </div>
</div>

<br>

<canvas id=\"pageTwoCanvas\" width=\"768\" height=\"1024\">

Your browser does not support the canvas element.

</canvas>

<script>
	var canvas = document.getElementById(\"pageOneCanvas\");
	var ctx = canvas.getContext(\"2d\");
	ctx.font = \"26px Arial\";
	var width = 768;
	var height = 1024;
	var headerTextSize = 30;
	var ninetyDegrees = 1.570796;
	var oneEightyDegrees = 2 * 1.570796;
	var twoSeventyDegrees = 3 * 1.570796;
	var index;

	var topMargin = 65;
	var rightMargin = 15;
	var bottomMargin = 5;
	var leftMargin = 5;
	var widthSpacing = 8;
	var heightSpacing = 8;
	var interiorLineMargin = 8;
	
	var cornerRoundover = 25;
	
	var divider = 100;
	var weekendMultiplier = 28;
	
	var numberOfWeekendLines = 4;
	var numberOfWeekLines = 5;
	var startLinesX = 0;
	var startLinesY = 0;
	var endLinesX = 0;
	var endLinesY = 0;
	var lineSpacing = 44;
		
	var weekendBubbleWidth = ((width - rightMargin - (3 * widthSpacing)) / divider) * weekendMultiplier;
	var weekendBubbleHeight = (height - topMargin - (2 * heightSpacing)) / 2;

	var weekBubbleWidth = ((width - rightMargin - (3 * widthSpacing)) / divider) * ((divider-weekendMultiplier)/2);
	var weekBubbleHeight = (height - topMargin - (3 * heightSpacing)) / 3;

	var weekendLineLength = (weekendBubbleHeight - (3 * interiorLineMargin))/2;
	var weekLineLength = weekBubbleHeight - (2 * interiorLineMargin);

	var saturdayBubbleX = 0 + widthSpacing; 
	var saturdayBubbleY = 0 + topMargin; 
	var wednesdayBubbleX = 0 + (2 * widthSpacing) + weekendBubbleWidth;
	var wednesdayBubbleY = 0 + topMargin;
	var informationBubbleX = 0 + (3 * widthSpacing) + weekendBubbleWidth + weekBubbleWidth;
	var informationBubbleY= 0 + topMargin; 
	var thursdayBubbleX = 0 + (2 * widthSpacing) + weekendBubbleWidth;
	var thursdayBubbleY = 0 + topMargin + heightSpacing + weekBubbleHeight;
	var mondayBubbleX = 0 + (3 * widthSpacing) + weekendBubbleWidth + weekBubbleWidth;
	var mondayBubbleY = 0 + topMargin + heightSpacing + weekBubbleHeight;
	var sundayBubbleX = 0 + widthSpacing; 
	var sundayBubbleY = 0 + topMargin + heightSpacing + weekendBubbleHeight;
	var fridayBubbleX = 0 + (2 * widthSpacing) + weekendBubbleWidth; 
	var fridayBubbleY = 0 + topMargin + (2 * heightSpacing) + (2 * weekBubbleHeight);
	var tuesdayBubbleX = 0 + (3 * widthSpacing) + weekendBubbleWidth + weekBubbleWidth;;
	var tuesdayBubbleY = 0 + topMargin + (2 * heightSpacing) + (2 * weekBubbleHeight);
	
	var weekdayTextOffsetX = weekBubbleWidth - headerTextSize;
	var weekdayTextOffsetY = heightSpacing;
	var weekendTextOffsetX = weekendBubbleWidth - headerTextSize;
	var weekendTextOffsetY = heightSpacing;
	
	ctx.fillStyle = \"#FFFFFF\";
	ctx.moveTo(0, 0);
	ctx.roundRect(saturdayBubbleX,		saturdayBubbleY, 	weekendBubbleWidth, weekendBubbleHeight, {upperLeft:cornerRoundover, upperRight:cornerRoundover, lowerLeft:cornerRoundover, lowerRight:cornerRoundover}, true, true);
	ctx.roundRect(sundayBubbleX,   		sundayBubbleY,   	weekendBubbleWidth, weekendBubbleHeight, {upperLeft:cornerRoundover, upperRight:cornerRoundover, lowerLeft:cornerRoundover, lowerRight:cornerRoundover}, true, true);
	ctx.roundRect(mondayBubbleX,   		mondayBubbleY,   	weekBubbleWidth,    weekBubbleHeight, {upperLeft:cornerRoundover, upperRight:cornerRoundover, lowerLeft:cornerRoundover, lowerRight:cornerRoundover}, true, true);
	ctx.roundRect(tuesdayBubbleX, 		tuesdayBubbleY, 	weekBubbleWidth, 	weekBubbleHeight, {upperLeft:cornerRoundover, upperRight:cornerRoundover, lowerLeft:cornerRoundover, lowerRight:cornerRoundover}, true, true);
	ctx.roundRect(wednesdayBubbleX, 	wednesdayBubbleY, 	weekBubbleWidth, 	weekBubbleHeight, {upperLeft:cornerRoundover, upperRight:cornerRoundover, lowerLeft:cornerRoundover, lowerRight:cornerRoundover}, true, true);
	ctx.roundRect(thursdayBubbleX, 		thursdayBubbleY, 	weekBubbleWidth, 	weekBubbleHeight, {upperLeft:cornerRoundover, upperRight:cornerRoundover, lowerLeft:cornerRoundover, lowerRight:cornerRoundover}, true, true);
	ctx.roundRect(fridayBubbleX, 		fridayBubbleY, 		weekBubbleWidth, 	weekBubbleHeight, {upperLeft:cornerRoundover, upperRight:cornerRoundover, lowerLeft:cornerRoundover, lowerRight:cornerRoundover}, true, true);
	ctx.roundRect(informationBubbleX, 	informationBubbleY, weekBubbleWidth, 	weekBubbleHeight, {upperLeft:cornerRoundover, upperRight:cornerRoundover, lowerLeft:cornerRoundover, lowerRight:cornerRoundover}, true, true);
	
	ctx.fillStyle = \"#000000\";
	ctx.save();
	ctx.translate((mondayBubbleX + weekdayTextOffsetX), (mondayBubbleY + weekdayTextOffsetY));
	ctx.rotate(ninetyDegrees);
	ctx.fillText(\"Monday\", 0, 0);
	ctx.restore();	
	
	ctx.save();
	ctx.translate((tuesdayBubbleX + weekdayTextOffsetX), (tuesdayBubbleY + weekdayTextOffsetY));
	ctx.rotate(ninetyDegrees);
	ctx.fillText(\"Tuesday\", 0, 0);
	ctx.restore();	

	ctx.save();
	ctx.translate((wednesdayBubbleX + weekdayTextOffsetX), (wednesdayBubbleY + weekdayTextOffsetY));
	ctx.rotate(ninetyDegrees);
	ctx.fillText(\"Wednesday\", 0, 0);
	ctx.restore();	

	ctx.save();
	ctx.translate((thursdayBubbleX + weekdayTextOffsetX), (thursdayBubbleY + weekdayTextOffsetY));
	ctx.rotate(ninetyDegrees);
	ctx.fillText(\"Thursday\", 0, 0);
	ctx.restore();	

	ctx.save();
	ctx.translate((fridayBubbleX + weekdayTextOffsetX), (fridayBubbleY + weekdayTextOffsetY));
	ctx.rotate(ninetyDegrees);
	ctx.fillText(\"Friday\", 0, 0);
	ctx.restore();	

	ctx.save();
	ctx.translate((informationBubbleX + weekdayTextOffsetX), (informationBubbleY + weekdayTextOffsetY));
	ctx.rotate(ninetyDegrees);
	ctx.fillText(\"InfoBox\", 0, 0);
	ctx.restore();	

	ctx.save();
	ctx.translate((saturdayBubbleX + weekendTextOffsetX), (saturdayBubbleY + weekendTextOffsetY));
	ctx.rotate(ninetyDegrees);
	ctx.fillText(\"Saturday\", 0, 0);
	ctx.restore();	

	ctx.save();
	ctx.translate((sundayBubbleX + weekendTextOffsetX), (sundayBubbleY + weekendTextOffsetY));
	ctx.rotate(ninetyDegrees);
	ctx.fillText(\"Sunday\", 0, 0);
	ctx.restore();	
	
	// infoBox lines
	startLinesX = informationBubbleX + interiorLineMargin;
	startLinesY = informationBubbleY + weekdayTextOffsetY;
	endLinesX = startLinesX;
	endLinesY = startLinesY + weekLineLength;
	for (index = 0; index < numberOfWeekLines; index++) 
	{
		ctx.moveTo(startLinesX, startLinesY);
		ctx.lineTo(endLinesX, endLinesY);
		ctx.stroke();
		startLinesX = startLinesX + lineSpacing;
		endLinesX = endLinesX + lineSpacing;
	}
	
	// Monday lines
	startLinesX = mondayBubbleX + interiorLineMargin;
	startLinesY = mondayBubbleY + weekdayTextOffsetY;
	endLinesX = startLinesX;
	endLinesY = startLinesY + weekLineLength;
	for (index = 0; index < numberOfWeekLines; index++) 
	{
		ctx.moveTo(startLinesX, startLinesY);
		ctx.lineTo(endLinesX, endLinesY);
		ctx.stroke();
		startLinesX = startLinesX + lineSpacing;
		endLinesX = endLinesX + lineSpacing;
	}

	// Tuesday lines
	startLinesX = tuesdayBubbleX + interiorLineMargin;
	startLinesY = tuesdayBubbleY + weekdayTextOffsetY;
	endLinesX = startLinesX;
	endLinesY = startLinesY + weekLineLength;
	for (index = 0; index < numberOfWeekLines; index++) 
	{
		ctx.moveTo(startLinesX, startLinesY);
		ctx.lineTo(endLinesX, endLinesY);
		ctx.stroke();
		startLinesX = startLinesX + lineSpacing;
		endLinesX = endLinesX + lineSpacing;
	}

	// Wednesday lines
	startLinesX = wednesdayBubbleX + interiorLineMargin;
	startLinesY = wednesdayBubbleY + weekdayTextOffsetY;
	endLinesX = startLinesX;
	endLinesY = startLinesY + weekLineLength;
	for (index = 0; index < numberOfWeekLines; index++) 
	{
		ctx.moveTo(startLinesX, startLinesY);
		ctx.lineTo(endLinesX, endLinesY);
		ctx.stroke();
		startLinesX = startLinesX + lineSpacing;
		endLinesX = endLinesX + lineSpacing;
	}

	// Thursday lines
	startLinesX = thursdayBubbleX + interiorLineMargin;
	startLinesY = thursdayBubbleY + weekdayTextOffsetY;
	endLinesX = startLinesX;
	endLinesY = startLinesY + weekLineLength;
	for (index = 0; index < numberOfWeekLines; index++) 
	{
		ctx.moveTo(startLinesX, startLinesY);
		ctx.lineTo(endLinesX, endLinesY);
		ctx.stroke();
		startLinesX = startLinesX + lineSpacing;
		endLinesX = endLinesX + lineSpacing;
	}

	// Friday lines
	startLinesX = fridayBubbleX + interiorLineMargin;
	startLinesY = fridayBubbleY + weekdayTextOffsetY;
	endLinesX = startLinesX;
	endLinesY = startLinesY + weekLineLength;
	for (index = 0; index < numberOfWeekLines; index++) 
	{
		ctx.moveTo(startLinesX, startLinesY);
		ctx.lineTo(endLinesX, endLinesY);
		ctx.stroke();
		startLinesX = startLinesX + lineSpacing;
		endLinesX = endLinesX + lineSpacing;
	}

	// Saturday lines
	startLinesX = saturdayBubbleX + interiorLineMargin;
	startLinesY = saturdayBubbleY + interiorLineMargin;
	endLinesX = startLinesX;
	endLinesY = startLinesY + weekendLineLength;
	for (index = 0; index < numberOfWeekendLines; index++) 
	{
		ctx.moveTo(startLinesX, startLinesY);
		ctx.lineTo(endLinesX, endLinesY);
		ctx.stroke();
		
		startLinesY = endLinesY + interiorLineMargin;
		endLinesY = startLinesY + weekendLineLength;
		ctx.moveTo(startLinesX, startLinesY);
		ctx.lineTo(endLinesX, endLinesY);
		ctx.stroke();
		
		startLinesX = startLinesX + lineSpacing;
		endLinesX = endLinesX + lineSpacing;
		startLinesY = saturdayBubbleY + weekendTextOffsetY;
		endLinesY = startLinesY + weekendLineLength;
	}

	// Sunday lines
	startLinesX = sundayBubbleX + interiorLineMargin;
	startLinesY = sundayBubbleY + interiorLineMargin;
	endLinesX = startLinesX;
	endLinesY = startLinesY + weekendLineLength;
	for (index = 0; index < numberOfWeekendLines; index++) 
	{
		ctx.moveTo(startLinesX, startLinesY);
		ctx.lineTo(endLinesX, endLinesY);
		ctx.stroke();
		
		startLinesY = endLinesY + interiorLineMargin;
		endLinesY = startLinesY + weekendLineLength;
		ctx.moveTo(startLinesX, startLinesY);
		ctx.lineTo(endLinesX, endLinesY);
		ctx.stroke();
		
		startLinesX = startLinesX + lineSpacing;
		endLinesX = endLinesX + lineSpacing;
		startLinesY = sundayBubbleY + weekendTextOffsetY;
		endLinesY = startLinesY + weekendLineLength;
	}

	var canvas = document.getElementById(\"pageTwoCanvas\");
	var ctx = canvas.getContext(\"2d\");

	ctx.font = \"24px Arial\";
	lineSpacing = 35;

	ctx.save();
	ctx.translate(40, 270);
	ctx.rotate(twoSeventyDegrees);
	ctx.fillText(\"Hempfield High\", 0, 0 * lineSpacing);
	ctx.fillText(\"(717) 898-5500\", 0, 1 * lineSpacing);
	ctx.fillText(\"\", 0, 2 * lineSpacing);
	ctx.fillText(\"Beittle & Becker\", 0, 3 * lineSpacing);
	ctx.fillText(\"(717) 299-8933\", 0, 4 * lineSpacing);
	ctx.fillText(\"\", 0, 5 * lineSpacing);
	ctx.fillText(\"Dr Aaron Miller\", 0, 6 * lineSpacing);
	ctx.fillText(\"(717) 569-4597\", 0, 7 * lineSpacing);
	ctx.fillText(\"\", 0, 8 * lineSpacing);
	ctx.fillText(\"Lititz Orth\", 0, 9 * lineSpacing);
	ctx.fillText(\"(717) 626-0600\", 0, 10 * lineSpacing);
	ctx.fillText(\"\", 0, 11 * lineSpacing);
	ctx.fillText(\"RHCooper Garage\", 0, 12 * lineSpacing);
	ctx.fillText(\"(717) 898-8306\", 0, 13 * lineSpacing);
	ctx.restore();	

	ctx.save();
	ctx.translate(40, 510);
	ctx.rotate(twoSeventyDegrees);
	ctx.fillText(\"Salad\", 0, 0 * lineSpacing);
	ctx.fillText(\"Mashed Potatoes\", 0, 1 * lineSpacing);
	ctx.fillText(\"Mac & Cheese\", 0, 2 * lineSpacing);
	ctx.fillText(\"Red Potatoes\", 0, 3 * lineSpacing);
	ctx.fillText(\"Rice\", 0, 4 * lineSpacing);
	ctx.fillText(\"Apples\", 0, 5 * lineSpacing);
	ctx.fillText(\"Asparagus\", 0, 6 * lineSpacing);
	ctx.fillText(\"\", 0, 7 * lineSpacing);
	ctx.fillText(\"\", 0, 8 * lineSpacing);
	ctx.restore();	

	ctx.save();
	ctx.translate(40, 780);
	ctx.rotate(twoSeventyDegrees);
	ctx.fillText(\"Enchiladas\", 0, 0 * lineSpacing);
	ctx.fillText(\"Meatball Subs\", 0, 1 * lineSpacing);
	ctx.fillText(\"Indian\", 0, 2 * lineSpacing);
	ctx.fillText(\"Tacos\", 0, 3 * lineSpacing);
	ctx.fillText(\"Ham & Mac\", 0, 4 * lineSpacing);
	ctx.fillText(\"Burgers & Dogs\", 0, 5 * lineSpacing);
	ctx.fillText(\"Spaghetti\", 0, 6 * lineSpacing);
	ctx.fillText(\"Baked Potatoes\", 0, 7 * lineSpacing);
	ctx.fillText(\"Quiche\", 0, 8 * lineSpacing);
	ctx.fillText(\"Popcorn Chicken Bowls\", 0, 9 * lineSpacing);
	ctx.fillText(\"Chili\", 0, 10 * lineSpacing);
	ctx.fillText(\"Ribs\", 0, 11 * lineSpacing);
	ctx.fillText(\"Grilled Cheese\", 0, 12 * lineSpacing);
	ctx.fillText(\"BLT\", 0, 13 * lineSpacing);
	ctx.fillText(\"Eggs\", 0, 14 * lineSpacing);
	ctx.fillText(\"Quesadillas\", 0, 15 * lineSpacing);
	ctx.fillText(\"\", 0, 16 * lineSpacing);
	ctx.fillText(\"\", 0, 17 * lineSpacing);
	ctx.restore();	

	ctx.save();
	ctx.translate(40, 1020);
	ctx.rotate(twoSeventyDegrees);
	ctx.fillText(\"Vacuum Up\", 0, 0 * lineSpacing);
	ctx.fillText(\"Vacuum Down\", 0, 1 * lineSpacing);
	ctx.fillText(\"Bathrooms\", 0, 2 * lineSpacing);
	ctx.fillText(\"Basement\", 0, 3 * lineSpacing);
	ctx.fillText(\"Upstairs Trash\", 0, 4 * lineSpacing);
	ctx.fillText(\"Trash to Cans\", 0, 5 * lineSpacing);
	ctx.fillText(\"Pick up Room\", 0, 6 * lineSpacing);
	ctx.fillText(\"Laundry\", 0, 7 * lineSpacing);
	ctx.fillText(\"Bed Clothes\", 0, 8 * lineSpacing);
	ctx.fillText(\"Dust\", 0, 9 * lineSpacing);
	ctx.fillText(\"Empty Fridge\", 0, 10 * lineSpacing);
	ctx.fillText(\"Grocery\", 0, 11 * lineSpacing);
	ctx.fillText(\"Vacuum Stairs\", 0, 12 * lineSpacing);
	ctx.fillText(\"\", 0, 13 * lineSpacing);
	ctx.fillText(\"Mow Front / Back\", 0, 14 * lineSpacing);
	ctx.fillText(\"Weed\", 0, 15 * lineSpacing);
	ctx.fillText(\"Pool\", 0, 16 * lineSpacing);
	ctx.restore();	
</script>";

$dataToReplace = "
	document.getElementById(\'infoLineOne\').innerHTML = \"Task Data for line one IB\"
	document.getElementById(\'infoLineTwo\').innerHTML = \"Task Data for line Two IB\"

	document.getElementById(\'mondayLineOne\').innerHTML = \"Task for line one monday\"
	document.getElementById(\'mondayLineTwo\').innerHTML = \"Task for line Two monday\"

	document.getElementById(\'tuesdayLineOne\').innerHTML = \"Task for line one tuesday\"
	document.getElementById(\'tuesdayLineTwo\').innerHTML = \"Task for line Two tuesday\"

	document.getElementById(\'wednesdayLineOne\').innerHTML = \"Task for line one wednesday\"
	document.getElementById(\'wednesdayLineTwo\').innerHTML = \"Task for line Two wednesday\"

	document.getElementById(\'thursdayLineOne\').innerHTML = \"Task for line one thursday\"
	document.getElementById(\'thursdayLineTwo\').innerHTML = \"Task for line Two thursday\"

	document.getElementById(\'fridayLineOne\').innerHTML = \"Task for line one friday\"
	document.getElementById(\'fridayLineTwo\').innerHTML = \"Task for line Two friday\"

	document.getElementById(\'saturdayLineOne\').innerHTML = \"Task line one saturday\"
	document.getElementById(\'saturdayLineTwo\').innerHTML = \"Task line Two saturday\"

	document.getElementById(\'sundayLineOne\').innerHTML = \"Task line one sunday\"
	document.getElementById(\'sundayLineTwo\').innerHTML = \"Task line Two sunday\" ";

$closingText = "</body> </html>";

	echo "line 457 </br>";
	$inputContents = file_get_contents("template.html");
	echo "line 460 </br>";
	echo "using the new template file <br>";
	file_put_contents("export.html", $inputContents);
	echo "line 463 </br>";

	$myfile = fopen("export.html", "w") or die("Unable to open file!");
	echo "line 466 </br>";

	if($weekValue == "0" || $weekValue == "1" || $weekValue == "t" || $weekValue == "T")
	{
		fwrite($myfile, "<script>");
		$weekdayCharacters = 30;
		$weekendCharacters = 21;
		// if we didn't ask for the current or the next week assume that we want an empty planner, we start with Monday
		if($weekValue == "0")
		{
			$nextDay = date("Y-m-d", strtotime("last monday"));  // didn't work
			echo "setting nextDay in the IF clause: " . $nextDay . "<br>";
		}
		else if($weekValue == "1")
		{
			$nextDay = date("Y-m-d", strtotime("next monday"));  // didn't work
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
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('mondayLineSix').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
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
		if($row = mysqli_fetch_array($rows)) 
		{
			fwrite($myfile, "document.getElementById('tuesdayLineSix').innerHTML = \" " . substr($row['taskDescription'], 0, $weekdayCharacters) . " \" \n");
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
		else
		{
			
		}
		fwrite($myfile, "</script>");
	}
	fwrite($myfile, $closingText);
	fclose($myfile);
}