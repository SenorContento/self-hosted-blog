import os
import sys
import subprocess

from urllib import parse
from ast import literal_eval

import interpreter;

def setupImportApplication(env):
    """ Import This Application's Functions From Other Files
        :param env: Environment Variables Dictionary
    """
    # This isn't necessary, probably because Interpreter was the caller for this file
    #interpreter_folder = env['DOCUMENT_ROOT'] + "/cgi/python/"
    #sys.path.insert(0, interpreter_folder)

    # https://stackoverflow.com/a/4383597/6828099
    this_folder = env['DOCUMENT_ROOT'] + "/assignments/Python/"
    sys.path.insert(0, this_folder)

def readFile(page):
    """ Read's File
        :param page: File Path String
        :return: File Content Bytes
    """
    with open(page, "r") as file:
        lines = '';
        for line in file:
            lines = lines + line

    return lines;

def printForm(env, title):
    """ Print's HTML Form for Assignment 9
        :param env: Environment Variables Dictionary
        :param title: Set's HTML Title in Header
        :return: Bytes Response for UWSGI to Return to Browser
    """
    header, footer = interpreter.generatePage(env, title);

    response = header.decode('utf-8') + readFile(env['DOCUMENT_ROOT'] + "/assignments/Python/form.html") + footer.decode('utf-8');
    return response.encode('utf-8');

def getRequest(env):
    """ Interpret's HTML Request Sent By User
        :param env: Environment Variables Dictionary
        :return: Dictionary Response of Request Parameters
    """
    # environ = dict(os.environ.items())
    if(env['REQUEST_METHOD'] == "GET"):
        #return('<h1>GET: "' + env['QUERY_STRING'] + '"</h1>').encode('utf-8');
        return dict(parse.parse_qsl(env['QUERY_STRING']))
    elif(env['REQUEST_METHOD'] == "POST"):
        # http://wsgi.tutorial.codepoint.net/parsing-the-request-post
        # https://www.python.org/dev/peps/pep-0333/#the-server-gateway-side
        try:
            request_body_size = int(env.get('CONTENT_LENGTH', 0))
        except (ValueError):
            request_body_size = 0
        request_body = env['wsgi.input'].read(request_body_size)
        #return('<h1>POST: "' + request_body.decode('utf-8') + '"</h1>').encode('utf-8');
        return dict(parse.parse_qsl(request_body.decode('utf-8')));
    else:
        #return('<h1>The Request "' + env['REQUEST_METHOD'] + '" is not supported!!!</h1>').encode('utf-8');
        error = "{'error':'The Request \"" + env['REQUEST_METHOD'] + "\" is not supported!!!'}"
        return literal_eval(error)

def init(env, start_response):
    """ Function that Gets Called by Interpreter
        :param env: Environment Variables Dictionary
        :param start_response: Function to Start Response to Browser
        :return: Bytes Response for UWSGI to Return to Browser
    """
    setupImportApplication(env); # Set's Up Environment for Custom Libraries

    query = getRequest(env);
    if len(query) == 0:
        start_response('200 OK', [('Content-Type','text/html'), ('charset','utf-8')])
        return printForm(env, "Python - Form");

    #start_response('200 OK', [('Content-Type','text/html'), ('charset','utf-8')])
    #return printRequests(env, query);

    return handleRequest(env, start_response, query);

def printRequests(env, query):
    """ Test Function to Make Sure Requests Are Handled Properly
        :param env: Environment Variables Dictionary
        :param query: Dictionary of Queries
        :return: Bytes Response for UWSGI to Return to Browser
    """
    request = ''
    for key in query:
        request = request + "Key: '" + key + "' Value: '" + query[key] + "'<br>";

    header, footer = interpreter.generatePage(env, "Python - Results");
    format = "<h1>" + request + "</h1>";

    response = header.decode('utf-8') + format + footer.decode('utf-8');
    return response.encode('utf-8');

def handleRequest(env, start_response, query):
    """ Handle Dictionary Request from Query
        :param env: Environment Variables Dictionary
        :param start_response: Function to Start Response to Browser
        :param query: Dictionary of Queries
        :return: Bytes Response for UWSGI to Return to Browser
    """
    try:
        import database;
    except ImportError:
        start_response('418 I\'m a teapot', [('Content-Type','text/html'), ('charset','utf-8')])
        return "ImportError! Cannot import database!".encode('utf-8');

    database_file = env['DOCUMENT_ROOT'] + "/assignments/Python/test-sqlite.db"

    conn = database.connect(database_file)
    with conn:
        None

    start_response('200 I\'m a teapot', [('Content-Type','text/html'), ('charset','utf-8')])
    return conn;