@extends('layouts.app')
@section('content')


<!--add studentModal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="saveform_err_list">

                </div>
                <div class="form-group mb-3">
                    <label for="">Name</label>
                    <input type="text" class="name form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="">Email</label>
                    <input type="text" class="email form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="">Phone</label>
                    <input type="text" class="phone form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="">Course</label>
                    <input type="text" class="course form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary add_student">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- end add student modal-->

<!--edit studentModal -->
<div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="updateform_err_list">

                </div>
                <input type="hidden" id="edit_student_id">

                <div class="form-group mb-3">
                    <label for="">Name</label>
                    <input type="text" id="edit_name" class="name form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="">Email</label>
                    <input type="text" id="edit_email" class="email form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="">Phone</label>
                    <input type="text" id="edit_phone" class="phone form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="">Course</label>
                    <input type="text" id="edit_course" class="course form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary update_student">Update</button>
            </div>
        </div>
    </div>
</div>
<!-- end edit student modal-->

<!--delete studentModal -->
<div class="modal fade" id="deleteStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Remove Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

               <input type="hidden" id="delete_student_id">
               <h4>Are you sure you want to delete <span class="student_name"></span></h4>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary delete_student_btn">Yes Delete</button>
            </div>
        </div>
    </div>
</div>
<!-- end delete student modal-->


<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div id="success_msg">

                </div>

                <div class="card-header">
                    <h4>Students Data
                        <a href="" class="btn btn-primary float-end btn-sm" data-bs-toggle="modal" data-bs-target="#addStudentModal">Add Student</a>
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Course</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
    $(document).ready(function() {

        function fetchStudent(){
            $.ajax({
                type: "GET",
                url: "fetch-students",
                dataType: "json",
                success: function (response) {
                    // console.log(response.students)
                    $('tbody').html('');
                    $.each(response.students, function (key, item) { 
                         $('tbody').append(`<tr>
                            <td>${item.name}</td>
                            <td>${item.email}</td>
                            <td>${item.phone}</td>
                            <td>${item.course}</td>
                            <td><button type="button" value="${item.id}" class="edit_student btn btn-primary btn-sm">Edit</button></td>
                            <td><button type="button" value="${item.id}" class="delete_student btn btn-danger btn-sm">Delete</button></td>
                         </tr>`);
                    });
                }   
            });
        }

        fetchStudent();

        $(document).on('click', '.edit_student', function(e){
            e.preventDefault();

            let student_id = $(this).val();
            // console.log(student_id);
            jQuery('#editStudentModal').modal('show');
            $.ajax({
                type: "GET",
                url: `/edit-student/${student_id}`,
                success: function (response) {
                    // console.log(response)
                    if(response.status == 404){
                        $('#success_msg').html('')
                        $('#success_msg').addClass('alert alert-danger')
                        $('#success_msg').text(response.message)

                    }else{
                        $('#edit_name').val(response.student.name)
                        $('#edit_email').val(response.student.email)
                        $('#edit_phone').val(response.student.phone)
                        $('#edit_course').val(response.student.course)
                        $('#edit_student_id').val(student_id)

                    }
                }
            });

        });

        $(document).on('click', '.update_student', function(e){
            e.preventDefault();
            $(this).text('Updating records...')

            let student_id = $('#edit_student_id').val();
            let data = {
                'name': $('#edit_name').val(),
                'email': $('#edit_email').val(),
                'phone': $('#edit_phone').val(),
                'course': $('#edit_course').val(),
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "PUT",
                url: `/update-student/${student_id}`,
                data: data,
                dataType: "json",
                success: function (response) {
                    // console.log(response)
                    if(response.status == 400){

                        $('#updateform_err_list').html('')
                        $('#updateform_err_list').addClass('alert alert-danger')
                        $.each(response.errors, function (key, err_values) { 
                             $('#updateform_err_list').append(`<em> ${err_values} <br></em>`)
                        });
                        $('.update_student').text('Update')

                    }else if(response.status == 404){

                        $('#updateform_err_list').html('')
                        $('#success_msg').addClass('alert alert-danger')
                        $('#success_msg').text(response.message)
                        $('.update_student').text('Update')

                    }else{

                        $('#updateform_err_list').html('')
                        $('#success_msg').html('')
                        $('#success_msg').addClass('alert alert-success')
                        $('#success_msg').text(response.message)

                        jQuery('#editStudentModal').modal('hide')
                        $('.update_student').text('Update')

                        fetchStudent()
                    }
                }
            });
        });


        $(document).on('click', '.delete_student', function(e){
            e.preventDefault(e)
            let student_id = $(this).val(); //using $(this) because of the value attr associated with the button
            // console.log(student_id);
            $('#delete_student_id').val(student_id)
            jQuery('#deleteStudentModal').modal('show')

            $.ajax({
                type: "GET",
                url:  `/remove-student/${student_id}`,
                success: function (response) {
                    if(response.status == 200){
                        $('.student_name').text(response.student.name);
                    }
                }
            });

        });

        $(document).on('click', '.delete_student_btn', function(e){
            e.preventDefault();
            $(this).text('Deleting...')
            let student_id = $('#delete_student_id').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "DELETE",
                url: `/remove-student/${student_id}`,
                success: function (response) {
                    $('#success_msg').addClass('alert alert-success')
                    $('#success_msg').text(response.message)
                    jQuery('#deleteStudentModal').modal('hide')
                    $('.delete_student_btn').text('Yes')
                    fetchStudent()
                }
            });
        });

        $(document).on('click', '.add_student', function(e) {
            e.preventDefault();
            var data = {
                'name': $('.name').val(),
                'email': $('.email').val(),
                'phone': $('.phone').val(),
                'course': $('.course').val(),

            }
            // console.log(data);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/students",
                data: data,
                dataType: "json",
                success: function(response) {
                    // console.log(response);
                    if(response.status == 400){
                        $('#saveform_err_list').html('')
                        $('#saveform_err_list').addClass('alert alert-danger')
                        $.each(response.errors, function (key, err_values) { 
                             $('#saveform_err_list').append('<em>' +err_values+ '<br>'+ '</em>')

                        });
                    }else{
                        $('#saveform_err_list').html('')
                        $('#success_msg').addClass('alert alert-success')
                        $('#success_msg').text(response.message)
                        jQuery('#addStudentModal').modal('hide')
                        $('#addStudentModal').find('input').val('')
                        fetchStudent();
                    }
                }
            });
        });

    });
</script>

@endsection