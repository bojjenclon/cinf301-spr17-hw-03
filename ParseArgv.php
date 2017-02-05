<?php

class ParseArgv {
  // the original arguments passed in from the command line
  private $cli_args = [];
  // all of the arguments joined into a single string with the path removed
  private $joined_args = null;
  // the final parsed version of the arguments
  private $parsed_args = null;

  public function __construct($cli_args) {
    $this->cli_args = $cli_args;
    $this->joined_args = join(' ', array_slice($cli_args, 1));

    $this->parse();
  }

  private function parse() {
    $this->parsed_args = [];

    // regex to find all of the single dash arguments
    preg_match_all("/(?:^|\s)-([a-zA-Z0-9_]+)(?:\s([^-]*\b))?\b/", $this->joined_args, $singleDashArgs, PREG_SET_ORDER);
    // regex to find all of the double dash arguments
    preg_match_all("/(?:^|\s)--([a-zA-Z0-9_]+)=([^-]*)\b/", $this->joined_args, $doubleDashArgs, PREG_SET_ORDER);

    // process single dash arguments
    foreach ($singleDashArgs as $arg) {
      // if the arg array contains more than 2 elements, 
      // the argument has a value following it
      // e.g. "-n 4"
      if (count($arg) > 2) {
        // if a comma exists within the value, it is a list and must be parsed
        if (strpos($arg[2], ',') !== false) {
          $argList = explode(',', $arg[2]);

          $this->parsed_args[$arg[1]] = $argList;
        }
        // otherwise, handle the value as-is
        else {
          $this->parsed_args[$arg[1]] = $arg[2];
        }
      }
      // handle a lone argument being passed
      // e.g. "-l"
      else {
        $this->parsed_args[$arg[1]] = true;
      }
    }

    // process double dash arguments
    // note: assumed format is "--argument=value
    foreach ($doubleDashArgs as $arg) {
      // if a comma exists within the value, it is a list and must be parsed
      if (strpos($arg[2], ',') !== false) {
        $argList = explode(',', $arg[2]);

        $this->parsed_args[$arg[1]] = $argList;
      }
      // otherwise, handle the value as-is
      else {
        $this->parsed_args[$arg[1]] = $arg[2];
      }
    }
  }

  public function __get($name) {
    // allow the parsed arguments to be retrieved directly with "->argv"
    if ($name === 'argv') {
      return $this->parsed_args;
    }

    return null;
  }
}

