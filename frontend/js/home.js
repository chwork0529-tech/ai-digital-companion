function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

function scrollFunction() {
    const backButton = document.getElementById('back-to-top');
    if (!backButton) return;

    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        backButton.style.display = 'block';
    } else {
        backButton.style.display = 'none';
    }
}

window.addEventListener('scroll', scrollFunction);