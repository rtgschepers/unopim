<?php

use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Schedule::command('unopim:product:index')->dailyAt('00:01');
Schedule::command('unopim:product:index')->dailyAt('12:01');
Schedule::command('unopim:category:index')->dailyAt('00:01');
Schedule::command('unopim:category:index')->dailyAt('12:01');
