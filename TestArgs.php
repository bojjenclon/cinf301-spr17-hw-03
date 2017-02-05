<?php

require_once "./ParseArgv.php";

function print_vargs($parsedArgs) {
  foreach ($parsedArgs as $k => $v) {
    $vOut = null;

    if (is_array($v)) {
      $vOut = join(',', $v);
    }
    else if (is_bool($v)) {
      $vOut = $v ? 'true' : 'false';
    }
    else {
      $vOut = $v;
    }

    echo "$k => $vOut\n";
  }
}

$parsed = new ParseArgv($_SERVER['argv']);
//var_dump($parsed ->argv);
print_vargs($parsed ->argv);

// php main.php -v -T 4 -l val1,val2,val3 --names=Austin,Duncan,Eddie --type=gold

