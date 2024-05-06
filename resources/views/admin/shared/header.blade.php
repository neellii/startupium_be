<div class="c-wrapper c-fixed-components">
    <header class="c-header c-header-light c-header-fixed c-header-with-subheader">
        <button class="c-header-toggler c-class-toggler ml-3 d-md-down-none" type="button" data-target="#sidebar"
                data-class="c-sidebar-lg-show" responsive="true">
            <span class="c-header-toggler-icon"></span>
        </button>
        <ul class="c-header-nav d-md-down-none">
            <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="{{route('admin.home')}}">Главная</a>
            </li>
            <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="{{route('admin.users.index')}}">Пользователи</a>
            </li>
            <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="{{route('admin.projects.index')}}">Проекты</a>
            </li>
            <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="{{route('admin.comments.index')}}">Комментарии</a>
            </li>
        </ul>
        <ul class="c-header-nav ml-auto mr-4">
            <li class="c-header-nav-item d-md-down-none mx-2"><a class="c-header-nav-link">
                    <svg class="c-icon">
                        <use xlink:href="{{ env('APP_URL', '') }}/icons/sprites/free.svg#cil-bell"></use>
                    </svg>
                </a></li>
            <li class="c-header-nav-item d-md-down-none mx-2"><a class="c-header-nav-link">
                    <svg class="c-icon">
                        <use xlink:href="{{ env('APP_URL', '') }}/icons/sprites/free.svg#cil-list-rich"></use>
                    </svg>
                </a></li>
            <li class="c-header-nav-item d-md-down-none mx-2"><a class="c-header-nav-link">
                    <svg class="c-icon">
                        <use xlink:href="{{ env('APP_URL', '') }}/icons/sprites/free.svg#cil-envelope-open"></use>
                    </svg>
                </a></li>
            <li class="c-header-nav-item dropdown"><a class="c-header-nav-link" data-toggle="dropdown" href="#"
                                                      role="button" aria-haspopup="true" aria-expanded="false">
                    <div class="c-avatar">
                        <img class="c-avatar-img" src="{{auth()->user()->getAvatarUrl()}}" alt="Admin">
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right pt-0">
                    <div class="dropdown-header bg-light py-2"><strong>Меню</strong></div>
                    <a class="dropdown-item" href="{{route('admin.home')}}">
                        <svg class="c-icon mr-2">
                            <use xlink:href="{{ env('APP_URL', '') }}/icons/sprites/free.svg#cil-applications"></use>
                        </svg>
                        Главная
                    </a>
                    <a class="dropdown-item" href="{{route('admin.users.index')}}">
                        <svg class="c-icon mr-2">
                            <use xlink:href="{{ env('APP_URL', '') }}/icons/sprites/free.svg#cil-people"></use>
                        </svg>
                        Пользователи
                    </a>
                    <a class="dropdown-item" href="{{route('admin.projects.index')}}">
                        <svg class="c-icon mr-2">
                            <use xlink:href="{{ env('APP_URL', '') }}/icons/sprites/free.svg#cil-file"></use>
                        </svg>
                        Проекты
                    </a>
                    <a class="dropdown-item" href="{{route('admin.comments.index')}}">
                        <svg class="c-icon mr-2">
                            <use xlink:href="{{ env('APP_URL', '') }}/icons/sprites/free.svg#cil-comment-square"></use>
                        </svg>
                        Комментарии
                    </a>
                    <div class="dropdown-header bg-light py-2"><strong>Жалобы</strong></div>
                    <a class="dropdown-item" href="#">
                        <svg class="c-icon mr-2">
                            <use xlink:href="{{ env('APP_URL', '') }}/icons/sprites/free.svg#cil-file"></use>
                        </svg>
                        Проекты
                        <span class="badge badge-danger ml-auto">42</span>
                    </a>
                    <a class="dropdown-item" href="#">
                        <svg class="c-icon mr-2">
                            <use xlink:href="{{ env('APP_URL', '') }}/icons/sprites/free.svg#cil-comment-square"></use>
                        </svg>
                        Комментарии
                        <span class="badge badge-danger ml-auto">42</span>
                    </a>
                    <a class="dropdown-item" href="#">
                        <svg class="c-icon mr-2">
                            <use xlink:href="{{ env('APP_URL', '') }}/icons/sprites/free.svg#cil-envelope-open"></use>
                        </svg>
                        Сообщения
                        <span class="badge badge-danger ml-auto">42</span>
                    </a>
                    <div class="dropdown-header bg-light py-2"><strong>Настройки</strong></div>
                    <a class="dropdown-item" href="{{route('admin.users.show', auth()->id())}}">
                        <svg class="c-icon mr-2">
                            <use xlink:href="{{ env('APP_URL', '') }}/icons/sprites/free.svg#cil-user"></use>
                        </svg>
                        Профиль
                    </a>
                    <a class="dropdown-item" href="#">
                        <svg class="c-icon mr-2">
                            <use xlink:href="{{ env('APP_URL', '') }}/icons/sprites/free.svg#cil-file"></use>
                        </svg>
                        Роли и разрешения
                    </a>
                    <div class="dropdown-header bg-light py-2">
                        <strong>DurDom.online</strong>
                    </div>
                    <a class="dropdown-item" href="/">
                        <svg class="c-icon mr-2">
                            <use xlink:href="{{ env('APP_URL', '') }}/icons/sprites/free.svg#cil-share"></use>
                        </svg>
                        На сайт
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">
                        <svg class="c-icon mr-2">
                            <use xlink:href="{{ env('APP_URL', '') }}/icons/sprites/free.svg#cil-account-logout"></use>
                        </svg>
                        <form action="{{route('logout')}}" method="POST"> @csrf
                            <button type="submit" class="btn btn-ghost-dark btn-block">Выйти</button>
                        </form>
                    </a>
                </div>
            </li>
        </ul>
    </header>
