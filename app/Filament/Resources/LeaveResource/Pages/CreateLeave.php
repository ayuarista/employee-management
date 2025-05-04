<?php

namespace App\Filament\Resources\LeaveResource\Pages;

use Filament\Actions;
use App\Models\LeaveApproval;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\LeaveResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLeave extends CreateRecord
{
    protected static string $resource = LeaveResource::class;

    public function mutateFormDataBeforeCreate($data):array
    {
        if(Auth::user()->hasRole('employee'))
        {
            $data['user_id'] = Auth::id();

            return $data;
        }
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public function afterCreate():void{
        LeaveApproval::create([
            'leave_id' => $this->record->id,
            'status' => 'pending'
        ]);
    }
}
