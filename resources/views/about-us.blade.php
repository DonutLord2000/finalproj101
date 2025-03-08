<x-app-layout>
    @section('title', 'GRC - About Us')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('About Us') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <h1 class="text-3xl font-bold text-red-600 mb-8 text-center">
                        THE GLOBAL RECIPROCAL COLLEGES PROFILE
                    </h1>

                    <div class="md:flex gap-8 mb-8">
                        <div class="md:w-1/3 mb-6 md:mb-0">
                            <img 
                                src="images/chairman.jpg" 
                                alt="Chairman Vicente N. Ongtenco" 
                                class="w-full rounded-lg shadow-md mx-auto"
                                style="width: auto; length: 30rem;"

                            />
                            <p class="text-center mt-4 text-gray-700 font-bold">Chairman Vicente N. Ongtenco</p>
                        </div>
                        
                        <div class="md:w-2/3 text-gray-700 leading-relaxed space-y-6">
                            <p>
                                With a dream of having a free education through reciprocation, where everyone can have the 
                                opportunity to change their lives through a very affordable tuition fee and even scholarship 
                                grants, available not just for the youth but also for adults, Chairman Vicente Ongtenco 
                                established the Global Reciprocal Colleges aiming to develop the youth to become responsible, 
                                competent, and dedicated professionals.
                            </p>
                            
                            <p>
                                In its pursuit of social and economic amelioration, on December 10, 2007, the Global 
                                Reciprocal Colleges was registered in the Security Exchange Commission (SEC), and in 
                                partnership with the Motortrade Life And Livelihood Assistance Foundation, Inc. (MLALAF), 
                                Global Reciprocal Colleges started a Technical Education and Skills Development Authority 
                                (TESDA) courses but in due course of time, GRC finally pursued courses that will help it to 
                                be established as a College institution.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-6 text-gray-700 leading-relaxed">
                        <p>
                            On August 13, 2013, GRC added two tertiary courses; the Bachelor of Elementary Education Major 
                            in Special Education under GR. No. 028 S. 2013 and Bachelor of Secondary Education Major in 
                            English, Major in Mathematics, and Major in School Physical Education under GR. No. 029 S. 2013, 
                            aiming to produce excellent educators.
                        </p>

                        <p>
                            In two years, another two courses were added. Under GR. No. 067 S. 2015, the Bachelor of 
                            Science in Entrepreneurship and under GR. No. 068 S. 2015 the Bachelor of Science in Business 
                            Administration Major in Marketing Management and Major in Human Resources Management on 
                            December 3, 2015.
                        </p>

                        <p>
                            With the success of these courses, on July 11, 2017, GRC added another course, the Bachelor of 
                            Science in Accountancy under GR. No. 036 S. 2017, and then the next year another one made it 
                            on the courses offered at GRC; the Bachelor of Science in Information Technology under GR. No. 
                            034 S. 2018 on May 4, 2018. With these successful courses and students produced by the 
                            institution, GRC was already recognized as an official college in the Philippines.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>