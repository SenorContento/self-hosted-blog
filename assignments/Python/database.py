import sys
import interpreter;

import sqlite3
from sqlite3 import Error

def setupImportApplication(env):
    """ Import This Application's Functions From Other Files
        :param env: Environment Variables Dictionary
    """
    # This isn't necessary, probably because Interpreter was the caller for this file
    #interpreter_folder = env['DOCUMENT_ROOT'] + "/cgi/python/"
    #sys.path.insert(0, interpreter_folder)

    #library_folder = env['DOCUMENT_ROOT'] + "/path-to-library/"
    #sys.path.insert(0, library_folder)
    None

def init(env, start_response):
    """ Function that Gets Called by Interpreter
        :param env: Environment Variables Dictionary
        :param start_response: Function to Start Response to Browser
        :return: Bytes Response for UWSGI to Return to Browser
    """
    setupImportApplication(env);

    return interpreter.printError404(env, start_response);

def connect(database):
    """ create a database connection to the SQLite database
    :param database: database file
    :return: Connection object or None
    """
    try:
        conn = sqlite3.connect(db_file)
        return conn
    except Error as e:
        print(e)

    return None