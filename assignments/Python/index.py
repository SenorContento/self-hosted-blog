import os
import sys
import subprocess
import html; # https://docs.python.org/3/library/html.html#html.escape

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
    """ Read File
        :param page: File Path String
        :return: File Content Bytes
    """
    with open(page, "r") as file:
        lines = '';
        for line in file:
            lines = lines + line

    return lines;

def printForm(env, title):
    """ Print HTML Form for Assignment 9
        :param env: Environment Variables Dictionary
        :param title: Set's HTML Title in Header
        :return: Bytes Response for UWSGI to Return to Browser
    """
    header, footer = interpreter.generatePage(env, title);

    source_code_link = '<a class="source-code-link" href="' + env['alex.github.project'] + '/tree/' + env['alex.github.branch'] + env['PATH_INFO'] + '">View Source Code</a><br>';
    response = header.decode('utf-8') + source_code_link + readFile(env['DOCUMENT_ROOT'] + "/assignments/Python/form.html") + footer.decode('utf-8');
    return response.encode('utf-8');

def getRequest(env):
    """ Interpret HTML Request Sent By User
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

def validateQuery(query):
    """ Validate Queries from User
        :param query: Dictionary of Queries
        :return: Tuple of Parsed and Validated Queries or Bytes Response for Error
    """

    complete = True;
    if not "first_name" in query:
        complete = False;
    elif not "last_name" in query:
        complete = False;
    elif not "color" in query:
        complete = False;
    elif not "food" in query:
        complete = False;

    request = ''
    for key in query:
        if key.startswith('language-'):
            request = request + query[key] + ", ";

    if request == '':
        request = "None"
    else:
        request = request[0:-2]

    if complete:
        return (query['first_name'], query['last_name'], query['color'], query['food'], request);
    else:
        #return ("first_name", "last_name", "color", "food", "languages");
        return "Sorry, but you are missing a parameter!!!".encode('utf-8');

def printTable(env, rows):
    """ Print HTML Table for Assignment 9
        :param env: Environment Variables Dictionary
        :param title: Set's HTML Title in Header
        :return: Bytes Response for UWSGI to Return to Browser
    """
    header, footer = interpreter.generatePage(env, 'Python - Results');

    table = '<link rel="stylesheet" href="assignment9.css">'
    table = table + '<table><thead><tr>'

    table = table + "<th>ID</th>"
    table = table + "<th>First Name</th>"
    table = table + "<th>Last Name</th>"
    table = table + "<th>Color</th>"
    table = table + "<th>Food</th>"
    table = table + "<th>Languages</th>"

    table = table + "</thead><tbody>"

    for row in rows:
        id, firstname, lastname, color, food, languages = row;

        table = table + "<tr>"
        table = table + "<td>" + str(id) + "</td>" # ID
        table = table + "<td>" + html.escape(firstname, quote=True) + "</td>" # First Name
        table = table + "<td>" + html.escape(lastname, quote=True) + "</td>" # Last Name
        table = table + "<td>" + html.escape(color, quote=True) + "</td>" # Color
        table = table + "<td>" + html.escape(food, quote=True) + "</td>" # Food
        table = table + "<td>" + html.escape(languages, quote=True) + "</td>" # Languages
        table = table + "</tr>"

    table = table + "</tbody></table>"

    message = "<h1>Limited to Last 10 Entries</h1>"
    response = header.decode('utf-8') + message + table + footer.decode('utf-8');
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

    # TODO: Move Database Outside of Webroot (So Git Doesn't Overwrite It - It is Not Publicly Accessible Regardless)
    # TODO: Add A Mkdir Command
    database_file = env['DOCUMENT_ROOT'] + "/server-data/databases/assignment9.db"

    conn = database.connect(database_file)
    with conn:
        database.createTable(conn)

        queries = validateQuery(query);

        if type(queries) is bytes:
            header, footer = interpreter.generatePage(env, "Python - Invalid Submission");
            format = "<h1>" + queries.decode('utf-8') + "</h1>";

            response = header.decode('utf-8') + format + footer.decode('utf-8');
            start_response('200 OK', [('Content-Type','text/html'), ('charset','utf-8')])
            return response.encode('utf-8');
        else:
            database.insertIntoTable(conn, queries)

        start_response('200 OK', [('Content-Type','text/html'), ('charset','utf-8')])
        return printTable(env, database.readFromTableLimit(conn))