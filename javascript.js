let navbar=document.querySelector('.header .flex .navbar');
document.querySelector('#menu-btn').onclick=() =>{
    navbar.classList.toggle('active');
}
// Function to handle click event on "Job" button and scroll to job categories section
document.addEventListener('DOMContentLoaded', function() {
    var jobButton = document.getElementById('jobButton');
    var jobCategoriesSection = document.getElementById('latCategories');

    jobButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link behavior

        // Scroll to the job categories section
        jobCategoriesSection.scrollIntoView({ behavior: 'smooth' });
    });
});
// Function to handle click event on "Job categries" button and scroll to job categories section
document.addEventListener('DOMContentLoaded', function() {
    var jobButton = document.getElementById('jobCatButton');
    var jobCategoriesSection = document.getElementById('jobCategories');

    jobButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link behavior

        // Scroll to the job categories section
        jobCategoriesSection.scrollIntoView({ behavior: 'smooth' });
    });
});
// Function to handle click event on "about" button and scroll to job categories section
document.addEventListener('DOMContentLoaded', function() {
    var jobButton = document.getElementById('aboutButton');
    var jobCategoriesSection = document.getElementById('about');

    jobButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link behavior

        // Scroll to the job categories section
        jobCategoriesSection.scrollIntoView({ behavior: 'smooth' });
    });
});
// Function to handle click event on "contact" button and scroll to job categories section
document.addEventListener('DOMContentLoaded', function() {
    var jobButton = document.getElementById('contactButton');
    var jobCategoriesSection = document.getElementById('contact');

    jobButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link behavior

        // Scroll to the job categories section
        jobCategoriesSection.scrollIntoView({ behavior: 'smooth' });
    });
});