<?php
namespace App\Http\Controllers;

use App\student_infos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

use DB;
use Session;


class FormController extends Controller
{

	public function processAction(Request $request, $type, $action, $user)
	{
		switch($type)
		{
			case 'login':
				
				$this->validate($request, [
						'password'=>'required|min:6',
						'confirm_password'=>'required|same:password'
					]);

				//echo bcrypt($request['password']); echo "<br/>"; echo Auth::user()->password; die;

				$oldPassword = DB::table('oldpwd')->select('checkit')->where([
							['matric_no','=',Auth::user()->matric_no],
							['checkit','=',md5(sha1($request['password']))]
						])->first();

				$oldPwd = (empty($oldPassword)) ? '': $oldPassword->checkit;

				if(md5(sha1($request['password'])) == $oldPwd)
				{
					$error = new MessageBag(['You cannot use old password as new password']);
					return redirect()->back()->withErrors($error);
				}
				else
				{
					$password = bcrypt($request['password']);

					if(student_infos::where('matric_no',Auth::user()->matric_no)->update(['password'=>$password,'hashkey'=>md5(sha1($request['password']))]))
					{
						//update password table

						DB::table('oldpwd')->insert(['matric_no'=>Auth::user()->matric_no, 'checkit'=>md5(sha1($request['password']))]);

						$message = 'Password Successfully Updated';

						return redirect()->back()->with(["message"=>$message]);

					}
					else
					{
						$error = new MessageBag(['Unable to update password']);
						return redirect()->back()->withErrors($error);
					}
				}

			break;

			default:
				$error = new MessageBag(['Invalid Response sent to server']);
				return redirect()->back()->withErrors($error);
			break;
		}
	}

	public function getSelectOption($listType,$optionValue)
	{

		$option= "<option value='' selected>---Select---</option>";

		switch($listType)
		{
			
			case 'countryList':
				$dbValues = DB::table('nationalities')->where("id",">",0)->get();
				$value='code';
				$valueName='country';
			break;

			case 'stateList':
				$dbValues = DB::table('states')->where("id",">",0)->get();
				$value='state_code';
				$valueName='state_name';
			break;

			case 'lgaList':
				$dbValues = DB::table('lgas')->where("state_code","=",$optionValue)->get();
				$value='lga_code';
				$valueName='lga_name';
			break;

			case 'departmentList':
				$dbValues = DB::table('dept')->where("faculty_code","=",$optionValue)->get();
				$value='course_code';
				$valueName='course_title';
			break;
		}

		$countData = count($dbValues);

		for($i=0;$i<$countData;$i++)
		{
			$option.="<option value='".$dbValues[$i]->$value."'>".$dbValues[$i]->$valueName."</option>";
		}

		if(count($countData)>0)
		{
			return response()->json(['status'=>1, 'message'=>$option]);
		}
		else
		{
			return response()->json(['status'=>0, 'message'=>'No Record Found']);
		}
	}

	public function fileProcessor(Request $request,$fileAction,$optionValue)
	{
		echo "here"; die;

		switch($fileAction)
		{
			case 'listOut':

				print_r($request->file('file'));die;

				$filedata = $request->file('file');

				$f = fopen($filedata,'r');
							
				$startRow = 1;

				$div = '';

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
								$div.=' <div class="row">
					                        <div class="col-sm-3">
					                          <div class="form-group form-group-default required">
					                            <input type="text" value="'.$rf[0].'" name="'.$startRow.'-code" class="form-control" required>
					                          </div>
					                        </div>
					                        <div class="col-sm-3">
					                          <div class="form-group form-group-default required">
					                            <input type="text" value="'.$rf[1].'" name="'.$startRow.'-title" class="form-control" required>
					                          </div>
					                        </div>
					                        <div class="col-sm-3">
					                          <div class="form-group form-group-default required">
					                            <input type="text" value="'.$rf[2].'" name="'.$startRow.'-unit" class="form-control" required>
					                          </div>
					                        </div>
					                        <div class="col-sm-3">
					                          <div class="form-group form-group-default required">
					                            <input type="text" value="'.$rf[3].'" name="'.$startRow.'-type" class="form-control" required>
					                          </div>
					                        </div>
			                      		</div>';

			                    $processed++;

							}
						}
										
					}	
				}

				if($processed > 0)
				{
					return response()->json(['status'=>1, 'message'=>$div]);
				}
				else
				{
					return response()->json(['status'=>0, 'message'=>'No Record to display']);
				}

			break;
		}
	}
}
?>