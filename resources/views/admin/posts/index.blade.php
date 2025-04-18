@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
    <div class="card">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Danh sách bài viết</h2>
            <a href="{{ route('admin.posts.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                <i class="fas fa-plus mr-2"></i>Thêm bài viết
            </a>
        </div>

        <div class="mb-4">
            <form action="{{ route('admin.posts.index') }}" method="GET" class="flex gap-4">
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
                        <th class="text-left py-3 px-4">Tiêu đề</th>
                        <th class="text-left py-3 px-4">Nội dung</th>
                        <th class="text-left py-3 px-4">Hình ảnh</th>
                        <th class="text-left py-3 px-4">Danh mục</th>
                        <th class="text-left py-3 px-4">Trạng thái</th>
                        <th class="text-left py-3 px-4">Tags</th>
                        <th class="text-left py-3 px-4">Người tạo</th>
                        <th class="text-left py-3 px-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $post->id }}</td>
                            <td class="py-3 px-4">{{ $post->title }}</td>
                            <td class="py-3 px-4">{{ $post->content }}</td>
                            <td class="py-3 px-4">
                                <span
                                    class="px-2 py-1 rounded-full text-sm
                            {{ $post->status ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $post->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="py-3 px-4">{{ $post->tags }}</td>
                            <td class="py-3 px-4">{{ $post->user->name }}</td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.posts.edit', $post) }}"
                                        class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
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
            console.log(@json($posts));
        </script>
    @endpush
@endsection
