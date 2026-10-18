<?php

namespace App\Support;

/** Thông báo lỗi kiểm tra dữ liệu bằng tiếng Việt, dùng chung cho các form. */
class ValidationMessages
{
    public static function messages(): array
    {
        return [
            'required'         => ':attribute không được để trống.',
            'string'           => ':attribute không hợp lệ.',
            'email'            => ':attribute không đúng định dạng.',
            'max'              => ':attribute không được vượt quá :max ký tự.',
            'min'              => ':attribute phải có ít nhất :min ký tự.',
            'unique'           => ':attribute đã được sử dụng.',
            'confirmed'        => 'Mật khẩu nhập lại không khớp.',
            'regex'            => ':attribute không hợp lệ.',
            'exists'           => ':attribute không hợp lệ.',
            'in'               => ':attribute không hợp lệ.',
            'different'        => 'Mật khẩu mới phải khác mật khẩu hiện tại.',
            'current_password' => 'Mật khẩu hiện tại không đúng.',
            'image'            => ':attribute phải là file hình ảnh.',
            'mimes'            => ':attribute chỉ chấp nhận định dạng: :values.',
            'file'             => ':attribute không hợp lệ.',
            'uploaded'         => 'Tải ảnh lên thất bại, vui lòng chọn ảnh nhỏ hơn 2MB.',

            // Thông báo riêng từng trường
            'Phone.regex'      => 'Số điện thoại phải gồm đúng 10 chữ số và bắt đầu bằng số 0.',
            'avatar.max'       => 'Ảnh đại diện không được vượt quá 2MB.',
        ];
    }

    public static function attributes(): array
    {
        return [
            'FullName'         => 'Họ tên',
            'Email'            => 'Email',
            'Phone'            => 'Số điện thoại',
            'Address'          => 'Địa chỉ',
            'password'         => 'Mật khẩu',
            'current_password' => 'Mật khẩu hiện tại',
            'avatar'           => 'Ảnh đại diện',
            'RoleID'           => 'Quyền',
            'Status'           => 'Trạng thái',
        ];
    }
}
