let currentPage = 1;
let records = [];
let recordsPerPage=5;

async function fetchData() {
    try {
        const response = await fetch('js/products.json'); // Load the JSON data
        records = await response.json(); // Parse the JSON data
        updatePage(currentPage); // Initialize page with first set of records
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

function paginate(records, page, perPage) {
    const start = (page - 1) * perPage;
    const end = page * perPage;
    console.log(records);
    return records.slice(start, end);
}

function updatePage(page){
    currentPage = page;
    const paginatedRecords = paginate(records, currentPage, recordsPerPage);
    renderRecords(paginatedRecords);
    renderPaginationControls(recordsPerPage);
}

function renderRecords(records) {
    const tableBody = document.getElementById('tablebody');
    tableBody.innerHTML = ''; // Clear existing rows

    records.forEach(record => {
        const row = `<tr>
            <td>${record.id}</td>
            <td>${record.p_name}</td>
            <td>${record.p_price}</td>
            <td><button type="button" class="btn btn-primary" onclick="fetchdataOnID() data-toggle="modal" data-target="#exampleModalCenter">Edit</button></td>
            <td><button type="button" class="btn btn-danger" onclick="deleteproduct()">Delete</button></td>
        </tr>`;
        tableBody.innerHTML += row;
    });
}

function renderPaginationControls(recordsPerPage) {
    const totalPages = Math.ceil(records.length / recordsPerPage);
    const paginationControls = document.getElementById('pagination-controls');
    // const paginationControls = document.getElementsByClassName('pagination');
    paginationControls.innerHTML = '';
    // <li class="page-item"><a class="page-link" href="#">Previous</a></li>
       // Previous button
       const prevButton = document.createElement('li');
       prevButton.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
       const prevLink = document.createElement('a');
       prevLink.className = 'page-link';
       prevLink.href = '#';
       prevLink.textContent = 'Previous';
       prevLink.onclick = () => {
           if (currentPage > 1) {
               updatePage(currentPage - 1);
           }
       };
       prevButton.appendChild(prevLink);
       paginationControls.appendChild(prevButton);

       const pageRange = getPageRange(currentPage, totalPages);
       pageRange.forEach(pageNumber => {
           const li = document.createElement('li');
           li.classList.add('page-item');
           if (pageNumber === currentPage) {
               li.classList.add('active'); // Mark the current page as active
           }
           const atag = document.createElement('a');
           atag.classList.add('page-link');
           atag.href = '#';
           atag.textContent = pageNumber;
           atag.onclick = () => updatePage(pageNumber);
           li.appendChild(atag);
           paginationControls.appendChild(li);
       });

    // Next button
    const nextButton = document.createElement('li');
    nextButton.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
    const nextLink = document.createElement('a');
    nextLink.className = 'page-link';
    nextLink.href = '#';
    nextLink.textContent = 'Next';
    nextLink.onclick = () => {
        if (currentPage < totalPages) {
            updatePage(currentPage + 1);
        }
    };
    nextButton.appendChild(nextLink);
    paginationControls.appendChild(nextButton);

}

function getPageRange(currentPage, totalPages) {
    const range = [];
    const maxPagesToShow = 3;
    let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
    let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

    if (endPage - startPage + 1 < maxPagesToShow) {
        startPage = Math.max(1, endPage - maxPagesToShow + 1);
    }

    for (let i = startPage; i <= endPage; i++) {
        range.push(i);
    }

    return range;
}

// Initial fetch and render
fetchData();
