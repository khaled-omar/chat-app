<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('certificates:expiry-reminder')->daily()->evenInMaintenanceMode()->onOneServer();
Schedule::command('certificates:expire')->dailyAt('01:00')->evenInMaintenanceMode()->onOneServer();
