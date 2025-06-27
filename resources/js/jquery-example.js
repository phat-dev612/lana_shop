// jQuery Example - Test file
$(document).ready(function () {
    console.log("jQuery is loaded successfully!");

    // Example: Add a click event to all buttons
    $("button").on("click", function () {
        console.log("Button clicked:", $(this).text());
    });

    // Example: Add smooth scrolling to anchor links
    $('a[href^="#"]').on("click", function (e) {
        e.preventDefault();
        const target = $(this.getAttribute("href"));
        if (target.length) {
            $("html, body").animate(
                {
                    scrollTop: target.offset().top - 100,
                },
                500
            );
        }
    });

    // Example: Add loading state to forms
    $("form").on("submit", function () {
        const submitBtn = $(this).find('button[type="submit"]');
        if (submitBtn.length) {
            submitBtn
                .prop("disabled", true)
                .html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...');
        }
    });
});
