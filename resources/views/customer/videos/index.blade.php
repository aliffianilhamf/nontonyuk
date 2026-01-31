<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Katalog Video') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                    {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">{{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($videos as $video)
                    <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow flex flex-col">
                        <div class="p-5 flex-1">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">{{ $video->title }}</h5>
                            <p class="mb-3 font-normal text-gray-700 truncate">
                                {{ $video->description ?? 'Tidak ada deskripsi' }}</p>
                        </div>

                        <div class="p-5 border-t bg-gray-50 rounded-b-lg">
                            @php
                                // permission untuk video ini dari data controller
                                $perm = $myPermissions[$video->id] ?? null;
                            @endphp

                            {{-- Belum pernah request --}}
                            @if (!$perm)
                                <form action="{{ route('customer.request', $video->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                        Minta Akses Tonton
                                    </button>
                                </form>

                                {{-- Sudah request, masih Pending --}}
                            @elseif($perm->status == 'pending')
                                <button disabled
                                    class="w-full text-white bg-yellow-400 cursor-not-allowed font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                    <svg class="inline w-4 h-4 me-2 animate-spin" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 4V2M12 20V22M4 12H2M22 12H20M19.07 4.93L17.66 6.34M6.34 17.66L4.93 19.07M19.07 19.07L17.66 17.66M6.34 6.34L4.93 4.93"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    Menunggu Persetujuan
                                </button>

                                {{-- Approved & Waktu Masih Valid  --}}
                            @elseif($perm->is_accessible)
                                <a href="{{ route('customer.watch', $video->id) }}"
                                    class="block w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                    <svg class="inline w-4 h-4 me-2" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                    </svg>
                                    Tonton Video Sekarang
                                </a>
                                <small class="text-green-700 block text-center mt-2">Berlaku s/d
                                    {{ $perm->expires_at->format('H:i d/M') }}</small>

                                {{-- Expired  --}}
                            @else
                                <form action="{{ route('customer.request', $video->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                        @if ($perm->status == 'rejected')
                                            Request Ditolak
                                        @else
                                            Akses Habis
                                        @endif
                                        - Request Ulang
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 col-span-3 text-center">Belum ada video yang tersedia.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
