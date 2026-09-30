<?php

use Illuminate\Support\Facades\Schedule;

// Mantenimiento básico
Schedule::command('model:prune')->daily()->at('01:00');
Schedule::command('queue:prune-batches')->daily()->at('01:30');
Schedule::command('cache:prune-stale-tags')->hourly();

// Sincronización del catálogo de dominios de IONOS con la tabla local
Schedule::command('dominios:sincronizar')->hourly()->withoutOverlapping();

// Tarea de renovación de dominios
Schedule::command('renovacion:cron')->dailyAt('09:00');
