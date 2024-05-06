<?php

return [
    // Exception messages
    'user_not_found' => 'Пользователь не найден.',
    'check_user' => 'Внимание, проверьте пользователя.',
    'message_not_found' => 'Сообщение не найдено.',
    'comment_not_found' => 'Комментарий не найден или был удален.',
    'project_not_found' => 'Проект не найден.',
    'email_exists' => 'Такой адрес электронной почты уже существует.',
    'something_went_wrong' => 'Ой... Что-то пошло не так, попробуйте позже.',
    'login_error' => 'Электронный адрес пользователя или пароль не совпадают.',
    'unique_email' => 'Такой адрес электронной почты уже существует.',
    'user_with_email_no_exists' => 'Пользователя с таким адресом электронной почты не существует.',
    'last_email_at' => 'Письмо с ссылкой для сброса пароля можно отправлять раз в 5 минут.',
    'email_link_time_is_up' => 'Время действия ссылки для сброса пароля истекло.',
    'too_many_requests' => 'Слишком много запросов.',
    'route_not_found' => 'Такого пути не существует',
    'email_has_not_verified' => 'Ваша электронная почта не подтверждена.',
    'socket_exception_access_denied' => 'Access denied.',
    'companion_not_selected' => 'Собеседник не выбран.',
    'wiki_section_already_exists' => 'Раздел с таким названием уже существует.',
    'wiki_section_not_found' => 'Такой раздел не найден.',
    'wiki_article_not_found' => 'Такая страница не найдена.',
    'wiki_article_already_exists' => 'Такой документ уже существует.',
    'application_already_exists' => 'Ваша заявка на рассмотрении.',
    'application_already_accepted' => 'Заявка уже принята.',
    'no_such_position_exists' => 'Такой специальности не существует.',
    'position_already_exists' => 'Такая специальность уже существует.',
    'subscriber_not_found' => 'Подписчик не найден.',
    'access_denied' => 'Отказано в доступе.',
    'content_not_found' => 'Упс.., ничего не найдено.',
    'project_title_exists' => 'Такое название проекта уже существует.',
    'unauthenticated' => 'Unauthenticated.',
    'blog_already_in_favorites' => 'This blog is already added to favorites.',
    'blog_already_out_favorites' => 'This blog is already removed from favorites.',
    'transmit_incorrect_data' => 'Переданные данные не верны.',

    // Per page
    'comments_per_page' => 20,
    'replies_per_page' => 10,
    'messages_per_page' => 50,
    'notifications_per_page' => 30,
    'projects_per_page' => 30,
    'project_subscribers_per_page' => 10,
    'last_email_minutes' => 2.5,
    'blogs_per_page' => 5,
    // Admin per page
    'user_projects_per_page' => 6,
    'users_per_page' => 30,
    'all_comments_per_page' => 25,
    'comments_children' => 10,
    'countries_per_page' => 100,
    'regions_per_page' => 100,
    'cities_per_page' => 300 ,

    // free icon
    'free_icon' => 'https://www.freeiconspng.com/uploads/no-image-icon-0.png',

    // success
    'user_successfully_deleted' => 'Пользователь успешно удален.',
    'mail_successfully_changed' => 'Ваш адрес электронной почты успешно изменен.',
    'password_successfully_changed' => 'Ваш пароль успешно изменен.',
    'account_protected' => 'Ваша учетная запись защищена.',
    'email_successfully_verified' => 'Ваш адрес электронной почты успешно подтвержден.',
    'password_reset_successfully_sent' => 'Письмо с ссылкой для сброса пароля отправлено на вашу почту.',
    'account_successfully_created' => 'Аккаунт успешно создан. Проверьте свою электронную почту.',

    'comment_remove' => 'Комментарий удален.',

    // tokens expires_in
    'access_token_expires_in' => 720, // passport умножает время на 2
    'refresh_token_expires_in' => 15, // passport умножает время на 2
    'access_token_expires_message' => 'Token expiration time is up.',
];
