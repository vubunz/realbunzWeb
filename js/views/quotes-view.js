/**
 * =====================================================
 * QUOTES VIEW
 * Quản lý hiển thị UI cho module quotes
 * =====================================================
 */

const QuotesView = (() => {
  // DOM Elements cache
  let elements = {}

  /**
   * Khởi tạo cache DOM elements
   */
  function init() {
    elements = {
      container: document.getElementById("quoteContainer"),
      text: document.getElementById("quoteText"),
      author: document.getElementById("quoteAuthor"),
      newQuoteBtn: document.getElementById("newQuoteBtn"),
      copyBtn: document.getElementById("copyQuoteBtn"),
    }
  }

  /**
   * Render quote với animation
   * @param {Object} quote - { text, author }
   */
  function render(quote) {
    // Fade out
    elements.container.style.opacity = "0"

    setTimeout(() => {
      elements.text.textContent = `"${quote.text}"`
      elements.author.textContent = `— ${quote.author}`

      // Fade in
      elements.container.style.opacity = "1"
    }, 200)
  }

  /**
   * Render quote ngay lập tức (không animation)
   * @param {Object} quote - { text, author }
   */
  function renderImmediate(quote) {
    elements.text.textContent = `"${quote.text}"`
    elements.author.textContent = `— ${quote.author}`
  }

  /**
   * Lấy text quote hiện tại để copy
   * @returns {string}
   */
  function getQuoteText() {
    return `${elements.text.textContent}\n${elements.author.textContent}`
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
    renderImmediate,
    getQuoteText,
    getElements,
  }
})()
