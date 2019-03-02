import sys
import interpreter;

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

