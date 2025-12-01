/**
 * =====================================================
 * COUNTDOWN MODEL
 * Quản lý dữ liệu và logic tính toán countdown
 * =====================================================
 */

const CountdownModel = (() => {
  // Private: Ngày Tết Nguyên Đán 2026 (17/02/2026)
  const TET_DATE = new Date("2026-02-17T00:00:00")

  // Private: Ngày bắt đầu tính progress (Tết 2025 - 29/01/2025)
  const START_DATE = new Date("2025-01-29T00:00:00")

  /**
   * Tính toán thời gian còn lại đến Tết
   * @returns {Object} { days, hours, minutes, seconds, isFinished }
   */
  function getTimeRemaining() {
    const now = new Date()
    const diff = TET_DATE - now

    // Nếu đã qua Tết
    if (diff <= 0) {
      return {
        days: 0,
        hours: 0,
        minutes: 0,
        seconds: 0,
        isFinished: true,
      }
    }

    return {
      days: Math.floor(diff / (1000 * 60 * 60 * 24)),
      hours: Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
      minutes: Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60)),
      seconds: Math.floor((diff % (1000 * 60)) / 1000),
      isFinished: false,
    }
  }

  /**
   * Tính phần trăm tiến độ đến Tết
   * @returns {number} Phần trăm (0-100)
   */
  function getProgress() {
    const now = new Date()
    const totalTime = TET_DATE - START_DATE
    const elapsed = now - START_DATE
    return Math.min(Math.max((elapsed / totalTime) * 100, 0), 100)
  }

  /**
   * Lấy ngày Tết
   * @returns {Date}
   */
  function getTetDate() {
    return TET_DATE
  }

  // Public API
  return {
    getTimeRemaining,
    getProgress,
    getTetDate,
  }
})()
