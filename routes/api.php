<?php

use App\Http\Controllers\Api\AbsentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClassroomObservationController;
use App\Http\Controllers\Api\CountyController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\DocumentCheckController;
use App\Http\Controllers\Api\FeePaidController;
use App\Http\Controllers\Api\InfrastructureController;
use App\Http\Controllers\Api\LeadershipController;
use App\Http\Controllers\Api\LevelAndSubjectController;
use App\Http\Controllers\Api\MySchoolController;
use App\Http\Controllers\Api\ParentParticipationController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\ReqTeacherController;
use App\Http\Controllers\Api\SchoolAssessmentController;
use App\Http\Controllers\Api\SchoolLevelController;
use App\Http\Controllers\Api\SchoolOwnershipController;
use App\Http\Controllers\Api\SchoolTypeController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\StudentParticipationController;
use App\Http\Controllers\Api\TextbooksTeachingController;
use App\Http\Controllers\Api\VerifyStudentController;
use App\Http\Controllers\Api\FeeController;
use App\Http\Controllers\Api\PositionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SchoolController;

// Public routes (no authentication required)
Route::post('/login', [AuthController::class, 'login']);

// Password reset routes (public)
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/verify-reset-code', [AuthController::class, 'verifyResetCode']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/resend-reset-code', [AuthController::class, 'resendResetCode']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {

    // Schools routes (prefixed as in original)
    Route::prefix('schools')->name('schools.')->group(function () {
        Route::get('/', [SchoolController::class, 'index'])->name('index');
        Route::post('/', [SchoolController::class, 'store'])->name('store');
        Route::get('/{school}', [SchoolController::class, 'show'])->name('show');
        Route::post('absents', [AbsentController::class, 'store']);
        Route::post('verify-students', [VerifyStudentController::class, 'store']);
        Route::post('staff', [StaffController::class, 'store']);
        Route::post('req-teachers', [ReqTeacherController::class, 'store']);
        Route::post('fees-paid', [FeePaidController::class, 'store']);
        Route::get('my', [SchoolController::class, 'mySchools']);
        Route::post('verification', [SchoolController::class, 'updateVerification']);
        Route::get('{schoolCode}/completeness', [SchoolController::class, 'checkCompleteness']);
        Route::delete('{schoolCode}/incomplete', [SchoolController::class, 'deleteIncompleteSchool']);
    });

    // School Types routes (prefixed as in original)
    Route::prefix('school-types')->name('school-types.')->group(function () {
        Route::get('/', [SchoolTypeController::class, 'index'])->name('index');
        Route::post('/', [SchoolTypeController::class, 'store'])->name('store');
        Route::get('/{schoolType}', [SchoolTypeController::class, 'show'])->name('show');
    });

    // School Levels routes (prefixed as in original)
    Route::prefix('school-levels')->name('school-levels.')->group(function () {
        Route::get('/', [SchoolLevelController::class, 'index'])->name('index');
        Route::post('/', [SchoolLevelController::class, 'store'])->name('store');
        Route::get('/{schoolLevel}', [SchoolLevelController::class, 'show'])->name('show');
    });

    // School Ownerships routes (prefixed as in original)
    Route::prefix('school-ownerships')->name('school-ownerships.')->group(function () {
        Route::get('/', [SchoolOwnershipController::class, 'index'])->name('index');
        Route::post('/', [SchoolOwnershipController::class, 'store'])->name('store');
        Route::get('/{schoolOwnership}', [SchoolOwnershipController::class, 'show'])->name('show');
    });

    // Counties routes (prefixed as in original)
    Route::prefix('counties')->name('counties.')->group(function () {
        Route::get('/', [CountyController::class, 'index'])->name('index');
        Route::post('/', [CountyController::class, 'store'])->name('store');
        Route::get('/{county}', [CountyController::class, 'show'])->name('show');
    });

    // Districts routes (prefixed as in original)
    Route::prefix('districts')->name('districts.')->group(function () {
        Route::get('/', [DistrictController::class, 'index'])->name('index');
        Route::post('/', [DistrictController::class, 'store'])->name('store');
        Route::get('/{district}', [DistrictController::class, 'show'])->name('show');
    });

    // Questions routes (prefixed as in original)
    Route::prefix('questions')->name('questions.')->group(function () {
        Route::get('/', [QuestionController::class, 'index'])->name('index');
        Route::post('/', [QuestionController::class, 'store'])->name('store');
        Route::get('/{question}', [QuestionController::class, 'show'])->name('show');
        Route::put('/{question}', [QuestionController::class, 'update'])->name('update');
        Route::delete('/{question}', [QuestionController::class, 'destroy'])->name('destroy');
    });

    // Document Check routes (prefixed as in original)
    Route::prefix('document-check')->name('document-check.')->group(function () {
        Route::post('/', [DocumentCheckController::class, 'store'])->name('store');
        Route::get('/', [DocumentCheckController::class, 'show'])->name('show');
    });

    // Leadership routes (prefixed as in original)
    Route::prefix('leadership')->name('leadership.')->group(function () {
        Route::post('/', [LeadershipController::class, 'store'])->name('store');
        Route::get('/', [LeadershipController::class, 'show'])->name('show');
    });

    // Infrastructure routes (prefixed as in original)
    Route::prefix('infrastructure')->name('infrastructure.')->group(function () {
        Route::post('/', [InfrastructureController::class, 'store'])->name('store');
        Route::get('/', [InfrastructureController::class, 'show'])->name('show');
    });

    // Classroom Observation routes (prefixed as in original)
    Route::prefix('classroom-observation')->name('classroom-observation.')->group(function () {
        Route::post('/', [ClassroomObservationController::class, 'store'])->name('store');
        Route::get('/', [ClassroomObservationController::class, 'show'])->name('show');
    });

    // Level routes (prefixed as in original)
    Route::prefix('level')->name('level.')->group(function () {
        Route::get('/grades', [LevelAndSubjectController::class, 'getGrades'])->name('grades');
        Route::get('/subjects', [LevelAndSubjectController::class, 'getSubjects'])->name('subjects');
    });

    // Positions Routes (prefixed as in original)
    Route::prefix('positions')->group(function () {
        Route::get('/', [PositionController::class, 'index'])->name('positions.index');
        Route::post('/', [PositionController::class, 'store'])->name('positions.store');
        Route::get('/dropdown', [PositionController::class, 'getForDropdown'])->name('positions.dropdown');
        Route::get('/{position}', [PositionController::class, 'show'])->name('positions.show');
        Route::put('/{position}', [PositionController::class, 'update'])->name('positions.update');
        Route::delete('/{position}', [PositionController::class, 'destroy'])->name('positions.destroy');
    });

    // Fees Routes (prefixed as in original)
    Route::prefix('fees')->group(function () {
        Route::get('/', [FeeController::class, 'index'])->name('fees.index');
        Route::post('/', [FeeController::class, 'store'])->name('fees.store');
        Route::get('/dropdown', [FeeController::class, 'getForDropdown'])->name('fees.dropdown');
        Route::get('/{fee}', [FeeController::class, 'show'])->name('fees.show');
        Route::put('/{fee}', [FeeController::class, 'update'])->name('fees.update');
        Route::delete('/{fee}', [FeeController::class, 'destroy'])->name('fees.destroy');
    });

    // Parent Participation routes (not prefixed in original)
    Route::post('/parent-participation', [ParentParticipationController::class, 'store']);
    Route::get('/parent-participation', [ParentParticipationController::class, 'show']);

    // Student Participation routes (not prefixed in original)
    Route::post('/student-participation', [StudentParticipationController::class, 'store']);
    Route::get('/student-participation', [StudentParticipationController::class, 'show']);

    // Textbooks Teaching routes (not prefixed in original)
    Route::post('/textbooks-teaching', [TextbooksTeachingController::class, 'store']);
    Route::get('/textbooks-teaching', [TextbooksTeachingController::class, 'show']);

    // My Schools routes (not prefixed in original)
    Route::get('/my-schools', [MySchoolController::class, 'index'])->name('my-schools.index');

    // Users routes (not prefixed in original)
    Route::get('/users', [AuthController::class, 'index']);

});
