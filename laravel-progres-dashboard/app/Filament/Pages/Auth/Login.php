<?php

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    protected string $view = 'filament.pages.auth.login';

    public function getTitle(): string | Htmlable
    {
        return 'Masuk ke SISDA Monev';
    }

    public function getHeading(): string | Htmlable | null
    {
        return null;
    }

    public function hasLogo(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->label('Username')
                    ->placeholder('Masukkan username')
                    ->required()
                    ->autofocus(),
                $this->getPasswordFormComponent()
                    ->placeholder('Masukkan password'),
                $this->getRememberFormComponent()
                    ->label('Ingat saya'),
            ])
            ->statePath('data');
    }

    protected function getAuthenticateFormAction(): Action
    {
        return parent::getAuthenticateFormAction()
            ->label('Masuk');
    }
}
