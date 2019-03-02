#!/usr/bin/env python3
try:
  import os
except ImportError:
  print("ImportError! Cannot import os!")

try:
  import imp
except ImportError:
  print("ImportError! Cannot import imp!")

try:
  from pathlib import Path
except ImportError:
  print("ImportError! Cannot import Path from pathlib!")

def startup():
    print("Custom Python 3 Web Interpreter Loaded");
startup();

#environment = [];

def printError404(env, start_response):
    #global environment;
    start_response('404 File Not Found', [('Content-Type','text/html'), ('charset','utf-8')])

    # Since I cannot execute the PHP server from here (afaict), I am just returning a basic html message for now
    return b'<h1>404 File Not Found</h1>';

    errorpage = env['DOCUMENT_ROOT'] + "/errors/404/index.php"

    with open(errorpage, "r") as file:
        #return 'test'.encode('utf-8');
        lines = '';
        for line in file:
            lines = lines + line

        return lines.encode('utf-8');

# Came from https://github.com/SenorContento/Rover/blob/f04b7713fda355cc158756bf74529ab076cd91d8/modules.py
def load(filepath, env, start_response):
    if not Path(filepath).is_file():
        return printError404(env, start_response);

    name,extension = os.path.splitext(os.path.split(filepath)[-1])

    if extension.lower() == '.py': # Checks if the extension of the module ends in .py (Scripted Python)
        module = imp.load_source(name, filepath)
    elif extension.lower() == '.pyc': # Checks if the extension of the module ends in .pyc (Compiled Python)
        module = imp.load_compiled(name, filepath)

    # Loads the function init
    init = 'init'
    if hasattr(module, init):
        function = getattr(module, init)(env, start_response)
    else:
        start_response('418 I\'m a teapot', [('Content-Type','text/html'), ('charset','utf-8')])
        response = '<h1>The Python Script "' + env['PATH_INFO'] + '" is missing its init function!!!</h1>';
        return response.encode('utf-8')

    return function

def application(env, start_response):
    #global environment;
    #environment = env;
    #start_response('200 OK', [('Content-Type','text/html'), ('charset','utf-8')])

    #response = b"<h1>Hello World From Python 3!</h1>";
    #response = '<h1>This is an encoded string!!!</h1>'.encode('utf-8') # Could also use ASCII

    # https://uwsgi-docs.readthedocs.io/en/latest/Nginx.html#what-is-the-uwsgi-params-file
    #testEnvironment = env['TESTME'].encode('utf-8')
    path = env['PATH_INFO'].encode('utf-8')
    root = env['DOCUMENT_ROOT'].encode('utf-8')

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

    file = root + path
    return[load(file.decode('utf-8'), env, start_response)]