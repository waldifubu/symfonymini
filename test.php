<?php

require_once __DIR__.'/vendor/autoload.php';

// Works with predefined scheduling definitions
$cron = new Cron\CronExpression('@daily');
$cron->isDue();
echo $cron->getNextRunDate()->format('Y-m-d H:i:s');
echo PHP_EOL;
echo $cron->getPreviousRunDate()->format('Y-m-d H:i:s');
echo PHP_EOL;
// Works with complex expressions
$cron = new Cron\CronExpression('3-59/15 6-12 */15 1 2-5');
echo $cron->getNextRunDate()->format('Y-m-d H:i:s');
echo PHP_EOL;
// Calculate a run date two iterations into the future
$cron = new Cron\CronExpression('@daily');
echo $cron->getNextRunDate(null, 2)->format('Y-m-d H:i:s');
echo PHP_EOL;
// Calculate a run date relative to a specific time
$cron = new Cron\CronExpression('@monthly');
echo $cron->getNextRunDate('2010-01-12 00:00:00')->format('Y-m-d H:i:s');
echo PHP_EOL;