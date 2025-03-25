<!-- How to Use Guide with Popups and Collapsible Sections -->
<div @click.stop x-data="{
    activeTab: 'quickStart',
    tipVisible: false,
    activeTip: null
}" class="space-y-4">
    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200">
        <div class="flex items-center gap-2 overflow-x-auto hide-scrollbar pb-1.5 -mx-1 px-1">
            <button 
                @click.stop="activeTab = 'quickStart'" 
                :class="{'text-green-600 border-green-500': activeTab === 'quickStart', 'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300': activeTab !== 'quickStart'}" 
                class="whitespace-nowrap px-3 py-1.5 border-b-2 text-xs font-medium flex items-center transition-colors">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Quick Start
            </button>
            <button 
                @click.stop="activeTab = 'examples'" 
                :class="{'text-green-600 border-green-500': activeTab === 'examples', 'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300': activeTab !== 'examples'}" 
                class="whitespace-nowrap px-3 py-1.5 border-b-2 text-xs font-medium flex items-center transition-colors">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Examples
            </button>
            <button 
                @click.stop="activeTab = 'tips'" 
                :class="{'text-green-600 border-green-500': activeTab === 'tips', 'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300': activeTab !== 'tips'}" 
                class="whitespace-nowrap px-3 py-1.5 border-b-2 text-xs font-medium flex items-center transition-colors">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
                Pro Tips
            </button>
        </div>
    </div>
    
    <!-- Quick Start Guide -->
    <div x-show="activeTab === 'quickStart'" class="space-y-3">
        <ol class="list-decimal pl-5 space-y-2 text-xs text-gray-600">
            <li>
                <div class="flex justify-between items-start">
                    <span>Set your <span class="font-medium text-gray-700">school hours</span> (e.g., 8:00 AM - 3:30 PM)</span>
                    <button 
                        @click.stop="tipVisible = true; activeTip = 'schoolHours'" 
                        class="ml-2 text-blue-500 hover:text-blue-700 p-0.5 rounded-full hover:bg-blue-50 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </div>
            </li>
            <li>
                <div class="flex justify-between items-start">
                    <span>Choose a <span class="font-medium text-gray-700">lesson duration</span> (typically 40-45 minutes)</span>
                    <button 
                        @click.stop="tipVisible = true; activeTip = 'lessonDuration'" 
                        class="ml-2 text-blue-500 hover:text-blue-700 p-0.5 rounded-full hover:bg-blue-50 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </div>
            </li>
            <li>
                <div class="flex justify-between items-start">
                    <span>Enable the types of activities you want in your schedule</span>
                    <button 
                        @click.stop="tipVisible = true; activeTip = 'activities'" 
                        class="ml-2 text-blue-500 hover:text-blue-700 p-0.5 rounded-full hover:bg-blue-50 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </div>
            </li>
            <li>Check the live preview to see your generated schedule</li>
            <li>Adjust advanced settings if needed for more customization</li>
        </ol>
    </div>
    
    <!-- Examples Section -->
    <div x-show="activeTab === 'examples'" class="space-y-3">
        <div x-data="{ open: false }" class="border border-blue-100 rounded-lg overflow-hidden">
            <button 
                @click.stop="open = !open" 
                class="w-full flex justify-between items-center bg-blue-50 px-3 py-2 text-left transition-colors hover:bg-blue-100"
            >
                <div class="flex items-center">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                    <span class="text-xs font-medium text-blue-800">Full School Day</span>
                </div>
                <svg 
                    :class="{'rotate-180': open}" 
                    class="w-4 h-4 text-blue-500 transform transition-transform duration-200" 
                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div 
                x-show="open" 
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="px-3 py-2 text-xs text-gray-600 bg-white"
            >
                <ul class="list-disc pl-5 space-y-1">
                    <li>School hours: 8:00 AM - 3:30 PM</li>
                    <li>Lesson duration: 40 minutes</li>
                    <li>Include short breaks after every 2 lessons (10 minutes)</li>
                    <li>Include lunch break at 12:30 PM (40 minutes)</li>
                    <li>Optionally include games & activities at 2:30 PM (40 minutes)</li>
                    <li>Add 5 minutes of movement time between classes</li>
                </ul>
            </div>
            </div>
            
        <div x-data="{ open: false }" class="border border-blue-100 rounded-lg overflow-hidden">
            <button 
                @click.stop="open = !open" 
                class="w-full flex justify-between items-center bg-blue-50 px-3 py-2 text-left transition-colors hover:bg-blue-100"
            >
                <div class="flex items-center">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                    <span class="text-xs font-medium text-blue-800">Half Day Schedule</span>
                </div>
                <svg 
                    :class="{'rotate-180': open}" 
                    class="w-4 h-4 text-blue-500 transform transition-transform duration-200" 
                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div 
                x-show="open" 
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="px-3 py-2 text-xs text-gray-600 bg-white"
            >
                <ul class="list-disc pl-5 space-y-1">
                    <li>School hours: 8:00 AM - 12:30 PM</li>
                    <li>Lesson duration: 40 minutes</li>
                    <li>Include short breaks after every 2 lessons (10 minutes)</li>
                    <li>Disable lunch break and games (not enough time)</li>
                    <li>Add 3 minutes of movement time between classes</li>
                </ul>
            </div>
            </div>
            
        <div x-data="{ open: false }" class="border border-blue-100 rounded-lg overflow-hidden">
            <button 
                @click.stop="open = !open" 
                class="w-full flex justify-between items-center bg-blue-50 px-3 py-2 text-left transition-colors hover:bg-blue-100"
            >
                <div class="flex items-center">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                    <span class="text-xs font-medium text-blue-800">Extended Day with Activities</span>
                </div>
                <svg 
                    :class="{'rotate-180': open}" 
                    class="w-4 h-4 text-blue-500 transform transition-transform duration-200" 
                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div 
                x-show="open" 
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="px-3 py-2 text-xs text-gray-600 bg-white"
            >
                <ul class="list-disc pl-5 space-y-1">
                    <li>School hours: 7:45 AM - 4:15 PM</li>
                    <li>Lesson duration: 45 minutes</li>
                    <li>Include short breaks after every 3 lessons (15 minutes)</li>
                    <li>Include lunch at 12:30 PM (45 minutes)</li>
                    <li>Include games & activities at 2:45 PM (60 minutes)</li>
                    <li>Add 5 minutes of movement time between classes</li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Pro Tips Section -->
    <div x-show="activeTab === 'tips'" class="space-y-3">
        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-3 border border-green-200">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-green-600 mt-0.5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
                <div class="text-xs text-gray-700">
                    <span class="font-medium">Movement Time:</span> Adding 3-5 minutes of movement time between classes helps create a more realistic schedule, giving students and teachers time to transition between classrooms.
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg p-3 border border-yellow-200">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-xs text-gray-700">
                    <span class="font-medium">Break Frequency:</span> For younger students, schedule breaks more frequently (after every 1-2 lessons). For older students, breaks can be less frequent (after every 2-3 lessons).
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-3 border border-blue-200">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <div class="text-xs text-gray-700">
                    <span class="font-medium">Lunch Timing:</span> For optimal learning, schedule lunch around the middle of the day (11:30 AM - 1:00 PM). Younger students may need earlier lunch times.
                </div>
            </div>
        </div>
    </div>
    
    <!-- Information Popups -->
    <div x-show="tipVisible" x-transition.opacity class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click.stop="tipVisible = false"></div>
            
            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-auto overflow-hidden">
                <!-- School Hours Tip -->
                <div x-show="activeTip === 'schoolHours'" class="divide-y divide-gray-200">
                    <div class="px-4 py-3 bg-gradient-to-r from-green-50 to-green-100">
                        <div class="flex justify-between items-center">
                            <h3 class="text-sm font-medium text-gray-800">School Hours</h3>
                            <button @click.stop="tipVisible = false" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-4 text-xs text-gray-600 space-y-2">
                        <p>School hours define the start and end time of your school day. These are the boundaries for generating your timetable.</p>
                        <p class="font-medium text-gray-700">Recommended Settings:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Primary Schools: 8:00 AM - 3:00 PM</li>
                            <li>Secondary Schools: 8:00 AM - 3:30 PM</li>
                            <li>Boarding Schools: 7:45 AM - 4:15 PM</li>
                        </ul>
                        <p class="text-gray-500 italic mt-2">Remember to consider your local context and transportation schedules when setting school hours.</p>
                    </div>
                </div>
                
                <!-- Lesson Duration Tip -->
                <div x-show="activeTip === 'lessonDuration'" class="divide-y divide-gray-200">
                    <div class="px-4 py-3 bg-gradient-to-r from-green-50 to-green-100">
                        <div class="flex justify-between items-center">
                            <h3 class="text-sm font-medium text-gray-800">Lesson Duration</h3>
                            <button @click.stop="tipVisible = false" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-4 text-xs text-gray-600 space-y-2">
                        <p>Lesson duration is the standard length of each teaching period in your timetable.</p>
                        <p class="font-medium text-gray-700">Recommended Durations:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Lower primary: 30-35 minutes (shorter attention spans)</li>
                            <li>Upper primary: 35-40 minutes</li>
                            <li>Secondary: 40-45 minutes</li>
                            <li>Advanced courses: 45-60 minutes</li>
                        </ul>
                        <p class="text-blue-600 flex items-center my-2">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Educational research suggests 40-45 minutes is optimal for secondary school students.
                        </p>
                    </div>
                </div>
                
                <!-- Activities Tip -->
                <div x-show="activeTip === 'activities'" class="divide-y divide-gray-200">
                    <div class="px-4 py-3 bg-gradient-to-r from-green-50 to-green-100">
                        <div class="flex justify-between items-center">
                            <h3 class="text-sm font-medium text-gray-800">Activity Types</h3>
                            <button @click.stop="tipVisible = false" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-4 text-xs text-gray-600 space-y-2">
                        <p>You can enable various types of activities to create a balanced school day:</p>
                        <ul class="space-y-2 mt-2">
                            <li class="flex items-start">
                                <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-blue-100 text-blue-600 mr-2 flex-shrink-0">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                                <div>
                                    <span class="font-medium text-gray-700">Short Breaks</span> (10-15 mins): Allow students to refresh between lessons, use restrooms, and prepare for next class
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-yellow-100 text-yellow-600 mr-2 flex-shrink-0">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3V3z" />
                                    </svg>
                                </span>
                                <div>
                                    <span class="font-medium text-gray-700">Lunch</span> (30-60 mins): Essential for nutrition and social development. Usually scheduled mid-day
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-purple-100 text-purple-600 mr-2 flex-shrink-0">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    </svg>
                                </span>
                                <div>
                                    <span class="font-medium text-gray-700">Games & Activities</span> (40-60 mins): Physical education, clubs and extracurriculars. Good for afternoons
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-gray-100 text-gray-600 mr-2 flex-shrink-0">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </span>
                                <div>
                                    <span class="font-medium text-gray-700">Movement Time</span> (3-5 mins): Transition time between classes. Especially important for larger schools
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 