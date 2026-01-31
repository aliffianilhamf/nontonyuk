<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                    <span class="font-medium">Berhasil!</span> {{ session('success') }}
                </div>
            @endif
            {{-- kelola User --}}
            <div class="mb-6">
                <a href="{{ route('users.index') }}"
                    class="text-white bg-gray-800 hover:bg-gray-900 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                    Kelola Data Customer
                </a>
            </div>
            {{-- Form Tambah Video --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Video Baru</h3>
                <form action="{{ route('videos.store') }}" method="POST" class="flex gap-4 items-end">
                    @csrf
                    <div class="flex-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Judul Video</label>
                        <input type="text" name="title" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900">URL Youtube</label>
                        <input type="text" name="url" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Simpan</button>
                </form>
            </div>

            {{-- CRUD Video --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Video</h3>
                <div class="relative overflow-x-auto border sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Judul</th>
                                <th class="px-6 py-3">URL</th>
                                <th class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($videos as $vid)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $vid->title }}</td>
                                    <td class="px-6 py-4 truncate max-w-xs">{{ $vid->url }}</td>
                                    <td class="px-6 py-4 flex gap-2">
                                        {{-- Edit --}}
                                        <a href="{{ route('videos.edit', $vid->id) }}"
                                            class="font-medium text-blue-600 hover:underline">Edit</a>

                                        {{-- Delete --}}
                                        <form action="{{ route('videos.destroy', $vid->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus video ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="font-medium text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Request User --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Permintaan Akses Masuk</h3>

                <div class="relative overflow-x-auto sm:rounded-lg border">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Tanggal Request</th>
                                <th scope="col" class="px-6 py-3">Customer</th>
                                <th scope="col" class="px-6 py-3">Video yang Diminta</th>
                                <th scope="col" class="px-6 py-3">Status Saat Ini</th>
                                <th scope="col" class="px-6 py-3">Aksi (Set Durasi)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $req)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $req->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $req->user->name }}</td>
                                    <td class="px-6 py-4">{{ $req->video->title }}</td>
                                    <td class="px-6 py-4">
                                        @if ($req->status == 'pending')
                                            <span
                                                class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded">Pending</span>
                                        @elseif($req->status == 'approved')
                                            <span
                                                class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded">Approved</span>
                                            <br><small class="text-gray-400">Sampai:
                                                {{ $req->expires_at->format('d M H:i') }}</small>
                                        @else
                                            <span
                                                class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded">{{ ucfirst($req->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($req->status == 'pending' || $req->status == 'expired')
                                            <div class="flex justify-start items-center gap-2">
                                                {{-- Form approval, input durasi --}}
                                                <form action="{{ route('admin.approve', $req->id) }}" method="POST"
                                                    class="flex items-center gap-2">
                                                    @csrf
                                                    <div class="relative w-24">
                                                        <input type="number" name="duration" min="1"
                                                            value="2" required
                                                            class="block p-2 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-lg border-s-gray-50 border-s-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                                            placeholder="Menit">
                                                        <span
                                                            class="absolute top-0 end-0 p-2 text-sm text-gray-400">Menit</span>
                                                    </div>
                                                    <button type="submit"
                                                        class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-xs px-3 py-2">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.reject', $req->id) }}" method="POST"
                                                    onsubmit="return confirm('Tolak permintaan akses ini?');">
                                                    @csrf
                                                    <button type="submit"
                                                        class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-2">
                                                        Reject
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-xs">Belum ada request masuk!</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada permintaan
                                        akses.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
