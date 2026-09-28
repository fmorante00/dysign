<x-admin-layout>

<div class="p-6">


    <!-- Header -->

    <div class="mb-8">

        <h1 class="text-3xl font-bold text-[#11175A]">
            Participation Reports
        </h1>

        <p class="mt-2 text-gray-500">
            Generate and review student participation reports based on finalized participation records and evaluation results.
        </p>

    </div>





    <!-- Summary Cards -->

    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">


        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

            <p class="text-sm text-gray-500">
                Total Students Evaluated
            </p>

            <h2 class="text-3xl font-bold text-[#11175A] mt-3">
                0
            </h2>

        </div>





        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

            <p class="text-sm text-gray-500">
                Highly Participative
            </p>

            <h2 class="text-3xl font-bold text-green-600 mt-3">
                0
            </h2>

        </div>





        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

            <p class="text-sm text-gray-500">
                Participative
            </p>

            <h2 class="text-3xl font-bold text-yellow-600 mt-3">
                0
            </h2>

        </div>





        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

            <p class="text-sm text-gray-500">
                Low Participation
            </p>

            <h2 class="text-3xl font-bold text-red-600 mt-3">
                0
            </h2>

        </div>


    </div>







    <!-- Filters -->

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">


        <h2 class="text-xl font-semibold text-[#11175A] mb-5">
            Report Filters
        </h2>



        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">


            <div>

                <label class="block text-sm text-gray-600 mb-2">
                    Program
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
                        All Programs
                    </option>


                </select>


            </div>





            <div>

                <label class="block text-sm text-gray-600 mb-2">
                    Year Level
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
                        All Year Levels
                    </option>


                </select>


            </div>





            <div>

                <label class="block text-sm text-gray-600 mb-2">
                    Classification
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
                        Highly Participative
                    </option>

                    <option>
                        Participative
                    </option>

                    <option>
                        Low Participation
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








    <!-- Participation Table -->


    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">


        <div class="p-6 border-b border-gray-200">


            <h2 class="text-xl font-semibold text-[#11175A]">
                Student Participation Results
            </h2>


            <p class="text-sm text-gray-500 mt-1">
                Displays student participation classification and evaluation results.
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
                            Program
                        </th>


                        <th class="px-6 py-4">
                            Events Attended
                        </th>


                        <th class="px-6 py-4">
                            Participation Rate
                        </th>


                        <th class="px-6 py-4">
                            Classification
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

                            No participation records available.

                        </td>


                    </tr>


                </tbody>


            </table>


        </div>


    </div>



</div>


</x-admin-layout>