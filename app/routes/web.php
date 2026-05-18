<?php

/*
|--------------------------------------------------------------------------
| PUBLIC – DOCUMENT VERIFICATION
|--------------------------------------------------------------------------
*/
$router->get('/verify/(:any)', 'Verify\VerifyController@index');

/*
|--------------------------------------------------------------------------
| PUBLIC – EXTERNAL SIGNING
|--------------------------------------------------------------------------
*/
$router->get('/sign/verify/(:any)', 'Sign\SignController@verifyCode');
$router->post('/sign/verify',       'Sign\SignController@verifySubmit');
$router->get('/sign/pdf/(:any)',    'Sign\SignController@servePdf');
$router->get('/sign/view/(:any)',   'Sign\SignController@serveSignedPdf');
$router->post('/sign/submit',       'Sign\SignController@submit');
$router->get('/sign/(:any)',        'Sign\SignController@index');

/*
|--------------------------------------------------------------------------
| ADMIN – CORE
|--------------------------------------------------------------------------
*/
$router->get('/admin', 'Admin\Main\AdminController@index');

/*
|--------------------------------------------------------------------------
| USERS – AUTH / NAVBAR
|--------------------------------------------------------------------------
*/
$router->get('/', 'Auth\Login\LoginController@index');
$router->get('/auth/login', 'Auth\Login\LoginController@index');
$router->post('/auth/login/authenticate', 'Auth\Login\LoginController@authenticate');
$router->post('/auth/login/google', 'Auth\Login\LoginController@google');
$router->get('/auth/logout', 'Auth\Login\LoginController@logout');
$router->post('/session/heartbeat', 'Auth\Login\LoginController@heartbeat');

/*
|--------------------------------------------------------------------------
| ADMIN – DEPARTMENT
|--------------------------------------------------------------------------
*/

$router->get('/admin/dashboard', 'Admin\Dashboard\DashboardController@index');

/*
|--------------------------------------------------------------------------
| ADMIN – DEPARTMENT
|--------------------------------------------------------------------------
*/
$router->get('/admin/departments', 'Admin\Departments\DepartmentsController@index');
$router->get('/admin/departments/json', 'Admin\Departments\DepartmentsController@json');
$router->post('/admin/departments/create', 'Admin\Departments\DepartmentsController@create');
$router->post('/admin/departments/update', 'Admin\Departments\DepartmentsController@update');
$router->post('/admin/departments/checkDuplicate', 'Admin\Departments\DepartmentsController@checkDuplicate');
$router->post('/admin/departments/delete', 'Admin\Departments\DepartmentsController@delete');
$router->get('/admin/departments/list', 'Admin\Departments\DepartmentsController@list');
$router->get('/admin/departments/summary', 'Admin\Departments\DepartmentsController@summary');


/*
|--------------------------------------------------------------------------
| ADMIN – USERS
|--------------------------------------------------------------------------
*/
$router->get('/admin/userAccounts', 'Admin\UserAccounts\UserAccountsController@index');
$router->get('/admin/userAccounts/json', 'Admin\UserAccounts\UserAccountsController@json');
$router->post('/admin/userAccounts/create', 'Admin\UserAccounts\UserAccountsController@create');
$router->post('/admin/userAccounts/edit', 'Admin\UserAccounts\UserAccountsController@edit');
$router->post('/admin/userAccounts/delete', 'Admin\UserAccounts\UserAccountsController@delete');
$router->post('/admin/userAccounts/checkEmailDuplicate', 'Admin\UserAccounts\UserAccountsController@checkEmailDuplicate');
$router->post('/admin/userAccounts/updateCredentials',  'Admin\UserAccounts\UserAccountsController@updateCredentials');

/*
|--------------------------------------------------------------------------
| ADMIN – PROGRESS
|--------------------------------------------------------------------------
*/
$router->get('/admin/progress',      'Admin\Progress\ProgressController@index');
$router->get('/admin/progress/json', 'Admin\Progress\ProgressController@json');

/*
|--------------------------------------------------------------------------
| ADMIN – MODULES
|--------------------------------------------------------------------------
*/
$router->get('/admin/modules', 'Admin\Modules\ModulesController@index');
$router->get('/admin/modules/json', 'Admin\Modules\ModulesController@json');
$router->post('/admin/modules/create', 'Admin\Modules\ModulesController@create');
$router->post('/admin/modules/edit', 'Admin\Modules\ModulesController@edit');
$router->post('/admin/modules/delete', 'Admin\Modules\ModulesController@delete');
$router->get('/admin/modules/questions', 'Admin\Modules\ModulesController@getQuestions');
$router->post('/admin/modules/questions/save', 'Admin\Modules\ModulesController@saveQuestions');

/*
|--------------------------------------------------------------------------
| STUDENT
|--------------------------------------------------------------------------
*/
$router->get('/student/dashboard',              'Student\StudentController@index');
$router->get('/student/modules',                'Student\StudentController@modules');
$router->get('/student/modules/(:num)',         'Student\StudentController@moduleView');
$router->get('/student/modules/(:num)/pdf',     'Student\StudentController@servePdf');
$router->get('/student/progress',               'Student\StudentController@progress');
$router->post('/student/api/test/submit',       'Student\StudentController@submitTest');
$router->post('/student/api/lesson/done',       'Student\StudentController@markLessonDone');

/*
|--------------------------------------------------------------------------
| STUDENT – PROFILE
|--------------------------------------------------------------------------
*/
$router->get('/student/profile',                  'Student\Profile\ProfileController@index');
$router->post('/student/profile/update',          'Student\Profile\ProfileController@update');
$router->post('/student/profile/change-password', 'Student\Profile\ProfileController@changePassword');
$router->post('/student/profile/upload-picture',  'Student\Profile\ProfileController@uploadPicture');
$router->get('/student/profile/picture',          'Student\Profile\ProfileController@serveProfilePicture');

/*
|--------------------------------------------------------------------------
| TEACHER – PROFILE
|--------------------------------------------------------------------------
*/
$router->get('/teacher/profile',                'Teacher\Profile\ProfileController@index');
$router->post('/teacher/profile/update',        'Teacher\Profile\ProfileController@update');
$router->post('/teacher/profile/change-password','Teacher\Profile\ProfileController@changePassword');
$router->post('/teacher/profile/upload-picture', 'Teacher\Profile\ProfileController@uploadPicture');
$router->get('/teacher/profile/picture',         'Teacher\Profile\ProfileController@serveProfilePicture');

$router->get('/teacher/dashboard', 'Teacher\Dashboard\TeacherController@index');
$router->get('/teacher/modules',   'Teacher\Dashboard\TeacherController@modules');
$router->get('/teacher/tests',     'Teacher\Dashboard\TeacherController@tests');
$router->get('/teacher/students',  'Teacher\Dashboard\TeacherController@students');
$router->get('/teacher/grades',    'Teacher\Dashboard\TeacherController@grades');
$router->get('/teacher/progress',  'Teacher\Dashboard\TeacherController@progress');
// Teacher AJAX endpoints
$router->get('/teacher/api/modules', 'Teacher\Dashboard\TeacherController@modulesJson');
$router->post('/teacher/api/modules/create', 'Teacher\Dashboard\TeacherController@moduleCreate');
$router->post('/teacher/api/modules/edit', 'Teacher\Dashboard\TeacherController@moduleEdit');
$router->post('/teacher/api/modules/delete', 'Teacher\Dashboard\TeacherController@moduleDelete');
$router->get('/teacher/api/questions', 'Teacher\Dashboard\TeacherController@questionsGet');
$router->post('/teacher/api/questions/save', 'Teacher\Dashboard\TeacherController@questionsSave');
$router->get('/teacher/api/students', 'Teacher\Dashboard\TeacherController@studentsJson');
$router->get('/teacher/api/students/unassigned', 'Teacher\Dashboard\TeacherController@unassignedStudentsJson');
$router->post('/teacher/api/students/create', 'Teacher\Dashboard\TeacherController@studentCreate');
$router->post('/teacher/api/students/create-new', 'Teacher\Dashboard\TeacherController@studentCreateNew');
$router->post('/teacher/api/students/edit', 'Teacher\Dashboard\TeacherController@studentEdit');
$router->post('/teacher/api/students/delete', 'Teacher\Dashboard\TeacherController@studentDelete');
$router->get('/teacher/api/grades', 'Teacher\Dashboard\TeacherController@gradesJson');
$router->post('/teacher/api/grades/create', 'Teacher\Dashboard\TeacherController@gradeCreate');
$router->post('/teacher/api/grades/edit', 'Teacher\Dashboard\TeacherController@gradeEdit');
$router->post('/teacher/api/grades/delete', 'Teacher\Dashboard\TeacherController@gradeDelete');
$router->get('/teacher/api/sections', 'Teacher\Dashboard\TeacherController@sectionsJson');
$router->post('/teacher/api/sections/create', 'Teacher\Dashboard\TeacherController@sectionCreate');
$router->post('/teacher/api/sections/edit', 'Teacher\Dashboard\TeacherController@sectionEdit');
$router->post('/teacher/api/sections/delete', 'Teacher\Dashboard\TeacherController@sectionDelete');

/*
|--------------------------------------------------------------------------
| ADMIN – PROFILE
|--------------------------------------------------------------------------
*/
$router->get('/admin/profile', 'Admin\Profile\ProfileController@index');
$router->post('/admin/profile/update', 'Admin\Profile\ProfileController@update');
$router->post('/admin/profile/change-password', 'Admin\Profile\ProfileController@changePassword');

/*
|--------------------------------------------------------------------------
| ADMIN – DOCUMENTS
|--------------------------------------------------------------------------
*/
$router->get('/admin/documents', 'Admin\Documents\DocumentsController@index');
$router->get('/admin/documents/json', 'Admin\Documents\DocumentsController@json');
$router->get('/admin/documents/stats', 'Admin\Documents\DocumentsController@stats');
$router->get('/admin/documents/view/(:any)', 'Admin\Documents\DocumentsController@viewDoc');
$router->get('/admin/documents/serve/(:any)', 'Admin\Documents\DocumentsController@serveFile');
$router->post('/admin/documents/cancel/(:any)', 'Admin\Documents\DocumentsController@cancel');
$router->post('/admin/documents/force-complete/(:any)', 'Admin\Documents\DocumentsController@forceComplete');

/*
|--------------------------------------------------------------------------
| ADMIN – AUDIT TRAIL
|--------------------------------------------------------------------------
*/
$router->get('/admin/audit_trail', 'Admin\AuditTrail\AuditTrailController@index');
$router->get('/admin/audit_trail/json', 'Admin\AuditTrail\AuditTrailController@json');

/*
|--------------------------------------------------------------------------
| ADMIN – NOTIFICATIONS
|--------------------------------------------------------------------------
*/
$router->get('/admin/notifications', 'Admin\Notifications\NotificationsController@index');
$router->get('/admin/notifications/json', 'Admin\Notifications\NotificationsController@json');
$router->get('/admin/notifications/unread-count', 'Admin\Notifications\NotificationsController@unreadCount');

/*
|--------------------------------------------------------------------------
| EMPLOYEES
|--------------------------------------------------------------------------
*/
$router->get('/employee/dashboard', 'Employee\Dashboard\EmployeeController@index');
$router->get('/employee/dashboard/json', 'Employee\Dashboard\EmployeeController@json');

/*
|--------------------------------------------------------------------------
| EMPLOYEES - DOCUMENTS
|--------------------------------------------------------------------------
*/
$router->get('/employee/my_documents', 'Employee\Documents\DocumentsController@index');
$router->get('/employee/documents/json', 'Employee\Documents\DocumentsController@json');
$router->post('/employee/documents/add', 'Employee\Documents\DocumentsController@add');
$router->post('/employee/documents/update', 'Employee\Documents\DocumentsController@update');
$router->post('/employee/documents/soft-delete', 'Employee\Documents\DocumentsController@softDelete');
$router->get('/employee/documents/users', 'Employee\Documents\DocumentsController@listUsers');
$router->get('/employee/my_documents/view/(:any)', 'Employee\Documents\DocumentsController@viewDocument');
$router->get('/employee/my_documents/download/(:any)', 'Employee\Documents\DocumentsController@download');
$router->post('/employee/documents/move-to-folder', 'Employee\Documents\DocumentsController@moveToFolder');
$router->post('/employee/folders/create', 'Employee\Documents\DocumentsController@folderCreate');
$router->post('/employee/folders/rename', 'Employee\Documents\DocumentsController@folderRename');
$router->post('/employee/folders/delete', 'Employee\Documents\DocumentsController@folderDelete');
$router->get('/employee/folders/list', 'Employee\Documents\DocumentsController@folderList');
$router->post('/employee/folders/trash', 'Employee\Documents\TrashDocumentsController@folderTrash');
$router->post('/employee/folders/restore', 'Employee\Documents\TrashDocumentsController@folderRestore');
$router->post('/employee/folders/force-delete', 'Employee\Documents\TrashDocumentsController@folderForceDelete');

$router->get('/employee/documents/trash', 'Employee\Documents\TrashDocumentsController@index');
$router->get('/employee/documents/trash/json', 'Employee\Documents\TrashDocumentsController@json');
$router->get('/employee/documents/trash/count', 'Employee\Documents\TrashDocumentsController@count');
$router->post('/employee/documents/trash/restore', 'Employee\Documents\TrashDocumentsController@restore');
$router->post('/employee/documents/trash/force-delete', 'Employee\Documents\TrashDocumentsController@forceDelete');
$router->post('/employee/documents/trash/empty', 'Employee\Documents\TrashDocumentsController@emptyTrash');
$router->get('/employee/documents/trash/download/(:num)', 'Employee\Documents\TrashDocumentsController@download');

$router->get('/employee/documents/pending', 'Employee\Documents\PendingDocumentsController@index');
$router->get('/employee/documents/pending/json', 'Employee\Documents\PendingDocumentsController@json');
$router->get('/employee/documents/pending/count', 'Employee\Documents\PendingDocumentsController@count');
$router->get('/employee/documents/pending/view/(:any)', 'Employee\Documents\PendingDocumentsController@viewDoc');
$router->get('/employee/documents/pending/download/(:any)', 'Employee\Documents\PendingDocumentsController@download');
$router->post('/employee/documents/pending/sign', 'Employee\Documents\PendingDocumentsController@sign');
$router->post('/employee/documents/share', 'Employee\Documents\DocumentsController@share');
$router->get('/employee/documents/signers/(:num)', 'Employee\Documents\DocumentsController@signers');
$router->post('/employee/documents/fields/save', 'Employee\Documents\DocumentsController@saveFields');
$router->get('/employee/documents/fields/(:num)', 'Employee\Documents\DocumentsController@getFields');
$router->get('/employee/documents/owner-signer-records/(:num)', 'Employee\Documents\DocumentsController@getOwnerSignerRecords');
$router->get('/employee/documents/pending/saved-signature', 'Employee\Documents\PendingDocumentsController@getSavedSignature');
$router->post('/employee/documents/reject', 'Employee\Documents\PendingDocumentsController@reject');
$router->post('/employee/documents/sign-as-owner', 'Employee\Documents\DocumentsController@signAsOwner');
$router->get('/employee/documents/scan-stats/(:num)', 'Employee\Documents\DocumentsController@scanStats');

/*
|--------------------------------------------------------------------------
| EMPLOYEES - SENT DOCUMENTS
|--------------------------------------------------------------------------
*/
$router->get('/employee/documents/sent', 'Employee\Documents\SentDocumentsController@index');
$router->get('/employee/documents/sent/json', 'Employee\Documents\SentDocumentsController@json');
$router->get('/employee/documents/sent/signers/(:num)', 'Employee\Documents\SentDocumentsController@signers');

/*
|--------------------------------------------------------------------------
| EMPLOYEES - SHARED WITH ME
|--------------------------------------------------------------------------
*/
$router->get('/employee/documents/shared', 'Employee\Documents\SharedDocumentsController@index');
$router->get('/employee/documents/shared/json', 'Employee\Documents\SharedDocumentsController@json');

/*
|--------------------------------------------------------------------------
| EMPLOYEES - COMPLETED DOCUMENTS
|--------------------------------------------------------------------------
*/
$router->get('/employee/documents/completed', 'Employee\Documents\CompletedDocumentsController@index');
$router->get('/employee/documents/completed/json', 'Employee\Documents\CompletedDocumentsController@json');

/*
|--------------------------------------------------------------------------
| EMPLOYEES - PROFILE
|--------------------------------------------------------------------------
*/
$router->get('/employee/profile', 'Employee\Profile\ProfileController@index');
$router->post('/employee/profile/update', 'Employee\Profile\ProfileController@update');
$router->post('/employee/profile/change-password', 'Employee\Profile\ProfileController@changePassword');
$router->post('/employee/profile/signature/save', 'Employee\Profile\ProfileController@saveSignature');
$router->post('/employee/profile/signature/delete', 'Employee\Profile\ProfileController@deleteSignature');
$router->get('/employee/profile/signature/serve', 'Employee\Profile\ProfileController@serveSignature');

/*
|--------------------------------------------------------------------------
| EMPLOYEES - NOTIFICATIONS
|--------------------------------------------------------------------------
*/
$router->get('/employee/notifications', 'Employee\Notifications\NotificationsController@index');
$router->get('/employee/notifications/json', 'Employee\Notifications\NotificationsController@json');
$router->post('/employee/notifications/mark-read', 'Employee\Notifications\NotificationsController@markRead');
$router->post('/employee/notifications/mark-all-read', 'Employee\Notifications\NotificationsController@markAllRead');
$router->post('/employee/notifications/toggle-read', 'Employee\Notifications\NotificationsController@toggleRead');
$router->get('/employee/notifications/unread-count', 'Employee\Notifications\NotificationsController@unreadCount');

/*
|--------------------------------------------------------------------------
| EMPLOYEES - AUDIT TRAIL
|--------------------------------------------------------------------------
*/
$router->get('/employee/audit_trail', 'Employee\AuditTrail\AuditTrailController@index');
$router->get('/employee/audit_trail/json', 'Employee\AuditTrail\AuditTrailController@json');

/*
|--------------------------------------------------------------------------
| EMPLOYEES - SETTINGS
|--------------------------------------------------------------------------
*/
$router->get('/employee/settings', 'Employee\Settings\SettingsController@index');
$router->post('/employee/settings/save', 'Employee\Settings\SettingsController@save');

/*
|--------------------------------------------------------------------------
| ADMIN – SETTINGS
|--------------------------------------------------------------------------
*/
$router->get('/admin/settings', 'Admin\Settings\SettingsController@index');
$router->post('/admin/settings/save', 'Admin\Settings\SettingsController@save');

/*
|--------------------------------------------------------------------------
| ADMIN – SIGNERS
|--------------------------------------------------------------------------
*/
$router->get('/admin/signers', 'Admin\Signers\SignersController@index');
$router->get('/admin/signers/json', 'Admin\Signers\SignersController@json');

/*
|--------------------------------------------------------------------------
| ADMIN – VERIFICATIONS
|--------------------------------------------------------------------------
*/
$router->get('/admin/verifications', 'Admin\Verifications\VerificationsController@index');
$router->get('/admin/verifications/json', 'Admin\Verifications\VerificationsController@json');
$router->get('/admin/verifications/stats', 'Admin\Verifications\VerificationsController@stats');

/*
|--------------------------------------------------------------------------
| ADMIN – REPORTS
|--------------------------------------------------------------------------
*/
$router->get('/admin/reports', 'Admin\Reports\ReportsController@index');
$router->get('/admin/reports/json', 'Admin\Reports\ReportsController@json');