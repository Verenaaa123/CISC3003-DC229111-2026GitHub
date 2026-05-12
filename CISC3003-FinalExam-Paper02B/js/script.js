/* =========================================================
   CISC3003 Final Exam Paper 02B - Contact Form Validation
   ========================================================= */
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('[data-contact-form]');

    if (!form) {
        return;
    }

    const showClientMessage = (message) => {
        let notice = form.querySelector('[data-client-validation-message]');

        if (!notice) {
            notice = document.createElement('div');
            notice.setAttribute('data-client-validation-message', 'true');
            notice.className = 'notice error';
            form.prepend(notice);
        }

        notice.textContent = message;
    };

    form.addEventListener('submit', (event) => {
        const subject = form.subject.value.trim();
        const message = form.message.value.trim();

        if (!form.checkValidity()) {
            event.preventDefault();
            showClientMessage('Client-side validation: please complete all contact form fields correctly.');
            return;
        }

        if (subject.length < 4) {
            event.preventDefault();
            showClientMessage('Client-side validation: subject must contain at least 4 characters.');
            return;
        }

        if (message.length < 10) {
            event.preventDefault();
            showClientMessage('Client-side validation: message must contain at least 10 characters.');
        }
    });
});
