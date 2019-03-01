#!/usr/bin/env python3
#import sys

def startup():
    print("Custom Python 3 Web Interpreter Loaded");

def application(env, start_response):
    start_response('200 OK', [('Content-Type','text/html'), ('charset','utf-8')])
    return [b"<h1>Hello World From Python 3!</h1>"]

startup();