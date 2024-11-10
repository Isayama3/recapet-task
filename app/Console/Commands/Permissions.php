<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Permissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE table permissions');
        DB::statement('ALTER TABLE permissions AUTO_INCREMENT = 1');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $base_path = app_path() . '/Http/Controllers/Admin';
        foreach (glob($base_path . '/*/Permissions.php') as $file) {
            require_once $file;
        }

        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'admin']);
        $permissions = Permission::pluck('id')->toArray();
        $role->syncPermissions($permissions);
        // $admin = Admin::first();
        // $admin->assignRole('super_admin');

        $role = Role::firstOrCreate(['name' => 'agent', 'guard_name' => 'admin']);
        $permissions = Permission::where(function ($q) {
            $q->where('name', 'admin.wallet-tickets.index')
                ->orWhere('name', 'admin.home.index')
                ->orWhere('name', 'admin.agents.wallet')
                ->orWhere('name', 'admin.wallet-tickets.collect')
                ->orWhere('name', 'admin.wallet-tickets.reject')
                ->orWhere('name', 'admin.wallet-tickets.reject')
                ->orWhere('name', 'admin.agent-bank-account-transactions.index')
                ->orWhere('name', 'admin.agent-bank-account-transactions.create')
                ->orWhere('name', 'admin.agent-bank-account-transactions.store')
            ;
        })->pluck('id')->toArray();
        $role->syncPermissions($permissions);

        return 'done';
    }
}
