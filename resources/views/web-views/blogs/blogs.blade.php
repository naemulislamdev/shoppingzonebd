@extends('layouts.front-end.app')
@section('title', 'Blogs')

<style>
    .blog-card {
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.15);
        transition: 0.5s ease-in-out;
    }
    .blog-img {
        position: relative;
    }
    .blog-img div {
        border-radius: 10px 10px 0 0;
        overflow: hidden;
    }
    .blog-img img {
        max-width: 100%;
        height: auto;
        overflow: hidden;
        transition: all 0.4s ease;
    }
    .blog-title {
        font-size: 18px;
        font-weight: 600;
        color: #000;
    }
    .blog-text {
        font-size: 14px;
        color: #555;
    }
    .read-more {
        text-decoration: none;
        font-size: 16px;
        font-weight: 600;
        color: #000;
        transition: 0.3s;
    }
    .read-more:hover {
        color: #ff5d00;
    }
    .blog-card:hover .blog-img img {
        transform: scale(1.1);
    }
    .blog-card:hover .blog-title {
        color: #ff5d00;
    }
    .blog-card:hover .read-more {
        color: #ff5d00;
    }
    .blog-card .category-btn {
        border-radius: 6px;
        background-color: #ff5d00;
        color: #fff;
        position: absolute;
        right: 18px;
        bottom: -15px;
        padding: 3px 12px;
        font-weight: 500;
    }
    .category-btn.btn:focus {
        outline: 0;
        box-shadow: 0 0 0 .2rem rgba(255, 93, 0, 0.25);
    }
    .read-more svg {
        transition: all 0.3s;
    }
    .read-more:hover svg {
        transform: translateX(5px)
    }
</style>
@section('content')
    <section class="py-3 career">
        <div class="container " style="min-height: 100vh;">
            <div class="row mb-3">
                <div class="col text-center">
                    <div class="section-heading-title">
                        <h3>Blogs</h3>
                        <div class="heading-border"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($blogs as $blog)
                    <div class="col-md-4 mb-4">
                        <div class="blog-card shadow-sm">
                            <a href="{{route('blog.details', $blog->slug)}}">
                                <div class="blog-img">
                                    <div>
                                        <img src="{{ asset('storage/blogs') }}/{{ $blog->image }}"
                                            onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'">
                                    </div>

                                    <button class="btn btn-sm category-btn">{{ $blog->blogCategory->name }}</button>
                                </div>

                                <div class="blog-content p-4">
                                    <a href="{{route('blog.details', $blog->slug)}}">
                                        <h5 class="blog-title mt-3">{{ $blog->title }}</h5>
                                    </a>
                                    <p class="blog-text">
                                        @php
                                            $shortDesc = Str::limit(strip_tags($blog->description), 200);
                                        @endphp

                                    <p>{!! $shortDesc !!}</p>

                                    @if (strlen(strip_tags($blog->description)) > 500)
                                        <a href="{{route('blog.details', $blog->slug)}}" class="text-primary">
                                         ...
                                        </a>
                                    @endif
                                    </p>


                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <span class="text-muted fw-bold">📅 {{ $blog->created_at->format('d M Y') }}
                                        </span>
                                        <a href="#" class="read-more stretched-link d-flex align-items-center">Read More <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-narrow-right"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M15 16l4 -4" /><path d="M15 8l4 4" /></svg></a>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach

                <!-- End Blog Card -->
            </div>
        </div>
    </section>
@endsection
