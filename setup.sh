#!/usr/bin/env bash

# create parameters.yml file for Travis test env
config_dir='src/TwoMartens/Bundle/CoreBundle/Tests/Functional/app/config/'
cp ${config_dir}parameters.yml.dist ${config_dir}parameters.yml
