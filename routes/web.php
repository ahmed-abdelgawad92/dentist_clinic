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
//LOGIN Routes
Route::get('/', [
  'uses'=>'Auth\LoginController@getLogin',
  'as'=>'login'
]);
Route::post('/',[
  'uses'=>'Auth\LoginController@authenticate',
  'as'=>'postLogin'
]);
Route::get("/logout",[
  'uses'=>'Auth\LoginController@logout',
  'as'=>'logout'
]);
//Authenticated Routes
Route::group(['middleware' => 'auth'], function() {
  Route::get('/home', [
    'uses'=>'AppointmentController@home',
    'as'=>'home'
  ]);
  //Recycle Routes
  Route::prefix('recycle/bin')->group(function(){
    //route to get teeth
    Route::get('teeth',[
      'uses'=>"RecycleBinController@getTeeth",
      'as'=>'allDeletedTeeth'
    ]);
    //route to get visits
    Route::get('visits',[
      'uses'=>"RecycleBinController@getAppointments",
      'as'=>'allDeletedAppointments'
    ]);
    //route to get patients
    Route::get('patients',[
      'uses'=>"RecycleBinController@getPatients",
      'as'=>'allDeletedPatients'
    ]);
    //route to get drugs
    Route::get('drugs',[
      'uses'=>"RecycleBinController@getDrugs",
      'as'=>'allDeletedDrugs'
    ]);
    //route to get working_times
    Route::get('working_times',[
      'uses'=>"RecycleBinController@getWorkingTimes",
      'as'=>'allDeletedWorkingTimes'
    ]);
    //route to get users
    Route::get('users',[
      'uses'=>"RecycleBinController@getUsers",
      'as'=>'allDeletedUsers'
    ]);
    //route to get diagnosis
    Route::get('diagnosis',[
      'uses'=>"RecycleBinController@getDiagnoses",
      'as'=>'allDeletedDiagnoses'
    ]);
    /*
    *
    *  Recover Models
    *
    */
    //route to get teeth
    Route::get('recover/{id}/teeth',[
      'uses'=>"RecycleBinController@recoverTooth",
      'as'=>'recoverTooth'
    ])->where('id','[0-9]+');
    //route to get visits
    Route::get('recover/{id}/visits',[
      'uses'=>"RecycleBinController@recoverAppointment",
      'as'=>'recoverAppointment'
    ])->where('id','[0-9]+');
    //route to get patients
    Route::get('recover/{id}/patients',[
      'uses'=>"RecycleBinController@recoverPatient",
      'as'=>'recoverPatient'
    ])->where('id','[0-9]+');
    //route to get drugs
    Route::get('recover/{id}/drugs',[
      'uses'=>"RecycleBinController@recoverDrug",
      'as'=>'recoverDrug'
    ])->where('id','[0-9]+');
    //route to get working_times
    Route::get('recover/{id}/working_times',[
      'uses'=>"RecycleBinController@recoverWorkingTime",
      'as'=>'recoverWorkingTime'
    ])->where('id','[0-9]+');
    //route to get users
    Route::get('recover/{id}/users',[
      'uses'=>"RecycleBinController@recoverUser",
      'as'=>'recoverUser'
    ])->where('id','[0-9]+');
    //route to get diagnosis
    Route::get('recover/{id}/diagnosis',[
      'uses'=>"RecycleBinController@recoverDiagnose",
      'as'=>'recoverDiagnose'
    ])->where('id','[0-9]+');
    /*
    *
    * Delete Permanently
    *
    */
    Route::get('delete/permanently/{id}/teeth',[
      'uses'=>"RecycleBinController@deletePerTooth",
      'as'=>'deleteTooth'
    ])->where('id','[0-9]+');
    //route to get visits
    Route::get('delete/permanently/{id}/visits',[
      'uses'=>"RecycleBinController@deletePerAppointment",
      'as'=>'deleteAppointment'
    ])->where('id','[0-9]+');
    //route to get patients
    Route::get('delete/permanently/{id}/patients',[
      'uses'=>"RecycleBinController@deletePerPatient",
      'as'=>'deletePatient'
    ])->where('id','[0-9]+');
    //route to get working_times
    Route::get('delete/permanently/{id}/working_times',[
      'uses'=>"RecycleBinController@deletePerWorkingTime",
      'as'=>'deleteWorkingTime'
    ])->where('id','[0-9]+');
    //route to get users
    Route::get('delete/permanently/{id}/users',[
      'uses'=>"RecycleBinController@deletePerUser",
      'as'=>'deleteUser'
    ])->where('id','[0-9]+');
    //route to get diagnosis
    Route::get('delete/permanently/{id}/diagnosis',[
      'uses'=>"RecycleBinController@deletePerDiagnose",
      'as'=>'deleteDiagnose'
    ])->where('id','[0-9]+');
  });
  //USERS Routes
  Route::prefix('user')->group(function(){
    //Create Users
    Route::get("create",[
      "uses"=>"UserController@create",
      "as"=>"createUser"
    ]);
    Route::post("create",[
      "uses"=>"UserController@store",
      "as"=>"createUser"
    ]);
    //Edit Users
    Route::get("edit/{id}",[
      "uses"=>"UserController@edit",
      "as"=>"updateUser"
    ])->where('id','[0-9]+');
    Route::put("edit/{id}",[
      "uses"=>"UserController@update",
      "as"=>"updateUser"
    ])->where('id','[0-9]+');
    //display user's profile
    Route::get("profile/{id}",[
      "uses"=>"UserController@show",
      "as"=>"showUser"
    ])->where('id','[0-9]+');
    //check username exists or not
    Route::post("check/username",[
      "uses"=>"UserController@checkUname",
      "as"=>"check_uname"
    ]);
    //change password
    Route::get("change_password",[
      "uses"=>"UserController@editPassword",
      "as"=>"changePassword"
    ]);
    Route::put("change_password",[
      "uses"=>"UserController@updatePassword",
      "as"=>"changePassword"
    ]);
    // upload profile photo
    Route::put('upload_profile_pic/{id}',[
      'uses'=>'UserController@uploadProfilePhoto',
      'as'=>'uploadUserPhoto'
    ])->where('id','[0-9]+');
    //get all process in a specific table by a specific user
    Route::get("{id}/{table}",[
      'uses'=>'UserController@getAllUserLogs',
      'as'=>'showAllUserLog'
    ])->where(['id' => '[0-9]+', 'table' => '[a-z_]+']);
    // get all user logs in all tables
    Route::get("log/{id}/",[
      'uses'=>'UserController@allUserLogs',
      'as'=>'allUserLogs'
    ])->where(['id' => '[0-9]+']);
    // delete user
    Route::get("delete/{id}",[
      'uses'=>'UserController@destroy',
      'as'=>'deleteUser'
    ])->where(['id' => '[0-9]+']);
    // get all users
    Route::get("all",[
      'uses'=>'UserController@index',
      'as'=>'allUser'
    ]);
    Route::post("search",[
      'uses'=>'UserController@search',
      'as'=>'searchUser'
    ]);
  });
  //user_logs Routes
  Route::prefix('user_logs')->group(function(){
    Route::get("all",[
      'uses'=>'UserLogController@index',
      'as'=>'allLogs'
    ]);
    Route::get("{table}",[
      'uses'=>'UserLogController@indexTable',
      'as'=>'allTableLogs'
    ])->where(['table'=>'[A-Za-z_]+']);
  });
  /*
   *
   *
   ****Working Times Routes within THE SYSTEM
   *
   *
   */
   Route::prefix('working_times/system')->group(function(){
     // Add Working times
     Route::get('create',[
       'uses'=>'WorkingTimeController@create',
       'as'=>'addWorkingTime'
     ]);
     Route::post('create',[
       'uses'=>'WorkingTimeController@store',
       'as'=>'addWorkingTime'
     ]);
     // edit Working times
     Route::get('edit/{id}',[
       'uses'=>'WorkingTimeController@edit',
       'as'=>'updateWorkingTime'
     ])->where('id','[0-9]+');
     Route::put('edit/{id}',[
       'uses'=>'WorkingTimeController@update',
       'as'=>'updateWorkingTime'
     ])->where('id','[0-9]+');
     // delete Working times
     Route::get('delete/{id}',[
       'uses'=>'WorkingTimeController@destroy',
       'as'=>'deleteWorkingTime'
     ])->where('id','[0-9]+');
     //show all working_time
     Route::get('all',[
       'uses'=>'WorkingTimeController@index',
       'as'=>'allWorkingTime'
     ]);
   });
  /*
   *
   *
   ****Drugs Routes within THE SYSTEM
   *
   *
   */
   Route::prefix('medication/system')->group(function(){
      //ADD Drugs
      Route::get('create',[
        'uses'=>'DrugController@create',
        'as'=>'addSystemDrug'
      ]);
      Route::post('create',[
        'uses'=>'DrugController@store',
        'as'=>'addSystemDrug'
      ]);
      //Update drugs
      Route::get('edit/{id}',[
        'uses'=>'DrugController@edit',
        'as'=>'updateSystemDrug'
      ])->where('id','[0-9]+');
      Route::put('edit/{id}',[
        'uses'=>'DrugController@update',
        'as'=>'updateSystemDrug'
      ])->where('id','[0-9]+');
      //show all drugs for a specific diagnosis
      Route::get('all',[
        'uses'=>'DrugController@index',
        'as'=>'showAllSystemDrugs'
      ]);
      //Delete Drug
      Route::get('delete/{id}',[
        'uses'=>'DrugController@destroy',
        'as'=>'deleteSystemDrug'
      ])->where('id','[0-9]+');
      //Search Drug
      Route::post('search/',[
        'uses'=>'DrugController@searchDrug',
        'as'=>'searchSystemDrug'
      ]);
  });
  //get all case photos
  Route::get('case_photo/delete/{id}',[
    'uses'=>'DiagnoseController@deleteCasePhoto',
    'as'=>'deleteCasePhoto'
    ])->where('id','[0-9]+');
  //Patients Routes
  Route::prefix('patient')->group(function(){
      //Add Patient
      Route::get('add',[
        'uses'=>'PatientController@create',
        'as'=>'addPatient'
      ]);
      Route::post('add',[
        'uses'=>'PatientController@store',
        'as'=>'addPatient'
      ]);
      //Update Patient
      Route::get('edit/{id}',[
        'uses'=>'PatientController@edit',
        'as'=>'updatePatient'
      ])->where('id','[0-9]+');
      Route::put('edit/{id}',[
        'uses'=>'PatientController@update',
        'as'=>'updatePatient'
      ])->where('id','[0-9]+');
      // upload profile photo
      Route::put('upload_profile_pic/{id}',[
        'uses'=>'PatientController@uploadProfilePhoto',
        'as'=>'uploadPatientPhoto'
      ])->where('id','[0-9]+');
      //show ALL Patients
      Route::get('all',[
        'uses'=>'PatientController@index',
        'as'=>'allPatient'
      ]);
      //show patient profile
      Route::get('profile/{id}', [
        'uses'=>'PatientController@show',
        'as'=>'profilePatient'
      ])->where('id','[0-9]+');
      //SEARCH Patient
      Route::post('search',[
        'uses'=>'PatientController@search',
        'as'=>'searchPatient'
      ]);
      Route::get('search-not-found',[
        'uses'=>'PatientController@getSearch',
        'as'=>'searchResults'
      ]);
      //get all case photos
      Route::get('{id}/case_photo/gallery',[
        'uses'=>'PatientController@getCasePhotos',
        'as'=>'showCasePhotoPatient'
        ])->where('id','[0-9]+');
      //get all payments of specific patient
      Route::get('{id}/all/payments',[
        'uses'=>'PatientController@allPayments',
        'as'=>'allPaymentPatient'
        ])->where('id','[0-9]+');
      //get all payments of all patients
      Route::get('all/payments',[
        'uses'=>'PatientController@allPatientPayments',
        'as'=>'allPayments'
        ])->where('id','[0-9]+');
      //Delete Patient
      Route::get('delete/{id}',[
        'uses'=>'PatientController@destroy',
        'as'=>'deletePatient'
      ])->where('id','[0-9]+');
      //
      //
      // Diagnose Routes
      //
      //
      //
      Route::prefix('diagnosis')->group(function(){
          //Add Diagnose
          Route::get('add/{id}',[
            'uses'=>'DiagnoseController@create',
            'as'=>'addDiagnose'
          ])->where('id','[0-9]+');
          Route::post('add/{id}',[
            'uses'=>'DiagnoseController@store',
            'as'=>'addDiagnose'
          ])->where('id','[0-9]+');
          //update Diagnose
          Route::get('edit/{id}',[
            'uses'=>'DiagnoseController@edit',
            'as'=>'updateDiagnose'
          ])->where('id','[0-9]+');
          Route::put('edit/{id}',[
            'uses'=>'DiagnoseController@update',
            'as'=>'updateDiagnose'
          ])->where('id','[0-9]+');
          //show diagnose with all its visits and radiology and drugs and payments
          Route::get('display/{id}',[
            'uses'=>'DiagnoseController@show',
            'as'=>'showDiagnose'
          ])->where('id','[0-9]+');
          //show all diagnoses for specific patient
          Route::get('{id}/all/diagnosis',[
            'uses'=>'DiagnoseController@index',
            'as'=>'allDiagnosesPatient'
          ])->where('id','[0-9]+');
          //show all undone diagnoses for specific patient
          Route::get('{id}/all/undone/diagnosis',[
            'uses'=>'DiagnoseController@undoneDiagnosis',
            'as'=>'allUnDiagnosesPatient'
          ])->where('id','[0-9]+');
          //Delete Diagnose
          Route::get('delete/{id}',[
            'uses'=>'DiagnoseController@destroy',
            'as'=>'deleteDiagnose'
          ])->where('id','[0-9]+');
          //Add total_price
          Route::put('{id}/add/discount',[
            'uses'=>'DiagnoseController@addDiscount',
            'as'=>'addDiscount'
          ])->where('id','[0-9]+');
          //Add payment
          Route::put('{id}/add/payment',[
            'uses'=>'DiagnoseController@addPayment',
            'as'=>'addPayment'
          ])->where('id','[0-9]+');
          //Add payment
          Route::post('{id}/add/case_photo',[
            'uses'=>'DiagnoseController@addCasePhoto',
            'as'=>'addCasePhoto'
          ])->where('id','[0-9]+');
          //finish a diagnosis
          Route::put('/{id}/finish',[
            'uses'=>'DiagnoseController@finishDiagnose',
            'as'=>'finishDiagnose'
          ])->where('id','[0-9]+');
          //get all case photos
          Route::get('{id}/case_photo/gallery',[
            'uses'=>'DiagnoseController@getCasePhotos',
            'as'=>'showCasePhotoDiagnosis'
            ])->where('id','[0-9]+');

          //add tooth
          Route::get('teeth/add/{id}',[
            'uses'=>'DiagnoseController@addTeeth',
            'as'=>"addTeeth"
          ])->where('id','[0-9]+');
          Route::post('teeth/add/{id}',[
            'uses'=>'DiagnoseController@storeTeeth',
            'as'=>"addTeeth"
          ])->where('id','[0-9]+');
          //Delet tooth
          Route::get('tooth/delete/{id}',[
            'uses'=>'ToothController@destroy',
            'as'=>"deleteTeeth"
          ])->where('id','[0-9]+');
          /*
           *
           *
           ****Drugs Routes within Diagnosis
           *
           *
           */
          //ADD Drugs to Diagnosis
          // Route::get('{id}/add/medication',[
          //   'uses'=>'DiagnoseDrugController@create',
          //   'as'=>'addDrug'
          // ])->where('id','[0-9]+');
          Route::post('{id}/add/medication',[
            'uses'=>'DiagnoseDrugController@store',
            'as'=>'addDrug'
          ])->where('id','[0-9]+');
          //Update drugs
          Route::get('medication/edit/{id}',[
            'uses'=>'DiagnoseDrugController@edit',
            'as'=>'updateDrug'
          ])->where('id','[0-9]+');
          Route::put('medication/edit/{id}',[
            'uses'=>'DiagnoseDrugController@update',
            'as'=>'updateDrug'
          ])->where('id','[0-9]+');
          //show all drugs for a specific diagnosis
          Route::get('{id}/medications',[
            'uses'=>'DiagnoseDrugController@index',
            'as'=>'showAllDrugs'
          ])->where('id','[0-9]+');
          //Delete Drug
          Route::get('medication/delete/{id}',[
            'uses'=>'DiagnoseDrugController@destroy',
            'as'=>'deleteDrug'
          ])->where('id','[0-9]+');

          /*
           *
           *
           ****OralRadiology Routes
           *
           *
           */
          //ADD OralRadiology
          Route::get('{id}/add/oralradiology',[
            'uses'=>'OralRadiologyController@create',
            'as'=>'addOralRadiology'
          ])->where('id','[0-9+]');
          Route::post('{id}/add/oralradiology',[
            'uses'=>'OralRadiologyController@store',
            'as'=>'addOralRadiology'
          ])->where('id','[0-9]+');
          //update OralRadiology
          Route::get('oralradiology/edit/{id}',[
            'uses'=>'OralRadiologyController@edit',
            'as'=>'updateOralRadiology'
          ])->where('id','[0-9+]');
          Route::put('oralradiology/edit/{id}',[
            'uses'=>'OralRadiologyController@update',
            'as'=>'updateOralRadiology'
          ])->where('id','[0-9]+');
          //show all Radiology for specific diagnosis
          Route::get('{id}/all/oralradiology',[
            'uses'=>'OralRadiologyController@index',
            'as'=>'showAllOralRadiologies'
          ])->where('id','[0-9]+');
          //delete oralradiology
          Route::get('oralradiology/{id}/delete',[
            'uses'=>'OralRadiologyController@destroy',
            'as'=>'deleteOralRadiology'
          ])->where('id','[0-9]+');


          //Appointment Routes
          Route::prefix('visit')->group(function(){
            //get available appointments
            Route::post("avaliable/visits",[
              'uses'=>"AppointmentController@getAvailableTime",
              'as'=>'getAvailableTime'
            ]);
            //Add Appointments
            Route::get('add/{id}',[
              'uses'=>'AppointmentController@create',
              'as'=>'addAppointment'
              ])->where('id','[0-9]+');
            Route::post('add/{id}',[
              'uses'=>'AppointmentController@store',
              'as'=>'addAppointment'
            ])->where('id','[0-9]+');
            //Update Appointment
            Route::get('edit/{id}',[
              'uses'=>'AppointmentController@edit',
              'as'=>'updateAppointment'
            ])->where('id','[0-9]+');
            Route::put('edit/{id}',[
              'uses'=>'AppointmentController@update',
              'as'=>'updateAppointment'
            ])->where('id','[0-9]+');
            //show all Appointment today
            Route::get('all/{date?}',[
              'uses'=>'AppointmentController@index',
              'as'=>'allAppointment'
            ]);
            //show all Appointment of a diagnosis
            Route::get('all/diagnosis/{id}',[
              'uses'=>'AppointmentController@allWithinDiagnose',
              'as'=>'showAllDiagnoseAppointments'
            ])->where('id','[0-9]+');
            //show all Appointment of a patient
            Route::get('all/patient/{id}',[
              'uses'=>'AppointmentController@allWithinPatient',
              'as'=>'showAllPatientAppointments'
            ])->where('id','[0-9]+');
            Route::get('approve/{id}',[
              'uses'=>'AppointmentController@approve',
              'as'=>'approveAppointment'
            ])->where('id','[0-9]+');
            Route::get('end/{id}',[
              'uses'=>'AppointmentController@endAppointment',
              'as'=>'endAppointment'
            ])->where('id','[0-9]+');
            Route::get('cancel/{id}',[
              'uses'=>'AppointmentController@cancelAppointment',
              'as'=>'cancelAppointment'
            ])->where('id','[0-9]+');
            Route::get('check/state',[
              'uses'=>'AppointmentController@checkState',
              'as'=>'checkStateAppointment'
            ]);
            Route::get('get/visits/ajax',[
              'uses'=>'AppointmentController@ajaxGetVisits',
              'as'=>'ajaxGetVisits'
            ]);
            //delete Appointment
            Route::get('delete/{id}',[
              'uses'=>'AppointmentController@destroy',
              'as'=>'deleteAppointment'
              ])->where('id','[0-9]+');
            });
      });
  });

});
