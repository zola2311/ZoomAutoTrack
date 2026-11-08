<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.pages.auth.login';

    /**
     * Configure form components with explicit validation messages.
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ]);
    }

    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent()
            ->validationMessages([
                'required' => 'Please enter your email address.',
                'email' => 'Please enter a valid email address format (e.g. name@example.com).',
            ]);
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->validationMessages([
                'required' => 'Please enter your password.',
            ]);
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.email' => 'These credentials do not match our records, or the account is inactive.',
        ]);
    }

    /**
     * Autofill username (email) and password into the login form for testing.
     */
    public function autofill(string $role): void
    {
        $email = match (strtolower(trim($role))) {
            'admin' => 'admin@autotrack.et',
            'receptionist', 'reception' => 'reception@autotrack.et',
            'mechanic', 'mechanics' => 'mechanic1@autotrack.et',
            'advisor', 'service_advisor' => 'advisor@autotrack.et',
            'manager' => 'manager@autotrack.et',
            'cashier' => 'cashier@autotrack.et',
            'inventory', 'inventory_manager' => 'inventory@autotrack.et',
            default => $role,
        };

        $this->form->fill([
            'email' => $email,
            'password' => 'password',
            'remember' => true,
        ]);

        Notification::make()
            ->title('Credentials Autofilled')
            ->body("Filled {$email} (Password: password)")
            ->success()
            ->send();
    }

    /**
     * Instantly log in as the given test role/user without typing anything.
     */
    public function quickLogin(string $email): ?LoginResponse
    {
        $user = User::where('email', $email)->first();

        if (! $user) {
            Notification::make()
                ->title('Account not found')
                ->body("No test user registered with email: {$email}")
                ->danger()
                ->send();

            return null;
        }

        if (isset($user->is_active) && ! $user->is_active) {
            Notification::make()
                ->title('Account Inactive')
                ->body("User {$email} is marked inactive.")
                ->danger()
                ->send();

            return null;
        }

        Filament::auth()->login($user, remember: true);
        if (request()->hasSession()) {
            request()->session()->regenerate();
        }

        Notification::make()
            ->title('Logged in successfully')
            ->body("Welcome back, {$user->name} ({$user->primary_role_name})!")
            ->success()
            ->send();

        return app(LoginResponse::class);
    }
}
