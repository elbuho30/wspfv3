<?php

namespace App\Filament\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as LoginBase;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Component;
use Illuminate\Validation\ValidationException;
use AbanoubNassem\FilamentGRecaptchaField\Forms\Components\GRecaptcha;

class Login extends LoginBase
{

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getDocumentFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getCaptchaFormComponent(),
                        $this->getRememberFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getDocumentFormComponent(): Component
    {
        return TextInput::make('nro_documento')
            ->label('Documento')
            ->required()
            ->autofocus()
            ->autocomplete()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getCaptchaFormComponent()
    {
        return GRecaptcha::make('g-recaptcha');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'nro_documento' => $data['nro_documento'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.nro_documento' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }
    
}
