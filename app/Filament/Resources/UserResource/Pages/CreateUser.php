<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function beforeCreate(): void
    {
        if ($this->data['password'] != $this->data['confirm_password']) {
            Notification::make()
                ->title('Password does not match')
                ->color('danger')
                ->icon('heroicon-o-exclamation-circle')
                ->send();

            throw new Halt();
        }
    }
}
