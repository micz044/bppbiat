document.addEventListener('DOMContentLoaded', () => {
    const mainSections = document.querySelectorAll('.main-section');

    mainSections.forEach(section => {
        section.style.opacity = 0;
        section.style.transform = 'translateY(20px)';
        setTimeout(() => {
            section.style.transition = 'all 1s ease';
            section.style.opacity = 1;
            section.style.transform = 'translateY(0)';
        }, 200);
    });
});