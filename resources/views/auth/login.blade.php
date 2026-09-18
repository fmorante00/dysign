<x-guest-layout>

<div class="min-h-screen flex items-center justify-center 
    bg-gradient-to-br from-blue-100 via-white to-yellow-50 p-6">


    <div class="
        w-full
        max-w-6xl
        min-h-[650px]
        bg-white
        rounded-3xl
        shadow-2xl
        overflow-hidden
        flex
    ">


        <!-- LEFT SIDE IMAGE -->

        <div class="
            hidden md:flex
            w-1/2
            relative
            overflow-hidden
        ">


            <img
                src="{{ asset('images/school.jpg') }}"
                class="
                absolute
                inset-0
                w-full
                h-full
                object-cover
                "
            >



            <div class="
                absolute
                inset-0
                bg-gradient-to-br
                from-blue-950/90
                via-blue-900/70
                to-blue-700/60
            ">
            </div>




            <div class="
                relative
                z-10
                flex
                flex-col
                justify-center
                p-12
                text-white
            ">


                <h1 class="
                    text-6xl
                    font-bold
                    tracking-wide
                ">

                    DySign

                </h1>


                <div class="
                    h-1
                    w-20
                    bg-yellow-400
                    rounded-full
                    mt-5
                ">
                </div>



                <p class="
                    mt-6
                    text-xl
                    text-blue-100
                    max-w-md
                ">

                    Digital Identity System

                    <br>

                    for a smarter and connected campus.

                </p>



                <div class="mt-10">


                    


                </div>


            </div>


        </div>







        <!-- RIGHT SIDE LOGIN -->

        <div class="
            w-full
            md:w-1/2
            flex
            items-center
            justify-center
            p-10
        ">



            <div class="
                w-full
                max-w-md
            ">




                <h2 class="
                    text-4xl
                    font-bold
                    text-blue-950
                ">

                    Welcome Back

                </h2>



                <p class="
                    mt-3
                    text-gray-500
                ">

                    Login to access your DySign account

                </p>





                <x-auth-session-status
                    class="mt-5"
                    :status="session('status')"
                />






                <form method="POST"
                    action="{{ route('login') }}"
                    class="mt-8">


                    @csrf





                    <!-- EMAIL -->

                    <div>


                        <x-input-label
                            for="email"
                            value="Email Address"
                        />



                        <div class="relative mt-2">


                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                class="
                                w-full
                                rounded-xl
                                border-gray-200
                                bg-gray-50
                                px-5
                                py-3
                                text-gray-700
                                shadow-sm
                                transition
                                focus:bg-white
                                focus:border-blue-700
                                focus:ring-4
                                focus:ring-blue-200
                                "
                                placeholder="Enter your email"
                            >


                        </div>


                        <x-input-error
                            :messages="$errors->get('email')"
                            class="mt-2"
                        />



                    </div>







                    <!-- PASSWORD -->

                    <div class="mt-5">


                        <x-input-label
                            for="password"
                            value="Password"
                        />



                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            class="
                            mt-2
                            w-full
                            rounded-xl
                            border-gray-200
                            bg-gray-50
                            px-5
                            py-3
                            text-gray-700
                            shadow-sm
                            transition
                            focus:bg-white
                            focus:border-blue-700
                            focus:ring-4
                            focus:ring-blue-200
                            "
                            placeholder="Enter your password"
                        >



                        <x-input-error
                            :messages="$errors->get('password')"
                            class="mt-2"
                        />


                    </div>







                    <div class="
                        flex
                        justify-between
                        items-center
                        mt-5
                    ">



                        <label class="flex items-center">


                            <input
                                type="checkbox"
                                name="remember"
                                class="rounded text-blue-700"
                            >


                            <span class="
                                ml-2
                                text-sm
                                text-gray-600
                            ">

                                Remember me

                            </span>


                        </label>




                        @if(Route::has('password.request'))

                        <a
                            href="{{ route('password.request') }}"
                            class="
                            text-sm
                            text-blue-700
                            hover:text-blue-900
                            "
                        >

                            Forgot password?

                        </a>


                        @endif


                    </div>








                    <button
                        class="
                        mt-8
                        w-full
                        rounded-xl
                        py-3
                        text-white
                        font-semibold
                        bg-gradient-to-r
                        from-blue-950
                        via-blue-800
                        to-blue-600
                        shadow-lg
                        hover:shadow-xl
                        transition
                        "
                    >

                        Login

                    </button>





                </form>





                <p class="
                    mt-10
                    text-center
                    text-xs
                    text-gray-400
                ">

                    Authorized Personnel Access Only

                    <br>

                    DySign Digital Identity System

                </p>




            </div>



        </div>



    </div>



</div>


</x-guest-layout>