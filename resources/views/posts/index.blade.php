@extends('layouts.app')

@section('content')
    <div class="py-6" dir="{{ App::isLocale('ar') ? 'rtl' : 'ltr' }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">{{ __('message.posts') }}</h1>
                <a href="{{ route('web.posts.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="{{ App::isLocale('ar') ? 'ml-2' : '-ml-1 mr-2' }} h-5 w-5"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('message.create_new_post') }}
                </a>
            </div>

            <div class="bg-white shadow rounded-lg mb-6 p-4">
                <form action="{{ route('web.posts.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="status"
                            class="block text-sm font-medium text-gray-700">{{ __('message.status') }}</label>
                        <select name="status" id="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">{{ __('message.all_statuses') }}</option>
                            @foreach(\App\Enums\PostStatus::cases() as $status)
                                <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                                    {{ __('message.' . strtolower($status->name)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="platform"
                            class="block text-sm font-medium text-gray-700">{{ __('message.platform') }}</label>
                        <select name="platform" id="platform"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">{{ __('message.all_platforms') }}</option>
                            @foreach($platforms as $platform)
                                <option value="{{ $platform->id }}" {{ request('platform') == $platform->id ? 'selected' : '' }}>
                                    {{ $platform->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label hidden for="date" class="block text-sm font-medium text-gray-700"></label>

                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="{{ App::isLocale('ar') ? 'ml-2' : '-ml-1 mr-2' }} h-5 w-5"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            {{ __('message.filter') }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @foreach(['image', 'title', 'content', 'scheduled_time', 'platforms', 'status', 'actions'] as $header)
                                    <th scope="col"
                                        class="px-6 py-3 text-{{ App::isLocale('ar') ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('message.' . $header) }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($posts as $post)
                                <tr>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-{{ App::isLocale('ar') ? 'right' : 'left' }}">
                                        @if($post->image_url)
                                            <img src="{{ asset($post->image_url) }}" alt="Post image"
                                                class="h-10 w-10 rounded-md object-cover {{ App::isLocale('ar') ? 'ml-3' : 'mr-3' }}">
                                        @else
                                            <span class="text-gray-500">{{ '-' }}</span>
                                        @endif
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-{{ App::isLocale('ar') ? 'right' : 'left' }}">
                                        {{ $post->title }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-normal text-sm text-gray-900 text-{{ App::isLocale('ar') ? 'right' : 'left' }}">
                                        <div class="flex items-center {{ App::isLocale('ar') ? 'flex-row-reverse' : '' }}">
                                            <div class="max-w-xs">
                                                {{ Str::limit($post->content, 50) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $post->scheduled_time ? $post->scheduled_time->format('Y-m-d H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($post->platforms as $platform)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $platform->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($post->status === 'published')
                                            bg-green-100 text-green-800
                                        @elseif($post->status === 'scheduled')
                                            bg-yellow-100 text-yellow-800
                                        @elseif($post->status === 'draft')
                                            bg-red-100 text-red-800
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif">
                                            {{ __('message.' . strtolower($post->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('web.posts.edit', $post) }}"
                                                class="text-blue-600 hover:text-blue-900">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('web.posts.destroy', $post) }}" method="POST"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE') <button type="button" class="text-red-600 hover:text-red-900"
                                                    onclick="confirmDelete(this)">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        {{ __('message.no_posts_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete(button) {
            const form = button.closest('form');

            Swal.fire({
                title: '{{ __("message.confirm_delete_title") }}',
                text: '{{ __("message.confirm_delete") }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: '{{ __("message.yes_delete") }}',
                cancelButtonText: '{{ __("message.no_cancel") }}',
                reverseButtons: {{ App::isLocale('ar') ? 'true' : 'false' }},
                focusCancel: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

    </script>
@endpush