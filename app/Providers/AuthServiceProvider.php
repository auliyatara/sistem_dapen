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
        Gate::define('manage-kepesertaan', function (User $user) {
            $role = $user->role;
            if (!$role || (int) $role->role_status !== 1) return false;
            $name = strtolower(preg_replace('/[^a-z0-9]/i', '', $role->role_name));
            // The application's super administrator is stored as "Admin".
            return in_array($name, ['kepesertaan', 'bidangkepesertaan', 'admin', 'superadmin'], true);
        });

        Gate::define('manage-kpi', function (User $user, Role $role) {
            $name = strtolower(preg_replace('/[^a-z0-9]/i', '', $role->role_name));
            $actor = $user->role;
            $actorName = $actor ? strtolower(preg_replace('/[^a-z0-9]/i', '', $actor->role_name)) : '';

            return (int) $role->role_status === 1
                && (!$actor || (int)$actor->role_status === 1)
                && !in_array($name, ['admin', 'superadmin'], true)
                && !in_array($actorName, ['admin', 'superadmin'], true)
                && (string) $user->user_role_id === (string) $role->role_id;
        });
    }
}
