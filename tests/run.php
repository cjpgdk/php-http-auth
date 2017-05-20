<?php
// parse command line aruments
$opts      = getopt('v');
$verbose   = isset($opts['v']);
$error     = false;
$start     = microtime(true);
$testFiles = glob(dirname(__FILE__)."/*.phpt", GLOB_NOSORT);
natsort($testFiles);
foreach ($testFiles as $file) {
    $fileBaseName = basename($file);
    ob_start();
    include $file;
    if (!preg_match("~^:NAME:\n(.*?)\n:CONTENT:\n(.*\n)?:EXPECTED:\n(.*)~s", str_replace("\r\n", "\n", ob_get_clean()), $match)) {
        echo "Test error: ({$fileBaseName})".PHP_EOL;
    } elseif ($match[2] !== $match[3]) {
        $error = true;
        echo "Test failed in $fileBaseName ($match[1])".PHP_EOL;
        if ($verbose) {
            echo ":Expected result:".PHP_EOL.$match[3].PHP_EOL.":Actual result:".PHP_EOL.$match[2].PHP_EOL;
        }
    }
}
printf("%.3F s, %d KiB\n", microtime(true) - $start, memory_get_peak_usage() / 1024);
!$error || exit(1);