<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('manage-kpi', function (User $user, Role $role) {
            $name = strtolower(preg_replace('/[^a-z0-9]/i', '', $role->role_name));

            return (int) $role->role_status === 1
                && !in_array($name, ['admin', 'superadmin'], true)
                && (string) $user->user_role_id === (string) $role->role_id;
        });
    }
}
