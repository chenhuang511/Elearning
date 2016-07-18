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
 * Strings for component 'backup', language 'vi', branch 'MOODLE_31_STABLE'
 *
 * @package   backup
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['autoactivedescription'] = 'Chọn xem liệu có thục hiện sao lưu tự động. Nếu thủ công được chọn các sao lưu tự động sẽ chỉ có thể thực hiện được thông qua mã nguồn CLI sao lưu tự động. Việc này có thể được thực hiện thủ công trên trình dòng lệnh hoặc thông qua cron.';
$string['autoactivedisabled'] = 'Tắt';
$string['autoactiveenabled'] = 'Mở';
$string['autoactivemanual'] = 'Bằng tay';
$string['automatedbackupschedule'] = 'Lịch làm việc';
$string['automatedbackupschedulehelp'] = 'Chọn ngày muốn thực hiện sao lưu tự động.';
$string['automatedbackupsinactive'] = 'Quản trị viên chưa mở chức năng sao lưu tự động';
$string['automatedbackupstatus'] = 'Trạng thái các bản sao lưu tự động';
$string['automatedsettings'] = 'Các thiết lập sao lưu tự động';
$string['automatedsetup'] = 'Thiết lập sao lưu tự động';
$string['automatedstorage'] = 'Lưu trữ sao lưu tự động';
$string['automatedstoragehelp'] = 'Chọn nơi bạn muốn các bản sao được lưu trữ khi chúng được tạo tự động.';
$string['backupactivity'] = 'Sao lưu hoạt động (activities): {$a}';
$string['backupcourse'] = 'Sao lưu khoá học: {$a}';
$string['backupcoursedetails'] = 'Chi tiết khoá học';
$string['backupcoursesection'] = 'Phiên: {$a}';
$string['backupcoursesections'] = 'Các phiên của khoá học';
$string['backupdate'] = 'Dữ liệu được lấy';
$string['backupdetails'] = 'Chi tiết sao lưu';
$string['backupdetailsnonstandardinfo'] = 'Tập tin được chọn không phải tập tin sao lưu chuẩn Moodle. Quá trình phục hồi sẽ cố chuyển tập tin sao lưu sang định dạng chuẩn và rồi phục hồi nó.';
$string['backupformat'] = 'Định dạng';
$string['backupformatimscc1'] = 'IMS Common Cartridge 1.0';
$string['backupformatimscc11'] = 'IMS Common Cartridge 1.1';
$string['backupformatmoodle1'] = 'Moodle 1';
$string['backupformatmoodle2'] = 'Moodle 2';
$string['backupformatunknown'] = 'Định dạng không xác định';
$string['backuplog'] = 'Thông tin kĩ thuật và cảnh báo';
$string['backupmode'] = 'Chế độ';
$string['backupmode10'] = 'Chung';
$string['backupmode20'] = 'Nhập';
$string['backupmode30'] = 'Hub';
$string['backupmode40'] = 'Cùng hệ thống';
$string['backupmode50'] = 'Tự động';
$string['backupmode60'] = 'Hoàn đổi';
$string['backupsection'] = 'Sau lưu phiên {$a} của khoá học';
$string['backupsettings'] = 'Các thiết lập sao lưu';
$string['backupstage16action'] = 'Tiếp tục';
$string['backupstage1action'] = 'Kế tiếp';
$string['backupstage2action'] = 'Kế tiếp';
$string['backupstage4action'] = 'Thực thi việc sao lưu';
$string['backupstage8action'] = 'Tiếp tục';
$string['backuptype'] = 'Kiểu';
$string['backuptypeactivity'] = 'Hoạt động';
$string['backuptypecourse'] = 'Khoá học';
$string['backuptypesection'] = 'Phân mục';
$string['backupversion'] = 'Phiên bản sao lưu';
$string['cannotfindassignablerole'] = 'Vai {$a} trong tập tin sao lưu không thể mô tả cho bất cứ vai nào mà bạn muốn gán.';
$string['choosefilefromactivitybackup'] = 'Khu vực sao lưu hoạt động';
$string['choosefilefromactivitybackup_help'] = 'Khi sao lưu các hoạt động bằng sử dụng các thiết lập mặc định, các tập tin sao lưu sẽ được lưu trữ ở đây';
$string['choosefilefromautomatedbackup'] = 'Các sao lưu tự động';
$string['choosefilefromautomatedbackup_help'] = 'Chứa các sao lưu được tạo tự động.';
$string['choosefilefromcoursebackup'] = 'Khu vực sao lưu khoá học';
$string['choosefilefromcoursebackup_help'] = 'Khi sao lưu các khoá học bằng sử dụng các thiết lập mặc định, các tập tin sao lưu sẽ được lưu trữ ở đây';
$string['choosefilefromuserbackup'] = 'Khu vực sao lưu riêng của người dùng';
$string['choosefilefromuserbackup_help'] = 'Khi sao lưu các khóa học với lựa chọn "Ẩn thông tin người dùng", các tập tin sao lưu sẽ được lưu trữ ở đây';
$string['configgeneralactivities'] = 'Cài đặt tính năng mặc định để bao gồm được các hoạt động khi sao lưu.';
$string['configgeneralanonymize'] = 'Nếu được kích hoạt, tất cả các thông tin về người dùng, theo mặc định, sẽ bị khuyết danh.';
$string['configgeneralbadges'] = 'Đặt mặc định đối với các huy hiệu đi kèm trong sao lưu.';
$string['configgeneralblocks'] = 'Đặt mặc định đối với các khối đi kèm trong sao lưu.';
$string['configgeneralcomments'] = 'Đặt mặc định đối với các bình luận đi kèm trong sao lưu.';
$string['configgeneralfilters'] = 'Đặt mặc định đối với bộ lọc đi kèm trong sao lưu.';
$string['configgeneralhistories'] = 'Đặt mặc định đối với lịch sử người dùng đi kèm trong sao lưu.';
$string['configgenerallogs'] = 'Nếu nhật kí được kích hoạt chúng sẽ đi kèm trong sao lưu theo mặc định.';
$string['configgeneralquestionbank'] = 'Nếu được kích hoạt ngân hàng câu hỏi sẽ được sao lưu mặc định. HÃY CHÚ Ý: Vô hiệu hóa thiết lập này sẽ vô hiệu hóa việc sao lưu các hoạt động sử dụng ngân hàng câu hỏi, như trắc nghiệm.';
$string['configgeneralroleassignments'] = 'Nếu được kích hoạt theo mắc định các phân quyền cũng sẽ được sao lưu.';
$string['configgeneralusers'] = 'Đặt mặc định đối với việc kèm theo người dùng trong sao lưu.';
$string['configgeneraluserscompletion'] = 'Nếu được kích hoạt thông tin hoàn tất người dùng sẽ đi kèm trong sao lưu theo mặc định.';
$string['configloglifetime'] = 'Chỉ định khoảng thời gian bạn muốn giữ thông tin nhật kí sao lưu. Nhật kí cũ hơn thời gian này sẽ tự động xóa. Khuyến khích giữ giá trị này nhỏ, bởi vì thông tin nhật kí sao lưu có thể khổng lồ.';
$string['confirmcancel'] = 'Hủy sao lưu';
$string['confirmcancelno'] = 'Giữ';
$string['confirmcancelquestion'] = 'Bạn có muốn hủy?
Bất kì thông tin nào bạn vừa nhập sẽ bị mất.';
$string['confirmcancelyes'] = 'Hủy';
$string['confirmnewcoursecontinue'] = 'Cảnh báo khóa học mới';
$string['confirmnewcoursecontinuequestion'] = 'Một khóa học tạm thời (ẩn) sẽ được tạo bởi quá trình phục hồi khóa học. Để bỏ phục hồi nhấn hủy. Không đóng trình duyệt trong khi phục hồi.';
$string['coursecategory'] = 'Chuyên mục mà khóa học sẽ được phục hồi thành';
$string['courseid'] = 'ID ban đầu';
$string['coursesettings'] = 'Cài đặt khóa học';
$string['coursetitle'] = 'Tiêu đề';
$string['currentstage1'] = 'Cài đặt ban đầu';
$string['currentstage16'] = 'Hoàn thành';
$string['currentstage2'] = 'Thiết lập giản đồ';
$string['currentstage4'] = 'Xác nhận và kiểm duyệt';
$string['currentstage8'] = 'Thực hiện sao lưu';
$string['enterasearch'] = 'Nhập tìm kiếm';
$string['error_block_for_module_not_found'] = 'Tìm thấy khối thực thể mồ côi (id: {$a->bid}) dành cho mô-đun khóa học ({$a->mid}). Khhối này sẽ không được sao lưu';
$string['error_course_module_not_found'] = 'Tìm thấy mô-đun khóa học mồ côi  (id: {$a}). Mô-đun này sẽ không được sao lưu';
$string['errorfilenamemustbezip'] = 'Tên tệp bạn vừa nhập phải là tệp ZIP và có đuôi .mbz';
$string['errorfilenamerequired'] = 'Bạn phải nhập tên tệp hợp lệ để sao lưu';
$string['errorinvalidformat'] = 'Định dạng sao lưu không rõ';
$string['errorinvalidformatinfo'] = 'Tập tin được họn không phải tập tin sao lưu Moodle hợp lệ và không thể phục hồi được.';
$string['errorminbackup20version'] = 'Tập tin phục hồi này đã được tạo với một phiên bản phát triển của bản sao lưu Moodle ({$a->backup}). Yêu cầu tối thiểu là {$a->min}. Không thể phục hồi được.';
$string['executionsuccess'] = 'Tập tin sao lưu được tạo lập thành công.';
$string['filealiasesrestorefailures'] = 'Các thất bại phục hồi định danh';
$string['filealiasesrestorefailures_help'] = 'Định danh là các liên kết tượng trưng đến các tập tin khác, bao gồm những cái được lưu trữ trong các kho bên ngoài. Trong một số trường hợp, Moodle không thể phục hồi chúng - ví dụ khi phục hồi sao lưu ở một trang khác khi mà tập tin được tham chiếu không tồn tại.

Thông tin chi tiết hơn và nguyên do thất bại thực sư có thể tìm thấy ở tập tin nhật kí phục hồi.';
$string['filealiasesrestorefailuresinfo'] = 'Một số định danh kèm trong tập tin sao lưu không thể phục hồi được. Danh sách sau chứa vị trí mong đợi của chúng và tập tin nguồn mà chúng chỉ đến tại trang gốc.';
$string['filename'] = 'Tên tệp';
$string['filereferencesincluded'] = 'Các tham chiếu tập tin';
$string['filereferencesnotsamesite'] = 'Sao lưu từ trang khác, các tham chiếu tập tin không thể phục hồi được';
$string['filereferencessamesite'] = 'Sao lưu cùng trang, các tham chiếu tập tin có thể phục hồi';
$string['generalactivities'] = 'Bao gồm các hoạt động';
$string['generalanonymize'] = 'Ẩn thông tin';
$string['generalbackdefaults'] = 'Mặc định sao lưu thông thường';
$string['generalbadges'] = 'Bao gồm các huy hiệu';
$string['generalblocks'] = 'Bao gồm các khối';
$string['generalcomments'] = 'Bao gồm các bình luận';
$string['generalfilters'] = 'Bao gồm các bộ lọc';
$string['generalhistories'] = 'Bao gồm lịch sử';
$string['generallogs'] = 'Bao gồm nhật kí';
$string['generalquestionbank'] = 'Bao gồm ngân hàng câu hỏi';
$string['generalroleassignments'] = 'Bao gồm các phân quyền';
$string['generalsettings'] = 'Các thiết lập sao lưu thông thường';
$string['generalusers'] = 'Bao gồm các người dùng';
$string['generaluserscompletion'] = 'Bao gồm thông tin hoàn tất người dùng';
$string['hidetypes'] = 'Ẩn tùy chọn loại';
$string['importbackupstage16action'] = 'Tiếp tục';
$string['importbackupstage1action'] = 'Kế tiếp';
$string['importbackupstage2action'] = 'Kế tiếp';
$string['importbackupstage4action'] = 'Thực hiện nhập';
$string['importbackupstage8action'] = 'Tiếp tục';
$string['importcurrentstage0'] = 'Chọn khóa học';
$string['importcurrentstage1'] = 'Các thiết lập ban đầu';
$string['importcurrentstage16'] = 'Hoàn thành';
$string['importcurrentstage2'] = 'Các thiết lập giản đồ';
$string['importcurrentstage4'] = 'Xác nhận và kiểm duyệt';
$string['importcurrentstage8'] = 'Thực hiện nhập';
$string['importfile'] = 'Nhập một tập tin sao lưu';
$string['importgeneralmaxresults'] = 'Số khóa học tối đa được liệt kê để nhập';
$string['importgeneralmaxresults_desc'] = 'Kiểm soát số khóa học được liệt liệt kê suốt bước đầu quá trình nhập';
$string['importgeneralsettings'] = 'Mặc định nhập thông thường';
$string['importsuccess'] = 'Nhập hoàn tất. Nhấn tiếp tục để trở về khóa học.';
$string['includeactivities'] = 'Bao gồm:';
$string['includeditems'] = 'Các mục bao gồm:';
$string['includefilereferences'] = 'Các tham chiếu tập tin đến nội dung bên ngoài';
$string['includesection'] = 'Phân mục {$a}';
$string['includeuserinfo'] = 'Dữ liệu người dùng';
$string['locked'] = 'Đã bị khóa';
$string['lockedbyconfig'] = 'Thiết lập này đã bị khóa bởi các thiết lập sao lưu mặc định';
$string['lockedbyhierarchy'] = 'Bị khóa bởi các bộ phụ thuộc';
$string['lockedbypermission'] = 'Bạn không đủ quyền hạn thay đổi thiết lập này';
$string['loglifetime'] = 'Giữ nhật kí trong';
$string['managefiles'] = 'Quản lí các tập tin sao lưu';
$string['missingfilesinpool'] = 'Một số tập tin không thể lưu trong quá trình sao lưu, sẽ không thể phục hồi chúng.';
$string['module'] = 'Mô-đun';
$string['moodleversion'] = 'Phiên bản Moodle';
$string['morecoursesearchresults'] = 'Tìm thấy hơn {$a} khóa học, hiện {$a} kết quả đầu tiên';
$string['moreresults'] = 'Có quá nhiều kết quả, nhập tìm kiếm cụ thể hơn';
$string['nomatchingcourses'] = 'Không có khóa học nào để hiển thị';
$string['norestoreoptions'] = 'Không có chuyên mục hay khóa học tồn tại nào mà bạn có thể phục hồi';
$string['originalwwwroot'] = 'URL sao lưu';
$string['preparingdata'] = 'Đang chuẩn bị dữ liệu';
$string['preparingui'] = 'Đang chuẩn bị hiển thị trang';
$string['previousstage'] = 'Trước';
$string['qcategory2coursefallback'] = 'Chuyên mục câu hỏi  "{$a->name}", ban đầu ở bối cảnh chuyên mục hệ thống/khóa học trong tập tin sao lưu, sẽ được tạo ở bối cảnh khóa học khi phục hồi';
$string['qcategorycannotberestored'] = 'Chuyên mục câu hỏi "{$a->name}" không thể phục hồi được';
$string['question2coursefallback'] = 'Chuyên mục câu hỏi "{$a->name}" , ban đầu ở bối cảnh chuyên mục hệ thống/khóa học trong tập tin sao lưu, sẽ được tạo ở bối cảnh khóa học khi phục hồi';
$string['restoreactivity'] = 'Hoạt động phục hồi';
$string['restorecourse'] = 'Phục hồi khóa học';
$string['restoreexecutionsuccess'] = 'Khóa học được phục hồi thành công, nhấn nút tiếp tục bên dưới sẽ cho bạn xem khóa học vừa phục hồi.';
$string['restorefileweremissing'] = 'Một số tập tin không thể phục hồi được vì chúng bị thiếu trong bản sao lưu.';
$string['restorerolemappings'] = 'Phục hồi các sắp đặt quyền';
$string['restorerootsettings'] = 'Các thiết lập phục hồi';
$string['restoresection'] = 'Phân mục phục hồi';
$string['restorestage1'] = 'Xác nhận';
$string['restorestage16'] = 'Kiểm duyệt';
$string['restorestage16action'] = 'Thực hiện phục hồi';
$string['restorestage1action'] = 'Kế tiếp';
$string['restorestage2'] = 'Đích';
$string['restorestage2action'] = 'Kế tiếp';
$string['restorestage32'] = 'Quá trình';
$string['restorestage32action'] = 'Tiếp tục';
$string['restorestage4'] = 'Cài đặt';
$string['restorestage4action'] = 'Kế tiếp';
$string['restorestage64'] = 'Hoàn thành';
$string['restorestage64action'] = 'Tiếp tục';
$string['restorestage8'] = 'Giản đồ';
$string['restorestage8action'] = 'Kế tiếp';
$string['restoretarget'] = 'Phục hồi phục tiêu';
$string['restoretocurrentcourse'] = 'Phục hồi thành khóa học này';
$string['restoretocurrentcourseadding'] = 'Trộn khóa học sao lưu thành khóa học này';
$string['restoretocurrentcoursedeleting'] = 'Xóa nội dung của khóa học này và phục hồi';
$string['restoretoexistingcourse'] = 'Phục hồi thành một khóa học vốn có';
$string['restoretoexistingcourseadding'] = 'Trộn khóa học sao lưu thành khóa học vốn có';
$string['restoretoexistingcoursedeleting'] = 'Xóa nội dung của khóa học vốn có và phục hồi';
$string['restoretonewcourse'] = 'Phục hồi thành khóa học mới';
$string['restoringcourse'] = 'Đang trong quá trình phục hồi khóa học';
$string['restoringcourseshortname'] = 'đang phục hồi';
$string['rootenrolmanual'] = 'Phục hồi thành ghi danh thủ công';
$string['rootsettingactivities'] = 'Bao gồm các hoạt động';
$string['rootsettinganonymize'] = 'Ẩn thông tin người dùng';
$string['rootsettingbadges'] = 'Bao gồm các huy hiệu';
$string['rootsettingblocks'] = 'Bao gồm các khối';
$string['rootsettingcalendarevents'] = 'Bao gồm các sự kiện lịch';
$string['rootsettingcomments'] = 'Bao gồm các bình luận';
$string['rootsettingfilters'] = 'Bao gồm các bộ lọc';
$string['rootsettinggradehistories'] = 'Bao gồm lịch sử điểm';
$string['rootsettingimscc1'] = 'Chuyển thành IMS Common Cartridge 1.0';
$string['rootsettingimscc11'] = 'Chuyển thành IMS Common Cartridge 1.1';
$string['rootsettinglogs'] = 'Bao gồm nhật kí khóa học';
$string['rootsettingquestionbank'] = 'Bao gồm ngân hàng câu hỏi';
$string['rootsettingroleassignments'] = 'Bao gồm các phân quyền người dùng';
$string['rootsettings'] = 'Các thiết lập sao lưu';
$string['rootsettingusers'] = 'Bao gồm các người dùng đã ghi danh';
$string['rootsettinguserscompletion'] = 'Bao gồm các chi tiết hoàn tất người dùng';
$string['sectionactivities'] = 'Hoạt động';
$string['sectioninc'] = 'Được kèm trong bản sao lưu (không có thông tin người dùng)';
$string['sectionincanduser'] = 'Được kèm trong bản sao lưu kèm theo thông tin người dùng';
$string['selectacategory'] = 'Chọn chuyên mục';
$string['selectacourse'] = 'Chọn khóa học';
$string['setting_course_fullname'] = 'Tên khóa học';
$string['setting_course_shortname'] = 'Tên ngắn khóa học';
$string['setting_course_startdate'] = 'Ngày bắt đầu khóa học';
$string['setting_keep_groups_and_groupings'] = 'Giữ các nhóm hiện tại';
$string['setting_keep_roles_and_enrolments'] = 'Giữ các quyền và ghi danh hiện tại';
$string['setting_overwriteconf'] = 'Đè thiết lập khóa học';
$string['showtypes'] = 'Hiện các lựa chọn loại';
$string['skiphidden'] = 'Bỏ qua các khóa học ẩn';
$string['skiphiddenhelp'] = 'Chọn xem có hay không việc bỏ qua các khóa học ẩn';
$string['skipmodifdays'] = 'Bỏ qua các khóa học không được chỉnh sửa kể từ';
$string['skipmodifdayshelp'] = 'Chọn bỏ qua các khóa học không được chỉnh sửa kể từ một số ngày';
$string['skipmodifprev'] = 'Bỏ qua các khóa học không được chỉnh sửa kể từ lần sao lưu trước';
$string['skipmodifprevhelp'] = 'Chọn xem có bỏ qua các khóa học không được chỉnh sửa kể từ lần sao lưu tự động trước. Yêu cầu nhật kí được kích hoạt.';
$string['storagecourseandexternal'] = 'Vùng tập tin sao lưu khóa học và thư mục được chỉ định';
$string['storagecourseonly'] = 'Vùng tập tin sao lưu khóa học';
$string['storageexternalonly'] = 'Thư mục được chỉ định sao lưu tự động';
$string['timetaken'] = 'Thời gian thực hiện';
$string['title'] = 'Tiêu đề';
$string['totalcategorysearchresults'] = 'Tổng số chuyên mục: {$a}';
$string['totalcoursesearchresults'] = 'Tổng số khóa học: {$a}';
$string['unnamedsection'] = 'Phân vùng không tên';
$string['userinfo'] = 'Thông tin người dùng';
