export default function () {
    // Form modal
    const formModal = document.getElementById("form-modal");

    if (formModal) {
        const openTriggers = Array.from(document.querySelectorAll("[data-modal-open]"));
        const closeTriggers = Array.from(document.querySelectorAll("[data-modal-close]"));

        // Bind open triggers
        openTriggers.forEach((trigger) => {
            trigger.addEventListener("click", openModal);
        });

        // Bind close triggers
        closeTriggers.forEach((trigger) => {
            trigger.addEventListener("click", closeModal);
        });

        function openModal(e) {
            e && e.preventDefault();
            formModal.classList.add("form-modal--open");
            document.body.classList.remove("menu-open");
        }

        function closeModal(e) {
            e && e.preventDefault();
            formModal.classList.remove("form-modal--open");
            window.sessionStorage.setItem("form-modal", "opened");
        }

        // Open after 15 seconds when not interacted with modal
        setTimeout(() => {
            var tmp = window.sessionStorage.getItem("form-modal");
            
            if (tmp !== "opened") {
                formModal.classList.add("form-modal--open");
            }
        }, 15000);
    }
}
