#!/usr/bin/env python3
import sys
headers = [ 'content-type: text/html;charset=utf-8' ]
body = b'<h1>Hello, world!</h1>\n' #this could be compressed, too, with the according information appended to headers
headers.append('content-length: {}'.format(len(body)))
sys.stdout.buffer.write('\r\n'.join(headers + ['', '']).encode('utf-8'))
sys.stdout.buffer.write(body)