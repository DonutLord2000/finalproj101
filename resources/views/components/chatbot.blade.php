<div id="floating-chatbot" class="fixed bottom-4 right-4 z-50">
    <button id="chat-icon" class="bg-primary text-primary-foreground rounded-full w-12 h-12 shadow-lg flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" aria-label="Open chat">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
    </button>
    <div id="chat-window" class="hidden bg-white rounded-lg shadow-xl w-80 h-96 absolute bottom-16 right-0">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold">Chat Support</h3>
            <button id="close-chat" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" aria-label="Close chat">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div id="chat-messages" class="h-64 overflow-y-auto p-4 space-y-4"></div>
        <form id="chat-form" class="p-4 border-t">
            <div class="flex space-x-2">
                <input type="text" id="user-input" class="flex-grow rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" placeholder="Type your message..." required>
                <button type="submit" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">Send</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatIcon = document.getElementById('chat-icon');
    const chatWindow = document.getElementById('chat-window');
    const closeChat = document.getElementById('close-chat');
    const chatForm = document.getElementById('chat-form');
    const userInput = document.getElementById('user-input');
    const chatMessages = document.getElementById('chat-messages');

    chatIcon.addEventListener('click', () => {
        chatWindow.classList.toggle('hidden');
    });

    closeChat.addEventListener('click', () => {
        chatWindow.classList.add('hidden');
    });

    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const message = userInput.value.trim();
        if (!message) return;

        addMessage('user', message);
        userInput.value = '';

        try {
            const response = await fetch('/api/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message })
            });

            if (!response.ok) {
                throw new Error('Failed to get response');
            }

            const data = await response.json();
            addMessage('assistant', data.message);
        } catch (error) {
            console.error('Error:', error);
            addMessage('assistant', 'Sorry, there was an error processing your request.');
        }
    });

    function addMessage(role, content) {
        const messageElement = document.createElement('div');
        messageElement.classList.add('mb-4', role === 'user' ? 'text-right' : 'text-left');
        
        const innerElement = document.createElement('div');
        const roleClasses = role === 'user' 
            ? ['bg-primary', 'text-primary-foreground'] 
            : ['bg-gray-200', 'text-gray-800'];
        
        innerElement.classList.add('inline-block', 'p-2', 'rounded-lg');
        roleClasses.forEach(cls => innerElement.classList.add(cls)); // Add classes one by one
        
        innerElement.textContent = content;
        
        messageElement.appendChild(innerElement);
        chatMessages.appendChild(messageElement);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

});
</script>