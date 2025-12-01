/**
 * =====================================================
 * THEME MODEL
 * Quản lý dữ liệu và logic theme (dark/light mode)
 * =====================================================
 */

const ThemeModel = (() => {
  // Constants
  const STORAGE_KEY = "theme"
  const DARK_THEME = "dark"
  const LIGHT_THEME = "light"

  // State
  let currentTheme = LIGHT_THEME

  /**
   * Khởi tạo theme từ localStorage hoặc system preference
   * @returns {string} Theme hiện tại
   */
  function initTheme() {
    const savedTheme = localStorage.getItem(STORAGE_KEY)
    const systemDark = window.matchMedia("(prefers-color-scheme: dark)").matches

    if (savedTheme === DARK_THEME || (!savedTheme && systemDark)) {
      currentTheme = DARK_THEME
    } else {
      currentTheme = LIGHT_THEME
    }

    return currentTheme
  }

  /**
   * Toggle theme
   * @returns {string} Theme mới
   */
  function toggleTheme() {
    currentTheme = currentTheme === DARK_THEME ? LIGHT_THEME : DARK_THEME
    localStorage.setItem(STORAGE_KEY, currentTheme)
    return currentTheme
  }

  /**
   * Set theme cụ thể
   * @param {string} theme - 'dark' hoặc 'light'
   */
  function setTheme(theme) {
    if (theme === DARK_THEME || theme === LIGHT_THEME) {
      currentTheme = theme
      localStorage.setItem(STORAGE_KEY, currentTheme)
    }
    return currentTheme
  }

  /**
   * Lấy theme hiện tại
   * @returns {string}
   */
  function getCurrentTheme() {
    return currentTheme
  }

  /**
   * Kiểm tra có phải dark mode không
   * @returns {boolean}
   */
  function isDarkMode() {
    return currentTheme === DARK_THEME
  }

  // Public API
  return {
    initTheme,
    toggleTheme,
    setTheme,
    getCurrentTheme,
    isDarkMode,
    DARK_THEME,
    LIGHT_THEME,
  }
})()
