<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Personnel
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-semibold mb-6">
                        Create Personnel Account
                    </h3>


                    <form method="POST" action="{{ route('personnel.store') }}">

                        @csrf


                        <!-- Personnel Information -->

                        <h4 class="font-semibold mb-4">
                            Personnel Information
                        </h4>


                        <div class="mb-4">

                            <label class="block mb-1">
                                First Name
                            </label>

                            <input type="text"
                                   name="first_name"
                                   class="border rounded w-full p-2"
                                   required>

                        </div>



                        <div class="mb-4">

                            <label class="block mb-1">
                                Last Name
                            </label>

                            <input type="text"
                                   name="last_name"
                                   class="border rounded w-full p-2"
                                   required>

                        </div>



                        <div class="mb-4">

                            <label class="block mb-1">
                                Department
                            </label>

                            <input type="text"
                                   name="department"
                                   class="border rounded w-full p-2"
                                   required>

                        </div>



                        <div class="mb-4">

                            <label class="block mb-1">
                                Position
                            </label>

                            <input type="text"
                                   name="position"
                                   class="border rounded w-full p-2"
                                   required>

                        </div>



                        <!-- Account Information -->

                        <h4 class="font-semibold mt-8 mb-4">
                            Account Information
                        </h4>



                        <div class="mb-4">

                            <label class="block mb-1">
                                Username
                            </label>

                            <input type="text"
                                   name="username"
                                   class="border rounded w-full p-2"
                                   required>

                        </div>



                        <div class="mb-4">

                            <label class="block mb-1">
                                Email
                            </label>

                            <input type="email"
                                   name="email"
                                   class="border rounded w-full p-2"
                                   required>

                        </div>



                        <div class="mb-4">

                            <label class="block mb-1">
                                Password
                            </label>

                            <input type="password"
                                   name="password"
                                   class="border rounded w-full p-2"
                                   required>

                        </div>



                        <div class="mb-6">

                            <label class="block mb-1">
                                Role
                            </label>


                            <select name="role_id"
                                    class="border rounded w-full p-2"
                                    required>


                                @foreach($roles as $role)

                                    <option value="{{ $role->role_id }}">
                                        {{ $role->role_name }}
                                    </option>

                                @endforeach


                            </select>

                        </div>



                        <!-- Buttons -->

                        <div class="flex gap-3">


                            <button type="submit"
                                style="background-color: green; color: white; padding: 10px 20px; border-radius: 8px;">
                                 Create Personnel
                            </button>



                            <a href="{{ route('personnel.index') }}"
                               class="bg-gray-500 text-white px-6 py-2 rounded">

                                Cancel

                            </a>


                        </div>


                    </form>


                </div>

            </div>

        </div>

    </div>

</x-app-layout>