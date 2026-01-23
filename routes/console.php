<?php

use Illuminate\Support\Facades\Schedule;

// Mantenimiento básico
Schedule::command('model:prune')->daily()->at('01:00');
Schedule::command('queue:prune-batches')->daily()->at('01:30');
Schedule::command('cache:prune-stale-tags')->hourly();

// Tarea de renovación de dominios
Schedule::command('renovacion:cron')->dailyAt('09:00');
