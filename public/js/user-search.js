document.addEventListener('DOMContentLoaded', function() {
    // DOM elements
    const searchCriteriaSelect = document.getElementById('search-criteria');
    const searchValueInput = document.getElementById('search-value');
    const searchOptions = document.getElementById('search-options');
    const clearBtn = document.getElementById('clear-search');
    
    // Options data storage
    let searchOptionsData = {
        usernames: [],
        firstnames: [],
        lastnames: []
    };
    
    // Debounce for search
    let debounceTimeout = null;
    
    // Load search options when page loads
    loadSearchOptions();
    
    // Add event listeners
    searchCriteriaSelect.addEventListener('change', handleCriteriaChange);
    searchValueInput.addEventListener('input', handleSearchInput);
    clearBtn.addEventListener('click', clearSearch);
    
    /**
     * Handle when user changes the search criteria
     */
    function handleCriteriaChange() {
        const criteria = searchCriteriaSelect.value;
        
        // Enable/disable the search value input
        if (criteria) {
            searchValueInput.disabled = false;
            searchValueInput.focus();
            
            // Update options based on selected criteria
            updateDatalistOptions(criteria);
        } else {
            searchValueInput.disabled = true;
            searchValueInput.value = '';
            // Clear the current results and show all users
            performSearch(null, null);
        }
    }
    
    /**
     * Handle search input
     */
    function handleSearchInput() {
        // Clear any existing timeout
        if (debounceTimeout) {
            clearTimeout(debounceTimeout);
        }
        
        // Set a new timeout to prevent too many requests
        debounceTimeout = setTimeout(() => {
            const criteria = searchCriteriaSelect.value;
            const value = searchValueInput.value.trim();
            
            if (criteria && value) {
                performSearch(criteria, value);
            } else if (criteria && !value) {
                // If criteria is selected but no value, show all
                performSearch(null, null);
            }
        }, 300); // 300ms delay
    }
    
    /**
     * Clear the search and reset to initial state
     */
    function clearSearch() {
        searchCriteriaSelect.value = '';
        searchValueInput.value = '';
        searchValueInput.disabled = true;
        performSearch(null, null);
    }
    
    /**
     * Update datalist options based on selected criteria
     */
    function updateDatalistOptions(criteria) {
        // Clear existing options
        searchOptions.innerHTML = '';
        
        let options = [];
        
        // Select the appropriate options based on criteria
        if (criteria === 'username') {
            options = searchOptionsData.usernames;
        } else if (criteria === 'firstname') {
            options = searchOptionsData.firstnames;
        } else if (criteria === 'lastname') {
            options = searchOptionsData.lastnames;
        }
        
        // Add options to datalist
        options.forEach(option => {
            if (option) { // Only add non-null/empty options
                const optionElement = document.createElement('option');
                optionElement.value = option;
                searchOptions.appendChild(optionElement);
            }
        });
    }
    
    /**
     * Load search options from the server
     */
    function loadSearchOptions() {
        fetch('/user/search-options', {
            method: 'GET'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Store the options
            searchOptionsData = data;
        })
        .catch(error => {
            console.error('Error loading search options:', error);
        });
    }
    
    /**
     * Perform search based on criteria and value
     */
    function performSearch(criteria, value) {
        // Create FormData object
        const formData = new FormData();
        
        // Add search parameters based on criteria
        if (criteria && value) {
            formData.append(criteria, value);
        }
        
        // Create fetch request
        fetch('/user/search', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            updateUsersTable(data);
        })
        .catch(error => {
            console.error('Error during search:', error);
        });
    }
    
    /**
     * Update the users table with search results
     */
    function updateUsersTable(users) {
        const tableBody = document.getElementById('users-table-body');
        
        // Clear current table content
        tableBody.innerHTML = '';
        
        if (users.length === 0) {
            // Display no results message
            const noResultsRow = document.createElement('tr');
            noResultsRow.innerHTML = '<td colspan="13">No users found.</td>';
            tableBody.appendChild(noResultsRow);
            return;
        }
        
        // Add each user to the table
        users.forEach(user => {
            const row = document.createElement('tr');
            
            // Format birthday if exists
            let birthday = '';
            if (user.userBirthday) {
                const date = new Date(user.userBirthday);
                birthday = date.toISOString().split('T')[0];
            }
            
            // Format picture
            let pictureHtml = '<span>No Picture</span>';
            if (user.userPicture) {
                pictureHtml = `<img src="/uploads/pictures/${user.userPicture}" alt="Profile" width="60">`;
            }
            
            // Create row HTML
            row.innerHTML = `
                <td>${user.userUsername || ''}</td>
                <td>${user.userEmail || ''}</td>
                <td>${user.userPassword || ''}</td>
                <td>${user.userFirstname || ''}</td>
                <td>${user.userLastname || ''}</td>
                <td>${birthday || ''}</td>
                <td>${user.userGender || ''}</td>
                <td>${pictureHtml}</td>
                <td>${user.userPhonenumber || ''}</td>
                <td>${user.userLevel || ''}</td>
                <td>${user.userRole || ''}</td>
                <td>
                    <a class="btn" href="/user/${user.userId}">Show</a>
                    <a class="btn" href="/user/${user.userId}/edit">Edit</a>
                </td>
            `;
            
            tableBody.appendChild(row);
        });
    }
}); 