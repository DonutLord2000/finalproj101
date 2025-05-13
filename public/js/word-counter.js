document.addEventListener("DOMContentLoaded", () => {
  // Get all content textareas
  const contentTextareas = document.querySelectorAll('textarea[name="content"]')

  contentTextareas.forEach((textarea) => {
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

    // Create warning message element (hidden by default)
    const warningMessage = document.createElement("div")
    warningMessage.className = "text-red-500 text-sm mt-1 hidden"
    textarea.parentNode.insertBefore(warningMessage, counterContainer.nextSibling)

    // Update word count on input
    textarea.addEventListener("input", () => {
      updateWordCount(textarea, wordCount, warningMessage)
    })

    // Initial count
    updateWordCount(textarea, wordCount, warningMessage)

    // Add debounced content check for moderation
    let debounceTimeout
    textarea.addEventListener("input", () => {
      clearTimeout(debounceTimeout)
      debounceTimeout = setTimeout(() => {
        checkContent(textarea, warningMessage)
      }, 1000) // Wait 1 second after typing stops
    })
  })

  // Create toast notification container if it doesn't exist
  if (!document.getElementById("toast-container")) {
    const toastContainer = document.createElement("div")
    toastContainer.id = "toast-container"
    toastContainer.className = "fixed top-4 right-4 z-50 flex flex-col space-y-4"
    document.body.appendChild(toastContainer)
  }

  // Handle form submissions
  const forms = document.querySelectorAll("form")
  forms.forEach((form) => {
    form.addEventListener("submit", (e) => {
      e.preventDefault() // Always prevent default form submission

      const textarea = form.querySelector('textarea[name="content"]')
      if (!textarea) return // Exit if no textarea found

      const submitButton = form.querySelector('button[type="submit"]')
      if (!submitButton) return // Exit if no submit button found

      // Check word count before submission
      const words = countWords(textarea.value)
      if (words > 700) {
        showToast("Content exceeds the maximum limit of 700 words.", "error")
        return
      }

      // Disable button and show loading state
      submitButton.disabled = true
      const originalText = submitButton.innerHTML
      submitButton.innerHTML =
        '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...'

      // Get form data
      const formData = new FormData(form)

      // Get CSRF token
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content")

      // Submit the form via AJAX
      fetch(form.action, {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": csrfToken,
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: formData,
      })
        .then((response) => {
          // Check if the response is JSON
          const contentType = response.headers.get("content-type")
          if (contentType && contentType.indexOf("application/json") !== -1) {
            return response.json()
          } else {
            // If not JSON, handle as text (could be a redirect)
            if (response.redirected) {
              window.location.href = response.url
              return { success: true }
            }
            return response.text().then((text) => {
              try {
                // Try to parse as JSON anyway
                return JSON.parse(text)
              } catch (e) {
                // If it's not JSON, check if it contains a redirect URL
                if (text.includes("redirect")) {
                  const match = text.match(/"redirect":"([^"]+)"/)
                  if (match && match[1]) {
                    window.location.href = match[1].replace(/\\\//g, "/")
                    return { success: true }
                  }
                }
                // If we can't handle it, just reload the page
                window.location.reload()
                return { success: true }
              }
            })
          }
        })
        .then((data) => {
          console.log("Form submission response:", data)

          if (data.success === false) {
            // Show error message as toast
            showToast(data.message, "error")

            // Also update the warning message below the textarea
            const warningMessage = textarea.parentNode.querySelector(".text-red-500")
            warningMessage.textContent = data.message
            warningMessage.classList.remove("hidden")

            // Reset button
            submitButton.disabled = false
            submitButton.innerHTML = originalText
          } else {
            // Success - show success toast and redirect
            showToast("Content posted successfully!", "success")

            // Redirect after a short delay to show the success message
            setTimeout(() => {
              if (data.redirect) {
                window.location.href = data.redirect
              } else {
                window.location.reload()
              }
            }, 1000)
          }
        })
        .catch((error) => {
          console.error("Error submitting form:", error)

          // Show generic error as toast
          showToast("An error occurred. Please try again.", "error")

          // Also update the warning message
          const warningMessage = textarea.parentNode.querySelector(".text-red-500")
          warningMessage.textContent = "An error occurred. Please try again."
          warningMessage.classList.remove("hidden")

          // Reset button
          submitButton.disabled = false
          submitButton.innerHTML = originalText
        })
    })
  })
})

function countWords(text) {
  return text
    .trim()
    .split(/\s+/)
    .filter((word) => word.length > 0).length
}

function updateWordCount(textarea, wordCountElement, warningElement) {
  const words = countWords(textarea.value)
  wordCountElement.textContent = words + " words"

  if (words > 700) {
    wordCountElement.classList.add("text-red-500")
    wordCountElement.classList.add("font-bold")
    warningElement.textContent = "Content exceeds the maximum limit of 700 words."
    warningElement.classList.remove("hidden")
  } else {
    wordCountElement.classList.remove("text-red-500")
    wordCountElement.classList.remove("font-bold")

    // Only hide the warning if it's showing the word count warning
    if (warningElement.textContent === "Content exceeds the maximum limit of 700 words.") {
      warningElement.classList.add("hidden")
    }
  }
}

// Update the checkContent function to better display warnings
function checkContent(textarea, warningElement) {
  // Only check if there's substantial content
  if (textarea.value.length < 10) return

  const formData = new FormData()
  formData.append("content", textarea.value)

  // Get CSRF token
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content")

  // Determine the endpoint based on the form action
  let checkEndpoint = "/threads/check-content"
  if (textarea.closest("form").action.includes("comments")) {
    checkEndpoint = "/comments/check-content"
  }

  fetch(checkEndpoint, {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": csrfToken,
      Accept: "application/json",
      "X-Requested-With": "XMLHttpRequest",
    },
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      console.log("Content check response:", data)
      if (!data.isSafe) {
        // Improve warning display
        warningElement.textContent = data.moderationMessage
        warningElement.classList.remove("hidden")
        warningElement.classList.add("p-2", "rounded", "bg-red-50", "border", "border-red-200")

        // Find the submit button for this textarea
        const form = textarea.closest("form")
        const submitButton = form ? form.querySelector('button[type="submit"]') : null

        // Disable submit button if content is not safe
        if (submitButton) {
          submitButton.disabled = true
          submitButton.classList.add("opacity-50", "cursor-not-allowed")
        }
      } else {
        // Clear warning if content is safe
        warningElement.textContent = ""
        warningElement.classList.add("hidden")
        warningElement.classList.remove("p-2", "rounded", "bg-red-50", "border", "border-red-200")

        // Find the submit button for this textarea
        const form = textarea.closest("form")
        const submitButton = form ? form.querySelector('button[type="submit"]') : null

        // Enable submit button if content is safe and not over word limit
        if (submitButton && countWords(textarea.value) <= 700) {
          submitButton.disabled = false
          submitButton.classList.remove("opacity-50", "cursor-not-allowed")
        }
      }
    })
    .catch((error) => {
      console.error("Error checking content:", error)
    })
}

// Toast notification function
function showToast(message, type = "info") {
  const toastContainer = document.getElementById("toast-container")

  const toast = document.createElement("div")
  toast.className = "rounded-md p-4 max-w-md shadow-lg transition-all duration-500 transform translate-x-0 opacity-100"

  // Set background color based on type
  switch (type) {
    case "success":
      toast.classList.add("bg-green-50", "border-l-4", "border-green-500")
      break
    case "error":
      toast.classList.add("bg-red-50", "border-l-4", "border-red-500")
      break
    case "warning":
      toast.classList.add("bg-yellow-50", "border-l-4", "border-yellow-500")
      break
    default:
      toast.classList.add("bg-blue-50", "border-l-4", "border-blue-500")
  }

  // Create toast content
  const icon = document.createElement("div")
  icon.className = "flex"

  let iconSvg = ""
  let textColor = ""

  switch (type) {
    case "success":
      iconSvg =
        '<svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>'
      textColor = "text-green-800"
      break
    case "error":
      iconSvg =
        '<svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>'
      textColor = "text-red-800"
      break
    case "warning":
      iconSvg =
        '<svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>'
      textColor = "text-yellow-800"
      break
    default:
      iconSvg =
        '<svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>'
      textColor = "text-blue-800"
  }

  icon.innerHTML = iconSvg

  const content = document.createElement("div")
  content.className = "ml-3 flex-1"

  const text = document.createElement("p")
  text.className = `text-sm ${textColor}`
  text.textContent = message

  content.appendChild(text)

  const closeButton = document.createElement("button")
  closeButton.className = "ml-auto -mx-1.5 -my-1.5 rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-gray-300"
  closeButton.innerHTML =
    '<svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>'
  closeButton.addEventListener("click", () => {
    toast.classList.replace("translate-x-0", "translate-x-full")
    toast.classList.replace("opacity-100", "opacity-0")
    setTimeout(() => {
      toast.remove()
    }, 300)
  })

  const flexContainer = document.createElement("div")
  flexContainer.className = "flex items-start"
  flexContainer.appendChild(icon)
  flexContainer.appendChild(content)
  flexContainer.appendChild(closeButton)

  toast.appendChild(flexContainer)
  toastContainer.appendChild(toast)

  // Auto-remove after 5 seconds
  setTimeout(() => {
    toast.classList.replace("translate-x-0", "translate-x-full")
    toast.classList.replace("opacity-100", "opacity-0")
    setTimeout(() => {
      toast.remove()
    }, 300)
  }, 5000)
}

// Live search functionality
function initializeLiveSearch() {
  const searchInput = document.querySelector('input[name="search"]')
  if (!searchInput) return

  // Store the original URL to maintain other query parameters
  const baseUrl = window.location.href.split("?")[0]

  // Debounce function to limit API calls
  let debounceTimer

  searchInput.addEventListener("input", () => {
    clearTimeout(debounceTimer)

    // Show loading indicator
    const searchForm = searchInput.closest("form")
    const searchButton = searchForm ? searchForm.querySelector('button[type="submit"]') : null

    if (searchButton) {
      const originalButtonText = searchButton.innerHTML
      searchButton.innerHTML =
        '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Searching...'
    }

    debounceTimer = setTimeout(() => {
      const searchValue = searchInput.value.trim()

      // Get current URL parameters
      const urlParams = new URLSearchParams(window.location.search)

      // Update or add search parameter
      if (searchValue) {
        urlParams.set("search", searchValue)
      } else {
        urlParams.delete("search")
      }

      // Build the new URL with all parameters
      const newUrl = baseUrl + (urlParams.toString() ? "?" + urlParams.toString() : "")

      // Fetch the search results
      fetch(newUrl, {
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((response) => response.text())
        .then((html) => {
          // Update the URL without refreshing the page
          window.history.pushState({ path: newUrl }, "", newUrl)

          // Parse the HTML response
          const parser = new DOMParser()
          const doc = parser.parseFromString(html, "text/html")

          // Find the threads container in the response
          const threadsContainer = doc.querySelector(".space-y-6")

          // Update the threads container in the current page
          if (threadsContainer) {
            document.querySelector(".space-y-6").innerHTML = threadsContainer.innerHTML
          }

          // Reset search button
          if (searchButton) {
            searchButton.innerHTML = "Search"
          }
        })
        .catch((error) => {
          console.error("Error performing live search:", error)

          // Reset search button on error
          if (searchButton) {
            searchButton.innerHTML = "Search"
          }
        })
    }, 500) // 500ms debounce time
  })

  // Prevent form submission since we're handling it via AJAX
  const searchForm = searchInput.closest("form")
  if (searchForm) {
    searchForm.addEventListener("submit", (e) => {
      e.preventDefault()
      // The search is already handled by the input event
    })
  }
}
