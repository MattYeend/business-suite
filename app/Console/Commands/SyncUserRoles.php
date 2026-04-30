<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:sync-user-roles')]
#[Description('Command description')]
class SyncUserRoles extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();
        $count = 0;

        foreach ($users as $user) {
            if ($user->is_super_admin) {
                $user->syncRoles(['super-admin']);
                $count++;
            } elseif ($user->is_admin) {
                $user->syncRoles(['admin']);
                $count++;
            } elseif ($user->is_user) {
                $user->syncRoles(['user']);
                $count++;
            }
        }

        $this->info("Synced roles for {$count} users.");
        return 0;
    }
}
