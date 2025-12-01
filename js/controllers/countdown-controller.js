/**
 * =====================================================
 * COUNTDOWN CONTROLLER
 * Điều phối giữa CountdownModel và CountdownView
 * =====================================================
 */

const CountdownController = (() => {
  // Interval ID để có thể clear nếu cần
  let intervalId = null

  // Declare CountdownModel and CountdownView variables
  const CountdownModel = window.CountdownModel // Assuming CountdownModel is available globally
  const CountdownView = window.CountdownView // Assuming CountdownView is available globally

  /**
   * Cập nhật countdown
   */
  function update() {
    const timeRemaining = CountdownModel.getTimeRemaining()
    const progress = CountdownModel.getProgress()

    CountdownView.render(timeRemaining)
    CountdownView.renderProgress(progress)
  }

  /**
   * Khởi tạo controller
   */
  function init() {
    // Khởi tạo view
    CountdownView.init()

    // Cập nhật lần đầu
    update()

    // Cập nhật mỗi giây
    intervalId = setInterval(update, 1000)
  }

  /**
   * Dừng countdown (nếu cần)
   */
  function stop() {
    if (intervalId) {
      clearInterval(intervalId)
      intervalId = null
    }
  }

  /**
   * Khởi động lại countdown
   */
  function restart() {
    stop()
    init()
  }

  // Public API
  return {
    init,
    update,
    stop,
    restart,
  }
})()
