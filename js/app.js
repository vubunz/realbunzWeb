/**
 * =====================================================
 * APP.JS - Entry Point
 * Khởi tạo toàn bộ ứng dụng và các utility functions
 * =====================================================
 */

/**
 * Utility: Hiển thị toast notification
 * @param {string} message - Nội dung thông báo
 */
function showToast(message) {
  const toast = document.getElementById("toast");
  const toastMessage = document.getElementById("toastMessage");

  toastMessage.textContent = message;
  toast.classList.remove("translate-y-20", "opacity-0");
  toast.classList.add("translate-y-0", "opacity-100");

  setTimeout(() => {
    toast.classList.add("translate-y-20", "opacity-0");
    toast.classList.remove("translate-y-0", "opacity-100");
  }, 2500);
}

/**
 * Khởi tạo keyboard shortcuts
 */
function initKeyboardShortcuts() {
  document.addEventListener("keydown", (e) => {
    // Bỏ qua nếu đang focus vào input
    if (e.target.tagName === "INPUT" || e.target.tagName === "TEXTAREA") {
      return;
    }

    switch (e.key) {
      case "ArrowLeft":
        if (
          typeof CalendarController !== "undefined" &&
          typeof CalendarController.goToPrevMonth === "function"
        ) {
          CalendarController.goToPrevMonth();
        }
        break;
      case "ArrowRight":
        if (
          typeof CalendarController !== "undefined" &&
          typeof CalendarController.goToNextMonth === "function"
        ) {
          CalendarController.goToNextMonth();
        }
        break;
      case "t":
      case "T":
        if (
          typeof CalendarController !== "undefined" &&
          typeof CalendarController.goToToday === "function"
        ) {
          CalendarController.goToToday();
        }
        break;
      case "q":
      case "Q":
        if (
          typeof QuotesController !== "undefined" &&
          typeof QuotesController.showRandomQuote === "function"
        ) {
          QuotesController.showRandomQuote();
        }
        break;
      case "d":
      case "D":
        if (
          typeof ThemeController !== "undefined" &&
          typeof ThemeController.toggle === "function"
        ) {
          ThemeController.toggle();
        }
        break;
    }
  });
}

/**
 * Main initialization
 * Khởi tạo tất cả controllers khi DOM ready
 */
document.addEventListener("DOMContentLoaded", () => {
  // Fetch blog posts từ backend PHP (demo)
  fetch("backend/routes/posts.php")
    .then((res) => res.json())
    .then((payload) => {
      const container = document.getElementById("blogGrid");
      if (!container || !payload || !payload.data) return;

      container.innerHTML = "";
      payload.data.forEach((post) => {
        const article = document.createElement("article");
        article.className =
          "rounded-2xl bg-amber-50/80 dark:bg-slate-800 p-5 border border-amber-100 dark:border-slate-700";
        article.innerHTML = `
          <h4 class="font-semibold mb-2 text-slate-800 dark:text-slate-50">
            ${post.title}
          </h4>
          <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">
            ${post.summary || "Bài viết đang được cập nhật nội dung."}
          </p>
          <span class="inline-flex items-center text-xs font-medium text-slate-500 dark:text-slate-400">
            Slug: ${post.slug}
          </span>
        `;
        container.appendChild(article);
      });
    })
    .catch((err) => {
      console.error("Không tải được posts từ backend:", err);
    });

  // Khởi tạo các controllers theo thứ tự
  if (
    typeof ThemeController !== "undefined" &&
    typeof ThemeController.init === "function"
  ) {
    ThemeController.init(); // Theme trước để UI không bị flash
  }
  if (
    typeof CountdownController !== "undefined" &&
    typeof CountdownController.init === "function"
  ) {
    CountdownController.init(); // Countdown
  }
  if (
    typeof CalendarController !== "undefined" &&
    typeof CalendarController.init === "function"
  ) {
    CalendarController.init(); // Lịch vạn niên
  }
  if (
    typeof QuotesController !== "undefined" &&
    typeof QuotesController.init === "function"
  ) {
    QuotesController.init(); // Quotes
  }

  // Khởi tạo keyboard shortcuts
  initKeyboardShortcuts();

  // Log để debug (có thể xóa sau)
  console.log("🧧 Tết Countdown App initialized successfully!");
  console.log("📁 MVC Architecture: Models, Views, Controllers");
});
