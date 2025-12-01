/**
 * =====================================================
 * QUOTES CONTROLLER
 * Điều phối giữa QuotesModel và QuotesView
 * =====================================================
 */

const QuotesModel = {
  // Declare QuotesModel here or import it
  getDailyQuote: () => {
    // Implementation to get daily quote
    return "Daily Quote"
  },
  getRandomQuote: () => {
    // Implementation to get random quote
    return "Random Quote"
  },
}

const QuotesView = {
  // Declare QuotesView here or import it
  init: () => {
    // Initialization code for QuotesView
  },
  getElements: () => ({
    newQuoteBtn: document.getElementById("new-quote-btn"),
    copyBtn: document.getElementById("copy-btn"),
  }),
  renderImmediate: (quote) => {
    // Code to render quote immediately
    console.log("Rendering daily quote:", quote)
  },
  render: (quote) => {
    // Code to render quote
    console.log("Rendering random quote:", quote)
  },
  getQuoteText: () => {
    // Code to get quote text
    return "Current Quote Text"
  },
}

const showToast = (message) => {
  // Declare showToast here or import it
  // Code to show toast message
  console.log(message)
}

const QuotesController = (() => {
  /**
   * Hiển thị quote của ngày
   */
  function showDailyQuote() {
    const quote = QuotesModel.getDailyQuote()
    QuotesView.renderImmediate(quote)
  }

  /**
   * Hiển thị quote ngẫu nhiên
   */
  function showRandomQuote() {
    const quote = QuotesModel.getRandomQuote()
    QuotesView.render(quote)
  }

  /**
   * Copy quote hiện tại vào clipboard
   */
  async function copyQuote() {
    const quoteText = QuotesView.getQuoteText()

    try {
      await navigator.clipboard.writeText(quoteText)
      // Gọi utility function từ app.js
      if (typeof showToast === "function") {
        showToast("Đã sao chép quote! 📋")
      }
    } catch (err) {
      if (typeof showToast === "function") {
        showToast("Không thể sao chép!")
      }
    }
  }

  /**
   * Khởi tạo controller
   */
  function init() {
    // Khởi tạo view
    QuotesView.init()

    // Bind events
    const elements = QuotesView.getElements()
    elements.newQuoteBtn.addEventListener("click", showRandomQuote)
    elements.copyBtn.addEventListener("click", copyQuote)

    // Hiển thị quote của ngày
    showDailyQuote()
  }

  // Public API
  return {
    init,
    showDailyQuote,
    showRandomQuote,
    copyQuote,
  }
})()
