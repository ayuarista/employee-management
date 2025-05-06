<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveApprovalResource\Pages;
use App\Filament\Resources\LeaveApprovalResource\RelationManagers;
use App\Models\LeaveApproval;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class LeaveApprovalResource extends Resource
{
    protected static ?string $model = LeaveApproval::class;

    protected static ?string $navigationIcon = 'hugeicons-validation-approval';

    protected static ?string $navigationGroup = 'Leave Management';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('leave_id')
                    ->relationship('leave', 'reason')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected'
                    ])
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                LeaveApproval::query()
                    ->where('status', 'pending')
            )
            ->columns([
                Tables\Columns\TextColumn::make('leave.reason')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('infolist')
                    ->heading('Leave Information')
                    ->schema([
                        TextEntry::make('leave.reason')
                            ->label('Leave Reason'),
                    ]),

                Actions::make([
                        Action::make('approve_leave')
                            ->requiresConfirmation()
                            ->modalHeading('Are you sure want to approve this leave?')
                            ->modalDescription('This action will approve the leave request.')
                            ->action(function(LeaveApproval $record) {
                                $record->update([
                                    'status' => 'approved',
                                    'user_id' => Auth::user()->id,
                                ]);

                                Notification::make()
                                    ->success()
                                    ->title('Leave request has been Approved')
                                    ->body('thank you for your action!')
                                    ->send();
                            })
                    ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaveApprovals::route('/'),
            'view' => Pages\ViewLeaveApproval::route('/{record}'),
        ];
    }
}