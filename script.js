// Function to format ISO 8601 dates into a readable format
function formatDate(isoDate) {
    const date = new Date(isoDate);
    if (isNaN(date)) return isoDate; // Fallback if date parsing fails
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric',
        hour12: true, // For 12-hour format with AM/PM
    });
}

// Apply the date formatting to the grid items
function applyDateFormatting() {
    const dateElements = document.querySelectorAll('.grid-item-date-val');
    dateElements.forEach((el) => {
        const isoDate = el.textContent.replace('Date: ', ''); // Extract the ISO date
        el.textContent = `${formatDate(isoDate)}`; // Replace with formatted date
    });
}

// Call this function when the page loads
document.addEventListener('DOMContentLoaded', applyDateFormatting);

function sortGrid(sortBy) {
    const gridContainer = document.getElementById('grid-container');
    const gridItems = Array.from(gridContainer.getElementsByClassName('grid-item'));

    gridItems.sort((a, b) => {
        const aValue = a.dataset[sortBy] || ''; // Default to empty string if attribute is missing
        const bValue = b.dataset[sortBy] || '';

        if (sortBy === 'id') {
            // Sort numerically for IDs
            return parseInt(aValue) - parseInt(bValue);
        } else if (sortBy === 'start_at') {
            // Sort by date
            return new Date(aValue) - new Date(bValue);
        } else {
            // Sort alphabetically for names
            return aValue.localeCompare(bValue);
        }
    });

    // Reorder the grid items
    gridItems.forEach(item => gridContainer.appendChild(item));
}