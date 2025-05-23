document.addEventListener("DOMContentLoaded", () => {
    // Get or create a persistent sessionId
    let sessionId
    // Try to get existing sessionId from localStorage
    const storedSessionId = localStorage.getItem("lumnix_session_id")
    if (storedSessionId) {
      sessionId = storedSessionId
    } else {
      // Create a new sessionId if none exists
      sessionId = "lumnix_" + Date.now().toString()
      // Store it for future page loads
      localStorage.setItem("lumnix_session_id", sessionId)
    }
  
    // Create the chatbot container
    const chatbotContainer = document.createElement("div")
    chatbotContainer.className = "lumnix-chatbot-container"
    chatbotContainer.innerHTML = `
          <div class="lumnix-chatbot-button" id="lumnix-chat-button">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
          </div>
          <div class="lumnix-chatbot-window" id="lumnix-chat-window">
              <div class="lumnix-chatbot-header">
                  <div class="lumnix-chatbot-title">Lumnix</div>
                  <div class="lumnix-chatbot-controls">
                      <div class="lumnix-chatbot-fullscreen" id="lumnix-chat-fullscreen">
                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-maximize-2"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" x2="14" y1="3" y2="10"/><line x1="3" x2="10" y1="21" y2="14"/></svg>
                      </div>
                      <div class="lumnix-chatbot-close" id="lumnix-chat-close">
                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                      </div>
                  </div>
              </div>
              <div class="lumnix-chatbot-messages" id="lumnix-chat-messages">
                  <!-- Messages will be added here -->
              </div>
              <div class="lumnix-chatbot-suggestions" id="lumnix-chat-suggestions">
                  <button class="lumnix-suggestion-button" data-query="Career Trajectory based on my current experience and education">
                      Career Trajectory
                  </button>
                  <button class="lumnix-suggestion-button" data-query="Career insight comparison of my profile to others">
                      Career Comparison
                  </button>
                  <button class="lumnix-suggestion-button" data-query="Career recommendations based on my experience or education">
                      Career Recommendationss
                  </button>
              </div>
              <div class="lumnix-chatbot-input">
                  <textarea id="lumnix-chat-input" placeholder="Type your message..."></textarea>
                  <button id="lumnix-chat-send">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                  </button>
              </div>
          </div>
          <div class="lumnix-chatbot-overlay" id="lumnix-chat-overlay"></div>
      `
    document.body.appendChild(chatbotContainer)
  
    // Get DOM elements
    const chatButton = document.getElementById("lumnix-chat-button")
    const chatWindow = document.getElementById("lumnix-chat-window")
    const chatClose = document.getElementById("lumnix-chat-close")
    const chatFullscreen = document.getElementById("lumnix-chat-fullscreen")
    const chatMessages = document.getElementById("lumnix-chat-messages")
    const chatInput = document.getElementById("lumnix-chat-input")
    const chatSend = document.getElementById("lumnix-chat-send")
    const chatOverlay = document.getElementById("lumnix-chat-overlay")
    const suggestionButtons = document.querySelectorAll(".lumnix-suggestion-button")
  
    // Chat state
    let isFullscreen = false
    let userName = "" // Will be populated when we get user data
    let isProcessing = false // Flag to track if the chatbot is processing a response
  
    // Initialize chat history from localStorage or create new array
    let chatHistory = []
    const localStorageKey = "lumnix_chat_history"
  
    // Try to load existing chat history from localStorage
    try {
      const savedHistory = localStorage.getItem(localStorageKey)
      console.log("Retrieved from localStorage:", savedHistory)
  
      if (savedHistory) {
        chatHistory = JSON.parse(savedHistory)
        console.log("Parsed chat history:", chatHistory)
  
        // If there's saved history, populate the chat UI with previous messages
        if (chatHistory.length > 0) {
          console.log("Restoring", chatHistory.length, "messages to UI")
          chatHistory.forEach((item) => {
            const sender = item.role === "user" ? "user" : "bot"
            addMessageToUI(item.content, sender, false) // false means don't update localStorage again
          })
        }
      } else {
        console.log("No saved chat history found")
      }
    } catch (error) {
      console.error("Error loading chat history from localStorage:", error)
      // If there's an error, start with a fresh history
      chatHistory = []
    }
  
    // Toggle chat window
    chatButton.addEventListener("click", () => {
      chatWindow.classList.toggle("lumnix-chatbot-window-active")
      chatButton.classList.toggle("lumnix-chatbot-button-hidden")
  
      // If this is the first time opening, fetch user name and send welcome message
      if (chatMessages.children.length === 0) {
        fetchUserNameAndWelcome()
      }
    })
  
    // Close chat window
    chatClose.addEventListener("click", () => {
      chatWindow.classList.remove("lumnix-chatbot-window-active")
      chatButton.classList.remove("lumnix-chatbot-button-hidden")
  
      // If in fullscreen, exit fullscreen first
      if (isFullscreen) {
        toggleFullscreen()
      }
    })
  
    // Toggle fullscreen
    chatFullscreen.addEventListener("click", toggleFullscreen)
  
    function toggleFullscreen() {
      isFullscreen = !isFullscreen
  
      if (isFullscreen) {
        chatWindow.classList.add("lumnix-chatbot-window-fullscreen")
        chatOverlay.classList.add("lumnix-chatbot-overlay-active")
        chatFullscreen.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-minimize-2"><polyline points="4 14 10 14 10 20"/><polyline points="20 10 14 10 14 4"/><line x1="14" x2="21" y1="10" y2="3"/><line x1="3" x2="10" y1="21" y2="14"/></svg>
        `
      } else {
        chatWindow.classList.remove("lumnix-chatbot-window-fullscreen")
        chatOverlay.classList.remove("lumnix-chatbot-overlay-active")
        chatFullscreen.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-maximize-2"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" x2="14" y1="3" y2="10"/><line x1="3" x2="10" y1="21" y2="14"/></svg>
        `
      }
    }
  
    // Handle suggestion button clicks
    suggestionButtons.forEach((button) => {
      button.addEventListener("click", () => {
        if (isProcessing) return // Don't allow clicks while processing
        const query = button.getAttribute("data-query")
        chatInput.value = query
        sendMessage()
      })
    })
  
    // Send message on button click
    chatSend.addEventListener("click", () => {
      if (!isProcessing) {
        sendMessage()
      }
    })
  
    // Send message on Enter key (but allow Shift+Enter for new lines)
    chatInput.addEventListener("keydown", (e) => {
      if (e.key === "Enter" && !e.shiftKey && !isProcessing) {
        e.preventDefault()
        sendMessage()
      }
    })
  
    // Function to disable input controls
    function disableInputControls() {
      isProcessing = true
      chatInput.disabled = true
      chatSend.disabled = true
  
      // Disable all suggestion buttons
      suggestionButtons.forEach((button) => {
        button.disabled = true
      })
    }
  
    // Function to enable input controls
    function enableInputControls() {
      isProcessing = false
      chatInput.disabled = false
      chatSend.disabled = false
  
      // Enable all suggestion buttons
      suggestionButtons.forEach((button) => {
        button.disabled = false
      })
  
      // Focus the input field for better UX
      chatInput.focus()
    }
  
    // Fetch user name and send welcome message
    function fetchUserNameAndWelcome() {
      // Disable controls while fetching
      disableInputControls()
  
      // Show typing indicator
      const typingIndicator = document.createElement("div")
      typingIndicator.className = "lumnix-message lumnix-bot-message lumnix-typing"
      typingIndicator.innerHTML =
        '<div class="lumnix-message-content"><div class="lumnix-typing-indicator"><span></span><span></span><span></span></div></div>'
      chatMessages.appendChild(typingIndicator)
  
      // Fetch user data
      fetch("/api/lumnix-user-info", {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "",
          "X-Requested-With": "XMLHttpRequest",
        },
        credentials: "same-origin",
      })
        .then((response) => response.json())
        .then((data) => {
          // Remove typing indicator
          const typingElements = document.getElementsByClassName("lumnix-typing")
          while (typingElements.length > 0) {
            typingElements[0].parentNode.removeChild(typingElements[0])
          }
  
          // Store user name
          userName = data.name || "there"
  
          // Add welcome message
          const welcomeMessage = `Hello ${userName}! I'm Lumnix, your personal career assistant. How can I help you today? You can click on the suggestion buttons below or type your own question.`
          addMessage(welcomeMessage, "bot")
  
          // Re-enable controls after welcome message
          enableInputControls()
        })
        .catch((error) => {
          console.error("Error fetching user info:", error)
  
          // Remove typing indicator
          const typingElements = document.getElementsByClassName("lumnix-typing")
          while (typingElements.length > 0) {
            typingElements[0].parentNode.removeChild(typingElements[0])
          }
  
          // Add generic welcome message
          const welcomeMessage =
            "Hello there! I'm Lumnix, your personal career assistant. How can I help you today? You can click on the suggestion buttons below or type your own question."
          addMessage(welcomeMessage, "bot")
  
          // Re-enable controls after welcome message
          enableInputControls()
        })
    }
  
    // Send message function
    function sendMessage() {
      const message = chatInput.value.trim()
      if (!message) return
  
      // Disable input controls while processing
      disableInputControls()
  
      // Add user message to chat
      addMessage(message, "user")
      chatInput.value = ""
  
      // Show typing indicator
      const typingIndicator = document.createElement("div")
      typingIndicator.className = "lumnix-message lumnix-bot-message lumnix-typing"
      typingIndicator.innerHTML =
        '<div class="lumnix-message-content"><div class="lumnix-typing-indicator"><span></span><span></span><span></span></div></div>'
      chatMessages.appendChild(typingIndicator)
      chatMessages.scrollTop = chatMessages.scrollHeight
  
      // Send message to server
      fetch("/api/lumnix-chat", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "",
          // Include session cookie
          "X-Requested-With": "XMLHttpRequest",
        },
        credentials: "same-origin",
        body: JSON.stringify({
          message: message,
          sessionId: sessionId,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          // Remove typing indicator
          const typingElements = document.getElementsByClassName("lumnix-typing")
          while (typingElements.length > 0) {
            typingElements[0].parentNode.removeChild(typingElements[0])
          }
  
          // Add bot response to chat
          addMessage(data.response, "bot")
  
          // Re-enable input controls after response
          enableInputControls()
        })
        .catch((error) => {
          console.error("Error:", error)
  
          // Remove typing indicator
          const typingElements = document.getElementsByClassName("lumnix-typing")
          while (typingElements.length > 0) {
            typingElements[0].parentNode.removeChild(typingElements[0])
          }
  
          // Add error message
          addMessage("Sorry, I encountered an error. Please try again later.", "bot")
  
          // Re-enable input controls after error
          enableInputControls()
        })
    }
  
    // Add a function to save chat history to localStorage
    function saveHistoryToLocalStorage() {
      try {
        console.log("Saving chat history to localStorage:", chatHistory)
        localStorage.setItem(localStorageKey, JSON.stringify(chatHistory))
      } catch (error) {
        console.error("Error saving chat history to localStorage:", error)
      }
    }
  
    // Function to add message to UI
    function addMessageToUI(message, sender, updateStorage = true) {
      const messageElement = document.createElement("div")
      messageElement.className = `lumnix-message lumnix-${sender}-message`
  
      // Format the message differently based on sender
      let formattedMessage = message
  
      // Convert URLs to links for all messages
      formattedMessage = formattedMessage.replace(
        /https?:\/\/[^\s]+/g,
        (url) => `<a href="${url}" target="_blank" rel="noopener noreferrer">${url}</a>`,
      )
  
      // For bot messages only, convert text between asterisks to bold
      if (sender === "bot") {
        // Replace text between double asterisks with bold text
        formattedMessage = formattedMessage.replace(/\*\*(.*?)\*\*/g, "<strong>$1</strong>")
  
        // Replace text between single asterisks with bold text (if not already processed)
        formattedMessage = formattedMessage.replace(/\*(.*?)\*/g, "<strong>$1</strong>")
      }
  
      // Handle line breaks for all messages
      formattedMessage = formattedMessage.replace(/\n/g, "<br>")
  
      messageElement.innerHTML = `<div class="lumnix-message-content">${formattedMessage}</div>`
      chatMessages.appendChild(messageElement)
      chatMessages.scrollTop = chatMessages.scrollHeight
    }
  
    // Function to add message to chat (both UI and data)
    function addMessage(message, sender) {
      // Add to UI
      addMessageToUI(message, sender)
  
      // Update chat history
      chatHistory.push({
        role: sender === "user" ? "user" : "assistant",
        content: message,
      })
  
      // Save to localStorage
      saveHistoryToLocalStorage()
    }
  
    // Add a function to clear chat history (to be called on logout)
    function clearChatHistory() {
      chatHistory = []
      localStorage.removeItem(localStorageKey)
  
      // Clear the chat UI
      while (chatMessages.firstChild) {
        chatMessages.removeChild(chatMessages.firstChild)
      }
    }
  
    // Add event listener for user logout (if you have a logout button)
    // This is a placeholder - you'll need to connect it to your actual logout process
    document.addEventListener("lumnix-user-logout", () => {
      clearChatHistory()
    })
  })
  