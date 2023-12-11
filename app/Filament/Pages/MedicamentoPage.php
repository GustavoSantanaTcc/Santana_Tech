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
    public $nome = '';
    public $fabricante = '';
    public $lote = '';
    public $validade = '';
    public $quantidade = '';
    public $tipo = '';
    public $descricao = '';

    public function table(Table $table): Table
    {
        return $table
            ->query(Medicamento::query())
            ->columns([
                TextColumn::make('nome')->sortable()->searchable(),
                TextColumn::make('validade')->dateTime('d/m/Y')->sortable(),
                TextColumn::make('fabricante'),
                TextColumn::make('tipo'),
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
                        $this->show_form = 'edit';
                        $id_edit = $medicamentos->id;
                        $dados = Medicamento::where('id', $id_edit)->first()->toArray();
                        $this->form->fill($dados);
                    }),
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                TextInput::make('nome')
                    ->label('Nome')
                    ->maxLength(255)
                    ->columnSpan(4)
                    ->placeholder('Digite o nome do medicamento')
                    ->required(),
                TextInput::make('fabricante')
                    ->label('Fabricante')
                    ->maxLength(255)
                    ->columnSpan(4)
                    ->placeholder('Digite o nome do fabricante')
                    ->required(),
                Select::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'Comprimido' => 'Comprimido',
                        'Gotas' => 'Gotas',
                        'Pomada' => 'Pomada',
                        'Xarope' => 'Xarope',
                        'Outros' => 'Outros',
                    ])
                    ->placeholder('Selecione o tipo de medicamento')
                    ->columnSpan(4)
                    ->required(),
                TextInput::make('lote')
                    ->label('Lote')
                    ->columns(2)
                    ->numeric()
                    ->required(),
                TextInput::make('quantidade')
                    ->label('Quantidade')
                    ->columns(2)
                    ->numeric()
                    ->required(),
                DatePicker::make('validade')
                    ->columnSpan(2)
                    ->label('Validade')
                    ->displayFormat('d/m/Y')
                    ->minDate(now())
                    ->placeholder('dd/mm/aaaa')
                    ->required(),
                MarkdownEditor::make('descricao')
                    ->label('Descrição')
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('public/uploads/markdown')
                    ->columnSpan(12),
            ])
            ->statePath('data');
    }

    public function showForm($view, $id = null)
    {
        $this->show_form = $view;

        if ($view == 'edit' && $id != null) {
            $this->id_edit = $id;
            $this->dados = Medicamento::find($id)->toArray();
            $this->form->fill($this->dados);
        } elseif ($view == 'view' && $id != null) {
            $this->medicamento_id = $id;
            $this->dados = Medicamento::find($id)->toArray();
            $this->form->fill($this->dados);
        } else {
            $this->form->fill();
        }
    }

    public function submit()
    {
        if ($this->show_form == 'edit') {
            $dados = $this->form->getState();
            Medicamento::where('id', $this->id_edit)->update($dados);
            $mensagem = "O medicamento foi atualizado com sucesso!";
            Notification::make()
                ->success()
                ->title($mensagem)
                ->send();

            $this->form->fill();
            $this->show_form = 'list';
        } else {
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
    }


    public function back()
    {
        $this->form->fill();
        $this->show_form = 'list';
    }

    public function mount()
    {
        $this->show_form = 'list';
        $this->form->fill();
    }

}