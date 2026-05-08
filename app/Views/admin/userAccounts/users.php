<!DOCTYPE html>
<html lang="en">
<?php admin_view_layout(['header', 'style']); ?>

<body>
<div id="wrapper">

  <?php admin_view_layout(['sidebar']); ?>

  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">

      <?php admin_view_layout(['navbar']); ?>

      <div class="container-fluid py-4">

        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
          <div>
            <h5 class="m-0 mb-1 font-weight-bold"><?= htmlspecialchars($pageTitle ?? 'User Management') ?></h5>
            <div class="text-muted" style="font-size:13px;"><?= htmlspecialchars($pageSubtitle ?? '') ?></div>
          </div>
          <button type="button" class="btn btn-success shadow-sm" data-toggle="modal" data-target="#addUserModal">
            <i class="bi bi-plus mr-1"></i> Add User
          </button>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="form-card">
              <div class="table-responsive">
                <table id="usersTable" class="table table-hover w-100" style="font-size:13px;">
                  <thead>
                    <tr>
                      <th style="width:48px;"></th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th>Joined</th>
                      <th style="width:100px;">Actions</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <?php admin_view_layout(['footer']); ?>
  </div>
</div>

<?php /* 1. Load jQuery, Bootstrap, DataTables, Notyf */ ?>
<?php admin_view_layout(['script']); ?>

<?php /* 2. Init notyf globally so modals can use it */ ?>
<script nonce="<?= csp_nonce() ?>">
var notyf = new Notyf({ duration: 3000, position: { x: 'right', y: 'bottom' } });
</script>

<?php /* 3. Modals — depend on jQuery + notyf */ ?>
<?php include __DIR__ . '/addUser_modal.php'; ?>
<?php include __DIR__ . '/editUser_modal.php'; ?>
<?php include __DIR__ . '/deleteUser_modal.php'; ?>

<?php /* 4. DataTable init */ ?>
<script nonce="<?= csp_nonce() ?>">
$(document).ready(function () {

  $('#usersTable').DataTable({
    ajax: {
      url: '<?= baseurl("/admin/userAccounts/json") ?>',
      dataSrc: 'data',
      error: function () {
        notyf.error('Failed to load users.');
      }
    },
    columns: [
      {
        data: 'name',
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
          var ini = (data || '?').split(' ').map(function (w) { return w[0]; }).join('').slice(0, 2).toUpperCase();
          var bg  = row.role === 'teacher' ? '#fff8e1' : '#e8f5e9';
          var fg  = row.role === 'teacher' ? '#e65100' : '#258517';
          return '<div style="width:32px;height:32px;border-radius:50%;background:' + bg + ';color:' + fg + ';display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:800;">' + ini + '</div>';
        }
      },
      {
        data: 'name',
        render: function (data) {
          return '<span class="font-weight-bold">' + (data || '') + '</span>';
        }
      },
      {
        data: 'email',
        render: function (data) {
          return data ? '<a href="mailto:' + data + '">' + data + '</a>' : '&mdash;';
        }
      },
      {
        data: 'role',
        render: function (data) {
          var map = {
            student: ['badge-students', 'Student'],
            teacher: ['badge-teachers', 'Teacher'],
            admin:   ['badge-admin',    'Admin']
          };
          var info = map[data] || ['badge-secondary', data];
          return '<span class="badge ' + info[0] + ' px-2">' + info[1] + '</span>';
        }
      },
      {
        data: 'created_at',
        render: function (data) {
          if (!data) return '&mdash;';
          return new Date(data).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
        }
      },
      {
        data: null,
        orderable: false,
        searchable: false,
        render: function (data) {
          var name  = $('<div>').text(data.name).html();
          var email = $('<div>').text(data.email || '').html();
          return '<button class="btn btn-sm btn-light mr-1"' +
            ' data-toggle="modal" data-target="#editUserModal"' +
            ' data-id="'    + data.id   + '"' +
            ' data-name="'  + name      + '"' +
            ' data-email="' + email     + '"' +
            ' data-role="'  + data.role + '"' +
            ' title="Edit"><i class="bi bi-pencil-fill text-secondary"></i></button>' +
            '<button class="btn btn-sm btn-light"' +
            ' data-toggle="modal" data-target="#deleteUserModal"' +
            ' data-id="'   + data.id + '"' +
            ' data-name="' + name    + '"' +
            ' title="Delete"><i class="bi bi-trash3-fill text-danger"></i></button>';
        }
      }
    ],
    order: [[4, 'desc']],
    responsive: true,
    autoWidth: false,
    processing: true,
    language: {
      processing:  '<span class="spinner-border spinner-border-sm text-success mr-1" role="status"></span> Loading...',
      emptyTable:  'No users found.',
      zeroRecords: 'No matching users found.'
    }
  });

});
</script>

</body>
</html>
