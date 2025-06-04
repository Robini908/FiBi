<div @click.stop x-data="{
    // UI state
    viewMode: 'list',
    timeSlots: [],
    
    // Form values
    startTime: null,
    endTime: null,
    lessonDuration: null,
    breakDuration: null,
    breakAfterLessons: null,
    includeLunch: false,
    lunchTime: null,
    lunchDuration: null,
    includeBreaks: false,
    includeTransitions: false,
    transitionTime: null,
    includeExtracurricular: false,
    extracurricularStartTime: null,
    extracurricularEndTime: null,
    includeWeekendClasses: false,
    weekendDay: 'saturday',
    weekendStartTime: null,
    weekendEndTime: null,
    weekendLessonDuration: null,
    includeMorningPrep: false,
    morningPrepStartTime: null,
    morningPrepEndTime: null,
    includeEveningPrep: false,
    eveningPrepStartTime: null,
    eveningPrepEndTime: null,
    
    init() {
        // Initialize with values from the form
        this.startTime = <?php echo \Illuminate\Support\Js::from($autoGenerateForm['school_start_time'] ?? '08:00')->toHtml() ?>;
        this.endTime = <?php echo \Illuminate\Support\Js::from($autoGenerateForm['school_end_time'] ?? '15:30')->toHtml() ?>;
        this.lessonDuration = parseInt(<?php echo \Illuminate\Support\Js::from($autoGenerateForm['lesson_duration'] ?? 40)->toHtml() ?>);
        this.breakDuration = parseInt(<?php echo \Illuminate\Support\Js::from($autoGenerateForm['break_duration'] ?? 15)->toHtml() ?>);
        this.includeBreaks = Boolean(<?php echo \Illuminate\Support\Js::from($autoGenerateForm['include_breaks'] ?? false)->toHtml() ?>);
        this.includeLunch = Boolean(<?php echo \Illuminate\Support\Js::from($autoGenerateForm['include_lunch'] ?? false)->toHtml() ?>);
        this.lunchTime = <?php echo \Illuminate\Support\Js::from($autoGenerateForm['lunch_time'] ?? '12:30')->toHtml() ?>;
        this.lunchDuration = parseInt(<?php echo \Illuminate\Support\Js::from($autoGenerateForm['lunch_duration'] ?? 40)->toHtml() ?>);
        this.includeTransitions = Boolean(<?php echo \Illuminate\Support\Js::from($autoGenerateForm['include_transitions'] ?? false)->toHtml() ?>);
        this.transitionTime = parseInt(<?php echo \Illuminate\Support\Js::from($autoGenerateForm['transition_duration'] ?? 5)->toHtml() ?>);
        this.includeExtracurricular = Boolean(<?php echo \Illuminate\Support\Js::from($autoGenerateForm['include_extracurricular'] ?? false)->toHtml() ?>);
        this.extracurricularStartTime = <?php echo \Illuminate\Support\Js::from($autoGenerateForm['extracurricular_start_time'] ?? '15:30')->toHtml() ?>;
        this.extracurricularEndTime = <?php echo \Illuminate\Support\Js::from($autoGenerateForm['extracurricular_end_time'] ?? '17:00')->toHtml() ?>;
        this.includeWeekendClasses = Boolean(<?php echo \Illuminate\Support\Js::from($autoGenerateForm['include_weekend_classes'] ?? false)->toHtml() ?>);
        this.weekendDay = <?php echo \Illuminate\Support\Js::from($autoGenerateForm['weekend_day'] ?? 'saturday')->toHtml() ?>;
        this.weekendStartTime = <?php echo \Illuminate\Support\Js::from($autoGenerateForm['weekend_start_time'] ?? '09:00')->toHtml() ?>;
        this.weekendEndTime = <?php echo \Illuminate\Support\Js::from($autoGenerateForm['weekend_end_time'] ?? '13:00')->toHtml() ?>;
        this.weekendLessonDuration = parseInt(<?php echo \Illuminate\Support\Js::from($autoGenerateForm['weekend_lesson_duration'] ?? 60)->toHtml() ?>);
        this.includeMorningPrep = Boolean(<?php echo \Illuminate\Support\Js::from($autoGenerateForm['include_morning_prep'] ?? false)->toHtml() ?>);
        this.morningPrepStartTime = <?php echo \Illuminate\Support\Js::from($autoGenerateForm['morning_prep_start_time'] ?? '06:00')->toHtml() ?>;
        this.morningPrepEndTime = <?php echo \Illuminate\Support\Js::from($autoGenerateForm['morning_prep_end_time'] ?? '07:30')->toHtml() ?>;
        this.includeEveningPrep = Boolean(<?php echo \Illuminate\Support\Js::from($autoGenerateForm['include_evening_prep'] ?? false)->toHtml() ?>);
        this.eveningPrepStartTime = <?php echo \Illuminate\Support\Js::from($autoGenerateForm['evening_prep_start_time'] ?? '19:00')->toHtml() ?>;
        this.eveningPrepEndTime = <?php echo \Illuminate\Support\Js::from($autoGenerateForm['evening_prep_end_time'] ?? '21:00')->toHtml() ?>;
        
        console.log('Preview initialized with:', {
            startTime: this.startTime,
            endTime: this.endTime,
            lessonDuration: this.lessonDuration,
            includeBreaks: this.includeBreaks,
            includeLunch: this.includeLunch,
            includeExtracurricular: this.includeExtracurricular,
            extracurricularStartTime: this.extracurricularStartTime,
            extracurricularEndTime: this.extracurricularEndTime,
            includeWeekendClasses: this.includeWeekendClasses
        });
        
        // Generate the preview
        this.generatePreview();
    },
    
    // Convert time string (HH:MM) to minutes since midnight
    timeToMinutes(timeStr) {
        if (!timeStr) return 0;
        
        const [hours, minutes] = timeStr.split(':').map(Number);
        if (isNaN(hours) || isNaN(minutes)) return 0;
        
        return hours * 60 + minutes;
    },
    
    // Convert minutes since midnight to time string (HH:MM)
    minutesToTime(totalMinutes) {
        if (totalMinutes === undefined || isNaN(totalMinutes)) {
            return '00:00';
        }
        
        const hours = Math.floor(totalMinutes / 60);
        const minutes = totalMinutes % 60;
        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
    },
    
    // Format minutes to display time
    formatTime(minutes) {
        if (minutes === undefined || isNaN(minutes)) {
            return '00:00';
        }
        
        const time = this.minutesToTime(minutes);
        const [hours, mins] = time.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12;
        return `${hour12}:${mins} ${ampm}`;
    },
    
    getTimeDifference(start, end) {
        const startMinutes = this.timeToMinutes(start);
        const endMinutes = this.timeToMinutes(end);
        return endMinutes - startMinutes;
    },
    
    generatePreview() {
        if (!this.startTime || !this.endTime) return;
        
        let allTimeSlots = [];
        
        // Generate regular weekday schedule
        const weekdaySchedule = this.generateDaySchedule(
            'Regular', 
            this.startTime, 
            this.endTime, 
            this.lessonDuration, 
            this.includeBreaks, 
            this.breakDuration, 
            this.includeTransitions ? this.transitionTime : 0
        );
        allTimeSlots = [...allTimeSlots, ...weekdaySchedule];
        
        // Add morning prep if enabled
        if (this.includeMorningPrep && this.morningPrepStartTime && this.morningPrepEndTime) {
            const morningPrepStart = this.timeToMinutes(this.morningPrepStartTime);
            const morningPrepEnd = this.timeToMinutes(this.morningPrepEndTime);
            
            if (morningPrepEnd > morningPrepStart) {
                allTimeSlots.push({
                    name: 'Morning Prep',
                    startTime: morningPrepStart,
                    endTime: morningPrepEnd,
                    duration: morningPrepEnd - morningPrepStart,
                    type: 'prep',
                    day: 'Weekday Morning'
                });
            }
        }
        
        // Add evening prep if enabled
        if (this.includeEveningPrep && this.eveningPrepStartTime && this.eveningPrepEndTime) {
            const eveningPrepStart = this.timeToMinutes(this.eveningPrepStartTime);
            const eveningPrepEnd = this.timeToMinutes(this.eveningPrepEndTime);
            
            if (eveningPrepEnd > eveningPrepStart) {
                allTimeSlots.push({
                    name: 'Evening Prep',
                    startTime: eveningPrepStart,
                    endTime: eveningPrepEnd,
                    duration: eveningPrepEnd - eveningPrepStart,
                    type: 'prep',
                    day: 'Weekday Evening'
                });
            }
        }
        
        // Add weekend classes if enabled
        if (this.includeWeekendClasses && this.weekendStartTime && this.weekendEndTime && this.weekendLessonDuration) {
            let weekendDays = [];
            
            // Handle the different weekend day options
            if (this.weekendDay === 'both') {
                weekendDays = ['Saturday', 'Sunday'];
            } else if (this.weekendDay === 'saturday' || this.weekendDay === 'sunday') {
                weekendDays = [this.weekendDay.charAt(0).toUpperCase() + this.weekendDay.slice(1)];
            } else {
                weekendDays = ['Saturday']; // Default to Saturday if invalid value
            }
            
            for (const day of weekendDays) {
                const weekendSchedule = this.generateDaySchedule(
                    day,
                    this.weekendStartTime,
                    this.weekendEndTime,
                    this.weekendLessonDuration,
                    this.includeBreaks,
                    this.breakDuration,
                    this.includeTransitions ? this.transitionTime : 0
                );
                
                allTimeSlots = [...allTimeSlots, ...weekendSchedule];
            }
        }
        
        // Sort time slots by day type and start time
        allTimeSlots.sort((a, b) => {
            // First by day type (Regular first, then Weekend, then Prep)
            const dayOrder = { 'Regular': 0, 'Saturday': 1, 'Sunday': 2, 'Weekday Morning': 3, 'Weekday Evening': 4 };
            const dayDiff = (dayOrder[a.day] || 99) - (dayOrder[b.day] || 99);
            if (dayDiff !== 0) return dayDiff;
            
            // Then by start time
            return a.startTime - b.startTime;
        });
        
        this.timeSlots = allTimeSlots;
    },
    
    generateDaySchedule(day, startTimeStr, endTimeStr, lessonDuration, includeBreaks, breakDuration, transitionTime) {
        // Return empty array if inputs are invalid
        if (!startTimeStr || !endTimeStr || !lessonDuration) return [];
        
        let daySchedule = [];
        let startTime = this.timeToMinutes(startTimeStr);
        let endTime = this.timeToMinutes(endTimeStr);
        
        if (startTime >= endTime) return [];
        
        let totalTime = endTime - startTime;
        let currentTime = startTime;
        let periodCount = 1;
        
        // If lunch is included and this is a regular day (not weekend)
        let lunchTime = null;
        if (this.includeLunch && day === 'Regular') {
            lunchTime = this.timeToMinutes(this.lunchTime);
            // If lunch time is within our school hours
            if (lunchTime >= startTime && lunchTime <= endTime) {
                // Ensure lunch doesn't start in the middle of a period
                let timeTillLunch = lunchTime - startTime;
                let periodsTillLunch = Math.floor(timeTillLunch / (lessonDuration + (includeBreaks ? breakDuration : 0) + transitionTime));
                lunchTime = startTime + (periodsTillLunch * (lessonDuration + (includeBreaks ? breakDuration : 0) + transitionTime));
            }
        }
        
        // For weekend classes, we use a different naming convention
        let periodPrefix = day === 'Regular' ? 'Period ' : (day + ' Class ');
        
        while (currentTime < endTime) {
            // Check if we've reached lunch time
            if (lunchTime && currentTime >= lunchTime && day === 'Regular') {
                daySchedule.push({
                    name: 'Lunch Break',
                    startTime: currentTime,
                    endTime: currentTime + this.lunchDuration,
                    duration: this.lunchDuration,
                    type: 'lunch',
                    day: day
                });
                
                currentTime += this.lunchDuration;
                lunchTime = null; // Mark lunch as added
                continue;
            }
            
            // Add a lesson period
            let periodEndTime = currentTime + lessonDuration;
            if (periodEndTime > endTime) {
                periodEndTime = endTime;
            }
            
            daySchedule.push({
                name: periodPrefix + periodCount,
                startTime: currentTime,
                endTime: periodEndTime,
                duration: periodEndTime - currentTime,
                type: day === 'Regular' ? 'lesson' : 'weekend',
                day: day
            });
            
            currentTime = periodEndTime;
            periodCount++;
            
            // If we've reached the end time, stop
            if (currentTime >= endTime) break;
            
            // Add a transition period if needed
            if (transitionTime > 0) {
                let transitionEndTime = currentTime + transitionTime;
                if (transitionEndTime > endTime) {
                    transitionEndTime = endTime;
                }
                
                daySchedule.push({
                    name: 'Transition',
                    startTime: currentTime,
                    endTime: transitionEndTime,
                    duration: transitionEndTime - currentTime,
                    type: 'transition',
                    day: day
                });
                
                currentTime = transitionEndTime;
            }
            
            // If we've reached the end time, stop
            if (currentTime >= endTime) break;
            
            // Add a break if needed
            if (includeBreaks && breakDuration > 0) {
                let breakEndTime = currentTime + breakDuration;
                if (breakEndTime > endTime) {
                    breakEndTime = endTime;
                }
                
                daySchedule.push({
                    name: 'Break',
                    startTime: currentTime,
                    endTime: breakEndTime,
                    duration: breakEndTime - currentTime,
                    type: 'break',
                    day: day
                });
                
                currentTime = breakEndTime;
            }
            
            // If we've reached the end time, stop
            if (currentTime >= endTime) break;
            
            // Add another transition period if needed
            if (transitionTime > 0) {
                let transitionEndTime = currentTime + transitionTime;
                if (transitionEndTime > endTime) {
                    transitionEndTime = endTime;
                }
                
                daySchedule.push({
                    name: 'Transition',
                    startTime: currentTime,
                    endTime: transitionEndTime,
                    duration: transitionEndTime - currentTime,
                    type: 'transition',
                    day: day
                });
                
                currentTime = transitionEndTime;
            }
        }
        
        // Handle extracurricular activities (for regular days only)
        if (this.includeExtracurricular && day === 'Regular') {
            // Add a transition before extracurricular if needed
            if (transitionTime > 0 && currentTime < endTime) {
                let transitionEndTime = currentTime + transitionTime;
                daySchedule.push({
                    name: 'Transition',
                    startTime: currentTime,
                            endTime: transitionEndTime,
                    duration: transitionEndTime - currentTime,
                            type: 'transition',
                    day: day
                });
                
                currentTime = transitionEndTime;
            }
            
            // Add extracurricular period
            let ecStartTime = Math.max(currentTime, this.timeToMinutes(this.extracurricularStartTime));
            let ecEndTime = this.timeToMinutes(this.extracurricularEndTime);
            
            if (ecEndTime > ecStartTime) {
                daySchedule.push({
                    name: 'Extracurricular Activities',
                    startTime: ecStartTime,
                    endTime: ecEndTime,
                    duration: ecEndTime - ecStartTime,
                    type: 'extracurricular',
                    day: day
                });
            }
        }
        
        return daySchedule;
    }
}" x-init="init" class="schedule-preview">
    <template x-if="timeSlots.length === 0">
        <div class="text-center py-6">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="mt-2 text-sm text-gray-500">Configure your schedule parameters above.</p>
            <p class="text-xs text-gray-400">Preview will update automatically.</p>
        </div>
    </template>
    
    <div x-show="timeSlots.length > 0" class="space-y-2 time-slots-preview">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-xs font-semibold text-gray-700">Generated Schedule</h3>
            
            <div class="flex items-center space-x-3">
                <!-- View switcher -->
                <div class="flex items-center bg-gray-100 rounded-md p-0.5">
                    <button @click.stop="viewMode = 'list'" :class="{'bg-white shadow-sm': viewMode === 'list', 'text-gray-500 hover:text-gray-700': viewMode !== 'list'}" class="px-2 py-1 rounded-md text-xs font-medium transition-colors duration-150 flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        List
                    </button>
                    <button @click.stop="viewMode = 'table'" :class="{'bg-white shadow-sm': viewMode === 'table', 'text-gray-500 hover:text-gray-700': viewMode !== 'table'}" class="px-2 py-1 rounded-md text-xs font-medium transition-colors duration-150 flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Table
                    </button>
                </div>
                
                <span class="text-xs text-gray-500">
                    <span x-text="formatTime(startTime)"></span> - <span x-text="formatTime(endTime)"></span>
                </span>
            </div>
        </div>
        
        <!-- List View -->
        <div x-show="viewMode === 'list'" class="space-y-1.5 max-h-[350px] overflow-y-auto scrollbar-thin pr-2">
            <template x-for="(slot, index) in timeSlots" :key="index">
                <div :class="{
                    'flex items-center p-2 rounded-lg border': true,
                    'bg-green-50 border-green-200': slot.type === 'lesson',
                    'bg-blue-50 border-blue-200': slot.type === 'break',
                    'bg-yellow-50 border-yellow-200': slot.type === 'lunch',
                    'bg-purple-50 border-purple-200': slot.type === 'extracurricular',
                    'bg-gray-50 border-gray-200': slot.type === 'transition',
                    'bg-indigo-50 border-indigo-200': slot.type === 'weekend',
                    'bg-amber-50 border-amber-200': slot.type === 'prep'
                }">
                    <div :class="{
                        'flex-shrink-0 w-2 h-10 rounded-full mr-3': true,
                        'bg-green-400': slot.type === 'lesson',
                        'bg-blue-400': slot.type === 'break',
                        'bg-yellow-400': slot.type === 'lunch',
                        'bg-purple-400': slot.type === 'extracurricular',
                        'bg-gray-400': slot.type === 'transition',
                        'bg-indigo-400': slot.type === 'weekend',
                        'bg-amber-400': slot.type === 'prep'
                    }"></div>
                    <div class="flex-grow">
                        <div class="flex justify-between">
                            <p :class="{
                                'text-xs font-medium': true,
                                'text-green-700': slot.type === 'lesson',
                                'text-blue-700': slot.type === 'break',
                                'text-yellow-700': slot.type === 'lunch',
                                'text-purple-700': slot.type === 'extracurricular',
                                'text-gray-700': slot.type === 'transition',
                                'text-indigo-700': slot.type === 'weekend',
                                'text-amber-700': slot.type === 'prep'
                            }" x-text="slot.name"></p>
                            <p class="text-xs text-gray-500">
                                <span x-text="formatTime(slot.startTime)"></span> - <span x-text="formatTime(slot.endTime)"></span>
                            </p>
                        </div>
                        <div class="mt-1 flex items-center justify-between">
                            <div class="flex items-center">
                                <span :class="{
                                    'inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium': true,
                                    'bg-green-100 text-green-800': slot.type === 'lesson',
                                    'bg-blue-100 text-blue-800': slot.type === 'break',
                                    'bg-yellow-100 text-yellow-800': slot.type === 'lunch',
                                    'bg-purple-100 text-purple-800': slot.type === 'extracurricular',
                                    'bg-gray-100 text-gray-800': slot.type === 'transition',
                                    'bg-indigo-100 text-indigo-800': slot.type === 'weekend',
                                    'bg-amber-100 text-amber-800': slot.type === 'prep'
                                }">
                                    <svg :class="{
                                        'w-3 h-3 mr-1': true,
                                        'text-green-500': slot.type === 'lesson',
                                        'text-blue-500': slot.type === 'break',
                                        'text-yellow-500': slot.type === 'lunch',
                                        'text-purple-500': slot.type === 'extracurricular',
                                        'text-gray-500': slot.type === 'transition',
                                        'text-indigo-500': slot.type === 'weekend',
                                        'text-amber-500': slot.type === 'prep'
                                    }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            x-show="slot.type === 'lesson'"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            x-show="slot.type === 'break'"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            x-show="slot.type === 'lunch'"
                                            d="M3 3h18v18H3V3z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            x-show="slot.type === 'extracurricular'"
                                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            x-show="slot.type === 'transition'"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            x-show="slot.type === 'weekend'"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            x-show="slot.type === 'prep'"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <span x-text="slot.type.charAt(0).toUpperCase() + slot.type.slice(1)"></span>
                                </span>
                                
                                <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <span x-text="slot.duration + ' mins'"></span>
                                </span>
                            </div>
                            
                            <span x-show="slot.day && slot.day !== 'Regular'" class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                <span x-text="slot.day"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        
        <!-- Table View -->
        <div x-show="viewMode === 'table'" class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-green-600 to-green-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Period</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Day</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Time</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Duration</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Type</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="(slot, index) in timeSlots" :key="index">
                        <tr :class="{
                            'bg-green-50': slot.type === 'lesson',
                            'bg-blue-50': slot.type === 'break',
                            'bg-yellow-50': slot.type === 'lunch',
                            'bg-purple-50': slot.type === 'extracurricular',
                            'bg-gray-50': slot.type === 'transition',
                            'bg-indigo-50': slot.type === 'weekend',
                            'bg-amber-50': slot.type === 'prep'
                        }">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div :class="{
                                        'flex-shrink-0 h-8 w-2 rounded mr-3': true,
                                        'bg-green-400': slot.type === 'lesson',
                                        'bg-blue-400': slot.type === 'break',
                                        'bg-yellow-400': slot.type === 'lunch',
                                        'bg-purple-400': slot.type === 'extracurricular',
                                        'bg-gray-400': slot.type === 'transition',
                                        'bg-indigo-400': slot.type === 'weekend',
                                        'bg-amber-400': slot.type === 'prep'
                                    }"></div>
                                    <span :class="{
                                        'text-sm font-medium': true,
                                        'text-green-700': slot.type === 'lesson',
                                        'text-blue-700': slot.type === 'break',
                                        'text-yellow-700': slot.type === 'lunch',
                                        'text-purple-700': slot.type === 'extracurricular',
                                        'text-gray-700': slot.type === 'transition',
                                        'text-indigo-700': slot.type === 'weekend',
                                        'text-amber-700': slot.type === 'prep'
                                    }" x-text="slot.name"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-700" x-text="slot.day || 'Regular'"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <span x-text="formatTime(slot.startTime)"></span> - <span x-text="formatTime(slot.endTime)"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <span x-text="slot.duration + ' mins'"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="{
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium': true,
                                    'bg-green-100 text-green-800': slot.type === 'lesson',
                                    'bg-blue-100 text-blue-800': slot.type === 'break',
                                    'bg-yellow-100 text-yellow-800': slot.type === 'lunch',
                                    'bg-purple-100 text-purple-800': slot.type === 'extracurricular',
                                    'bg-gray-100 text-gray-800': slot.type === 'transition',
                                    'bg-indigo-100 text-indigo-800': slot.type === 'weekend',
                                    'bg-amber-100 text-amber-800': slot.type === 'prep'
                                }" x-text="slot.type.charAt(0).toUpperCase() + slot.type.slice(1)"></span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 text-xs flex flex-wrap gap-2">
            <div class="inline-flex items-center">
                <span class="w-2 h-2 bg-green-400 rounded-full mr-1"></span>
                <span class="text-gray-700">Lesson</span>
            </div>
            <div x-show="includeBreaks" class="inline-flex items-center">
                <span class="w-2 h-2 bg-blue-400 rounded-full mr-1"></span>
                <span class="text-gray-700">Break</span>
            </div>
            <div x-show="includeLunch" class="inline-flex items-center">
                <span class="w-2 h-2 bg-yellow-400 rounded-full mr-1"></span>
                <span class="text-gray-700">Lunch</span>
            </div>
            <div x-show="includeExtracurricular" class="inline-flex items-center">
                <span class="w-2 h-2 bg-purple-400 rounded-full mr-1"></span>
                <span class="text-gray-700">Activities</span>
            </div>
            <div x-show="includeTransitions" class="inline-flex items-center">
                <span class="w-2 h-2 bg-gray-400 rounded-full mr-1"></span>
                <span class="text-gray-700">Movement</span>
            </div>
            <div x-show="includeWeekendClasses" class="inline-flex items-center">
                <span class="w-2 h-2 bg-indigo-400 rounded-full mr-1"></span>
                <span class="text-gray-700">Weekend</span>
            </div>
            <div x-show="includeMorningPrep || includeEveningPrep" class="inline-flex items-center">
                <span class="w-2 h-2 bg-amber-400 rounded-full mr-1"></span>
                <span class="text-gray-700">Prep</span>
            </div>
        </div>
        
        <!-- Legend -->
        <div class="mt-4 p-3 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h4 class="text-sm font-medium mb-2 text-gray-700">Legend:</h4>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-7 gap-2">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-green-400 mr-2"></div>
                    <span class="text-xs text-gray-600">Lesson</span>
                </div>
                <div x-show="includeBreaks" class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-blue-400 mr-2"></div>
                    <span class="text-xs text-gray-600">Break</span>
                </div>
                <div x-show="includeLunch" class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-yellow-400 mr-2"></div>
                    <span class="text-xs text-gray-600">Lunch</span>
                </div>
                <div x-show="includeExtracurricular" class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-purple-400 mr-2"></div>
                    <span class="text-xs text-gray-600">Extracurricular</span>
                </div>
                <div x-show="includeTransitions" class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-gray-400 mr-2"></div>
                    <span class="text-xs text-gray-600">Transition</span>
                </div>
                <div x-show="includeWeekendClasses" class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-indigo-400 mr-2"></div>
                    <span class="text-xs text-gray-600">Weekend</span>
                </div>
                <div x-show="includeMorningPrep || includeEveningPrep" class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-amber-400 mr-2"></div>
                    <span class="text-xs text-gray-600">Prep</span>
                </div>
            </div>
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/timetable/partials/time-slots-preview.blade.php ENDPATH**/ ?>