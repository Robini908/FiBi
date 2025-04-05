<div class="sidebar sidebar-light sidebar-main sidebar-expand-md">
    <div class="sidebar-mobile-toggler">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        <span>Library Navigation</span>
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>

    <div class="sidebar-content">
        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">
                    <div class="mr-3">
                        <a href="#"><img src="{{ Auth::user()->photo ?? Qs::getDefaultUserImage() }}" width="38" height="38" class="rounded-circle" alt=""></a>
                    </div>

                    <div class="media-body">
                        <div class="media-title font-weight-semibold">{{ Auth::user()->name }}</div>
                        <div class="font-size-xs opacity-50">
                            <i class="icon-user font-size-sm"></i> &nbsp;{{ Qs::getUserRole() }}
                        </div>
                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="#" class="text-white"><i class="icon-cog3"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <!-- Main -->
                <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Library</div> <i class="icon-menu" title="Library"></i></li>
                
                <li class="nav-item">
                    <a href="{{ route('library.dashboard') }}" class="nav-link {{ request()->is('library/dashboard*') ? 'active' : '' }}">
                        <i class="icon-home4"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('library.catalog') }}" class="nav-link {{ request()->is('library/catalog*') ? 'active' : '' }}">
                        <i class="icon-books"></i>
                        <span>Book Catalog</span>
                    </a>
                </li>

                @if(Qs::isStudent())
                <li class="nav-item">
                    <a href="{{ route('library.my-books') }}" class="nav-link {{ request()->is('library/my-books*') ? 'active' : '' }}">
                        <i class="icon-bookmark"></i>
                        <span>My Books</span>
                    </a>
                </li>
                @endif

                @if(Qs::isParent())
                <li class="nav-item">
                    <a href="{{ route('library.my-children-books') }}" class="nav-link {{ request()->is('library/my-children-books*') ? 'active' : '' }}">
                        <i class="icon-bookmark"></i>
                        <span>My Children's Books</span>
                    </a>
                </li>
                @endif

                @if(Qs::isLibrarian() || Qs::isAdministrator())
                <li class="nav-item nav-item-submenu {{ request()->is('library/books*') ? 'nav-item-open' : '' }}">
                    <a href="#" class="nav-link"><i class="icon-book3"></i> <span>Book Management</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Book Management" style="{{ request()->is('library/books*') ? 'display:block' : '' }}">
                        <li class="nav-item">
                            <a href="{{ route('library.books.index') }}" class="nav-link {{ request()->is('library/books') ? 'active' : '' }}">
                                All Books
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('library.books.create') }}" class="nav-link {{ request()->is('library/books/create') ? 'active' : '' }}">
                                Add New Book
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('library.categories.index') }}" class="nav-link {{ request()->is('library/categories*') ? 'active' : '' }}">
                                Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('library.authors.index') }}" class="nav-link {{ request()->is('library/authors*') ? 'active' : '' }}">
                                Authors
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item nav-item-submenu {{ request()->is('library/circulation*') ? 'nav-item-open' : '' }}">
                    <a href="#" class="nav-link"><i class="icon-rotate-cw2"></i> <span>Circulation</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Circulation" style="{{ request()->is('library/circulation*') ? 'display:block' : '' }}">
                        <li class="nav-item">
                            <a href="{{ route('library.loans.index') }}" class="nav-link {{ request()->is('library/circulation/loans') ? 'active' : '' }}">
                                Loans
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('library.reservations.index') }}" class="nav-link {{ request()->is('library/circulation/reservations') ? 'active' : '' }}">
                                Reservations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('library.circulation.overdue') }}" class="nav-link {{ request()->is('library/circulation/overdue') ? 'active' : '' }}">
                                Overdue Books
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('library.inventory') }}" class="nav-link {{ request()->is('library/inventory*') ? 'active' : '' }}">
                        <i class="icon-stack"></i>
                        <span>Inventory</span>
                    </a>
                </li>
                @endif

                @if(Qs::isLibrarian() || Qs::isAdministrator() || Qs::isAccountant())
                <li class="nav-item">
                    <a href="{{ route('library.reports') }}" class="nav-link {{ request()->is('library/reports*') ? 'active' : '' }}">
                        <i class="icon-chart"></i>
                        <span>Reports</span>
                    </a>
                </li>
                @endif

                @if(Qs::isLibrarian() || Qs::isAdministrator())
                <li class="nav-item">
                    <a href="{{ route('library.settings') }}" class="nav-link {{ request()->is('library/settings*') ? 'active' : '' }}">
                        <i class="icon-gear"></i>
                        <span>Settings</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div> 