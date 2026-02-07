<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create {username} {password} {--role=superadmin}';
    protected $description = 'Create a new admin account';

    public function handle()
    {
        $admin = Admin::create([
            'username' => $this->argument('username'),
            'password' => $this->argument('password'),
            'role' => $this->option('role'),
        ]);

        $this->info("Admin '{$admin->username}' created successfully with role '{$admin->role}'.");
        return Command::SUCCESS;
    }
}
