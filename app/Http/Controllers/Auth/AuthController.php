<?php
  
namespace App\Http\Controllers\Auth;
  
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use Hash;
  
class AuthController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        return view('auth.login');
    }  
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registration()
    {
        return view('auth.registration');
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postLogin(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
	    'lastname' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('firstname', 'lastname', 'password');
	$request->session()->put('firstname', $credentials['firstname']);
        if (Auth::attempt($credentials)) {
            return redirect()->intended('update')
                        ->withSuccess('You have Successfully logged in');
        }
  
        return redirect("registration")->withSuccess('Your credentials were incorrect');
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postRegistration(Request $request)
    {  
        $request->validate([
            'firstname' => 'required',
	    'lastname' => 'required',
            'email' => 'required|email|unique:users',
	    'phoneNum' => 'required|regex:/(01)[0-9]{9}/',
            'password' => 'required|min:6',
        ]);
           
        $data = $request->all();
        $check = $this->create($data);
         
        return redirect("login")->withSuccess('You have Successfully created your account!');
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function userHub()
    {
        if(Auth::check()){
            return view('userHub');
        }
  
        return redirect("login")->withSuccess('Your credentials were incorrect');
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function create(array $data)
    {
      return User::create([
        'firstname' => $data['firstname'],
	'lastname' => $data['lastname'],
        'email' => $data['email'],
	'phoneNum' => $data['phoneNum'],
        'password' => Hash::make($data['password'])
      ]);
    }
    
    public function createInfo(array $data)
    {
      return UserInfo::create([
	'address' => $data['address'],
	'numBedrooms' => $data['numBedrooms'],
        'numBathrooms' => $data['numBathrooms'],
	'numFloors' => $data['numFloors'],
        'squareFootage' => $data['squareFootage'],
	'constType' => $data['constType'],
	'roofType' => $data['roofType'],
        'foundType' => $data['foundType'],
	'numFamily' => $data['numFamily'],
        'monIncome' => $data['monIncome'],
	'mortgage' => $data['mortgage'],
	'numCellDevices' => $data['numCellDevices'],
	'numMinutes' => $data['numMinutes'],
	'hotspot' => $data['hotspot'],
	'intCalling' => $data['intCalling'],
      ]);
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout() {
        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }

    public function firstname()
    {
    	return 'firstname';
    }


}