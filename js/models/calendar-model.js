/**
 * =====================================================
 * CALENDAR MODEL
 * Quản lý dữ liệu lịch và thuật toán chuyển đổi âm dương lịch
 * Nguồn thuật toán: Ho Ngoc Duc - https://www.informatik.uni-leipzig.de/~duc/amlich/
 * =====================================================
 */

const CalendarModel = (() => {
  // Private: Tên tháng tiếng Việt
  const MONTH_NAMES = [
    "Tháng 1",
    "Tháng 2",
    "Tháng 3",
    "Tháng 4",
    "Tháng 5",
    "Tháng 6",
    "Tháng 7",
    "Tháng 8",
    "Tháng 9",
    "Tháng 10",
    "Tháng 11",
    "Tháng 12",
  ]

  // Private: Tên ngày trong tuần
  const DAY_NAMES = ["Chủ nhật", "Thứ hai", "Thứ ba", "Thứ tư", "Thứ năm", "Thứ sáu", "Thứ bảy"]

  // Private: Thiên Can
  const THIEN_CAN = ["Giáp", "Ất", "Bính", "Đinh", "Mậu", "Kỷ", "Canh", "Tân", "Nhâm", "Quý"]

  // Private: Địa Chi
  const DIA_CHI = ["Tý", "Sửu", "Dần", "Mão", "Thìn", "Tỵ", "Ngọ", "Mùi", "Thân", "Dậu", "Tuất", "Hợi"]

  // State: Tháng/năm đang hiển thị
  let currentYear = new Date().getFullYear()
  let currentMonth = new Date().getMonth()

  /* ==================== THUẬT TOÁN ÂM LỊCH ==================== */

  function jdFromDate(dd, mm, yy) {
    const a = Math.floor((14 - mm) / 12)
    const y = yy + 4800 - a
    const m = mm + 12 * a - 3
    let jd =
      dd +
      Math.floor((153 * m + 2) / 5) +
      365 * y +
      Math.floor(y / 4) -
      Math.floor(y / 100) +
      Math.floor(y / 400) -
      32045
    if (jd < 2299161) {
      jd = dd + Math.floor((153 * m + 2) / 5) + 365 * y + Math.floor(y / 4) - 32083
    }
    return jd
  }

  function getNewMoonDay(k, timeZone) {
    const T = k / 1236.85
    const T2 = T * T
    const T3 = T2 * T
    const dr = Math.PI / 180
    let Jd1 = 2415020.75933 + 29.53058868 * k + 0.0001178 * T2 - 0.000000155 * T3
    Jd1 = Jd1 + 0.00033 * Math.sin((166.56 + 132.87 * T - 0.009173 * T2) * dr)
    const M = 359.2242 + 29.10535608 * k - 0.0000333 * T2 - 0.00000347 * T3
    const Mpr = 306.0253 + 385.81691806 * k + 0.0107306 * T2 + 0.00001236 * T3
    const F = 21.2964 + 390.67050646 * k - 0.0016528 * T2 - 0.00000239 * T3
    let C1 = (0.1734 - 0.000393 * T) * Math.sin(M * dr) + 0.0021 * Math.sin(2 * dr * M)
    C1 = C1 - 0.4068 * Math.sin(Mpr * dr) + 0.0161 * Math.sin(dr * 2 * Mpr)
    C1 = C1 - 0.0004 * Math.sin(dr * 3 * Mpr)
    C1 = C1 + 0.0104 * Math.sin(dr * 2 * F) - 0.0051 * Math.sin(dr * (M + Mpr))
    C1 = C1 - 0.0074 * Math.sin(dr * (M - Mpr)) + 0.0004 * Math.sin(dr * (2 * F + M))
    C1 = C1 - 0.0004 * Math.sin(dr * (2 * F - M)) - 0.0006 * Math.sin(dr * (2 * F + Mpr))
    C1 = C1 + 0.001 * Math.sin(dr * (2 * F - Mpr)) + 0.0005 * Math.sin(dr * (2 * Mpr + M))
    let deltat
    if (T < -11) {
      deltat = 0.001 + 0.000839 * T + 0.0002261 * T2 - 0.00000845 * T3 - 0.000000081 * T * T3
    } else {
      deltat = -0.000278 + 0.000265 * T + 0.000262 * T2
    }
    return Math.floor(Jd1 + C1 - deltat + 0.5 + timeZone / 24)
  }

  function getSunLongitude(jdn, timeZone) {
    const T = (jdn - 2451545.5 - timeZone / 24) / 36525
    const T2 = T * T
    const dr = Math.PI / 180
    const M = 357.5291 + 35999.0503 * T - 0.0001559 * T2 - 0.00000048 * T * T2
    const L0 = 280.46645 + 36000.76983 * T + 0.0003032 * T2
    let DL = (1.9146 - 0.004817 * T - 0.000014 * T2) * Math.sin(dr * M)
    DL = DL + (0.019993 - 0.000101 * T) * Math.sin(dr * 2 * M) + 0.00029 * Math.sin(dr * 3 * M)
    let L = L0 + DL
    L = L * dr
    L = L - Math.PI * 2 * Math.floor(L / (Math.PI * 2))
    return Math.floor((L / Math.PI) * 6)
  }

  function getLunarMonth11(yy, timeZone) {
    const off = jdFromDate(31, 12, yy) - 2415021
    const k = Math.floor(off / 29.530588853)
    let nm = getNewMoonDay(k, timeZone)
    const sunLong = getSunLongitude(nm, timeZone)
    if (sunLong >= 9) {
      nm = getNewMoonDay(k - 1, timeZone)
    }
    return nm
  }

  function getLeapMonthOffset(a11, timeZone) {
    const k = Math.floor((a11 - 2415021.076998695) / 29.530588853 + 0.5)
    let last = 0
    let i = 1
    let arc = getSunLongitude(getNewMoonDay(k + i, timeZone), timeZone)
    do {
      last = arc
      i++
      arc = getSunLongitude(getNewMoonDay(k + i, timeZone), timeZone)
    } while (arc !== last && i < 14)
    return i - 1
  }

  /**
   * Chuyển đổi dương lịch sang âm lịch
   * @param {number} dd - Ngày
   * @param {number} mm - Tháng (1-12)
   * @param {number} yy - Năm
   * @param {number} timeZone - Múi giờ
   * @returns {Array} [ngày, tháng, năm, nhuận]
   */
  function convertSolar2Lunar(dd, mm, yy, timeZone = 7) {
    const dayNumber = jdFromDate(dd, mm, yy)
    const k = Math.floor((dayNumber - 2415021.076998695) / 29.530588853)
    let monthStart = getNewMoonDay(k + 1, timeZone)
    if (monthStart > dayNumber) {
      monthStart = getNewMoonDay(k, timeZone)
    }
    let a11 = getLunarMonth11(yy, timeZone)
    let b11 = a11
    let lunarYear
    if (a11 >= monthStart) {
      lunarYear = yy
      a11 = getLunarMonth11(yy - 1, timeZone)
    } else {
      lunarYear = yy + 1
      b11 = getLunarMonth11(yy + 1, timeZone)
    }
    const lunarDay = dayNumber - monthStart + 1
    const diff = Math.floor((monthStart - a11) / 29)
    let lunarLeap = 0
    let lunarMonth = diff + 11
    if (b11 - a11 > 365) {
      const leapMonthDiff = getLeapMonthOffset(a11, timeZone)
      if (diff >= leapMonthDiff) {
        lunarMonth = diff + 10
        if (diff === leapMonthDiff) {
          lunarLeap = 1
        }
      }
    }
    if (lunarMonth > 12) {
      lunarMonth = lunarMonth - 12
    }
    if (lunarMonth >= 11 && diff < 4) {
      lunarYear -= 1
    }
    return [lunarDay, lunarMonth, lunarYear, lunarLeap]
  }

  /**
   * Lấy Can Chi của năm
   * @param {number} year - Năm âm lịch
   * @returns {string}
   */
  function getCanChiYear(year) {
    const canIndex = (year + 6) % 10
    const chiIndex = (year + 8) % 12
    return `${THIEN_CAN[canIndex]} ${DIA_CHI[chiIndex]}`
  }

  /* ==================== CALENDAR DATA METHODS ==================== */

  /**
   * Lấy dữ liệu lịch cho tháng
   * @param {number} year - Năm
   * @param {number} month - Tháng (0-11)
   * @returns {Object} Dữ liệu lịch
   */
  function getMonthData(year, month) {
    const today = new Date()
    const firstDay = new Date(year, month, 1)
    const startingDay = firstDay.getDay()
    const daysInMonth = new Date(year, month + 1, 0).getDate()
    const daysInPrevMonth = new Date(year, month, 0).getDate()

    // Ngày âm lịch đầu tháng
    const lunarFirstDay = convertSolar2Lunar(1, month + 1, year, 7)

    const days = []

    // Ngày tháng trước
    for (let i = startingDay - 1; i >= 0; i--) {
      const day = daysInPrevMonth - i
      const prevMonth = month - 1 < 0 ? 11 : month - 1
      const prevYear = month - 1 < 0 ? year - 1 : year
      const lunar = convertSolar2Lunar(day, prevMonth + 1, prevYear, 7)
      days.push({
        day,
        month: prevMonth,
        year: prevYear,
        isOtherMonth: true,
        isToday: false,
        isSunday: new Date(prevYear, prevMonth, day).getDay() === 0,
        lunar,
      })
    }

    // Ngày tháng hiện tại
    for (let day = 1; day <= daysInMonth; day++) {
      const lunar = convertSolar2Lunar(day, month + 1, year, 7)
      const date = new Date(year, month, day)
      days.push({
        day,
        month,
        year,
        isOtherMonth: false,
        isToday: day === today.getDate() && month === today.getMonth() && year === today.getFullYear(),
        isSunday: date.getDay() === 0,
        lunar,
      })
    }

    // Ngày tháng sau
    const totalCells = startingDay + daysInMonth
    const remainingCells = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7)
    for (let day = 1; day <= remainingCells; day++) {
      const nextMonth = month + 1 > 11 ? 0 : month + 1
      const nextYear = month + 1 > 11 ? year + 1 : year
      const lunar = convertSolar2Lunar(day, nextMonth + 1, nextYear, 7)
      days.push({
        day,
        month: nextMonth,
        year: nextYear,
        isOtherMonth: true,
        isToday: false,
        isSunday: new Date(nextYear, nextMonth, day).getDay() === 0,
        lunar,
      })
    }

    return {
      year,
      month,
      monthName: MONTH_NAMES[month],
      lunarMonth: lunarFirstDay[1],
      lunarYear: lunarFirstDay[2],
      canChiYear: getCanChiYear(lunarFirstDay[2]),
      days,
    }
  }

  /**
   * Lấy thông tin chi tiết của một ngày
   * @param {number} day - Ngày
   * @param {number} month - Tháng (0-11)
   * @param {number} year - Năm
   * @returns {Object}
   */
  function getDayDetail(day, month, year) {
    const date = new Date(year, month, day)
    const lunar = convertSolar2Lunar(day, month + 1, year, 7)

    return {
      solarDate: `${day}/${month + 1}/${year}`,
      dayOfWeek: DAY_NAMES[date.getDay()],
      lunarDay: lunar[0],
      lunarMonth: lunar[1],
      lunarYear: lunar[2],
      canChiYear: getCanChiYear(lunar[2]),
    }
  }

  // Setters cho state
  function setCurrentMonth(year, month) {
    currentYear = year
    currentMonth = month
  }

  function getCurrentState() {
    return { year: currentYear, month: currentMonth }
  }

  // Public API
  return {
    getMonthData,
    getDayDetail,
    getCanChiYear,
    convertSolar2Lunar,
    setCurrentMonth,
    getCurrentState,
    MONTH_NAMES,
    DAY_NAMES,
  }
})()
