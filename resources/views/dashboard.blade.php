@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="statsGrid">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="mx-4">
                        <h2 class="text-2xl font-bold text-gray-700">{{ $totalPosts }}</h2>
                        <p class="text-sm text-gray-500">{{ __('message.Total Posts') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="mx-4">
                        <h2 class="text-2xl font-bold text-gray-700">{{ $scheduledPosts }}</h2>
                        <p class="text-sm text-gray-500">{{ __('message.Scheduled Posts') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="mx-4">
                        <h2 class="text-2xl font-bold text-gray-700">{{ $connectedPlatforms }}</h2>
                        <p class="text-sm text-gray-500">{{ __('message.Connected Platforms') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="mb-4">
                <h3 class="text-lg font-medium text-gray-900">{{ __('message.Daily Posts Limit') }}</h3>
                <p class="text-sm text-gray-500">{{ __('message.posts today') }}: {{ $todayPostsCount }}/10
                </p>
            </div>

            @php
                $progressPercentage = ($todayPostsCount / 10) * 100;
                $progressColor = $progressPercentage >= 80 ? 'red' : ($progressPercentage >= 60 ? 'yellow' : 'green');
            @endphp

            <div class="relative pt-1">
                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-{{$progressColor}}-200">
                    <div style="width:{{$progressPercentage}}%"
                        class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-{{$progressColor}}-500 transition-all duration-500">
                    </div>
                </div>
            </div>

            @if($todayPostsCount >= 8)
                <div class="mt-4 bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                {{ __('message.approaching daily post limit warning') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">{{ __('message.statistics') }}</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-base font-medium text-gray-700 mb-4">{{ __('message.posts_by_platform') }}</h4>
                        <div class="h-64">
                            <canvas id="platformChart"></canvas>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-base font-medium text-gray-700 mb-4">{{ __('message.publish_success_rate') }}</h4>
                        <div class="h-64">
                            <canvas id="successRateChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">{{ __('message.Recent Posts') }}</h3>

                    <div class="flex space-x-4">
                        <select id="statusFilter"
                            class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">{{ __('message.All Statuses') }}</option>
                            <option value="draft">{{ __('message.Draft') }}</option>
                            <option value="scheduled">{{ __('message.Scheduled') }}</option>
                            <option value="published">{{ __('message.Published') }}</option>
                        </select>

                        <div class="flex space-x-2">
                            <input type="date" id="dateFrom"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <input type="date" id="dateTo"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 pt-4 border-b border-gray-200">
                <div class="flex space-x-4">
                    <button id="listViewBtn" class="px-4 py-2 text-sm font-medium text-gray-700 border-b-2 border-blue-500">
                        {{ __('message.List View') }}
                    </button>
                    <button id="calendarViewBtn" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                        {{ __('message.Calendar View') }}
                    </button>
                </div>
            </div>

            <div id="listView" class="p-6">
                @if($recentPosts->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($recentPosts as $post)
                            <div class="py-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-900">{{ $post->title }}</h4>
                                        <p class="mt-1 text-sm text-gray-500">
                                            {{ \Illuminate\Support\Str::limit($post->content, 100) }}
                                        </p>
                                        <div class="mt-2 flex items-center space-x-2">
                                            @if($post->scheduled_time)
                                                <span
                                                    class="text-xs text-gray-500">{{ $post->scheduled_time->format('M d, Y H:i') }}</span>
                                            @endif
                                            <span class="text-xs px-2 inline-flex leading-5 font-semibold rounded-full 
                                                        @if($post->status === 'published') bg-green-100 text-green-800
                                                        @elseif($post->status === 'scheduled') bg-blue-100 text-blue-800
                                                        @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($post->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        @foreach($post->platforms as $platform)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $platform->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-gray-500">{{ __('message.No posts yet') }}</p>
                    </div>
                @endif
            </div>

            <div id="calendarView" class="p-6 hidden">
                <div class="bg-white">
                    <div class="grid grid-cols-7 gap-px border-b border-gray-300">
                        <div class="text-center py-2">{{ __('message.Sun') }}</div>
                        <div class="text-center py-2">{{ __('message.Mon') }}</div>
                        <div class="text-center py-2">{{ __('message.Tue') }}</div>
                        <div class="text-center py-2">{{ __('message.Wed') }}</div>
                        <div class="text-center py-2">{{ __('message.Thu') }}</div>
                        <div class="text-center py-2">{{ __('message.Fri') }}</div>
                        <div class="text-center py-2">{{ __('message.Sat') }}</div>
                    </div>
                    <div id="calendar-grid" class="grid grid-cols-7 gap-px bg-gray-200">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const listViewBtn = document.getElementById('listViewBtn');
                const calendarViewBtn = document.getElementById('calendarViewBtn');
                const listView = document.getElementById('listView');
                const calendarView = document.getElementById('calendarView');
                const statusFilter = document.getElementById('statusFilter');
                const dateFrom = document.getElementById('dateFrom');
                const dateTo = document.getElementById('dateTo');

                // View Toggle
                listViewBtn.addEventListener('click', () => {
                    listView.classList.remove('hidden');
                    calendarView.classList.add('hidden');
                    listViewBtn.classList.add('border-b-2', 'border-blue-500', 'text-gray-700');
                    listViewBtn.classList.remove('text-gray-500');
                    calendarViewBtn.classList.remove('border-b-2', 'border-blue-500', 'text-gray-700');
                    calendarViewBtn.classList.add('text-gray-500');
                    loadPosts();
                });

                calendarViewBtn.addEventListener('click', () => {
                    calendarView.classList.remove('hidden');
                    listView.classList.add('hidden');
                    calendarViewBtn.classList.add('border-b-2', 'border-blue-500', 'text-gray-700');
                    calendarViewBtn.classList.remove('text-gray-500');
                    listViewBtn.classList.remove('border-b-2', 'border-blue-500', 'text-gray-700');
                    listViewBtn.classList.add('text-gray-500');
                    loadCalendarData();
                });

                function loadPosts() {
                    const url = new URL('{{ route("web.dashboard.filter") }}', window.location.origin);
                    url.searchParams.append('status', statusFilter.value);
                    url.searchParams.append('from', dateFrom.value);
                    url.searchParams.append('to', dateTo.value);

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            updatePostsList(data.posts);
                        });
                }

                function loadCalendarData() {
                    const now = new Date();
                    const start = new Date(now.getFullYear(), now.getMonth(), 1);
                    const end = new Date(now.getFullYear(), now.getMonth() + 1, 0);

                    fetch(`{{ route("web.dashboard.calendar") }}?start=${start.toISOString()}&end=${end.toISOString()}`)
                        .then(response => response.json())
                        .then(data => {
                            updateCalendarView(data.posts);
                        });
                }

                function updatePostsList(posts) {
                    const container = document.getElementById('listView');
                    if (posts.length === 0) {
                        container.innerHTML = `
                                                                <div class="text-center py-4">
                                                                    <p class="text-gray-500">{{ __('message.No posts yet') }}</p>
                                                                </div>
                                                            `;
                        return;
                    }

                    const html = posts.map(post => `
                                                            <div class="py-4 border-b border-gray-200">
                                                                <div class="flex justify-between items-start">
                                                                    <div>
                                                                        <h4 class="text-lg font-medium text-gray-900">${post.title}</h4>
                                                                        <p class="mt-1 text-sm text-gray-500">${post.content.substring(0, 100)}...</p>
                                                                        <div class="mt-2 flex items-center space-x-2">                                            ${post.scheduled_time ? `<span class="text-xs text-gray-500">${new Date(post.scheduled_time).toLocaleString()}</span>` : ''}
                                                                            <span class="text-xs px-2 inline-flex leading-5 font-semibold rounded-full 
                                                                                ${post.status === 'published' ? 'bg-green-100 text-green-800' :
                            post.status === 'scheduled' ? 'bg-blue-100 text-blue-800' :
                                'bg-gray-100 text-gray-800'}">
                                                                                ${post.status.charAt(0).toUpperCase() + post.status.slice(1)}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex space-x-2">
                                                                        ${post.platforms.map(platform => `
                                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                                ${platform.name}
                                                                            </span>
                                                                        `).join('')}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        `).join('');

                    container.innerHTML = html;
                }

                function updateCalendarView(posts) {
                    const calendarGrid = document.getElementById('calendar-grid');
                    const now = new Date();
                    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
                    const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
                    const daysInMonth = lastDay.getDate();
                    const startPadding = firstDay.getDay();

                    let html = '';

                    for (let i = 0; i < startPadding; i++) {
                        html += `<div class="h-32 bg-gray-50 p-2"></div>`;
                    }

                    for (let day = 1; day <= daysInMonth; day++) {
                        const date = new Date(now.getFullYear(), now.getMonth(), day);
                        const dayPosts = posts.filter(post => {
                            const postDate = new Date(post.start);
                            return postDate.getDate() === day;
                        });

                        html += `
                                                                <div class="h-32 bg-white p-2 border border-gray-200">
                                                                    <div class="font-semibold text-gray-700 mb-2">${day}</div>
                                                                    ${dayPosts.map(post => `
                                                                        <div class="text-xs mb-1 p-1 rounded bg-blue-100 text-blue-800 truncate">
                                                                            ${post.title}
                                                                        </div>
                                                                    `).join('')}
                                                                </div>
                                                            `;
                    }

                    calendarGrid.innerHTML = html;
                }

                const platformCtx = document.getElementById('platformChart').getContext('2d');
                new Chart(platformCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($platformStats->pluck('name')) !!},
                        datasets: [{
                            label: '{{ __('message.posts_by_platform') }}',
                            data: {!! json_encode($platformStats->pluck('posts_count')) !!},
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.5)',
                                'rgba(255, 99, 132, 0.5)',
                                'rgba(255, 206, 86, 0.5)',
                                'rgba(75, 192, 192, 0.5)',
                                'rgba(153, 102, 255, 0.5)'
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                display: true
                            },
                            y: {
                                display: true,
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });

                const successRateCtx = document.getElementById('successRateChart').getContext('2d');
                new Chart(successRateCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['{{ __('message.published') }}', '{{ __('message.scheduled') }}', '{{ __('message.draft') }}'],
                        datasets: [{
                            data: {!! json_encode($statusStats) !!},
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.8)',
                                'rgba(255, 206, 86, 0.8)',
                                'rgba(255, 99, 132, 0.8)'
                            ],
                            borderColor: [
                                'rgba(75, 192, 192, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(255, 99, 132, 1)'
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: '{{ App::isLocale("ar") ? "left" : "right" }}',
                                display: true,
                                labels: {
                                    padding: 20,
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                enabled: true
                            }
                        },
                        layout: {
                            padding: 20
                        },
                        cutout: '60%',
                        animation: {
                            animateScale: true,
                            animateRotate: true
                        }
                    }
                });

                statusFilter.addEventListener('change', loadPosts);
                dateFrom.addEventListener('change', loadPosts);
                dateTo.addEventListener('change', loadPosts);

                loadPosts();
            });
        </script>
    @endpush
@endsection