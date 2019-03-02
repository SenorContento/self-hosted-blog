def init(env, start_response):
    start_response('200 OK', [('Content-Type','text/html'), ('charset','utf-8')])
    response =  '<h1>I am "' + env['PATH_INFO'] + '"!!!</h1>' # Could also use ASCII

    return response.encode('utf-8');