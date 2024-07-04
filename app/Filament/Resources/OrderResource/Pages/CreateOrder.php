<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Forms;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\Technic;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Illuminate\Database\Eloquent\Model;

class CreateOrder extends CreateRecord
{

    use HasWizard;
    protected static string $resource = OrderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Información general')
                        ->schema([
                            Forms\Components\TextInput::make('uid')
                                ->label('UID')
                                ->default('OR-'.$this->countOrder())
                                ->disabled()
                                ->dehydrated()
                                ->required()
                                ->maxLength(32)
                                ->unique(ignoreRecord: true)
                                ->columnSpan(1),
                            Forms\Components\Select::make('technic_id')
                                ->label('Técnico')
                                ->options(Technic::all()->pluck('name', 'id'))
                                ->native(false)
                                ->placeholder('Seleccionat técnico')
                                ->required()
                                ->searchable()
                                ->validationMessages([
                                    'required' => 'Técnico es requerido',
                                ]),
                            Forms\Components\TextInput::make('customer')
                                ->label('Nombre de cliente')
                                ->required()
                                ->maxLength(100)
                                ->validationMessages([
                                    'required' => 'Nombre del cliente es requerido',
                                    'max' => 'El nombre del cliente no debe exceder los 100 caracteres'
                                ])->columnSpan(2),
                            Forms\Components\TextInput::make('brand')
                                ->label('Marca del auto')
                                ->required()
                                ->maxLength(50)
                                ->validationMessages([
                                    'required' => 'Marca del auto es requerido',
                                    'max' => 'La marca del auto no debe exceder los 50 caracteres'
                                ]),
                            Forms\Components\TextInput::make('model')
                                ->label('Modelo del auto')
                                ->required()
                                ->maxLength(50)
                                ->validationMessages([
                                    'required' => 'Modelo del auto es requerido',
                                    'max' => 'El modelo del auto no debe exceder los 50 caracteres'
                                ]),
                            Forms\Components\TextInput::make('year')
                                ->label('Año del auto')
                                ->numeric()
                                ->minValue(1900)
                                ->maxValue(2030)
                                ->required()
                                 ->validationMessages([
                                    'required' => 'Año del auto es requerido',
                                    'min' => 'El año del auto no debe ser menor a 1900',
                                    'max' => 'El año del auto no debe exceder del 2030'
                                ]),
                            Forms\Components\Textarea::make('comments')
                                ->label(__('Comentarios'))
                                ->rows(6)
                                ->autosize()
                                ->maxLength(65535)
                                ->validationMessages([
                                    'max' => 'La descripción no debe exceder los 65535 caracteres',
                                ])
                                ->columnSpan('full')
                        ])->columns(3),
                    Wizard\Step::make('Artículos de Productos')
                        ->schema([
                            OrderResource::getItemsRepeater(),
                        ]),
                ])
                ->cancelAction($this->getCancelFormAction())
                ->submitAction($this->getSubmitFormAction())
                ->skippable($this->hasSkippableSteps())
            ]) ->columns(null);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['info_date'] = date('Y-m-d');
        
        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var Order $order */
        $order = $this->record;

        $order->total = $order->items->sum(fn (OrderDetail $product) => $product->price * $product->quantity);
        $order->save();
    }

    private function countOrder(): string
    {
        $count = Order::query()->withTrashed()->count() + 1;
        return str_pad($count, 6, '0', STR_PAD_LEFT);
    }
}
