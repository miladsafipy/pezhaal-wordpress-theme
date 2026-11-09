
document.addEventListener("DOMContentLoaded", function () {

    const menuToggle = document.getElementById("menu-toggle");
    const menuClose = document.getElementById("menu-close");

    const sidebar = document.getElementById("mobile-sidebar");
    const overlay = document.getElementById("mobile-overlay");

    if (!menuToggle || !menuClose || !sidebar || !overlay) {
        return;
    }

    // Open Sidebar
    function openSidebar() {


        sidebar.classList.add("is-open");
        overlay.classList.add("is-open");

        document.body.classList.add("sidebar-open");

        sidebar.setAttribute("aria-hidden", "false");
        menuToggle.setAttribute("aria-expanded", "true");
    }


    // Close Sidebar
    function closeSidebar() {

        sidebar.classList.remove("is-open");
        overlay.classList.remove("is-open");

        document.body.classList.remove("sidebar-open");

        sidebar.setAttribute("aria-hidden", "true");
        menuToggle.setAttribute("aria-expanded", "false");
    }


    // Events
    menuToggle.addEventListener("click", openSidebar);

    menuClose.addEventListener("click", closeSidebar);

    overlay.addEventListener("click", closeSidebar);


    // Close with Escape
    document.addEventListener("keydown", function (event) {

        if (event.key === "Escape") {
            closeSidebar();
        }

    });

});
