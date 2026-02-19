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
use App\Http\Controllers\API\FeeController;
use App\Http\Controllers\API\PositionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SchoolController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

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

    });

    Route::prefix('school-types')->name('school-types.')->group(function () {
        Route::get('/', [SchoolTypeController::class, 'index'])->name('index');
        Route::post('/', [SchoolTypeController::class, 'store'])->name('store');
        Route::get('/{schoolType}', [SchoolTypeController::class, 'show'])->name('show');
        // Route::put('/{schoolType}', ...)->name('update');
        // Route::delete('/{schoolType}', ...)->name('destroy');
    });

    Route::prefix('school-levels')->name('school-levels.')->group(function () {
        Route::get('/', [SchoolLevelController::class, 'index'])->name('index');
        Route::post('/', [SchoolLevelController::class, 'store'])->name('store');
        Route::get('/{schoolLevel}', [SchoolLevelController::class, 'show'])->name('show');
        // Route::put('/{schoolLevel}', ...)->name('update');
        // Route::delete('/{schoolLevel}', ...)->name('destroy');
    });

    Route::prefix('school-ownerships')->name('school-ownerships.')->group(function () {
        Route::get('/', [SchoolOwnershipController::class, 'index'])->name('index');
        Route::post('/', [SchoolOwnershipController::class, 'store'])->name('store');
        Route::get('/{schoolOwnership}', [SchoolOwnershipController::class, 'show'])->name('show');
        // Route::put('/{schoolOwnership}', ...)->name('update');
        // Route::delete('/{schoolOwnership}', ...)->name('destroy');
    });

    Route::prefix('counties')->name('counties.')->group(function () {
        Route::get('/', [CountyController::class, 'index'])->name('index');
        Route::post('/', [CountyController::class, 'store'])->name('store');
        Route::get('/{county}', [CountyController::class, 'show'])->name('show');
        // Route::put('/{county}', ...)->name('update');
        // Route::delete('/{county}', ...)->name('destroy');
    });

    Route::prefix('districts')->name('districts.')->group(function () {
        Route::get('/', [DistrictController::class, 'index'])->name('index');
        Route::post('/', [DistrictController::class, 'store'])->name('store');
        Route::get('/{district}', [DistrictController::class, 'show'])->name('show');
        // Route::put('/{district}', ...)->name('update');
        // Route::delete('/{district}', ...)->name('destroy');
    });

    Route::prefix('districts')->name('districts.')->group(function () {
        Route::get('/', [DistrictController::class, 'index'])->name('index');
        Route::post('/', [DistrictController::class, 'store'])->name('store');
        Route::get('/{district}', [DistrictController::class, 'show'])->name('show');
        // Route::put('/{district}', ...)->name('update');
        // Route::delete('/{district}', ...)->name('destroy');
    });
// routes/api.php

    Route::prefix('questions')->name('questions.')->group(function () {
        Route::get('/', [QuestionController::class, 'index'])->name('index');           // GET /api/questions?cat=Document%20check
        Route::post('/', [QuestionController::class, 'store'])->name('store');
        Route::get('/{question}', [QuestionController::class, 'show'])->name('show');
        Route::put('/{question}', [QuestionController::class, 'update'])->name('update');
        Route::delete('/{question}', [QuestionController::class, 'destroy'])->name('destroy');
    });

    // routes/api.php

    Route::prefix('document-check')->name('document-check.')->group(function () {
        Route::post('/', [DocumentCheckController::class, 'store'])->name('store');
        Route::get('/', [DocumentCheckController::class, 'show'])->name('show'); // optional: fetch existing
    });

    Route::prefix('leadership')->name('leadership.')->group(function () {
        Route::post('/', [LeadershipController::class, 'store'])->name('store');
        Route::get('/', [LeadershipController::class, 'show'])->name('show'); // ?school=MHS-001
    });

    Route::prefix('infrastructure')->name('infrastructure.')->group(function () {
        Route::post('/', [InfrastructureController::class, 'store'])->name('store');
        Route::get('/', [InfrastructureController::class, 'show'])->name('show'); // ?school=MHS-001
    });

    Route::prefix('classroom-observation')->name('classroom-observation.')->group(function () {
        Route::post('/', [ClassroomObservationController::class, 'store'])->name('store');
        Route::get('/', [ClassroomObservationController::class, 'show'])->name('show'); // ?school=MHS-001&class_num=1
    });

    Route::prefix('level')->name('level.')->group(function () {
        Route::get('/grades', [LevelAndSubjectController::class, 'getGrades'])->name('grades');
        Route::get('/subjects', [LevelAndSubjectController::class, 'getSubjects'])->name('subjects');
    });

    // Positions Routes
    Route::prefix('positions')->group(function () {
        Route::get('/', [PositionController::class, 'index'])->name('positions.index');
        Route::post('/', [PositionController::class, 'store'])->name('positions.store');
        Route::get('/dropdown', [PositionController::class, 'getForDropdown'])->name('positions.dropdown');
        Route::get('/{position}', [PositionController::class, 'show'])->name('positions.show');
        Route::put('/{position}', [PositionController::class, 'update'])->name('positions.update');
        Route::delete('/{position}', [PositionController::class, 'destroy'])->name('positions.destroy');
    });

// Fees Routes
    Route::prefix('fees')->group(function () {
        Route::get('/', [FeeController::class, 'index'])->name('fees.index');
        Route::post('/', [FeeController::class, 'store'])->name('fees.store');
        Route::get('/dropdown', [FeeController::class, 'getForDropdown'])->name('fees.dropdown');
        Route::get('/{fee}', [FeeController::class, 'show'])->name('fees.show');
        Route::put('/{fee}', [FeeController::class, 'update'])->name('fees.update');
        Route::delete('/{fee}', [FeeController::class, 'destroy'])->name('fees.destroy');
    });

        Route::post('/parent-participation', [ParentParticipationController::class, 'store']);
        Route::get('/parent-participation', [ParentParticipationController::class, 'show']);

        Route::post('/student-participation', [StudentParticipationController::class, 'store']);
        Route::get('/student-participation', [StudentParticipationController::class, 'show']);

        Route::post('/textbooks-teaching', [TextbooksTeachingController::class, 'store']);
        Route::get('/textbooks-teaching', [TextbooksTeachingController::class, 'show']);
        Route::get('/my-schools', [MySchoolController::class, 'index'])->name('my-schools.index');
});
