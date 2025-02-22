<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Portfolio</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
      html, body {
        height: 100%; /* Ensure body takes full height */
        margin: 0; /* Remove default margin */
        padding: 0; /* Remove default padding */
      }

      body {
        display: flex;
        flex-direction: column; /* Make body a flex container */
      }

      #content {
        flex: 1; /* Allow content to grow and fill available space */
      }

      footer {
        flex-shrink: 0; /* Prevent footer from shrinking */
      }
      
       /* Mock Browser Window Styles */
      .mock-browser-image-container {
        position: relative;
        height: 12rem; /* Fixed height for the container */
        overflow: hidden; /* Hide the overflowing part of the image */
      }

      .mock-browser-image-container img {
        display: block;
        width: 100%;
        height: auto;
        transition: transform 5s ease-in-out; /* Smooth scroll animation */
      }

      /* Hover effect to scroll the image to the end */
      .mock-browser-image-container:hover img {
        transform: translateY(calc(-100% + 12rem)); /* Scroll the image to the bottom */
      }
    </style>
    @yield('css')
  </head>
  <body class="bg-gray-50 relative">
    <div id="content" class="flex flex-col min-h-screen bg-gray-50">
      @include('front.layouts.nav')

      <main class="grow">
        @yield('content')
      </main>

      @include('front.layouts.footer')
    </div>

    @yield('js')

    <script>
      lucide.createIcons();

      // Toggle Mobile Menu
      const mobileMenuButton = document.getElementById('mobileMenuButton');
      const mobileMenu = document.getElementById('mobileMenu');

      mobileMenuButton.addEventListener('click', () => {
          mobileMenu.classList.toggle('hidden');
      });

      // Toggle Mobile Dropdown
      const mobileDropdownButton = document.getElementById('mobileDropdownButton');
      const mobileDropdown = document.getElementById('mobileDropdown');

      mobileDropdownButton.addEventListener('click', () => {
          mobileDropdown.classList.toggle('hidden');
      });

      // Close Mobile Menu When Clicking Outside
      document.addEventListener('click', (e) => {
          if (!mobileMenuButton.contains(e.target) && !mobileDropdownButton.contains(e.target)) {
              mobileMenu.classList.add('hidden');
              mobileDropdown.classList.add('hidden');
          }
      });
    </script>
  </body>
</html>