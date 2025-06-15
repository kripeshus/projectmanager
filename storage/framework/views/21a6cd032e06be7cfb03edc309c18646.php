<!DOCTYPE html>
<html>
<head>
    <title>
    <?php if($type === 'project'): ?>
        Project
    <?php elseif($type === 'task'): ?>
        Task
    <?php else: ?>
        Time Entry
    <?php endif; ?>
    </title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>


</head>
<body>
    <h1>Hi <?php echo e($user->name); ?></h1>
    <table class="table">
    <thead>
        <tr>
            <th scope="col">SNo</th>
            <th scope="col">Project Name</th>
            <?php if($type === 'task' || $type === 'time_entry'): ?>
            <th scope="col">Task Name</th>
            <?php endif; ?>
            <?php if($type === 'time_entry'): ?>
            <th scope="col">Hours</th>
            <th scope="col">Date</th>
            <th scope="col">Description</th>
            <?php endif; ?>
            <?php if($type === 'task' || $type === 'project'): ?>
            <th scope="col">Status</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td><?php echo e(($type === 'task' || $type === 'time_entry') ? ($data->project->name ?? 'N/A') : ($data->name ?? 'N/A')); ?></td>
                <?php if($type === 'task'): ?>
                <td><?php echo e($data->name); ?></td>
                <?php endif; ?>
                <?php if($type === 'time_entry'): ?>
                <td><?php echo e($data->task->name); ?></td>
                <?php endif; ?>
                <?php if($type === 'time_entry'): ?>
                <td><?php echo e($data->hours); ?></td>
                <td><?php echo e($data->date); ?></td>
                <td><?php echo e($data->description); ?></td>
                <?php endif; ?>
                <?php if($type === 'task' || $type === 'project'): ?>
                <td><?php echo e(ucfirst($data->status)); ?></td>
                <?php endif; ?>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php if($type == 'time_entry'): ?>
            <tr>
                <form id="timeEntryForm">
                <td>
                    <button type="submit" class="btn btn-sm btn-success">Add</button>
                </td>
                <td>
                    <input type="text" name="project" id="project" class="form-control" required>
                    <input type="hidden" name="project_id" id="project_id">
                </td>
                <td>
                    <input type="text" name="task" id="task" class="form-control" required>
                    <input type="hidden" name="task_id" id="task_id">
                </td>
                <td><input type="number" name="hour" id="hour" class="form-control"></td>
                <td><input type="date" name="date" id="date" class="form-control" required></td>
                <td><input type="text" name="description" id="description" class="form-control"></td>
                </form>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
    <div id="passwordmessage" class="text-danger mt-3 text-center"></div>
    <button id="logoutBtn">Logout</button>

    <script>
        $('#logoutBtn').click(function () {
            $.ajax({
                url: "<?php echo e(route('logout')); ?>",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    alert(response.message);
                    window.location.href = "/login";
                },
                error: function () {
                    alert("Logout failed");
                }
            });
        });
        
        let projectMap = {};
        let taskMap = {};

        $('#project').autocomplete({
            source: function (request, response) {
                $.getJSON("<?php echo e(route('project.search')); ?>", { term: request.term }, function (data) {
                    projectMap = {};
                    response($.map(data, function (obj) {
                        projectMap[obj.name] = obj.id;
                        return obj.name;
                    }));
                });
            },
            select: function (event, ui) {
                $('#project_id').val(projectMap[ui.item.value]);
            },
            change: function () {
                if (!projectMap[$('#project').val()]) {
                    $('#project_id').val('');
                }
            }
        });

        $('#task').autocomplete({
            source: function (request, response) {
                let projectId = $('#project_id').val();
                let project = $('#project').val();
                if (project && !projectId) {
                    response([]);
                    return;
                }
                $.getJSON("<?php echo e(route('task.search')); ?>", { term: request.term }, function (data) {
                    taskMap = {};
                    response($.map(data, function (obj) {
                        taskMap[obj.name] = obj.id;
                        return obj.name;
                    }));
                });
            },
            select: function (event, ui) {
                $('#task_id').val(taskMap[ui.item.value]);
            },
            change: function () {
                if (!taskMap[$('#task').val()]) {
                    $('#task_id').val('');
                }
            }
        });

        $('#timeEntryForm').submit(function (e) {
            e.preventDefault();
            if(!$('#project').val()){
            $('#passwordmessage').text('Project Name is required.');
            return false
            }
            if(!$('#task').val()){
            $('#passwordmessage').text('Task Name is required.');
            return false
            }
            if(!$('#hour').val()){
            $('#passwordmessage').text('Hours is required.');
            return false
            }
            if(!$('#date').val()){
            $('#passwordmessage').text('Date is required.');
            return false
            }
            if(!$('#description').val()){
            $('#passwordmessage').text('Description is required.');
            return false
            }
            $.ajax({
                url: "<?php echo e(route('timeentry.add')); ?>",
                method: "POST",
                data: {
                    project_id: $('#project_id').val(),
                    task_id: $('#task_id').val(),
                    project: $('#project').val(),
                    task: $('#task').val(),
                    hours: $('#hour').val(),
                    date: $('#date').val(),
                    description: $('#description').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    alert(response.message);
                    $('#timeEntryForm')[0].reset();
                    $('#project_id').val('');
                    $('#task_id').val('');
                    location.reload();
                },
                error: function (xhr) {
                    alert(xhr.responseJSON?.message || 'Error adding time entry.');
                }
            });
        })

    </script>
</body>
</html>
<?php /**PATH /home/kombanstech/Desktop/project_managment/resources/views/list.blade.php ENDPATH**/ ?>