<?php

namespace Tests;

use App\Models\User;
use App\Services\Auth\RegistrationService;
use App\Services\Schedule\ScheduleService;

trait RegisterSuccessUser
{
    protected bool $initialized = false;
    protected RegistrationService $registrationService;
    protected string $adminEmail = 'test-admin@invygo.com';
    protected string $adminPassword = 'demo12345';
    protected string $role = User::ROLE_ADMIN;

    /**
     * Register our success user to db
     */
    protected function registerSuccessUser(): void
    {
        if (!$this->initialized) {
            // Load if not loaded
            $this->registrationService = app(RegistrationService::class);
            $this->initialized = true;
        }

        $successUserData = [
            "name" => "Success login user",
            "email" => $this->adminEmail,
            "role" => $this->role,
            "password" => $this->adminPassword,
            "password_confirmation" => $this->adminPassword,
        ];

        // We will remove our test user, just to ensure that we will not get duplicates
        User::query()->where('email', $this->adminEmail)->delete();

        // Then we will register our success user to test the login functional
        $this->registrationService->register($successUserData);
    }
}
