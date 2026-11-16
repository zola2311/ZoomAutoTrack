@php
    $record = $getRecord();
    $voiceUrl = $record ? $record->voice_note_url : null;
@endphp

@if($voiceUrl)
    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px 18px; margin: 4px 0;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; flex-wrap: wrap; gap: 8px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; background-color: #EBF3FA; color: #1E69B8; font-size: 16px;">
                    🎙️
                </span>
                <div>
                    <div style="font-weight: 600; font-size: 13px; color: #1e293b;">
                        Customer Voice Note Attached
                    </div>
                    <div style="font-size: 11px; color: #64748b;">
                        Play the recording to hear the customer describe the defect or vehicle sound.
                    </div>
                </div>
            </div>
            <a href="{{ $voiceUrl }}" download="voice_note_{{ $record->quotation_number }}.webm" target="_blank"
               style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px; font-size: 11px; font-weight: 500; color: #1E69B8; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; text-decoration: none;">
                ⬇️ Download Audio
            </a>
        </div>
        <div>
            <audio controls preload="metadata" style="width: 100%; max-width: 600px; outline: none; border-radius: 6px;">
                <source src="{{ $voiceUrl }}">
                Your browser does not support the audio element.
            </audio>
        </div>
    </div>
@else
    <div style="padding: 12px 14px; background-color: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 6px; color: #64748b; font-size: 12px; display: flex; align-items: center; gap: 8px;">
        <span>🎙️</span>
        <span>No voice note recording was provided for this quotation inquiry.</span>
    </div>
@endif
