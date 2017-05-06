<?php
namespace App\Http\Controllers;

use App\student_infos;
use App\deptModel;


use Illuminate\Http\Request;

use DB;
use Response;

class APIUserController extends Controller
{
	protected $students = null;

	protected $id = null;

	protected $canAccessAPI = ['10.0.2.2','192.168.10.10'];

	//protected $hidden = ['password', 'remember_token', 'id', 'created_at', 'updated_at', 'hashkey'];

	public function __construct(student_infos $students)
	{
		//checking for REMOTE PRIVILEGE TO API SERVICE CALL
		if(!in_array($_SERVER['REMOTE_ADDR'], $this->canAccessAPI))
		{
			return Response::json(['data'=>'UnAuthorized'],401);
		}
		else
		{
			$this->students = $students;
		}
		
	}

	public function getAll($id)
	{
		return $this->students->getAll();
	}

	public function getUserData($id)
	{
		//print_r($this->students); die;

		$this->id = $id;

		$studentDetails = DB::table('student_infos')
							->join('student_aca_info', function($join){
							$join->on('student_infos.matric_no','=','student_aca_info.matric_no')->where('student_infos.matric_no',$this->id);
						})->first();

		//$students =  $this->students->getUser($id);

		if(!$studentDetails)
		{
			return Response::json(['response'=>'Record not exist']);
		}
		else
		{

			return $studentDetails;
		}

	}

	public function getUserLoginAPI($id,$key)
	{

		$studentLogin =  $this->students->getUserAuthLogin($id,$key);

		if(!$studentLogin)
		{
			return response()->json(['success'=>'-1','response_code'=>'00','data'=>'Authentication Failed'],200);
		}
		else
		{

			$studentRecord = $this->getUserData($id);

			if($studentRecord)
			{
				return response()->json(['success'=>'1','response_code'=>'01','data'=>$studentRecord],200);
			}
			else
			{
				return response()->json(['success'=>'-1','response_code'=>'02','data'=>'Unable to fetch record, database/server issue, contact admin'],200);
			}
			
		}


	}

	public function getAddressPrivilege($addressAPI)
	{
		if( $addressAPI)
			return true;
		else
			return false;
	}




}