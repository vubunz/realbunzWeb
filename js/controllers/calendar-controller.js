/**
 * =====================================================
 * CALENDAR CONTROLLER
 * Điều phối giữa CalendarModel và CalendarView
 * =====================================================
 */

const CalendarController = (() => {
  // State: Tháng/năm đang hiển thị
  let displayYear = new Date().getFullYear();
  let displayMonth = new Date().getMonth();

  // Sử dụng trực tiếp CalendarModel và CalendarView được khai báo global
  const CalendarModelRef = CalendarModel;
  const CalendarViewRef = CalendarView;

  /**
   * Render lịch cho tháng/năm hiện tại
   */
  function renderCurrentMonth() {
    const monthData = CalendarModelRef.getMonthData(displayYear, displayMonth);
    CalendarViewRef.render(monthData);
  }

  /**
   * Chuyển tháng trước
   */
  function goToPrevMonth() {
    displayMonth--;
    if (displayMonth < 0) {
      displayMonth = 11;
      displayYear--;
    }
    renderCurrentMonth();
  }

  /**
   * Chuyển tháng sau
   */
  function goToNextMonth() {
    displayMonth++;
    if (displayMonth > 11) {
      displayMonth = 0;
      displayYear++;
    }
    renderCurrentMonth();
  }

  /**
   * Quay về tháng hiện tại
   */
  function goToToday() {
    const today = new Date();
    displayYear = today.getFullYear();
    displayMonth = today.getMonth();
    renderCurrentMonth();
  }

  /**
   * Xử lý khi click vào ngày
   * @param {Object} dayData - Dữ liệu ngày được click
   */
  function handleDayClick(dayData) {
    const detail = CalendarModelRef.getDayDetail(
      dayData.day,
      dayData.month,
      dayData.year
    );
    CalendarViewRef.showDayDetail(detail);
  }

  /**
   * Khởi tạo controller
   */
  function init() {
    // Khởi tạo view
    CalendarViewRef.init();

    // Đăng ký callback
    CalendarViewRef.onDayClick(handleDayClick);

    // Bind events
    const elements = CalendarViewRef.getElements();
    elements.prevMonth.addEventListener("click", goToPrevMonth);
    elements.nextMonth.addEventListener("click", goToNextMonth);
    elements.todayBtn.addEventListener("click", goToToday);

    // Render lần đầu
    renderCurrentMonth();
  }

  // Public API
  return {
    init,
    goToPrevMonth,
    goToNextMonth,
    goToToday,
    renderCurrentMonth,
  };
})();
