@props([
    'month' => null,
    'year' => null,
    'events' => [],
    'dateFormat' => 'Y-m-d',
    'minDate' => null,
    'maxDate' => null,
    'disabledDates' => [],
    'highlightToday' => true,
    'startOfWeek' => 0, // 0 = Sunday, 1 = Monday
    'showAdjacentMonths' => true,
    'allowSelection' => false,
    'selectedDate' => null,
    'eventColors' => [
        'default' => 'bg-green-500',
        'primary' => 'bg-green-500',
        'secondary' => 'bg-gray-500',
        'success' => 'bg-green-500',
        'danger' => 'bg-red-500',
        'warning' => 'bg-yellow-500',
        'info' => 'bg-blue-500',
    ],
])

@php
    use Carbon\Carbon;
    
    // Set current month and year if not provided
    $now = Carbon::now();
    $month = $month ?? $now->month;
    $year = $year ?? $now->year;
    
    // Create a Carbon instance for the first day of the selected month
    $firstDayOfMonth = Carbon::createFromDate($year, $month, 1)->startOfDay();
    
    // Get month details
    $daysInMonth = $firstDayOfMonth->daysInMonth;
    $firstDayOfWeek = ($firstDayOfMonth->dayOfWeek - $startOfWeek + 7) % 7;
    $monthName = $firstDayOfMonth->format('F');
    
    // Get previous and next month for navigation
    $prevMonth = $firstDayOfMonth->copy()->subMonth();
    $nextMonth = $firstDayOfMonth->copy()->addMonth();
    
    // Calculate weeks needed to display (maximum 6 rows)
    $weeksNeeded = ceil(($daysInMonth + $firstDayOfWeek) / 7);
    
    // Day name labels 
    $dayLabels = [];
    $day = Carbon::now()->startOfWeek()->addDays($startOfWeek);
    
    for ($i = 0; $i < 7; $i++) {
        $dayLabels[] = $day->format('D');
        $day->addDay();
    }
    
    // Helper function to check if a date has events
    function hasEvents($date, $events, $dateFormat) {
        $formattedDate = $date->format($dateFormat);
        return array_filter($events, function($event) use ($formattedDate) {
            return isset($event['date']) && $event['date'] === $formattedDate;
        });
    }
    
    // Helper function to check if a date is disabled
    function isDisabled($date, $minDate, $maxDate, $disabledDates, $dateFormat) {
        $formattedDate = $date->format($dateFormat);
        
        if (in_array($formattedDate, $disabledDates)) {
            return true;
        }
        
        if ($minDate && $date->lt(Carbon::parse($minDate)->startOfDay())) {
            return true;
        }
        
        if ($maxDate && $date->gt(Carbon::parse($maxDate)->endOfDay())) {
            return true;
        }
        
        return false;
    }
    
    // Helper function to check if a date is selected
    function isSelected($date, $selectedDate, $dateFormat) {
        if (!$selectedDate) return false;
        
        $formattedDate = $date->format($dateFormat);
        $formattedSelectedDate = Carbon::parse($selectedDate)->format($dateFormat);
        
        return $formattedDate === $formattedSelectedDate;
    }
@endphp

<div 
    {{ $attributes->merge(['class' => 'calendar bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden']) }}
    x-data="{
        month: {{ $month }},
        year: {{ $year }},
        selectedDate: '{{ $selectedDate }}',
        
        init() {
            // Watch for changes to the selectedDate property
            this.$watch('selectedDate', (value) => {
                if (value) {
                    this.$dispatch('date-selected', {
                        date: value,
                        formattedDate: value
                    });
                }
            });
        },
        
        prevMonth() {
            if (this.month === 1) {
                this.month = 12;
                this.year--;
            } else {
                this.month--;
            }
            this.$dispatch('month-changed', { month: this.month, year: this.year });
        },
        
        nextMonth() {
            if (this.month === 12) {
                this.month = 1;
                this.year++;
            } else {
                this.month++;
            }
            this.$dispatch('month-changed', { month: this.month, year: this.year });
        },
        
        selectDate(date) {
            @if($allowSelection)
                this.selectedDate = date;
            @endif
        },
        
        isToday(year, month, day) {
            const today = new Date();
            return year === today.getFullYear() && month === today.getMonth() + 1 && day === today.getDate();
        }
    }"
>
    <!-- Calendar header -->
    <div class="p-4 flex items-center justify-between bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div>
            <span 
                class="text-lg font-semibold text-gray-900 dark:text-white"
                x-text="`${new Date(year, month-1).toLocaleString('default', { month: 'long' })} ${year}`"
            >{{ $monthName }} {{ $year }}</span>
        </div>
        
        <div class="flex space-x-1">
            <button 
                type="button" 
                @click="prevMonth()"
                class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none"
                aria-label="Previous month"
            >
                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            
            <button 
                type="button"
                @click="nextMonth()"
                class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none"
                aria-label="Next month"
            >
                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>
    
    <!-- Calendar grid -->
    <div class="calendar-grid bg-white dark:bg-gray-800">
        <!-- Day headers -->
        <div class="grid grid-cols-7 border-b border-gray-200 dark:border-gray-700">
            @foreach($dayLabels as $dayLabel)
                <div class="py-2 text-center text-sm font-medium text-gray-500 dark:text-gray-400">
                    {{ $dayLabel }}
                </div>
            @endforeach
        </div>
        
        <!-- Calendar days -->
        <div 
            class="grid grid-cols-7 h-full" 
            x-data="{
                getDaysInMonth(year, month) {
                    return new Date(year, month, 0).getDate();
                },
                getFirstDayOfMonth(year, month) {
                    const firstDay = new Date(year, month - 1, 1).getDay();
                    return (firstDay - {{ $startOfWeek }} + 7) % 7;
                }
            }"
        >
            <template x-for="week in {{ $weeksNeeded }}">
                <template x-for="dayOffset in 7">
                    @php
                        // Initialize variables to use in the template
                        $firstDayCalc = "getFirstDayOfMonth(year, month)";
                        $daysInMonthCalc = "getDaysInMonth(year, month)";
                        $prevDaysInMonthCalc = "getDaysInMonth(year, month-1)";
                        $dayCalc = "((week - 1) * 7 + dayOffset - $firstDayCalc)";
                        $currentDayCalc = "$dayCalc + 1";
                        
                        $isCurrentMonthDay = "$dayCalc >= 0 && $dayCalc < $daysInMonthCalc";
                        $isPrevMonthDay = "$dayCalc < 0";
                        $isNextMonthDay = "$dayCalc >= $daysInMonthCalc";
                        
                        $prevMonthDayCalc = "$prevDaysInMonthCalc - Math.abs($dayCalc) + 1";
                        $nextMonthDayCalc = "$dayCalc - $daysInMonthCalc + 1";
                        
                        $displayedDayCalc = "$isCurrentMonthDay ? $currentDayCalc : ($isPrevMonthDay ? $prevMonthDayCalc : $nextMonthDayCalc)";
                        
                        $isTodayCalc = "isToday(
                            $isCurrentMonthDay ? year : ($isPrevMonthDay ? (month === 1 ? year - 1 : year) : (month === 12 ? year + 1 : year)),
                            $isCurrentMonthDay ? month : ($isPrevMonthDay ? (month === 1 ? 12 : month - 1) : (month === 12 ? 1 : month + 1)),
                            $displayedDayCalc
                        )";
                        
                        $getMonthForDate = "$isCurrentMonthDay ? month : ($isPrevMonthDay ? (month === 1 ? 12 : month - 1) : (month === 12 ? 1 : month + 1))";
                        $getYearForDate = "$isCurrentMonthDay ? year : ($isPrevMonthDay ? (month === 1 ? year - 1 : year) : (month === 12 ? year + 1 : year))";
                        
                        $formattedDateCalc = "
                            `\${$getYearForDate}-\${String($getMonthForDate).padStart(2, '0')}-\${String($displayedDayCalc).padStart(2, '0')}`
                        ";
                    @endphp
                    
                    <div 
                        :class="{
                            'bg-white dark:bg-gray-800 border-b border-r border-gray-200 dark:border-gray-700 min-h-[80px]': true,
                            'opacity-50': {{ $isCurrentMonthDay }} ? false : {{ $showAdjacentMonths ? 'false' : 'true' }},
                            'cursor-pointer': {{ $allowSelection ? 'true' : 'false' }},
                            'bg-gray-50 dark:bg-gray-750': {{ $isTodayCalc }} && {{ $highlightToday ? 'true' : 'false' }},
                            'bg-green-50 dark:bg-green-900 font-semibold': selectedDate === {{ $formattedDateCalc }}
                        }"
                        :style="{{ $isCurrentMonthDay }} || {{ $showAdjacentMonths }} ? {} : { visibility: 'hidden' }"
                        @if($allowSelection)
                            @click="selectDate({{ $formattedDateCalc }})"
                        @endif
                    >
                        <div class="p-1 h-full">
                            <!-- Day number -->
                            <div 
                                :class="{
                                    'flex justify-between items-start': true,
                                    'text-gray-900 dark:text-white': {{ $isCurrentMonthDay }},
                                    'text-gray-400 dark:text-gray-500': !{{ $isCurrentMonthDay }},
                                    'font-bold': {{ $isTodayCalc }} && {{ $highlightToday ? 'true' : 'false' }}
                                }"
                            >
                                <span x-text="{{ $displayedDayCalc }}" class="text-sm p-1"></span>
                                
                                <!-- Today indicator -->
                                <span 
                                    x-show="{{ $isTodayCalc }} && {{ $highlightToday ? 'true' : 'false' }}" 
                                    class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-800 px-2 py-0.5 text-xs font-medium text-green-800 dark:text-green-100 ml-auto"
                                >
                                    Today
                                </span>
                            </div>
                            
                            <!-- Event indicators - We'll generate this dynamically via JS -->
                            <div 
                                class="mt-1 space-y-1 text-xs"
                                x-data="{
                                    getEvents(date) {
                                        const events = {{ json_encode($events) }};
                                        return events.filter(event => event.date === date);
                                    }
                                }"
                            >
                                <template x-for="event in getEvents({{ $formattedDateCalc }})" :key="event.id">
                                    <div 
                                        class="px-2 py-0.5 truncate rounded text-white"
                                        :class="event.color ? '{{ implode(' ', array_map(function($color) { return 'bg-' . $color; }, array_keys($eventColors))) }}' : '{{ $eventColors['default'] }}'"
                                        :style="event.color && !{{ json_encode(array_flip(array_keys($eventColors))) }}[event.color] ? {backgroundColor: event.color} : {}"
                                        x-text="event.title"
                                    ></div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
        </div>
    </div>
</div> 