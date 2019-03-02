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

    #QUERY_STRING - hello=yes
    #REQUEST_METHOD - GET
    #CONTENT_TYPE - ?
    #CONTENT_LENGTH - ?
    #REQUEST_URI - /path-to-python/script.py?hello=yes - Client?
    #PATH_INFO - /path-to-python/script.py?hello=yes - Server?
    #DOCUMENT_ROOT - /usr/local/Cellar/nginx/1.15.8/html
    #SERVER_PROTOCOL - HTTP/2.0
    #REMOTE_ADDR - 127.0.0.1 - Client?
    #REMOTE_PORT - 49450 - Client?
    #SERVER_ADDR - ?
    #SERVER_PORT - 443
    #SERVER_NAME - localhost

    return[path]