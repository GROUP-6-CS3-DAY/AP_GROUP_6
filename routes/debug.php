<?php

// Add this to your routes/web.php or create this as a separate debug route file

Route::get('/debug/project/{id}', function($id) {
    $repo = app(\App\Infrastructure\Repositories\EloquentProjectRepository::class);
    
    // Run the debug method
    $debug = $repo->debugProjectRelationships($id);
    
    return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
})->name('debug.project');
