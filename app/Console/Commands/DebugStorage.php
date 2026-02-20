<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DebugStorage extends Command
{
    protected $signature = 'debug:storage';
    protected $description = 'Debug storage configuration and permissions';

    public function handle()
    {
        $this->info('=== Storage Debug ===');
        
        // Check storage directories
        $this->info("\n1. Checking directories:");
        $dirs = [
            'storage/app/public' => storage_path('app/public'),
            'public/storage' => public_path('storage'),
        ];
        
        foreach ($dirs as $name => $path) {
            $exists = file_exists($path);
            $writable = is_writable($path);
            $this->line("$name: " . ($exists ? '✓ exists' : '✗ missing') . 
                       ($exists && $writable ? ' ✓ writable' : ($exists ? ' ✗ not writable' : '')));
        }
        
        // Check symlink
        $this->info("\n2. Checking symlink:");
        $link = public_path('storage');
        if (is_link($link)) {
            $target = readlink($link);
            $this->line("public/storage → $target");
        } else {
            $this->error("public/storage is not a symlink!");
        }
        
        // Test write
        $this->info("\n3. Testing write:");
        try {
            $testFile = 'test-' . time() . '.txt';
            Storage::disk('public')->put($testFile, 'test content');
            $this->info("✓ Write successful: $testFile");
            
            // Clean up
            Storage::disk('public')->delete($testFile);
            $this->info("✓ Delete successful");
        } catch (\Exception $e) {
            $this->error("✗ Write failed: " . $e->getMessage());
        }
        
        // Check permissions
        $this->info("\n4. Permissions:");
        $storagePath = storage_path('app/public');
        if (file_exists($storagePath)) {
            $perms = substr(sprintf('%o', fileperms($storagePath)), -4);
            $owner = posix_getpwuid(fileowner($storagePath))['name'] ?? 'unknown';
            $this->line("storage/app/public: $perms (owner: $owner)");
        }
        
        return 0;
    }
}
