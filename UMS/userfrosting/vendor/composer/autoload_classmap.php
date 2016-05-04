<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'AccessCondition' => $baseDir . '/models/auth/AccessCondition.php',
    'EasyPeasyICS' => $vendorDir . '/phpmailer/phpmailer/extras/EasyPeasyICS.php',
    'Fortress\\ClientSideValidationAdapter' => $vendorDir . '/alexweissman/fortress/fortress/adapters/ClientSideValidationAdapter.php',
    'Fortress\\DataSanitizer' => $vendorDir . '/alexweissman/fortress/fortress/DataSanitizer.php',
    'Fortress\\DataSanitizerInterface' => $vendorDir . '/alexweissman/fortress/fortress/DataSanitizer.php',
    'Fortress\\FormValidationAdapter' => $vendorDir . '/alexweissman/fortress/fortress/FormValidationAdapter.php',
    'Fortress\\HTTPRequestFortress' => $vendorDir . '/alexweissman/fortress/fortress/HTTPRequestFortress.php',
    'Fortress\\JqueryValidationAdapter' => $vendorDir . '/alexweissman/fortress/fortress/JqueryValidationAdapter.php',
    'Fortress\\MessageStream' => $vendorDir . '/alexweissman/fortress/fortress/MessageStream.php',
    'Fortress\\MessageTranslator' => $vendorDir . '/alexweissman/fortress/fortress/MessageTranslator.php',
    'Fortress\\RequestSchema' => $vendorDir . '/alexweissman/fortress/fortress/RequestSchema.php',
    'Fortress\\ServerSideValidator' => $vendorDir . '/alexweissman/fortress/fortress/ServerSideValidator.php',
    'Fortress\\ServerSideValidatorInterface' => $vendorDir . '/alexweissman/fortress/fortress/ServerSideValidator.php',
    'PHPMailer' => $vendorDir . '/phpmailer/phpmailer/class.phpmailer.php',
    'POP3' => $vendorDir . '/phpmailer/phpmailer/class.pop3.php',
    'ParserNodeFunctionEvaluator' => $baseDir . '/models/auth/ParserNodeFunctionEvaluator.php',
    'SMTP' => $vendorDir . '/phpmailer/phpmailer/class.smtp.php',
    'Slim\\Extras\\Middleware\\CsrfGuard' => $baseDir . '/middleware/CsrfGuard.php',
    'UserFrosting\\AccessConditionExpression' => $baseDir . '/models/auth/AccessConditionExpression.php',
    'UserFrosting\\AccountController' => $baseDir . '/controllers/AccountController.php',
    'UserFrosting\\AccountDisabledException' => $baseDir . '/middleware/usersession/Exception.php',
    'UserFrosting\\AccountInvalidException' => $baseDir . '/middleware/usersession/Exception.php',
    'UserFrosting\\AdminController' => $baseDir . '/controllers/AdminController.php',
    'UserFrosting\\ApiController' => $baseDir . '/controllers/ApiController.php',
    'UserFrosting\\AuthCompromisedException' => $baseDir . '/middleware/usersession/Exception.php',
    'UserFrosting\\AuthController' => $baseDir . '/controllers/AuthController.php',
    'UserFrosting\\AuthExpiredException' => $baseDir . '/middleware/usersession/Exception.php',
    'UserFrosting\\Authentication' => $baseDir . '/models/auth/Authentication.php',
    'UserFrosting\\AuthorizationException' => $baseDir . '/models/auth/AuthorizationException.php',
    'UserFrosting\\BaseController' => $baseDir . '/controllers/BaseController.php',
    'UserFrosting\\Context' => $baseDir . '/models/database/Context.php',
    'UserFrosting\\Database' => $baseDir . '/models/database/Database.php',
    'UserFrosting\\DatabaseInvalidException' => $baseDir . '/middleware/usersession/Exception.php',
    'UserFrosting\\DatabaseTable' => $baseDir . '/models/database/DatabaseTable.php',
    'UserFrosting\\EmailRecipient' => $baseDir . '/models/notify/EmailRecipient.php',
    'UserFrosting\\Group' => $baseDir . '/models/database/Group.php',
    'UserFrosting\\GroupAuth' => $baseDir . '/models/database/GroupAuth.php',
    'UserFrosting\\GroupController' => $baseDir . '/controllers/GroupController.php',
    'UserFrosting\\GroupLoader' => $baseDir . '/models/database/GroupLoader.php',
    'UserFrosting\\InstallController' => $baseDir . '/controllers/InstallController.php',
    'UserFrosting\\MdlCacheFlags' => $baseDir . '/models/database/MdlCacheFlags.php',
    'UserFrosting\\MdlCohort' => $baseDir . '/models/database/MdlCohort.php',
    'UserFrosting\\MdlCohortController' => $baseDir . '/controllers/MdlCohortController.php',
    'UserFrosting\\MdlCohortMembers' => $baseDir . '/models/database/MdlCohortMembers.php',
    'UserFrosting\\MdlContext' => $baseDir . '/models/database/MdlContext.php',
    'UserFrosting\\MdlCourseCategories' => $baseDir . '/models/database/MdlCourseCategories.php',
    'UserFrosting\\MdlPermissionsController' => $baseDir . '/controllers/MdlPermissionsController.php',
    'UserFrosting\\MdlRole' => $baseDir . '/models/database/MdlRole.php',
    'UserFrosting\\MdlTag' => $baseDir . '/models/database/MdlTag.php',
    'UserFrosting\\MdlTagInstance' => $baseDir . '/models/database/MdlTagInstance.php',
    'UserFrosting\\MdlRoleAllowAssign' => $baseDir . '/models/database/MdlRoleAllowAssign.php',
    'UserFrosting\\MdlRoleAllowOverride' => $baseDir . '/models/database/MdlRoleAllowOverride.php',
    'UserFrosting\\MdlRoleAllowSwitch' => $baseDir . '/models/database/MdlRoleAllowSwitch.php',
    'UserFrosting\\MdlUser' => $baseDir . '/models/database/MdlUser.php',
    'UserFrosting\\MdlUserController' => $baseDir . '/controllers/MdlUserController.php',
    'UserFrosting\\MdlUserPreferences' => $baseDir . '/models/database/MdlUserPreferences.php',
    'UserFrosting\\Notification' => $baseDir . '/models/notify/Notification.php',
    'UserFrosting\\PageSchema' => $baseDir . '/models/PageSchema.php',
    'UserFrosting\\SiteSettings' => $baseDir . '/models/database/SiteSettings.php',
    'UserFrosting\\UFModel' => $baseDir . '/models/database/UFModel.php',
    'UserFrosting\\User' => $baseDir . '/models/database/User.php',
    'UserFrosting\\UserAuth' => $baseDir . '/models/database/UserAuth.php',
    'UserFrosting\\UserCollection' => $baseDir . '/models/collections/UserCollection.php',
    'UserFrosting\\UserController' => $baseDir . '/controllers/UserController.php',
    'UserFrosting\\UserEvent' => $baseDir . '/models/database/UserEvent.php',
    'UserFrosting\\UserFrosting' => $baseDir . '/models/UserFrosting.php',
    'UserFrosting\\UserLoader' => $baseDir . '/models/database/UserLoader.php',
    'UserFrosting\\UserSession' => $baseDir . '/middleware/usersession/UserSession.php',
    'ntlm_sasl_client_class' => $vendorDir . '/phpmailer/phpmailer/extras/ntlm_sasl_client.php',
    'phpmailerException' => $vendorDir . '/phpmailer/phpmailer/class.phpmailer.php',
);
