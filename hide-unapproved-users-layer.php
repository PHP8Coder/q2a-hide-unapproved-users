<?php

class qa_html_theme_layer extends qa_html_theme_base
{
    public function ranking($ranking)
    {
        if ($this->template === 'users' && isset($ranking['items'])) {
            if (qa_get_logged_in_level() >= QA_USER_LEVEL_ADMIN) {
                parent::ranking($ranking);
                return;
            }

            $filtered = [];

            foreach ($ranking['items'] as $item) {
                if (!isset($item['label'])) {
                    continue;
                }

                $handle = trim(strip_tags($item['label']));
                $user = qa_db_select_with_pending(
                    qa_db_user_account_selectspec($handle, false)
                );

                if (($user['flags'] & QA_USER_FLAGS_APPROVED) !== 0 && ($user['flags'] & QA_USER_FLAGS_USER_BLOCKED) === 0) {
                    $filtered[] = $item;
                }
            }

            $ranking['items'] = $filtered;
        }

        parent::ranking($ranking);
    }
  
  public function main()
  {
    
    if ($this->template === 'users' && qa_get_logged_in_level() < QA_USER_LEVEL_ADMIN) {
      $pagesize = qa_opt('page_size_users');
      $start = qa_get_start();
      
      $user_count = qa_db_read_one_value(
        qa_db_query_sub(
          "SELECT COUNT(*) FROM ^users WHERE (flags & #) = 0 AND (flags & #) != 0",
          QA_USER_FLAGS_USER_BLOCKED,
          QA_USER_FLAGS_APPROVED
        ),
        true
      );
      
      $this->content['page_links'] = qa_html_page_links(
        qa_request(),
        $this->request,
        $user_count,
        $start,
        $pagesize,
        null,
        null,
        false,
        false
      );
      
      unset($this->content['navigation']['page']);
    }
    
    parent::main();
  }

}

