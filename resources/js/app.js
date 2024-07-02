require("./bootstrap");

$(document).ready(function () {
    $('[data-bs-toggle="tooltip"]').tooltip();
    // Show & Hide Password
    $(".togglePass").click(function () {
        let input = $(this).siblings("input");
        let icon = $(this).find("i");

        if (icon.hasClass("bi-eye-slash")) {
            input.attr("type", "text");
            icon.removeClass("bi-eye-slash").addClass("bi-eye");
        } else {
            input.attr("type", "password");
            icon.addClass("bi-eye-slash").removeClass("bi-eye");
        }
    });

    // table header filter option toggle
    $(".header-filter li").on("click", function (e) {
        e.preventDefault();
        $(this).addClass("active").siblings().removeClass("active");
        return false;
    });

    $(".matrix-btn").on("click", function (e) {
        $(".history-page").addClass("d-none");
        $(".matrix-page").removeClass("d-none");
        $(".back-btn")
            .parent()
            .removeClass("d-none")
            .siblings(".history-page-title")
            .addClass("d-none");
    });

    $(".back-btn").on("click", function (e) {
        $(".matrix-page").addClass("d-none");
        $(".history-page").removeClass("d-none");
        $(this)
            .parent()
            .addClass("d-none")
            .siblings(".history-page-title")
            .removeClass("d-none");
    });

    // Radial Progress bar
    $(window)
        .scroll(function () {
            $("svg.radial-progress").each(function (index, value) {
                // If svg.radial-progress is approximately 25% vertically into the window when scrolling from the top or the bottom
                if (
                    $(window).scrollTop() >
                        $(this).offset().top - $(window).height() * 0.75 &&
                    $(window).scrollTop() <
                        $(this).offset().top +
                            $(this).height() -
                            $(window).height() * 0.25
                ) {
                    // Get percentage of progress
                    percent = $(value).data("percentage");
                    // Get radius of the svg's circle.complete
                    radius = $(this).find($("circle.complete")).attr("r");
                    // Get circumference (2πr)
                    circumference = 2 * Math.PI * radius;
                    // Get stroke-dashoffset value based on the percentage of the circumference
                    strokeDashOffset =
                        circumference - (percent * circumference) / 100;
                    // Transition progress for 1.25 seconds
                    $(this)
                        .find($("circle.complete"))
                        .animate(
                            { "stroke-dashoffset": strokeDashOffset },
                            1250
                        );
                }
            });
        })
        .trigger("scroll");
});
