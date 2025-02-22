<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid md:grid-cols-3 gap-8">
        <div>
            {{-- <h3 class="text-xl font-bold mb-4">{{settings()->get('site_name')}}</h3> --}}
            @if(settings()->get('logo'))
                <img src="{{ Storage::url(settings()->get('logo')) }}" alt="Site Logo" class="h-20">
            @endif
          <p class="text-gray-400">{{settings()->get('footer_text')}}</p>
        </div>
        <div>
          <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
          <ul class="space-y-2">
            <li><a href="about.html" class="text-gray-400 hover:text-white">About</a></li>
            <li><a href="projects.html" class="text-gray-400 hover:text-white">Projects</a></li>
            <li><a href="blog.html" class="text-gray-400 hover:text-white">Blog</a></li>
          </ul>
        </div>
        <div>
          <h4 class="text-lg font-semibold mb-4">Connect</h4>
          <div class="flex gap-x-4">
            <a href="{{settings()->get('social_github')}}" class="text-gray-400 hover:text-white">
              <i data-lucide="github" class="w-5 h-5"></i>
            </a>
            <a href="{{settings()->get('social_twitter')}}" class="text-gray-400 hover:text-white">
              <i data-lucide="twitter" class="w-5 h-5"></i>
            </a>
            <a href="{{settings()->get('social_linkedin')}}" class="text-gray-400 hover:text-white">
              <i data-lucide="linkedin" class="w-5 h-5"></i>
            </a>
            <a href="mailto:{{settings()->get('site_email')}}?subject=Hello&body=I want to get in touch" class="text-gray-400 hover:text-white">
              <i data-lucide="mail" class="w-5 h-5"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> {{settings()->get('site_name')}}. All rights reserved.</p>
       </div>
    </div>
  </footer>