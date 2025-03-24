@extends('layouts.app')

@section('content')
    <div class="bg-gradient-to-b from-blue-50 to-white min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Company Management</h1>

            <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-blue-100">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Company List</h2>
                        <!-- Add search input -->
                        <input type="text" 
                               id="searchInput" 
                               placeholder="Search companies..." 
                               class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                        <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                            <!-- Your existing thead code remains the same -->
                            <tbody id="companiesTableBody">
                                @foreach ($companies as $company)
                                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-colors">
                                        <!-- Your existing row code remains the same -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add JavaScript for search functionality -->
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();
            const tbody = document.getElementById('companiesTableBody');
            const rows = tbody.getElementsByTagName('tr');

            for (let row of rows) {
                const cells = row.getElementsByTagName('td');
                let found = false;
                
                for (let cell of cells) {
                    if (cell.textContent.toLowerCase().indexOf(searchText) > -1) {
                        found = true;
                        break;
                    }
                }
                
                row.style.display = found ? '' : 'none';
            }
        });
    </script>
@endsection
