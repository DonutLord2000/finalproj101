<x-app-layout>
    @section('title', 'GRC - Contact Us')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contact Us') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <!-- Physical Address Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">PHYSICAL ADDRESS</h3>
                        <p class="text-gray-700">
                            454 GRC BLDG. Rizal Ave. Cor. 9th ave. East Grace Park, Caloocan City 1400 Metro Manila. | 
                            <a href="https://www.google.com/maps/place/Global+Reciprocal+Colleges+-+GRC/@14.6498417,120.9813463,17z/data=!3m1!4b1!4m6!3m5!1s0x3397b5d4fab883bb:0x96f1adb22bed4d5e!8m2!3d14.6498365!4d120.9839212!16s%2Fg%2F1z2v7w380?entry=ttu&g_ep=EgoyMDI0MTExMy4xIKXMDSoASAFQAw%3D%3D" target="_blank" class="text-blue-600 hover:text-blue-800">View in Google Map</a>
                        </p>
                    </div>

                    <!-- Mobile & Email Directory Section -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">MOBILE & EMAIL DIRECTORY</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-red-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-white">
                                            Office
                                        </th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-white">
                                            Contact no.
                                        </th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-white">
                                            Email Address
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Admissions</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">09519637603 – 09283875420</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="mailto:admissions@grc.edu.ph" class="text-red-600 hover:text-red-800">admissions@grc.edu.ph</a>
                                        </td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Guidance</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">N/A</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="mailto:guidanceoffice@grc.edu.ph" class="text-red-600 hover:text-red-800">guidanceoffice@grc.edu.ph</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Cashier 1</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">09602723578</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="mailto:cashier1@grc.edu.ph" class="text-red-600 hover:text-red-800">cashier1@grc.edu.ph</a>
                                        </td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Cashier 2</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">09602723578</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="mailto:cashier2@grc.edu.ph" class="text-red-600 hover:text-red-800">cashier2@grc.edu.ph</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">General Services</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">N/A</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="mailto:generalservices@grc.edu.ph" class="text-red-600 hover:text-red-800">generalservices@grc.edu.ph</a>
                                        </td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Human Resource</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">N/A</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="mailto:hr-recruitment@grc.edu.ph" class="text-red-600 hover:text-red-800">hr-recruitment@grc.edu.ph</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">I.T office</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">N/A</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="mailto:info@grc.edu.ph" class="text-red-600 hover:text-red-800">info@grc.edu.ph</a>
                                        </td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Library</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">N/A</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="mailto:library@grc.edu.ph" class="text-red-600 hover:text-red-800">library@grc.edu.ph</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Marketing</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">N/A</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="mailto:marketing@grc.edu.ph" class="text-red-600 hover:text-red-800">marketing@grc.edu.ph</a>
                                        </td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Office of Student Affairs</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">N/A</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="mailto:osa@grc.edu.ph" class="text-red-600 hover:text-red-800">osa@grc.edu.ph</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Registrar</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">8-452-2945</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="mailto:registrar@grc.edu.ph" class="text-red-600 hover:text-red-800">registrar@grc.edu.ph</a>
                                        </td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Research & Community Extension</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">N/A</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="mailto:research@grc.edu.ph" class="text-red-600 hover:text-red-800">research@grc.edu.ph</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Scholarship</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">N/A</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="mailto:scholarship@grc.edu.ph" class="text-red-600 hover:text-red-800">scholarship@grc.edu.ph</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-12" style="margin-top: 30pt">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 mt-4">ACADEMIC DEPARTMENT HEADS</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-red-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-white">
                                            College
                                        </th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-white">
                                            Name
                                        </th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-white">
                                            Email
                                        </th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-white">
                                            Facebook
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">DEAN</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Prof. Mark Anthony Soriano</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="mailto:msoriano@grc.edu.ph" class="text-red-600 hover:text-red-800">msoriano@grc.edu.ph</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">
                                            <a href="https://www.facebook.com/mhac.soriano.1">Open</a>
                                        </td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">College of Accountancy</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Prof. Bartolome Urbano</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="mailto:burbano@grc.edu.ph" class="text-red-600 hover:text-red-800">burbano@grc.edu.ph</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">
                                            <a href="https://www.facebook.com/boyet.urbano.5">Open</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">College of Computer Studies</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Prof. Domingo Tanael</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="mailto:dotanael@grc.edu.ph" class="text-red-600 hover:text-red-800">dotanael@grc.edu.ph</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">
                                            <a href="https://web.facebook.com/dominic.tan.9803">Open</a>
                                        </td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">College of Business Administration & Entrepreneurship</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Prof. Telesforo C. Bernabe Jr.</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="mailto:tbernabe@grc.edu.ph" class="text-red-600 hover:text-red-800">tbernabe@grc.edu.ph</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">
                                            <a href="https://www.facebook.com/mike.tokyodrift">Open</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">College of Education</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Prof. Joy Bonifacio</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="mailto:jbadilla@grc.edu.ph" class="text-red-600 hover:text-red-800">jbadilla@grc.edu.ph</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">
                                            <a href="https://web.facebook.com/joy.bonifacio.5">Open</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>