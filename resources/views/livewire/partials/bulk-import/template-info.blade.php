<div class="mb-8">
    <div 
        class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden" 
        x-data="{ open: false }">
        
        <!-- Info header with toggle -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center cursor-pointer"
            @click="open = !open">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Import File Format Information
            </h3>
            <button type="button" class="text-gray-400 hover:text-gray-500">
                <svg 
                    class="h-5 w-5 transition-transform transform"
                    :class="{ 'rotate-180': open }"
                    xmlns="http://www.w3.org/2000/svg" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>
        
        <!-- Content area (expandable) -->
        <div class="px-6 py-4 bg-white" x-show="open" x-transition>
            <p class="text-sm text-gray-600 mb-4">
                To successfully import students, your Excel or CSV file must include the following columns in the exact order shown below. 
                You can download our template file to ensure the correct format.
            </p>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Column</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Required</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Format/Example</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">First Name</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Student's first name</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Required
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">John</td>
                        </tr>
                        
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Last Name</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Student's last name</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Required
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">Doe</td>
                        </tr>
                        
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Admission Number</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Unique student identifier</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Required
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">ADM2023001</td>
                        </tr>
                        
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Class</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Student's class name</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Required
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">Grade 10A</td>
                        </tr>
                        
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Date of Birth</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Student's birth date</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Required
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">2005-01-15 (YYYY-MM-DD)</td>
                        </tr>
                        
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Gender</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Student's gender</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Required
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">Male or Female</td>
                        </tr>
                        
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Parent Name</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Parent/Guardian full name</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Required
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">Jane Doe</td>
                        </tr>
                        
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Parent Email</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Parent/Guardian email address</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Required
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">parent@example.com</td>
                        </tr>
                        
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Parent Phone</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Parent/Guardian phone number</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Required
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">+1234567890</td>
                        </tr>
                        
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Address</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Student's residential address</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Optional
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">123 Main St, City</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6 p-4 bg-blue-50 rounded-md border border-blue-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 md:flex md:justify-between">
                        <p class="text-sm text-blue-700">
                            <strong>Tips:</strong> Make sure the first row contains column headers, and all required fields are filled out. Empty rows will be skipped.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Download Template Button -->
            <div class="mt-6 flex justify-center">
                <a href="{{ route('download.template') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download Template
                </a>
            </div>
        </div>
    </div>
</div> 