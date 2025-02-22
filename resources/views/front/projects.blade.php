@extends('front.layouts.app')

@section('content')

<!-- Projects Content -->
<div class="pt-24 pb-16 bg-linear-to-b from-white to-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-12">
      <h2 class="text-3xl font-bold text-gray-900 relative">
        Projects
        <div class="absolute -bottom-2 left-0 w-24 h-1 bg-linear-to-r from-blue-500 to-purple-500"></div>
      </h2>
     
    </div>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      @foreach($projects as $project)
      <div class="group bg-white rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1 flex flex-col">
        <!-- Mock Browser Window -->
        <div class="relative overflow-hidden shrink-0">
          <!-- Browser Top Bar -->
          <div class="absolute top-0 left-0 w-full h-8 bg-gray-100 flex items-center px-3 space-x-2 z-10">
            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
          </div>
          <!-- Scrollable Image Container -->
          <div class="mock-browser-image-container h-48 overflow-hidden">
            <img
              src="{{asset('storage/'.$project->image)}}"
              alt="{{$project->title}}"
              class="w-full h-auto transition-transform duration-1000 ease-in-out group-hover:translate-y-[calc(-100%+12rem)]"
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
</div>

@endsection