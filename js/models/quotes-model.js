/**
 * =====================================================
 * QUOTES MODEL
 * Quản lý dữ liệu và logic lấy quotes
 * =====================================================
 */

const QuotesModel = (() => {
  // Private: Danh sách quotes
  const QUOTES = [
    { text: "Một năm khởi đầu từ mùa xuân, một ngày khởi đầu từ buổi sáng.", author: "Tục ngữ Việt Nam" },
    { text: "Đi một ngày đàng, học một sàng khôn.", author: "Tục ngữ Việt Nam" },
    { text: "Có chí thì nên.", author: "Tục ngữ Việt Nam" },
    { text: "Lửa thử vàng, gian nan thử sức.", author: "Tục ngữ Việt Nam" },
    { text: "Kiến tha lâu cũng đầy tổ.", author: "Tục ngữ Việt Nam" },
    { text: "Học, học nữa, học mãi.", author: "Vladimir Lenin" },
    { text: "Thất bại là mẹ thành công.", author: "Tục ngữ" },
    { text: "Không có việc gì khó, chỉ sợ lòng không bền.", author: "Hồ Chí Minh" },
    { text: "Sống là cho, đâu chỉ nhận riêng mình.", author: "Tố Hữu" },
    { text: "Muốn sang thì bắc cầu kiều, muốn con hay chữ thì yêu lấy thầy.", author: "Ca dao Việt Nam" },
    { text: "Uống nước nhớ nguồn.", author: "Tục ngữ Việt Nam" },
    { text: "Ăn quả nhớ kẻ trồng cây.", author: "Tục ngữ Việt Nam" },
    { text: "Một cây làm chẳng nên non, ba cây chụm lại nên hòn núi cao.", author: "Tục ngữ Việt Nam" },
    { text: "Hạnh phúc không phải là đích đến mà là cách bạn đi.", author: "Khuyết danh" },
    { text: "Ngày hôm nay là món quà, đó là lý do nó được gọi là hiện tại.", author: "Khuyết danh" },
    { text: "Thời gian là vàng.", author: "Tục ngữ" },
    { text: "Nhất tự vi sư, bán tự vi sư.", author: "Tục ngữ" },
    { text: "Đừng để ngày mai những gì có thể làm hôm nay.", author: "Benjamin Franklin" },
    {
      text: "Cuộc sống không phải là chờ đợi bão tố đi qua, mà là học cách nhảy múa dưới mưa.",
      author: "Vivian Greene",
    },
    { text: "Hãy là chính mình, vì tất cả những người khác đã có người làm rồi.", author: "Oscar Wilde" },
    {
      text: "Thành công không phải chìa khóa của hạnh phúc. Hạnh phúc là chìa khóa của thành công.",
      author: "Albert Schweitzer",
    },
    {
      text: "Đường đi khó không khó vì ngăn sông cách núi, mà khó vì lòng người ngại núi e sông.",
      author: "Nguyễn Bá Học",
    },
    { text: "Tốt gỗ hơn tốt nước sơn.", author: "Tục ngữ Việt Nam" },
    { text: "Có công mài sắt, có ngày nên kim.", author: "Tục ngữ Việt Nam" },
    { text: "Gần mực thì đen, gần đèn thì sáng.", author: "Tục ngữ Việt Nam" },
    { text: "Người ta là hoa đất.", author: "Tục ngữ Việt Nam" },
    { text: "Đói cho sạch, rách cho thơm.", author: "Tục ngữ Việt Nam" },
    { text: "Ở hiền gặp lành.", author: "Tục ngữ Việt Nam" },
    { text: "Giấy rách phải giữ lấy lề.", author: "Tục ngữ Việt Nam" },
    { text: "Năm mới tới, lộc mới về, vạn sự như ý.", author: "Lời chúc Tết" },
  ]

  // State: Quote hiện tại
  let currentQuote = null

  /**
   * Lấy quote dựa trên ngày (mỗi ngày 1 quote cố định)
   * @returns {Object} Quote object { text, author }
   */
  function getDailyQuote() {
    const today = new Date()
    // Tạo seed từ ngày để có cùng quote trong ngày
    const seed = today.getFullYear() * 10000 + (today.getMonth() + 1) * 100 + today.getDate()
    const index = seed % QUOTES.length
    currentQuote = QUOTES[index]
    return currentQuote
  }

  /**
   * Lấy quote ngẫu nhiên
   * @returns {Object} Quote object { text, author }
   */
  function getRandomQuote() {
    const index = Math.floor(Math.random() * QUOTES.length)
    currentQuote = QUOTES[index]
    return currentQuote
  }

  /**
   * Lấy quote hiện tại
   * @returns {Object|null}
   */
  function getCurrentQuote() {
    return currentQuote
  }

  /**
   * Lấy tổng số quotes
   * @returns {number}
   */
  function getTotalQuotes() {
    return QUOTES.length
  }

  /**
   * Thêm quote mới (cho phép mở rộng từ backend sau này)
   * @param {Object} quote - { text, author }
   */
  function addQuote(quote) {
    if (quote && quote.text && quote.author) {
      QUOTES.push(quote)
      return true
    }
    return false
  }

  // Public API
  return {
    getDailyQuote,
    getRandomQuote,
    getCurrentQuote,
    getTotalQuotes,
    addQuote,
  }
})()
