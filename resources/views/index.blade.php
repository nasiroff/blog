@extends('layouts.app')

@section('title', 'Home page')

@section('content')

    <div>
        <div class="row tm-row">
            @foreach($posts as $post)
                <article class="col-12 col-md-6 tm-post">
                    <hr class="tm-hr-primary">
                    <a href="{!! route('site.posts.show', $post) !!}" class="effect-lily tm-post-link tm-pt-60">

                        @if($post->mainImage)
                            <div class="tm-post-link-inner">
                                <img src="{!! asset('storage/'. $post->mainImage->path) !!}" alt="Image"
                                     class="img-fluid">
                            </div>
                        @endif

                        @if(!$post->created_at->diffInWeeks())
                            <span class="position-absolute tm-new-badge">New</span>
                        @endif
                        <h2 class="tm-pt-30 tm-color-primary tm-post-title">{{ mb_substr($post->title, 0, 30) }}</h2>
                    </a>
                    <p class="tm-pt-30">
                        {{ mb_substr($post->content, 0, 50) }}...
                    </p>
                    <div class="d-flex justify-content-between tm-pt-45">
                        <span class="tm-color-primary">{!! $post->category->name !!}</span>
                        <span class="tm-color-primary">{!! $post->created_at->format('M d Y') !!}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span>{{ $post->comments_count }} comments</span>
                        <span>by {{ $post->owner->name }}</span>
                    </div>
                </article>
            @endforeach
        </div>
        {!! $posts->links('layouts.paginate') !!}
    </div>

@endsection

@push('scripts')
    <script>
        let pages = 2;
        let current_page = 0;
        let bool = false;
        let lastPage;
        $(window).scroll(function () {
            let height = $(document).height();
            if ($(window).scrollTop() + $(window).height() >= height && bool === false && lastPage > pages - 2) {
                // bool = true;
                // $('.ajax-load').show();
                // lazyLoad(pages)
                //     .then(() => {
                //         bool = false;
                //         pages++;
                //         if (pages - 2 === lastPage) {
                //             $('.no-data').show();
                //         }
                //     })
            }
        })

        function lazyLoad(page) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '?page=' + page,
                    type: 'GET',
                    beforeSend: function () {
                        $('.ajax-load').show();
                    },
                    success: function (response) {
                        $('.ajax-load').hide();
                        let html = '';
                        for (let i = 0; i < response.data.data.length; i++) {
                            html += `
                                   <div class="col-md-4 mb-3" >
                                        <div class="card">
                                           <div class="card-header">
                                             Employee Title
                                           </div>
                                          <div class="card-body">
                                             <table class="table">
                                               <tr>
                                               <th>Name</th>
                                               <td>:</td>
                                               <td>` + response.data.data[i].name + `</td>
                                          </tr>
                                         <tr>
                                         <th>Phone</th>
                                          <td>:</td>
                                          <td>` + response.data.data[i].phone + `</td>
                                       </tr>
                                     </table>
                                   </div>
                             </div>
                        </div>`;
                        }
                        $('#data_temp').append(html);
                        resolve();
                    }
                });
            })
        }


        function loadData(page) {
            $.ajax({
                url: '?page=' + page,
                type: 'GET',
                beforeSend: function () {
                    $('.ajax-load').show();
                },
                success: function (response) {
                    $('.ajax-load').hide();
                    lastPage = response.data.last_page;
                    console.log(response);
                    let html = '';
                    for (let i = 0; i < response.data.data.length; i++) {
                        html += `<div class="col-md-4 mb-3" >
                                    <div class="card">
                                        <div class="card-header">
                                        Employee Title
                                        </div>
                       <div class="card-body">
                            <table class="table">
                               <tr>
                                   <th>Name</th>
                                    <td>:</td>
                                    <td>` + response.data.data[i].name + `</td>
                                 </tr>
                                 <tr>
                                  <th>Phone</th>
                                    <td>:</td>
                                   <td>` + response.data.data[i].phone + `</td>
                                  </tr>
                             </table>
                         </div>
                         </div>
                    </div>`;
                    }
                    $('#data_temp').html(html);
                }
            });
        }
    </script>
@endpush
