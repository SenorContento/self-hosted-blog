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
    """ Create a Database Connection to the SQLite Database
    :param database: Database File
    :return: Connection Object or Error as String
    """
    try:
        conn = sqlite3.connect(database)
        return conn
    except Error as e:
        return e.encode('utf-8');

# http://www.sqlitetutorial.net/sqlite-python/
def createTable(conn):
    """ Create a Table in the SQLite Database
    :param conn: SQLite Connection Object
    """
    sql = ''' CREATE TABLE Assignment9 (id INTEGER PRIMARY KEY AUTOINCREMENT,
                                         firstname TEXT NOT NULL,
                                         lastname TEXT NOT NULL,
                                         color TEXT NOT NULL,
                                         food TEXT NOT NULL,
                                         languages TEXT NOT NULL) '''
    cur = conn.cursor()
    cur.execute(sql)

# Key: 'first_name' Value: 'Alex'
# Key: 'last_name' Value: 'Contento'
# Key: 'color' Value: 'blue'
# Key: 'food' Value: 'hot-food'
# Key: 'language-python' Value: 'python'
# Key: 'language-php' Value: 'php'
# Key: 'language-html' Value: 'html'

def insertIntoTable(conn, parameters):
    """ Inserts Data Into a Table in the SQLite Database
    :param conn: SQLite Connection Object
    :param parameters: Tuple of Entries for Insertion
    :return: Last Row ID
    """
    sql = ''' INSERT INTO Assignment9 (firstname, lastname, color, food, languages)
                                        VALUES(?,?,?,?,?) '''

    cur = conn.cursor()
    cur.execute(sql, parameters)
    return cur.lastrowid

def readFromTable(conn):
    """ Reads Data From a Table in the SQLite Database
    :param conn: SQLite Connection Object
    :return: Array of Table Rows
    """
    sql = ''' SELECT * FROM Assignment9 '''

    cur = conn.cursor()
    cur.execute(sql)

    rows = cur.fetchall()
    return rows
