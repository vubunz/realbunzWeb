/**
 * =====================================================
 * THEME CONTROLLER
 * Điều phối giữa ThemeModel và ThemeView
 * =====================================================
 */

const ThemeController = (() => {
  // Declare ThemeModel and ThemeView
  const ThemeModel = {
    toggleTheme: () => {
      // Implementation for toggling theme
    },
    initTheme: () => {
      // Implementation for initializing theme
    },
  }

  const ThemeView = {
    init: () => {
      // Implementation for initializing view
    },
    applyTheme: (theme) => {
      // Implementation for applying theme
    },
    getToggleButton: () => {
      // Implementation for getting toggle button
    },
  }

  /**
   * Toggle theme
   */
  function toggle() {
    const newTheme = ThemeModel.toggleTheme()
    ThemeView.applyTheme(newTheme)
  }

  /**
   * Khởi tạo controller
   */
  function init() {
    // Khởi tạo view
    ThemeView.init()

    // Khởi tạo theme từ storage/system
    const currentTheme = ThemeModel.initTheme()
    ThemeView.applyTheme(currentTheme)

    // Bind event
    ThemeView.getToggleButton().addEventListener("click", toggle)
  }

  // Public API
  return {
    init,
    toggle,
  }
})()
