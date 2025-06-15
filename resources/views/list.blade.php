<!DOCTYPE html>
<html>
<head>
    <title>
    @if ($type === 'project')
        Project
    @elseif ($type === 'task')
        Task
    @else
        Time Entry
    @endif
    </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>


</head>
<body>
    <h1>Hi {{ $user->name }}</h1>
    <ul class="nav mb-4">
    <li class="nav-item">
        <a class="nav-link" href="/task">Task</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/project">Project</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/time">Time Entry</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/report">Report</a>
    </li>
    </ul>
    <table class="table">
    <thead>
        <tr>
            <th scope="col">SNo</th>
            <th scope="col">Project Name</th>
            @if($type === 'task' || $type === 'time_entry')
            <th scope="col">Task Name</th>
            @endif
            @if($type === 'time_entry')
            <th scope="col">Hours</th>
            <th scope="col">Date</th>
            <th scope="col">Description</th>
            @endif
            @if($type === 'task' || $type === 'project')
            <th scope="col">Status</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($datas as $index => $data)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ ($type === 'task' || $type === 'time_entry') ? ($data->project->name ?? 'N/A') : ($data->name ?? 'N/A') }}</td>
                @if($type === 'task')
                <td>{{ $data->name }}</td>
                @endif
                @if($type === 'time_entry')
                <td>{{ $data->task->name }}</td>
                @endif
                @if($type === 'time_entry')
                <td>{{ $data->hours }}</td>
                <td>{{ $data->date }}</td>
                <td>{{ $data->description }}</td>
                @endif
                @if($type === 'task' || $type === 'project')
                <td>{{ ucfirst($data->status) }}</td>
                @endif
            </tr>
        @endforeach
        @if($type == 'time_entry')
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
                <td><input type="number" name="hour" id="hour" class="form-control" min="1"></td>
                <td><input type="date" name="date" id="date" class="form-control" required></td>
                <td><input type="text" name="description" id="description" class="form-control"></td>
                </form>
            </tr>
        @endif
    </tbody>
</table>
    <div id="passwordmessage" class="text-danger mt-3 text-center"></div>
    <button id="logoutBtn">Logout</button>

    <script>
        $('#logoutBtn').click(function () {
            $.ajax({
                url: "{{ route('logout') }}",
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
                $.getJSON("{{ route('project.search') }}", { term: request.term }, function (data) {
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
                $.getJSON("{{ route('task.search') }}", { term: request.term }, function (data) {
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
                url: "{{ route('timeentry.add') }}",
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
