#!/bin/sh

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
AQUA='\033[0;36m'
MAGENTA='\033[0;35m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color
printf "${RED}WebDo ... ${NC} Attack that List!\n"

cols=`tput cols`

if [ "$1" = "--task" ] || [ "$1" = "-t" ]
then
    printf "Adding Task: ${NC}" 
    echo $2
	curl -s --data "action=addTask&task=$2&project=$3&priority=$4" https://northridge-studios.com/webdo/webdo_interface.php | w3m -dump -T text/html
    curl -s --data "action=retrieveTaskListForProject&project=$3&previousProject=default" https://northridge-studios.com/webdo/webdo_interface.php | w3m -dump -cols "$cols" -T text/html

fi

if [ "$1" = "--late" ] || [ "$1" = "-l" ] || [ "$1" = "late" ]
then
    printf "showing ${RED}Late${NC} tasks: "
    curl -s --data "action=retrieveLateTasks" https://northridge-studios.com/webdo/webdo_interface.php | w3m -dump -cols "$cols" -T text/html
fi

if [ "$1" = "--setProject" ] || [ "$1" = "-s" ]
then
    printf "showing tasks from : ${RED} %s ${NC}"  $2
    curl -s --data "action=retrieveTaskListForProject&project=$2&previousProject=default" https://northridge-studios.com/webdo/webdo_interface.php | w3m -dump -cols "$cols" -T text/html
fi

if [ "$1" = "--Close" ] || [ "$1" = "-c" ] || [ "$1" = "close" ]
then
	printf "Closing task ${GREEN} %s ${NC}" $2
    curl -s --data "action=closeTaskByNumber&taskID=$2" https://northridge-studios.com/webdo/webdo_interface.php | w3m -dump -cols "$cols" -T text/html
fi

if [ "$1" = "--TEST" ] || [ "$1" = "-99" ]
then
    curl -s --data "action=outputTest" https://northridge-studios.com/webdo/webdo_interface.php | w3m -dump -cols "$cols" -T text/html
fi

if [ "$1" = "--notes" ] || [ "$1" = "-n" ] || [ "$1" = "-N" ]
then
	printf "dumping task information ${GREEN} %s ${NC}\n" $2
    
	returnString=`curl -s --data "action=outputNotes&task=$2" https://northridge-studios.com/webdo/webdo_interface.php `
    echo -ne $returnString
    #printf "%b" $returnString
fi

if [ "$1" = "--find" ] || [ "$1" = "-f" ] || [ "$1" = "-F" ]
then
	printf "searching for string ${GREEN} %s ${NC}\n" $2
    
	returnString=`curl -s --data "action=findTasksByString&searchString=$2" https://northridge-studios.com/webdo/webdo_interface.php `
    echo -ne $returnString
    #printf "%b" $returnString
fi

if [ "$1" = "--weekly" ] || [ "$1" = "-w" ]
then
    printf "dumping weekly report for previous week\n"
    
    curl -s --data "action=outputWeeklyReport" https://northridge-studios.com/webdo/webdo_interface.php | w3m -dump -cols "$cols" -T text/html
fi

if [ "$1" = "--help" ] || [ "$1" = "-h" ] || [ "$1" = "-H" ]
then
	echo "-t --task : \"task header\" project priority : will add a task in the project specified"
	echo "-n --notes : \"weekly header\" : will output the notes of the specified task"
	echo "-f --find : search for string \"searchString\""
	echo "-l --late : show late tasks"
	echo "-s --setProject : project"
	echo "-w --weekly   will output the weekly report for the previous week"
	echo "-c --Close : taskID to close"
fi

echo "WebDo script exiting"
