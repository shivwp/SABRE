<style type="text/css">
    .app-sidebar {
    background: #325187!important;
    color: #ffffff;
}
.side-header {
    display: flex;
    border-bottom: 1px solid #eaedf1;
    padding: 15px 10px;
    transition: left .3s ease,width .3s ease;
}
.slide-item.active, .slide-item:focus, .slide-item:hover {
    text-decoration: none;
    color: #fff;
}
#back-to-top {
    background-image: linear-gradient(90deg,#003ba1 0,#879abb) !important;
}
.user-info .text-dark {
    color: #ffffff!important;
}
.user-info .text-muted {
    color: #ffffff!important;
}
.sidebar-navs a {
    color: #fff!important;
}
.side-menu__item {
    color: #ffffff;
}
.side-menu .side-menu__icon {
    color: #ffffff;
}
.side-menu__item.active{
    background-color: #ffffff1f;
    color: #fff;
}
.slide-menu a.active {
    color: #fff;
}
.slide-item {
    color: #fff;
}
.nav {
    display: flex;
    flex-wrap: wrap;
    padding-left: 0;
    margin-bottom: 0;
    list-style: none;
    justify-content: center;
}
img.header-brand-img.light-logo1{
    width: 125px;
    height: auto;
}
.side-header .header-brand1 {
    display: flex;
    width: 100%;
    text-align: center;
    justify-content: center;
}
.side-menu__item {
    padding: 5px 5px;
}
.hor-menu .horizontalMenu>.horizontalMenu-list>li>a.active, .hor-menu .horizontalMenu>.horizontalMenu-list>li>a:hover, .horizontalMenu>.horizontalMenu-list>li>.horizontal-megamenu .link-list li a:hover, .horizontalMenu>.horizontalMenu-list>li>ul.sub-menu>li>a:hover, .horizontalMenu>.horizontalMenu-list>li>ul.sub-menu>li>ul.sub-menu>li>a:hover, .side-menu__item.active, .side-menu__item.active .side-menu__icon, .side-menu__item:focus, .side-menu__item:focus .side-menu__icon, .side-menu__item:hover, .side-menu__item:hover .side-menu__icon {
    background-color: #ffffff1f;
    color: #fff;
}
</style>
<!--APP-SIDEBAR-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
                <aside class="app-sidebar">
                    <div class="side-header">
                        <a class="header-brand1" href="{{ url('/' . $page='index') }}">
                            <img src="{{URL::asset('/images/logo/1657177079.png')}}" class="header-brand-img desktop-logo" alt="logo">
                            <img src="{{URL::asset('/images/logo/1657177079.png')}}"  class="header-brand-img toggle-logo" alt="logo">
                            <img src="{{URL::asset('/images/logo/1657177079.png')}}" class="header-brand-img light-logo" alt="logo">
                            <img src="{{URL::asset('/images/logo/1657177079.png')}}" class="header-brand-img light-logo1" alt="logo">
                        </a><!-- LOGO -->
                        <a aria-label="Hide Sidebar" class="app-sidebar__toggle ml-auto" data-toggle="sidebar" href="#"></a><!-- sidebar-toggle-->
                    </div>
                    <div class="app-sidebar__user">
                        <div class="dropdown user-pro-body text-center">
                            <div class="user-pic">

                                <img src="{{url('/')}}/{{ Auth::user()->profile_image}}" alt="user-img" class="avatar-xl rounded-circle">
                            </div>
                            <div class="user-info">
                                <h6 class=" mb-0 text-dark">{{ Auth::user()->roles->first()->title}}</h6>
                                <span class="text-muted app-sidebar__user-name text-sm">Administrator</span>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar-navs">
                        <ul class="nav  nav-pills-circle">
                            <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Settings">
                                <a class="nav-link text-center m-2" href="{{url('/dashboard/settings')}}">
                                    <i class="fe fe-settings"></i>
                                </a>
                            </li>
                            <li>
                            </li>
                            {{-- <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Chat">
                                <a class="nav-link text-center m-2">
                                    <i class="fe fe-mail"></i>
                                </a>
                            </li> --}}
                            {{-- <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Followers">
                                <a class="nav-link text-center m-2">
                                    <i class="fe fe-user"></i>
                                </a>
                            </li> --}}
                            <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Logout">
                                <a href="{{ route('login') }}" class="nav-link text-center m-2" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <i class="fe fe-power"></i>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                    <ul class="side-menu mt-3" >
                        <li class="slide">
                            <a class="side-menu__item" data-toggle="slide" href="{{url('/')}}"><i class="side-menu__icon ti-home"></i><span class="side-menu__label">Dashboard</span></a>
                            {{--<ul class="slide-menu">
                                <li><a href="{{ url('/' . $page='home') }}" class="slide-item">Sales</a></li>
                                <li class="sub-slide">
                                    <a class="sub-side-menu__item" data-toggle="sub-slide" href="#"><span class="sub-side-menu__label">Marketing</span><i class="sub-angle fa fa-angle-right"></i></a>
                                    <ul class="sub-slide-menu">
                                        <li><a class="sub-slide-item" href="{{ route('dashboard.dashboard.index') }}">Rewards</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ url('/' . $page='index3') }}" class="slide-item">Service</a></li>
                                <li><a href="{{ url('/' . $page='index4') }}" class="slide-item">Finance</a></li>
                                <li><a href="{{ url('/' . $page='index2') }}" class="slide-item">Operation</a></li>
                                <li><a href="{{ url('/' . $page='index5') }}" class="slide-item">Support</a></li>
                                <li><a href="{{ url('/' . $page='index3') }}" class="slide-item">Delivery</a></li>
                                <li><a href="{{ url('/' . $page='index4') }}" class="slide-item">Party</a></li>
                                <li><a href="{{ url('/' . $page='index5') }}" class="slide-item">IT</a></li>
                            </ul>--}}
                        </li>
                        @can('page_access')
                        <li class="slide">
                            <a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon fe fe-book-open"></i><span class="side-menu__label">Pages</span><i class="angle fa fa-angle-right"></i></a>
                            <ul class="slide-menu">
                                <!-- <li><a href="{{ route('dashboard.homepage.edit',1) }}" class="slide-item">Home</a></li> -->
                                <li><a href="{{ route('dashboard.pages.index') }}" class="slide-item">Page List</a></li>
                                <li><a href="{{ route('dashboard.pages.create') }}" class="slide-item">Add New </a></li>
                            </ul>
                        </li>
                        @endcan
                        @can('blogs_access')
                          <li class="slide">
                            <a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon fe fe-message-square"></i><span class="side-menu__label">Blogs</span><i class="angle fa fa-angle-right"></i></a>
                            <ul class="slide-menu">
                                <!-- <li><a href="{{ route('dashboard.blog-category.index') }}" class="slide-item">Feed Category</a></li>
                                <li><a href="{{ route('dashboard.blog-tags.index') }}" class="slide-item">Feed Tags</a></li> -->
                                <li><a href="{{ route('dashboard.blogs.index') }}" class="slide-item">Blog List</a></li>
                            </ul>
                        </li>
                        @endcan
                        <li class="slide">
                            <a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon fa fa-commenting"></i><span class="side-menu__label">FAQ</span><i class="angle fa fa-angle-right"></i></a>
                                <ul class="slide-menu">
                                    <li><a href="{{ route('dashboard.faq.create') }}" class="slide-item">Add New</a></li>
                                    <li><a href="{{ route('dashboard.faq.index') }}" class="slide-item">FAQ List</a></li>
                                </ul>
                         </li>
                        @can('menu_access')
                         <li>
                            <a class="side-menu__item" href="{{ route('dashboard.menus.index') }}"><i class="side-menu__icon icon icon-list"></i><span class="side-menu__label">Menus</span></a>
                        </li>
                        @endcan
                        {{--<li class="slide">
                            <a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon fa fa-female"></i><span class="side-menu__label">Models Management</span><i class="angle fa fa-angle-right"></i></a>
                            <ul class="slide-menu">
                                <li><a href="{{ route('dashboard.models.index') }}" class="slide-item">Models</a></li>
                                <li><a href="{{ route('dashboard.model-orientation.index') }}" class="slide-item">Model Orientation</a></li>
                                <li><a href="{{ route('dashboard.model-category.index') }}" class="slide-item">Model Category</a></li>
                                <li><a href="{{ route('dashboard.model-ethnicity.index') }}" class="slide-item">Model Ethnicity</a></li>
                                <li><a href="{{ route('dashboard.model-language.index') }}" class="slide-item">Model Language</a></li>
                                <li><a href="{{ route('dashboard.model-hair.index') }}" class="slide-item">Model Hair</a></li>
                                <li><a href="{{ route('dashboard.model-fetishes.index') }}" class="slide-item">Model Fetishes</a></li>
                            </ul>
                        </li>--}}
                        <!-- <li class="slide">
                            <a class="side-menu__item" data-toggle="slide" href="#"> <i class="side-menu__icon fa fa-clock-o"></i><span class="side-menu__label">User Availability</span><i class="angle fa fa-angle-right"></i></a>
                            <ul class="slide-menu">
                                <li><a href="{{ route('dashboard.user-availability.index') }}" class="slide-item">Availability List</a></li>
                            </ul>
                        </li> -->
                        <li>
                            <a class="side-menu__item" href="{{ route('dashboard.guard.index') }}"><i class="side-menu__icon fa fa-shield"></i><span class="side-menu__label">Guards</span></a>
                        </li>
                        <li class="slide">
                            <a class="side-menu__item" data-toggle="slide" href="#"> <i class="side-menu__icon fa fa-briefcase"></i><span class="side-menu__label">Task Management</span><i class="angle fa fa-angle-right"></i></a>
                            <ul class="slide-menu">
                                <li><a href="{{ route('dashboard.jobs.index') }}" class="slide-item">Jobs</a></li>
                                <li><a href="{{ route('dashboard.job-category.index') }}" class="slide-item">Category</a></li>
                                <li><a href="{{ route('dashboard.job-apply.index') }}" class="slide-item">Applied Jobs</a></li>
                            </ul>
                        </li>
                        @can('user_management_access')
                           <li class="slide">
                                <a class="side-menu__item" data-toggle="slide" href="#"> <i class="side-menu__icon fe fe-user"></i><span class="side-menu__label">Users Management</span><i class="angle fa fa-angle-right"></i></a>
                                <ul class="slide-menu">
                                    @can('role_access')
                                    <li><a href="{{ route('dashboard.roles.index') }}" class="slide-item">User Roles</a></li>
                                    @endcan

                                    @can('permission_access')
                                    <li><a href="{{ route('dashboard.permissions.index') }}" class="slide-item">Role Permissions</a></li>
                                    @endcan

                                    @can('user_access')
                                    <li><a href="{{ route('dashboard.users.index') }}" class="slide-item">User</a></li>
                                    @endcan
                                </ul>
                           </li>
                        @endcan
                        <li class="slide">
                            <a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon fe fe-settings"></i><span class="side-menu__label"> Web Settings</span><i class="angle fa fa-angle-right"></i></a>
                            <ul class="slide-menu">
                                <li><a href="{{ route('dashboard.mail.index') }}" class="slide-item">Mail Template</a></li>
                                <li><a href="{{ route('dashboard.settings.index') }}" class="slide-item">Settings</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="side-menu__item" href="{{ route('dashboard.task-log.index') }}"><i class="side-menu__icon fa fa-tasks"></i><span class="side-menu__label">Task Log</span></a>
                        </li>
                        <li>
                            <a class="side-menu__item" href="{{ route('dashboard.logActivity') }}"><i class="side-menu__icon icon icon-clock"></i><span class="side-menu__label">Logs Activity</span></a>
                        </li>
                        {{--@can('support_access')
                        <li class="slide">
                            <a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon fe fe-slack"></i><span class="side-menu__label">Support</span><i class="angle fa fa-angle-right"></i></a>
                            <ul class="slide-menu">
                                <li><a href="{{ route('dashboard.support-tickets.index') }}" class="slide-item">All tickets</a></li>
                                <li><a href="{{ route('dashboard.support-category.index') }}" class="slide-item">Support Category</a></li>
                            </ul>
                        </li>
                        @endcan--}}
                        {{--<li>
                            <a class="side-menu__item" href="{{route("dashboard.cookie-cache-clear")}}"><i class="side-menu__icon ti-brush-alt"></i><span class="side-menu__label">Clear Cache</span></a>
                        </li>--}}
                    </ul>
                </aside>



<!--/APP-SIDEBAR-->



