@extends('layouts.admin')

@section('title', 'Thêm người dùng mới')

@section('content')
    <div class="card">
        <div class="mb-6">
            <h2 class="text-xl font-semibold">Thêm bài viết mới</h2>
        </div>

        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                        Tiêu đề
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                        class="border rounded w-full px-4 py-2 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="content">
                        Nội dung
                    </label>
                    <textarea name="content" id="content"
                        class="border rounded w-full px-4 py-2 @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
                        Hình ảnh
                    </label>
                    <input type="file" name="image" id="image"
                        class="border rounded w-full px-4 py-2 @error('image') border-red-500 @enderror">
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="category">
                        Danh mục
                    </label>
                    <input type="text" name="category" id="category" value="{{ old('category') }}"
                        class="border rounded w-full px-4 py-2 @error('category') border-red-500 @enderror">
                    @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                        Trạng thái
                    </label>
                    <input type="text" name="status" id="status" value="{{ old('status') }}"
                        class="border rounded w-full px-4 py-2 @error('status') border-red-500 @enderror">
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="tags">
                        Tags
                    </label>
                    <input type="text" name="tags" id="tags" value="{{ old('tags') }}"
                        class="border rounded w-full px-4 py-2 @error('tags') border-red-500 @enderror">
                    @error('tags')
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
