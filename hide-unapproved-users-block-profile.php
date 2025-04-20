<?php

class hide_unapproved_users_block_profile
{
    public function match_request($request)
    {
        return preg_match('#^user/([^/]+)$#', $request);
    }

    public function process_request($request)
    {
        preg_match('#^user/([^/]+)$#', $request, $matches);
        $handle = $matches[1];

        $user = qa_db_select_with_pending(
            qa_db_user_account_selectspec($handle, false)
        );

        if (empty($user)) {
            return null;
        }

        $current_userid = qa_get_logged_in_userid();
        $is_admin = qa_get_logged_in_level() >= QA_USER_LEVEL_ADMIN;
        $is_self = $current_userid === $user['userid'];

        $is_approved = ($user['flags'] & QA_USER_FLAGS_APPROVED) !== 0;

        if (!$is_approved && !$is_admin && !$is_self) {
            header("Location: /users", true, 302);
            exit;
        }

        require_once QA_INCLUDE_DIR . 'pages/user-profile.php';
        return qa_page_user_profile($handle, $user['userid'], $user);
    }
}
