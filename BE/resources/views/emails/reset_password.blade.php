<x-mail::message>
# Xin chào, {{ $user->ho_ten }}!

Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu khôi phục mật khẩu cho tài khoản Heritage Archivist của bạn.

Dưới đây là mã xác nhận của bạn:

<x-mail::panel>
# {{ $code }}
</x-mail::panel>

Vui lòng nhập mã này vào trang web để tiếp tục quá trình đổi mật khẩu. Mã này có hiệu lực trong vòng 15 phút.

Nếu bạn không yêu cầu khôi phục mật khẩu, bạn có thể bỏ qua email này. Tài khoản của bạn vẫn được bảo mật.

Trân trọng,<br>
Đội ngũ {{ config('app.name') }}
</x-mail::message>
