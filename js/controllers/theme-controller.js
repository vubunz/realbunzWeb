/**
 * =====================================================
 * THEME CONTROLLER
 * Điều phối giữa ThemeModel và ThemeView
 * =====================================================
 */

// Sử dụng ThemeModel (model) và ThemeView (view) đã được khai báo

const ThemeController = (() => {
  /**
   * Toggle theme
   */
  function toggle() {
    const newTheme = ThemeModel.toggleTheme();
    ThemeView.applyTheme(newTheme);
  }

  /**
   * Khởi tạo controller
   */
  function init() {
    // Khởi tạo view
    ThemeView.init();

    // Khởi tạo theme từ storage/system
    const currentTheme = ThemeModel.initTheme();
    ThemeView.applyTheme(currentTheme);

    // Bind event
    const btn = ThemeView.getToggleButton();
    if (btn) {
      btn.addEventListener("click", toggle);
    }
  }

  // Public API
  return {
    init,
    toggle,
  };
})();
