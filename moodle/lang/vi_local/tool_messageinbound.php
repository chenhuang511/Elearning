<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Local language pack from http://192.168.1.253
 *
 * @package    tool
 * @subpackage messageinbound
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['component'] = 'Thành phần';
$string['configmessageinboundhost'] = 'Địa chỉ của máy chủ mà Moodle nên kiểm tra thư chống lại. Để xác định một cổng không mặc định, bạn có thể sử dụng [server]: [cổng], ví dụ định dạng mail.example.com:587. Nếu bạn bỏ trống trường này, Moodle sẽ sử dụng cổng mặc định cho các loại máy chủ mail mà bạn chỉ định.';
$string['description'] = 'Mô tả';
$string['domain'] = 'Tên miền Email';
$string['edit'] = 'Chỉnh sửa';
$string['editinghandler'] = 'Chỉnh sửa {$a}';
$string['enabled'] = 'Đã kích hoạt';
$string['incomingmailconfiguration'] = 'Cấu hình đầu vào của thư';
$string['incomingmailserversettings'] = 'Cài đặt máy chủ thư đến';
$string['incomingmailserversettings_desc'] = 'Moodle có khả năng kết nối đến các máy chủ IMAP có cấu hình thích hợp. Bạn có thể chỉ định các thiết lập được dùng để kết nối với máy chủ IMAP của bạn ở đây.';
$string['invalid_recipient_handler'] = 'Nếu một thông báo hợp lệ được nhận, nhưng người gửi không thể xác thực, tin nhắn được lưu trữ trên máy chủ email và người sử dụng sẽ được liên lạc bằng cách sử dụng địa chỉ email trong hồ sơ người dùng của họ. Người dùng được tạo cơ hội để trả lời để xác nhận tính xác thực của thông báo ban đầu. Xử lý này xử lý những bài trả lời. Nó không thể vô hiệu hóa xác minh người gửi xử lý này vì người dùng có thể trả lời từ một địa chỉ email không chính xác nếu cấu hình máy khách email của họ là không chính xác.';
$string['invalid_recipient_handler_name'] = 'Xử lý người nhận không hợp lệ';
$string['mailbox'] = 'Tên hộp thư';
$string['mailboxconfiguration'] = 'Cấu hình hộp thư';
$string['mailsettings'] = 'Cài đặt thư';
$string['messageinboundenabled'] = 'Kích hoạt tính năng xử lý thư đến';
$string['messageinboundenabled_desc'] = 'Xử lý thư đến phải được kích hoạt để cho các tin nhắn được gửi đi với các thông tin thích hợp.';
$string['messageinboundgeneralconfiguration'] = 'Cấu hình chung';
$string['messageinboundgeneralconfiguration_desc'] = 'Xử lý thông báo cho phép bạn nhận và xủ lý thông báo với Moodle. Điều này có các ứng dụng như gửi trả lời email đến bài viết diễn đàn hoặc thêm các tập tin vào tập tin cá nhân của người dùng.';
$string['messageinboundhost'] = 'Máy chủ thư đến';
$string['messageinboundhostpass'] = 'Mật khẩu';
$string['messageinboundhostpass_desc'] = 'Đây là mật khẩu cung cấp dịch vụ của bạn đã được cung cấp để đăng nhập với tài khoản email của bạn.';
$string['messageinboundhostssl'] = 'Sử dụng SSL';
$string['messageinboundhostssl_desc'] = 'Một số máy chủ thư hỗ trợ một mức độ bảo mật bổ sung bằng cách mã hóa thông tin liên lạc giữa Moodle và máy chủ của bạn. Chúng tôi khuyên bạn nên sử dụng mã hóa SSL này nếu máy chủ của bạn hỗ trợ nó.';
$string['messageinboundhostuser'] = 'Kí danh';
$string['messageinboundhostuser_desc'] = 'Đây là tên người cung cấp dịch vụ của bạn sẽ cung cấp để đăng nhập với tài khoản email của bạn.';
$string['messageinboundmailboxconfiguration_desc'] = 'Khi thông điệp được gửi đi, chúng phù hợp với các định dạng address+data@example.com. Để chắc chắn tạo ra các địa chỉ từ Moodle, xin vui lòng ghi rõ địa chỉ mà bạn thường sử dụng trước ký hiệu @, và miền sau @ ký riêng. Ví dụ, tên hộp thư trong ví dụ này sẽ là "địa chỉ", và miền E-mail sẽ là "example.com". Bạn nên sử dụng một tài khoản e-mail dành riêng cho mục đích này.';
$string['messageprovider:invalidrecipienthandler'] = 'Tin nhắn để xác nhận rằng tin nhắn được gửi đến từ bạn';
$string['messageprovider:messageprocessingerror'] = 'Cảnh báo khi một thông báo gửi vào không thể được xử lý';
$string['messageprovider:messageprocessingsuccess'] = 'Xác nhận một tin nhắn được xử lý thành công';
$string['message_handlers'] = 'Xử lý tin nhắn';
$string['name'] = 'Tên';
