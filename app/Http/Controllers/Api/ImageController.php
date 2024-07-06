<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi kiểm tra lại',
                'data' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = 'C:\\Users\\Admin\\Downloads\\anh'; // Đường dẫn lưu trữ trên hệ thống của bạn

            // Tạo thư mục nếu chưa tồn tại
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Lưu tệp vào đường dẫn cụ thể
            $file->move($destinationPath, $filename);

            // Đường dẫn tệp sau khi lưu
            $filePath = $destinationPath . '\\' . $filename;

            return response()->json([
                'success' => true,
                'message' => 'Hình ảnh được tải lên thành công',
                'data' => ['file_path' => $filePath]
            ], Response::HTTP_CREATED);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không có tệp nào được tải lên',
        ], Response::HTTP_BAD_REQUEST);
    }
}
