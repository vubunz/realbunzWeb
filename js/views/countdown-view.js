/**
 * =====================================================
 * COUNTDOWN VIEW
 * Quản lý hiển thị UI cho module countdown
 * =====================================================
 */

const CountdownView = (() => {
  // DOM Elements cache
  let elements = {}

  /**
   * Khởi tạo cache DOM elements
   */
  function init() {
    elements = {
      days: document.getElementById("days"),
      hours: document.getElementById("hours"),
      minutes: document.getElementById("minutes"),
      seconds: document.getElementById("seconds"),
      progressBar: document.getElementById("progressBar"),
      progressPercent: document.getElementById("progressPercent"),
    }
  }

  /**
   * Cập nhật số với hiệu ứng animation
   * @param {HTMLElement} element - Element cần cập nhật
   * @param {string} value - Giá trị mới
   */
  function updateNumberWithAnimation(element, value) {
    if (element.textContent !== value) {
      element.style.transform = "scale(1.1)"
      element.textContent = value
      setTimeout(() => {
        element.style.transform = "scale(1)"
      }, 100)
    }
  }

  /**
   * Render countdown
   * @param {Object} data - { days, hours, minutes, seconds, isFinished }
   */
  function render(data) {
    if (data.isFinished) {
      elements.days.textContent = "🎊"
      elements.hours.textContent = "Chúc"
      elements.minutes.textContent = "Mừng"
      elements.seconds.textContent = "Năm Mới!"
      return
    }

    updateNumberWithAnimation(elements.days, String(data.days).padStart(2, "0"))
    updateNumberWithAnimation(elements.hours, String(data.hours).padStart(2, "0"))
    updateNumberWithAnimation(elements.minutes, String(data.minutes).padStart(2, "0"))
    updateNumberWithAnimation(elements.seconds, String(data.seconds).padStart(2, "0"))
  }

  /**
   * Render progress bar
   * @param {number} progress - Phần trăm (0-100)
   */
  function renderProgress(progress) {
    elements.progressBar.style.width = `${progress}%`
    elements.progressPercent.textContent = `${progress.toFixed(1)}%`
  }

  // Public API
  return {
    init,
    render,
    renderProgress,
  }
})()
