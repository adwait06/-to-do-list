<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PHP Simple To Do List App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h2>PHP Simple To Do List App</h2>
        </div>
        <div class="card-body">
		<a href= "{{ route('tasks.show') }}" id="alltask"  class="btn btn-success mb-3" >Show all task </a>
		<span style="float:right"> <a id="addtask" href= "{{ route('tasks.index') }}"  class="btn btn-info mb-3" >Add Task</a> </span>
            <hr/>
		
			 <form id="postForm" name="postForm" class="form-horizontal">
                    <input type="hidden" name="post_id" id="post_id">
                    <div class="col-md-12 row g-3">
                        <label for="title" class="col-sm-3 control-label">Title</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" value="" maxlength="50" required="">
                        <input type="hidden" class="form-control" id="status" name="status"   value="Non completed">
        <div id="responseMessage" style="color:#FF0000"></div>
						</div>
						 <div class="col-md-3">
                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save
                        </button>
                    </div>
						
                    </div>
                   
                   
                </form>
				
				<br/>
				<div id="refresh">
            <table class="table table-bordered" id="postsTable">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Task</th>
                    <th>Status</th>
                    <th width="150px">Action</th>
                </tr>
                </thead>
                <tbody>
				 @foreach($tasks as $task)
				 <tr> 
				 <td>{{ $task->id }}</td>
				 <td>{{ $task->title }}</td>
				 <td>{{ $task->status }}</td>
				  <td>
				  <button data-id="{{ $task->id }}" class="btn btn-danger deletePost">Delete</button>		  
                  
				   <br/>
				    <input type="checkbox" class="complete-task" data-task-id="{{ $task->id }}">
                completed 
				  
               
				
				 </td>
				  </tr>
				  @endforeach
				</tbody>
            </table>
			</div>
 
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalHeading"></h4>
            </div>
            <div class="modal-body">
               
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

      

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Sending..');

            $.ajax({
                data: $('#postForm').serialize(),
                url: "{{ route('tasks.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    $('#postForm').trigger("reset");                  
                    $('#saveBtn').html('Save');
					$('#refresh').load(document.URL + ' #refresh');
                    
                },
				 error: function(data) {
            if(data.status === 422) {
                var errors = data.responseJSON.errors;
                var errorMessage = '';
                $.each(errors, function(key, value) {
                    errorMessage += value[0] + '<br>';
                });
                $('#responseMessage').html(errorMessage);
            } else {
                $('#responseMessage').text('An error occurred. Please try again.');
            }
        }
                
            });
        });

        $('body').on('click', '.deletePost', function () {
            var post_id = $(this).data("id");
			 if (confirm("Are You Sure Delete this Task!!")) {
           

            $.ajax({
                type: "DELETE",
                url: "{{ route('tasks.store') }}" + '/' + post_id,
                success: function (data) {
					$('#refresh').load(document.URL + ' #refresh');
                    fetchPosts();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
			 } else {}
        });
		
		
		
				
    });
	
	
	
</script>
<script>
        $(document).ready(function() {
            $('.complete-task').on('change', function() {
                var taskElement = $(this).closest('tr');
                //var taskId = taskElement.data('task-id');$(this).data("id");
				  var taskId = $(this).data("task-id");;
//alert(taskId);
                $.ajax({
                    url: '/tasks/' + taskId + '/complete',
                    method: 'PATCH',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        if (response.success) {
                            taskElement.fadeOut('slow', function() {
                                $(this).remove();
                            });
                        }
                    },
                    error: function(response) {
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
</body>
</html>
