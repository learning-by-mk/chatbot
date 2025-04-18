@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm">Tổng số người dùng</h3>
                    <p class="text-2xl font-bold">{{ $totalUsers }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-users text-blue-500 text-xl"></i>
                </div>
            </div>
        </div>

        {{-- <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm">Người dùng mới hôm nay</h3>
                    <p class="text-2xl font-bold">{{ $newUsersToday }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-user-plus text-green-500 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm">Tổng số bài viết</h3>
                    <p class="text-2xl font-bold">{{ $totalPosts }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-file-alt text-purple-500 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm">Bài viết mới hôm nay</h3>
                    <p class="text-2xl font-bold">{{ $newPostsToday }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-plus-circle text-yellow-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div> --}}

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <div class="card">
                <h3 class="text-lg font-semibold mb-4">Người dùng mới nhất</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Tên</th>
                                <th class="text-left py-2">Email</th>
                                <th class="text-left py-2">Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($latestUsers as $user)
                                <tr class="border-b">
                                    <td class="py-2">{{ $user->name }}</td>
                                    <td class="py-2">{{ $user->email }}</td>
                                    <td class="py-2">{{ $user->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- <div class="card">
            <h3 class="text-lg font-semibold mb-4">Bài viết mới nhất</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Tiêu đề</th>
                            <th class="text-left py-2">Tác giả</th>
                            <th class="text-left py-2">Ngày đăng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestPosts as $post)
                            <tr class="border-b">
                                <td class="py-2">{{ $post->title }}</td>
                                <td class="py-2">{{ $post->user->name }}</td>
                                <td class="py-2">{{ $post->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div> --}}
        </div>
    @endsection
