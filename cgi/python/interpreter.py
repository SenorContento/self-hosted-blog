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
  import subprocess
except ImportError:
  print("ImportError! Cannot import subprocess!")

try:
  from pathlib import Path
except ImportError:
  print("ImportError! Cannot import Path from pathlib!")

def startup():
    """ Tests to Ensure Interpreter is Loaded By UWSGI
    """
    print("Custom Python 3 Web Interpreter Loaded");
startup();

def init(env, start_response):
    """ Function that Gets Called by Interpreter
        :param env: Environment Variables Dictionary
        :param start_response: Function to Start Response to Browser
        :return: Bytes Response for UWSGI to Return to Browser
    """
    #start_response('404 File Not Found', [('Content-Type','text/html'), ('charset','utf-8')])
    #header, footer = generatePage(env, '404 - File Not Found');
    #return (header.decode('utf-8') + "<p>I am not supposed to be run directly!!!</p>" + footer.decode('utf-8')).encode('utf-8');
    return printError404(env, start_response);

def generatePage(env, title):
    """ Generate's HTML Header and Footer Using PHP
        :param env: Environment Variables Dictionary
        :param title: Set's HTML Title in Header
        :return: Tuple Bytes [header, footer]
    """
    if(env['alex.server.type'] == "development"):
        php_path = "/usr/local/bin/php";
    else:
        php_path = "/usr/bin/php";

    os.environ["alex.server.name"] = env['alex.server.name'];
    os.environ["alex.server.type"] = env['alex.server.type'];

    # I need to add a way to automatically poll all variables and pass the ones beginning with php.alex
    os.environ["alex.github.project"] = env['alex.github.project'];
    os.environ["alex.github.branch"] = env['alex.github.branch'];
    os.environ["alex.server.page.title"] = title;

    header_path = env['DOCUMENT_ROOT'] + "/server_data/header.php";
    proch = subprocess.Popen([php_path, header_path], stdout=subprocess.PIPE)
    header = proch.stdout.read();

    footer_path = env['DOCUMENT_ROOT'] + "/server_data/footer.php";
    procf = subprocess.Popen([php_path, footer_path], stdout=subprocess.PIPE)
    footer = procf.stdout.read();

    return header, footer;

def grabPage(env, page_path):
    """ Generate's HTML from PHP File
        under Web Root or Subdirectories
        :param env: Environment Variables Dictionary
        :param page_path: Page Path to Pass to PHP
        :return: Bytes Response
    """
    if(env['alex.server.type'] == "development"):
        php_path = "/usr/local/bin/php";
    else:
        php_path = "/usr/bin/php";

    # https://stackoverflow.com/a/4514776
    os.environ["alex.server.name"] = env['alex.server.name'];
    os.environ["alex.server.type"] = env['alex.server.type'];

    # I need to add a way to automatically poll all variables and pass the ones beginning with php.alex
    os.environ["alex.github.project"] = env['alex.github.project'];
    os.environ["alex.github.branch"] = env['alex.github.branch'];

    os.environ["PWD"] = env['DOCUMENT_ROOT'];
    #return os.environ["PWD"].encode('utf-8');
    page = env['DOCUMENT_ROOT'] + page_path;
    proc = subprocess.Popen([php_path, page], stdout=subprocess.PIPE)

    return proc.stdout.read();

def printError404(env, start_response):
    """ Prints 404 File Not Found Error
        :param env: Environment Variables Dictionary
        :param start_response: Function to Start Response to Browser
        :return: Bytes Response for UWSGI to Return to Browser
    """
    start_response('404 File Not Found', [('Content-Type','text/html'), ('charset','utf-8')])
    return grabPage(env, "/errors/404/index.php");

# Came from https://github.com/SenorContento/Rover/blob/f04b7713fda355cc158756bf74529ab076cd91d8/modules.py
def load(filepath, env, start_response):
    """ Load's Python Script Requested by User
        :param filepath: Page Path to Requested Python Script
        :param env: Environment Variables Dictionary
        :param start_response: Function to Start Response to Browser
        :return: Bytes Response for UWSGI to Return to Browser
    """
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

        header, footer = generatePage(env, "418 - Missing Init Function");
        response = header.decode('utf-8') + '<h1>The Python Script "' + env['PATH_INFO'] + '" is missing its init function!!!</h1>' + footer.decode('utf-8');
        return response.encode('utf-8')

    return function

def application(env, start_response):
    """ Function that Gets Called by UWSGI (Main Entry Point)
        :param env: Environment Variables Dictionary
        :param start_response: Function to Start Response to Browser
        :return: Bytes Response for UWSGI to Return to Browser
    """
    # https://uwsgi-docs.readthedocs.io/en/latest/Nginx.html#what-is-the-uwsgi-params-file

    # Example Environment - https://localhost/path-to-python/script.py?hello=yes
    # QUERY_STRING - hello=yes
    # REQUEST_METHOD - GET
    # CONTENT_TYPE - ?
    # CONTENT_LENGTH - ?
    # REQUEST_URI - /path-to-python/script.py?hello=yes - Client?
    # PATH_INFO - /path-to-python/script.py?hello=yes - Server?
    # DOCUMENT_ROOT - /usr/local/Cellar/nginx/1.15.8/html
    # SERVER_PROTOCOL - HTTP/2.0
    # REMOTE_ADDR - 127.0.0.1 - Client?
    # REMOTE_PORT - 49450 - Client?
    # SERVER_ADDR - ?
    # SERVER_PORT - 443
    # SERVER_NAME - localhost

    file = env['DOCUMENT_ROOT'] + env['PATH_INFO']
    return[load(file, env, start_response)]