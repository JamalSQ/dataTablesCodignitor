<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD using AJAX</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />

      <style>
      .title{
        filter: drop-shadow(6px 9px 4px gray);
      }
      .columName{
        cursor: pointer;
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
            <div class="col-md-6 p-2">
            <input type="search" name="search" autocomplete="off" id="searchInput" placeholder="Search..."><br><br>
            <div id="searchResults"></div>
            <br>
                <table class="table" id="myTable">
                    <thead>
                      <tr>
                      <th scope="col" class="columName" onclick="sorting('id')">ID</th>
                      <th scope="col" class="columName" onclick="sorting('pname')">Name</th>
                      <th scope="col" class="columName" onclick="sorting('pprice')">Price</th>
                        <th scope="col">Actions</th>
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
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<!-- 
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script> -->
<!-- <script src="//cdn.datatables.net/2.1.4/js/dataTables.min.js"></script> -->

<script src="<?= base_url('js/liveDataUsingAjax.js'); ?>"></script>


 
</body>
</html>