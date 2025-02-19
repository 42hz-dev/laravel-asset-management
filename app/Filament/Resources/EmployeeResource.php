<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Employee';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('department_id')
                    ->label('부서명')
                    ->relationship(name: 'department', titleAttribute: 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('이름')
                    ->required()
                    ->maxLength(15),
                Forms\Components\TextInput::make('phone')
                    ->label('휴대폰')
                    ->tel()
                    ->required()
                    ->maxLength(13),
                Forms\Components\Select::make('gender')
                    ->label('성별')
                    ->options([
                        'M' => '남자',
                        'W' => '여자'
                    ])
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('상태')
                    ->options([
                        1 => '재직',
                        2 => '퇴사'
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('date_hired')
                    ->label('입사 날짜')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('department.name')
                    ->label('부서명')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('이름')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('휴대폰')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('성별')
                    ->formatStateUsing(fn ($state) => $state === 'M' ? '남자' : ($state === 'W' ? '여자' : ''))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->label('상태')
                    ->formatStateUsing(fn (int $state) => $state === 1 ? '재직' : ($state === 0 ? '퇴사' : '휴무'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_hired')
                    ->label('입사일')
                    ->date()
                    ->formatStateUsing(fn (string $state) => Carbon::parse($state)->translatedFormat('Y년 m월 d일'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('등록일')
                    ->dateTime()
                    ->formatStateUsing(fn (string $state) => Carbon::parse($state)->translatedFormat('Y년 m월 d일'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('수정일')
                    ->dateTime()
                    ->formatStateUsing(fn (string $state) => Carbon::parse($state)->translatedFormat('Y년 m월 d일'))
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
                Section::make('직원 정보')
                    ->schema([
                        TextEntry::make('department.name')->label('부서명'),
                        TextEntry::make('name')->label('이름'),
                        TextEntry::make('phone')->label('번호'),
                        TextEntry::make('status')->label('상태')->formatStateUsing(fn (int $state) => $state === 1 ? '재직' : ($state === 0 ? '퇴사' : '휴무')),
                        TextEntry::make('gender')->label('성별')->formatStateUsing(fn ($state) => $state === 'M' ? '남자' : ($state === 'W' ? '여자' : '')),
                        TextEntry::make('date_hired')->label('입사일')->formatStateUsing(fn (string $state) => Carbon::parse($state)->translatedFormat('Y년 m월 d일')),
                    ])->columns(2)
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
