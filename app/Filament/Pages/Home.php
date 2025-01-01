<?php

namespace App\Filament\Pages;

use App\Models\ContentManagementSystem;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Log;

class Home extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $model = ContentManagementSystem::class;

    protected static string $view = 'filament.pages.home';

    protected static ?string $navigationLabel = 'Home';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'Content Management';

    public ?array $data = [];

    public function getTitle(): string
    {
        return 'Home Pages';
    }

    public function mount(): void
    {
        $data = [];

        $existingWelcomeSection = ContentManagementSystem::where('page', 'home')
            ->where('section', 1)
            ->first();

        if ($existingWelcomeSection) {
            $welcomeSectionAttributes = $existingWelcomeSection->attributesToArray();

            $data['welcome_section'] = [
                'title_name' => $welcomeSectionAttributes['title_name'],
                'subtitle' => $welcomeSectionAttributes['subtitle'],
                'background_image' => $welcomeSectionAttributes['background_image'],
            ];
        }

        $existingAboutSection = ContentManagementSystem::where('page', 'home')
            ->where('section', 2)
            ->first();

        if ($existingAboutSection) {
            $aboutSectionAttributes = $existingAboutSection->attributesToArray();

            $icons = json_decode($aboutSectionAttributes['icons'], true);

            $data['about_section'] = [
                'title_name' => $aboutSectionAttributes['title_name'],
                'subtitle' => $aboutSectionAttributes['subtitle'],
                'value' => $aboutSectionAttributes['value'],
                'icons' => $icons,
            ];
        }

        $existingResortSection = ContentManagementSystem::where('page', 'home')
            ->where('section', 3)
            ->first();

        if ($existingResortSection) {
            $resortSectionAttributes = $existingResortSection->attributesToArray();

            $data['resort_houses_section'] = [
                'title_name' => $resortSectionAttributes['title_name'],
                'subtitle' => $resortSectionAttributes['subtitle'],
                'value' => $resortSectionAttributes['value'],
            ];
        }

        $existingVideoSection = ContentManagementSystem::where('page', 'home')
            ->where('section', 4)
            ->first();

        if ($existingVideoSection) {
            $videoSectionAttributes = $existingVideoSection->attributesToArray();

            $data['video_section'] = [
                'title_name' => $videoSectionAttributes['title_name'],
            ];
        }

        $this->form->fill($data);
    }



    public function form(Form $form): Form
    {
        $maxLength = 50;
        $title = 50;

        return $form->schema([
            \Filament\Forms\Components\Tabs::make('tabs')
                ->tabs([
                    \Filament\Forms\Components\Tabs\Tab::make('Welcome Section')
                        ->schema([
                            \Filament\Forms\Components\Fieldset::make('Content')
                                ->schema([
                                    \Filament\Forms\Components\Hidden::make('page')
                                        ->default('home'),
                                    \Filament\Forms\Components\Hidden::make('section')
                                        ->default(1),
                                    \Filament\Forms\Components\TextInput::make('title_name')
                                        ->label('Title')
                                        ->reactive()
                                        ->maxLength($title)
                                        ->afterStateUpdated(function ($state, $set) use ($title) {
                                            $set('title_name', substr($state, 0, $title));
                                        })
                                        ->hint(
                                            function ($get) use ($title) {
                                                $remainingLength = $title - strlen($get('title_name'));
                                                return $remainingLength > 0
                                                    ? "Remaining characters: {$remainingLength} characters"
                                                    : "Maximum length reached";
                                            }
                                        ),
                                    \Filament\Forms\Components\TextInput::make('subtitle')
                                        ->label('Tagline')
                                        ->reactive()
                                        ->maxLength($maxLength)
                                        ->afterStateUpdated(function ($state, $set) use ($maxLength) {
                                            $set('subtitle', substr($state, 0, $maxLength));
                                        })
                                        ->hint(
                                            function ($get) use ($maxLength) {
                                                $remainingLength = $maxLength - strlen($get('subtitle'));
                                                return $remainingLength > 0
                                                    ? "Remaining characters: {$remainingLength} characters"
                                                    : "Maximum length reached";
                                            }
                                        ),
                                    \Filament\Forms\Components\FileUpload::make('background_image')
                                        ->label('Background Image')
                                        ->image()
                                        ->placeholder('Must be high quality image (PNG, JPEG, SVG)')
                                        ->columnSpan('full')
                                ])
                        ])->statePath('welcome_section'),

                    \Filament\Forms\Components\Tabs\Tab::make('About Section')
                        ->schema([
                            \Filament\Forms\Components\Fieldset::make('Content')
                                ->schema([
                                    \Filament\Forms\Components\Hidden::make('page')
                                        ->default('home'),
                                    \Filament\Forms\Components\Hidden::make('section')
                                        ->default(2),
                                    \Filament\Forms\Components\TextInput::make('title_name')
                                        ->label('Title')
                                        ->reactive()
                                        ->maxLength($title)
                                        ->afterStateUpdated(function ($state, $set) use ($title) {
                                            $set('title_name', substr($state, 0, $title));
                                        })
                                        ->hint(
                                            function ($get) use ($title) {
                                                $remainingLength = $title - strlen($get('title_name'));
                                                return $remainingLength > 0
                                                    ? "Remaining characters: {$remainingLength} characters"
                                                    : "Maximum length reached";
                                            }
                                        ),
                                    \Filament\Forms\Components\TextInput::make('subtitle')
                                        ->label('Subtitle')
                                        ->reactive()
                                        ->maxLength($maxLength)
                                        ->afterStateUpdated(function ($state, $set) use ($maxLength) {
                                            $set('subtitle', substr($state, 0, $maxLength));
                                        })
                                        ->hint(
                                            function ($get) use ($maxLength) {
                                                $remainingLength = $maxLength - strlen($get('subtitle'));
                                                return $remainingLength > 0
                                                    ? "Remaining characters: {$remainingLength} characters"
                                                    : "Maximum length reached";
                                            }
                                        ),
                                    \Filament\Forms\Components\MarkdownEditor::make('value')
                                        ->label('Description')
                                        ->placeholder('Enter Description Here')
                                        ->columnSpan('full'),
                                    \Filament\Forms\Components\Repeater::make('icons')
                                        ->schema([
                                            \Filament\Forms\Components\FileUpload::make('image')
                                                ->image()
                                                ->label('Icon Image'),
                                            \Filament\Forms\Components\TextInput::make('icon_name')
                                                ->label('Name')
                                        ])
                                        ->label('Icons (Optional)')
                                        ->addActionLabel('Add another Icon')
                                        ->grid(2)
                                        ->maxItems(4)
                                        ->defaultItems(4)
                                        ->collapsible()
                                        ->columnSpan('full'),
                                ])
                        ])->statePath('about_section'),

                    \Filament\Forms\Components\Tabs\Tab::make('Resort Houses Section')
                        ->schema([
                            \Filament\Forms\Components\Fieldset::make('Content')
                                ->schema([
                                    \Filament\Forms\Components\Hidden::make('page')
                                        ->default('home'),
                                    \Filament\Forms\Components\Hidden::make('section')
                                        ->default(3),
                                    \Filament\Forms\Components\TextInput::make('title_name')
                                        ->label('Title')
                                        ->reactive()
                                        ->maxLength($title)
                                        ->afterStateUpdated(function ($state, $set) use ($title) {
                                            $set('title_name', substr($state, 0, $title));
                                        })
                                        ->hint(
                                            function ($get) use ($title) {
                                                $remainingLength = $title - strlen($get('title_name'));
                                                return $remainingLength > 0
                                                    ? "Remaining characters: {$remainingLength} characters"
                                                    : "Maximum length reached";
                                            }
                                        ),
                                    \Filament\Forms\Components\TextInput::make('subtitle')
                                        ->label('Subtitle')
                                        ->reactive()
                                        ->maxLength($maxLength)
                                        ->afterStateUpdated(function ($state, $set) use ($maxLength) {
                                            $set('subtitle', substr($state, 0, $maxLength));
                                        })
                                        ->hint(
                                            function ($get) use ($maxLength) {
                                                $remainingLength = $maxLength - strlen($get('subtitle'));
                                                return $remainingLength > 0
                                                    ? "Remaining characters: {$remainingLength} characters"
                                                    : "Maximum length reached";
                                            }
                                        ),
                                    \Filament\Forms\Components\MarkdownEditor::make('value')
                                        ->label('Description')
                                        ->placeholder('Enter Description Here')
                                        ->columnSpan('full'),
                                ])
                        ])->statePath('resort_houses_section'),

                    \Filament\Forms\Components\Tabs\Tab::make('Video Section')
                        ->schema([
                            \Filament\Forms\Components\Fieldset::make('Content')
                                ->schema([
                                    \Filament\Forms\Components\Hidden::make('page')
                                        ->default('home'),
                                    \Filament\Forms\Components\Hidden::make('section')
                                        ->default(4),
                                    \Filament\Forms\Components\TextInput::make('title_name')
                                        ->label('Video Url')
                                        ->url()
                                        ->hint('Must be a youtube video link')
                                        ->columnSpan('full')
                                        ->suffixIcon('heroicon-m-globe-alt'),
                                ])
                        ])->statePath('video_section'),
                ])->persistTabInQueryString()
        ])->statePath('data');
    }


    public function getFormActions()
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Save Changes')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            $aboutSectionData = $data['about_section'];
            $welcomeSectionData = $data['welcome_section'];
            $resortSectionData = $data['resort_houses_section'];
            $videoSectionData = $data['video_section'];

            // welcome section creation or update
            if ($welcomeSectionData) {
                $existingRecord = ContentManagementSystem::where('page', 'home')
                    ->where('section', 1)
                    ->first();

                if ($existingRecord) {
                    $existingRecord->update([
                        'title_name' => $welcomeSectionData['title_name'],
                        'subtitle' => $welcomeSectionData['subtitle'],
                        'background_image' => $welcomeSectionData['background_image'],
                    ]);
                } else {
                    ContentManagementSystem::create([
                        'page' => 'home',
                        'section' => 1,
                        'title_name' => $welcomeSectionData['title_name'],
                        'subtitle' => $welcomeSectionData['subtitle'],
                        'background_image' => $welcomeSectionData['background_image'],
                    ]);
                }
            }

            //about section creation or update
            if ($aboutSectionData) {
                $existingRecord = ContentManagementSystem::where('page', 'home')
                    ->where('section', 2)
                    ->first();

                if ($existingRecord) {
                    $existingRecord->update([
                        'title_name' => $aboutSectionData['title_name'],
                        'subtitle' => $aboutSectionData['subtitle'],
                        'value' => $aboutSectionData['value'],
                        'icons' => json_encode($aboutSectionData['icons']),
                    ]);
                } else {
                    ContentManagementSystem::create([
                        'page' => 'home',
                        'section' => 2,
                        'title_name' => $aboutSectionData['title_name'],
                        'subtitle' => $aboutSectionData['subtitle'],
                        'value' => $aboutSectionData['value'],
                        'icons' => json_encode($aboutSectionData['icons']),
                    ]);
                }
            }

            //resort house section creation or update
            if ($resortSectionData) {
                $existingRecord = ContentManagementSystem::where('page', 'home')
                    ->where('section', 3)
                    ->first();

                if ($existingRecord) {
                    $existingRecord->update([
                        'title_name' => $resortSectionData['title_name'],
                        'subtitle' => $resortSectionData['subtitle'],
                        'value' => $resortSectionData['value'],
                    ]);
                } else {
                    ContentManagementSystem::create([
                        'page' => 'home',
                        'section' => 3,
                        'title_name' => $resortSectionData['title_name'],
                        'subtitle' => $resortSectionData['subtitle'],
                        'value' => $resortSectionData['value'],
                    ]);
                }
            }

            if ($videoSectionData) {
                $existingRecord = ContentManagementSystem::where('page', 'home')
                    ->where('section', 4)
                    ->first();

                if ($existingRecord) {
                    $existingRecord->update([
                        'title_name' => $videoSectionData['title_name'],
                    ]);
                } else {
                    ContentManagementSystem::create([
                        'page' => 'home',
                        'section' => 4,
                        'title_name' => $videoSectionData['title_name'],
                    ]);
                }
            }
        } catch (Halt $exception) {
            return;
        }

        \Filament\Notifications\Notification::make()
            ->success()
            ->title('Saved')
            ->send();
    }
}
