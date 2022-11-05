<x-layouts.layout :$userProfilePic>


    <!-- main  -->
    <main class="col-span-6 row-span-6">
        <!-- create post -->
        <div class="mb-4 px-4 py-3 bg-white rounded-full box-border">
            <form>
                <div class="flex items-center box-border">
                    <div>
                        <img src="{{$userProfilePic}}" style="clip-path:circle()" class="w-12">
                    </div>
                    <textarea class="w-full focus:ring-0 resize-none align-center rounded-full border-none h-10"
                        placeholder="What's in your mind?"></textarea>
                    <button type="submit"
                        class="h-auto text-white bg-indigo-600 rounded-full text-lg px-8 py-2">Post</button>
                </div>
            </form>
        </div>

        <!-- posts  -->
        <div class="space-y-4">
            @foreach($posts as $post)
            <article class="space-y-4 p-4 bg-white rounded-2xl">
                <!-- header part  -->
                <div class="flex space-x-4">
                    <div>
                        <img src="{{ $post->user->profile_photo_path ? asset('storage/' . $post->user->profile_photo_path) : asset('storage') . '/default/default.png' }}"
                            style="clip-path:circle()" class="w-12">
                    </div>

                    <div>
                        <p><strong class='text-lg'>{{$post->user->name}}</strong></p>
                        <small class="text-gray-500 font-semibold">{{$post->created_at->diffForHumans()}}</small>
                    </div>
                </div>

                <!-- caption  -->
                <div>
                    @if(Str::length($post->caption) <= 150) <p class="text-gray-900">
                        {{ $post->caption}}
                        </p>

                        @else
                        <p class="text-gray-900">
                            {{ Str::substr($post->caption, 0, 150) }} <span
                                onclick="expandCaption(event,`{{ $post->caption }}`)"
                                class='text-gray-500 hover:cursor-pointer'>...view more
                            </span>
                        </p>
                        @endif
                </div>

                <!-- picture  -->
                <div>
                    <img onclick="previewPost(event,{{$post->id}})" src="{{ asset('storage') . '/' . $post->image  }}"
                        class="toggle-window rounded-xl w-full max-h-96 object-cover">
                </div>

                <!-- love, comment, share, bookmark, section -->
                <div class='flex justify-between'>
                    <div class='flex space-x-4 pl-4'>
                        <div>
                            <form>
                                <input type="hidden" name="postId" value="{{$post->id}}">

                                <!-- renader the like button based on wheather he liked the post or not -->
                                @if($post->likes->contains('user_id',auth()->id()))
                                <button type="submit" class='submit-widget'>
                                    <x-svg.heart type='unlike' />
                                </button>
                                @else
                                <button type="submit" class='submit-widget'>
                                    <x-svg.heart type='like' />
                                </button>
                                @endif
                            </form>

                        </div>

                        <div onclick="previewPost(event,{{ $post->id }})">
                            <img src="{{asset('images/comments.svg')}}"
                                class="toggle-window block h-8 w-auto hover:cursor-pointer transition-all duration-300 hover:scale-125"></img>
                        </div>

                        <div>
                            <img onclick="this.nextElementSibling.classList.toggle('hidden')"
                                src="{{asset('images/share.svg')}}"
                                class="block h-8 w-auto hover:cursor-pointer hover:scale-125 transition-all duration-300"></img>
                            <!-- share post snippet  -->
                            <div onclick="this.classList.toggle('hidden')"
                                class='hidden fixed top-0 right-0 bottom-0 left-0'
                                style=" background: rgba(0, 0, 0,0.2);">
                                <div
                                    class="w-4/12 h-20 bg-white absolute rounded-lg left-2/4 top-2/4 -translate-y-2/4 -translate-x-2/4">
                                    <div class="h-full">
                                        <div class="flex justify-center items-center h-full">
                                            <div class="px-2 py-1 shadow space-x-4">
                                                <a class="text-sm text-blue-500 hover:underline"
                                                    href="{{ route('post.show',$post->id) }}">{{ route('post.show',$post->id) }}</a>
                                                <button onclick="sharePost('{{ route('post.show',$post->id) }}')"
                                                    class="text-lg text-blue-600 hover:cursor-pointer">Copy</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <form>
                            <input type="hidden" name="postId" value="{{$post->id}}">

                            <!-- renader the bookmark button based on wheather he bookmarked the post or not -->
                            @if($post->bookmarks->contains('user_id',auth()->id()))
                            <button type="submit" class='submit-widget'>
                                <x-svg.bookmark type='unsave' />
                            </button>
                            @else
                            <button type="submit" class='submit-widget'>
                                <x-svg.bookmark type='save' />
                            </button>
                            @endif
                        </form>

                    </div>
                </div>

                <!-- love list section  -->
                <div class='flex items-center space-x-2 '>
                    <!-- 3 picture first  -->
                    <div class='flex items-center pl-5'>
                        @foreach($post->likes as $like)
                        @if($loop->count >=4)
                        @break
                        @endif
                        <div class=' -ml-4 p-1'>
                            <div style="clip-path:circle()">
                                <img src="{{ $like->user->profile_photo_path ? asset('storage/' . $like->user->profile_photo_path) : asset('storage') . '/default/default.png' }}"
                                    class="w-7">
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <p class='text-lg'>liked by @if( $post->likes->first()->user->name ?? null)
                        <strong>{{ $post->likes->first()->user->name }}</strong>@endif

                        @if($post->likes->count() >= 2)
                        and <strong> {{$post->likes->count() - 1}} others </strong>
                    </p>
                    @endif
                </div>

                <!-- view comment section  -->

                <div>
                    <span onclick="previewPost(event,{{$post->id}})"
                        class='toggle-window text-gray-400 text-sm hover:cursor-pointer'>view all 45
                        comments</span>

                    <div class="hidden" data-id="{{ $post->id }}">
                        <x-posts.comments :$post />
                    </div>

                </div>


                <!-- add comment section  -->

                <div>
                    <hr>
                    <form>
                        <div class='flex items-center justify-between form'>
                            <input type="hidden" name="postId" value="{{$post->id}}">
                            <input type="text" name='comment_text' class='w-full border-none focus:ring-0'
                                placeholder="Add a comment...😊">
                            <input type="submit" class=' submit-comment text-blue-500 hover:cursor-pointer'
                                value="Post" />
                        </div>
                        <span class='error text-red-600 text-sm'></span>
                    </form>
                </div>

            </article>
            @endforeach
            {{ $posts->links() }}
        </div>

    </main>


</x-layouts.layout>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<script>
    $(document).ready(function () {
        //set the csrf token
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
            }
        });

        $(".submit-widget").click(function (e) {
            e.preventDefault();
            const postdata = $(this).parent().serializeArray();

            //toggle the bookmark icon
            const src = $(this).children("img").attr("src")
            const data = replaceSVG(src);

            $(this).children("img").attr("src", data.newSrc)

            // send the request if the url is defined
            const newUrl = data.url
            if (newUrl != undefined) {
                $.ajax({
                    type: "POST",
                    url: newUrl,
                    data: {
                        postId: postdata[0].value
                    },
                    success: (result) => {
                        console.log(result.success);

                    }
                });
            }
        })


        function replaceSVG(src) {
            let newSrc, url;

            if (src.search("-save") != -1) {
                newSrc = src.replace('-save', '-unsave')
                url = "{{ route('bookmark.store') }}";
            } else if (src.search("-unsave") != -1) {
                newSrc = src.replace('-unsave', '-save');
                url = "{{ route('bookmark.delete') }}";
            } else if (src.search("-like") != -1) {
                newSrc = src.replace('-like', '-unlike')
                url = "{{ route('like.store') }}";
            } else if (src.search("-unlike") != -1) {
                newSrc = src.replace('-unlike', '-like');
                url = "{{ route('like.delete') }}";
            }

            return {
                newSrc,
                url
            }

        }



        //get the form data as array of name and value contains url of the route 
        $(".submit-comment").click(function (e) {
            e.preventDefault();
            $(this).parents('form').children('span').text(" ")

            const postdata = $(this).parents('form').serializeArray();

            $.ajax({
                type: "POST",
                url: "{{route('comment.store')}}",
                data: {
                    postId: postdata[0].value,
                    comment_text: postdata[1].value
                },
                success: (result) => {
                    console.log(result.success);

                    $(`div[data-id=${postdata[0].value}]`).append($(` <div class="flex items-start justify-start space-x-3">
                             <div class="pt-1">
                                 <img src="${result.imagePath}"
                                     class="w-10" style="clip-path:circle()">
                             </div>

                             <div class="flex flex-col space-y-1">
                                 <p class="text-slate-800">${result.comment_user_name}<span
                                         class="text-xs pl-4 text-gray-400"> ${result.comment_created_at}
                                     </span></p>
                                 <p class="text-md font-serif">${result.comment_text}</p>
                             </div>
                         </div>`))
                },
                error: (result) => {
                    $(this).parents('form').children('span').text(result
                        .responseJSON.message)
                }
            });


        });
    });

    function previewPost(event, id) {
        let ele = event.target
        if (ele.classList.contains('toggle-window')) {
            $("div").find(`[data-id='${id}']`)[0].classList.toggle('hidden');
        }
    }

    function expandCaption(event, caption) {
        let parent = event.target.parentElement;

        parent.innerHTML = caption;
    }

    function sharePost(link) {
        navigator.clipboard.writeText(link)
    }

</script>
</body>

</html>
