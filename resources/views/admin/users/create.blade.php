<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Customer Baru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow sm:rounded-lg">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Nama</label>
                    <input type="text" name="name" required
                        class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                    <input type="email" name="email" required
                        class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                    <input type="password" name="password" required
                        class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                        class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5">
                </div>
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
            </form>
        </div>
    </div>
</x-app-layout>
