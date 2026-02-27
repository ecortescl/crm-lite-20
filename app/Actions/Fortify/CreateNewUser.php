<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\Tenant;
use App\Models\User;
use App\Services\TenantProvisioningService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    public function __construct(
        private readonly TenantProvisioningService $tenantProvisioning,
    ) {}

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'company_name' => ['required', 'string', 'max:255'],
            'password' => $this->passwordRules(),
        ])->validate();

        return DB::transaction(function () use ($input) {
            $tenantName = trim($input['company_name']);
            $slugBase = Str::slug($tenantName) ?: 'workspace';
            $slug = $slugBase;
            $counter = 1;

            while (Tenant::where('slug', $slug)->exists()) {
                $slug = $slugBase.'-'.$counter;
                $counter++;
            }

            $tenant = Tenant::create([
                'name' => $tenantName,
                'slug' => $slug,
            ]);

            $user = User::create([
                'tenant_id' => $tenant->id,
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => $input['password'],
            ]);

            $this->tenantProvisioning->provision($tenant, $user);

            return $user;
        });
    }
}
