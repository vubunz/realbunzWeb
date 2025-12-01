/**
 * =====================================================
 * CALENDAR CONTROLLER
 * Điều phối giữa CalendarModel và CalendarView
 * =====================================================
 */

const CalendarController = (() => {
  // State: Tháng/năm đang hiển thị
  let displayYear = new Date().getFullYear()
  let displayMonth = new Date().getMonth()

  // Declare CalendarModel and CalendarView
  const CalendarModel = window.CalendarModel // Assuming CalendarModel is available globally
  const CalendarView = window.CalendarView // Assuming CalendarView is available globally

  /**
   * Render lịch cho tháng/năm hiện tại
   */
  function renderCurrentMonth() {
    const monthData = CalendarModel.getMonthData(displayYear, displayMonth)
    CalendarView.render(monthData)
  }

  /**
   * Chuyển tháng trước
   */
  function goToPrevMonth() {
    displayMonth--
    if (displayMonth < 0) {
      displayMonth = 11
      displayYear--
    }
    renderCurrentMonth()
  }

  /**
   * Chuyển tháng sau
   */
  function goToNextMonth() {
    displayMonth++
    if (displayMonth > 11) {
      displayMonth = 0
      displayYear++
    }
    renderCurrentMonth()
  }

  /**
   * Quay về tháng hiện tại
   */
  function goToToday() {
    const today = new Date()
    displayYear = today.getFullYear()
    displayMonth = today.getMonth()
    renderCurrentMonth()
  }

  /**
   * Xử lý khi click vào ngày
   * @param {Object} dayData - Dữ liệu ngày được click
   */
  function handleDayClick(dayData) {
    const detail = CalendarModel.getDayDetail(dayData.day, dayData.month, dayData.year)
    CalendarView.showDayDetail(detail)
  }

  /**
   * Khởi tạo controller
   */
  function init() {
    // Khởi tạo view
    CalendarView.init()

    // Đăng ký callback
    CalendarView.onDayClick(handleDayClick)

    // Bind events
    const elements = CalendarView.getElements()
    elements.prevMonth.addEventListener("click", goToPrevMonth)
    elements.nextMonth.addEventListener("click", goToNextMonth)
    elements.todayBtn.addEventListener("click", goToToday)

    // Render lần đầu
    renderCurrentMonth()
  }

  // Public API
  return {
    init,
    goToPrevMonth,
    goToNextMonth,
    goToToday,
    renderCurrentMonth,
  }
})()
