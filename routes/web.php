<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('locale/{locale}', function ($locale){
    Session::put('locale', $locale);
    return redirect()->back();
});

Auth::routes();

Route::group(['middleware' => ['auth']], function() {

// Main Controller includes files, messages and messages of specific users
    Route::resource('main-control','MainController');
    // user-message-control
    Route::any('/user-message-control','MainController@spesificUserMessageControlSearch')->name('search-owner-of-message');
    // View delete message
    Route::any('view-message-delete/{id}/{user_id}','MainController@viewMessageDelete')->name('view-delete');
    // user-message-delete Deleting spesific user's messages
    Route::any('/user-message-delete','MainController@spesificUserseMessages');
    Route::any('/user-message-delete/{id}','MainController@spesificUserseMessagesSearch')->name('/sending-users-id');
    // File Control Delete
    Route::any('file-control','MainController@fileControlSearch')->name('file-search');
    // Monitoring messages
    Route::any('monitoring-message', 'MainController@monitoringMessSearch')->name('monitoring-message-search');
    // Message Users Function view
    Route::any('/message-users-delete','MainController@messageUsersDeleteSearch')->name('message-users-delete/search');
    //  Delete Files
    Route::post('/delete-multiple-files','MainController@deleteMultipleFiles');
    // Delete Spesific User Message
    Route::post('/delete-multiple-mess','MainController@deleteMultipleSpesificUserMessages');
    // Delete General Messages
    Route::post('/delete-multiple-general-mess','MainController@deleteMultipleGeneralMessages');
    // Delete Message Inside (Through View)
    Route::post('/delete-inside-message','MainController@deleteInsideMessage');



    Route::get('/', 'HomeController@index')->name('home');

    Route::get('/home', 'HomeController@index')->name('home');

    // online app
    Route::get('bank-user','AccountController@bankUserIndex')->name('bank-user');
    Route::post('replyAcc', 'AccountController@replyAccPost')->name('reply.acc');
    // end online app

    Route::resource('mes-type','MesTypeController');

    // User Role
    Route::resource('admin/roles', 'RoleController');
    Route::get('admin/ora','RoleController@ora');

    // User
    Route::resource('admin/users','UserController');
    Route::get('admin/users1','UserController@getTest');
    // User search
    Route::any('users-search','UserController@search')->name('users/search');

    // Department
    Route::resource('admin/departments','DepartmentController');

    Route::post('/get-department','DepartmentController@department');

    Route::post('/get-user-department','DepartmentController@userDepartment');

    Route::post('/postbranch','DepartmentController@subdep');

    // Group
    Route::resource('groups','GroupController');

    Route::post('cgroup','GroupController@store')->name('cgroup');

    // Message
    Route::resource('messages','MessageController');

    Route::get('fe/compose','MessageController@compose')->name('fe-compose');

    Route::post('fe/compose','MessageController@store')->name('post-fe-compose');
    Route::post('fe-ajax-compose','MessageController@feAjaxCompose');

    Route::get('group-compose','MessageController@groupCompose')->name('fe-group-compose');

    Route::post('group-compose','MessageController@storeGroup')->name('post-fe-group-compose');

    Route::post('post-fe-forward','MessageController@storeFR')->name('post-fe-forward');

    Route::get('/get-all-users/{q}','MessageController@getAllUsers');

    Route::get('fe/sent','MessageController@feSent')->name('fe-sent');

    Route::post('forward','MessageController@storeForward')->name('forward');

    Route::get('fe/view/{id}/{hash}','MessageController@view')->name('fe-view');

    Route::get('view_control/{mes_gen}','MessageController@viewControl')->name('view_control');

    Route::get('fe/view/sent/{id}/{hash}','MessageController@viewFESent')->name('fe-view-sent');

    Route::get('fe-fileDownload/{id}','MessageController@fileDownload');

    Route::get('fe-fileDownloadAll/{message_id}','MessageController@fileDownloadAll')->name('fe-download-all');

    Route::get('fe-fileView/{id}','MessageController@fileView');

    Route::get('fe/getBlade','MessageController@getBlade');

    Route::get('fe/deleteMessage','MessageController@destroy');

    // Message users
    Route::resource('message-users','MessageUsersController');

    Route::get('fe/inbox','MessageUsersController@inbox')->name('fe-inbox');

    Route::get('fe/all-inbox','MessageUsersController@allInbox')->name('fe-all-inbox');

    Route::get('fe/get-filial','MessageUsersController@getFilial');

    Route::get('deleted','MessageUsersController@deletedMessages')->name('fe-deleted');

    Route::post('fe/deleteAllMessage','MessageUsersController@destroy');

    Route::get('fe/deleteInboxMessage','MessageUsersController@destroyInbox');

    Route::get('fe/getSentUsers','MessageUsersController@getSentUsers');

    #Route::get('fe/term-inbox','MessageUsersController@termInbox')->name('fe-term-inbox');

    // Message users search
    #Route::any('all-search','MessageUsersController@search')->name('all/search');

    // Ajax
    #Route::get('ajax', function(){ return view('ajax'); });

    Route::post('/get-depart-users','AjaxController@getDepartUsers');

    Route::post('/get-group-users','AjaxController@getGroupUsers');

    #Route::post('/get-sent-users','AjaxController@getSentUsers');

    #Route::post('/get-files-modal','AjaxController@getFilesModal');

    #Route::post('/controlDateAjax','AjaxController@controlMessages');

    #Route::post('/delete-multiple','AjaxController@deleteMultiple');

    // EDO Route

    // Edo users
    Route::resource('edo-users','EdoUsersController');

    Route::any('edo-users-search','EdoUsersController@index');

    Route::get('/edo/d-users','EdoUsersController@departmentUsers')->name('d-users');

    Route::post('d-user-post','EdoUsersController@storeDUser')->name('d-user-post');

    Route::get('d-user-edit/{id}','EdoUsersController@editD')->name('d-user-edit');

    Route::post('d-user-delete/{id}','EdoUsersController@destroyD')->name('d-user-delete');

    // Edo user roles
    Route::resource('edo-user-roles','EdoUserRolesController');

    // Edo message
    Route::resource('edo-message','EdoMessageController');

    Route::get('/edo/home','EdoMessageController@index')->name('edo-home');

    Route::get('/edo/task/create','EdoMessageController@create')->name('office-create');

    Route::get('/edo/view-g-task/{id}', 'EdoMessageController@viewGuideTask')->name('view-guide-task');

    Route::post('/edo-message-file/upload', 'EdoMessageController@fileUpload');

    Route::get('/edo-message-file/delete/{id}', 'EdoMessageController@destroy');

    Route::get('/edo/edit-g-task/{id}', 'EdoMessageController@editGuideTask')->name('edit-guide-task');

    Route::get('edo-fileDownload/{id}','EdoMessageController@fileDownload');

    Route::get('edo-fileView/{id}','EdoMessageController@fileView');

    Route::get('edo-fileReplyDownload/{id}','EdoMessageController@fileReplyDownload');

    Route::get('edo-fileReplyView/{id}','EdoMessageController@fileReplyView');

    # Jamshid To change Receivers
    Route::get('/edo/edit-g-task-change/{id}', 'EdoMessageController@editGuideTaskChange')->name('edit-guide-task-change');

    Route::post('create-g-s_task','EdoMessageController@storeCreateGuideTask')->name('create-g-s_task');

    # Jamshid To store the change Receivers
    Route::post('edit-g-s_task-change','EdoMessageController@storeEditGuideTaskChange')->name('edit-g-s_task-change');

    Route::post('edit-g-s_task','EdoMessageController@storeEditGuideTask')->name('edit-g-s_task');

    Route::get('guide-task-confirm/{id}', 'EdoMessageController@guideTaskConfirm')->name('guide-task-confirm');

    Route::get('/edo/edit-performer/{id}/{slug}', 'EdoMessageController@editPerformer')->name('edit-performer');

    Route::get('/edo/view-task-process/{id}/{slug}', 'EdoMessageController@viewTaskProcess')->name('view-task-process');

    Route::get('/edo/view-d-resolution/{id}', 'EdoMessageController@viewDirectorResolution')->name('view-d-resolution');

    // Edo message journals
    Route::resource('edo-message-journals','EdoMessageJournalsController');

    Route::get('/edo/g-tasks/inbox','EdoMessageJournalsController@guideTaskInbox')->name('guide-tasks-inbox');

    Route::get('/edo/g-tasks/g-inbox','EdoMessageUsersController@guideTaskGInbox')->name('guide-tasks-g-inbox');

    Route::get('/edo/g-tasks/g-process','EdoMessageUsersController@guideTaskGProcess')->name('guide-tasks-g-process');

    Route::get('/edo/g-tasks/resolution','EdoMessageJournalsController@guideTaskResolution')->name('guide-tasks-resolution');

    Route::post('redirect-task','EdoMessageJournalsController@redirectTask')->name('redirect-task');

    Route::get('/edo/g-tasks/redirect','EdoMessageJournalsController@guideTasksRedirect')->name('g-tasks-redirect');

    Route::post('get-journal-number', 'EdoMessageJournalsController@getJournalNumber')->name('get-journal-number');

    Route::post('cancel-g-task','EdoMessageJournalsController@cancelGuideTask')->name('cancel-g-task');

    Route::post('cancel-d-task','EdoMessageJournalsController@cancelDirectorTask')->name('cancel-d-task');

    Route::get('/edo/g-tasks/sent','EdoMessageJournalsController@guideTasksSent')->name('g-tasks-sent');

    Route::get('/edo/g-tasks/closed','EdoMessageJournalsController@guideTasksClosed')->name('g-tasks-closed');

    Route::get('/edo/tasks/control','EdoMessageJournalsController@control')->name('/edo/tasks/control');

    Route::get('/edo/office-tasks/sent','EdoMessageJournalsController@officeTasksSent')->name('office-tasks-sent');

    Route::get('director-confirm/{m_id}/{u_id}/{mu_id}', 'EdoMessageJournalsController@directorConfirm');

    Route::get('office-journal-edit/{id}','EdoMessageJournalsController@officeJournalEdit')->name('office-journal-edit');

    Route::post('office-journal-post','EdoMessageJournalsController@officeJournalPost')->name('office-journal-post');

    Route::get('helper-journal-delete/{id}','EdoMessageJournalsController@helperJournalDelete')->name('helper-journal-delete');

    Route::any('office-tasks-sent','EdoMessageJournalsController@officeTasksSent')->name('office-tasks-sent');

    Route::any('g-tasks-sent','EdoMessageJournalsController@guideTasksSent')->name('g-tasks-sent');

    # Jamshid added New to change Receivers
    Route::any('/g-tasks-sent-change','EdoMessageJournalsController@guideTasksSentChange')->name('g-tasks-sent-change');

    // Edo message users
    Route::resource('edo-message-users','EdoMessageUsersController');

    Route::get('/edo/d-tasks/reg', 'EdoMessageUsersController@directorTasksReg')->name('d-tasks-reg');

    Route::get('/edo/d-tasks/inbox', 'EdoMessageUsersController@directorTasksInbox')->name('d-tasks-inbox');

    Route::get('/edo/d-tasks/closed', 'EdoMessageUsersController@directorTasksClosed')->name('d-tasks-closed');

    Route::get('/edo/view-d-task/{id}/{slug}', 'EdoMessageUsersController@viewDirectorTask')->name('view-d-task');

    Route::get('/edo/view-reg-task/{id}/{slug}', 'EdoMessageUsersController@viewRegDirectorTask')->name('view-reg-task');

    Route::get('edo/view-d-task/process/{id}/{slug}', 'EdoMessageUsersController@viewDirectorTaskProcess')->name('view-d-task-process');

    Route::post('dep-reg-task','EdoMessageUsersController@DepRegTask')->name('dep-reg-task');

    Route::post('dep-reg-task1','EdoMessageUsersController@DepRegTask1')->name('dep-reg-task1');

    Route::any('/edo/d-tasks/process','EdoMessageUsersController@directorTasksProcess')->name('d-tasks-process');

    Route::any('/edo/d-tasks/journal','EdoMessageUsersController@directorTasksJournal')->name('d-tasks-journal');

    // Edo message sub users
    Route::resource('edo-message-sub-users','EdoMessageSubUsersController');

    Route::any('/edo/e-tasks/inbox', 'EdoMessageSubUsersController@empTasksInbox')->name('e-tasks-inbox');

    Route::any('/edo/e-tasks/process', 'EdoMessageSubUsersController@empTasksProcess')->name('e-tasks-process');

    Route::any('/edo/e-tasks/closed', 'EdoMessageSubUsersController@empTasksClosed')->name('e-tasks-closed');

    Route::get('/edo/view-e-task/{id}/{slug}', 'EdoMessageSubUsersController@viewEmpTask')->name('view-e-task');

    Route::post('edit-sub-performer','EdoMessageSubUsersController@storeEditSubPerformer')->name('edit-sub-performer');

    Route::post('create-e-task-emp','EdoMessageSubUsersController@storeDirectorEmp')->name('create-e-task-emp');

    Route::get('/edo/view-depart-d-task/{id}/{slug}', 'EdoMessageSubUsersController@viewDepartDTask')->name('view-depart-d-task');

    Route::post('add-sub-users','EdoMessageSubUsersController@storeAddSubUsers')->name('add-sub-users');

    // edo edp inbox journals
    Route::get('d-office-journal-edit/{id}','EdoDepInboxJournalsController@officeJournalEdit')->name('d-office-journal-edit');

    Route::post('d-office-journal-post','EdoDepInboxJournalsController@officeJournalPost')->name('d-office-journal-post');


    // Edo reply message
    Route::resource('edo-reply-messages','EdoReplyMessageController');

    Route::get('/edo-s-reply-cancel/{id}', 'EdoReplyMessageController@cancelReply')->name('edo-s-reply-cancel');

    Route::get('replyMessage', 'EdoReplyMessageController@replyMessage');

    Route::post('replyMessage', 'EdoReplyMessageController@replyMessagePost')->name('reply.message');

    Route::post('reply-edit', 'EdoReplyMessageController@replyEdit')->name('reply-edit');

    Route::post('reply-receive', 'EdoReplyMessageController@replyReceive')->name('reply-receive');

    Route::post('req-confirm', 'EdoReplyMessageController@reqConfirm')->name('req-confirm');

    Route::get('/director-close-task/{id}/{dId}', 'EdoReplyMessageController@directorCloseTask')->name('director-close-task');
    #####################################################################################################################################
    Route::get('/reply-confirm/{id}/{dId}/{departId}', 'EdoReplyMessageController@replyConfirm')->name('reply-confirm');
    #####################################################################################################################################
    Route::post('guide-receive', 'EdoReplyMessageController@guideReceive')->name('guide-receive');

    Route::post('guide-cancel', 'EdoReplyMessageController@guideCancel')->name('guide-cancel');

    Route::post('req-task-confirm', 'EdoReplyMessageController@reqTaskConfirm')->name('req-task-confirm');

    Route::get('/reply-task-confirm/{id}/{dId}', 'EdoReplyMessageController@replyTaskConfirm')->name('reply-task-confirm');

    Route::post('get-sub-perf-users', 'EdoReplyMessageController@getSubPerfUsers')->name('get-sub-perf-users');

    Route::get('/reply-d-d-confirm/{id}/{dId}', 'EdoReplyMessageController@replyDepartDConfirm')->name('reply-d-d-confirm');

    // Helper task
    Route::resource('helper-tasks','HelperTaskController');

    // Edo type messages
    Route::resource('edo-type-messages','EdoTypeMessagesController');

    // Edo journals
    Route::resource('edo-journals','EdoJournalController');

    Route::any('edo-journals.viewTasks/{id}','EdoJournalController@viewTasks')->name('edo-journals.viewTasks');

    Route::post('/get-executors','EdoJournalController@getExecutors');

    // Ip networks
    Route::resource('ip-networks','IpNetworksController');

    // Management protocols
    Route::resource('edo-management-protocols','EdoManagementProtocolsController');

    Route::get('/qr/account/{name}/{hash}', 'EdoManagementProtocolsController@qrAccount')->name('qr-account');

    Route::get('/edo/create-protocol', 'EdoManagementProtocolsController@createProtocol');

    Route::get('/edo/index-protocols', 'EdoManagementProtocolsController@indexProtocol');

    Route::any('/edo/member-protocols', 'EdoManagementProtocolsController@memberProtocol');

    Route::any('/edo/hr-member-protocols/{protocol_dep}', 'EdoManagementProtocolsController@hrMemberProtocol');

    Route::post('/edo/store-protocol','EdoManagementProtocolsController@storeProtocol')->name('edo-store-protocol');

    Route::post('/edo/store-edo-protocol','EdoManagementProtocolsController@storeEditProtocol')->name('edo-store-edit-protocol');

    Route::post('/edo/cancel-protocol','EdoManagementProtocolsController@cancelProtocol')->name('cancel-protocol');

    Route::get('/edo/edit-protocol/{id}/{hash}', 'EdoManagementProtocolsController@editProtocol')->name('edit-protocol');

    Route::get('/edo/view-protocol/{id}/{hash}', 'EdoManagementProtocolsController@viewProtocol')->name('view-protocol');

    Route::get('/edo/view-m-protocol/{id}/{hash}', 'EdoManagementProtocolsController@viewMProtocol')->name('view-m-protocol');

    Route::get('/edo/confirm-protocol/{id}', 'EdoManagementProtocolsController@confirmProtocol')->name('confirm-protocol');

    // Main staff
    Route::any('/edo/staff-protocols', 'EdoManagementProtocolsController@staffProtocol')->name('edo-staff-protocols');

    Route::get('/edo/create-staff-protocol', 'EdoManagementProtocolsController@createStaffProtocol');

    Route::post('/edo/store-edo-stf-protocol','EdoManagementProtocolsController@storeEditStfProtocol')->name('edo-store-edit-stf-protocol');

    Route::get('/edo/edit-stf-protocol/{id}/{hash}', 'EdoManagementProtocolsController@editStfProtocol')->name('edit-stf-protocol');

    Route::get('/edo/delete-stf-protocol/{id}', 'EdoManagementProtocolsController@deleteStfProtocol')->name('delete-stf-protocol');

    Route::get('/edo/view-stf-protocol/{id}/{hash}', 'EdoManagementProtocolsController@viewStfProtocol')->name('view-stf-protocol');

    Route::get('prt-fileDownload/{id}', 'EdoManagementProtocolsController@fileDownload');
    Route::get('prt-fileView/{id}', 'EdoManagementProtocolsController@fileView');
    Route::get('prt-fileRemove/{id}', 'EdoManagementProtocolsController@fileRemove');

    Route::get('/edo/view-my-stf-protocol/{id}/{hash}', 'EdoManagementProtocolsController@viewMyStfProtocol')->name('view-my-stf-protocol');

    Route::get('stf-number-edit/{id}','EdoManagementProtocolsController@stfNumberEdit');

    Route::post('stf-number-post','EdoManagementProtocolsController@stfNumberPost')->name('stf-number-post');

    Route::post('stf-main-confirm','EdoManagementProtocolsController@stfMainConfirm')->name('stf-main-confirm');

    Route::post('stf-main-cancel', 'EdoManagementProtocolsController@stfMainCancel')->name('stf-main-cancel');

    // my protocols emp
    Route::get('/edo/my-protocols', 'EdoManagementProtocolsController@myProtocol')->name('edo-my-protocols');

    // QrCode Message
    Route::resource('edo-qr-messages','EdoQrMessagesController');

    Route::get('/edo/qr-messages', 'EdoQrMessagesController@index')->name('edo-qr-message-index');

    Route::get('/edo/view-qr-message/{id}/{hash}', 'EdoQrMessagesController@viewQrMessage')->name('view-qr-message');
    Route::get('/edo/view-pdf/{id}/{hash}', 'EdoQrMessagesController@viewPdf')->name('view-pdf');

    Route::get('/edo/guide-qr-messages', 'EdoQrMessagesController@guideIndex')->name('edo-guide-qr-message-index');

    Route::get('/edo/view-guide-qr-message/{id}/{hash}', 'EdoQrMessagesController@viewGuideQrMessage')->name('view-guide-qr-message');

    Route::get('edo-qr-load/{file}','EdoQrMessagesController@downloadFile')->name('edo-qr-load');

    Route::post('edo-rq-guide-confirm/{id}','EdoQrMessagesController@guideConfirm')->name('edo-rq-guide-confirm');

    Route::get('edo-qr-file-delete/{file}','EdoQrMessagesController@deleteFile')->name('edo-qr-file-delete');

    Route::get('/edo/pdf-qr-message/{id}/{hash}','EdoQrMessagesController@generatePdf')->name('pdf-qr-message');


    // ip networks
    Route::get('admin/ip-networks', 'IpNetworksController@allIPNetworks')->name('it-admin-ip-networks');
    Route::get('admin/it-test', 'IpNetworksController@test')->name('it-test');

    Route::any('ip-network-search','IpNetworksController@allIPNetworks')->name('ip-network-search');

    // Laravel log
    Route::get('/storage/log', 'HomeController@storageLog')->name('storage-log');

    Route::get('storage/{log}', function ($log)
    {
        $pathToFile = storage_path() . "/logs/". $log;
        return response()->file($pathToFile);
    });

    //Clear route cache:
    Route::get('/route-cache', function() {
        Artisan::call('route:cache');
        return 'Routes cache cleared';
    });

    //Clear config cache:
    Route::get('/config-cache', function() {
        Artisan::call('config:cache');
        return 'Config cache cleared';
    });

// Clear application cache:
    Route::get('/clear-cache', function() {
        Artisan::call('cache:clear');
        return 'Application cache cleared';
    });

    // Clear view cache:
    Route::get('/view-clear', function() {
        Artisan::call('view:clear');
        return 'View cache cleared';
    });

});

