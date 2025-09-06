<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUserCommand extends Command
{
    protected $signature = 'user:check {email}';
    protected $description = 'Check user status';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User not found with email: {$email}");
            return 1;
        }

        $this->info("User found with email: {$email}");
        $this->info("Role: " . $user->role);
        $this->info("Is Admin: " . ($user->is_admin ? 'Yes' : 'No'));
        $this->info("Is Super Admin: " . ($user->isSuperAdmin() ? 'Yes' : 'No'));
        $this->info("isAdmin() returns: " . ($user->isAdmin() ? 'Yes' : 'No'));
        
        return 0;
    }
}
