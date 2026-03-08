<?php

use App\Http\Controllers\AgentHeartbeatController;
use Illuminate\Support\Facades\Route;

Route::post('/agent/heartbeat', [AgentHeartbeatController::class, 'store'])->name('api.agent.heartbeat');
