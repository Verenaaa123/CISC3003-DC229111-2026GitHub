/* =========================================================
   CISC3003 Final Exam Paper 02A - Form Interaction
   ========================================================= */
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('[data-enhanced-form]');

    if (!form) {
        return;
    }

    form.addEventListener('submit', (event) => {
        const checkedSkills = form.querySelectorAll('input[name="skills[]"]:checked');
        const comments = form.querySelector('#comments');

        if (checkedSkills.length === 0) {
            event.preventDefault();
            alert('Please choose at least one skill to improve.');
            return;
        }

        if (comments && comments.value.trim().length < 10) {
            event.preventDefault();
            alert('Please write at least 10 characters in the learning goal.');
        }
    });
});
