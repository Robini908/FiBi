<!-- Subject Preferences Tab Content -->
<div class="space-y-5 py-3">
    <?php
        // Define color mapping globally for the template so it's available everywhere
        $colorMapping = [
            // Mathematics/Science categories (blue hues)
            'Mathematics' => 'bg-blue-100 text-blue-800',
            'Math' => 'bg-blue-100 text-blue-800',
            'Science' => 'bg-cyan-100 text-cyan-800',
            'Physics' => 'bg-cyan-100 text-cyan-800',
            'Chemistry' => 'bg-teal-100 text-teal-800',
            'Biology' => 'bg-emerald-100 text-emerald-800',
            
            // Language categories (purple hues)
            'Language' => 'bg-indigo-100 text-indigo-800',
            'Languages' => 'bg-indigo-100 text-indigo-800',
            'English' => 'bg-violet-100 text-violet-800',
            'Literature' => 'bg-violet-100 text-violet-800',
            'Foreign' => 'bg-fuchsia-100 text-fuchsia-800',
            
            // Art/Creative categories (pink/red hues)
            'Arts' => 'bg-purple-100 text-purple-800',
            'Music' => 'bg-pink-100 text-pink-800',
            'Drama' => 'bg-rose-100 text-rose-800',
            'Creative' => 'bg-purple-100 text-purple-800',
            
            // Humanities (warm hues)
            'Humanities' => 'bg-amber-100 text-amber-800',
            'History' => 'bg-orange-100 text-orange-800',
            'Geography' => 'bg-amber-100 text-amber-800',
            'Social' => 'bg-yellow-100 text-yellow-800',
            'Religious' => 'bg-lime-100 text-lime-800',
            
            // Physical categories (green hues)
            'Physical Education' => 'bg-green-100 text-green-800',
            'Sports' => 'bg-green-100 text-green-800',
            'Health' => 'bg-emerald-100 text-emerald-800',
            
            // Technical categories (red hues)
            'Technology' => 'bg-rose-100 text-rose-800',
            'Computer' => 'bg-rose-100 text-rose-800',
            'IT' => 'bg-red-100 text-red-800',
            'ICT' => 'bg-red-100 text-red-800',
            
            // Core category
            'Core' => 'bg-blue-100 text-blue-800',
            
            // Default
            'Uncategorized' => 'bg-gray-100 text-gray-800'
        ];
    ?>

    <!-- Status Card -->
    <div class="bg-green-50 p-4 rounded-lg border border-green-200 flex items-center justify-between">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
            </div>
            <div>
                <p class="font-medium text-green-700"><span class="text-green-600 font-bold"><?php echo e(count($autoGenerateForm['subject_preferences'] ?? [])); ?></span> subjects available for timetable generation</p>
                <!--[if BLOCK]><![endif]--><?php if(empty($autoGenerateForm['subject_preferences'])): ?>
                    <p class="text-yellow-600 font-bold text-sm">No subject preferences found! Using default subjects.</p>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
        <div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Set preferences to optimize scheduling
            </span>
        </div>
    </div>
    
    <!-- Subject Preferences Table -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
        <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <div>
                <h3 class="text-sm font-medium text-gray-700">Subject Scheduling Preferences</h3>
                <p class="text-xs text-gray-500 mt-1">Configure subject scheduling preferences and frequency</p>
            </div>
            <div class="flex items-center">
                <input type="checkbox" 
                    wire:model.defer="autoGenerateForm.enable_subject_time_preferences" 
                    id="enable_time_prefs_top" 
                    class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                <label for="enable_time_prefs_top" class="ml-2 block text-sm font-medium text-gray-700">Enable time preferences</label>
            </div>
        </div>

        <!-- Compact Table Design -->
        <div class="overflow-x-auto max-h-[400px]">
            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                <thead class="bg-gray-50 sticky top-0 z-10">
                    <tr>
                        <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[30%]">
                            Subject
                        </th>
                        <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[30%]">
                            Preferred Time
                        </th>
                        <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap w-[12%]">
                            Max<br>Consecutive
                        </th>
                        <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap w-[14%]">
                            Weekly<br>Frequency
                        </th>
                        <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[14%]">
                            Daily<br>Limit
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                <!--[if BLOCK]><![endif]--><?php if(!empty($autoGenerateForm['subject_preferences'])): ?>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $autoGenerateForm['subject_preferences']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subjectId => $preferences): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            // Get category to determine badge color
                            $category = $preferences['category'] ?? 'Uncategorized';
                            
                            // Generate a unique color for each category based on the category ID or hash
                            $categoryId = $preferences['category_id'] ?? null;
                            
                            // Get the color for the category, fallback to gray if not found
                            $categoryBadgeClass = $colorMapping[$category] ?? 'bg-gray-100 text-gray-800';
                            
                            // If no exact match, try to find a partial match
                            if (!isset($colorMapping[$category])) {
                                foreach($colorMapping as $key => $color) {
                                    if(str_contains(strtolower($category), strtolower($key))) {
                                        $categoryBadgeClass = $color;
                                        break;
                                    }
                                }
                            }
                            
                            // Get preferred time
                            $preferredTime = $preferences['preferred_time'] ?? 'any';
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-3 py-2 text-sm">
                                <div class="flex flex-col space-y-1">
                                    <div class="flex items-center">
                                        <span class="font-medium text-gray-900 truncate max-w-[120px]" title="<?php echo e($preferences['name'] ?? 'Unknown Subject'); ?>">
                                            <?php echo e($preferences['name'] ?? 'Unknown Subject'); ?>

                                        </span>
                                        <span class="ml-1.5 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium <?php echo e($categoryBadgeClass); ?>">
                                            <?php echo e($category); ?>

                                        </span>
                                    </div>
                                    <!--[if BLOCK]><![endif]--><?php if(isset($preferences['teacher_name']) && $preferences['teacher_name']): ?>
                                        <span class="text-xs text-green-600 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span class="truncate max-w-[120px]" title="<?php echo e($preferences['teacher_name']); ?>"><?php echo e($preferences['teacher_name']); ?></span>
                                        </span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </td>
                            <td class="px-3 py-2 text-sm">
                                <div x-data="{ time: '<?php echo e($preferredTime); ?>' }">
                                    <div class="flex flex-wrap gap-1.5">
                                        <label @click="time = 'morning'" 
                                            :class="{'bg-green-100 border-green-500 text-green-700': time === 'morning', 'bg-gray-50 border-gray-300 text-gray-500 hover:bg-gray-100': time !== 'morning'}"
                                            class="inline-flex items-center px-2 py-1 border rounded-md text-xs font-medium cursor-pointer transition-colors duration-200">
                                            <input type="radio" 
                                                   x-model="time"
                                                   name="time_pref_<?php echo e($subjectId); ?>" 
                                                   value="morning" 
                                                   wire:model.defer="autoGenerateForm.subject_preferences.<?php echo e($subjectId); ?>.preferred_time" 
                                                   class="sr-only">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                            AM
                                        </label>
                                        
                                        <label @click="time = 'midday'" 
                                            :class="{'bg-green-100 border-green-500 text-green-700': time === 'midday', 'bg-gray-50 border-gray-300 text-gray-500 hover:bg-gray-100': time !== 'midday'}"
                                            class="inline-flex items-center px-2 py-1 border rounded-md text-xs font-medium cursor-pointer transition-colors duration-200">
                                            <input type="radio" 
                                                   x-model="time"
                                                   name="time_pref_<?php echo e($subjectId); ?>" 
                                                   value="midday" 
                                                   wire:model.defer="autoGenerateForm.subject_preferences.<?php echo e($subjectId); ?>.preferred_time" 
                                                   class="sr-only">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Mid
                                        </label>
                                        
                                        <label @click="time = 'afternoon'" 
                                            :class="{'bg-green-100 border-green-500 text-green-700': time === 'afternoon', 'bg-gray-50 border-gray-300 text-gray-500 hover:bg-gray-100': time !== 'afternoon'}"
                                            class="inline-flex items-center px-2 py-1 border rounded-md text-xs font-medium cursor-pointer transition-colors duration-200">
                                            <input type="radio" 
                                                   x-model="time"
                                                   name="time_pref_<?php echo e($subjectId); ?>" 
                                                   value="afternoon" 
                                                   wire:model.defer="autoGenerateForm.subject_preferences.<?php echo e($subjectId); ?>.preferred_time" 
                                                   class="sr-only">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" />
                                            </svg>
                                            PM
                                        </label>
                                        
                                        <label @click="time = 'any'" 
                                            :class="{'bg-green-100 border-green-500 text-green-700': time === 'any', 'bg-gray-50 border-gray-300 text-gray-500 hover:bg-gray-100': time !== 'any'}"
                                            class="inline-flex items-center px-2 py-1 border rounded-md text-xs font-medium cursor-pointer transition-colors duration-200">
                                            <input type="radio" 
                                                   x-model="time"
                                                   name="time_pref_<?php echo e($subjectId); ?>" 
                                                   value="any" 
                                                   wire:model.defer="autoGenerateForm.subject_preferences.<?php echo e($subjectId); ?>.preferred_time" 
                                                   class="sr-only">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Any
                                        </label>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-2 text-sm text-center">
                                <div class="flex justify-center">
                                    <input 
                                        type="number" 
                                        min="1" 
                                        max="5" 
                                        value="<?php echo e($preferences['max_consecutive'] ?? 2); ?>"
                                        wire:model.defer="autoGenerateForm.subject_preferences.<?php echo e($subjectId); ?>.max_consecutive" 
                                        class="w-12 text-center shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                </div>
                            </td>
                            <td class="px-3 py-2 text-sm">
                                <div class="flex flex-col">
                                    <div class="flex justify-center mb-1">
                                        <input 
                                            type="number" 
                                            min="0" 
                                            max="10" 
                                            value="<?php echo e($preferences['weekly_frequency'] ?? 3); ?>"
                                            wire:model.defer="autoGenerateForm.subject_preferences.<?php echo e($subjectId); ?>.weekly_frequency" 
                                            class="w-12 text-center shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-green-600 h-1.5 rounded-full" style="width: <?php echo e(min(100, (($preferences['weekly_frequency'] ?? 3) / 10) * 100)); ?>%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-2 text-sm">
                                <select
                                    wire:model.defer="autoGenerateForm.subject_preferences.<?php echo e($subjectId); ?>.daily_limit" 
                                    class="w-full text-sm shadow-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                    <option value="1">Max 1</option>
                                    <option value="2">Max 2</option>
                                    <option value="3">Max 3</option>
                                    <option value="0">No limit</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <p class="mt-1 font-medium text-gray-500">No subjects available</p>
                                <p class="mt-1 text-xs text-gray-400">The system will use default subjects</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </tbody>
            </table>
        </div>
        
        <!-- Dynamic Legend for the table -->
        <div class="p-3 border-t border-gray-200 bg-gray-50 grid grid-cols-2 md:grid-cols-4 gap-2 text-xs text-gray-500">
            <?php
                // Define a function to extract colors from the colorMapping without duplicating code
                function getColorForCategoryDisplay($categoryName, $colors) {
                    if (isset($colors[$categoryName])) {
                        $colorClass = $colors[$categoryName];
                        // Strip the text color part to get just the bg color
                        $bgOnly = explode(' ', $colorClass)[0];
                        return $bgOnly;
                    }
                    
                    // Try partial matching
                    foreach ($colors as $key => $color) {
                        if (str_contains(strtolower($categoryName), strtolower($key))) {
                            $bgOnly = explode(' ', $color)[0];
                            return $bgOnly;
                        }
                    }
                    
                    return 'bg-gray-100'; // Default
                }
                
                // Get categories from the component property
                $dbCategories = $this->subjectCategories ?? collect();
                $displayCategories = [];
                
                // First, use available categories from the database
                if ($dbCategories->isNotEmpty()) {
                    foreach ($dbCategories as $category) {
                        $colorClass = getColorForCategoryDisplay($category->name, $colorMapping);
                        $displayCategories[$category->name] = $colorClass;
                    }
                } 
                // If no categories, or if we need more, extract from preferences
                else {
                    // Get unique categories from preferences
                    $uniqueCategories = [];
                    if (!empty($autoGenerateForm['subject_preferences'])) {
                        foreach ($autoGenerateForm['subject_preferences'] as $pref) {
                            $cat = $pref['category'] ?? 'Uncategorized';
                            if (!isset($uniqueCategories[$cat])) {
                                $colorClass = getColorForCategoryDisplay($cat, $colorMapping);
                                $uniqueCategories[$cat] = $colorClass;
                            }
                        }
                        $displayCategories = $uniqueCategories;
                    }
                }
                
                // If we still have no categories, show default ones
                if (empty($displayCategories)) {
                    $displayCategories = [
                        'Mathematics' => 'bg-blue-100',
                        'Science' => 'bg-cyan-100',
                        'Languages' => 'bg-indigo-100',
                        'Arts' => 'bg-purple-100',
                        'Humanities' => 'bg-amber-100',
                        'Sports/PE' => 'bg-green-100',
                        'Technology' => 'bg-rose-100',
                        'Other' => 'bg-gray-100'
                    ];
                }
            ?>
            
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $displayCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $colorClass): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-4 h-4 mr-2 rounded-full <?php echo e($colorClass); ?>"></div>
                    <span class="truncate"><?php echo e($category); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/timetable/partials/auto-generate-modal-subject-preferences.blade.php ENDPATH**/ ?>