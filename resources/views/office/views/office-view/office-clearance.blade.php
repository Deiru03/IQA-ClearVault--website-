<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Clearances') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-indigo-200">
                <div class="p-8 text-gray-900">
                    <h3 class="text-2xl font-bold mb-6 text-indigo-700 border-b-2 border-indigo-200 pb-2">Clearances Management</h3>
                    @if($userClearance)
                        <div class="flex items-center mb-6 bg-green-100 p-4 rounded-lg shadow-md">
                            <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-lg font-semibold text-green-700">You have an active clearance.</p>
                                <p class="text-sm text-green-600">Here you can view and manage your clearances efficiently.</p>
                            </div>
                        </div>
                        <div class="bg-white p-2">
                            <span class="mx-2"></span>
                            <a href="{{ route('phd.programHeadDean.indexPhD') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                View Existing Checklists
                            </a>
                        </div>
                        {{-- TESTING --}}
                        {{-- @include('faculty.views.clearances.clearance-show', ['userClearance' => $userClearance, 'isInclude' => true]) --}}
                    @else
                        <div class="flex items-center mb-6 bg-blue-100 p-4 rounded-lg shadow-md">
                            <svg class="w-8 h-8 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-lg font-semibold text-blue-700">No active clearance found.</p>
                                <p class="text-sm text-blue-600">To get started, please obtain a copy of your clearance from the shared clearances list.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>            
        </div>
    </div>
    @if($userClearance)
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-indigo-200">
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-4 px-4" aria-label="Tabs">
                        <button onclick="switchTab('phd-tab')" 
                            class="tab-button px-6 py-4 text-sm font-medium border-b-2 transition-colors duration-200 ease-in-out border-transparent hover:border-indigo-300 text-gray-500 hover:text-indigo-600"
                            id="phd-btn">
                            @if (Auth::user()->user_type == 'Program-Head')
                                Program Head Clearance Requirements
                            @else 
                            Dean Clearance Requirements
                            @endif
                        </button>
                        <button onclick="switchTab('clearance-tab')" 
                                class="tab-button px-6 py-4 text-sm font-medium border-b-2 transition-colors duration-200 ease-in-out border-transparent hover:border-indigo-300 text-gray-500 hover:text-indigo-600"
                                id="clearance-btn">
                            Faculty Clearance Requirements
                        </button>
                    </nav>
                </div>

                @php
                    
                    // $userClearanceFaculty = $userClearance->user->facultyClearance;

                @endphp

                <div id="phd-tab" class="tab-content">
                    @include('admin.views.phdean-views.phd-clearance-show', ['userClearance' => $userClearance, 'isInclude' => true, 'bodyClass' => 'is-clearance-show'])
                </div>
                <div id="clearance-tab" class="tab-content">
                    <div class="p-6 flex items-center justify-center bg-yellow-50 border-2 border-yellow-200 rounded-lg m-4">
                        <svg class="w-6 h-6 text-yellow-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <p class="text-yellow-700">
                            <span class="font-semibold">Notice:</span>
                            This feature is currently in testing phase and under development. Please disregard this section for now as it will be implemented in future updates.
                        </p>
                    </div>
                    @include('faculty.views.clearances.clearance-show', ['userClearance' => $userClearanceFaculty, 'isInclude' => true, 'bodyClass' => 'is-clearance-show'])
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initial setup
                switchTab('phd-tab');

                function switchTab(tabId) {
                    // Hide all tab contents
                    document.querySelectorAll('.tab-content').forEach(tab => {
                        tab.classList.add('hidden');
                    });

                    // Remove active state from all buttons
                    document.querySelectorAll('.tab-button').forEach(button => {
                        button.classList.remove('border-indigo-500', 'text-indigo-600', 'bg-gradient-to-r', 
                            'from-indigo-100', 'to-blue-50', 'font-bold', 'scale-105', 'shadow-lg');
                        button.classList.add('border-transparent', 'text-gray-500');
                        button.style.transform = 'scale(1)';
                        button.style.boxShadow = 'none';
                    });

                    // Show selected tab with enhanced animation
                    const selectedTab = document.getElementById(tabId);
                    if (selectedTab) {
                        selectedTab.classList.remove('hidden');
                        selectedTab.classList.add('animate-fadeIn');
                        selectedTab.style.animation = 'slideIn 0.4s ease-out';
                    }

                    // Enhanced active button styling with gradient and glow effect
                    const activeButton = document.getElementById(tabId.replace('-tab', '-btn'));
                    if (activeButton) {
                        activeButton.classList.remove('border-transparent', 'text-gray-500');
                        activeButton.classList.add(
                            'border-indigo-500',
                            'text-indigo-600',
                            'bg-gradient-to-r',
                            'from-indigo-100',
                            'to-blue-50',
                            'font-bold',
                            'scale-105',
                            'shadow-lg'
                        );
                        // Add sophisticated transition and glow effects
                        activeButton.style.transform = 'scale(1.05)';
                        activeButton.style.boxShadow = '0 0 15px rgba(99, 102, 241, 0.4)';
                        activeButton.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                        activeButton.style.borderImage = 'linear-gradient(to right, #818cf8, #6366f1) 1';
                    }

                    // Add keyframe animation for slide effect
                    const style = document.createElement('style');
                    style.textContent = `
                        @keyframes slideIn {
                            from {
                                opacity: 0;
                                transform: translateY(10px);
                            }
                            to {
                                opacity: 1;
                                transform: translateY(0);
                            }
                        }
                    `;
                    document.head.appendChild(style);
                }

                // Add click event listeners to buttons
                document.querySelectorAll('.tab-button').forEach(button => {
                    button.addEventListener('click', function() {
                        const tabId = this.id.replace('-btn', '-tab');
                        switchTab(tabId);
                    });
                });
            });
        </script>
    @else
        <div class="mb-6">
            <div class="container mx-auto px-8 py-12">
                <div class="text-center">
                    <i class="fas fa-file-alt text-6xl text-indigo-500 mb-6"></i>
                    <h2 class="text-4xl font-bold mb-4 text-indigo-800">
                        No Clearances Available
                    </h2>
                    <p class="text-xl text-gray-700 mb-8">
                        It looks like you haven't obtained a copy of your clearance yet.
                    </p>
                    <a href="{{ route('phd.programHeadDean.indexPhD') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105">
                        Get Your Clearance
                    </a>
                </div>
            </div>
        </div>
    @endif
</x-admin-layout>