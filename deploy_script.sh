#!/bin/bash
www_path=/alidata/www
deploy_path=${www_path}/${JOB_BASE_NAME};
app_path=${deploy_path}/app
app_path_tmp=${deploy_path}/app_tmp
tmp_path=${deploy_path}/tmp
param="--username fuchao --password fuchao123 --no-auth-cache"
svn_trunk_path=${SVN_URL}
svn_main_path=${svn_trunk_path%/trunk}
disk_trunk_path=${deploy_path}/trunk
disk_tag_path=${deploy_path}/${SVN_TAG}

config_real=real
config_test=test
function config()
{
        if [[ $CONFIG_FILE == ${config_real}* ]]
        then
            cp -arf ${1}/common/config/database_${config_real}.php ${1}/common/config/database.php
            cp -arf ${1}/backend/protected/config/database_${config_real}.php ${1}/backend/protected/config/database.php
            cp -arf ${1}/common/config/params_${config_real}.php ${1}/common/config/params.php
        elif [[ $CONFIG_FILE == $config_test* ]]   
        then
            cp -arf ${1}/common/config/database_${config_test}.php ${1}/common/config/database.php
            cp -arf ${1}/backend/protected/config/database_${config_test}.php ${1}/backend/protected/config/database.php
            cp -arf ${1}/common/config/params_${config_test}.php ${1}/common/config/params.php
        fi
}

if [ ! -d ${deploy_path} ];then
    mkdir -p ${deploy_path}
    mkdir -p ${app_path}
    svn co ${svn_main_path} ${deploy_path} ${param}
    cp -arf ${disk_trunk_path}/* ${app_path}
    config ${app_path}
else
    svn revert -R ${disk_tag_path} ${param}
    svn up ${disk_tag_path} ${param}
    mkdir -p ${tmp_path}
    cp -arf ${disk_tag_path}/* ${tmp_path}
    config ${tmp_path}
    mv ${app_path} ${app_path_tmp}
    mv ${tmp_path} ${app_path}
    rm -rf ${app_path_tmp}
fi
mkdir -p ${app_path}/backend/protected/runtime
chmod 777 -R ${app_path}/backend/protected/runtime
mkdir -p ${app_path}/backend/assets
chmod 777 -R ${app_path}/backend/assets
mkdir -p ${app_path}/protected/runtime
chmod 777 -R ${app_path}/protected/runtime
mkdir -p ${app_path}/common/data/cache
chmod 777 -R ${app_path}/common/data/cache
mkdir -p ${app_path}/assets
chmod 777 -R ${app_path}/assets

echo BRANCH_NAME:${BRANCH_NAME}
echo CHANGE_ID:${CHANGE_ID}
echo CHANGE_URL:${CHANGE_URL}
echo CHANGE_TITLE:${CHANGE_TITLE}
echo CHANGE_AUTHOR:${CHANGE_AUTHOR}
echo CHANGE_AUTHOR_DISPLAY_NAME:${CHANGE_AUTHOR_DISPLAY_NAME}
echo CHANGE_AUTHOR_EMAIL:${CHANGE_AUTHOR_EMAIL}
echo CHANGE_TARGET:${CHANGE_TARGET}
echo BUILD_NUMBER:${BUILD_NUMBER}
echo BUILD_ID:${BUILD_ID}
echo BUILD_DISPLAY_NAME:${BUILD_DISPLAY_NAME}
echo JOB_NAME:${JOB_NAME}
echo JOB_BASE_NAME:${JOB_BASE_NAME}
echo BUILD_TAG:${BUILD_TAG}
echo EXECUTOR_NUMBER:${EXECUTOR_NUMBER}
echo NODE_NAME:${NODE_NAME}
echo NODE_LABELS:${NODE_LABELS}
#echo WORKSPACE:${WORKSPACE}
#echo JENKINS_HOME:${JENKINS_HOME}
#echo JENKINS_URL:${JENKINS_URL}
echo BUILD_URL:${BUILD_URL}
echo JOB_URL:${JOB_URL}
echo SVN_REVISION:${SVN_REVISION}
echo SVN_URL:${SVN_URL}
echo SVN_TAG:${SVN_TAG}
echo CONFIG_FILE:${CONFIG_FILE}