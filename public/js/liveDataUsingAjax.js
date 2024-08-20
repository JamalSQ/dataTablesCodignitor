// For fetching the record from the database
fetchdata();

let sortOrder = {
    pname: 'asc',
    pprice: 'asc',
    id:'asc'
};

async function sorting(variable){

        const response = await fetch('js/products.json'); // Load the JSON data
        records = await response.json(); 
        const products = paginate(records, currentPage, recordsPerPage);
    
        if (sortOrder[variable] === 'asc') {
            // Ascending order sorting
            if (variable === "pname") {
                products.sort((a, b) => a.p_name.toLowerCase().localeCompare(b.p_name.toLowerCase()));
            } else if (variable === "pprice") {
                products.sort((a, b) => parseFloat(a.p_price) - parseFloat(b.p_price));
            }
            else if (variable === "id") {
                products.sort((a, b) => parseFloat(a.id) - parseFloat(b.id));
            }
            // Toggle the sort order to descending for the next click
            sortOrder[variable] = 'desc';
        } else {

            // Descending order sorting
            if (variable === "pname") {
                products.sort((a, b) => b.p_name.toLowerCase().localeCompare(a.p_name.toLowerCase()));
            } else if (variable === "pprice") {
                products.sort((a, b) => parseFloat(b.p_price) - parseFloat(a.p_price));
            }
            else if (variable === "id") {
                products.sort((a, b) => parseFloat(b.id) - parseFloat(a.id));
            }
            // Toggle the sort order to ascending for the next click
            sortOrder[variable] = 'asc';
        }
        disply_search_result(products);
}

function disply_search_result(results){
      // Display the search results
      const resultsContainer = document.getElementById('tablebody');
      resultsContainer.innerHTML = '';

      if (results.length > 0) {
          results.forEach(product => {
            const tr = document.createElement('tr');

            // Create and set the first cell (ID)
            const td1 = document.createElement('td');
            td1.textContent = product.id;
            tr.appendChild(td1); // Append the cell to the row

            // Create and set the second cell (Name)
            const td2 = document.createElement('td');
            td2.textContent = product.p_name;
            tr.appendChild(td2); // Append the cell to the row

            // Create and set the third cell (Price)
            const td3 = document.createElement('td');
            td3.textContent = product.p_price;
            tr.appendChild(td3); // Append the cell to the row

            // Create the Edit button
            const edtButton = document.createElement('button');
            edtButton.textContent = "Edit";
            edtButton.classList.add('btn', 'btn-primary','mx-3','my-2');
            edtButton.setAttribute('data-toggle', 'modal');
            edtButton.setAttribute('data-target', '#exampleModalCenter'); // Optional: Add Bootstrap classes
            edtButton.addEventListener('click', () => {
                    // Use template literals correctly to pass product ID
                    fetchdataOnID(product.id);
                });
                tr.appendChild(edtButton);

                // Create the Delete button (if needed)
                const delButton = document.createElement('button');
                delButton.textContent = "Delete";
                delButton.classList.add('btn', 'btn-danger');
                delButton.addEventListener('click', () => {
                        // Use template literals correctly to pass product ID
                        deleteproduct(product.id);
                    });
                tr.appendChild(delButton);
            // Append the row to the table body
            resultsContainer.appendChild(tr);
          });
      } else {
          resultsContainer.textContent = 'No products found.';
      }
}
       // Function to load and search data from the file
       async function searchProducts(query) {
        // Fetch the JSON file containing the data
        const response = await fetch('js/products.json');
        const products = await response.json();
        if(query==""){
            const results=paginate(records, currentPage, recordsPerPage);
            disply_search_result(results);
        }else{
            // Filter the products based on the search query
            const results = products.filter(product => 
                product.p_name.toLowerCase().includes(query.toLowerCase()) ||
                product.p_price.toString().includes(query)
            );
            disply_search_result(results);
        } 
    }

    // Add event listener to the search input field
    document.getElementById('searchInput').addEventListener('input', (e) => {
        searchProducts(e.target.value);
    });

async function updateFile() {
    try {
        $.ajax({
            url: '/updatefile',
            method: 'GET',
            dataType: 'json',
            // success: function(data) {    
            //     if (data.status === 'success') {
            //         console.log('File updated successfully');
            //     } else {
            //         console.error('Error updating file');
            //     }
            // }
            });      
    } catch (error) {
        console.error('Fetch error:', error);
    }
}

// Update file every 20 seconds
setInterval(updateFile, 20000); 

function fetchdata(){
            $.ajax({
                url: '/getAllProducts',
                method: 'GET',
                dataType: 'json',
                success: function(response) {   
                    console.log(response);                
                    var table = $('#tablebody');
                    table.empty();
                    var products = response.data;
                    if (products.length > 0) {
                        $.each(products, function(index, product){
                            table.append(
                                '<tr>' +
                                    '<td>' + product.id + '</td>' +
                                    '<td>' + product.p_name + '</td>' +
                                    '<td>' + product.p_price + '</td>' +
                                    '<td><button type="button" class="btn btn-primary" onclick="fetchdataOnID(' + product.id + ')" data-toggle="modal" data-target="#exampleModalCenter">Edit</button> <button type="button" class="btn btn-danger" onclick="deleteproduct(' + product.id + ')">Delete</button></td>' +
                                '</tr>'
                            );
                        });
                    } else {
                        table.append('<tr><td colspan="5">No data found</td></tr>');
                    }
                }
            });
        }
        // Delete a specific record based on id
    function deleteproduct(id) {
      $.ajax({
        url:"/deleteSingleProduct",
        method:"POST",
        data:{
            id:id
        },
        dataType:'json',
        success:function(deletedata){
          if(deletedata.status==='success'){
            $('#msg').html(deletedata.message)
                        .css({
                            backgroundColor: 'green',
                            color: 'white'
                        })
                        .slideDown()
                        .delay(1000)
                        .slideUp();
                        fetchdata();
          }else{
            $('#msg').html(deletedata.error)
                        .css({
                            backgroundColor: 'red',
                            color: 'white'
                        })
                        .slideDown()
                        .delay(1000)
                        .slideUp();
          }
        }
      });
    }


          
// Display data in the pop up dialog box for updation, so fetching the data based on the id

function fetchdataOnID(id){
  var id=id;
  if(id==""){
    $('#msg').html("Id does not found")
                        .css({
                            backgroundColor: 'red',
                            color: 'white'
                        })
                        .slideDown()
                        .delay(1000)
                        .slideUp();
                        fetchdata();
  }
  $.ajax({
        url:"getsingleproduct",
        method:"POST",
        data:{
          id:id
        },
        dataType:'json',
        success:function(responce){
            var data=responce.data
          $("#upid").val(data.id);
          $("#upname").val(data.p_name);
          $("#upprice").val(data.p_price);
        }
  });
}

// update products 

function updateData(){
        var pname = $('#upname').val();
        var pprice = $('#upprice').val();
        var id = $('#upid').val();
        if (pname === "" || pprice === "") {
          $('#msg').html("Fill all the fields")
            .css({
                backgroundColor: 'red',
                color: 'white'
            })
            .slideDown()
            .delay(1000)
            .slideUp();
        } else {
            if(isNaN(pprice)){
                $('#msg').html("The price should be numeric.")
                .css({
                    backgroundColor: 'red',
                    color: 'white'
                })
                .slideDown()
                .delay(1000)
                .slideUp();
            }else{
                console.log(pname+'  '+pprice);
            $.ajax({
                url: 'updateProducts', // Ensure this path is correct
                method: 'POST',
                data: {
                    id:id,
                    pname: pname,
                    pprice: pprice
                },
                dataType: 'json',
                cache: false,
                success: function(response) {
                    if (response.status === 'success') {
                        $('#msg').html(response.message)
                        .css({
                            backgroundColor: 'green',
                            color: 'white'
                        })
                        .slideDown()
                        .delay(1000)
                        .slideUp();
                        $('#pname').val('');
                        $('#pprice').val('');
                        fetchdata();
                    } else {
                      $('#msg').html(response.message)
                      .css({
                          backgroundColor: 'red',
                          color: 'white'
                      })
                      .slideDown()
                      .delay(1000)
                      .slideUp();
                    }
                }
              })
        }
    }
      }

$(document).ready(function() {


// Inserting data 

$('#productForm').on('submit', function(event) {
        event.preventDefault();
        var pname = $('#pname').val();
        var pprice = $('#pprice').val();

        if (pname === "" || pprice === "") {
          $('#msg').html("Fill all the fields")
    .css({
        backgroundColor: 'red',
        color: 'white'
    })
    .slideDown()
    .delay(1000)
    .slideUp();
        } else {
            $.ajax({
                url: 'insertProducts', // Ensure this path is correct
                method: 'POST',
                data: {
                    pname: pname,
                    pprice: pprice
                },
                dataType: 'json',
                cache: false, // Prevent caching
                success: function(response) {
                    if (response.status === 'success') {
                      
                        $('#msg').html(response.message)
                        .css({
                            backgroundColor: 'green',
                            color: 'white'
                        })
                        .slideDown()
                        .delay(1000)
                        .slideUp();
                        $('#pname').val('');
                        $('#pprice').val('');
                        fetchdata();
                    } else {
                      $('#msg').html(response.message)
                      .css({
                          backgroundColor: 'red',
                          color: 'white'
                      })
                      .slideDown()
                      .delay(1000)
                      .slideUp();
                    }
                },
                error: function(xhr, status, error) {
                    $('#msg').html('An error occurred: ' + error).fadeIn().delay(5000).fadeOut();
                    console.error('AJAX Error:', status, error);
                }
            });
        }
    });

});