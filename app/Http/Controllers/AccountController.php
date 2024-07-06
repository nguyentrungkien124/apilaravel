<?php

namespace App\Http\Controllers;

use App\Models\Khachhang;
use App\Mail\VerifyAccount;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Mail;
use Hash;


class AccountController extends Controller
{

    public function login()
    {
        return view('account.login');
    }
    public function favorite()
    {
        // Lấy người dùng hiện tại
        $user = auth('cus')->user();
      
        // Giả định rằng bạn có trường 'khachhang_id' trong bảng 'yeuthich' trỏ đến 'id' của bảng người dùng
        $favorites = Favorite::where('khachhang_id', $user->id)->get();
        // dd($favorites);
    
        // Truyền danh sách yêu thích vào view
        return view('account.favorite', compact('favorites'));
    }
    
    public function logout()
    {
        auth('cus')->logout();
        return redirect()->route('account.login')->with('ok', 'Tạm biệt');
    }
    public function check_login(Request $req)
    {
        $req->validate([
            'email' => 'required|exists:khachhang',
            'password' => 'required',
        ], [
            'email.exists' => 'Email không tồn tại hoặc không hợp lệ.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $data = $req->only('email', 'password');
        $check = auth('cus')->attempt($data);

        if ($check) {
            if (auth('cus')->user()->email_verified_at == '') {
                auth('cus')->logout();
                return redirect()->back()->with('no', 'Lỗi: Vui lòng xác minh tài khoản của bạn.');
            }
            return redirect()->route('home.index')->with('ok', 'Chào mừng bạn đã đăng nhập thành công.');
        }

        return redirect()->back()->with('no', 'Lỗi: Email hoặc mật khẩu không đúng.');
    }

    public function register()
    {
        return view('account.register');
    }

    public function check_register(Request $req)
    {

        $req->validate([
            'tenkh' => 'required|min:6|max:100',
            'email' => 'required|email|min:6|max:100|unique:khachhang',
            'password' => 'required|min:4',
            'confirm_password' => 'required|same:password',
            'sodt' => 'required|min:6|max:100|unique:khachhang'
        ], [
            'tenkh.required' => 'Họ tên không được để trống',
            'tenkh.min' => 'Họ tên tối thiểu 6 kí tự',

        ]);

        $data = $req->only('tenkh', 'email', 'sodt', 'diachi');
        $data['password'] = bcrypt($req->password);


        if ($acc = Khachhang::create($data)) {
            Mail::to($acc->email)->send(new VerifyAccount($acc));
            return redirect()->route('account.login')->with('ok', 'Đăng kí thành công , vui lòng check email để xác nhận');
        }
        return redirect()->back()->with('no', 'Không thành công vui lòng nhập lại');
    }

    public function veryfy($email)
    {
        $acc = Khachhang::where('email', $email)->whereNull('email_verified_at')->firstOrFail();
        Khachhang::where('email', $email)->update(['email_verified_at' => date('Y-m-d')]);
        return redirect()->route('account.login')->with('ok', 'Đăng kí thành công');
    }

    public function change_password()
    {
        return view('account.change_password');
    }

    public function check_change_password(Request $req)
    {
        $auth = auth('cus')->user();
        $req -> validate([
            'old_password'=>['required', function($attr,$value,$fail)use($auth){
          
                if(!Hash::check($value,$auth->password))  {
                    $fail('Mật khẩu của bạn không khớp');
                }

            }],
            'password' => 'required|min:4',
            'conffirm_password'=>'required|same:password'
        ]); 
        $data['password'] = bcrypt($req->password);

        if ( $auth->update($data)) {
            auth('cus')->logout();
            return redirect()->route('account.login')->with('ok', 'Cập nhật thành công mật khẩu');
        }

        return redirect()->back()->with('no', 'Lỗi vui lòng kiểm tra lại');
        
        
    }

    public function forgot_password()
    {
        return view('account.forgot_password');
    }

    public function check_forgot_password()
    {
    }

    public function profile()
    {
        $auth = auth('cus')->user();
        return view('account.profile', compact('auth'));
    }

    public function check_profile(Request $req)
    {
        $auth = auth('cus')->user();

        $req->validate([
            'tenkh' => 'required|min:6|max:100',
            'email' => 'required|email|min:6|max:100|unique:khachhang,email,' . $auth->id,
            'password' => [
                'required',
                function ($attribute, $value, $fail) use ($auth) {
                    if (!Hash::check($value, $auth->password)) {
                        return $fail('Sai mật khẩu');
                    }
                }
            ],
        ], [
            'tenkh.required' => 'Họ tên không được để trống',
            'tenkh.min' => 'Họ tên tối thiểu 6 kí tự',
        ]);

        $data = $req->only('tenkh', 'email', 'sodt', 'diachi');

        $check = $auth->update($data);

        if ($check) {
            return redirect()->back()->with('ok', 'Cập nhật thành công');
        }

        return redirect()->back()->with('no', 'Lỗi vui lòng kiểm tra lại');
    }

    public function reset_password()
    {
        return view('account.reset_password');
    }

    public function check_reset_password()
    {
    }
}
