@extends('front.layouts.app')

@section('content')

<!-- Blog Content -->
<div class="pt-24 pb-16 bg-gradient-to-b from-white to-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-12">
        <h2 class="text-3xl font-bold text-gray-900 relative">Blog Posts
          <div class="absolute -bottom-2 left-0 w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-500"></div>
        </h2>
       
    </div>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($blogs as $blog)
        <article class="group bg-white rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1 flex flex-col">
          <div class="relative overflow-hidden flex-shrink-0">
              <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 to-purple-500/10 group-hover:opacity-75 transition-opacity duration-300"></div>  
              <img
                src="{{asset('storage/'.$blog->image)}}"
                alt="Blog post 1"
                class="w-full h-48 object-cover transform transition-transform duration-500 group-hover:scale-110"
              />
          </div>
            <div class="p-6 flex flex-col flex-grow">
              <div class="flex items-center mb-4">
                  <div>
                    <p class="text-xs text-gray-500">March 15, 2024</p>
                  </div>
              </div>
              <h3 class="text-xl font-semibold mb-2 text-gray-900 group-hover:text-blue-600 transition-colors duration-300">{{$blog->title}}</h3>
              <p class="text-gray-600 mb-4 flex-grow">{{getCleanContentAttribute($blog->content)}}</p>
              
              <div class="flex justify-between items-center mt-auto">
                <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-300">
                    Read More
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
              </div>
            </div>
        </article>
        @endforeach
    </div>
  </div>
</div>

@endsection