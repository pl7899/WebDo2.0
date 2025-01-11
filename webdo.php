<!DOCTYPE html>
<html lang="en">
<!-- $Rev: 751 $ -->
<head>
    <link id="mainstyle" rel="stylesheet" type="text/css" href="b_orig_th.css"> </link>
    <link rel="stylesheet" type="text/css" href="main_format.css"> </link>
	<link rel="stylesheet" href="nice-date-picker.css"></link>
    <title>
        webDo Interface version with rev
    </title>
</head>
<body>
<script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"> </script>
<script src="nice-date-picker.js"></script>
<script src="webdo_shim.js"></script>
<script>
    var input = document;
    var commandLineMode;
    var lastProjectSetActive ="default";
    var twoPartCommand = false;
    var todayDate = new Date();
	fullYear = todayDate.getFullYear();
	currentMonth = todayDate.getMonth()+1;
	currentDateNumber = todayDate.getDate();
    var currentWeek = week(fullYear, todayDate.getMonth()+1, (todayDate.getDate() - todayDate.getDay()));

    input.addEventListener("keyup", function(event)
    {
        event.preventDefault();
        document.getElementById('debug_area').innerHTML = "key value: " + String.fromCharCode(event.keyCode) + " : " + event.keyCode;

		var dummyElement = document.getElementById('genericInput');
		var isCommandLineEnabled = (document.activeElement === dummyElement);
		dummyElement = document.getElementById('notesInput');
		var isNotesUpdateEnabled = (document.activeElement === dummyElement);

        if(!isCommandLineEnabled && !isNotesUpdateEnabled)
        {
	        if(event.keyCode === 49)
	        {
		        // the '!' key handler
		        commandLineMode = "!";
		        setupGenericInput("ID number of task to reOpen: <br>", "");
		        reloadCss();
	        }
	        else if(event.keyCode === 50)
	        {
		        // the '@' key handler, actually '2'
		        commandLineMode = "@";
		        setupGenericInput("update the project for the task: <br>", "");
	        }
	        else if(event.keyCode === 56)
	        {
		        // the '*' key handler, actually the '8'
				dumpAllTasks();
	        }
	        else if(event.keyCode === 190)
	        {
		        // the '.' key handler
		        commandLineMode = ".";
		        setupGenericInput("enter the project to focus on: <br>", "");
	        }
	        else if(event.keyCode === 65)
	        {
		        // the 'a' key handler
		        commandLineMode = "A";
		        displayStringAtGenericInput("Use '<' and '>' to view theme options: <br>");
	        }
	        else if(event.keyCode === 66)
	        {
		        // the 'b' key handler
		        commandLineMode = "B";
		        setupGenericInput("list tasks for single action separated by commas: <br>", "");
	        }
	        else if(event.keyCode === 67)
	        {
		        // the 'c' key handler
		        commandLineMode = "C";
		        setupGenericInput("ID number of task to Close: <br>", "");
	        }
	        else if(event.keyCode === 68)
	        {
		        // the 'd' key handler
		        commandLineMode = "D";
		        setupGenericInput("ID number of task to Delete: <br>", "");
	        }
	        else if(event.keyCode === 69)
	        {
		        // the 'E' key handler
		        commandLineMode = "E";
		        setupGenericInput("Week to Export (0-this week, E-empty): <br>", "");
	        }
	        else if(event.keyCode === 70)
	        {
		        // the 'F' key handler
		        commandLineMode = "F";
		        setupGenericInput("string to Find in existing global tasks <br>currently limited to a single word:", "");
	        }
	        else if(event.keyCode === 71)
	        {
		        // the 'G' key handler
		        commandLineMode = "G";
		        fullDateString = fullYear + "-" + currentMonth + "-" + currentDateNumber;
		        setupGenericInput("update the tarGet date YYYY-MM-DD:", fullDateString);
	        }
	        else if(event.keyCode === 72)
	        {
		        // the 'h' key handler
		        commandLineMode = "H";
		        setupGenericInput("New text for task Header:", document.getElementById('currentlyActiveTaskHeader').innerHTML);
	        }
	        else if(event.keyCode === 74)
	        {
		        // the 'j' key handler
		        commandLineMode = "J";
		        setupGenericInput("Name of project to Delete: <br>", "");
	        }
	        else if(event.keyCode === 76)
	        {
		        // the 'L' key handler
		        // commandLineMode = "L";  // One and done functionality, no need to enter commandLineMode
				lastProjectSetActive = "&LATE";
				handleProjectSelection(lastProjectSetActive);
	        }
	        else if(event.keyCode === 78)
	        {
		        // the 'n' key handler
		        commandLineMode = "N";
		        setupNotesInput("Notes updated below, CTRL+Enter to submit: <br>", "");
	        }
	        else if(event.keyCode === 79)
	        {
		        // the 'o' key handler
		        commandLineMode = "O";
		        setupGenericInput("Set Options:<br>", "");
	        }
	        else if(event.keyCode === 80)
	        {
		        // the 'p' key handler
		        commandLineMode = "P";
		        setupGenericInput("New Project:<br>", "");
	        }
	        else if(event.keyCode === 82)
	        {
		        // the 'r' key handler
	            retrieveProjectList();
	        }
	        else if(event.keyCode === 83)
	        {
		        // the 's' key handler
		        commandLineMode = "S";
		        setupGenericInput("Set Active Project: <br>", "");
	        }
	        else if(event.keyCode === 84)
	        {
		        // the 't' key handler
		        commandLineMode = "T";
		        setupGenericInput("New Task:<br>", "");
	        }
	        else if(event.keyCode === 85)
	        {
		        // the 'u' key handler
		        commandLineMode = "U";
		        setupGenericInput("Task To Update:<br>", "");
	        }
	        else if(event.keyCode === 86)
		    {
		        // the 'v' key handler
		        commandLineMode = "V";
		        setupGenericInput("Project To Veil/unVeil:<br>", "");
			}
	        else if(event.keyCode === 87)
		    {
		        // the 'w' key handler
		        showWeeklyWorkReport(currentWeek);
			}
	        else if(event.keyCode === 89)
	        {
		        // the 'y' key handler
		        commandLineMode = "Y";
		        setupGenericInput("New Task Priority:<br>", "");
	        }
	        else if(event.keyCode === 221)
	        {
		        // the 'y' key handler
		        commandLineMode = "]";
		        setupGenericInput("Set Dependency:<br>", "");
	        }
	    }

	    if(isCommandLineEnabled)
	    {
		    if(event.keyCode == 13)
		    {
			    // return key struck while in command line mode
				if(document.getElementById('genericInput').value != '')
				{
				    if(commandLineMode === "!")
				    {
						openTaskByNumber(lastProjectSetActive);
					}
				    else if(commandLineMode === "@")
				    {
						newProject = document.getElementById('genericInput').value;
						taskToUpdate = document.getElementById('currentlyActiveTask').innerHTML;
						updateTaskProject(newProject, taskToUpdate);
					}
				    else if(commandLineMode === ".")
				    {
						projectToFocusOn = document.getElementById('genericInput').value;
						focusOnProject(projectToFocusOn);
						disableGenericInput();
					}
				    else if(commandLineMode === "]")
				    {
						newDependency = document.getElementById('genericInput').value;
						taskToUpdate = document.getElementById('currentlyActiveTask').innerHTML;
						updateTaskDependency(newDependency, taskToUpdate);
					}
				    else if(commandLineMode === "B")
				    {
						batchTaskList = document.getElementById('genericInput').value;
						twoPartCommand = true;
				        yearString = fullYear + "-";
				        setupGenericInput("update the tarGet date YYYY-MM-DD:", yearString);
						commandLineMode = "BEX";
				    }
				    else if(commandLineMode === "BEX")
				    {
						newDate = document.getElementById('genericInput').value;
						twoPartCommand = false;
						batchTakeAction(batchTaskList, newDate);
				    }

				    else if(commandLineMode === "C")
				    {
						closeTaskByNumber(lastProjectSetActive);
					}
				    else if(commandLineMode === "D")
				    {
						deleteTaskByNumber(lastProjectSetActive);
					}
				    else if(commandLineMode === "E")
				    {
						exportTasksByWeekNumber(document.getElementById('genericInput').value.trim());
					}
				    else if(commandLineMode === "F")
				    {
						handleProjectSelection("%" + document.getElementById('genericInput').value.trim());
					}
				    else if(commandLineMode === "G")
				    {
						newDate = document.getElementById('genericInput').value;
						taskToUpdate = document.getElementById('currentlyActiveTask').innerHTML;
						updateTaskTargetDate(newDate, taskToUpdate);
					}
					else if (commandLineMode === "H")
					{
						newHeader = document.getElementById('genericInput').value;
						taskToUpdate = document.getElementById('currentlyActiveTask').innerHTML;
						updateTaskHeader(newHeader, taskToUpdate);
					}
					else if (commandLineMode === "J")
					{
						deleteProjectByName();
					}
					else if (commandLineMode === "O")
					{
						newOptions = document.getElementById('genericInput').value;
						setOptions(newOptions);
					}
					else if (commandLineMode === "P")
					{
						createNewProject();
					}
					else if (commandLineMode === "S")
					{
						handleProjectSelection(document.getElementById('genericInput').value.trim());
					}
				    else if (commandLineMode === "T")
				    {
						createNewTask(lastProjectSetActive);
					}
				    else if (commandLineMode === "U")
				    {
						retrieveTaskForUpdate(document.getElementById('genericInput').value.trim(), null);
					}
				    else if (commandLineMode === "V")
				    {
						updateProjectHiddenBit(document.getElementById('genericInput').value.trim(), null);
					}
				    else if (commandLineMode === "Y")
				    {
						newPriority = document.getElementById('genericInput').value;
						taskToUpdate = document.getElementById('currentlyActiveTask').innerHTML;
						updateTaskPriority(newPriority, taskToUpdate);
					}
					if(twoPartCommand == true)
					{
						// no action
					}
					else
					{
						// normal activities, no need for a two part action
						document.getElementById('genericInput').value = '';
					    commandLineMode = "";
						disableGenericInput();						
					}
				}
		    }
		    else if(event.keyCode === 27)
		    {
			    // ESC key pressed
			    commandLineMode = "";
				disableGenericInput();
		    }
	    }
	    else if (isNotesUpdateEnabled)
	    {
		    if( (event.ctrlKey) && (event.keyCode == 13))
		    {
				if (commandLineMode === "N")
				{
					newNotes = document.getElementById('notesInput').value;
					taskToUpdate = document.getElementById('currentlyActiveTask').innerHTML;
					updateTaskNotes(newNotes, taskToUpdate);
				}
				document.getElementById('notesInput').value = '';
			    commandLineMode = "";
				disableNotesInput();
				disableGenericInput();
			}
		    else if(event.keyCode === 27)
		    {
			    // ESC key pressed
			    commandLineMode = "";
			    retrieveTaskForUpdate(document.getElementById('currentlyActiveTask').innerHTML, null);
				disableNotesInput();
				disableGenericInput();
		    }		    
	    }
	    else if(commandLineMode === "A")
	    {
			
			if(event.keyCode == 13)
			{
				// enter key pressed
				commandLineMode = "";
				disableGenericInput();
				setThemeDB();
			}
			else if(event.keyCode === 27)
			{
				// ESC key pressed
				commandLineMode = "";
				disableGenericInput();
				changeTheme(0, 0);
				setThemeDB();
			}
			else if(event.keyCode === 188)
			{
				// < key pressed
				changeTheme(-1, null);
			}
			else if(event.keyCode === 190)
			{
				// > key pressed
				changeTheme(1, null);
			}
	    }



    });
</script>
   <div class="row">
        <div class="columnLeft25" id="test_area">
			<div id="calendar-demo">
			  <div id="calendar-demo-wrapper" style="margin-top:24px; margin-left:15px;"></div>
			  <span class="calendar-demo-msg"></span>
			</div>
        </div>
        <div class="columnRight75">
	        <div class="columnLeft50" >
			    <pre class="largeText" style="margin-left:65px;">
                __    ____           ___    ____ 
 _      _____  / /_  / __ \____     |__ \  / __ \
| | /| / / _ \/ __ \/ / / / __ \    __/ / / / / /
| |/ |/ /  __/ /_/ / /_/ / /_/ /   / __/_/ /_/ / 
|__/|__/\___/_.___/_____/\____/   /____(_)____/  
   .........................................
			    </pre>
	        </div>
	        <div class="columnRight50">
				<div id="auto_load_time" class=columnRight style="margin-top:20px;">
					<p>location 1</p>
      			</div>
	        </div>
     	</div>

   <div class="row">
        <div id="section_1" class="columnLeft35" style="margin-top:0px;">
            <p id="project_list_buttons"></p> 
        </div>
        <div class="columnRight65">
				<div class="columnRight50">
			        <p id="section_3" class="regularText">
						[<span style="color:var(--commandlist_highlight)">R</span>] <span style="color:var(--commandlist_highlight)">R</span>efresh project list <br>
						[<span style="color:var(--commandlist_highlight)">P</span>] add <span style="color:var(--commandlist_highlight)">P</span>roject <br>
						[<span style="color:var(--commandlist_highlight)">S</span>] <span style="color:var(--commandlist_highlight)">S</span>et active project <br>
						[<span style="color:var(--commandlist_highlight)">L</span>] show all <span style="color:var(--commandlist_highlight)">L</span>ate tasks <br>
						[<span style="color:var(--commandlist_highlight)">T</span>] add <span style="color:var(--commandlist_highlight)">T</span>ask (#n for priority @string for project) <br>
						[<span style="color:var(--commandlist_highlight)">V</span>] <span style="color:var(--commandlist_highlight)">V</span>eil / unVeil a project (by proj)<br>
						---[<span style="color:var(--commandlist_highlight)">Y</span>] update priorit<span style="color:var(--commandlist_highlight)">Y</span> of active task<br>
						---[<span style="color:var(--commandlist_highlight)">G</span>] update tar<span style="color:var(--commandlist_highlight)">G</span>et date of active task<br>
						---[<span style="color:var(--commandlist_highlight)">H</span>] update <span style="color:var(--commandlist_highlight)">H</span>eading of active task<br>
						---[<span style="color:var(--commandlist_highlight)">N</span>] update <span style="color:var(--commandlist_highlight)">N</span>otes of active task<br>
			        </p>
				</div>
			<div class="columnLeft50">
			        <p id="section_3" class="regularText">
						[<span style="color:var(--commandlist_highlight)">W</span>] show <span style="color:var(--commandlist_highlight)">W</span>eekly work report<br>
						[<span style="color:var(--commandlist_highlight)">D</span>] <span style="color:var(--commandlist_highlight)">D</span>elete task (by ID)<br>
						[<span style="color:var(--commandlist_highlight)">J</span>] delete pro<span style="color:var(--commandlist_highlight)">J</span>ect (by name)<br>
						[<span style="color:var(--commandlist_highlight)">C</span>] <span style="color:var(--commandlist_highlight)">C</span>lose task (by ID)<br>
						[<span style="color:var(--commandlist_highlight)">O</span>] set <span style="color:var(--commandlist_highlight)">O</span>ptions (showClosed)<br>
						[<span style="color:var(--commandlist_highlight)">F</span>] <span style="color:var(--commandlist_highlight)">F</span>ind string in existing tasks<br>
						[<span style="color:var(--commandlist_highlight)">B</span>] list tasks for a <span style="color:var(--commandlist_highlight)">B</span>atch action<br>
						[<span style="color:var(--commandlist_highlight)">@</span>] modify project task assigned <span style="color:var(--commandlist_highlight)">@</span><br>
						[<span style="color:var(--commandlist_highlight)">.</span>] open the project focused page <span style="color:var(--commandlist_highlight)">.</span><br>
			        </p>
			        <p id="section_2" class="regularText"></p>					
			</div>
    </div>
   <div class="row">
        <div id="task_list_display_area" class="columnLeft60"> 
        </div>
        <div id="task_form_display_area" class="columnRight38">
			<p><span style="color:var(--strong_text);">// ----- test of location </span></p> 
			<div id="task_update_header" class="update_header">
				<span>// --- Task to [U]pdate </span>
			</div>
			<div id="task_update_area" class="update_area"> 
				// update area location
			</div>
			<p><span style="color:var(--strong_text);">// --- Error output  </span></p> 
			<div id="error_reporting_area"></div>
			<div id="console_log_output_area"></div>	
			<table id="file_revision_table" style="color:var(--button_dashes);">
				<tr>
					<td>webdo.php</td>
					<td>$Rev: 751 $</td>
				</tr>
				<tr>
					<td>webdo_shim.js</td>
					<td></td>
				</tr>
				<tr>
					<td>webdo_interface.php</td>
					<td></td>
				</tr>
				<tr>
					<td>sublime.css</td>
					<td></td>
				</tr>
			</table>
			<p id="debug_area"> </p>

		</div>   
<div>
	<p id="dataBaseDumpArea"> </p>
</div>
    <script>
        $(document).ready(function() {
	        auto_load_date();
			setInterval(auto_load_date, 23000);
	        getFileRevisionNumber();
   			disableGenericInput();
            retrieveProjectList();
            lastProjectSetActive = "AllTasks";
			handleProjectSelection(lastProjectSetActive);	
			retrieveThemeDB();
        });
		
        function auto_load_date() {
            var d = new Date();
            var minutesString = d.getMinutes();
            minutesString = minutesString<10 ? "0" + minutesString : minutesString;
            var timeString = "<span class=timeFormat>" + d.getHours() + ":" + minutesString + "</span> <br>";
            timeString = timeString + "<span class=dateFormat>" + d.toDateString() + "</span>";
            document.getElementById("auto_load_time").innerHTML = timeString;
        }
        
        var d = new Date();
        currentMonth = d.getMonth() + 1;
        currentYear = d.getFullYear();
                
		new niceDatePicker({
			dom:document.getElementById('calendar-demo-wrapper'),
			onClickDate:function(date){
				if(commandLineMode === "G")
				{
					document.getElementById('genericInput').value = date;
					document.getElementById("genericInput").focus();
				}
				else if(commandLineMode === "BEX")
				{
					document.getElementById('genericInput').value = date;
					document.getElementById("genericInput").focus();
				}
			},
			year:currentYear,
			month:currentMonth,
			mode:'en'
		});
		
		function focusOnProject(projectToFocusOn)
		{
			window.open('../focusview/focusview.php?projectName=' + projectToFocusOn,'_blank');
		}
		
    </script>

</body>
</html>