<?php

namespace App\Filament\Pages;

use App\Models\Medicamento;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Livewire\Attributes\Validate;

class MedicamentoPage extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.medicamento-page';
    protected static ?string $title = 'Medicamentos';
    protected static ?string $nagigationTitle = 'Medicamentos';
    protected static ?string $navigationGroup = 'Cadastros';

    public $dados;
    public ?array $data = [];
    public $medicamento_id;
    public $show_form;
    public $value;
    public $id;
    public $id_edit;

    #[Validate('required')]
    public $nome = '';

    // #[Validate('required')]
    // public $fabricante = '';

    // public $lote = '';
    // public $validade = '';
    // public $quantidade = '';
    // public $tipo = '';
    // public $descricao = '';

    public function table(Table $table): Table
    {
        return $table
            ->query(Medicamento::query())
            ->columns([
                TextColumn::make('nome')->sortable()->searchable(),
                // TextColumn::make('validade')->dateTime('d/m/Y')->sortable(),
                // TextColumn::make('fabricante'),
                // TextColumn::make('tipo'),
            ])->defaultSort('nome', 'asc')
            ->filters([
                // ...
            ])
            ->actions([
                DeleteAction::make()->label('Excluir')->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Medicamento deletado')
                        ->body('O medicamento foi deletado com sucesso!'),
                ),
                Action::make('edit')
                    ->label('Editar')
                    ->icon('heroicon-o-pencil')
                    ->action(function (Medicamento $medicamentos) {
                        $this->showForm($this->value = 'edit', $medicamentos->id);
                        // dd($medicamentos->id);
                    }),
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                TextInput::make('nome')
                    ->maxLength(255)
                    ->columnSpan(4)
                    ->placeholder('Digite o nome do medicamento')
                    ->required(),
                // TextInput::make('fabricante')
                //     ->maxLength(255)
                //     ->columnSpan(4)
                //     ->placeholder('Digite o nome do fabricante'),
                // Select::make('tipo')
                //     ->options([
                //         'Comprimido' => 'Comprimido',
                //         'Gotas' => 'Gotas',
                //         'Pomada' => 'Pomada',
                //         'Xarope' => 'Xarope',
                //         'Outros' => 'Outros',
                //     ])
                //     ->placeholder('Selecione o tipo de medicamento')
                //     ->columnSpan(4),
                // TextInput::make('lote')
                //     ->columns(2)
                //     ->numeric(),
                // TextInput::make('quantidade')
                //     ->columns(2)
                //     ->numeric(),
                // DatePicker::make('validade')
                //     ->columnSpan(2)
                //     ->displayFormat('d/m/Y')
                //     ->placeholder('dd/mm/aaaa'),
                // MarkdownEditor::make('descricao')
                //     ->columnSpan(12),
            ])
            ->statePath('data');
    }

    public function showForm($view, $id = null)
    {
        $this->show_form = $view;
        if ($id != null && $view == 'edit') {
            $this->show_form = 'create';
            $this->id_edit = $id;
            $this->dados = Medicamento::where('id', $id)->first()->toArray();
            $this->form->fill($this->dados);
        }
        if ($id != null && $view == 'edit') {
            $this->medicamento_id = $id;
            $this->show_form = 'view';
            $this->dados = Medicamento::where('id', $id)->first()->toArray();
            $this->form->fill($this->dados);
        }
    }

    public function submit()
    {
        // $this->validate();
        $dados = $this->form->getState();

        Medicamento::create($dados);

        $mensagem = "O medicamento foi cadastrado com sucesso!";

        Notification::make()
            ->success()
            ->title($mensagem)
            ->send();

        $this->form->fill();
        $this->show_form = 'list';
    }


    public function back()
    {
        $this->form->fill();
        $this->show_form = 'list';
    }

    public function mount()
    {
        $this->show_form = 'create';
        $this->form->fill();
    }

}