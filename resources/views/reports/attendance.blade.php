<x-admin-layout>

<div class="p-6">

    <!-- Header -->
    <div class="mb-8">

        <h1 class="text-3xl font-bold text-[#11175A]">
            Attendance Reports
        </h1>

        <p class="mt-2 text-gray-500">
            Generate and review official attendance reports from finalized event attendance records.
        </p>

    </div>



    <!-- Summary Cards -->

    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">


        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

            <p class="text-sm text-gray-500">
                Total Events
            </p>

            <h2 class="text-3xl font-bold text-[#11175A] mt-3">
                0
            </h2>

        </div>




        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

            <p class="text-sm text-gray-500">
                Total Present
            </p>

            <h2 class="text-3xl font-bold text-green-600 mt-3">
                0
            </h2>

        </div>




        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

            <p class="text-sm text-gray-500">
                Late Attendance
            </p>

            <h2 class="text-3xl font-bold text-yellow-600 mt-3">
                0
            </h2>

        </div>




        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

            <p class="text-sm text-gray-500">
                Completion Rate
            </p>

            <h2 class="text-3xl font-bold text-[#11175A] mt-3">
                0%
            </h2>

        </div>


    </div>





    <!-- Filters -->

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">


        <h2 class="text-xl font-semibold text-[#11175A] mb-5">
            Report Filters
        </h2>



        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">


            <div>

                <label class="block text-sm text-gray-600 mb-2">
                    Event
                </label>


                <select
                class="
                w-full
                border
                border-gray-300
                rounded-xl
                px-4
                py-3
                text-gray-700
                focus:ring-2
                focus:ring-yellow-400
                "
                >

                    <option>
                        Select Event
                    </option>

                </select>

            </div>





            <div>

                <label class="block text-sm text-gray-600 mb-2">
                    Attendance Status
                </label>


                <select
                class="
                w-full
                border
                border-gray-300
                rounded-xl
                px-4
                py-3
                text-gray-700
                "
                >

                    <option>
                        All
                    </option>

                    <option>
                        Present
                    </option>

                    <option>
                        Late
                    </option>

                    <option>
                        Absent
                    </option>


                </select>


            </div>





            <div class="flex items-end">


                <button
                class="
                w-full
                bg-[#D4A017]
                hover:bg-[#C49310]
                text-white
                font-semibold
                rounded-xl
                py-3
                transition
                "
                >

                    Generate Report

                </button>


            </div>


        </div>


    </div>






    <!-- Table -->

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">


        <div class="p-6 border-b border-gray-200">

            <h2 class="text-xl font-semibold text-[#11175A]">
                Attendance Records
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Finalized attendance information from school events.
            </p>

        </div>




        <div class="overflow-x-auto">


            <table class="w-full">


                <thead class="bg-gray-50">


                    <tr class="text-left text-sm text-gray-600">


                        <th class="px-6 py-4">
                            Student
                        </th>


                        <th class="px-6 py-4">
                            Event
                        </th>


                        <th class="px-6 py-4">
                            Time In
                        </th>


                        <th class="px-6 py-4">
                            Time Out
                        </th>


                        <th class="px-6 py-4">
                            Status
                        </th>


                    </tr>


                </thead>



                <tbody>


                    <tr class="border-t">


                        <td colspan="5"
                        class="
                        px-6
                        py-8
                        text-center
                        text-gray-400
                        "
                        >

                            No attendance records available.

                        </td>


                    </tr>


                </tbody>


            </table>


        </div>


    </div>



</div>


</x-admin-layout>