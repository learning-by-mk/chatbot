<x-chat-layout>
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl overflow-hidden">
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
            </div>

            <!-- Chat Input -->
            <div class="border-t p-4 bg-white flex justify-between items-center">
                <button type="button"
                    class="right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600  mr-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                </button>
                <form id="chat-form" class="flex items-center space-x-2 flex-1">
                    @csrf
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

    @push('scripts')
        <script>
            let chatId = 0;
            document.addEventListener('DOMContentLoaded', function() {
                const chatContainer = document.getElementById('chat-container');
                const chatForm = document.getElementById('chat-form');
                const messageInput = document.getElementById('message-input');

                function createTypingIndicator() {
                    const indicator = document.createElement('div');
                    indicator.className = 'typing-indicator';
                    for (let i = 0; i < 3; i++) {
                        const dot = document.createElement('div');
                        dot.className = 'typing-dot';
                        indicator.appendChild(dot);
                    }
                    return indicator;
                }

                function typeMessage(message, isUser = false) {
                    const messageDiv = document.createElement('div');
                    messageDiv.className = `flex ${isUser ? 'justify-end' : 'justify-start'}`;

                    const bubble = document.createElement('div');
                    bubble.className =
                        `chat-bubble ${isUser ? 'user-bubble bg-blue-500 text-white' : 'bot-bubble bg-gray-200 text-gray-800'} rounded-2xl px-4 py-2`;

                    messageDiv.appendChild(bubble);
                    chatContainer.appendChild(messageDiv);

                    let i = 0;
                    const typingInterval = setInterval(() => {
                        if (i < message.length) {
                            bubble.textContent += message.charAt(i);
                            i++;
                            chatContainer.scrollTop = chatContainer.scrollHeight;
                        } else {
                            clearInterval(typingInterval);
                        }
                    }, 30);
                }

                chatForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const message = messageInput.value.trim();
                    if (message) {
                        typeMessage(message, true);
                        messageInput.value = '';

                        // Show typing indicator
                        const typingDiv = document.createElement('div');
                        typingDiv.className = 'flex justify-start';
                        typingDiv.appendChild(createTypingIndicator());
                        chatContainer.appendChild(typingDiv);
                        chatContainer.scrollTop = chatContainer.scrollHeight;

                        // Simulate bot response after a delay
                        setTimeout(() => {
                            callbackApi(message).then(data => {
                                typingDiv.remove();
                                console.log(data);
                                // typeMessage(data.choices[0].message.content);
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
