const hamburger = document.querySelector('.hamburger');
const navLinks = document.querySelector('.nav-links');

hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('active');
});


window.addEventListener('scroll', function () {
    const nav = document.querySelector('nav');
    if (window.scrollY > 50) {
        nav.classList.add('scrolled');
    } else {
        nav.classList.remove('scrolled');
    }
});

let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    const slides = document.getElementsByClassName("testimonial-slide");
    const dots = document.getElementsByClassName("dot");

    if (n > slides.length) { slideIndex = 1 }
    if (n < 1) { slideIndex = slides.length }

    for (let i = 0; i < slides.length; i++) {
        slides[i].classList.remove("active");
    }
    for (let i = 0; i < dots.length; i++) {
        dots[i].classList.remove("active");
    }

    slides[slideIndex - 1].classList.add("active");
    dots[slideIndex - 1].classList.add("active");
}

// Event Listeners
document.querySelector('.prev').addEventListener('click', () => plusSlides(-1));
document.querySelector('.next').addEventListener('click', () => plusSlides(1));

document.querySelectorAll('.dot').forEach((dot, index) => {
    dot.addEventListener('click', () => currentSlide(index + 1));
});


const ribbonContainer = document.getElementById('ribbon-container');

function createRibbon() {
    const ribbon = document.createElement('div');
    ribbon.className = 'ribbon';
    
    // Randomize the position
    const randomX = Math.random() * window.innerWidth;
    ribbon.style.left = `${randomX}px`;
    
    // Randomize animation duration
    const randomDuration = Math.random() * 2 + 3; // Between 3 and 5 seconds
    ribbon.style.animationDuration = `${randomDuration}s`;
    
    ribbonContainer.appendChild(ribbon);
    
    // Remove ribbon after animation ends
    ribbon.addEventListener('animationend', () => {
        ribbonContainer.removeChild(ribbon);
    });
}

// Create multiple ribbons
for (let i = 0; i < 20; i++) {
    setTimeout(createRibbon, i * 300); // Stagger the creation
}