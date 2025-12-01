@extends('layouts.app')

@section('title', 'Kelola Akun - DailyMoo')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm uppercase tracking-widest text-primary font-semibold">Superadmin</p>
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">Kelola Akun</h1>
                    <p class="text-gray-600 mt-1">Kelola akun pegawai (akun pembeli hanya bisa dikelola via phpMyAdmin)</p>
                </div>
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        {{-- Form Tambah Akun --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8" x-data="{ showForm: false }">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Tambah Akun Baru</h2>
                <button @click="showForm = !showForm" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                    <span x-show="!showForm">+ Tambah Akun</span>
                    <span x-show="showForm">Tutup</span>
                </button>
            </div>

            <form x-show="showForm" 
                  x-transition
                  action="{{ route('account.store') }}" 
                  method="POST" 
                  class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input type="text" name="username" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                               value="{{ old('username') }}">
                        @error('username', 'accountStore')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                               value="{{ old('email') }}">
                        @error('email', 'accountStore')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" required minlength="8"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                        @error('password', 'accountStore')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select name="role" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary bg-gray-100"
                                disabled>
                            <option value="pegawai" selected>Pegawai</option>
                        </select>
                        <input type="hidden" name="role" value="pegawai">
                        <p class="text-xs text-gray-500 mt-1">Akun pembeli hanya bisa dibuat via phpMyAdmin</p>
                        @error('role', 'accountStore')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                        Simpan Akun
                    </button>
                </div>
            </form>
        </div>

        {{-- Daftar Akun --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Daftar Akun</h2>

            @if($users->isEmpty())
                <div class="text-center py-12">
                    <p class="text-gray-500">Belum ada akun pegawai.</p>
                    <p class="text-sm text-gray-400 mt-2">Akun pembeli hanya bisa dikelola via phpMyAdmin</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr x-data="{ editing: false }" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div x-show="!editing" class="text-sm font-medium text-gray-900">{{ $user->username }}</div>
                                        <input x-show="editing" 
                                               type="text" 
                                               x-ref="usernameInput"
                                               value="{{ $user->username }}"
                                               class="w-full px-3 py-1 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div x-show="!editing" class="text-sm text-gray-500">{{ $user->email }}</div>
                                        <input x-show="editing" 
                                               type="email" 
                                               x-ref="emailInput"
                                               value="{{ $user->email }}"
                                               class="w-full px-3 py-1 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div x-show="!editing">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                {{ $user->role === 'pegawai' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </div>
                                        <select x-show="editing" 
                                                x-ref="roleInput"
                                                class="w-full px-3 py-1 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary bg-gray-100"
                                                disabled>
                                            <option value="pegawai" selected>Pegawai</option>
                                        </select>
                                        <input type="hidden" x-ref="roleInput" value="pegawai">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div x-show="!editing" class="flex items-center gap-2">
                                            <button @click="editing = true" 
                                                    class="text-primary hover:text-primary-dark">
                                                Edit
                                            </button>
                                            <form action="{{ route('account.destroy', $user) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                        <div x-show="editing" class="flex flex-col gap-2">
                                            <form action="{{ route('account.update', $user) }}" method="POST" class="inline-flex items-center gap-2">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="username" :value="$refs.usernameInput.value">
                                                <input type="hidden" name="email" :value="$refs.emailInput.value">
                                                <input type="hidden" name="role" :value="$refs.roleInput.value">
                                                <button type="submit" class="px-3 py-1 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">
                                                    Simpan
                                                </button>
                                            </form>
                                            @error('username', 'accountUpdate')
                                                <p class="text-red-500 text-xs">{{ $message }}</p>
                                            @enderror
                                            @error('email', 'accountUpdate')
                                                <p class="text-red-500 text-xs">{{ $message }}</p>
                                            @enderror
                                            @error('role', 'accountUpdate')
                                                <p class="text-red-500 text-xs">{{ $message }}</p>
                                            @enderror
                                            <button @click="editing = false" class="text-gray-600 hover:text-gray-800 text-sm">
                                                Batal
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

