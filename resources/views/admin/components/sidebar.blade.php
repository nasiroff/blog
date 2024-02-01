<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <div class="sidebar-content">
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <li class="nav-item nav-item-submenu {!! activeRoute('admin.posts.*', 'nav-item-expanded nav-item-open') !!}">
                    <a href="#" class="nav-link">
                        <i class="icon-image2"></i>
                        <span>
                            Posts &nbsp;&nbsp;&nbsp;<span class="border-1 pl-1 pr-1 rounded-pill">{!! $postCount !!}</span>
                        </span>
                    </a>
                    <ul class="nav nav-group-sub" data-submenu-title="Layouts">
                        <li class="nav-item">
                            <a href="{!! route('admin.posts.index', ['status' => \App\Models\Post::STATUS_PENDING]) !!}"
                               class="nav-link
                                        {!! whenActiveRoute(request()->routeIs('admin.posts.index') && request()->input('status') == 1) !!}"
                            >
                                Gozlemede olanlar postlar
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-group-sub" data-submenu-title="Layouts">
                        <li class="nav-item">
                            <a href="{!! route('admin.posts.index', ['status' => \App\Models\Post::STATUS_ACCEPTED]) !!}"
                               class="nav-link
                                        {!! whenActiveRoute(request()->routeIs('admin.posts.index') && request()->input('status') == \App\Models\Post::STATUS_ACCEPTED) !!}"
                            >
                                Qebul edilmisler
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-group-sub" data-submenu-title="Layouts">
                        <li class="nav-item">
                            <a href="{!! route('admin.posts.index', ['status' => \App\Models\Post::STATUS_REJECTED]) !!}"
                               class="nav-link
                                        {!! whenActiveRoute(request()->routeIs('admin.posts.index') && request()->input('status') == \App\Models\Post::STATUS_REJECTED) !!}"
                            >
                                Legv edilmisler
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-group-sub" data-submenu-title="Layouts">
                        <li class="nav-item">
                            <a href="{!! route('admin.posts.popular') !!}"
                               class="nav-link
                                        {!! activeRoute('admin.posts.popular') !!}"
                            >
                                Legv edilmisler
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                            <a href="{!! route('admin.users.index', ['status' => \App\Models\Post::STATUS_PENDING]) !!}"
                               class="nav-link {!! activeRoute('admin.users.index') !!}">
                                Istifadeciler &nbsp;&nbsp;&nbsp;
                                <span class="border-1 pl-1 pr-1 rounded-pill">{!! $userCount !!}</span>
                            </a>
                </li>
                {{--                <li class="nav-item nav-item-submenu @if(request()->routeIs('admin.projects.*')) nav-item-expanded nav-item-open @endif">--}}
                {{--                    <a href="#" class="nav-link"><i class="icon-drawer"></i> <span>Proyektlər</span></a>--}}
                {{--                    <ul class="nav nav-group-sub" data-submenu-title="Layouts">--}}
                {{--                        <li class="nav-item"><a href="{!! route('admin.projects.index', ['hidden-projects' => 0]) !!}"--}}
                {{--                                                class="nav-link @if(request()->routeIs('admin.projects.index') && intval(request()->query('hidden-projects')) === 0) active @endif">Açıq--}}
                {{--                                proyektlər</a></li>--}}
                {{--                    </ul>--}}
                {{--                    <ul class="nav nav-group-sub" data-submenu-title="Layouts">--}}
                {{--                        <li class="nav-item"><a href="{!! route('admin.projects.index', ['hidden-projects' => 1]) !!}"--}}
                {{--                                                class="nav-link @if(intval(request()->routeIs('admin.projects.index') && request()->query('hidden-projects')) === 1) active @endif">Gizli--}}
                {{--                                proyektlər</a></li>--}}
                {{--                    </ul>--}}
                {{--                </li>--}}
                {{--                <li class="nav-item">--}}
                {{--                    <a href="{!! route('admin.translations.index') !!}"--}}
                {{--                       class="nav-link @if(request()->routeIs('admin.translations.*')) active @endif">--}}
                {{--                        <i class="icon-transmission"></i>--}}
                {{--                        <span>--}}
                {{--									Tərcümələr--}}
                {{--								</span>--}}
                {{--                    </a>--}}
                {{--                </li>--}}
                {{--                <li class="nav-item nav-item-submenu @if(request()->routeIs('admin.payments.*')) nav-item-expanded nav-item-open @endif">--}}
                {{--                    <a href="#" class="nav-link"><i class="icon-coin-dollar"></i> <span>Ödənişlər</span></a>--}}
                {{--                    <ul class="nav nav-group-sub" data-submenu-title="Layouts">--}}
                {{--                        <li class="nav-item"><a href="{!! route('admin.payments.index') !!}"--}}
                {{--                                                class="nav-link @if(request()->routeIs('admin.payments.index') && intval(request()->query('hidden-projects')) === 0) active @endif">Tamalanmış</a>--}}
                {{--                        </li>--}}
                {{--                    </ul>--}}
                {{--                    <ul class="nav nav-group-sub" data-submenu-title="Layouts">--}}
                {{--                        <li class="nav-item"><a href="{!! route('admin.payments.index') !!}"--}}
                {{--                                                class="nav-link @if(request()->routeIs('admin.payments.index')) active @endif">Tamamlanmamış</a>--}}
                {{--                        </li>--}}
                {{--                    </ul>--}}
                {{--                </li>--}}
            </ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        setTimeout(function () {
            $('.is-open').trigger('click');
        }, 2000);
    });
</script>
