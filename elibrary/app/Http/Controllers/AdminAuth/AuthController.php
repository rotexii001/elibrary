<?php 

namespace App\Http\Controllers\AdminAuth;

use App\AdminUsers;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\MessageBag;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Auth;

class AuthController extends Controller
{
	use AuthenticatesAndRegistersUsers, ThrottlesLogins;

	protected $redirectTo = '/web/admin';
	protected $guard = '/web/admin';

	public function getLogin()
	{
		if(!empty(Auth::user()))
		{
			$error = new MessageBag(['A user already exist on this browser, kindly switch to another or logout']);
			return redirect()->back()->withErrors($error);
		}
		else
		{
			if(Auth::guard('admin')->check())
			{
				return redirect()->route('admin-dashboard');
			}
			else
			{
				return view('admin.login');
			}
		}
	}
}