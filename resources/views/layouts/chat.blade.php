<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .chat-bubble {
            position: relative;
            max-width: 80%;
        }

        .chat-bubble::before {
            content: '';
            position: absolute;
            width: 0;
            height: 0;
            border-style: solid;
        }

        .user-bubble::before {
            border-width: 8px 0 8px 8px;
            border-color: transparent transparent transparent #3b82f6;
            right: -8px;
            top: 12px;
        }

        .bot-bubble::before {
            border-width: 8px 8px 8px 0;
            border-color: transparent #e5e7eb transparent transparent;
            left: -8px;
            top: 12px;
        }

        .typing-indicator {
            display: flex;
            gap: 4px;
            padding: 8px;
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            background-color: #9ca3af;
            border-radius: 50%;
            animation: typing 1s infinite;
        }

        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }


        .code-container {
            background: #f5f5f5;
            border-radius: 6px;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .code-header {
            display: flex;
            justify-content: space-between;
            padding: 10px 15px;
            background: #e0e0e0;
            border-top-left-radius: 6px;
            border-top-right-radius: 6px;
            border-bottom: 1px solid #ccc;
        }

        .language-badge {
            background: #3490dc;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        .copy-btn {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 2px 10px;
            cursor: pointer;
            font-size: 12px;
        }

        .copy-btn:hover {
            background: #f0f0f0;
        }

        pre {
            margin: 0;
            padding: 15px;
            overflow-x: auto;
            font-size: 14px;
            line-height: 1.5;
        }

        code {
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', 'Consolas', monospace;
        }

        @keyframes typing {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100">
    {{ $slot }}
    @stack('scripts')
</body>

</html>
