<div id="floating-chatbot" class="fixed bottom-4 right-4 z-50">
    <button id="chat-icon" class="bg-primary text-primary-foreground rounded-full w-12 h-12 shadow-lg flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" aria-label="Open chat">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
    </button>
    <div id="chat-window" class="hidden bg-white rounded-lg shadow-xl w-96 h-96 fixed bottom-16 right-0 transition-all duration-500 ease-in-out flex flex-col mr-5">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold">AI-Lumni</h3>
            <button id="fullscreen-chat" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" aria-label="Fullscreen chat">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6v6h6M20 18v-6h-6M4 18l6-6M20 6l-6 6" />
                </svg>
            </button>
        </div>
        <div id="chat-messages" class="flex-grow overflow-y-auto p-4 space-y-4 bg-gray-300"></div>
        <div id="thinking-indicator" class="hidden text-blue-500 font-bold animate-pulse bg-transparent">AI-Lumni is thinking...</div>
        <form id="chat-form" class="p-4 border-t flex items-center space-x-2">
            <input type="text" id="user-input" class="flex-grow rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" placeholder="Type your message..." required>
            <button type="submit" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">Send</button>
        </form>
    </div>
    <!-- Dark Background Overlay -->
    <div id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-all duration-500 z-40"></div>
</div>

<style>
    /* Scaled fullscreen styling */
    #chat-window.scaled-fullscreen {
        width: 80vw;
        height: 80vh;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        position: fixed;
        z-index: 50;
        border-radius: 1rem;
    }
    /* Message container height when scaled up */
    #chat-messages {
        flex: 1; /* Expands to fill the available space */
        overflow-y: auto; /* Enables vertical scrolling for messages */
        padding: 1rem;
        background-color: #f9f9f9;
    }

    /* Form area always sticks to the bottom */
    #chat-form {
        border-top: 1px solid #ddd;
        background-color: white;
    }

    #user-input {
        flex-grow: 1;
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-right: 0.5rem;
    }

    #chat-form button {
        padding: 0.5rem 1rem;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    #chat-form button:hover {
        background-color: #0056b3;
    }

    /* Overlay */
    #overlay {
        z-index: 40;
    }

    /* Overlay visible state */
    #overlay.visible {
        display: block;
    }

    /* New style for thinking indicator */
    #thinking-indicator {
        padding: 0 !important; /* Remove padding */
        margin: 0 !important;  /* Remove margins */
        border: none !important; /* Ensure no borders */
        background-color: transparent !important; /* Fully transparent */
    }


    @keyframes blink {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const chatIcon = document.getElementById('chat-icon');
    const chatWindow = document.getElementById('chat-window');
    const chatForm = document.getElementById('chat-form');
    const userInput = document.getElementById('user-input');
    const chatMessages = document.getElementById('chat-messages');
    const overlay = document.getElementById('overlay');
    const fullscreenChat = document.getElementById('fullscreen-chat');
    const thinkingIndicator = document.getElementById('thinking-indicator');

    let chatHistory = [];

    // Load chat history from localStorage
    function loadChatHistory() {
        const storedHistory = localStorage.getItem('chatHistory');
        chatMessages.innerHTML = ''; // Clear existing chat messages
        if (storedHistory) {
            chatHistory = JSON.parse(storedHistory);
            const oneHourAgo = Date.now() - 60 * 60 * 1000;
            chatHistory = chatHistory.filter(msg => msg.timestamp > oneHourAgo);
            chatHistory.forEach(msg => addMessage(msg.role, msg.content, false));
        }
        scrollToBottom(); // Ensure the chat is scrolled to the bottom
    }

    // Save chat history to localStorage
    function saveChatHistory() {
        localStorage.setItem('chatHistory', JSON.stringify(chatHistory));
    }

    // Scroll chat to the bottom
    function scrollToBottom() {
        chatMessages.scrollTo({
            top: chatMessages.scrollHeight,
            behavior: 'smooth'
        });
    }

    // Load chat history on page load
    loadChatHistory();

    // Open/close chat window
    chatIcon.addEventListener('click', () => {
        chatWindow.classList.toggle('hidden');
        if (!chatWindow.classList.contains('hidden')) {
            scrollToBottom(); // Scroll to the bottom when the chat window is opened
        }
    });

    // Toggle fullscreen/scaled-up view
    fullscreenChat.addEventListener('click', toggleFullscreen);
    overlay.addEventListener('click', toggleFullscreen);

    function toggleFullscreen() {
        const isScaled = chatWindow.classList.contains('scaled-fullscreen');
        chatWindow.classList.toggle('scaled-fullscreen', !isScaled);
        overlay.classList.toggle('visible', !isScaled);
        if (!isScaled) {
            updateFullscreenSize();
            window.addEventListener('resize', updateFullscreenSize);
        } else {
            chatWindow.style.width = '';
            chatWindow.style.height = '';
            window.removeEventListener('resize', updateFullscreenSize);
        }
    }

    function updateFullscreenSize() {
        if (chatWindow.classList.contains('scaled-fullscreen')) {
            chatWindow.style.width = `${window.innerWidth * 0.8}px`;
            chatWindow.style.height = `${window.innerHeight * 0.8}px`;
        }
    }

    // Send message
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const message = userInput.value.trim();
        if (!message) return;

        addMessage('user', message);
        userInput.value = ''; // Clear input field

        // Show thinking indicator
        thinkingIndicator.classList.remove('hidden');

        try {
            const response = await fetch('/chatbot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message, history: chatHistory })
            });

            if (!response.ok) {
                throw new Error('Failed to get response');
            }

            const data = await response.json();
            
            // Hide thinking indicator
            thinkingIndicator.classList.add('hidden');

            // Use typewriter effect for assistant's message
            typewriterEffect('assistant', data.message);
        } catch (error) {
            console.error('Error:', error);
            
            // Hide thinking indicator
            thinkingIndicator.classList.add('hidden');

            addMessage('assistant', 'Sorry but you have to login and verify your account to use this service');
        }
    });

    // Add message to chat window and load history
    function addMessage(role, content, addToHistory = true) {
        const messageElement = document.createElement('div');
        messageElement.classList.add('mb-4', role === 'user' ? 'text-right' : 'text-left');

        const innerElement = document.createElement('div');
        innerElement.classList.add('inline-block', 'p-2', 'rounded-lg', 'shadow-sm', 'transition', 'duration-300', 'ease-in-out');
        
        if (role === 'user') {
            innerElement.classList.add('bg-primary', 'text-primary-foreground');
        } else {
            innerElement.classList.add('bg-gray-200', 'text-gray-800');
        }

        innerElement.textContent = content;

        messageElement.appendChild(innerElement);
        chatMessages.appendChild(messageElement);

        scrollToBottom();

        if (addToHistory) {
            chatHistory.push({ role, content, timestamp: Date.now() });
            saveChatHistory();
        }
    }

    // Typewriter effect for assistant messages
    function typewriterEffect(role, content) {
        const messageElement = document.createElement('div');
        messageElement.classList.add('mb-4', 'text-left');

        const innerElement = document.createElement('div');
        innerElement.classList.add('inline-block', 'p-2', 'rounded-lg', 'shadow-sm', 'transition', 'duration-300', 'ease-in-out', 'bg-gray-200', 'text-gray-800');

        messageElement.appendChild(innerElement);
        chatMessages.appendChild(messageElement);

        let i = 0;
        const speed = 10; // Adjust the speed of typing here (lower is faster)

        function typeWriter() {
            if (i < content.length) {
                innerElement.textContent += content.charAt(i);
                i++;
                setTimeout(typeWriter, speed);
            }
            scrollToBottom();
        }

        typeWriter();

        setTimeout(() => {
            chatHistory.push({ role, content, timestamp: Date.now() });
            saveChatHistory();
        }, content.length * speed);
    }
});
</script>