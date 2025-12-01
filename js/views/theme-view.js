/**
 * =====================================================
 * THEME VIEW
 * Quản lý hiển thị UI cho theme (dark/light mode)
 * =====================================================
 */

const ThemeView = (() => {
  // DOM Elements cache
  let elements = {}

  /**
   * Khởi tạo cache DOM elements
   */
  function init() {
    elements = {
      toggleBtn: document.getElementById("themeToggle"),
      sunIcon: document.getElementById("sunIcon"),
      moonIcon: document.getElementById("moonIcon"),
      html: document.documentElement,
    }
  }

  /**
   * Apply theme vào DOM
   * @param {string} theme - 'dark' hoặc 'light'
   */
  function applyTheme(theme) {
    if (theme === "dark") {
      elements.html.classList.add("dark")
    } else {
      elements.html.classList.remove("dark")
    }
  }

  /**
   * Lấy toggle button để bind event
   * @returns {HTMLElement}
   */
  function getToggleButton() {
    return elements.toggleBtn
  }

  // Public API
  return {
    init,
    applyTheme,
    getToggleButton,
  }
})()
