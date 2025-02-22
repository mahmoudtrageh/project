@extends('front.layouts.app')

@section('css')
<!-- Custom CSS for Animations -->
<style>
  @keyframes fade-in {
      0% { opacity: 0; transform: translateY(20px); }
      100% { opacity: 1; transform: translateY(0); }
  }

  .animate-fade-in {
      animation: fade-in 0.5s ease-out forwards;
  }

  .delay-100 {
      animation-delay: 0.1s;
  }

  .delay-200 {
      animation-delay: 0.2s;
  }

  .delay-300 {
      animation-delay: 0.3s;
  }

  body {
    overflow-x: hidden;
  }

</style>
@endsection

@section('content')

<!-- Decorative background shapes -->
<div class="absolute top-0 right-0 -z-10 w-96 h-96 bg-blue-100 rounded-full blur-3xl opacity-30 transform translate-x-1/2 -translate-y-1/2"></div>
<div class="absolute bottom-0 left-0 -z-10 w-96 h-96 bg-purple-100 rounded-full blur-3xl opacity-30 transform -translate-x-1/2 translate-y-1/2"></div>

<!-- About Section -->
<section id="about" class="pt-32 pb-24 relative overflow-hidden">
  <!-- Decorative elements -->
  <div class="absolute top-0 right-0 w-96 h-96 bg-blue-50 rounded-full transform translate-x-1/3 -translate-y-1/3 opacity-50"></div>
  <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-50 rounded-full transform -translate-x-1/3 translate-y-1/3 opacity-50"></div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
  
      <div class="lg:flex items-center gap-16">
          <div class="lg:w-1/2 mb-12 lg:mb-0 relative group">
               <!-- Image container with hover effect -->
          <div class="relative overflow-hidden rounded-2xl shadow-2xl transition-all duration-300 group-hover:shadow-3xl">
              <div class="absolute inset-0 bg-linear-to-r from-blue-500/10 to-purple-500/10 group-hover:opacity-75 transition-opacity duration-300"></div>
            @if(settings()->get('about_image'))
                <img src="{{ Storage::url(settings()->get('about_image')) }}" alt="Site Logo" class="w-full h-96 object-cover transform transition-transform duration-500 group-hover:scale-110">
            @else
                <img
                    src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=600"
                    alt="Profile"
                    class="w-full h-96 object-cover transform transition-transform duration-500 group-hover:scale-110"
                />
            @endif
          <!-- Decorative corner elements -->
          <div class="absolute top-0 right-0 w-24 h-24 bg-blue-100/50 transform rotate-45 translate-x-12 -translate-y-12"></div>
          <div class="absolute bottom-0 left-0 w-24 h-24 bg-purple-100/50 transform rotate-45 -translate-x-12 translate-y-12"></div>
        </div>
      </div>
      <div class="lg:w-1/2 relative">
          <h1 class="text-5xl font-bold text-gray-900 mb-6 flex items-center">Hi, I'm {{settings()->get('about_name')}}
              <span class="ms-3 inline-block w-4 h-4 bg-green-500 rounded-full"></span>
          </h1>
            <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                {{settings()->get('about_description')}}
            </p>
            <div class="flex gap-x-6 mb-8">
                <a href="{{settings()->get('social_github')}}" class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:bg-blue-500 hover:text-white transition-colors duration-300">
                    <i data-lucide="github" class="w-8 h-8"></i>
                </a>
                <a href="{{settings()->get('social_twitter')}}" class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:bg-blue-500 hover:text-white transition-colors duration-300">
                    <i data-lucide="twitter" class="w-8 h-8"></i>
                </a>
                <a href="{{settings()->get('social_linkedin')}}" class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:bg-blue-500 hover:text-white transition-colors duration-300">
                    <i data-lucide="linkedin" class="w-8 h-8"></i>
                </a>
                <a href="mailto:{{settings()->get('site_email')}}?subject=Hello&body=I want to get in touch" class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:bg-blue-500 hover:text-white transition-colors duration-300">
                    <i data-lucide="mail" class="w-8 h-8"></i>
                </a>
            </div>
            <a href="{{route('about')}}" class="inline-flex items-center px-6 py-3 bg-linear-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:from-blue-600 hover:to-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl text-lg font-semibold">
                Learn more about me  <svg class="w-6 h-6 ms-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</div>
</section>

<!-- Services Section -->
<section id="services" class="py-16 bg-linear-to-b from-white to-gray-50 relative overflow-hidden">

  <!-- Decorative Shapes -->
  <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-linear-to-r from-blue-500 to-purple-500 rounded-full opacity-5 blur-3xl"></div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <!-- Section Heading -->
    <div class="text-center mb-16">
      <h2 class="text-3xl font-bold text-gray-900 relative inline-block">
        My Services
        <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-linear-to-r from-blue-500 to-purple-500"></div>
      </h2>
      <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
        I specialize in web development, API development, and consulting to help you build scalable, efficient, and user-friendly solutions.
      </p>
    </div>

    <!-- Services Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- Web Development -->
      <div class="group bg-white rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <div class="p-8">
          <div class="flex items-center justify-center w-16 h-16 bg-blue-50 rounded-full mb-6">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-4">Web Development</h3>
          <p class="text-gray-600 mb-4">
            I build responsive, user-friendly websites that are optimized for performance and scalability. From simple blogs to complex web applications, I deliver solutions tailored to your needs.
          </p>
          {{-- <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-300">
            Learn More
            <svg class="w-4 h-4 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
          </a> --}}
        </div>
      </div>

      <!-- API Development -->
      <div class="group bg-white rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <div class="p-8">
          <div class="flex items-center justify-center w-16 h-16 bg-purple-50 rounded-full mb-6">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-4">API Development</h3>
          <p class="text-gray-600 mb-4">
            I design and develop robust, secure, and scalable APIs to power your applications. Whether RESTful or GraphQL, I ensure seamless integration and performance.
          </p>
          {{-- <a href="#" class="inline-flex items-center text-purple-600 hover:text-purple-800 transition-colors duration-300">
            Learn More
            <svg class="w-4 h-4 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
          </a> --}}
        </div>
      </div>

      <!-- Consulting -->
      <div class="group bg-white rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <div class="p-8">
          <div class="flex items-center justify-center w-16 h-16 bg-blue-50 rounded-full mb-6">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-4">Consulting</h3>
          <p class="text-gray-600 mb-4">
            I provide expert consulting services to help you plan, design, and implement your projects. From architecture reviews to performance optimization, I’ve got you covered.
          </p>
          {{-- <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-300">
            Learn More
            <svg class="w-4 h-4 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
          </a> --}}
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Tech Stack Section -->
<section id="tech-stack" class="py-16 bg-linear-to-b from-gray-50 to-white relative overflow-hidden">

  <!-- Decorative Shapes -->
  <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-linear-to-r from-blue-500 to-purple-500 rounded-full opacity-5 blur-3xl"></div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <!-- Section Heading -->
    <div class="text-center mb-16">
      <h2 class="text-3xl font-bold text-gray-900 relative inline-block">
        My Tech Stack
        <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-linear-to-r from-blue-500 to-purple-500"></div>
      </h2>
      <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
        We use the latest technologies and tools to build innovative and scalable solutions. Here are some of the technologies we work with:
      </p>
    </div>

    <!-- Tech Stack Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-8">
     
      <!-- Tech Item 4: PHP -->
      <div class="group flex flex-col items-center justify-center p-6 bg-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg" alt="PHP" class="w-16 h-16 mb-4" />
        <p class="text-lg font-semibold text-gray-900">PHP</p>
      </div>

    <!-- Tech Item 5: Laravel -->
    <div class="group flex flex-col items-center justify-center p-6 bg-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
      <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/laravel/laravel-plain.svg" alt="Laravel" class="w-16 h-16 mb-4" onerror="this.onerror=null; this.src='https://laravel.com/img/logomark.min.svg';" />
      <p class="text-lg font-semibold text-gray-900">Laravel</p>
    </div>

       <!-- MySQL Section -->
      <div class="group flex flex-col items-center justify-center p-6 bg-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg" alt="MySQL" class="w-16 h-16 mb-4" />
        <p class="text-lg font-semibold text-gray-900">MySQL</p>
      </div>

       <!-- Tech Item 3: Tailwind CSS -->
       <div class="group flex flex-col items-center justify-center p-6 bg-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tailwindcss/tailwindcss-plain.svg" alt="Tailwind CSS" class="w-16 h-16 mb-4" onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/commons/d/d5/Tailwind_CSS_Logo.svg';" />
        <p class="text-lg font-semibold text-gray-900">Tailwind CSS</p>
      </div>

        <!-- JavaScript Section -->
        <div class="group flex flex-col items-center justify-center p-6 bg-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
          <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg" alt="JavaScript" class="w-16 h-16 mb-4" />
          <p class="text-lg font-semibold text-gray-900">JavaScript</p>
        </div>

        <!-- jQuery Section -->
        <div class="group flex flex-col items-center justify-center p-6 bg-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
          <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/jquery/jquery-original.svg" alt="jQuery" class="w-16 h-16 mb-4" />
          <p class="text-lg font-semibold text-gray-900">jQuery</p>
        </div>

      {{-- <!-- Tech Item 1: React -->
      <div class="group flex flex-col items-center justify-center p-6 bg-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/react/react-original.svg" alt="React" class="w-16 h-16 mb-4" />
        <p class="text-lg font-semibold text-gray-900">React</p>
      </div>
       <!-- Tech Item 6: Next.js -->
       <div class="group flex flex-col items-center justify-center p-6 bg-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nextjs/nextjs-original.svg" alt="Next.js" class="w-16 h-16 mb-4" />
        <p class="text-lg font-semibold text-gray-900">Next.js</p>
      </div> --}}

    </div>
  </div>
</section>

<!-- Projects Section -->
<section id="projects" class="py-16 bg-linear-to-b from-white to-gray-50 relative overflow-hidden">
  <!-- Decorative Shapes -->
  <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-linear-to-r from-blue-500 to-purple-500 rounded-full opacity-5 blur-3xl"></div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="flex justify-between items-center mb-12">
      <h2 class="text-3xl font-bold text-gray-900 relative">
        Featured Projects
        <div class="absolute -bottom-2 left-0 w-24 h-1 bg-linear-to-r from-blue-500 to-purple-500"></div>
      </h2>
      <a href="projects.html" class="group inline-flex items-center px-4 py-2 text-blue-600 hover:text-blue-800 transition-colors duration-300">
        View all projects
        <svg class="w-4 h-4 ms-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
        </svg>
      </a>
    </div>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      @foreach($projects as $project)
      <div class="group bg-white rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1 flex flex-col">
        <!-- Mock Browser Window -->
        <div class="relative overflow-hidden shrink-0">
          <!-- Browser Top Bar -->
          <div class="absolute top-0 left-0 w-full h-8 bg-gray-100 flex items-center px-3 gap-x-2 z-10">
            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
          </div>
          <!-- Scrollable Image Container -->
          <div class="mock-browser-image-container h-48 overflow-hidden">
            <img
              src="{{asset('storage/'.$project->image)}}"
              alt="{{$project->title}}"
              class="w-full h-auto transition-transform duration-1000 ease-in-out group-hover:translate-y-[-50%]"
            />
          </div>
          <!-- Shape Overlay -->
          <div class="absolute inset-0 bg-linear-to-r from-blue-500/20 to-purple-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        </div>
        <!-- Project Details -->
        <div class="p-6 flex flex-col grow">
          <h3 class="text-xl font-semibold mb-2 text-gray-900">{{$project->title}}</h3>
          <p class="text-gray-600 mb-4 grow">{{getCleanContentAttribute($project->description)}}</p>
          <a href="{{$project->url}}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-300 mt-auto">
            View Project
            <svg class="w-4 h-4 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
          </a>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- Clients Section -->
<section id="clients" class="py-16 bg-linear-to-b from-gray-50 to-white relative overflow-hidden">

  <!-- Decorative Shapes -->
  <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-linear-to-r from-blue-500 to-purple-500 rounded-full opacity-5 blur-3xl"></div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <!-- Section Heading -->
    <div class="text-center mb-16">
      <h2 class="text-3xl font-bold text-gray-900 relative inline-block">
        Clients I've Worked With
        <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-linear-to-r from-blue-500 to-purple-500"></div>
      </h2>
      <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
        I’ve had the privilege of working with some amazing companies and brands. Here are a few of them:
      </p>
    </div>

    <!-- Clients Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-8">
      <!-- Client 1 -->
      <div class="flex items-center justify-center p-6 bg-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <img src="https://scontent.fcai21-3.fna.fbcdn.net/v/t39.30808-6/350658951_162858643250265_7449091825014918868_n.jpg?_nc_cat=100&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=oi_gyC6G7EYQ7kNvgExipc2&_nc_oc=Adjl2ybUrpTaYV3YLkUrcax1ch8rBLyCaAUf7BjbfgLdJq9akRBmTikQOKl7LonAGmk&_nc_zt=23&_nc_ht=scontent.fcai21-3.fna&_nc_gid=Al6P6t6636kT8y_TkKUEXhb&oh=00_AYAss-ZMUPrnVEjIfV89ZXeB-HuIkNq0JThGODkYZguz2A&oe=67BD8FFE" alt="Client Logo" class="w-24 h-24 object-contain" />
      </div>

      <!-- Client 2 -->
      <div class="flex items-center justify-center p-6 bg-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <img src="https://metacodya.com/wp-content/uploads/2024/12/metacodya-logo-1400x379.png" alt="Client Logo" class="w-24 h-24 object-contain" />
      </div>

      <!-- Client 3 -->
      <div class="flex items-center justify-center p-6 bg-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <img src="https://tazamun.ae/web-hosting/img/logoheader.png" alt="Client Logo" class="w-24 h-24 object-contain" />
      </div>

      <!-- Client 4 -->
      <div class="flex items-center justify-center p-6 bg-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <img src="https://longimanus-liveaboard.com/public/front/images/logo.jpeg" alt="Client Logo" class="w-24 h-24 object-contain" />
      </div>

      <!-- Client 5 -->
      <div class="flex items-center justify-center p-6 bg-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <img src="https://dsyncsolutions.com/uploads/media/gI9EGxTveIi19rGs0roZ115pMbw0PxUldoEb9UxG.svg" alt="Client Logo" class="w-24 h-24 object-contain" />
      </div>

      <!-- Client 6 -->
      <div class="flex items-center justify-center p-6 bg-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <img src="https://www.vowalaa.com/public/assets/images/home/logo.webp" alt="Client Logo" class="w-24 h-24 object-contain" />
      </div>
    </div>
  </div>
</section>

<!-- Pricing Section -->
{{-- <section id="pricing" class="py-16 bg-linear-to-b from-gray-50 to-white relative overflow-hidden">

  <!-- Decorative Shapes -->
  <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-linear-to-r from-blue-500 to-purple-500 rounded-full opacity-5 blur-3xl"></div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <!-- Section Heading -->
    <div class="text-center mb-16">
      <h2 class="text-3xl font-bold text-gray-900 relative inline-block">
        Pricing
        <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-linear-to-r from-blue-500 to-purple-500"></div>
      </h2>
      <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
        Choose a plan that fits your needs. All plans come with a 100% satisfaction guarantee.
      </p>
    </div>

    <!-- Pricing Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- Basic Plan -->
      <div class="group bg-white rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <div class="p-8 text-center">
          <h3 class="text-xl font-semibold text-gray-900 mb-4">Basic Plan</h3>
          <p class="text-gray-600 mb-4">Perfect for small projects or startups.</p>
          <p class="text-4xl font-bold text-gray-900 mb-6">$499</p>
          <ul class="text-gray-600 mb-6">
            <li class="mb-2">1 Website</li>
            <li class="mb-2">5 Pages</li>
            <li class="mb-2">Basic SEO</li>
            <li class="mb-2">1 Revision</li>
            <li class="mb-2">1 Month Support</li>
          </ul>
          <a href="#" class="inline-flex items-center justify-center w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300">
            Get Started
          </a>
        </div>
      </div>

      <!-- Standard Plan -->
      <div class="group bg-white rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <div class="p-8 text-center">
          <h3 class="text-xl font-semibold text-gray-900 mb-4">Standard Plan</h3>
          <p class="text-gray-600 mb-4">Ideal for growing businesses.</p>
          <p class="text-4xl font-bold text-gray-900 mb-6">$999</p>
          <ul class="text-gray-600 mb-6">
            <li class="mb-2">1 Website</li>
            <li class="mb-2">10 Pages</li>
            <li class="mb-2">Advanced SEO</li>
            <li class="mb-2">3 Revisions</li>
            <li class="mb-2">3 Months Support</li>
          </ul>
          <a href="#" class="inline-flex items-center justify-center w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-300">
            Get Started
          </a>
        </div>
      </div>

      <!-- Premium Plan -->
      <div class="group bg-white rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <div class="p-8 text-center">
          <h3 class="text-xl font-semibold text-gray-900 mb-4">Premium Plan</h3>
          <p class="text-gray-600 mb-4">Best for large-scale projects.</p>
          <p class="text-4xl font-bold text-gray-900 mb-6">$1999</p>
          <ul class="text-gray-600 mb-6">
            <li class="mb-2">1 Website</li>
            <li class="mb-2">Unlimited Pages</li>
            <li class="mb-2">Advanced SEO</li>
            <li class="mb-2">Unlimited Revisions</li>
            <li class="mb-2">6 Months Support</li>
          </ul>
          <a href="#" class="inline-flex items-center justify-center w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300">
            Get Started
          </a>
        </div>
      </div>
    </div>
  </div>
</section> --}}

<!-- Testimonial Section -->
{{-- <section id="testimonials" class="py-16 bg-linear-to-b from-white to-gray-50 relative overflow-hidden">

  <!-- Decorative Shapes -->
  <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-linear-to-r from-blue-500 to-purple-500 rounded-full opacity-5 blur-3xl"></div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <!-- Section Heading -->
    <div class="text-center mb-16">
      <h2 class="text-3xl font-bold text-gray-900 relative inline-block">
        What Our Clients Say
        <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-linear-to-r from-blue-500 to-purple-500"></div>
      </h2>
      <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
        Hear from our satisfied clients who have experienced our services.
      </p>
    </div>

    <!-- Testimonial Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- Testimonial 1 -->
      <div class="bg-white rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <div class="p-8">
          <div class="flex items-center mb-4">
            <img class="w-12 h-12 rounded-full object-cover" src="https://placehold.co/150" alt="Client 1">
            <div class="ms-4">
              <h3 class="text-lg font-semibold text-gray-900">John Doe</h3>
              <p class="text-sm text-gray-600">CEO, Company A</p>
            </div>
          </div>
          <p class="text-gray-600">
            "The service was exceptional! The team went above and beyond to deliver exactly what we needed. Highly recommend!"
          </p>
        </div>
      </div>

      <!-- Testimonial 2 -->
      <div class="bg-white rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <div class="p-8">
          <div class="flex items-center mb-4">
            <img class="w-12 h-12 rounded-full object-cover" src="https://placehold.co/150" alt="Client 2">
            <div class="ms-4">
              <h3 class="text-lg font-semibold text-gray-900">Jane Smith</h3>
              <p class="text-sm text-gray-600">Founder, Startup B</p>
            </div>
          </div>
          <p class="text-gray-600">
            "Incredible experience from start to finish. The team was professional, responsive, and delivered on time. Will work with them again!"
          </p>
        </div>
      </div>

      <!-- Testimonial 3 -->
      <div class="bg-white rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <div class="p-8">
          <div class="flex items-center mb-4">
            <img class="w-12 h-12 rounded-full object-cover" src="https://placehold.co/150" alt="Client 3">
            <div class="ms-4">
              <h3 class="text-lg font-semibold text-gray-900">Michael Brown</h3>
              <p class="text-sm text-gray-600">CTO, Enterprise C</p>
            </div>
          </div>
          <p class="text-gray-600">
            "The quality of work exceeded our expectations. The team was knowledgeable and easy to work with. Highly satisfied!"
          </p>
        </div>
      </div>

      <!-- Testimonial 4 -->
      <div class="bg-white rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <div class="p-8">
          <div class="flex items-center mb-4">
            <img class="w-12 h-12 rounded-full object-cover" src="https://placehold.co/150" alt="Client 4">
            <div class="ms-4">
              <h3 class="text-lg font-semibold text-gray-900">Sarah Johnson</h3>
              <p class="text-sm text-gray-600">Marketing Director, Agency D</p>
            </div>
          </div>
          <p class="text-gray-600">
            "Fantastic service! The team was creative, efficient, and delivered outstanding results. Couldn't be happier!"
          </p>
        </div>
      </div>

      <!-- Testimonial 5 -->
      <div class="bg-white rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <div class="p-8">
          <div class="flex items-center mb-4">
            <img class="w-12 h-12 rounded-full object-cover" src="https://placehold.co/150" alt="Client 5">
            <div class="ms-4">
              <h3 class="text-lg font-semibold text-gray-900">David Wilson</h3>
              <p class="text-sm text-gray-600">Product Manager, Tech E</p>
            </div>
          </div>
          <p class="text-gray-600">
            "The team was a pleasure to work with. They understood our vision and delivered a product that perfectly matched our needs."
          </p>
        </div>
      </div>

      <!-- Testimonial 6 -->
      <div class="bg-white rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
        <div class="p-8">
          <div class="flex items-center mb-4">
            <img class="w-12 h-12 rounded-full object-cover" src="https://placehold.co/150" alt="Client 6">
            <div class="ms-4">
              <h3 class="text-lg font-semibold text-gray-900">Emily Davis</h3>
              <p class="text-sm text-gray-600">Design Lead, Creative F</p>
            </div>
          </div>
          <p class="text-gray-600">
            "The service was top-notch. The team was collaborative, innovative, and delivered beyond our expectations. Highly recommend!"
          </p>
        </div>
      </div>
    </div>
  </div>
</section> --}}

<section class="py-16 bg-linear-to-b from-white to-gray-50 relative overflow-hidden">
  <!-- Decorative Shapes -->
  <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-linear-to-r from-blue-500 to-purple-500 rounded-full opacity-5 blur-3xl"></div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="flex justify-between items-center mb-12">
      <h2 class="text-3xl font-bold text-gray-900 relative">
        Latest Videos
        <div class="absolute -bottom-2 left-0 w-24 h-1 bg-linear-to-r from-blue-500 to-purple-500"></div>
      </h2>
      <a href="#" class="group inline-flex items-center px-4 py-2 text-blue-600 hover:text-blue-800 transition-colors duration-300">
        View all videos
        <svg class="w-4 h-4 ms-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
        </svg>
      </a>
    </div>

    <!-- Videos Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- Video Card 1 -->
      <div class="group relative">
        <img src="https://placehold.co/640x360" alt="Video Title" class="w-full aspect-video object-cover rounded-lg">
        <div class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-sm px-2 py-1 rounded-sm">
          12:34
        </div>
      </div>

      <!-- Video Card 2 -->
      <div class="group relative">
        <img src="https://placehold.co/640x360" alt="Video Title" class="w-full aspect-video object-cover rounded-lg">
        <div class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-sm px-2 py-1 rounded-sm">
          15:20
        </div>
      </div>

      <!-- Video Card 3 -->
      <div class="group relative">
        <img src="https://placehold.co/640x360" alt="Video Title" class="w-full aspect-video object-cover rounded-lg">
        <div class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-sm px-2 py-1 rounded-sm">
          08:45
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Blog Section -->
<section id="blog" class="py-16 relative overflow-hidden">

  <!-- Decorative Shapes -->
  <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-linear-to-r from-blue-500 to-purple-500 rounded-full opacity-5 blur-3xl"></div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="flex justify-between items-center mb-12">
          <h2 class="text-3xl font-bold text-gray-900 relative">Latest Blog Posts
            <div class="absolute -bottom-2 left-0 w-24 h-1 bg-linear-to-r from-blue-500 to-purple-500"></div>
          </h2>
          <a href="blog.html" class="group inline-flex items-center px-4 py-2 text-blue-600 hover:text-blue-800 transition-colors duration-300">
              View all posts  
              <svg class="w-4 h-4 ms-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
              </svg>
          </a>
      </div>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          @foreach($blogs as $blog)
          <article class="group bg-white rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1 flex flex-col">
            <div class="relative overflow-hidden shrink-0">
                <div class="absolute inset-0 bg-linear-to-r from-blue-500/10 to-purple-500/10 group-hover:opacity-75 transition-opacity duration-300"></div>  
                <img
                  src="{{asset('storage/'.$blog->image)}}"
                  alt="Blog post 1"
                  class="w-full h-48 object-cover transform transition-transform duration-500 group-hover:scale-110"
                />
                <!-- Shape Overlay -->
                <div class="absolute inset-0 bg-linear-to-r from-blue-500/20 to-purple-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </div>
              <div class="p-6 flex flex-col grow">
                <div class="flex items-center mb-4">
                    <div>
                      <p class="text-xs text-gray-500">March 15, 2024</p>
                    </div>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-gray-900 group-hover:text-blue-600 transition-colors duration-300">{{$blog->title}}</h3>
                <p class="text-gray-600 mb-4 grow">{{getCleanContentAttribute($blog->content)}}</p>
                
                <div class="flex justify-between items-center mt-auto">
                  <div class="flex items-center text-gray-500 text-sm">
                      <!-- Optional: Add author or category here -->
                  </div>
                  <a href="{{route('blog.details', $blog)}}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-300">
                      Read More
                      <svg class="w-4 h-4 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                      </svg>
                  </a>
                </div>
              </div>
          </article>
          @endforeach
      </div>
  </div>
</section>

@endsection