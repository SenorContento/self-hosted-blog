#!/usr/bin/env python3
def startup():
    print("Custom Python 3 Web Interpreter Loaded");
startup();

def application(env, start_response):
    start_response('200 OK', [('Content-Type','text/html'), ('charset','utf-8')])

    #response = b"<h1>Hello World From Python 3!</h1>";
    response = '<h1>This is an encoded string!!!</h1>'.encode('utf-8') # Could also use ASCII

    # https://uwsgi-docs.readthedocs.io/en/latest/Nginx.html#what-is-the-uwsgi-params-file
    #testEnvironment = env['TESTME'].encode('utf-8')
    path = env['PATH_INFO'].encode('utf-8')

    return[path]