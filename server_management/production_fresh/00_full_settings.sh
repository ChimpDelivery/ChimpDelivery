# entry point for preparing production server
# don't forget to check crontab for multiple same entry

# color utility
export COLOR_RED='\033[0;31m'
export COLOR_GREEN='\033[0;32m'
export COLOR_YELLOW='\033[0;33m'
export COLOR_CYAN='\033[46m'
export NO_COLOR='\033[0m'

# save project folder path
export PROJECT_FOLDER="/var/www/html/TalusWebBackend"

# todo: add yes/no input for confirmation
echo "${COLOR_RED}Caution! Do not run this script if the production server is already live."
echo "\n${COLOR_GREEN}Installation starting...${NO_COLOR}"
echo "${COLOR_YELLOW}Target project folder:${NO_COLOR} $PROJECT_FOLDER"

sh 01_init_server.sh
sh 02_init_project.sh
sh 03_refresh_project.sh
