const btn = document.querySelector('.top-button');

window.addEventListener('scroll', () => {
    if (window.scrollY > 300) {
        btn.style.display = 'inline-block';
    } else {
        btn.style.display = 'none';
    }
});

btn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
})