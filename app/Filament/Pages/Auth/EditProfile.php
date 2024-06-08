<?php
 
namespace App\Filament\Pages\Auth;
 
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use App\Filament\Pages\Dashboard;
 
class EditProfile extends BaseEditProfile
{


    protected function getRedirectUrl(): string
    {
        return Dashboard::getUrl();
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('avatar_url')
                    ->avatar()
                    ->label('Foto de perfil')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('avatars')
                    ->previewable(true)
                    ->fetchFileInformation(false)
                    ->circleCropper(),
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        dd($data);
    }
}
