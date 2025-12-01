/**
 * =====================================================
 * QUOTES CONTROLLER
 * Điều phối giữa QuotesModel và QuotesView
 * =====================================================
 */

// Sử dụng QuotesModel, QuotesView từ các file model/view
// và hàm showToast được khai báo global trong app.js

const QuotesController = (() => {
  /**
   * Hiển thị quote của ngày
   */
  function showDailyQuote() {
    const quote = QuotesModel.getDailyQuote();
    QuotesView.renderImmediate(quote);
  }

  /**
   * Hiển thị quote ngẫu nhiên
   */
  function showRandomQuote() {
    const quote = QuotesModel.getRandomQuote();
    QuotesView.render(quote);
  }

  /**
   * Copy quote hiện tại vào clipboard
   */
  async function copyQuote() {
    const quoteText = QuotesView.getQuoteText();

    try {
      await navigator.clipboard.writeText(quoteText);
      // Gọi utility function từ app.js
      if (typeof showToast === "function") {
        showToast("Đã sao chép quote! 📋");
      }
    } catch (err) {
      if (typeof showToast === "function") {
        showToast("Không thể sao chép!");
      }
    }
  }

  /**
   * Khởi tạo controller
   */
  function init() {
    // Khởi tạo view
    QuotesView.init();

    // Bind events
    const elements = QuotesView.getElements();
    elements.newQuoteBtn.addEventListener("click", showRandomQuote);
    elements.copyBtn.addEventListener("click", copyQuote);

    // Hiển thị quote của ngày
    showDailyQuote();
  }

  // Public API
  return {
    init,
    showDailyQuote,
    showRandomQuote,
    copyQuote,
  };
})();
