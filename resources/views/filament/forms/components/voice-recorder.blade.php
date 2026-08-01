@php
    $statePath = $getStatePath();
    $csrfToken = csrf_token();
    $uploadUrl = route('voice-recordings.store');
    $existingPath = $getState();
    $existingUrl = $existingPath ? asset($existingPath) : null;
@endphp

<div
    x-data="{
        state: $wire.entangle('{{ $statePath }}'),
        existingAudioUrl: '{{ $existingUrl }}',
        recorder: null,
        chunks: [],
        recording: false,
        uploading: false,
        audioUrl: null,
        seconds: 0,
        timer: null,
        error: null,
        hasRecording: false,

        init() {
            if (this.existingAudioUrl) {
                this.hasRecording = true;
                this.audioUrl = this.existingAudioUrl;
            }
        },

        async startRecording() {
            this.error = null;
            this.chunks = [];
            this.hasRecording = false;
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                this.recorder = new MediaRecorder(stream, {
                    mimeType: 'audio/webm;codecs=opus'
                });
                this.chunks = [];

                this.recorder.ondataavailable = (e) => {
                    if (e.data.size > 0) {
                        this.chunks.push(e.data);
                    }
                };

                this.recorder.onstop = () => {
                    stream.getTracks().forEach(track => track.stop());
                    this.uploadRecording();
                };

                this.recorder.start(1000);
                this.recording = true;
                this.seconds = 0;
                this.timer = setInterval(() => this.seconds++, 1000);
            } catch (err) {
                this.error = 'Microphone access denied.';
            }
        },

        stopRecording() {
            if (this.recorder && this.recording) {
                this.recorder.stop();
                this.recording = false;
                clearInterval(this.timer);
            }
        },

        async uploadRecording() {
            if (this.chunks.length === 0) {
                this.error = 'No audio recorded.';
                return;
            }

            this.uploading = true;
            this.error = null;

            try {
                const blob = new Blob(this.chunks, { type: 'audio/webm' });
                this.audioUrl = URL.createObjectURL(blob);

                const formData = new FormData();
                formData.append('audio', blob, 'recording.webm');

                const response = await fetch('{{ $uploadUrl }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ $csrfToken }}',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const data = await response.json();

                if (data.success) {
                    this.state = data.path;
                    this.hasRecording = true;
                    this.audioUrl = data.url || asset(data.path);
                } else {
                    this.error = data.error || 'Upload failed.';
                }
            } catch (err) {
                this.error = 'Upload failed.';
            } finally {
                this.uploading = false;
            }
        },

        clearRecording() {
            this.state = null;
            this.audioUrl = null;
            this.seconds = 0;
            this.error = null;
            this.chunks = [];
            this.hasRecording = false;
            this.existingAudioUrl = null;
        },

        formatTime(s) {
            const m = Math.floor(s / 60).toString().padStart(2, '0');
            const sec = (s % 60).toString().padStart(2, '0');
            return m + ':' + sec;
        }
    }"
    class="w-full space-y-2"
>
    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 p-3 shadow-sm w-full">

        <!-- 1. RECORDING STATE -->
        <template x-if="recording">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5 shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                    </span>
                    <span class="text-xs font-medium text-red-600 dark:text-red-400">
                        Recording... <span x-text="formatTime(seconds)" class="font-mono"></span>
                    </span>
                </div>
                <button
                    type="button"
                    x-on:click="stopRecording()"
                    style="display: inline-flex; align-items: center; gap: 6px; white-space: nowrap;"
                    class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-red-500 transition"
                >
                    <svg style="width: 12px; height: 12px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 24 24">
                        <rect x="6" y="6" width="12" height="12" rx="2"></rect>
                    </svg>
                    <span style="display: inline-block;">Stop Recording</span>
                </button>
            </div>
        </template>

        <!-- 2. UPLOADING STATE -->
        <template x-if="uploading">
            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 py-1">
                <svg class="animate-spin h-4 w-4 text-primary-600 shrink-0" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Saving audio file...</span>
            </div>
        </template>

        <!-- 3. HAS RECORDING STATE -->
        <template x-if="hasRecording && audioUrl && !recording && !uploading">
            <div class="space-y-2 w-full">
                <audio :src="audioUrl" controls style="width: 100%; height: 36px;" class="block rounded-lg"></audio>
                <div class="flex justify-start">
                    <button
                        type="button"
                        x-on:click="clearRecording()"
                        class="inline-flex items-center gap-1 text-xs font-medium text-red-600 hover:text-red-700 dark:text-red-400 transition"
                    >
                        <span>✕</span>
                        <span>Remove recording</span>
                    </button>
                </div>
            </div>
        </template>

        <!-- 4. EMPTY STATE -->
        <template x-if="!hasRecording && !recording && !uploading">
            <div style="display: flex; align-items: center; justify-content: flex-end; width: 100%;">
                <button
                    type="button"
                    x-on:click="startRecording()"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-1.5 text-xs font-medium text-gray-800 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition shrink-0"
                >
                    <svg style="width: 14px; height: 14px; display: inline-block; vertical-align: middle;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                    </svg>
                    <span>Record Note</span>
                </button>
            </div>
        </template>

        <!-- ERROR STATE -->
        <template x-if="error">
            <div class="mt-2 text-xs font-medium text-red-600 dark:text-red-400" x-text="error"></div>
        </template>
    </div>
</div>
