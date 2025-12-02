@extends('layouts.front-end.app')
@section('title', 'Blog Details')
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
        color: #222;
    }

    .blog-text {
        font-size: 14px;
        color: #555;
    }

    .read-more {
        text-decoration: none;
        font-size: 16px;
        font-weight: 600;
        color: #ff5d00;
        transition: 0.3s;
    }

    .read-more:hover {
        color: #ff5d00;
        text-decoration: underline;
    }

    .blog-card:hover .blog-img img {
        transform: scale(1.1);

    }

    .blog-card .category-btn {
        border-radius: 6px;
        border: 2px solid #ff5d00;
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
</style>
@section('content')
    <section class="py-3 career">
        <div class="container " style="min-height: 100vh;">
            <div class="row mb-3">
                <div class="col text-center">
                    <div class="section-heading-title">
                        <h3>Blogs Details</h3>
                        <div class="heading-border"></div>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-lg-6">
                     <img src="{{ asset('storage/blogs') }}/{{ $blog->image }}"
                                            onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'">
                </div>
                <div class="col-lg-6">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor facere enim, porro temporibus placeat recusandae omnis ratione sequi quidem dolorem necessitatibus, atque rerum tempore sed esse quisquam magni maiores rem consequatur quaerat? Similique nisi soluta nihil nulla iusto cupiditate maxime minima vero nobis accusamus dolorum possimus, repellendus quasi corrupti aliquam eveniet placeat. Impedit voluptates dolor, error tempora alias minus eligendi omnis eveniet quis, veniam voluptatem voluptate distinctio? Fuga quia ea in voluptatem fugiat nam! Culpa fugit perspiciatis eius suscipit dignissimos dolore nihil consequuntur! Temporibus corrupti ducimus iusto quas rerum atque, non accusantium provident maiores reprehenderit, placeat doloribus porro assumenda quaerat?
                </div>
            </div>
        </div>
    </section>
@endsection
