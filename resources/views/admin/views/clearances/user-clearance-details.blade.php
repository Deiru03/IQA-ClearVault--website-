<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Clearance Details') }}
        </h2>
    </x-slot>

    <style>
        #notification {
            z-index: 9999;
            bring-to-front: 9999;
        }
    </style>

    <!-- Notification component -->
    <div id="notification" class="fixed top-5 right-5 bg-green-500 text-white p-4 rounded-lg shadow-lg transform transition-all duration-500 -translate-y-full opacity-0 z-50" style="z-index: 9999;">
        <p id="notificationMessage" class="font-semibold"></p>
    </div>

    {{-- <!-- Loading overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="animate-spin rounded-full h-32 w-32 border-t-4 border-b-4 border-white"></div>
    </div> --}}

    @php
        $onCheckCount = 0;
        $resubmitsCount = 0;
        $compliedCount = 0;
        $returnedCompliedCount = 0;
        $notApplicableCount = 0;
        $totalRequirements = count($userClearance->sharedClearance->clearance->requirements);

        foreach ($userClearance->sharedClearance->clearance->requirements as $requirement) {
            $feedback = $requirement->feedback->where('user_id', $userClearance->user->id)->first();
            $uploadedFile = $userClearance->uploadedClearances
                ->where('user_id', $userClearance->user->id)
                ->where('requirement_id', $requirement->id)
                ->sortByDesc('created_at')
                ->first();

            // Check for Not Applicable status first
            if ($feedback && !$feedback->is_archived && $feedback->signature_status == 'Not Applicable') {
                $notApplicableCount++;
            }
            // Then check uploaded files
            else if ($uploadedFile && !$uploadedFile->is_archived) {
                if ($feedback && !$feedback->is_archived) {
                    if ($feedback->signature_status == 'Resubmit') {
                        $resubmitsCount++;
                    } elseif ($feedback->signature_status == 'Checking') {
                        $onCheckCount++;
                    } elseif ($feedback->signature_status == 'Complied') {
                        $compliedCount++;
                    }
                }
            }
            if ($isComplied = $uploadedFile && $feedback && $feedback->signature_status == 'Resubmit' && $uploadedFile->created_at > $feedback->updated_at) {
                $returnedCompliedCount++;
            }
        }
    @endphp

    <!-- Ribbon Navigation -->
    <div class="mb-4">
        <h3 class="text-lg font-medium text-gray-700 mb-3 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Available Clearance Documents
        </h3>
        
        <div class="p-4 bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($userClearances as $clearance)
                    <a href="{{ route('admin.clearances.show', ['userId' => $user->id, 'clearanceId' => $clearance->id]) }}"
                       class="relative group">
                        <div class="{{ $clearance->id == $userClearance->id 
                            ? 'bg-blue-50 border-blue-200' 
                            : 'bg-white hover:bg-gray-50 border-gray-200' }} 
                            p-4 rounded-lg border transition-all duration-200 hover:shadow-sm">
                            
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="{{ $clearance->id == $userClearance->id ? 'bg-blue-200' : 'bg-gray-100' }} p-2 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                
                                <span class="font-medium text-sm text-gray-700 truncate">
                                    {{ $clearance->sharedClearance->clearance->document_name }}
                                </span>
                            </div>

                            @php
                                $totalRequirements = $clearance->sharedClearance->clearance->requirements->count();
                                $completedRequirements = $clearance->sharedClearance->clearance->requirements->filter(function($req) use ($user) {
                                    return $req->feedback->where('user_id', $user->id)->whereIn('signature_status', ['Complied', 'Not Applicable'])->count() > 0;
                                })->count();
                                $progress = $totalRequirements > 0 ? ($completedRequirements / $totalRequirements) * 100 : 0;
                            @endphp
                            
                            <div class="space-y-1.5">
                                <div class="flex justify-between items-center text-xs mb-1">
                                    <span class="text-gray-500">Progress</span>
                                    <span class="font-medium text-gray-700">{{ number_format($progress, 0) }}%</span>
                                </div>
                                <div class="w-full h-2 bg-gray-100 border border-gray-300 rounded overflow-hidden">
                                    <div class="h-full {{ $progress == 100 ? 'bg-green-500' : 'bg-gray-700' }} rounded transition-all duration-200"
                                         style="width: {{ $progress }}%"></div>
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $completedRequirements }}/{{ $totalRequirements }} requirements
                                </div>
                            </div>

                            <!-- Improved Tooltip -->
                            <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs py-1.5 px-3 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                                Click to View Detailed Information
                                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 border-4 border-transparent border-t-gray-800"></div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Display User ID and Name --}}
    <div class="mb-8 p-6 flex items-center space-x-8 border border-gray-300">
        <div class="flex flex-col md:flex-row items-start md:items-center space-y-6 md:space-y-0 md:space-x-8 p-6 w-full">
            <div class="flex-shrink-0">
                @if ($userClearance->user->profile_picture)
                    @if (str_contains($userClearance->user->profile_picture, 'http'))
                        <img src="{{ $userClearance->user->profile_picture }}" alt="{{ $userClearance->user->name }}" class="w-32 h-32 object-cover rounded-full border-4 border-indigo-200">
                    @else
                        <img src="{{ url('/profile_pictures/' . basename($userClearance->user->profile_picture)) }}" alt="{{ $userClearance->user->name }}" class="w-32 h-32 object-cover rounded-full border-4 border-indigo-200">
                    @endif
                @else
                    <div class="w-32 h-32 flex items-center justify-center rounded-full text-white font-bold text-4xl border-4 border-indigo-200" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        {{ strtoupper(substr($userClearance->user->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="flex-grow w-1/2">
                <h3 class="text-3xl font-extrabold text-gray-800 mb-2">{{ $userClearance->user->name }}</h3>
                <p class="text-lg text-indigo-600 mb-4">{{ $userClearance->user->email }}</p>
                <div class="grid grid-cols-2 gap-y-3 gap-x-8 text-gray-700">
                    <div>
                        <span class="font-semibold text-gray-900">ID:</span>
                        <span>{{ $userClearance->user->id }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-900">Position:</span>
                        <span>{{ $userClearance->user->position }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-900">Unit:</span>
                        <span>{{ $userClearance->user->units }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-900">Campus:</span>
                        <span>{{ $userClearance->user->campus->name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-900">College:</span>
                        <span>{{ $college->name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-900">Program:</span>
                        <span>{{ $program->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-l-2 border-gray-400 h-52 mx-6"></div>

        <div class="w-3/4">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-2xl font-semibold text-gray-800">Clearance Details</h4>
                <div class="h-1 flex-grow mx-4 bg-gradient-to-r from-indigo-500 to-purple-500 rounded"></div>
            </div>
            <div class="space-y-4">
                <div>
                    <span class="text-lg font-medium text-gray-700">Title:</span>
                    <p class="text-xl text-indigo-600">{{ $userClearance->sharedClearance->clearance->document_name }}</p>
                </div>
                <div>
                    <span class="text-lg font-medium text-gray-700">Description:</span>
                    <p class="text-gray-600 mt-1">{{ $userClearance->sharedClearance->clearance->description ?? 'No description available' }}</p>
                </div>
            </div>
        </div>
    </div>

    @php
        $totalRequirements = count($userClearance->sharedClearance->clearance->requirements);
        $completedRequirements = 0;
        $pendingRequirements = 0;
        $notApplicable = 0;

        foreach($userClearance->sharedClearance->clearance->requirements as $req) {
            $feedback = $req->feedback->where('user_id', $userClearance->user->id)->first();
            if($feedback && $feedback->signature_status === 'Complied') {
                $completedRequirements++;
            } elseif($feedback && $feedback->signature_status === 'Not Applicable') {
                $notApplicable++;
            } else {
                $pendingRequirements++;
            }
        }

        $progressPercentage = ($completedRequirements + $notApplicable) / $totalRequirements * 100;
    @endphp

    <!-- Reset Button -->
    @if (Auth::user()->user_type === 'Admin')
        <div class="flex items-center space-x-2">
            <button id="resetButton" class="
                {{ $progressPercentage == 100 ? 'bg-green-500 hover:bg-green-700 border-2 border-green-600 hover:border-green-800' : 'bg-red-500 hover:bg-red-700 border-2 border-red-600 hover:border-red-800' }}
                text-white font-bold py-3 px-6 rounded-lg shadow-lg transform transition duration-200 ease-in-out hover:scale-105 flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                </svg>
                <span>Update User Clearances</span>
            </button>
            <span class="text-sm text-gray-600 mt-2">
                This will reset the user's clearances to the selected academic year and semester.<br>
                If <span class="text-green-500"><strong>green</strong></span>, the user's clearances are complete.<br>
                If <span class="text-red-500"><strong>red</strong></span>, the user's clearances are incomplete/in-progress.
            </span>
        </div>
    @endif

   <!-- Confirmation Modal of Reset , Update , Archive -->
    <div id="confirmationModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75 hidden z-50">
        <div class="bg-white p-8 rounded-xl shadow-2xl max-w-2xl w-full">
            <!-- Title and Description -->
            <div class="mb-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Reset Clearance</h3>
                <p id="confirmationMessage" class="text-gray-600"></p>
            </div>

            <!-- Clearance Selection with improved design -->
            <div class="mb-6">
                <label for="clearanceSelect" class="block text-lg font-semibold text-gray-700 mb-2">
                    Select Clearance Document
                </label>
                <div class="relative">
                    <select id="clearanceSelect" class="appearance-none w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-200 text-gray-700 bg-white">
                        @foreach($userClearances as $clearance)
                            <option value="{{ $clearance->id }}" {{ $clearance->id == $userClearance->id ? 'selected' : '' }} class="py-2">
                                {{ $clearance->sharedClearance->clearance->document_name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-500">Choose the clearance document you want to reset</p>
            </div>

            <!-- Academic Year Selection with matching style -->
            @php
                $currentYear = date('Y');
                $currentMonth = date('m');
                $currentAcademicYear = $currentMonth >= 8 ? "$currentYear - " . ($currentYear + 1) : ($currentYear - 1) . " - $currentYear";

                $academicYears = [];
                for ($i = -2; $i < 3; $i++) {
                    $startYear = $currentYear + $i;
                    $endYear = $startYear + 1;
                    $academicYears[] = "$startYear - $endYear";
                }
            @endphp

            {{-- Academic Year Selection RESET OR UPDATE OR ARCHIVES --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label for="academicYear" class="block text-lg font-semibold text-gray-700 mb-2">
                        Academic Year
                    </label>
                    <div class="relative">
                        <select id="academicYear" class="appearance-none w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-200 text-gray-700 bg-white">
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" {{ $year === $currentAcademicYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="semester" class="block text-lg font-semibold text-gray-700 mb-2">
                        Semester
                    </label>
                    <div class="relative">
                        <select id="semester" class="appearance-none w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-200 text-gray-700 bg-white">
                            <option value="1">1st Semester</option>
                            <option value="2">2nd Semester</option>
                            <option value="3">3rd Semester</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4">
                <button onclick="closeConfirmationModal()" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-all duration-200 flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancel
                </button>
                <button id="confirmResetButton" class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition-all duration-200 flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Confirm Reset
                </button>
            </div>
        </div>
    </div>
    
    <!-- Clearance Information Section -->
    <div class="mt-6 mb-8 bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
        <div class="border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white">
            <div class="px-6 py-3 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Clearance Information
                </h3>
                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    <a href="{{ route('admin.reports.export', ['userId' => $userClearance->user->id]) }}" 
                       target="_blank"
                       class="flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-lg hover:from-indigo-600 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <span class="text-sm font-medium">Export</span>
                    </a>
                    
                    <a href="{{ route('admin.clearance.print', ['clearanceId' => $userClearance->sharedClearance->clearance_id, 'userId' => $userClearance->user->id]) }}" 
                       target="_blank"
                       class="flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-300 transform hover:scale-105 shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span class="text-sm font-medium">Print</span>
                    </a>

                    <div class="relative group">
                        <button disabled class="flex items-center px-4 py-2 bg-gradient-to-r from-blue-400 to-blue-500 text-white rounded-lg opacity-60 cursor-not-allowed">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span class="text-sm font-medium">Email</span>
                        </button>
                        <div class="absolute -top-12 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white px-3 py-1 rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                            Not Available!
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Left Column: Academic & Status Info -->
                <div class="space-y-3">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="flex items-center mb-2">
                            <svg class="w-4 h-4 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <h4 class="text-sm font-semibold text-gray-700">Academic Period</h4>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div class="bg-white p-2 rounded">
                                <span class="text-gray-500">Year:</span>
                                <span class="font-medium text-gray-800 ml-1">{{ $userClearance->academic_year }}</span>
                            </div>
                            <div class="bg-white p-2 rounded">
                                <span class="text-gray-500">Term:</span>
                                <span class="font-medium text-gray-800 ml-1">{{ $userClearance->semester }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="flex items-center mb-2">
                            <svg class="w-4 h-4 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h4 class="text-sm font-semibold text-gray-700">Latest Activity</h4>
                        </div>
                        @php
                            $latestUpload = $userClearance->uploadedClearances
                                ->where('user_id', $userClearance->user->id)
                                ->sortByDesc('created_at')
                                ->first();
                            
                            $latestFeedback = collect($userClearance->sharedClearance->clearance->requirements)
                                ->flatMap->feedback
                                ->where('user_id', $userClearance->user->id)
                                ->sortByDesc('created_at')
                                ->first();
                        @endphp
                        <div class="space-y-2 text-sm">
                            <div class="bg-white p-2 rounded">
                                <span class="text-gray-500">Latest Upload:</span>
                                <span class="font-medium text-gray-800 ml-1">
                                    {{ $latestUpload ? $latestUpload->created_at->format('M d, Y h:i A') : 'No uploads yet' }}
                                </span>
                            </div>
                            <div class="bg-white p-2 rounded">
                                <span class="text-gray-500">Latest Activity:</span>
                                <span class="font-medium text-gray-800 ml-1">
                                    {{ $latestFeedback ? $latestFeedback->updated_at->format('M d, Y h:i A') : 'No feedback yet' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Middle Column: Requirements Summary -->
          
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center mb-3">
                        <svg class="w-4 h-4 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h4 class="text-sm font-semibold text-gray-700">Requirements Status</h4>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white p-3 rounded-lg border border-gray-100">
                            <div class="text-2xl font-bold text-indigo-600">{{ $totalRequirements }}</div>
                            <div class="text-sm text-gray-500">Total Requirements</div>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-gray-100">
                            <div class="text-2xl font-bold text-green-600">{{ $completedRequirements }}</div>
                            <div class="text-sm text-gray-500">Completed</div>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-gray-100">
                            <div class="text-2xl font-bold text-yellow-600">{{ $pendingRequirements }}</div>
                            <div class="text-sm text-gray-500">Checking</div>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-gray-100">
                            <div class="text-2xl font-bold text-purple-600">{{ $notApplicable }}</div>
                            <div class="text-sm text-gray-500">Not Applicable</div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Progress -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center mb-3">
                        <svg class="w-4 h-4 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <h4 class="text-sm font-semibold text-gray-700">Overall Progress</h4>
                    </div>
                    @php
                        $progressPercentage = ($completedRequirements + $notApplicable) / $totalRequirements * 100;
                    @endphp
                    <div class="bg-white p-4 rounded-lg border border-gray-100">
                        <div class="flex justify-between items-center mb-2">
                            <div class="text-3xl font-bold {{ $progressPercentage == 100 ? 'text-green-600' : 'text-indigo-600' }}">
                                {{ number_format($progressPercentage, 1) }}%
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full {{ $progressPercentage == 100 ? 'bg-green-100 text-green-800' : 'bg-indigo-100 text-indigo-800' }}">
                                {{ $progressPercentage == 100 ? 'Completed' : 'In Progress' }}
                            </span>
                        </div>
                        <div class="h-3 relative w-full bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full transition-all duration-500 {{ $progressPercentage == 100 ? 'bg-green-500' : 'bg-indigo-500' }}"
                                 style="width: {{ $progressPercentage }}%"></div>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">
                            {{ $progressPercentage == 100 
                                ? '✨ All requirements have been completed!' 
                                : '🔍 Keep validating documents to make progress!' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('resetButton').addEventListener('click', function() {
            const clearanceStatus = '{{ $userClearance->user->clearances_status }}';
            const message = clearanceStatus === 'Complete'
                ? 'The clearance is complete. Are you sure you want to reset?'
                : 'The clearance is not complete. Are you sure you want to reset?';

            document.getElementById('confirmationMessage').textContent = message;
            document.getElementById('confirmationModal').classList.remove('hidden');
        });

        document.getElementById('confirmResetButton').addEventListener('click', function() {
            const academicYear = document.getElementById('academicYear').value;
            const semester = document.getElementById('semester').value;
            const clearanceId = document.getElementById('clearanceSelect').value;

            fetch('{{ route('admin.clearance.resetSpecific', ['userId' => $userClearance->user->id, 'clearanceId' => $userClearance->sharedClearance->clearance->id]) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    academicYear, 
                    semester,
                    clearanceId: parseInt(clearanceId)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('User clearance reset successfully.');
                    location.reload();
                } else {
                    showNotification('Failed to reset user clearance.', false);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred.', false);
            });

            closeConfirmationModal();
        });

        function closeConfirmationModal() {
            document.getElementById('confirmationModal').classList.add('hidden');
        }
    </script>

    <h3 class="text-3xl font-bold mb-6 text-gray-800 hidden">{{ $userClearance->sharedClearance->clearance->document_name }}</h3>
    <div class="overflow-x-auto shadow-md rounded-lg">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-indigo-600 text-white">
                <tr>
                    <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider w-[50px]">No.</th>
                    <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">Requirement</th>
                    <th class="py-3 px-4 text-center text-xs font-medium uppercase tracking-wider">Uploaded Files</th>
                    <th class="py-3 px-4 text-center text-xs font-medium uppercase tracking-wider">Date Upload</th>
                    <th class="py-3 px-4 text-center text-xs font-medium uppercase tracking-wider">Document<br>Status</th>
                    @if(Auth::user()->user_type === 'Admin' || Auth::user()->user_type === 'Program-Head' || Auth::user()->user_type === 'Dean')
                        <th class="py-3 px-4 text-center text-xs font-medium uppercase tracking-wider">Feedback</th>
                        <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider text-center">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($userClearance->sharedClearance->clearance->requirements as $requirement)
                    @php
                        // Get the feedback for the current requirement and user
                        $feedback = $requirement->feedback->where('user_id', $userClearance->user->id)->first();

                        // Get the most recent uploaded file for the current requirement and user
                        $uploadedFile = $userClearance->uploadedClearances
                            ->where('user_id', $userClearance->user->id)
                            ->where('requirement_id', $requirement->id)
                            ->sortByDesc('created_at')
                            ->first();

                        // Determine if the requirement is complied based on feedback and upload dates
                        $isComplied = $uploadedFile && $feedback && $feedback->signature_status == 'Resubmit' && $uploadedFile->created_at > $feedback->updated_at;
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors duration-150
                        @if($isComplied)
                            bg-blue-50
                        @elseif($uploadedFile && !$uploadedFile->is_archived)
                            @if($feedback && !$feedback->is_archived)
                                bg-{{ $feedback->signature_status == 'Complied' ? 'green' : ($feedback->signature_status == 'Resubmit' ? 'red' : 'yellow') }}-50
                            @else
                                bg-yellow-50
                            @endif
                        @else
                            bg-gray-50
                        @endif
                    ">
                        <td class="px-4 py-4 text-sm text-gray-900 whitespace-pre-line w-[50px]">{{ $loop->iteration }}</td>
                        <td class="px-4 py-4 text-sm text-gray-900 whitespace-pre-line">{{ $requirement->requirement }}</td>
                        <td class="px-4 py-4">
                            @foreach($userClearance->uploadedClearances->where('user_id', $userClearance->user->id)->where('requirement_id', $requirement->id)->where('is_archived', false)->sortByDesc('created_at') as $uploaded)
                                <div class="flex items-center justify-start space-x-3">
                                    <span class="w-3 h-3 bg-gradient-to-r from-green-400 to-blue-500 rounded-full shadow-md"></span>
                                    <!-- Update the link to use the viewFile function for previewing -->
                                    <a href="#" data-path="{{ $uploaded->file_path }}" class="file-link text-indigo-600 hover:text-indigo-800 hover:underline text-sm font-medium transition duration-300">
                                        {{ basename($uploaded->file_path) }}
                                    </a>
                                </div>
                            @endforeach
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900 whitespace-pre-line">{{ ($uploadedFile && !$uploadedFile->is_archived) ? $uploadedFile->updated_at->format('M d, Y h:i A') : 'N/A' }}</td>
                        <td class="px-4 py-4 text-center">
                            @if($isComplied)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-500">
                                    Returned Complied
                                </span>
                            @elseif($uploadedFile && !$uploadedFile->is_archived)
                                @if($feedback && !$feedback->is_archived)
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $feedback->signature_status == 'Complied' ? 'green' : ($feedback->signature_status == 'Resubmit' ? 'red' : 'yellow') }}-100 text-{{ $feedback->signature_status == 'Complied' ? 'green' : ($feedback->signature_status == 'Resubmit' ? 'red' : 'yellow') }}-800 border border-{{ $feedback->signature_status == 'Complied' ? 'green' : ($feedback->signature_status == 'Resubmit' ? 'red' : 'yellow') }}-500">
                                        {{ $feedback->signature_status }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-500">Checking</span>
                                @endif
                            @else
                                @if($feedback && $feedback->signature_status == 'Not Applicable')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 border border-purple-500">Not Applicable</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-500">No Attachment</span>
                                @endif
                            @endif
                        </td>
                        @if(Auth::user()->user_type === 'Admin' || Auth::user()->user_type === 'Program-Head' || Auth::user()->user_type === 'Dean')
                            <td class="px-4 py-4">
                                @if($feedback && !$feedback->is_archived && !empty($feedback->message) && $uploadedFile && !$uploadedFile->is_archived)
                                    <p class="text-yellow-800"><strong> {{ $feedback->message }}</strong></p>
                                @else
                                    <p class="text-gray-400 italic">No comments yet.</p>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <button onclick="openFeedbackModal({{ $requirement->id }})" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-full transition-colors duration-200 text-sm font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-opacity-50">
                                    Actions Document
                                </button>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>



    <!-- Feedback Modal -->
    <div id="feedbackModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-70 hidden z-50 transition-opacity duration-300">
        <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-md w-full relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-indigo-500 to-blue-500"></div>
            <h3 class="text-3xl font-bold mb-6 text-gray-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Provide Feedback
            </h3>
            <form id="feedbackForm">
                @csrf
                <input type="hidden" name="requirement_id" id="requirementId">
                <input type="hidden" name="user_id" value="{{ $userClearance->user->id }}">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Requirement Details</label>
                    <p id="requirementName" class="text-sm text-gray-600 bg-gray-100 p-2 rounded whitespace-pre-line" style="max-height: 250px; overflow-y: auto;"></p>
                </div>
                <div class="mb-6">
                    <label for="signatureStatus" class="block text-sm font-medium text-gray-700 mb-2">Document Status</label>
                    <select name="signature_status" id="signatureStatus" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        @if(Auth::user()->user_type !== 'Admin') disabled @endif>
                        <option value="Checking">Checking</option>
                        <option value="Complied">Complied</option>
                        <option value="Resubmit">Resubmit</option>
                        <option value="Not Applicable">Not Applicable</option>
                    </select>
                    @if(Auth::user()->user_type !== 'Admin')
                        <p class="mt-2 text-sm text-gray-500 italic">Only <strong class="text-indigo-500">Admin</strong> can validate documents. You may still leave feedback below.</p>
                    @endif
                </div>
                <div class="mb-6">
                    <label for="feedbackMessage" class="block text-sm font-medium text-gray-700 mb-2">Feedback (Optional)</label>
                    <textarea name="message" id="feedbackMessage" rows="4" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter feedback if needed"></textarea>
                </div>
                <div class="mt-8 flex justify-end space-x-4">
                    <button type="button" onclick="closeFeedbackModal()" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-md flex items-center transition duration-300 ease-in-out hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-md flex items-center transition duration-300 ease-in-out hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-70 hidden z-50 transition-opacity duration-300">
        <div class="bg-white p-3 rounded-2xl shadow-2xl max-w-5xl w-11/12 h-5/6 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
            <div class="flex justify-between items-center mb-4">
                <h3 id="previewFileName" class="text-2xl font-bold text-gray-800"></h3>
                <button onclick="closePreviewModal()" class="text-gray-500 hover:text-gray-700 transition duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-auto h-full">
                <iframe id="previewFrame" class="w-full h-full border-0"></iframe>
            </div>
        </div>
    </div>


    <script>
        function openFeedbackModal(requirementId) {
            const feedback = @json($userClearance->sharedClearance->clearance->requirements->pluck('feedback', 'id'));
            const requirements = @json($userClearance->sharedClearance->clearance->requirements->pluck('requirement', 'id'));
            const currentFeedback = feedback[requirementId]?.find(f => f.user_id === {{ $userClearance->user->id }});

            document.getElementById('requirementId').value = requirementId;
            document.getElementById('requirementName').textContent = `Requirement ID: ${requirementId}\n${requirements[requirementId]}`;
            document.getElementById('signatureStatus').value = currentFeedback?.signature_status || 'Checking';
            // document.getElementById('feedbackMessage').value = currentFeedback?.message || '';

            // Clear the feedback when the modal is opened
            document.getElementById('feedbackMessage').value = '';

            document.getElementById('feedbackModal').classList.remove('hidden');
        }

        function closeFeedbackModal() {
            document.getElementById('feedbackModal').classList.add('hidden');
        }

        function showNotification(message, isSuccess = true) {
            const notification = document.getElementById('notification');
            const notificationMessage = document.getElementById('notificationMessage');
            notificationMessage.textContent = message;

            if (isSuccess) {
            notification.classList.remove('bg-red-500');
            notification.classList.add('bg-green-500');
            } else {
            notification.classList.remove('bg-green-500');
            notification.classList.add('bg-red-500');
            }

            notification.classList.remove('-translate-y-full', 'opacity-0');
            setTimeout(() => {
            notification.classList.add('-translate-y-full', 'opacity-0');
            }, 5000);
        }

        document.getElementById('feedbackForm').addEventListener('submit', function(event) {
            event.preventDefault();

            // Enable the dropdown before submission
            const signatureStatus = document.getElementById('signatureStatus');
                if (signatureStatus.hasAttribute('disabled')) {
                    signatureStatus.removeAttribute('disabled');
                }

            const formData = new FormData(this);

            fetch('{{ route('admin.clearance.feedback.store') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success === true) {
                    closeFeedbackModal();
                    showNotification('Your action was successfully saved', true);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    closeFeedbackModal();
                    showNotification('There was an error saving your action', false)
                }
            })
            .catch(error => {
            console.error('Error:', error);
            showNotification(error.message || 'An error occurred in saving the feedback.', false);
            });
        });

        function viewFile(path, filename) {
            const previewModal = document.getElementById('previewModal');
            const previewFrame = document.getElementById('previewFrame');
            const previewFileName = document.getElementById('previewFileName');

          // Convert the storage URL to our direct file viewing route
            const fileUrl = `/file-view/${path}`;
            previewFrame.src = fileUrl;
            previewFileName.textContent = filename;

            previewModal.classList.remove('hidden');
            previewModal.classList.add('flex');
        }

        function closePreviewModal() {
            const previewModal = document.getElementById('previewModal');
            const previewFrame = document.getElementById('previewFrame');

            previewModal.classList.add('hidden');
            previewModal.classList.remove('flex');
            previewFrame.src = '';
        }

        // Update the link to use viewFile function
        document.querySelectorAll('.file-link').forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const path = this.getAttribute('data-path');
                const filename = this.textContent;
                viewFile(path, filename);
            });
        });
    </script>
</x-admin-layout>
