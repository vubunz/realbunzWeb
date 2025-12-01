/**
 * =====================================================
 * COUNTDOWN CONTROLLER
 * Điều phối giữa CountdownModel và CountdownView
 * =====================================================
 */

const CountdownController = (() => {
  // Interval ID để có thể clear nếu cần
  let intervalId = null;

  // Sử dụng trực tiếp CountdownModel và CountdownView được khai báo global
  const CountdownModelRef = CountdownModel;
  const CountdownViewRef = CountdownView;

  /**
   * Cập nhật countdown
   */
  function update() {
    const timeRemaining = CountdownModelRef.getTimeRemaining();
    const progress = CountdownModelRef.getProgress();

    CountdownViewRef.render(timeRemaining);
    CountdownViewRef.renderProgress(progress);
  }

  /**
   * Khởi tạo controller
   */
  function init() {
    // Khởi tạo view
    CountdownViewRef.init();

    // Cập nhật lần đầu
    update();

    // Cập nhật mỗi giây
    intervalId = setInterval(update, 1000);
  }

  /**
   * Dừng countdown (nếu cần)
   */
  function stop() {
    if (intervalId) {
      clearInterval(intervalId);
      intervalId = null;
    }
  }

  /**
   * Khởi động lại countdown
   */
  function restart() {
    stop();
    init();
  }

  // Public API
  return {
    init,
    update,
    stop,
    restart,
  };
})();
