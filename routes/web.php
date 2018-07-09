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
  Route::get('/home', function () {
      return view('home');
  })->name('home');
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
    ])->where(['id' => '[0-9]+', 'table' => '[a-z]+']);
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
    ])->where(['table'=>'[A-Za-z]+']);
  });
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
          Route::put('{id}/add/total_price',[
            'uses'=>'DiagnoseController@addTotalPrice',
            'as'=>'addTotalPrice'
          ])->where('id','[0-9]+');
          //Add payment
          Route::put('{id}/add/payment',[
            'uses'=>'DiagnoseController@addPayment',
            'as'=>'addPayment'
          ])->where('id','[0-9]+');
          //finish a diagnosis
          Route::put('/{id}/finish',[
            'uses'=>'DiagnoseController@finishDiagnose',
            'as'=>'finishDiagnose'
          ])->where('id','[0-9]+');
          /*
           *
           *
           ****Drugs Routes
           *
           *
           */
          //ADD Drugs
          Route::get('{id}/add/drug',[
            'uses'=>'DrugController@create',
            'as'=>'addDrug'
          ])->where('id','[0-9]+');
          Route::post('{id}/add/drug',[
            'uses'=>'DrugController@store',
            'as'=>'addDrug'
          ])->where('id','[0-9]+');
          //Update drugs
          Route::get('drug/edit/{id}',[
            'uses'=>'DrugController@edit',
            'as'=>'updateDrug'
          ])->where('id','[0-9]+');
          Route::put('drug/edit/{id}',[
            'uses'=>'DrugController@update',
            'as'=>'updateDrug'
          ])->where('id','[0-9]+');
          //show all drugs for a specific diagnosis
          Route::get('{id}/drugs',[
            'uses'=>'DrugController@index',
            'as'=>'showAllDrugs'
          ])->where('id','[0-9]+');
          //Delete Drug
          Route::get('drug/{id}',[
            'uses'=>'DrugController@destroy',
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
            ])->where('id'.'[0-9]+');
            Route::put('edit/{id}',[
              'uses'=>'AppointmentController@update',
              'as'=>'updateAppointment'
            ])->where('id','[0-9]+');
            //show all Appointment today
            Route::get('all/{date?}',[
              'uses'=>'AppointmentController@index',
              'as'=>'allAppointment'
            ]);
            //delete Appointment
            Route::delete('delete/{id}',[
              'uses'=>'AppointmentController@destroy',
              'as'=>'deleteAppointment'
              ])->where('id','[0-9]+');
            });
      });
  });

});
