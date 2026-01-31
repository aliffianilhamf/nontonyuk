<x-app-layout>
    {{-- Header dengan Tombol Kembali --}}
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Menonton: {{ $video->title }}
            </h2>
            <a href="{{ route('customer.videos') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke
                Katalog</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div id="timer-alert"
                class="p-4 mb-4 text-blue-800 border border-blue-300 rounded-lg bg-blue-50 flex items-center justify-between"
                role="alert">
                <div class="flex items-center">
                    <svg class="flex-shrink-0 w-4 h-4 me-2 animate-pulse" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                        <span class="font-medium">Sisa Waktu Akses:</span>
                        <span id="countdown-display" class="font-bold text-lg ml-2">Menghitung...</span>
                    </div>
                </div>
            </div>

            <div class="bg-black rounded-lg overflow-hidden shadow-xl aspect-video flex items-center justify-center">


                @php
                    $url = $video->url;
                    $videoId = null;

                    // Cek Pattern 1: youtube.com/watch?v=ID
                    if (preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $url, $matches)) {
                        $videoId = $matches[1];
                    }
                    // Cek Pattern 2: youtu.be/ID (Short Link)
                    elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
                        $videoId = $matches[1];
                    }
                    // Cek Pattern 3: youtube.com/embed/ID
                    elseif (preg_match('/embed\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
                        $videoId = $matches[1];
                    }
                @endphp

                @if ($videoId)
                    <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $videoId }}?autoplay=1"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                @else
                    <div class="text-white text-center p-4">
                        <p class="text-lg font-bold">Format Video Tidak Didukung</p>
                        <p class="text-sm text-gray-400">URL: {{ $video->url }}</p>
                        <a href="{{ $video->url }}" target="_blank" class="text-blue-400 underline mt-2 block">Buka
                            link asli</a>
                    </div>
                @endif
            </div>

            <div class="mt-6 bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-2">Deskripsi</h3>
                <p class="text-gray-600">{{ $video->description ?? 'Tidak ada deskripsi.' }}</p>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            // sisa waktu dari Controller
            let timeLeft = {{ $remainingSeconds }};

            const displayElement = document.getElementById('countdown-display');
            const alertElement = document.getElementById('timer-alert');

            // format jadi Jam:Menit:Detik
            function formatTime(seconds) {
                if (seconds < 0) return "00:00:00";
                let h = Math.floor(seconds / 3600);
                let m = Math.floor((seconds % 3600) / 60);
                let s = seconds % 60;
                return [h, m, s].map(v => v < 10 ? "0" + v : v).join(":");
            }

            // 2. Interval setiap 1 detik
            const timerInterval = setInterval(function() {
                timeLeft--;

                // Update tampilan
                displayElement.textContent = formatTime(timeLeft);

                if (timeLeft < 300 && timeLeft > 0) {
                    alertElement.classList.remove('text-blue-800', 'border-blue-300', 'bg-blue-50');
                    alertElement.classList.add('text-red-800', 'border-red-300', 'bg-red-50');
                }

                // 3. waktu habis
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    displayElement.textContent = "Waktu Habis! Mengalihkan...";

                    // Redirect paksa kembali ke halaman list
                    window.location.href = "{{ route('customer.videos') }}";
                }
            }, 1000); // 1000ms = 1 detik

            // tampilan awal
            displayElement.textContent = formatTime(timeLeft);
        </script>
    @endpush
</x-app-layout>
