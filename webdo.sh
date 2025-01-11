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

if [ "$1" == "--task" ] || [ "$1" == "-t" ]
then
    echo "Adding Task: ${RED}" "$2 ${NC}"
	curl -s --data "action=addTask&task=$2&project=$3&priority=$4" https://182studios.com/webdo/webdo_interface.php | w3m -dump -T text/html
    curl -s --data "action=retrieveTaskListForProject&project=$3&previousProject=default" https://182studios.com/webdo/webdo_interface.php | w3m -dump -cols "$cols" -T text/html

fi

if [ "$1" == "--late" ] || [ "$1" == "-l" ] || [ "$1" == "late" ]
then
    echo "showing ${RED}Late${NC} tasks: "
    curl -s --data "action=retrieveLateTasks" https://182studios.com/webdo/webdo_interface.php | w3m -dump -cols "$cols" -T text/html
fi

if [ "$1" == "--setProject" ] || [ "$1" == "-s" ]
then
    echo "showing tasks from : ${RED}" "$2 ${NC}"
    curl -s --data "action=retrieveTaskListForProject&project=$2&previousProject=default" https://182studios.com/webdo/webdo_interface.php | w3m -dump -cols "$cols" -T text/html
fi

if [ "$1" == "--Close" ] || [ "$1" == "-c" ] || [ "$1" == "close" ]
then
	echo "Closing task ${GREEN}" "$2 ${NC}"
    curl -s --data "action=closeTaskByNumber&taskID=$2" https://182studios.com/webdo/webdo_interface.php | w3m -dump -cols "$cols" -T text/html
fi

if [ "$1" == "--TEST" ] || [ "$1" == "-99" ]
then
    curl -s --data "action=outputTest" https://182studios.com/webdo/webdo_interface.php | open -dump -cols "$cols" -T text/html
fi


if [ "$1" == "--help" ] || [ "$1" == "-h" ]
then
	echo "-t --task : \"task header\" project priority"
	echo "-l --late : show late tasks"
	echo "-s --setProject : project"
	echo "-c --Close : taskID to close"
fi

echo "WebDo script exiting"
