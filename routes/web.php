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

    Route::get('ef/compose','MessageController@compose')->name('ef-compose');

    Route::post('ef/compose','MessageController@store')->name('ef-compose');

    Route::get('group-compose','MessageController@groupCompose')->name('ef-group-compose');

    Route::post('group-compose','MessageController@store1')->name('ef-group-compose');

    Route::get('/get-all-users/{q}','MessageController@getAllUsers');

    Route::any('ef/sent','MessageController@eFSent')->name('ef-sent');

    Route::post('forward','MessageController@storeForward')->name('forward');

    Route::get('view/{mes_gen}','MessageController@view')->name('view');

    Route::get('view_control/{mes_gen}','MessageController@viewControl')->name('view_control');

    Route::get('ef/view/sent/{user_id}/{mes_gen}','MessageController@viewEFSent')->name('view-sent');

    Route::get('view_my/{mes_gen}','MessageController@view_my')->name('view_my');

    Route::get('control','MessageController@control')->name('control');

    Route::get('load/{file}','MessageController@getDownload')->name('load');

    Route::get('file-download/{id}','MessageController@getDownload')->name('file-download');
    Route::get('preview/{previewFile}','MessageController@previewPdf')->name('preview');
    Route::get('myjpg/{file}','MessageController@previewJpg')->name('myjpg');

    Route::get('/ef-sent/delete/{id}','MessageController@destroy');

    // Message users
    Route::resource('message-users','MessageUsersController');

    Route::get('ef/inbox','MessageUsersController@inbox')->name('ef-inbox');

    Route::get('ef/term-inbox','MessageUsersController@termInbox')->name('ef-term-inbox');

    Route::get('ef/all-inbox','MessageUsersController@allInbox')->name('ef-all-inbox');

    Route::get('deleted','MessageUsersController@deletedMessages')->name('ef-deleted');

    Route::get('ym-newm1','MessageUsersController@ymNewm1')->name('ym-newm1');

    Route::get('edo-load/{file}','EdoMessageController@downloadFile')->name('edo-load');

    Route::get('load-all/{file}','MessageController@downloadAll')->name('load-all');

    Route::get('edo-reply-load/{file}','EdoMessageController@downloadReplyFile')->name('edo-reply-load');

    Route::get('preview/{previewFile}','MessageController@previewPdf')->name('preview');

    Route::get('edoPreView/{preViewFile}','EdoMessageController@preViewPdf')->name('edoPreView');

    Route::get('edoPreViewImg/{imgId}','EdoMessageController@preViewImg')->name('edoPreViewImg');

    Route::get('export/{id}', 'MessageUsersController@exportControl');

    // Message users search
    Route::any('all-search','MessageUsersController@search')->name('all/search');

    // Ajax
    Route::get('ajax', function(){ return view('ajax'); });

    Route::post('/get-depart-users','AjaxController@getDepartUsers');

    Route::post('/get-group-users','AjaxController@getGroupUsers');

    Route::post('/get-sent-users','AjaxController@getSentUsers');

    Route::post('/get-files-modal','AjaxController@getFilesModal');

    Route::post('/controlDateAjax','AjaxController@controlMessages');

    Route::post('/delete-multiple','AjaxController@deleteMultiple');

    // Chat message
    Route::resource('chat', 'ChatMessageController');

    Route::get('/chat', 'ChatMessageController@index')->name('chat.index');

    Route::post('/send-message', 'ChatMessageController@sendMessage');

    Route::post('/get-message', 'ChatMessageController@getMessage');

    Route::post('/get-search-users', 'ChatMessageController@getSearchUsers');

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

    Route::get('/edo/download-protocol-file/{id}', 'EdoManagementProtocolsController@downloadProtocolFile')->name('download-protocol-file');
    Route::get('/edo/preview-protocol-file/{id}', 'EdoManagementProtocolsController@previewProtocolFile')->name('preview-protocol-file');
    Route::get('/edo/remove-single-protocol-file/{id}', 'EdoManagementProtocolsController@removeSingleProtocolFile')->name('remove-single-protocol-file');

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


    // uw project begin
    Route::resource('uw/filials', 'FilialsController');
    Route::resource('uw/uw-users', 'UwUsersController');
    Route::resource('uw-clients', 'UwClientsController');
    Route::get('/uw/home', 'UwClientsController@home');
    Route::get('/uw-clients/create', 'UwClientsController@create');
    Route::get('/uw/client-katm/{id}/{claim_id}', 'UwClientsController@clientKatm')->name('uw-katm');
    Route::post('/uw/create-client','UwClientsController@store');
    Route::post('/get-districts','UwClientsController@getDistricts');
    Route::post('/get-reg-districts','UwClientsController@getRegDistricts');
    Route::get('/uw/get-client-katm/{cid}','UwClientsController@getClientKatm');
    Route::get('/uw/get-client-inps/{cid}','UwClientsController@getClientInps');
    Route::post('uw-clients-edit','UwClientsController@storeEdit');
    Route::post('uw-risk-edit','UwClientsController@riskEdit');
    Route::post('/uw/cs-app-send', 'UwClientsController@csAppSend');
    Route::post('uw-client-files/upload', 'UwClientsController@fileUpload');
    Route::get('/uw/filePreView/{preViewFile}','UwClientsController@preViewPdf')->name('filePreView');
    Route::get('file-load/{file}','UwClientsController@downloadFile')->name('file-load');
    Route::get('/uw-client-file/delete/{id}', 'UwClientsController@destroyFile');
    Route::get('/uw/clients/{status}', 'UwClientsController@CsIndex');
    Route::any('/uw/loan-app/{status}','UwClientsController@riskAdminIndex');
    Route::any('/uw/all-clients','UwClientsController@allClients');
    Route::get('/uw/view-loan/{id}/{claim_id}', 'UwClientsController@riskAdminView');
    Route::get('/uw/view-loan-super-admin/{id}/{claim_id}', 'UwClientsController@superAdminView');
    Route::post('/uw/risk-admin-confirm', 'UwClientsController@riskAdminConfirm');
    Route::post('/uw/risk-admin-cancel', 'UwClientsController@riskAdminCancel');
    Route::get('/uw/loan-app-statistics', 'UwClientsController@loanAppStatistics');
    Route::post('/uw/calc-form','UwClientsController@calcForm');
    Route::get('/uw/get-app-blank/{claim_id}','UwClientsController@getAppBlank');
    Route::resource('uw-loan-types', 'UwLoanTypesController');
    Route::delete('uw/loan-types/{id}', 'UwLoanTypesController@destroy');

    Route::group(['prefix'=>'uw'], function(){
        Route::get('clients', 'UwCreateClientsController@index')->name('uw.create.clients.index');

        Route::get('get-loan-type', 'UwCreateClientsController@getLoanType')->name('uw.get.loan.type');

        Route::get('create-step-one/{id}', 'UwCreateClientsController@createStepOne')->name('uw.create.step.one');
        Route::post('create-step-one', 'UwCreateClientsController@postCreateStepOne')->name('uw.create.step.one.post');

        Route::get('create-step-two/{id}', 'UwCreateClientsController@createStepTwo')->name('uw.create.step.two');
        Route::post('create-step-two', 'UwCreateClientsController@postCreateStepTwo')->name('uw.create.step.two.post');

        Route::get('create-step-three/{id}', 'UwCreateClientsController@createStepThree')->name('uw.create.step.three');
        Route::post('create-step-three', 'UwCreateClientsController@postCreateStepThree')->name('uw.create.step.three.post');

        Route::get('create-step-result/{id}', 'UwCreateClientsController@createStepResult')->name('uw.create.step.result');

        Route::post('uw-online-registration', 'UwInquiryIndividualController@onlineRegistration')->name('uw.online.registration');
        Route::get('uw-get-result-buttons/{id}', 'UwInquiryIndividualController@getResultButtons')->name('uw.get-result-buttons');
        Route::get('get-client-res-k/{id}','UwInquiryIndividualController@getClientKatm');
        Route::get('get-client-res-i/{id}','UwInquiryIndividualController@getClientInps');
        Route::get('get-status-send/{id}','UwInquiryIndividualController@getStatusSend');
        Route::get('get-confirm-send/{id}','UwInquiryIndividualController@getConfirmSend');

        // GUAR
        Route::get('uw-get-client-guars/{id}', 'UwCreateClientsController@getClientGuars')->name('uw.get-client-guars');
        Route::post('create-client-guar', 'UwCreateClientsController@createClientGuar')->name('uw.create-client-guar');
        Route::get('edit-client-guar/{id}', 'UwCreateClientsController@editClientGuar');
        Route::get('delete-client-guar/{id}', 'UwCreateClientsController@deleteClientGuar');

        // FILE
        Route::get('uw-get-client-files/{id}', 'UwCreateClientsController@getClientFiles')->name('uw.get-client-files');
        Route::post('create-client-file', 'UwCreateClientsController@createClientFile')->name('uw.create-client-file');
        Route::get('delete-client-file/{id}', 'UwCreateClientsController@deleteClientFile');
    });
    // DEBTORS
    Route::resource('uw-debtors', 'UwClientDebtorsController');

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

