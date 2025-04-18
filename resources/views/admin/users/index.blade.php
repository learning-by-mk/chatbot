@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
    <div class="card">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Danh sách người dùng</h2>
            <a href="{{ route('admin.users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                <i class="fas fa-plus mr-2"></i>Thêm người dùng
            </a>
        </div>

        <div class="mb-4">
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex gap-4">
                <input type="text" name="search" value="{{ request('search') }}" class="border rounded px-4 py-2 flex-1"
                    placeholder="Tìm kiếm theo tên hoặc email...">
                <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    <i class="fas fa-search mr-2"></i>Tìm kiếm
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3 px-4">ID</th>
                        <th class="text-left py-3 px-4">Tên</th>
                        <th class="text-left py-3 px-4">Email</th>
                        <th class="text-left py-3 px-4">Vai trò</th>
                        <th class="text-left py-3 px-4">Ngày tạo</th>
                        <th class="text-left py-3 px-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $user->id }}</td>
                            <td class="py-3 px-4">{{ $user->name }}</td>
                            <td class="py-3 px-4">{{ $user->email }}</td>
                            <td class="py-3 px-4">
                                <span
                                    class="px-2 py-1 rounded-full text-sm
                            {{ $user->isAdmin() ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $user->isAdmin() ? 'Admin' : 'User' }}
                                </span>
                            </td>
                            <td class="py-3 px-4">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- <div class="mt-4">
            {{ $users->links() }}
        </div> --}}
    </div>
    @push('scripts')
        <script>
            console.log(@json($users));
        </script>
    @endpush
@endsection
