/**
 * =====================================================
 * CALENDAR VIEW
 * Quản lý hiển thị UI cho module lịch vạn niên
 * =====================================================
 */

const CalendarView = (() => {
  // DOM Elements cache
  let elements = {}

  // Callback cho sự kiện click ngày
  let onDayClickCallback = null

  /**
   * Khởi tạo cache DOM elements
   */
  function init() {
    elements = {
      currentMonthYear: document.getElementById("currentMonthYear"),
      lunarMonthYear: document.getElementById("lunarMonthYear"),
      calendarGrid: document.getElementById("calendarGrid"),
      selectedDayInfo: document.getElementById("selectedDayInfo"),
      selectedDateTitle: document.getElementById("selectedDateTitle"),
      selectedDateLunar: document.getElementById("selectedDateLunar"),
      selectedDateCanChi: document.getElementById("selectedDateCanChi"),
      prevMonth: document.getElementById("prevMonth"),
      nextMonth: document.getElementById("nextMonth"),
      todayBtn: document.getElementById("todayBtn"),
    }
  }

  /**
   * Render header tháng/năm
   * @param {Object} data - { monthName, year, lunarMonth, canChiYear }
   */
  function renderHeader(data) {
    elements.currentMonthYear.textContent = `${data.monthName} ${data.year}`
    elements.lunarMonthYear.textContent = `Tháng ${data.lunarMonth} năm ${data.canChiYear}`
  }

  /**
   * Tạo element cho một ngày
   * @param {Object} dayData - Dữ liệu ngày
   * @returns {HTMLElement}
   */
  function createDayElement(dayData) {
    const div = document.createElement("div")

    // Build classes
    let classes = "calendar-day p-2 rounded-xl text-center cursor-pointer transition-all duration-200 "

    if (dayData.isOtherMonth) {
      classes += "opacity-30 "
    }

    if (dayData.isToday) {
      classes += "bg-red-600 text-white shadow-lg "
    } else if (dayData.isSunday) {
      classes += "text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 "
    } else {
      classes += "hover:bg-slate-100 dark:hover:bg-slate-700 "
    }

    div.className = classes

    // Content
    div.innerHTML = `
            <div class="font-semibold text-base">${dayData.day}</div>
            <div class="text-xs ${dayData.isToday ? "text-white/80" : "text-slate-400 dark:text-slate-500"}">${dayData.lunar[0]}</div>
        `

    // Event listener
    div.addEventListener("click", () => {
      if (onDayClickCallback) {
        onDayClickCallback(dayData)
      }
    })

    return div
  }

  /**
   * Render lưới lịch
   * @param {Array} days - Mảng dữ liệu các ngày
   */
  function renderGrid(days) {
    elements.calendarGrid.innerHTML = ""
    days.forEach((dayData) => {
      elements.calendarGrid.appendChild(createDayElement(dayData))
    })
  }

  /**
   * Render toàn bộ lịch
   * @param {Object} monthData - Dữ liệu tháng từ CalendarModel
   */
  function render(monthData) {
    renderHeader(monthData)
    renderGrid(monthData.days)
  }

  /**
   * Hiển thị chi tiết ngày được chọn
   * @param {Object} detail - { dayOfWeek, solarDate, lunarDay, lunarMonth, lunarYear, canChiYear }
   */
  function showDayDetail(detail) {
    elements.selectedDateTitle.textContent = `${detail.dayOfWeek}, ${detail.solarDate}`
    elements.selectedDateLunar.textContent = `📅 Âm lịch: ${detail.lunarDay}/${detail.lunarMonth}/${detail.lunarYear}`
    elements.selectedDateCanChi.textContent = `🐉 Năm ${detail.canChiYear}`
    elements.selectedDayInfo.classList.remove("hidden")
  }

  /**
   * Ẩn chi tiết ngày
   */
  function hideDayDetail() {
    elements.selectedDayInfo.classList.add("hidden")
  }

  /**
   * Đăng ký callback khi click vào ngày
   * @param {Function} callback
   */
  function onDayClick(callback) {
    onDayClickCallback = callback
  }

  /**
   * Lấy các elements để bind event
   * @returns {Object}
   */
  function getElements() {
    return elements
  }

  // Public API
  return {
    init,
    render,
    renderHeader,
    renderGrid,
    showDayDetail,
    hideDayDetail,
    onDayClick,
    getElements,
  }
})()
