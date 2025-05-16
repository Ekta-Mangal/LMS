<?php

use App\Http\Controllers\Admin\Approval\LevelUpgradeController;
use App\Http\Controllers\Admin\Approval\ModuleApproveController;
use App\Http\Controllers\Admin\Enrollment\UserDetailsController;
use App\Http\Controllers\Admin\ManageCourse\ManageContentController;
use App\Http\Controllers\Admin\ManageCourse\ManageCourseController;
use App\Http\Controllers\Admin\ManageCourse\ManageModuleController;
use App\Http\Controllers\Admin\Users\UserListController;
use App\Http\Controllers\Certificate\CertifiController;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Students\ModuleController;
use App\Http\Controllers\Students\TestController;
use App\Http\Middleware\CheckUserRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController as APIUserController;


Route::get('/', function () {
    return view('auth.login');
});

Route::get('emslogin', [APIUserController::class, 'emslogin']);

Route::middleware('auth')->group(function () {

    ############################ dashboard Routes ###############################
    Route::get('/dashboard', [DashboardController::class, 'view'])->name('dashboard');
    Route::get('/enrollagent', [DashboardController::class, 'enroll'])->name('enrollagent');
    Route::get('/restart', [DashboardController::class, 'restart'])->name('restart');
    Route::post('/restartcourse', [DashboardController::class, 'restartcourse'])->name('restartcourse');

    ############################ Profile Routes ###############################
    Route::get('/profile', [ProfileController::class, 'view'])->name('profile');
    Route::post('/profileupdate', [ProfileController::class, 'update'])->name('profileupdate');

    ############################ Course Details Routes ###############################
    Route::get('/coursedetails', [CourseController::class, 'view'])->name('coursedetails');
    Route::get('/coursemodules', [CourseController::class, 'viewmodules'])->name('coursemodules');
    Route::post('/upgrade_level', [CourseController::class, 'upgradeLevel'])->name('upgrade_level');

    ############################ Certificate Routes ###############################
    Route::get('/certificate', [CertifiController::class, 'view'])->name('certificate');
    Route::get('/certificate_download', [CertifiController::class, 'download'])->name('certificate.download');
    Route::get('/layout', [CertifiController::class, 'layout'])->name('certificate.layout');

    ############################ Students Routes ###############################
    Route::get('/moduleDetails', [ModuleController::class, 'view'])->name('moduleDetails');
    Route::get('/startmodule', [ModuleController::class, 'start'])->name('startmodule');
    Route::get('gettabdata', [ModuleController::class, 'gettabdata'])->name('gettabdata');
    Route::post('/modulecompleted', [ModuleController::class, 'update'])->name('modulecompleted');
    Route::get('/modulereattempt', [ModuleController::class, 'reattempt'])->name('module.reattempt');
    Route::post('/videoscompleted', [ModuleController::class, 'videoupdate'])->name('videoscompleted');
    Route::get('/start-test/{id}', [TestController::class, 'view'])->name('start.test');
    Route::post('/reattempt', [TestController::class, 'reattempt'])->name('assessment.reattempt');
    Route::post('/submitquiz', [TestController::class, 'insert'])->name('submit.quiz');

    ############################ Admin Routes ###############################
    Route::middleware([CheckUserRole::class])->group(function () {

        ############################Session Approval Routes ###############################
        Route::get('/module_completetion', [ModuleApproveController::class, 'view'])->name('module_completetion');
        Route::post('/module_completetion_accept', [ModuleApproveController::class, 'accept'])->name('module_completetion_accept');
        Route::post('/module_completetion_reject', [ModuleApproveController::class, 'reject'])->name('module_completetion_reject');
        Route::get('/level_upgrade_approval', [LevelUpgradeController::class, 'view'])->name('level_upgrade_approval');
        Route::get('/upgradedetails', [LevelUpgradeController::class, 'viewdetails'])->name('upgradedetails');
        Route::post('/level_upgrade_accept', [LevelUpgradeController::class, 'accept'])->name('level_upgrade_accept');
        Route::post('/level_upgrade_reject', [LevelUpgradeController::class, 'reject'])->name('level_upgrade_reject');

        ############################ Users List Routes ###############################
        Route::get('/userslist', [UserListController::class, 'view'])->name('userslist');

        ############################ Enrollment Routes ###############################
        Route::get('/userdetails', [UserDetailsController::class, 'view'])->name('userdetails');

        ############################ Manage Course Routes ###############################
        Route::get('manageCourse/list', [ManageCourseController::class, 'list'])->name('manageCourse.list');
        Route::get('manageCourse/add', [ManageCourseController::class, 'add'])->name('manageCourse.add');
        Route::post('manageCourse/postadd', [ManageCourseController::class, 'postadd'])->name('manageCourse.postadd');
        Route::get('manageCourse/edit', [ManageCourseController::class, 'edit'])->name('manageCourse.edit');
        Route::post('manageCourse/update', [ManageCourseController::class, 'update'])->name('manageCourse.update');
        Route::get('manageCourse/delete', [ManageCourseController::class, 'delete'])->name('manageCourse.delete');

        ############################ Manage Module Routes ###############################
        Route::get('manageModule/list', [ManageModuleController::class, 'list'])->name('manageModule.list');
        Route::get('manageModule/add', [ManageModuleController::class, 'add'])->name('manageModule.add');
        Route::get('getModulesByCourse', [ManageModuleController::class, 'getModulesByCourse'])->name('getModulesByCourse');
        Route::post('manageModule/postadd', [ManageModuleController::class, 'postadd'])->name('manageModule.postadd');
        Route::get('manageModule/edit', [ManageModuleController::class, 'edit'])->name('manageModule.edit');
        Route::post('manageModule/update', [ManageModuleController::class, 'update'])->name('manageModule.update');
        Route::get('manageModule/delete', [ManageModuleController::class, 'delete'])->name('manageModule.delete');

        ############################ Manage Content Routes ###############################
        Route::get('manageContent/list', [ManageContentController::class, 'list'])->name('manageContent.list');
        Route::get('manageContent/add', [ManageContentController::class, 'add'])->name('manageContent.add');
        Route::post('manageContent/postadd', [ManageContentController::class, 'postadd'])->name('manageContent.postadd');
        Route::get('manageContent/edit', [ManageContentController::class, 'edit'])->name('manageContent.edit');
        Route::get('manageContent/editquiz', [ManageContentController::class, 'editquiz'])->name('manageContent.editquiz');
        Route::post('manageContent/update', [ManageContentController::class, 'update'])->name('manageContent.update');
        Route::post('manageContent/updatequiz', [ManageContentController::class, 'updatequiz'])->name('manageContent.updatequiz');
        Route::post('manageContent/removeFile', [ManageContentController::class, 'removeFile'])->name('manageContent.removeFile');
        Route::get('manageContent/delete', [ManageContentController::class, 'delete'])->name('manageContent.delete');
        Route::get('manageContent/deleteQuiz', [ManageContentController::class, 'deleteQuiz'])->name('manageContent.deleteQuiz');
    });
});

require __DIR__ . '/auth.php';