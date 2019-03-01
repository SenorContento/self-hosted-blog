#!/usr/bin/env python3
#import sys

# Documentation Used to Help Add Python 3 Support
# https://www.paulox.net/2017/04/04/how-to-use-uwsgi-with-python3-6-in-ubuntu/
# https://www.digitalocean.com/community/tutorials/how-to-set-up-uwsgi-and-nginx-to-serve-python-apps-on-ubuntu-14-04
# https://cuasan.wordpress.com/2015/01/25/simple-cgi-web-app-testing-setup-with-python-cgi-and-nginxuwsgi/
# https://stackoverflow.com/questions/22798105/uwsgi-cannot-find-python-module
# http://raspberrywebserver.com/cgiscripting/setting-up-nginx-and-uwsgi-for-cgi-scripting.html
# https://www.paulox.net/2017/04/04/how-to-use-uwsgi-with-python3-6-in-ubuntu/

#

# Written By Alex

def startup():
    print("Custom Python 3 Web Interpreter Loaded");

def application(env, start_response):
    start_response('200 OK', [('Content-Type','text/html'), ('charset','utf-8')])
    return [b"<h1>Hello World From Python 3!</h1>"]

startup();