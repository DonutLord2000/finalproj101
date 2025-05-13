document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.querySelector('input[name="search"]')
  const threadsContainer = document.querySelector(".space-y-6")
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content")

  if (!searchInput || !threadsContainer) return

  let debounceTimer

  // Function to perform the search
  function performSearch(query) {
    // Show loading indicator
    const loadingIndicator = document.createElement("div")
    loadingIndicator.id = "search-loading"
    loadingIndicator.className = "text-center py-4"
    loadingIndicator.innerHTML = `
            <svg class="animate-spin h-8 w-8 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-2 text-gray-600">Searching...</p>
        `

    // Remove any existing loading indicator
    const existingIndicator = document.getElementById("search-loading")
    if (existingIndicator) {
      existingIndicator.remove()
    }

    // Add loading indicator if search is not empty
    if (query.trim() !== "") {
      threadsContainer.innerHTML = ""
      threadsContainer.appendChild(loadingIndicator)
    }

    // Get current URL and parameters
    const url = new URL(window.location.href)
    const params = new URLSearchParams(url.search)

    // Update or add search parameter
    if (query.trim() === "") {
      params.delete("search")
    } else {
      params.set("search", query)
    }

    // Build the new URL with updated parameters
    const newUrl = `${window.location.pathname}?${params.toString()}`

    // Fetch the search results
    fetch(newUrl, {
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        Accept: "text/html",
        "X-CSRF-TOKEN": csrfToken,
      },
    })
      .then((response) => response.text())
      .then((html) => {
        // Update browser history without reloading the page
        window.history.pushState({}, "", newUrl)

        // Parse the HTML response
        const parser = new DOMParser()
        const doc = parser.parseFromString(html, "text/html")

        // Extract the threads container content
        const newThreadsContainer = doc.querySelector(".space-y-6")

        if (newThreadsContainer) {
          threadsContainer.innerHTML = newThreadsContainer.innerHTML

          // Reinitialize any JavaScript that needs to run on the new content
          initializeReactionButtons()
          initializeDropdownMenus()
          initializeWordCounter()
        } else {
          threadsContainer.innerHTML =
            '<div class="text-center py-8"><p class="text-gray-500">No results found</p></div>'
        }
      })
      .catch((error) => {
        console.error("Error performing search:", error)
        threadsContainer.innerHTML =
          '<div class="text-center py-8"><p class="text-red-500">An error occurred while searching. Please try again.</p></div>'
      })
  }

  // Add input event listener with debounce
  searchInput.addEventListener("input", function () {
    clearTimeout(debounceTimer)
    debounceTimer = setTimeout(() => {
      performSearch(this.value)
    }, 500) // 500ms debounce delay
  })

  // Function to initialize reaction buttons
  function initializeReactionButtons() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content")

    function updateButtonState(button, isReacted) {
      const type = button.dataset.type
      const icon = button.querySelector(type === "upvote" ? ".upvote-icon" : ".heart-icon")

      if (isReacted) {
        icon.style.color = type === "upvote" ? "blue" : "red"
      } else {
        icon.style.color = "gray"
      }
    }

    document.querySelectorAll(".react-btn").forEach((button) => {
      const type = button.dataset.type
      const threadId = button.dataset.thread
      const upvoteCount = document.querySelector(`.upvote-count-${threadId}`)
      const heartCount = document.querySelector(`.heart-count-${threadId}`)

      // Set initial state
      updateButtonState(button, button.dataset.reacted === "true")

      // Add click event listener
      button.addEventListener("click", () => {
        fetch(`/threads/${threadId}/react`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
          },
          body: JSON.stringify({ type: type }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data && data.counts) {
              upvoteCount.textContent = data.counts.upvotes
              heartCount.textContent = data.counts.hearts

              // Update button states
              document.querySelectorAll(`.react-btn[data-thread="${threadId}"]`).forEach((btn) => {
                const btnType = btn.dataset.type
                updateButtonState(btn, data.userReacted[btnType])
              })
            } else {
              console.error("Unexpected response format:", data)
            }
          })
          .catch((error) => console.error("Error:", error))
      })
    })
  }

  // Function to initialize dropdown menus
  function initializeDropdownMenus() {
    document.querySelectorAll('[id^="options-menu-"]').forEach((button) => {
      const threadId = button.id.split("-").pop()
      const dropdownMenu = document.getElementById(`dropdown-menu-${threadId}`)

      if (button && dropdownMenu) {
        button.addEventListener("click", (event) => {
          event.stopPropagation()
          dropdownMenu.classList.toggle("hidden")
        })

        // Close the dropdown when clicking outside
        document.addEventListener("click", () => {
          dropdownMenu.classList.add("hidden")
        })
      }
    })

    const dropdownButtons = document.querySelectorAll('[id^="comment-options-menu-"]')

    dropdownButtons.forEach((button) => {
      const id = button.id
      let dropdownId

      // Determine the correct dropdown ID based on the button ID
      if (id.startsWith("comment-options-menu-index-")) {
        dropdownId = id.replace("comment-options-menu-index-", "comment-dropdown-menu-index-")
      } else if (id.startsWith("comment-options-menu-")) {
        dropdownId = id.replace("comment-options-menu-", "comment-dropdown-menu-")
      } else if (id.startsWith("options-menu-")) {
        dropdownId = id.replace("options-menu-", "dropdown-menu-")
      }

      const dropdownMenu = document.getElementById(dropdownId)

      if (button && dropdownMenu) {
        button.addEventListener("click", (event) => {
          event.stopPropagation()

          // Close all other dropdowns first
          document.querySelectorAll('.comment-dropdown-menu, [id^="dropdown-menu-"]').forEach((menu) => {
            if (menu.id !== dropdownId) {
              menu.classList.add("hidden")
            }
          })

          // Toggle the current dropdown
          dropdownMenu.classList.toggle("hidden")

          // Check if dropdown would go off-screen to the right
          if (!dropdownMenu.classList.contains("hidden")) {
            const rect = dropdownMenu.getBoundingClientRect()
            const parentRect = button.parentElement.getBoundingClientRect()

            // If dropdown would go off right edge of screen
            if (rect.right > window.innerWidth) {
              dropdownMenu.classList.add("dropdown-right")
            } else {
              dropdownMenu.classList.remove("dropdown-right")
            }

            // If dropdown would go off bottom of screen, position it above the button
            if (rect.bottom > window.innerHeight) {
              dropdownMenu.style.bottom = parentRect.height + "px"
              dropdownMenu.style.top = "auto"
            } else {
              dropdownMenu.style.top = ""
              dropdownMenu.style.bottom = ""
            }
          }
        })

        // Close dropdowns when clicking outside
        document.addEventListener("click", (event) => {
          if (!button.contains(event.target)) {
            dropdownMenu.classList.add("hidden")
          }
        })
      }
    })
  }

  // Function to initialize word counter
  function initializeWordCounter() {
    const contentTextareas = document.querySelectorAll('textarea[name="content"]')

    contentTextareas.forEach((textarea) => {
      // Check if word counter already exists for this textarea
      if (!textarea.nextElementSibling || !textarea.nextElementSibling.classList.contains("word-counter")) {
        // Create word counter element
        const counterContainer = document.createElement("div")
        counterContainer.className = "word-counter flex justify-between text-sm text-gray-500 mt-1"

        const wordCount = document.createElement("span")
        wordCount.className = "word-count"
        wordCount.textContent = "0 words"

        const wordLimit = document.createElement("span")
        wordLimit.className = "word-limit"
        wordLimit.textContent = "Max: 700 words"

        counterContainer.appendChild(wordCount)
        counterContainer.appendChild(wordLimit)

        // Insert counter after textarea
        textarea.parentNode.insertBefore(counterContainer, textarea.nextSibling)

        // Create warning message element (visible by default)
        const warningMessage = document.createElement("div")
        warningMessage.className = "text-red-500 text-sm mt-1 hidden"
        textarea.parentNode.insertBefore(warningMessage, counterContainer.nextSibling)

        // Update word count on input
        textarea.addEventListener("input", () => {
          updateWordCount(textarea, wordCount, warningMessage)
        })

        // Initial count
        updateWordCount(textarea, wordCount, warningMessage)
      }
    })

    function updateWordCount(textarea, wordCountElement, warningElement) {
      const words = countWords(textarea.value)
      wordCountElement.textContent = words + " words"

      // Find the submit button for this textarea
      const form = textarea.closest("form")
      const submitButton = form ? form.querySelector('button[type="submit"]') : null

      if (words > 700) {
        wordCountElement.classList.add("text-red-500")
        wordCountElement.classList.add("font-bold")
        warningElement.textContent = "Content exceeds the maximum limit of 700 words."
        warningElement.classList.remove("hidden")

        // Disable submit button if word count is over limit
        if (submitButton) {
          submitButton.disabled = true
          submitButton.classList.add("opacity-50", "cursor-not-allowed")
        }
      } else if (words < 3) {
        // Disable submit button if less than 3 words
        if (submitButton) {
          submitButton.disabled = true
          submitButton.classList.add("opacity-50", "cursor-not-allowed")
        }

        wordCountElement.classList.remove("text-red-500")
        wordCountElement.classList.remove("font-bold")

        // Only hide the warning if it's showing the word count warning
        if (warningElement.textContent === "Content exceeds the maximum limit of 700 words.") {
          warningElement.classList.add("hidden")
        }
      } else {
        // Enable submit button if word count is valid
        if (submitButton) {
          submitButton.disabled = false
          submitButton.classList.remove("opacity-50", "cursor-not-allowed")
        }

        wordCountElement.classList.remove("text-red-500")
        wordCountElement.classList.remove("font-bold")

        // Only hide the warning if it's showing the word count warning
        if (warningElement.textContent === "Content exceeds the maximum limit of 700 words.") {
          warningElement.classList.add("hidden")
        }
      }
    }

    function countWords(text) {
      return text
        .trim()
        .split(/\s+/)
        .filter((word) => word.length > 0).length
    }
  }
})
