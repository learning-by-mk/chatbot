@extends('layouts.admin')

@section('title', 'Thêm người dùng mới')

@section('content')
    <div class="card">
        <div class="mb-6">
            <h2 class="text-xl font-semibold">Thêm người dùng mới</h2>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST">


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Tên người dùng
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="border rounded w-full px-4 py-2 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="border rounded w-full px-4 py-2 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Mật khẩu
                    </label>
                    <input type="password" name="password" id="password"
                        class="border rounded w-full px-4 py-2 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirmation">
                        Xác nhận mật khẩu
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="border rounded w-full px-4 py-2">
                    @error('password_confirmation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="role">
                        Vai trò
                    </label>
                    <select name="role" id="role"
                        class="border rounded w-full px-4 py-2 @error('role') border-red-500 @enderror">
                        <option value="" disabled selected>Chọn vai trò</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex gap-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    <i class="fas fa-save mr-2"></i>Lưu
                </button>
                <a href="{{ route('admin.users.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    <i class="fas fa-times mr-2"></i>Hủy
                </a>
            </div>
        </form>
    </div>
@endsection
