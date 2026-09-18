<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Personnel Management
        </h2>

    </x-slot>



    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900">


                    <div class="flex justify-between mb-4">

                        <h3 class="text-lg font-semibold">
                            Personnel List
                        </h3>


                        <a href="{{ route('personnel.create') }}"
                           class="bg-blue-500 text-white px-4 py-2 rounded">

                            Add Personnel

                        </a>

                    </div>




                    <table class="w-full border">


                        <thead>

                            <tr class="border-b">


                                <th class="p-2 text-left">
                                    Name
                                </th>


                                <th class="p-2 text-left">
                                    Department
                                </th>


                                <th class="p-2 text-left">
                                    Position
                                </th>


                                <th class="p-2 text-left">
                                    Status
                                </th>


                                <th class="p-2 text-left">
                                    Action
                                </th>


                            </tr>

                        </thead>




                        <tbody>


                            @foreach($personnel as $person)


                            <tr class="border-b">


                                <td class="p-2">

                                    {{ $person->first_name }}
                                    {{ $person->last_name }}

                                </td>



                                <td class="p-2">

                                    {{ $person->department }}

                                </td>



                                <td class="p-2">

                                    {{ $person->position }}

                                </td>




                                <td class="p-2">

                                    @if($person->user->status == 'Active')

                                        <span class="text-green-600 font-semibold">
                                            Active
                                        </span>

                                    @else

                                        <span class="text-red-600 font-semibold">
                                            Inactive
                                        </span>

                                    @endif


                                </td>




                                <td class="p-2">


                                   <a href="{{ route('personnel.edit', $person->personnel_id) }}"
       class="bg-yellow-500 text-white px-3 py-1 rounded">

        Edit

    </a>



    @if($person->user->status == 'Active')


    <form method="POST"
          action="{{ route('personnel.destroy', $person->personnel_id) }}"
          class="inline"
          onsubmit="return confirm('Are you sure you want to deactivate this personnel account?');">


        @csrf

        @method('DELETE')


        <button type="submit"
                class="bg-red-500 text-white px-3 py-1 rounded">

            Deactivate

        </button>


    </form>



@else


    <form method="POST"
          action="{{ route('personnel.activate', $person->personnel_id) }}"
          class="inline"
          onsubmit="return confirm('Are you sure you want to activate this personnel account?');">


        @csrf

        @method('PATCH')


        <button type="submit"
                class="bg-green-500 text-white px-3 py-1 rounded">

            Activate

        </button>


    </form>


@endif 

                                </td>



                            </tr>


                            @endforeach



                        </tbody>



                    </table>



                </div>

            </div>


        </div>

    </div>


</x-app-layout>