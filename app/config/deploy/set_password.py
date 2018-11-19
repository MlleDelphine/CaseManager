#!/bin/python3

import subprocess 
from subprocess import PIPE
import re
import argparse
import os.path
import getpass

class BadType(Exception):
  def __init__(self):
    pass

class BadFormat(Exception):
  def __init__(self):
    pass


class Request:
  def __init__(self, label):
    if(not isinstance(label, str)):
        raise BadType()
    
    lst = label.split(":")
    if(len(lst) != 2):
        raise BadFormat()
    self.kind = lst[0];           #Kind is expected to be "password" or "user" 
    self.identifier = lst[1]

class Requester:
  def __init__(self, keyfile, password):
    self.keyfile = keyfile
    self.password = password
    self.pattern = re.compile("#!(.*)!#")

  def replace(self, label):
    request = Request(label)
    if(request.kind == "user"):
      getCmd = "getLogin("+request.identifier+")"
    elif(request.kind == "password"):
      getCmd = "getPassword("+request.identifier+")"
    else:
      raise BadFormat()
    p = subprocess.Popen(['keepassx', '-cmd', "open("+self.keyfile+","+self.password+");"+getCmd+";close()"],stdin=PIPE, stdout=PIPE, stderr=PIPE)
    output, err = p.communicate()
    if(p.returncode != 0):
      print(err)
      raise BadFormat
    output = ''.join(output.decode("utf-8").splitlines())
    return output

def parseAndReplace(toParsePath, toWritePath, requester):
  def do_replace(matchObj):
    return requester.replace(matchObj.group(1))

  toParseFile = open(toParsePath, 'r')
  toParseStr = toParseFile.read()
  parsedStr = re.sub(requester.pattern, do_replace, toParseStr)
  toWriteFile = open(toWritePath, 'w')
  toWriteFile.write(parsedStr)


def main():
  parser = argparse.ArgumentParser(description='Call keepassx (command line) to replace password files.')
  parser.add_argument('--dir', type=str, dest='argDir', help='The directory were are the config files.', required=True)
  parser.add_argument('--file', type=str, dest='argFile', help='The file to write data on (we expect a corresponding .dist file.', required=True)
  parser.add_argument('--key', type=str, dest='argKeyFile', help='The keypass file from which to get the key.', required=True)
  #Ni unue atentas ke la dosierujoj bezonataj ekzistas.


  args = parser.parse_args()
  toParsePath = args.argDir+"/"+args.argFile+".dist"
  if(not os.path.isfile(toParsePath)):
    print(toParsePath+" must exist")
    exit(1)
  if(not os.path.isfile(toParsePath)):
    print(toParsePath+" must exist")
    exit(1)
  argpass=getpass.getpass("Please provide the root master password to populate parameters files.\n")
  toWritePath = args.argDir+"/"+args.argFile
  requester = Requester(args.argKeyFile, argpass)
  parseAndReplace(toParsePath, toWritePath, requester)

main()
