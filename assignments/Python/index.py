from urllib import parse
from ast import literal_eval

def getRequest(env):
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
    query = getRequest(env);
    start_response('200 OK', [('Content-Type','text/html'), ('charset','utf-8')])

    test = ''
    for key in query:
        test = test + "Key: '" + key + "' Value: '" + query[key] + "'<br>";

    return test.encode('utf-8');