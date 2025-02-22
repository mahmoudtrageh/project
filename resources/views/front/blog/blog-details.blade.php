@extends('front.layouts.app')

@section('content')

<div class="max-w-4xl mx-auto px-4 py-8 mt-20">
    <!-- Blog Header -->
    <div class="mb-8">
      <h1 class="text-4xl font-bold text-gray-900 mb-4">{{$blog->title}}</h1>
      <div class="flex items-center gap-x-4 text-gray-600">
       
        <span>March 15, 2024</span>
      </div>
    </div>

    <!-- Featured Image -->
    <div class="mb-8">
      <img src="{{asset('storage/'.$blog->image)}}" alt="Blog featured image" class="w-full h-[400px] object-cover rounded-lg">
    </div>

    <!-- Blog Content -->
    <article class="prose lg:prose-xl">
      <div class="bg-white rounded-lg shadow-md p-8">
        <p class="text-gray-700 mb-6 leading-relaxed">
            {{$blog->content}}
        </p>

       
      </div>
    </article>

    
  </div>

@endsection