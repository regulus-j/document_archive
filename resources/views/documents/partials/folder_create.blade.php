<!-- Modal -->
@if(session('success'))
    <script>
        alert("{{ session('success') }}");
    </script>
@endif

<div id="createFolder_modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Create New Folder
                    </h3>
                    <div class="mt-2">
                        <form action="{{ route('folders.store') }}" method="POST">
                            @csrf
                            <div class="flex items-center justify-between">
                                <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-md" placeholder="Folder name" required>
                                <input type="hidden" name="team_id" id="selected-team-id" value="">
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-sm py-2 px-4 rounded transition-colors">Create</button>
                            </div>
                        </form>

                        {{-- Team Search --}}
                        <form id="team-search-form">
                            @csrf
                            <div class="flex items-center justify-between">
                                <input type="text" name="search" id="search" class="w-full px-4 py-2 border border-gray-300 rounded-md" placeholder="Search team"   >
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-sm py-2 px-4 rounded transition-colors">Search</button>
                            </div>
                        </form>

                        <!-- Team Search Results -->
                        <div id="team-search-results" class="mt-2">
                            <table id="teams-table" class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Team Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teams as $team)
                                        <tr class="team-row cursor-pointer" data-team-id="{{ $team->id }}">
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ $team->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button id="close-modal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                Cancel
            </button>
        </div>
    </div>
</div>

<script>
    document.getElementById('open-modal').addEventListener('click', function() {
        document.getElementById('createFolder_modal').classList.remove('hidden');
    });

    document.getElementById('close-modal').addEventListener('click', function() {
        document.getElementById('createFolder_modal').classList.add('hidden');
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('team-search-form');
        const searchInput = document.getElementById('search');
        const resultsContainer = document.getElementById('team-search-results');
        const teamIdInput = document.getElementById('selected-team-id');
    
        searchForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
    
            const query = searchInput.value;
    
            fetch('{{ route('teams.search') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ search: query })
            })
            .then(response => response.json())
            .then(data => {
                // Clear previous results
                resultsContainer.innerHTML = '';
    
                if (data.teams.length > 0) {
                    const table = document.createElement('table');
                    table.classList.add('min-w-full', 'bg-white', 'shadow-md', 'rounded-lg', 'overflow-hidden');
    
                    const thead = document.createElement('thead');
                    thead.classList.add('bg-gray-100');
                    thead.innerHTML = `
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Team Name</th>
                        </tr>
                    `;
                    table.appendChild(thead);
    
                    const tbody = document.createElement('tbody');
                    data.teams.forEach(team => {
                        const row = document.createElement('tr');
                        row.classList.add('team-row', 'cursor-pointer');
                        row.setAttribute('data-team-id', team.id);
                        row.innerHTML = `
                            <td class="px-6 py-4 text-sm text-gray-900">${team.name}</td>
                        `;
                        tbody.appendChild(row);
    
                        // Add click event listener to the row
                        row.addEventListener('click', function() {
                            // Remove the 'selected' class from all rows
                            document.querySelectorAll('.team-row').forEach(r => r.classList.remove('bg-blue-100'));
    
                            // Add the 'selected' class to the clicked row
                            this.classList.add('bg-blue-100');
    
                            // Update the hidden input value with the selected team ID
                            teamIdInput.value = this.getAttribute('data-team-id');
                        });
                    });
                    table.appendChild(tbody);
    
                    resultsContainer.appendChild(table);
                } else {
                    resultsContainer.textContent = 'No teams found.';
                }
            })
            .catch(error => {
                console.error('Error fetching teams:', error);
            });
        });
    
        // Add click event listeners to existing rows
        document.querySelectorAll('.team-row').forEach(row => {
            row.addEventListener('click', function() {
                // Remove the 'selected' class from all rows
                document.querySelectorAll('.team-row').forEach(r => r.classList.remove('bg-blue-100'));
    
                // Add the 'selected' class to the clicked row
                this.classList.add('bg-blue-100');
    
                // Update the hidden input value with the selected team ID
                teamIdInput.value = this.getAttribute('data-team-id');
            });
        });
    });
    </script>
