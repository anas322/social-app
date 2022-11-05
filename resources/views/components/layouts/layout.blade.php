<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Posts - social </title>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        @vite(['resources/css/app.css'])
    </head>

    <body class='bg-gray-100 container mx-auto'>
        <header class='grid grid-cols-12 gap-x-6 px-2 py-3 bg-white rounded-b-lg'>

            <div class='ml-4 col-span-3 flex items-center space-x-1'> <object data="{{asset('images/logo.svg')}}"
                    class="block h-12 w-auto"></object><span class='text-2xl font-bold'>memes</span></div>

            <div class='col-span-6'>
                <form>
                    <div class="bg-gray-100 rounded-full flex items-center w-full">
                        <object data="{{asset('images/search.svg')}}"
                            class="block h-6 w-auto p-3 box-content "></object>
                        <input type="text" class="border-none bg-gray-100 w-full rounded-full focus:ring-0"
                            placeholder='Search for creators ❤️'>
                    </div>
                </form>
            </div>

            <div class='col-span-3'>
                <div class="flex justify-end items-center space-x-16 pr-8">
                    <a href="{{route('post.create')}}"
                        class="h-auto text-white bg-indigo-600 rounded-full text-lg px-8 py-2 ">create</a>
                    <div>
                        <a href="{{route('prof.index',auth()->user()->id)}}">
                            <img src="{{$userProfilePic}}" style="clip-path:circle()" class="w-9">
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <section class="grid grid-cols-12 gap-x-6 my-2 auto-rows-min max-w-screen-2xl mx-auto">
            <!-- side left -->
            <aside class="  col-span-3 row-span-2  ">

                <div class='flex items-center space-x-10 py-6 pl-4 rounded-2xl mb-4 bg-white'>
                    <div><img src="{{$userProfilePic}}" style='clip-path : circle()' class='block h-12 w-auto'></div>
                    <div>
                        <p><strong>{{auth()->user()->name}}</strong></p>
                        <p class='text-gray-400'>{{auth()->user()->email}}</p>
                    </div>
                </div>


                <div class='flex flex-col bg-white rounded-2xl overflow-hidden'>

                    <div @class(["bg-gray-100 border-l-8 border-blue-600"=> Route::has('post.index'),'flex items-center
                        space-x-10 py-6 pl-4']) >
                        <div>
                            <object data="{{asset('images/home-icon.svg')}}" class="block h-8 w-auto"></object>
                        </div>
                        <strong @class(["text-blue-600"=> Route::has('post.index'),'capitalize'])>home</strong>
                    </div>

                    <div class='flex items-center space-x-10 py-6 pl-4 '>
                        <div>
                            <object data="{{asset('images/explore.svg')}}" class="block h-8 w-auto"></object>
                        </div>
                        <strong class='capitalize'>explore</strong>
                    </div>

                    <div class='flex items-center space-x-10 py-6 pl-4 '>
                        <div class='relative'>
                            <object data="{{asset('images/notify.svg')}}" class="block h-8 w-auto"></object>
                            <span class="flex absolute h-3 w-3 top-0 right-0 -mt-1 -mr-1">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 "></span>
                            </span>
                        </div>
                        <strong class='capitalize'>notifications </strong>
                    </div>

                    <div class='flex items-center space-x-10 py-6 pl-4 '>
                        <div class='relative'>
                            <object data="{{asset('images/messages.svg')}}" class="block h-8 w-auto"></object>
                            <span class="flex absolute h-3 w-3 top-0 right-0 -mt-1 -mr-1">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 "></span>
                            </span>
                        </div>
                        <strong class='capitalize'>messages</strong>
                    </div>

                    <div class='flex items-center space-x-10 py-6 pl-4 '>
                        <div>
                            <object data="{{asset('images/bookmarks.svg')}}" class="block h-8 w-auto"></object>
                        </div>
                        <strong class='capitalize'><a href="{{ route('bookmark.index') }}">bookmarks</a></strong>
                    </div>

                    <div class='flex items-center space-x-10 py-6 pl-4 '>
                        <div>
                            <object data="{{asset('images/theme.svg')}}" class="block h-11 w-auto"></object>
                        </div>
                        <strong class='capitalize'>theme</strong>
                    </div>

                    <div class='flex items-center space-x-10 py-6 pl-4 '>
                        <div>
                            <object data="{{asset('images/settings.svg')}}" class="block h-8 w-auto"></object>
                        </div>
                        <strong class='capitalize'>settings</strong>
                    </div>
                </div>
            </aside>

            <!-- main  -->
            <main class="col-span-6 row-span-6">
             {{ $slot }}
            </main>

            <!-- side right  -->
            <aside class=" col-span-3 row-span-2 rounded-2xl">
                <div class='flex flex-col space-y-5 p-4 bg-white rounded-2xl overflow-hidden'>
                    <div>
                        <span class='uppercase'>followers</span>
                        <hr>
                    </div>

                    <div class="flex flex-col space-y-5">

                        @forelse (auth()->user()->profile->followers as $follower)
                        @if($loop->iteration >= 10)
                        @break
                        @endif
                        <div class='flex items-center space-x-8'>
                            <div><img
                                    src="{{ $follower->profile_photo_path ? asset('storage/' . $follower->profile_photo_path) : asset('storage') . '/default/default.png' }}"
                                    class='block h-11 w-auto rounded-xl'></div>
                            <span class='text-lg text-slate-700'>{{ $follower->name }}</span>
                        </div>

                        @empty
                        <p>No followers 🙄</p>

                        @endforelse

                    </div>

                </div>
            </aside>
        </section>
    </body>

</html>