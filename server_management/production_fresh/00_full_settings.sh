# entry point for preparing production server

export LC_ALL=en_US.UTF-8
export LANG=en_US.UTF-8
export LANGUAGE=en_US.UTF-8

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

echo "\n${COLOR_CYAN}Step 1 - SERVER INITIALIZATION${NO_COLOR}"
sh 01_init_server.sh
echo "\n${COLOR_GREEN}Step 1 - Completed!${NO_COLOR}"

echo "\n${COLOR_CYAN}Step 2 - PROJECT INITIALIZATION${NO_COLOR}"
sh 02_init_project.sh
echo "\n${COLOR_GREEN}Step 2 - Completed!${NO_COLOR}"

echo "\n${COLOR_CYAN}Step 3 - PROJECT REFRESHING${NO_COLOR}"
sh 03_refresh_project.sh
echo "\n${COLOR_GREEN}Step 3 - Completed!${NO_COLOR}"

# display success
echo "\n${COLOR_GREEN}Success ! Project initialized!${NO_COLOR}"

# unset created environment variables after all process completed
unset PROJECT_FOLDER
unset COLOR_RED
unset COLOR_GREEN
unset COLOR_YELLOW
unset COLOR_CYAN
unset NO_COLOR
