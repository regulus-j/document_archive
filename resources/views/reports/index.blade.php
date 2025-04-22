@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white p-4 md:p-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header Box -->
            <div class="bg-white rounded-xl shadow-xl mb-6 border border-blue-100 overflow-hidden">
                <div class="bg-white p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-md">
                            <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">{{ __('Report Management') }}</h1>
                            <p class="text-sm text-gray-500">Generate and view custom reports</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('reports.analytics') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-md text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ __('See Analytics') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-white border-l-4 border-emerald-500 text-emerald-700 p-4 mb-6 rounded-r-lg shadow-md"
                    role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Content -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-blue-100">
                <div class="lg:flex">
                    <!-- Left Panel -->
                    <div class="lg:w-1/3 bg-white p-6 border-b lg:border-b-0 lg:border-r border-blue-100">
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                {{ __('Create a Report') }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ __('Create a custom report based on collected data.') }}
                            </p>
                        </div>

                        <form action="{{ route('reports.generate') }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="space-y-2">
                                <label for="report_type"
                                    class="block text-sm font-medium text-gray-700">{{ __('Choose report type') }}</label>
                                <select name="report_type" id="report_type"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="audit_history">{{ __('Audit History') }}</option>
                                    <option value="company_performance">{{ __('Company Performance') }}</option>
                                </select>
                                <p class="text-xs text-gray-500">Select the type of report you want to generate</p>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">{{ __('Choose date range') }}</label>
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <input type="date" name="start_date"
                                            class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            placeholder="{{ __('From') }}">
                                    </div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <input type="date" name="end_date"
                                            class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            placeholder="{{ __('To') }}">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500">Select the date range for your report data</p>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">{{ __('Export Format') }}</label>
                                <div class="flex space-x-4">
                                    <div class="flex items-center">
                                        <input type="radio" id="export_none" name="export_format" value="none" checked
                                            class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <label for="export_none" class="ml-2 block text-sm text-gray-700">
                                            Preview Only
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" id="export_pdf" name="export_format" value="pdf"
                                            class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <label for="export_pdf" class="ml-2 block text-sm text-gray-700">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                                    <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.15-.459.222-.328.168-.61.335-.815.534-.107.104-.189.207-.242.32-.051.112-.063.234-.019.349.027.07.091.138.19.178a.663.663 0 0 0 .292-.004c.336-.137.642-.48.912-.816.228-.28.47-.63.719-.93.239-.3.442-.555.592-.75a9.053 9.053 0 0 0-.625-.216c-.189-.061-.384-.12-.587-.166zm3.26-3.216c.135.074.2.175.198.273 0 .086-.034.16-.088.226a.602.602 0 0 1-.156.147c-.117.096-.259.16-.39.16-.144 0-.302-.053-.44-.154-.169-.123-.26-.143-.3-.148a5.01 5.01 0 0 0-.368.069 28.64 28.64 0 0 0-.83.195c-.239.572-.445 1.112-.576 1.541-.016.066-.03.126-.044.183.116-.043.223-.087.318-.128.36-.157.699-.32 1.004-.481.304-.16.577-.31.802-.44.023-.031.052-.044.083-.51.177-.037.377-.05.575-.035zm-2.36 4.49v.002z"/>
                                                </svg>
                                                PDF
                                            </div>
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" id="export_excel" name="export_format" value="excel"
                                            class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <label for="export_excel" class="ml-2 block text-sm text-gray-700">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V9h-3.5V3z"/>
                                                    <path d="M12.021 6.828c.461-1.062.992-1.828 2.312-1.828v9.5c-2.937 0-5.5-4.666-5.5-4.666S6.25 14.5 3.312 14.5v-9.5c1.321 0 1.851.766 2.312 1.828.396.739.76 1.291 1.521 1.616-.358-.322-.6-.695-.842-1.091-.483-.974-1.096-2.353-3.303-2.353v9.5c2.361 0 4.256-1.2 5.873-3.193.438-.537.847-1.128 1.203-1.731C9.518 10.461 9.9 11.1 10.331 11.659c2.273 2.974 3.585 2.841 4.998 2.841v-9.5c-2.208 0-2.82 1.38-3.303 2.353-.242.396-.484.769-.842 1.091.761-.325 1.125-.877 1.521-1.616z"/>
                                                </svg>
                                                Excel
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500">Choose how you want to receive the report</p>
                            </div>

                            <div class="pt-4">
                                <button type="submit" id="generate-report-btn"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-md text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ __('Generate Report') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Right Panel -->
                    <div class="lg:flex-1 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ __('Generated Report Preview') }}
                        </h3>

                        @if (isset($data) && $data->count())
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th
                                                class="bg-white px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider border-b border-blue-200">
                                                ID
                                            </th>
                                            <th
                                                class="bg-white px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider border-b border-blue-200">
                                                Created At
                                            </th>
                                            <th
                                                class="bg-white px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider border-b border-blue-200">
                                                Details
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($data as $item)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $item->id }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $item->created_at }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ json_encode($item->toArray()) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div
                                class="bg-gray-50 border-2 border-dashed border-blue-200 rounded-lg h-96 flex items-center justify-center">
                                <div class="text-center px-4">
                                    <svg class="mx-auto h-12 w-12 text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No report generated yet</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ __('Your generated report will appear here') }}
                                    </p>
                                    <p class="mt-3 text-xs text-gray-500">
                                        Use the form on the left to generate a new report
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reportTypeSelect = document.getElementById('report_type');
        const startDateInput = document.querySelector('input[name="start_date"]');
        const endDateInput = document.querySelector('input[name="end_date"]');
        const generateButton = document.getElementById('generate-report-btn');
        
        // Initial validation on page load
        validateForm();
        
        // Add event listeners to all form inputs
        reportTypeSelect.addEventListener('change', validateForm);
        startDateInput.addEventListener('change', validateForm);
        endDateInput.addEventListener('change', validateForm);
        
        function validateForm() {
            // Check if all required fields are filled
            const isReportTypeSelected = reportTypeSelect.value !== '';
            const isStartDateFilled = startDateInput.value !== '';
            const isEndDateFilled = endDateInput.value !== '';
            
            // Enable/disable the generate button based on validation
            if (isReportTypeSelected && isStartDateFilled && isEndDateFilled) {
                generateButton.disabled = false;
                generateButton.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                generateButton.disabled = true;
                generateButton.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }
    });
</script>
@endsection