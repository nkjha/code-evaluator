<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SubmissionController;

Route::post('/submit', [SubmissionController::class, 'submit']);
Route::get('/submissions/{submission}/status', [SubmissionController::class, 'status']);