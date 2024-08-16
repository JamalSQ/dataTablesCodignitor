<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD using AJAX</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
      .title{
        filter: drop-shadow(6px 9px 4px gray);
      }
    </style>
</head>
<body>
    <div class="container m-5">
        <div class="row">
            <h1 class="text-center m-4 title">AJAX Based CRUD using Codignitor 4</h1>
            <div class="col-md-6">
                <form id="productForm">
                        <div class="form-group">
                            <label for="">Enter product name</label>
                            <input type="text" name="pname" id="pname" class="form-control">
                        </div>
                        </br>
                        <div class="form-group">
                            <label for="">Enter Price</label>
                            <input type="text" name="pprice" id="pprice" class="form-control">
                        </div>
                        </br>
                        <div class="form-group mb-4">
                            <button type="submit" onclick="" class="btn btn-success mt-2">Submit</button>
                        </div>
                  <p id="msg" style="z-index:999;" class="alert"></p>
                </form>
            </div>
            <div class="col-md-6 bg-light p-2">
                <table class="table table-striped">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">price</th>
                        <th colspan="2">Actions</th>
                      </tr>
                    </thead>
                    <tbody id="tablebody">
                    </tbody>
                  </table>
            </div>
        </div>
    </div>

    <!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLongTitle">Update Product</h5>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
          <!-- <span aria-hidden="true">&times;</span> -->
        </button>
      </div>
      <div class="modal-body">
      <form id="productForm" method="POST" action="<?= base_url('insertProducts');?>">
                      <input type="hidden" name="upid" id="upid" class="form-control">
                  <div class="form-group">
                      <label for="">Enter product name</label>
                      <input type="text" name="upname" id="upname" class="form-control">
                  </div>
                  </br>
                  <div class="form-group">
                      <label for="">Enter Price</label>
                      <input type="text" name="upprice" id="upprice" class="form-control">
                  </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" onclick="updateData()" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
   <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
 
 <script>
    fetchdata();
// For fetching the record from the database
function fetchdata(){
            $.ajax({
                url: '/getAllProducts',
                method: 'GET',
                dataType: 'json',
                success: function(response) {                   
                    var table = $('#tablebody');
                    table.empty();
                    var products = response.data;
                    if (products.length > 0) {
                        $.each(products, function(index, product) {
                            table.append(
                                '<tr>' +
                                    '<td>' + product.id + '</td>' +
                                    '<td>' + product.p_name + '</td>' +
                                    '<td>' + product.p_price + '</td>' +
                                    '<td><button type="button" class="btn btn-primary" onclick="fetchdataOnID(' + product.id + ')" data-toggle="modal" data-target="#exampleModalCenter">Edit</button></td>' +
                                    '<td><button type="button" class="btn btn-danger" onclick="deleteproduct(' + product.id + ')">Delete</button></td>' +
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
                    console.log(response)
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
</script>
</body>
</html>