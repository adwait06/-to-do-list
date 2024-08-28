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
				<h4> Show All Task </h4>	
<br/>				
            <table class="table table-bordered" id="postsTable">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Task</th>
                    <th>Status</th>
                   
                </tr>
                </thead>
                <tbody>
				 @foreach($tasks as $task)
				 <tr> 
				 <td>{{ $task->id }}</td>
				 <td>{{ $task->title }}</td>
				 <td>{{ $task->status }}</td>
				  
				  </tr>
				  @endforeach
				</tbody>
            </table>
			
 
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
$('#alltask').on('click', function() {
   // $('#postForm').hide();
});

    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Fetch Posts
        function fetchPosts() {
            $.ajax({
                type: "GET",
                url: "{{ route('tasks.index') }}",
                success: function (data) {
					//alert(data);
                    var rows = '';
                    data.forEach(function (task) {
                        rows += '<tr id="post_' + task.id + '">';
                        rows += '<td>' + task.id + '</td>';
                        rows += '<td>' + task.title + '</td>';
                        rows += '<td>' + task.status + '</td>';
                        rows += '<td>';
                        rows += '<button data-id="' + task.id + '" class="btn btn-primary editPost">Edit</button>';
                        rows += '<button data-id="' + task.id + '" class="btn btn-danger deletePost">Delete</button>';
                        rows += '</td>';
                        rows += '</tr>';
                    });
                    $('#postsTable tbody').html(rows);
                }
            });
        }

        fetchPosts();

        $('#createNewPost').click(function () {
            $('#saveBtn').val("create-post");
            $('#post_id').val('');
            $('#postForm').trigger("reset");
            $('#modalHeading').html("Create New Post");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editPost', function () {
            var post_id = $(this).data('id');
			alert(post_id);
            $.get("{{ route('tasks.index') }}" + '/' + post_id, function (data) {
				alert('mycheck');
                $('#modalHeading').html("Edit Post");
                $('#saveBtn').val("edit-post");              
                $('#post_id').val(data.id);
                $('#title').val(data.title);
			   
                $('#status').val(data.status);
            })
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
                    fetchPosts();
                },
                error: function (data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Save');
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
alert(taskId);
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
