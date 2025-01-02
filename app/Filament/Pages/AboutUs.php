<?php

namespace App\Filament\Pages;

use App\Models\ContentManagementSystem;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;

class AboutUs extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $model = ContentManagementSystem::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'About Us';

    protected static string $view = 'filament.pages.about-us';

    protected static ?string $navigationGroup = 'Content Management';

    public ?array $data = [];

    public function getTitle(): string
    {
        return 'About Us Pages';
    }

    public function mount(): void
    {
        $data = [];

        $existingWelcomeSection = ContentManagementSystem::where('page', 'about')
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

        $existingIntroductionSection = ContentManagementSystem::where('page', 'about')
            ->where('section', 2)
            ->first();

        if ($existingIntroductionSection) {
            $introductionSectionAttributes = $existingIntroductionSection->attributesToArray();

            $data['introduction_section'] = [
                'title_name' => $introductionSectionAttributes['title_name'],
                'value' => $introductionSectionAttributes['value'],
                'background_image' => $introductionSectionAttributes['background_image'],
            ];
        }

        $existingFeatureSection = ContentManagementSystem::where('page', 'about')
            ->where('section', 3)
            ->first();

        if ($existingFeatureSection) {
            $featureSectionAttributes = $existingFeatureSection->attributesToArray();

            $icons = json_decode($featureSectionAttributes['icons'], true);

            $data['feature_section'] = [
                'icons' => $icons,
            ];
        }

        $existingHistorySection = ContentManagementSystem::where('page', 'about')
            ->where('section', 4)
            ->first();

        if ($existingHistorySection) {
            $historySectionAttributes = $existingHistorySection->attributesToArray();

            $data['history_section'] = [
                'title_name' => $historySectionAttributes['title_name'],
                'subtitle' => $historySectionAttributes['subtitle'],
                'value' => $historySectionAttributes['value'],
                'background_image' => $historySectionAttributes['background_image'],
            ];
        }

        $existingFAQSection = ContentManagementSystem::where('page', 'about')
            ->where('section', 5)
            ->first();

        if ($existingFAQSection) {
            $faqSectionAttributes = $existingFAQSection->attributesToArray();

            $data['faq_section'] = [
                'title_name' => $faqSectionAttributes['title_name'],
                'value' => $faqSectionAttributes['value'],
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
                        ->icon('heroicon-o-sparkles')
                        ->schema([
                            \Filament\Forms\Components\Fieldset::make('Content')
                                ->schema([
                                    \Filament\Forms\Components\Hidden::make('page_welcome')
                                        ->default('about'),
                                    \Filament\Forms\Components\Hidden::make('section_welcome')
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

                    \Filament\Forms\Components\Tabs\Tab::make('Introduction Section')
                        ->icon('heroicon-o-light-bulb')
                        ->schema([
                            \Filament\Forms\Components\Fieldset::make('Content')
                                ->schema([
                                    \Filament\Forms\Components\Hidden::make('page')
                                        ->default('about'),
                                    \Filament\Forms\Components\Hidden::make('section')
                                        ->default(2),
                                    \Filament\Forms\Components\TextInput::make('title_name')
                                        ->label('Title')
                                        ->reactive()
                                        ->columnSpan('full')
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
                                    \Filament\Forms\Components\MarkdownEditor::make('value')
                                        ->label('Description')
                                        ->placeholder('Enter Description Here')
                                        ->columnSpan('full'),

                                    \Filament\Forms\Components\FileUpload::make('background_image')
                                        ->label('Background Image')
                                        ->image()
                                        ->placeholder('Must be high quality image (PNG, JPEG, SVG)')
                                        ->columnSpan('full')

                                ])
                        ])->statePath('introduction_section'),

                    \Filament\Forms\Components\Tabs\Tab::make('Features Section')
                        ->icon('heroicon-o-star')
                        ->schema([
                            \Filament\Forms\Components\Fieldset::make('Content')
                                ->schema([
                                    \Filament\Forms\Components\Hidden::make('page')
                                        ->default('about'),
                                    \Filament\Forms\Components\Hidden::make('section')
                                        ->default(3),
                                    \Filament\Forms\Components\Repeater::make('icons')
                                        ->schema([
                                            \Filament\Forms\Components\FileUpload::make('image')
                                                ->image()
                                                ->label('Icon Image'),
                                            \Filament\Forms\Components\TextInput::make('icon_name')
                                                ->label('Feature Name'),
                                            \Filament\Forms\Components\MarkdownEditor::make('value')
                                                ->label('Description')
                                                ->placeholder('Enter Description Here')
                                                ->columnSpan('full'),
                                        ])
                                        ->label('')
                                        ->addActionLabel('Add another feature')
                                        ->grid(2)
                                        ->maxItems(4)
                                        ->defaultItems(4)
                                        ->collapsible()
                                        ->columnSpan('full'),
                                ])
                        ])->statePath('feature_section'),

                    \Filament\Forms\Components\Tabs\Tab::make('History Section')
                        ->icon('heroicon-o-clock')
                        ->schema([
                            \Filament\Forms\Components\Fieldset::make('Content')
                                ->schema([
                                    \Filament\Forms\Components\Hidden::make('page')
                                        ->default('about'),
                                    \Filament\Forms\Components\Hidden::make('section')
                                        ->default(4),
                                    \Filament\Forms\Components\TextInput::make('title_name')
                                        ->label('Title')
                                        ->reactive()
                                        ->columnSpan('full')
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
                                    \Filament\Forms\Components\MarkdownEditor::make('value')
                                        ->label('Description')
                                        ->placeholder('Enter Description Here')
                                        ->columnSpan('full'),

                                    \Filament\Forms\Components\FileUpload::make('background_image')
                                        ->label('Background Image')
                                        ->image()
                                        ->placeholder('Must be high quality image (PNG, JPEG, SVG)')
                                        ->columnSpan('full')
                                ])
                        ])->statePath('history_section'),

                    \Filament\Forms\Components\Tabs\Tab::make('Highlighted FAQ Section')
                        ->icon('heroicon-o-question-mark-circle')
                        ->schema([
                            \Filament\Forms\Components\Fieldset::make('Content')
                                ->schema([
                                    \Filament\Forms\Components\Hidden::make('page')
                                        ->default('about'),
                                    \Filament\Forms\Components\Hidden::make('section')
                                        ->default(4),
                                    \Filament\Forms\Components\TextInput::make('title_name')
                                        ->label('Question')
                                        ->placeholder('Enter your highlighted faq here')
                                        ->columnSpan('full'),

                                    \Filament\Forms\Components\MarkdownEditor::make('value')
                                        ->label('Description')
                                        ->placeholder('Enter Answer Here')
                                        ->columnSpan('full'),
                                ])
                        ])->statePath('faq_section'),
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
            $welcomeSectionData = $data['welcome_section'];
            $introductionSectionData = $data['introduction_section'];
            $featureSectionData = $data['feature_section'];
            $historySectionData = $data['history_section'];
            $faqSectionData = $data['faq_section'];

            // welcome section creation or update
            if ($welcomeSectionData) {
                $existingRecord = ContentManagementSystem::where('page', 'about')
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
                        'page' => 'about',
                        'section' => 1,
                        'title_name' => $welcomeSectionData['title_name'],
                        'subtitle' => $welcomeSectionData['subtitle'],
                        'background_image' => $welcomeSectionData['background_image'],
                    ]);
                }
            }

            //introduction section creation or update
            if ($introductionSectionData) {
                $existingRecord = ContentManagementSystem::where('page', 'about')
                    ->where('section', 2)
                    ->first();

                if ($existingRecord) {
                    $existingRecord->update([
                        'title_name' => $introductionSectionData['title_name'],
                        'value' => $introductionSectionData['value'],
                        'background_image' => $introductionSectionData['background_image'],
                    ]);
                } else {
                    ContentManagementSystem::create([
                        'page' => 'about',
                        'section' => 2,
                        'title_name' => $introductionSectionData['title_name'],
                        'value' => $introductionSectionData['value'],
                        'background_image' => $introductionSectionData['background_image'],
                    ]);
                }
            }

            //feature section creation or update
            if ($featureSectionData) {
                $existingRecord = ContentManagementSystem::where('page', 'about')
                    ->where('section', 3)
                    ->first();

                if ($existingRecord) {
                    $existingRecord->update([
                        'icons' => json_encode($featureSectionData['icons']),
                    ]);
                } else {
                    ContentManagementSystem::create([
                        'page' => 'about',
                        'section' => 3,
                        'icons' => json_encode($featureSectionData['icons']),
                    ]);
                }
            }

            // history section creation or update
            if ($historySectionData) {
                $existingRecord = ContentManagementSystem::where('page', 'about')
                    ->where('section', 4)
                    ->first();

                if ($existingRecord) {
                    $existingRecord->update([
                        'title_name' => $historySectionData['title_name'],
                        'value' => $historySectionData['value'],
                        'background_image' => $historySectionData['background_image'],
                    ]);
                } else {
                    ContentManagementSystem::create([
                        'page' => 'about',
                        'section' => 4,
                        'title_name' => $historySectionData['title_name'],
                        'value' => $historySectionData['value'],
                        'background_image' => $historySectionData['background_image'],
                    ]);
                }
            }

            //faq section creation or update
            if ($faqSectionData) {
                $existingRecord = ContentManagementSystem::where('page', 'about')
                    ->where('section', 5)
                    ->first();

                if ($existingRecord) {
                    $existingRecord->update([
                        'title_name' => $faqSectionData['title_name'],
                        'value' => $faqSectionData['value'],
                    ]);
                } else {
                    ContentManagementSystem::create([
                        'page' => 'about',
                        'section' => 5,
                        'title_name' => $faqSectionData['title_name'],
                        'value' => $faqSectionData['value'],
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
