<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class VoiceRecorder extends Field
{
    protected string $view = 'filament.forms.components.voice-recorder';

    protected function setUp(): void
    {
        parent::setUp();

        // ✅ Set default state path
        $this->statePath($this->getName());
    }
}
