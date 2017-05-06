<?php
namespace App\Http\Controllers;

use App\adminUsers;
use App\user_accounts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use DB;
use Image;
use Session;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminController extends Controller
{

	public function getActionUser($action,$user)
	{
		if($action=='edit')
		{

			$userData = adminUsers::where("username", $user)->first();

			$message = '<form role="form" id="edit-admin" method="post">
                      <div class="form-group-attached">
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="form-group form-group-default">
                              <label>Full Name</label>
                              <input type="text" value="'.$userData->fullname.'" name="fullname" id="fullname" class="form-control">
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-6">
                            <div class="form-group form-group-default">
                              <label>Username</label>
                              <input type="text" value="'.$userData->username.'" name="username" id="username" class="form-control">
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group form-group-default">
                              <label>Password</label>
                              <input type="text" name="password" id="password" class="form-control">
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-sm-6">
                            <div class="form-group form-group-default form-group-default-select2">
                              
                              <select name="access" class=" full-width" data-init-plugin="select2">
                              	<option value="">---Access Type---</option>
                                <option value="1">Super Admin</option>
                                <option value="2">Uni/School Heads</option>
                                <option value="3">Bursary Admin</option>
                                <option value="4">Main Administrator</option>
                                <option value="5">Administrator</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group form-group-default form-group-default-select2">
                              
                              <select name="privileges" class=" full-width" data-init-plugin="select2" multiple>
                              	<option value="">---Privileges---</option>
                                <option value="create">Create</option>
                                <option value="edit">Edit</option>
                                <option value="delete">Delete</option>
                                <option value="retrieve">Retrieve</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <button type="button" id="editAdmin" class="btn btn-primary btn-block m-t-5">Edit Admin</button>
                      </div>
                    </form>';

                    return response()->json(['status'=>1,'message'=>$message]);
		}
		else
		{

			$userData = adminUsers::where("username", $user_id)->first();

			if($userData->delete())
			{
				//creating success message
				$message = 'User '.$user_id.' Successfully Deleted';
				return redirect()->back()->with(["message"=>$message]);
			}
			else
			{
				$errors = new MessageBag;
				$error = new MessageBag(['errormessage' => ['Unable to Delete User']]);
				return redirect()->back()->withErrors($error);
			}	
		}
		

	}

	public function adminSignIn(Request $request)
	{
		//$password = md5($request['password']);

		if(Auth::guard('admin')->attempt(['username'=>$request['loginid'],'password'=>$request['password']]))
		{	
			
			$adminRecord = Auth::guard('admin')->user()->toArray();

			//1 = active , 2=suspended
 
			if($adminRecord['status']==1)
			{
				//creating session variable
				
				session(["accountDetails"=>$adminRecord,"userType"=>2,"AdminOn"=>1]);
				return redirect()->route('admin-dashboard');
				
			}
			else
			{
				$error = new MessageBag(['Account deactivated / not active']);
				return redirect()->back()->withErrors($error);
			}
		}
		else
		{
			$error = new MessageBag(['Username / Password invalid']);
			return redirect()->back()->withErrors($error);
		}
	}

	public function logoutUser(Request $request)
	{
		
		$request->session()->flush();

		Auth::logout();

		return redirect()->route('adminSignIn');
	}

	public function createUser(Request $request)
	{
		//validate inputs

		$this->validate($request, [
				'usertype'=>'required',
				'email'=>'required|email|unique:users',
				'password'=>'required|min:6',
				'fullname'=>'required'
			]);

		$fullname = $request['fullname'];
		$email = $request['email'];
		$password = bcrypt($request['password']);
		$userType = $request['usertype'];

		$user = new UserAccount();
		$user->fullname = $fullname;
		$user->email = $email;
		$user->password = $password;
		$user->usertype = $userType;

		if($user->save())
		{
			$message = 'New User '.$email.' Successfully Added';
			return redirect()->back()->with(["message"=>$message]);
		}
		else
		{
			$errors = new MessageBag;
			$error = new MessageBag(['errormessage' => ['Unable to add user']]);
			return redirect()->back()->withErrors($error);
		}
	}

	public function getAdminDashBoard()
	{
		return view('admin.dashboard');
	}

	public function getAdminCoursePage()
	{
		$course_information = DB::table('course_informations')->where("status","=",1)->get();
		$session = DB::table('session')->where("status","=",1)->get();
		$semester = DB::table('semester')->where("status","=",1)->get();
		$faculty = DB::table('faculty')->where("pid",">",0)->get();
		$level = DB::table('levels')->where("id",">",0)->get();
		return view('adminPortal.course-data',['course_info'=>$course_information, 'semester'=>$semester,'session'=>$session,'faculty'=>$faculty,'level'=>$level]);
	}

	public function getAdminCourseSetupPage()
	{
		$course_information = DB::table('course_informations')->where("status","=",1)->get();
		$session = DB::table('session')->where("status","=",1)->get();
		$semester = DB::table('semester')->where("status","=",1)->get();
		$faculty = DB::table('faculty')->where("pid",">",0)->get();
		$level = DB::table('levels')->where("id",">",0)->get();
		return view('adminPortal.course-setup',['course_info'=>$course_information, 'semester'=>$semester,'session'=>$session,'faculty'=>$faculty,'level'=>$level,'responseState'=>0,'message'=>'']);
	}

	public function getAdminPaymentSetupPage()
	{

		$session = DB::table('session')->where("status","=",1)->get();
		$semester = DB::table('semester')->where("status","=",1)->get();
		$faculty = DB::table('faculty')->where("pid",">",0)->get();
		$level = DB::table('levels')->where("id",">",0)->get();
		$paymentCategory = DB::table('payment_category_lists')->where("status",1)->get();

		return view('adminPortal.payment-setup',['semester'=>$semester,'session'=>$session,'faculty'=>$faculty,'level'=>$level,'paymentCategory'=>$paymentCategory,'responseState'=>0,'message'=>'']);
	}

	public function getAdminUsers()
	{
		$adminusers = adminUsers::where("username","!=",Auth::guard('admin')->user()->username)->get();
		return view('adminPortal.adminusers',['admins'=>$adminusers]);
	}

	public function getPaymentCategory()
	{
		$paymentCategory = DB::table('payment_category_lists')->where("status",1)->get();
		return view('adminPortal.paymentCategoryList',['paymentCateList'=>$paymentCategory]);
	}

	public function submitFormData(Request $request, $formNow)
	{
		
		if($formNow=='addAdmin')
		{

			$this->validate($request, [
			'param.0.name'=>'required',
			'param.1.name'=>'required|unique:admin_users,username',
			'param.2.name'=>'required',
			'param.3.name'=>'required',
			'param.4.name'=>'required',
			'param.5.name'=>'required'
			]);

			$admin = new adminUsers();

			$admin->fullname = $request['param.0.value'];
			$admin->username = $request['param.1.value'];
			$admin->password = bcrypt($request['param.2.value']);
			$admin->access = $request['param.3.value'];
			$admin->privilege = $request['param.4.value'];
			$admin->status = 1;


			if($admin->save())
			{
				return response()->json(['status'=>1,"message"=>"<span style='color:green'> ( Admin Successfully Added )</span>"]);
			}
			else
			{
				return response()->json(['status'=>2,"message"=>"<span style='color:red'>Unable to add Admin</span>"]);
			}
		}
		elseif($formNow == 'addCourseData')
		{
			//checking file load type
			if($request['param.9.value']=='single')
			{
				$this->validate($request, [
				'param.0.name'=>'required',
				'param.1.name'=>'required',
				'param.2.name'=>'required',
				'param.3.name'=>'required',
				'param.4.name'=>'required',
				'param.5.name'=>'required',
				'param.6.name'=>'required',
				'param.7.name'=>'required',
				'param.8.name'=>'required'
				]);

				$existCodeTitle = DB::table('course_informations')->select('id')->where([
										['code','=',"'".$request['param.2.value']."'"],
										['title','=',"'".$request['param.0.value']."'"]
								])->first();

				if(count($existCodeTitle) > 0)
				{
					return response()->json(['status'=>2,"message"=>"<span style='color:red'>This Course Code and Title already exist together.</span>"]);
				}
				else
				{

					$cInfo = new course_information();

					$cInfo->code = $request['param.2.value'];
					$cInfo->level = $request['param.1.value'];
					$cInfo->title = $request['param.0.value'];
					$cInfo->entry_session = $request['param.3.value'];
					$cInfo->faculty = $request['param.6.value'];
					$cInfo->department = $request['param.7.value'];
					$cInfo->entry_semester = $request['param.4.value'];
					$cInfo->status = $request['param.5.value'];
					$cInfo->added_by = Auth::guard('admin')->user()->username;

					if($cInfo->save())
					{
						return response()->json(['status'=>1,"message"=>"<span style='color:green; font-weight:bold'> ( Course Successfully Added )</span>"]);
					}
					else
					{
						return response()->json(['status'=>2,"message"=>"<span style='color:red'>Unable to add Course Information</span>"]);
					}

				}
				
			}
			else
			{

			}
		}
		elseif($formNow == 'addCouseInformation')
		{
			$this->validate($request, [
				'session'=>'required',
				'faculty'=>'required',
				'dept'=>'required',
				'level'=>'required',
				'semester'=>'required',
				'course_info_file'=>'required'
			]);

			$startRow = 1;

			$div = '';

			$f = fopen($request->file('course_info_file'), 'r');

			$theArray = array();

			$processed = 0;

			
						
			while(($rf = fgetcsv($f,700)) !=FALSE)
			{							
				if(!empty($rf[0]) && !empty($rf[1]) && !empty($rf[2]) && !empty($rf[3]))
				{					
					if($startRow == 1)
					{
						//checking header content
						if(strtolower(trim(str_replace(" ",'',$rf[0])))!="code" || strtolower(trim($rf[1]))!="title" || strtolower(trim($rf[2]))!="unit" || strtolower(trim($rf[3]))!="type")
						{
							return response()->json(['status'=>0, 'message'=>'Invalid File Header Found, Check the format given for correction']);
							exit;
						}
						else
						{
							$startRow++;		
							continue;			
						}
					}
					else
					{
						if(empty($rf[0]))
						{
							$startRow++;
							continue;
						}
						else
						{

							$courseData = new course_information();
							$courseData->code = $rf[0];
							$courseData->title = $rf[1];
							$courseData->entry_session = $request['session'];
							$courseData->entry_semester = $request['semester'];
							$courseData->faculty = $request['faculty'];
							$courseData->department = $request['dept'];
							$courseData->level = $request['level'];
							$courseData->status = 1;
							$courseData->added_by = Auth::guard('admin')->user()->username;
							$courseData->date_added = gmdate("Y h:i:s");
							
							if($courseData->save())
							{
								$processed++;
							}
							$startRow++;
						}
					}
									
				}	
			}

			if($processed > 0)
			{
				
				//$arrayCollection = collect([$theArray]);
				//print_r($arrayCollection[0][0]); die;
				$message = 'Course Information Successfully Added';
				return redirect()->back()->with(["message"=>$message]);
			}
			else
			{
				$error = new MessageBag(['errormessage' => ['No course information added']]);
				return redirect()->back()->withErrors($error);
			}
		}
		elseif($formNow == 'DisplaySetUpCourseFields')
		{

			$faculty = $request['param.0.value'];
			$dept = $request['param.1.value'];
			$level = $request['param.2.value'];
			$semester = $request['param.3.value'];
			$session = $request['param.4.value'];
			$token = $request['param.5.value'];

			//checking if course has been setup for this department in respect to Session & Semester

			if($courseData = course_setup::select('*')->where([
					["faculty","=",$faculty],
					["department","=",$dept],
					["level","=",$level],
					["session","=",$session],
					["semester","=",$semester]
				])->first())
			{
				$message = "<div>PREVIOUS COURSE SETUP FOUND, YOU CAN UPDATE THIS COURSE INFORMATION</div>";
				$message.="<table cellspacing='0' cellpadding='0'>";
				
				$message.="<form method='post' id='courseInfoSetupEdit'><input type='hidden' name='countData' value='".count($courseData)."'/><table cellspacing='0' cellpadding='0' class='table table-striped'>";
				$message.="<thead><tr>
							<th>S/N</th>
							<th>&nbsp;</th>
							<th>Course Code</th>
							<th>Couse Title</th>
							<th>Unit</th>
							<th>Type</th>
						   </tr></thead><tbody>";
				$start = 1;

				foreach($courseData as $courseInfo)
				{
					$message.="<tr>
					<td>".$start."</td>
					<td><input type='checkbox' name='checkbox-".$start."' value='".$courseInfo->id."'/></td>
					<td>".$courseInfo->code." <input type='hidden' name='courseId-".$start."' value='".$courseInfo->id."'/></td>
					<td>".$courseInfo->title."</td>";

					//unitLength
					$message.="<td>".'
                    <select name="unit-'.$start.'" id="unit-'.$start.'" data-init-plugin="select2">
                      <option value="">---Select---</option>';

                      foreach(config('app.unitLength') as $ulength)
                      {
                      	$message.="<option value='".$ulength."'>".$ulength."</option>";
                      }
                    $message.="</select></td>";

					$message.="<td>".'
                    <select name="courseType-'.$start.'" id="courseType-'.$start.'" data-init-plugin="select2">
                      <option value="">---Select---</option>';

                      foreach(config('app.courseType') as $lev)
                      {
                      	$message.="<option value='".$lev."'>".$lev."</option>";
                      }                      
                                                                                 
                    $message.='</select>'."</td></tr>";



					$start++;
				}

				$button = "<button type='button' onclick='javascript:addCourseForm.init()' id='setUpCourseForm' class='btn btn-primary btn-block m-t-5'>SET UP COURSE</button>";
				
				$message.='</tbody></table><div class="col-sm-3">
							'.$button.'
						</div>
							
						</div>
						
						<input type="hidden" value="'.$token.'" name="_token" />
						</form>';
			}
			else
			{
				//GETTING COURSE INFORMATION FOR THIS DEPARTMENT


				$courseInformation = course_information::select(['id','title','code'])->where([
					["faculty","=",$faculty],
					["department","=",$dept],
					["level","=",$level],
					["entry_semester","=",$semester],
					["status","=",1]
				])->get();

				if(count($courseInformation) > 0)
				{

					//$action = config('app.application_url');

					//$linkPath = 'http://gportal.app:8000/admin/dataForm/process/addCourseSetupForm';

					$message = "<div>SETUP REGISTERABLE COURSES FOR THIS DEPARTMENT <span id='course-set-info'></span></div>";
					$message.="<form method='post' id='courseInfoSetup'><input type='hidden' name='countData' value='".count($courseInformation)."'/><table cellspacing='0' cellpadding='0' class='table table-striped'>";
					$message.="<thead><tr>
								<th>S/N</th>
								<th>&nbsp;</th>
								<th>Course Code</th>
								<th>Couse Title</th>
								<th>Unit</th>
								<th>Type</th>
							   </tr></thead><tbody>";
					$start = 1;
					foreach($courseInformation as $courseInfo)
					{
						$message.="<tr>
						<td>".$start."</td>
						<td><input type='checkbox' name='checkbox-".$start."' value='".$courseInfo->id."'/></td>
						<td>".$courseInfo->code." <input type='hidden' name='courseId-".$start."' value='".$courseInfo->id."'/></td>
						<td>".$courseInfo->title."</td>";

						//unitLength
						$message.="<td>".'
	                    <select name="unit-'.$start.'" id="unit-'.$start.'" data-init-plugin="select2">
	                      <option value="">---Select---</option>';

	                      foreach(config('app.unitLength') as $ulength)
	                      {
	                      	$message.="<option value='".$ulength."'>".$ulength."</option>";
	                      }
	                    $message.="</select></td>";

						$message.="<td>".'
	                    <select name="courseType-'.$start.'" id="courseType-'.$start.'" data-init-plugin="select2">
	                      <option value="">---Select---</option>';

	                      foreach(config('app.courseType') as $lev)
	                      {
	                      	$message.="<option value='".$lev."'>".$lev."</option>";
	                      }                      
	                                                                                 
	                    $message.='</select>'."</td></tr>";



						$start++;
					}

					$button = "<button type='button' onclick='javascript:addCourseForm.init()' id='setUpCourseForm' class='btn btn-primary btn-block m-t-5'>SET UP COURSE</button>";
					
					$message.='</tbody></table><div class="col-sm-3">
								'.$button.'
							</div>
								
							</div>
							
							<input type="hidden" value="'.$token.'" name="_token" />
							</form>';


				}
				else
				{
					$message = "<span style='color:#F00; font-weight:bold'>NO COURSE INFORMATION FOUND...</span>";
				}


			}

			return response()->json(['status'=>1,"message"=>stripslashes($message)]);
			
		}
		elseif($formNow == 'addCourseSetupForm')
		{
					/*
					param[0][name]:faculty
					param[0][value]:04
					param[1][name]:dept
					param[1][value]:0404
					param[2][name]:level
					param[2][value]:100L
					param[3][name]:semester
					param[3][value]:f-sem
					param[4][name]:session
					param[4][value]:2016-2017
					param[5][name]:_token
					param[5][value]:L3EzAw8NZccHzXV6maF9UJn7q5WJABUw3qgyjBpE
					param[6][name]:countData
					param[6][value]:2
					param[7][name]:checkbox-1
					param[7][value]:3
					param[8][name]:courseId-1
					param[8][value]:3
					param[9][name]:unit-1
					param[9][value]:6
					param[10][name]:courseType-1
					param[10][value]:Core
					param[11][name]:checkbox-2
					param[11][value]:4
					param[12][name]:courseId-2
					param[12][value]:4
					param[13][name]:unit-2
					param[13][value]:8
					param[14][name]:courseType-2
					param[14][value]:Mandatory
					param[15][name]:_token
					param[15][value]:L3EzAw8NZccHzXV6maF9UJn7q5WJABUw3qgyjBpE
					_token:L3EzAw8NZccHzXV6maF9UJn7q5WJABUw3qgyjBpE
					*/

					$faculty = $request['param.0.value'];
					$dept = $request['param.1.value'];
					$level = $request['param.2.value'];
					$semester = $request['param.3.value'];
					$session = $request['param.4.value'];

					$startField = 7;

					$arrayData = array();

					for($d=1;$d<=$request['param.6.value'];$d++)
					{

						if($request["param.".$startField.".name"] == 'checkbox-'.$d)
						{
							//process for dependent parameters
							$cs = $startField+1;
							$un = $startField+2;
							$co = $startField+3;

							$courseId = $request['param.'.$cs.'.value'];
							$unit = $request['param.'.$un.'.value'];
							$courseType = $request['param.'.$co.'.value'];

							$startField = $startField + 4;

							$arrayNow = array($courseId=>array($unit,$courseType));
							
							array_push($arrayData,$arrayNow);
							
						}
						else
						{
							$startField = $startField + 3;
						}
					}

					if(count($arrayData) > 0)
					{
						//checking if a record already exist

						$existSetup = course_setup::select('id')->where([
										['faculty','=',$request['param.0.value']],
										['department','=',$request['param.1.value']],
										['session','=',$request['param.4.value']],
										['semester','=',$request['param.3.value']],
										['level','=',$request['param.2.value']]
							])->first();
						//insert record

						if(empty($existSetup))
						{
							$courseSetUp = new course_setup();
							$courseSetUp->faculty = $request['param.0.value'];
							$courseSetUp->department = $request['param.1.value'];
							$courseSetUp->session = $request['param.4.value'];
							$courseSetUp->semester = $request['param.3.value'];
							$courseSetUp->level = $request['param.2.value'];
							$courseSetUp->can_register = json_encode($arrayData);
							if($courseSetUp->save())
							{

								return response()->json(['status'=>1,"message"=>"<span style='color:green; font-weight:bold'> ( Course Information Successfully Added)</span>"]);
							}
							else
							{
								return response()->json(['status'=>2,"message"=>"<span style='color:red'>Unable to add Course Information</span>"]);
							}
						}
						else
						{
							return response()->json(['status'=>2,"message"=>"<span style='color:red'>Course Information ALready setup for this selection</span>"]);
						}
						
					}
					else
					{
						return response()->json(['status'=>2,"message"=>"<span style='color:red'>No Record Selected to Add</span>"]);
					}

					
		}
		elseif($formNow == 'addPaymentSetup')
		{
					
			$faculty = $request['param.0.value'];
			$dept = $request['param.1.value'];
			$level = $request['param.2.value'];
			$session = $request['param.3.value'];
            $paymentList = $request['param.4.value'];

			$startField = 5;

			$arrayData = array();

			for($d=1;$d<=$paymentList;$d++)
			{
                $vNow = $d.'_chk';

				if($request["param.".$startField.".name"]==$vNow)
				{
					//process for dependent parameters
                    $fv = $startField+1;

                    $feeTypeId = $request["param.".$startField.".value"];

					$feeValue = $request['param.'.$fv.'.value'];

					$startField = $startField + 2;

					$arrayNow = array($feeTypeId=>$feeValue);
					
					array_push($arrayData,$arrayNow);
					
				}
				else
				{
					$startField = $startField + 1;
				}
			}



			if(count($arrayData) > 0)
			{
				//checking if a record already exist

				$existSetup = payments_schedule::select('id')->where([
								['fac_id','=',$faculty],
								['dept_id','=',$dept],
								['session','=',$session],
								['current_level','=',$level]
					])->first();
				//insert record

				if(empty($existSetup))
				{
					$paymentSetup = new payments_schedule();
					$paymentSetup->fac_id = $faculty;
					$paymentSetup->dept_id = $dept;
					$paymentSetup->session = $session;
					$paymentSetup->current_level = $level;
					$paymentSetup->payment_info = json_encode($arrayData);

					if($paymentSetup->save())
					{

						return response()->json(['status'=>1,"message"=>"<span style='color:green; font-weight:bold'> ( Payment Information Successfully Added)</span>"]);
					}
					else
					{
						return response()->json(['status'=>2,"message"=>"<span style='color:red'>Unable to add Payment Information</span>"]);
					}
				}
				else
				{
					return response()->json(['status'=>2,"message"=>"<span style='color:red'>Payment Information already setup for this selection</span>"]);
				}
				
			}
			else
			{
				return response()->json(['status'=>2,"message"=>"<span style='color:red'>No payment data entered</span>"]);
			}

					
		}

	}
}
?>