/**
 * =====================================================
 * APP.JS - Entry Point
 * Khởi tạo toàn bộ ứng dụng và các utility functions
 * =====================================================
 */

// Declare controllers before using them
const CalendarController = {
  goToPrevMonth: () => {},
  goToNextMonth: () => {},
  goToToday: () => {},
}

const QuotesController = {
  showRandomQuote: () => {},
}

const ThemeController = {
  init: () => {},
  toggle: () => {},
}

const CountdownController = {
  init: () => {},
}

/**
 * Utility: Hiển thị toast notification
 * @param {string} message - Nội dung thông báo
 */
function showToast(message) {
  const toast = document.getElementById("toast")
  const toastMessage = document.getElementById("toastMessage")

  toastMessage.textContent = message
  toast.classList.remove("translate-y-20", "opacity-0")
  toast.classList.add("translate-y-0", "opacity-100")

  setTimeout(() => {
    toast.classList.add("translate-y-20", "opacity-0")
    toast.classList.remove("translate-y-0", "opacity-100")
  }, 2500)
}

/**
 * Khởi tạo keyboard shortcuts
 */
function initKeyboardShortcuts() {
  document.addEventListener("keydown", (e) => {
    // Bỏ qua nếu đang focus vào input
    if (e.target.tagName === "INPUT" || e.target.tagName === "TEXTAREA") {
      return
    }

    switch (e.key) {
      case "ArrowLeft":
        CalendarController.goToPrevMonth()
        break
      case "ArrowRight":
        CalendarController.goToNextMonth()
        break
      case "t":
      case "T":
        CalendarController.goToToday()
        break
      case "q":
      case "Q":
        QuotesController.showRandomQuote()
        break
      case "d":
      case "D":
        ThemeController.toggle()
        break
    }
  })
}

/**
 * Main initialization
 * Khởi tạo tất cả controllers khi DOM ready
 */
document.addEventListener("DOMContentLoaded", () => {
  // Khởi tạo các controllers theo thứ tự
  ThemeController.init() // Theme trước để UI không bị flash
  CountdownController.init() // Countdown
  CalendarController.init() // Lịch vạn niên
  QuotesController.init() // Quotes

  // Khởi tạo keyboard shortcuts
  initKeyboardShortcuts()

  // Log để debug (có thể xóa sau)
  console.log("🧧 Tết Countdown App initialized successfully!")
  console.log("📁 MVC Architecture: Models, Views, Controllers")
})
