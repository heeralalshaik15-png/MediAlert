document.addEventListener('DOMContentLoaded', function() {

  // --- Existing notification code ---
  if (window.Notification && Notification.permission !== "granted") {
    Notification.requestPermission().then(function(permission){
      // no-op
    });
  }

  // --- Theme toggle code ---
  const themeToggleBtn = document.getElementById('theme-toggle');

  // Check saved theme in localStorage
  if (localStorage.getItem('theme') === 'dark') {
    document.body.classList.add('dark-theme');
    if (themeToggleBtn) themeToggleBtn.textContent = '☀️'; // Sun icon for dark mode
  } else {
    if (themeToggleBtn) themeToggleBtn.textContent = '🌙'; // Moon icon for light mode
  }

  // Toggle theme on button click
  if (themeToggleBtn) {
    themeToggleBtn.addEventListener('click', () => {
      document.body.classList.toggle('dark-theme');

      if (document.body.classList.contains('dark-theme')) {
        localStorage.setItem('theme', 'dark');
        themeToggleBtn.textContent = '☀️';
      } else {
        localStorage.setItem('theme', 'light');
        themeToggleBtn.textContent = '🌙';
      }
    });
  }

});
