<?php

use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Database Backup
|--------------------------------------------------------------------------
*/

Schedule::command(
    'backup:database'
)->dailyAt('02:00')->withoutOverlapping();

/*
|--------------------------------------------------------------------------
| Cleanup Old Backups
|--------------------------------------------------------------------------
*/

Schedule::command(
    'backup:cleanup'
)->dailyAt('03:00')->withoutOverlapping();