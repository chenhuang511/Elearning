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
 * @package    core
 * @subpackage admin
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['alternativefullnameformat'] = 'Thay thế định dạng tên đầy đủ';
$string['alternativefullnameformat_desc'] = 'Điều này xác định tên được hiển thị đến người dùng như thế nào với khả năng xem tên đầy đủ (bởi người dùng mặc định với vai trò của người quản lý, giáo viên, trợ giảng). Giữ chỗ mà có thể được sử dụng cho thiết lập "Định dạng tên đầy đủ".';
$string['availablesearchareas'] = 'Khu vực sẵn sàng cho tìm kiếm';
$string['configcustommenuitems'] = 'Bạn có thể cấu hình một menu tùy chỉnh ở đây được thể hiện bởi các chủ đề. Mỗi dòng bao gồm một số văn bản trình đơn, một liên kết URL (tùy chọn), một danh hiệu tooltip (tùy chọn) và một mã ngôn ngữ hoặc bằng dấu phẩy danh sách các mã (tùy chọn, để hiển thị dòng cho người sử dụng chỉ có ngôn ngữ nào đó), cách nhau bằng ký tự ống. Bạn có thể chỉ định một cơ cấu sử dụng dấu gạch ngang, và các bộ chia có thể được sử dụng bằng cách thêm một dòng của một hoặc nhiều ký tự # nơi mong muốn. Ví dụ: <pre> Moodle community|https://moodle.org -Moodle free support|https://moodle.org/support -### -Moodle development|https://moodle.org/development --Moodle Docs|http://docs.moodle.org|Moodle Docs --German Moodle Docs|http://docs.moodle.org/de|Documentation in German|de ##### Moodle.com|http://moodle.com/ </pre>';
$string['configcustomusermenuitems'] = 'Bạn có thể cấu hình các nội dung của thực đơn người dùng (với ngoại lệ của các log out liên kết, được tự động thêm vào). Mỗi dòng được tách ra bởi | ký tự và bao gồm 1) một chuỗi trong "langstringname, componentname" hình thức hoặc là văn bản rõ ràng, 2) một URL, và 3) một biểu tượng, hoặc như là một biểu tượng pix hoặc như một URL. Bộ chia có thể được sử dụng bằng cách thêm một dòng của một hoặc nhiều ký tự # nơi mong muốn.';
$string['configpathtodu'] = 'Đường dẫn đến du';
$string['configproxybypass'] = 'Dấu phẩy ngăn cách danh sách máy chủ (một phần) hoặc địa chỉ IP, cái nên vượt qua proxy (ví dụ: 192.168., .mydomain.com)';
$string['configtempdatafoldercleanup'] = 'Gỡ bỏ các tập tin dữ liệu tạm thời từ thư mục dữ liệu đã quá thời gian lựa chọn.';
$string['enableglobalsearch'] = 'Kích hoạt tìm kiếm toàn bộ';
$string['enableglobalsearch_desc'] = 'Nếu được kích hoạt, dữ liệu sẽ được lập chỉ mục và đồng bộ bởi một nhiệm vụ theo lịch trình.';
$string['enablesearchareas'] = 'Kích hoạt khu vực tìm kiếm';
$string['frontpagesettings'] = 'Cài đặt trang chủ';
$string['globalsearchmanage'] = 'Quản lý tìm kiếm toàn bộ';
$string['indexdata'] = 'Dữ liệu chỉ mục';
$string['linkcoursesections'] = 'Luôn liên kết các buổi của khóa học';
$string['linkcoursesections_help'] = 'Luôn luôn cố gắng cung cấp một liên kết cho các buổi của khóa học. Các buổi của khóa học thường chỉ được hiển thị như là liên kết nếu định dạng hiển thị một phần duy nhất cho mỗi trang. Nếu thiết lập này được kích hoạt một liên kết sẽ luôn luôn được cung cấp.';
$string['locationsettings'] = 'Cài đặt định vị';
$string['mymoodle'] = 'Trang cá nhân';
$string['navexpandmycourses'] = 'Khóa học của tôi được hiển thị mở rộng trên Dashboard';
$string['navexpandmycourses_desc'] = 'Nếu được kích hoạt, khóa học của tôi ban đầu được hiển thị mở rộng trong khối điều hướng trên Dashboard';
$string['passwordchangelogout'] = 'Đăng xuất sau khi thay đổi mật khẩu';
$string['passwordchangelogout_desc'] = 'Nếu được kích hoạt, khi mật khẩu được thay đổi, tất cả các phiên trình duyệt được chấm dứt, ngoài ra mật khẩu mới được chỉ định. (Cài đặt này không ảnh hưởng đến sự thay đổi mật khẩu qua việc tải lên số lượng lớn người dùng.)';
$string['passwordreuselimit'] = 'Giới hạn thay đổi mật khẩu';
$string['passwordreuselimit_desc'] = 'Số lần người dùng phải thay đổi mật khẩu của họ trước khi họ được phép tái sử dụng một mật khẩu. Hashes của mật khẩu sử dụng trước đó được lưu trữ trong bảng cơ sở dữ liệu địa phương. Tính năng này có thể không tương thích với một số bổ sung xác thực bên ngoài.';
$string['pathtogs_help'] = 'Trên hầu hết các cài đặt Linux, điều này có thể được để lại như \'/ usr / bin / gs\'. Trên Windows nó sẽ là một cái gì đó như \'c: \\ gs \\ bin \\ gswin32c.exe\' (chắc chắn rằng không có dấu cách trong đường dẫn - nếu bản sao cần thiết các tập tin \'gswin32c.exe\' và \'gsdll32.dll\' vào một thư mục mới mà không có một không gian trong đường dẫn)';
$string['pathtounoconv'] = 'Đường dẫn đến chuyển đổi tài liệu unoconv';
$string['pathtounoconv_help'] = 'Đường dẫn đến chuyển đổi tài liệu unoconv. Đây là một thực thi mà có thể chuyển đổi giữa các định dạng tài liệu hỗ trợ bởi LibreOffice. Đây là tùy chọn, nhưng nếu được chỉ định, Moodle sẽ sử dụng nó để tự động chuyển đổi giữa các định dạng tài liệu. Điều này được sử dụng để hỗ trợ một phạm vi rộng lớn hơn của các tập tin đầu vào cho tính năng PDF chuyển nhượng chú thích.';
$string['plugins'] = 'Tiện ích';
$string['profilecategory'] = 'Danh mục';
$string['questionbehaviours'] = 'Hành vi câu hỏi';
$string['questiontypes'] = 'Loại hình câu hỏi';
$string['searchengine'] = 'Bộ máy tìm kiếm';
$string['searchsetupinfo'] = 'Cài đặt tìm kiếm';
$string['selectsearchengine'] = 'Chọn bộ máy tìm kiếm';
$string['setupsearchengine'] = 'Cài đặt bộ máy tìm kiếm';
$string['tempdatafoldercleanup'] = 'Dọn dẹp các tập tin dữ liệu tạm thời cũ hơn';
$string['tools'] = 'Công cụ quản trị viên';
$string['unsupporteddbtablerowformat'] = 'Cơ sở dữ liệu của bạn có sử dụng bảng Antelope như là định dạng tập tin. Bạn được khuyến cáo để chuyển đổi các bảng cho các định dạng tập tin Barracuda. Xem tài liệu <a href="https://docs.moodle.org/en/cli">Quản lý tài liệu thông qua dòng lệnh</a> chi tiết của một công cụ cho chuyển đổi bảng InnoDB đến Barracuda.';
