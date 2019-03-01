#!/usr/bin/env python3
import sys

def simple(): # Python 2
    headers = ['content-type: text/html;charset=utf-8']
    body = b'<h1>Hello World!</h1>\n'
    print('\r\n'.join(headers + ['', '']).encode('utf-8'));
    print(body);

def start(): # Python 3
    headers = ['content-type: text/html;charset=utf-8']
    body = b'<h1>Hello, world - Start()!</h1>\n' #this could be compressed, too, with the according information appended to headers
    headers.append('content-length: {}'.format(len(body)))
    sys.stdout.buffer.write('\r\n'.join(headers + ['', '']).encode('utf-8'))
    sys.stdout.buffer.write(body)

def application(env, start_response):
    start_response('200 OK', [('Content-Type','text/html')])
    return [b"UWSCGI Response"]

simple();