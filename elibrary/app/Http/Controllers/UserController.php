<?php
namespace App\Http\Controllers;

use App\user_accounts;
use App\library_categories;
use App\library_data;
use App\library_subcategory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use DB;
use Image;
use Illuminate\Pagination\LengthAwarePaginator;


class UserController extends Controller
{

	public function getLoginPage() //using
	{
		if(empty(Auth::user()))
		{
			return view('intro');
		}
		else
		{
			return redirect()->route('home');
		}
	}

	public function getDeleteUser($user_id)
	{
		$userData = UserAccount::where("email", $user_id)->first();

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

	public function UserSignUp(Request $request)
	{

		$this->validate($request, [
				'username'=>'required|max:20|unique:user_accounts',
				'email'=>'required|email|unique:user_accounts',
				'confirm-password' => 'required|same:password',
				'password'=>'required|min:6'
			]);

		$userCanProceed = DB::table('gportal_students_data')->select('student_id')->where("student_id",$request['username'])->value('student_id');

		if(!empty($userCanProceed) && $userCanProceed===$request['username']) //update 
		{
			$username = $request['username'];
			$email = $request['email'];
			$password = bcrypt($request['password']);
			$token = $request['_token'];

			$regData = new UserAccount();
			$regData->username=$username;
			$regData->password=$password;
			$regData->student_status=1;
			$regData->email=$email;
			$regData->remember_token=$token;

			if($regData->save())
			{
				return redirect()->back()->with(["message"=>"Account Successfully Created, Please Login"]);
			}
			else
			{
				$error = new MessageBag(['Error:Unable to create account']);
				return redirect()->back()->withErrors($error);
			}
		}
		else
		{
			$error = new MessageBag(['Access Denied::']);
			return redirect()->back()->withErrors($error);
		}
		
	}

	public function UserSignIn(Request $request) //using
	{

		$this->validate($request, [
				'loginid'=>'required',
				'password'=>'required'
			]);

		if(Auth::attempt(['userid'=>$request['loginid'],'password'=>$request['password']]))
		{	
			//1 = active student, 2=graduated, 3=suspended, 4=expelled;

			if(Auth::user()->account_status==1)
			{
				session(["accountDetails"=>Auth::user()->toArray()]);

				return redirect()->route('home');
			}
			else
			{
				
				if(Auth::user()->std_status->first()==3)
				{
					$error = new MessageBag(['This account has been suspended, contact appropriate school authority']);
				}
				elseif(Auth::user()->std_status->first()==4)
				{
					$error = new MessageBag(['Account deactivated or student expelled']);
				}

				return redirect()->back()->withErrors($error);
			}
		}
		else
		{
			
			$error = new MessageBag(['Username / Password invalid']);

			return redirect()->back()->withErrors($error);
			
		}
	}

	public function logoutAccount(Request $request)
	{
		
		$request->session()->flush();

		Auth::logout();

		return redirect()->route('intro');
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
			$error = new MessageBag(['errormessage' => ['Unable to add user']]);
			return redirect()->back()->withErrors($error);
		}
	}

	public function getDashBoard()
	{
		$availableData = library_data::select('*')->where([['id','>',0],['status','=',1]])->paginate();
		return view('view.dashboard',["libraryContents"=>$availableData]);
	}

	public function submitFormData(Request $request, $formNow)
	{
		if($formNow == 'personal_data')
		{
			$this->validate($request, [
				'surname'=>'required',
				'othernames'=>'required',
				'status'=>'required',
				'gender'=>'required',
				'date_of_birth'=>'required',
				'country_code'=>'required',
				'mobile'=>'required|numeric',
				'email'=>'required|email',
				'blood_group'=>'required',
				'marital_status'=>'required',
				'religion'=>'required',
				'country'=>'required',
				'state'=>'required',
				'local_government'=>'required',
				'home_address'=>'required',
			]);
			
			$othernames = explode(" ",$request['othernames']);
			
			if(count($othernames)==1)
			{
				$othernames[1] ='';
			}

			$pData = new userBiodata();
			
			$pData->student_id = Auth::user()->username;
			$pData->surname = $request['surname'];
			$pData->middle_name = $othernames[1];
			$pData->last_name = $othernames[0];
			$pData->title = $request['status'];
			$pData->gender = $request['gender'];
			$pData->dob = $request['date_of_birth'];
			$pData->mobile = $request['country_code']." ".$request['mobile'];
			$pData->email = $request['email'];
			$pData->blood_group = $request['blood_group'];
			$pData->marital_status = $request['marital_status'];
			$pData->religion = $request['religion'];
			$pData->nationality = $request['country'];
			$pData->state_of_origin = $request['state'];
			$pData->lga = $request['local_government'];
			$pData->home_address = $request['home_address'];

			if($pData->save())
			{
				//update personal data record, both in session and db.
				
				DB::table('gportal_students_data')->where("student_id", Auth::user()->username)->update(['personal_data'=>1]);

				$message = 'Record Successfully Saved, Kindly Complete the Information Below.';

				//redirect to academic information form
				return redirect()->route('udg-biodata-form',['formType'=>'undergraduate-biodata-form','formNow'=>'academic-information','message'=>$message]);

			}
			else
			{
				$error = new MessageBag(['errormessage' => ['Registration not successful']]);
				return redirect()->back()->withErrors($error);
			}
		}
		elseif($formNow == 'academic_data')
		{
			$this->validate($request,[
				'faculty'=>'required',
				'dept'=>'required',
				'course'=>'required',
				'current_level'=>'required',
				'entry_session'=>'required',
				'entry_year'=>'required',
				'mode_admission'=>'required'
			]);

			$acaObj = new userAcademicInfo();

			$acaObj->student_id = Auth::user()->username;
			$acaObj->year_admitted = $request["entry_year"];
			$acaObj->session_admitted = $request["entry_session"];
			$acaObj->current_level = $request["current_level"];
			$acaObj->faculty = $request["faculty"];
			$acaObj->department = $request["dept"];
			$acaObj->mode_of_admission = $request["mode_admission"];
			$acaObj->remember_token = $request["_token"];

			if($acaObj->save())
			{
				//update academic column in table
				DB::table('gportal_students_data')->where("student_id", Auth::user()->username)->update(['academic_data'=>1]);

				$message = 'Record Successfully Saved, Kindly Complete the Information Below.';

				//redirect to academic information form
				return redirect()->route('udg-biodata-form',['formType'=>'undergraduate-biodata-form','formNow'=>'family-information','message'=>$message]);
			}
			else
			{
				$error = new MessageBag(['errormessage' => ['Registration not successful']]);
				return redirect()->back()->withErrors($error);
			}

		}
		elseif($formNow == 'family_data')
		{
			$this->validate($request, [
				'father_name'=>'required',
				'father_occp'=>'required',
				'father_addr'=>'required',
				'father_mobile'=>'required',
				'mother_name'=>'required',
				'mother_occp'=>'required',
				'mother_addr'=>'required',
				'mother_mobile'=>'required',
				'sponsor_name'=>'required',
				'sponsor_occp'=>'required',
				'sponsor_relationship'=>'required',
				'sponsor_mobile'=>'required',
				'nok_name'=>'required',
				'nok_relationship'=>'required',
				'nok_address'=>'required',
				'nok_mobile'=>'required'
			]);

			$familyInfo = new userGuardianInfo();

			$familyInfo->student_id = Auth::user()->username;
			$familyInfo->father_name = $request['father_name'];
			$familyInfo->father_address = $request['father_addr'];
			$familyInfo->father_mobile = $request['father_mobile'];
			$familyInfo->father_occp = $request['father_occp'];

			$familyInfo->mother_name = $request['mother_name'];
			$familyInfo->mother_address = $request['mother_addr'];
			$familyInfo->mother_mobile = $request['mother_mobile'];
			$familyInfo->mother_occp = $request['mother_occp'];

			$familyInfo->nok_name = $request['nok_name'];
			$familyInfo->nok_address = $request['nok_address'];
			$familyInfo->nok_mobile = $request['nok_mobile'];
			$familyInfo->nok_relationship = $request['nok_relationship'];

			$familyInfo->sponsor_name = $request['sponsor_name'];
			$familyInfo->sponsor_relationship = $request['sponsor_relationship'];
			$familyInfo->sponsor_mobile = $request['sponsor_mobile'];
			$familyInfo->sponsor_occp = $request['sponsor_occp'];

			$familyInfo->remember_token = $request['_token'];

			if($familyInfo->save())
			{
				//update personal data record, both in session and db.
				
				DB::table('gportal_students_data')->where("student_id", Auth::user()->username)->update(['family_data'=>1]);

				$message = 'Record Successfully Saved, Kindly Complete the Information Below.';

				//redirect to academic information form
				return redirect()->route('udg-biodata-form',['formType'=>'undergraduate-biodata-form','formNow'=>'educational-information','message'=>$message]);

			}
			else
			{
				$error = new MessageBag(['errormessage' => ['Registration not successful']]);
				return redirect()->back()->withErrors($error);
			}

		}
		elseif($formNow=="education_data")
		{
			$this->validate($request,[
				'pr_name_1'=>'required',
				'year_from_1'=>'required',
				'year_to_1'=>'required',
				'pr_cert_1'=>'required',
				'sec_name_1'=>'required',
				'sec_year_from_1'=>'required',
				'sec_year_to_1'=>'required',
				'sec_cert_1'=>'required',
			]);

			$primaryData1 = array(
								"school name"=>$request['pr_name_1'],
								"year"=>$request['year_from_1']." to ".$request['year_to_1'],
								"certificate"=>$request['pr_cert_1']
							);

			$primaryData2 = array(
								"school name"=>$request['pr_name_2'],
								"year"=>$request['year_from_2']." to ".$request['year_to_2'],
								"certificate"=>$request['pr_cert_2']
							);

			$primaryData3 = array(
								"school name"=>$request['pr_name_3'],
								"year"=>$request['year_from_3']." to ".$request['year_to_3'],
								"certificate"=>$request['pr_cert_3']
							);

			$secondaryData1 = array(
								"school name"=>$request['sec_name_1'],
								"year"=>$request['sec_year_from_1']." to ".$request['sec_year_to_1'],
								"certificate"=>$request['sec_cert_1']
							);

			$secondaryData2 = array(
								"school name"=>$request['sec_name_2'],
								"year"=>$request['sec_year_from_2']." to ".$request['sec_year_to_2'],
								"certificate"=>$request['sec_cert_2']
							);

			$secondaryData3 = array(
								"school name"=>$request['sec_name_3'],
								"year"=>$request['sec_year_from_3']." to ".$request['sec_year_to_3'],
								"certificate"=>$request['sec_cert_3']
							);

			$tertiaryData1 = array(
								"school name"=>$request['ter_name_1'],
								"year"=>$request['ter_year_from_1']." to ".$request['ter_year_to_1'],
								"certificate"=>$request['ter_cert_1']
							);

			$tertiaryData2 = array(
								"school name"=>$request['ter_name_2'],
								"year"=>$request['ter_year_from_2']." to ".$request['ter_year_to_2'],
								"certificate"=>$request['ter_cert_2']
							);

			$tertiaryData3 = array(
								"school name"=>$request['ter_name_3'],
								"year"=>$request['ter_year_from_3']." to ".$request['ter_year_to_3'],
								"certificate"=>$request['ter_cert_3']
							);

			$primary1 = json_encode($primaryData1);
			$primary2 = json_encode($primaryData2);
			$primary3 = json_encode($primaryData3);

			$secondary1 = json_encode($secondaryData1);
			$secondary2 = json_encode($secondaryData2);
			$secondary3 = json_encode($secondaryData3);

			$tertiary1 = json_encode($tertiaryData1);
			$tertiary2 = json_encode($tertiaryData2);
			$tertiary3 = json_encode($tertiaryData3);

			$eduData = new  userEducationHistory();

			$eduData->student_id = Auth::user()->username;
			$eduData->primary_1 = $primary1;
			$eduData->primary_2 = $primary2;
			$eduData->primary_3 = $primary3;

			$eduData->secondary_1 = $secondary1;
			$eduData->secondary_2 = $secondary2;
			$eduData->secondary_3 = $secondary3;

			$eduData->tertiary_1 = $tertiary1;
			$eduData->tertiary_2 = $tertiary2;
			$eduData->tertiary_3 = $tertiary3;

			$eduData->remember_token = $request['_token'];

			if($eduData->save())
			{
				//update academic column in table
				DB::table('gportal_students_data')->where("student_id", Auth::user()->username)->update(['education_data'=>1]);

				$message = 'Record Successfully Saved, Kindly Complete the Information Below.';

				//redirect to academic information form
				return redirect()->route('udg-biodata-form',['formType'=>'undergraduate-biodata-form','formNow'=>'result-obtained','message'=>$message]);
			}
			else
			{
				$error = new MessageBag(['errormessage' => ['Registration not successful']]);
				return redirect()->back()->withErrors($error);
			}
		}
		elseif($formNow=="result_data")
		{
			$this->validate($request,[
				"schl_name_1" =>"required",
				"exam_type_1"=>"required",
				"exam_number_1"=>"required",
				"exam_period_1"=>"required",
				"exam_year_1"=>"required"
			]);	

			$examInfo_1 = array(
						"school_name"=>$request['schl_name_1'],
						"exam_type"=>$request['exam_type_1'],
						"exam_number"=>$request['exam_number_1'],
						"exam_period"=>$request['exam_period_1'],
						"exam_year"=>$request['exam_year_1']
					);

			$result_info_1 = array();

			
			for($ri=1;$ri<=9;$ri++)
			{
				if(!empty($request["ols".$ri]))
				{
					array_merge($result_info_1,array($request["ols".$ri]=>$request["olg".$ri]));
				}
			}

			$examInfo_2 = array(
						"school_name"=>$request['schl_name_2'],
						"exam_type"=>$request['exam_type_2'],
						"exam_number"=>$request['exam_number_2'],
						"exam_period"=>$request['exam_period_2'],
						"exam_year"=>$request['exam_year_2']
					);

			$result_info_2 = array();

			
			for($ri=1;$ri<=9;$ri++)
			{
				if(!empty($request["ols2".$ri]))
				{
					array_merge($result_info_2,array($request["ols2".$ri]=>$request["olg2".$ri]));
				}
			}

			if($request['schl_name_2']!='' && $request['exam_type_2']!='' && $request['exam_number_2']!='' && $request['exam_period_2']!='' && $request['exam_year_2']!='')
			{
				$sitting = 2;
			}
			else{
				$sitting = 1;
			}

			$resultData = new userOlevelResult();

			$resultData->student_id = Auth::user()->username;
			$resultData->exam_info_1 = json_encode($examInfo_1);
			$resultData->result_data_1 = json_encode($result_info_1);
			$resultData->exam_info_2 = json_encode($examInfo_2);
			$resultData->result_data_2 = json_encode($result_info_2);
			$resultData->number_of_sitting = $sitting;
			$resultData->remember_token = $request['_token'];

			if($resultData->save())
			{
				//update academic column in table
				DB::table('gportal_students_data')->where("student_id", Auth::user()->username)->update(['result_obtained'=>1]);

				$message = 'Record Successfully Saved, Kindly Complete the Information Below.';

				//redirect to academic information form
				return redirect()->route('udg-biodata-form',['formType'=>'undergraduate-biodata-form','formNow'=>'credentials','message'=>$message]);
			}
			else
			{
				$error = new MessageBag(['errormessage' => ['Registration not successful']]);
				return redirect()->back()->withErrors($error);
			}
		}
		elseif($formNow=='credentials')
		{

			$this->validate($request,[
				"file"=>'required|min:20|max:50'
			]);

			$processed = 0;

			$userFileOn = userCredentials::where("student_id", Auth::user()->username)->first();
		
			$filedata = $request->file('file');

			$Ext = $filedata->getClientOriginalExtension();

			$fileName = time().'.'.$Ext;

			if(empty($userFileOn))
			{
				//first time processing user's file
				$file = json_encode(array($fileName));
				$uCredential = new userCredentials();
				$uCredential->student_id = Auth::user()->username;
				$uCredential->files = $file;
				$uCredential->remember_token = $request['_token'];
				if($uCredential->save())
				{
					$processed = 1;
				}
			}
			else
			{
				//$file = json_encode(array($fileName));
				$do = 'update';
				$files = json_decode($userFileOn->files);
				$merge = array_merge($files,array($fileName));
				$file = json_encode($merge);
				if(userCredentials::where("student_id", Auth::user()->username)->update(['files'=>$file]))
				{
					$processed = 1;
				}
			}

			if($processed == 1)
			{
				if(Image::make($filedata)->save(public_path('/assets/img/credentials/'.$fileName)))
				{
					$message = 'File Saved!';
					echo $message;
					//return response()->json(['status'=>1, 'message'=>$message]);
					DB::table('gportal_students_data')->where("student_id", Auth::user()->username)->update(['credentials'=>1]);
				}
			}


			

		}
	}
}
?>