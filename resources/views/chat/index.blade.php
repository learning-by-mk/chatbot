<?php
$sending = false;
?>

<x-chat-layout>
    <div class="flex h-screen bg-gray-100">
        <!-- Left Sidebar - Chat History -->
        <div class="w-1/5 bg-white border-r border-gray-200" style="min-width: 280px;">
            <div class="p-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Lịch sử chat</h2>
            </div>
            <div class="p-4 border-b border-gray-200">
                <a href="{{ route('chat.chat') }}" class="text-white bg-blue-500 rounded-lg p-2">
                    + Tạo chat mới
                </a>
            </div>

            <div class="overflow-y-auto h-[calc(100vh-4rem)]"
                style="scrollbar-width: thin; scrollbar-color: #d1d5db #f3f4f6; height: calc(100vh - 4rem);">
                <div class="p-4 space-y-4 ">
                    @foreach ($chats as $chatItem)
                        <a href="{{ route('chat.chat', $chatItem->uuid) }}">
                            <div
                                class="bg-gray-50 rounded-lg p-2 hover:bg-gray-100 transition-colors duration-200 cursor-pointer">
                                <div class="mb-2">
                                    <p class="text-sm font-medium text-gray-900 mb-1">{{ $chatItem->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $chatItem->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="w-4/5 flex-1 flex flex-col">
            <div class="flex-1 overflow-y-auto p-4">
                <div class="w-full max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
                    <!-- Chat Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4 text-white">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold">Chat Assistant</h2>
                                <p class="text-sm text-blue-100">Online</p>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Messages -->
                    <div id="chat-container" class="h-[500px] overflow-y-auto p-4 space-y-4 bg-gray-50">
                        <!-- Messages will appear here -->
                        @if ($chat)
                            @foreach ($chat->messages as $message)
                                @if ($message['role'] === 'user')
                                    <div class="flex justify-end">
                                        <div class="bg-blue-500 text-white rounded-lg px-4 py-2">
                                            {{ $message['content'] }}
                                        </div>
                                    </div>
                                @elseif ($message['role'] === 'assistant')
                                    <div class="flex justify-start">
                                        <div class="bg-gray-200 text-gray-800 rounded-lg px-4 py-2">
                                            <div class="prose prose-sm max-w-none tex2jax_process">
                                                @php
                                                    $content = $message['content'];
                                                    // Phân tách nội dung thành các phần dựa trên dấu hiệu đặc biệt
                                                    $parts = preg_split(
                                                        '/(```[\s\S]*?```|\[\[[\s\S]*?\]\]|\[[\s\S]*?\])/',
                                                        $content,
                                                        -1,
                                                        PREG_SPLIT_DELIM_CAPTURE,
                                                    );

                                                    foreach ($parts as $part) {
                                                        if (
                                                            preg_match('/^```(\w+)?\n([\s\S]*?)```$/', $part, $matches)
                                                        ) {
                                                            // Xử lý code block
                                                            $language = $matches[1] ?? '';
                                                            $code = htmlspecialchars($matches[2]);
                                                            echo "<pre><code class=\"language-{$language}\">{$code}</code></pre>";
                                                        } elseif (
                                                            preg_match('/^\[\[([\s\S]*?)\]\]$/', $part, $matches)
                                                        ) {
                                                            // Xử lý công thức toán học dạng display
                                                            echo "\[{$matches[1]}\]";
                                                        } elseif (preg_match('/^\[([\s\S]*?)\]$/', $part, $matches)) {
                                                            // Xử lý công thức toán học dạng inline
                                                            echo "\({$matches[1]}\)";
                                                        } else {
                                                            // Xử lý text thông thường
                                                            echo nl2br(e($part));
                                                        }
                                                    }
                                                @endphp
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>

                    <!-- Chat Input -->
                    <div class="border-t p-4 bg-white flex justify-between items-center">
                        <button type="button"
                            class="right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 mr-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                        </button>
                        <form id="chat-form" class="flex items-center space-x-2 flex-1">

                            <div class="flex-1 relative">
                                <input type="text" id="message-input"
                                    class="w-full px-4 py-3 rounded-full border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Nhập tin nhắn...">
                            </div>
                            <button type="submit"
                                class="p-3 rounded-full bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Katex: Công cụ hiển thị công thức toán học --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css"
            integrity="sha384-GvrOXuhMATgEsSwCs4smul74iXGOixntILdUW9XmUC6+HX0sLNAK3q71HotJqlAn" crossorigin="anonymous">
        <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.js"
            integrity="sha384-cpW21h6RZv/phavutF+AuVYrr+dA8xD9zs6FwLpaCct6O9ctzYFfFr4dgmgccOTx" crossorigin="anonymous">
        </script>
        <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/contrib/auto-render.min.js"
            integrity="sha384-+VBxd3r6XgURycqtZ117nYw44OOcIax56Z4dCRWbxyPt0Koah1uHoK0o4+/RRE05" crossorigin="anonymous">
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                renderMathInElement(document.body, {
                    // Các tùy chọn rendering
                    delimiters: [{
                            left: '$$',
                            right: '$$',
                            display: true
                        },
                        {
                            left: '$',
                            right: '$',
                            display: false
                        },
                        {
                            left: '\\(',
                            right: '\\)',
                            display: false
                        },
                        {
                            left: '\\[',
                            right: '\\]',
                            display: true
                        }
                    ],
                    throwOnError: false
                });
            });
        </script>
        {{-- Highlight.js: Công cụ hiển thị code --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/github.min.css">

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Highlight tất cả các block code
                document.querySelectorAll('pre code').forEach((block) => {
                    hljs.highlightElement(block);
                });
            });
        </script>


        <script>
            function scrollToBottom() {
                const container = document.getElementById('chat-container');
                container.scrollTop = container.scrollHeight;
            }

            // Scroll xuống dưới cùng khi trang tải xong
            document.addEventListener('DOMContentLoaded', scrollToBottom);

            // Khởi tạo highlight.js
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('pre code').forEach((block) => {
                    hljs.highlightElement(block);
                });
            });

            // Hàm copy code
            function copyCode(button) {
                const codeBlock = button.closest('.code-container').querySelector('code');
                const text = codeBlock.textContent;

                navigator.clipboard.writeText(text).then(() => {
                    // Đổi text của button
                    const originalText = button.textContent;
                    button.textContent = 'Copied!';

                    // Trở lại text ban đầu sau 2 giây
                    setTimeout(() => {
                        button.textContent = originalText;
                    }, 2000);
                });
            }
        </script>
        <script>
            console.log('Chat data in view:', @json($chat));
            let chatId = {{ $chat->id ?? 0 }};
            let sending = false;
            document.addEventListener('DOMContentLoaded', function() {
                const chatContainer = document.getElementById('chat-container');
                const chatForm = document.getElementById('chat-form');
                const messageInput = document.getElementById('message-input');
                const submitButton = chatForm.querySelector('button[type="submit"]');

                function updateSubmitButton() {
                    submitButton.disabled = sending;
                    if (sending) {
                        submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    } else {
                        submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                }

                function createTypingIndicator() {
                    const indicator = document.createElement('div');
                    indicator.className = 'typing-indicator flex space-x-1';
                    for (let i = 0; i < 3; i++) {
                        const dot = document.createElement('div');
                        dot.className = 'w-2 h-2 bg-gray-400 rounded-full animate-bounce';
                        dot.style.animationDelay = `${i * 0.2}s`;
                        indicator.appendChild(dot);
                    }
                    return indicator;
                }

                function typeMessage(message, isUser = false) {
                    const messageDiv = document.createElement('div');
                    messageDiv.className = `flex ${isUser ? 'justify-end' : 'justify-start'} mb-4`;

                    const bubble = document.createElement('div');
                    bubble.className =
                        `max-w-[70%] ${isUser ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'} rounded-2xl px-4 py-2 shadow-sm`;

                    messageDiv.appendChild(bubble);
                    chatContainer.appendChild(messageDiv);


                    if (isUser) {
                        bubble.textContent = message;
                    } else {
                        let i = 0;
                        const typingInterval = setInterval(() => {
                            if (i < message.length) {
                                bubble.textContent += message.charAt(i);
                                i++;
                                chatContainer.scrollTop = chatContainer.scrollHeight;
                            } else {
                                sending = false;
                                updateSubmitButton();
                                clearInterval(typingInterval);
                            }
                        }, 10);
                    }
                }

                chatForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (sending) return;

                    const message = messageInput.value.trim();
                    if (message) {
                        sending = true;
                        updateSubmitButton();

                        typeMessage(message, true);
                        messageInput.value = '';

                        const typingDiv = document.createElement('div');
                        typingDiv.className = 'flex justify-start mb-4';
                        typingDiv.appendChild(createTypingIndicator());
                        chatContainer.appendChild(typingDiv);
                        chatContainer.scrollTop = chatContainer.scrollHeight;

                        setTimeout(() => {
                            callbackApi(message).then(data => {
                                typingDiv.remove();
                                typeMessage(data?.chat?.messages[data?.chat?.messages?.length -
                                    1]?.content);
                            }).catch(error => {
                                sending = false;
                                updateSubmitButton();
                                console.error('Error:', error);
                            });
                        }, 1500);
                    }
                });

                const callbackApi = async (message) => {
                    try {
                        const response = await fetch(`/chat/${chatId}/message`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify({
                                message: message
                            })
                        });

                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        const data = await response.json();
                        chatId = data?.chat?.id;
                        return data;
                    } catch (error) {
                        console.error('Error:', error);
                        throw error;
                    }
                }
            });
        </script>
    @endpush
</x-chat-layout>
