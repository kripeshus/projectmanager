<!DOCTYPE html>
<html>
<head>
    <title>Project Report</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="p-5">
    <h2>Search by Project</h2>
    <select id="projectFilter" class="form-select w-50 mb-4">
        <option value="">All Projects</option>
        <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($proj->id); ?>"><?php echo e($proj->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <h3>Report</h3>
    <table class="table table-bordered" id="reportTable">
        <thead>
            <tr>
                <th>SNo</th>
                <th>Name</th>
                <th>Total Hours</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <script>
        function fetchReport(projectId = '') {
            $.get("<?php echo e(route('timeentry.data')); ?>", { project_id: projectId }, function(data) {
                let tbody = '';
                let sno = 1;
                data.forEach((project) => {
                    tbody += `<tr class="table-secondary"><td>${sno++}</td><td>${project.project}</td><td>${project.total}</td></tr>`;
                    project.tasks.forEach(task => {
                        tbody += `<tr><td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;${task.name}</td><td>${task.hours}</td></tr>`;
                    });
                });
                $('#reportTable tbody').html(tbody);
            });
        }


        $('#projectFilter').change(function () {
            fetchReport($(this).val());
        });

        $(document).ready(function () {
            fetchReport();
        });
    </script>
</body>
</html>
<?php /**PATH /home/kombanstech/Desktop/project_managment/resources/views/report.blade.php ENDPATH**/ ?>