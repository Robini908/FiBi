<!-- Google-style Tabs Navigation -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <!-- We'll only keep this component for mobile view where the sidebar isn't visible -->
    <div class="lg:hidden">
        <div class="border-b border-gray-200">
            <div class="flex px-4 py-3 space-x-6 overflow-x-auto hide-scrollbar">
                <button 
                    @click="activeTab = 'profile'" 
                    :class="{ 
                        'text-blue-600 border-blue-600 font-medium': activeTab === 'profile', 
                        'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300': activeTab !== 'profile' 
                    }"
                    class="whitespace-nowrap pb-3 px-1 border-b-2 font-medium text-sm"
                >
                    Profile Information
                </button>
                
                <button 
                    @click="activeTab = 'password'" 
                    :class="{ 
                        'text-blue-600 border-blue-600 font-medium': activeTab === 'password', 
                        'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300': activeTab !== 'password' 
                    }"
                    class="whitespace-nowrap pb-3 px-1 border-b-2 font-medium text-sm"
                >
                    Password & Security
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style> 