/* =========================================================
   CISC3003 Final Exam Paper 02C - Auth UI and Validation
   ========================================================= */
document.addEventListener('DOMContentLoaded', () => {
    const showClientMessage = (form, message) => {
        let notice = form.querySelector('[data-client-validation-message]');

        if (!notice) {
            notice = document.createElement('div');
            notice.setAttribute('data-client-validation-message', 'true');
            notice.className = 'notice error';
            form.prepend(notice);
        }

        notice.textContent = message;
    };

    document.querySelectorAll('[data-show-panel]').forEach((button) => {
        button.addEventListener('click', () => {
            const targetId = button.dataset.showPanel;

            document.querySelectorAll('.tab-button').forEach((tab) => tab.classList.remove('active'));
            document.querySelectorAll('.auth-panel').forEach((panel) => panel.classList.remove('active'));

            button.classList.add('active');
            document.getElementById(targetId)?.classList.add('active');
        });
    });

    document.querySelectorAll('[data-register-form]').forEach((form) => {
        const email = form.querySelector('[data-email-check]');
        const emailMessage = form.querySelector('[data-email-message]');
        const password = form.querySelector('input[name="password"]');
        const confirmation = form.querySelector('input[name="password_confirmation"]');

        if (email && emailMessage) {
            email.addEventListener('blur', async () => {
                const value = email.value.trim();

                if (!value) {
                    emailMessage.textContent = '';
                    emailMessage.className = 'field-hint';
                    return;
                }

                try {
                    const response = await fetch(`validate_email.php?email=${encodeURIComponent(value)}`);
                    const result = await response.json();
                    emailMessage.textContent = result.message;
                    emailMessage.className = `field-hint ${result.available ? 'ok' : 'bad'}`;
                } catch (error) {
                    emailMessage.textContent = 'Email availability check failed.';
                    emailMessage.className = 'field-hint bad';
                }
            });
        }

        form.addEventListener('submit', (event) => {
            if (!form.checkValidity()) {
                return;
            }

            if (password && !/(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}/.test(password.value)) {
                event.preventDefault();
                showClientMessage(form, 'JavaScript validation: password must be at least 8 characters and include uppercase, lowercase, and a number.');
                return;
            }

            if (password && confirmation && password.value !== confirmation.value) {
                event.preventDefault();
                showClientMessage(form, 'JavaScript validation: passwords do not match.');
            }
        });
    });

    document.querySelectorAll('[data-password-reset-form]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const password = form.querySelector('input[name="password"]');
            const confirmation = form.querySelector('input[name="password_confirmation"]');

            if (password && confirmation && password.value !== confirmation.value) {
                event.preventDefault();
                showClientMessage(form, 'JavaScript validation: passwords do not match.');
            }
        });
    });
});
