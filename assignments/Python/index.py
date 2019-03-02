import os
import subprocess

from urllib import parse
from ast import literal_eval

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

    header_path = env['DOCUMENT_ROOT'] + "/php_data/header.php";
    proch = subprocess.Popen([php_path, header_path], stdout=subprocess.PIPE)
    header = proch.stdout.read();

    footer_path = env['DOCUMENT_ROOT'] + "/php_data/footer.php";
    procf = subprocess.Popen([php_path, footer_path], stdout=subprocess.PIPE)
    footer = procf.stdout.read();

    return header, footer;

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
    header, footer = generatePage(env, title);

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
    query = getRequest(env);
    if len(query) == 0:
        start_response('200 OK', [('Content-Type','text/html'), ('charset','utf-8')])
        return printForm(env, "Python - Form");

    start_response('200 OK', [('Content-Type','text/html'), ('charset','utf-8')])

    test = ''
    for key in query:
        test = test + "Key: '" + key + "' Value: '" + query[key] + "'<br>";

    header, footer = generatePage(env, "Python - Results");
    format = "<h1>" + test + "</h1>";

    response = header.decode('utf-8') + format + footer.decode('utf-8');
    return response.encode('utf-8');