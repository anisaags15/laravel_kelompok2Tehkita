<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">

  <!-- Left navbar links -->
  <ul class="navbar-nav">

    <!-- Toggle Sidebar -->
    <li class="nav-item">
      <a class="nav-link"
         data-widget="pushmenu"
         href="#"
         role="button">

        <i class="fas fa-bars"></i>
      </a>
    </li>

    <!-- Home -->
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('admin.dashboard') }}" class="nav-link">
        Home
      </a>
    </li>

    <!-- Contact -->
    <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link">
        Contact
      </a>
    </li>

  </ul>


  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">

    <!-- Search -->
    <li class="nav-item">
      <a class="nav-link"
         data-widget="navbar-search"
         href="#"
         role="button">

        <i class="fas fa-search"></i>
      </a>

      <div class="navbar-search-block">

        <form class="form-inline">

          <div class="input-group input-group-sm">

            <input class="form-control form-control-navbar"
                   type="search"
                   placeholder="Search"
                   aria-label="Search">

            <div class="input-group-append">

              <button class="btn btn-navbar" type="submit">
                <i class="fas fa-search"></i>
              </button>

              <button class="btn btn-navbar"
                      type="button"
                      data-widget="navbar-search">

                <i class="fas fa-times"></i>
              </button>

            </div>

          </div>

        </form>

      </div>
    </li>


    <!-- Messages -->
    <li class="nav-item dropdown">

      <a class="nav-link"
         data-toggle="dropdown"
         href="#">

        <i class="far fa-comments"></i>
        <span class="badge badge-danger navbar-badge">3</span>
      </a>

      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

        <span class="dropdown-item dropdown-header">
          3 Messages
        </span>

        <div class="dropdown-divider"></div>

        <a href="#" class="dropdown-item text-center">
          See All Messages
        </a>

      </div>

    </li>


    <!-- Notifications -->
    <li class="nav-item dropdown">

      <a class="nav-link"
         data-toggle="dropdown"
         href="#">

        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge">15</span>
      </a>

      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

        <span class="dropdown-item dropdown-header">
          15 Notifications
        </span>

        <div class="dropdown-divider"></div>

        <a href="#" class="dropdown-item text-center">
          See All Notifications
        </a>

      </div>

    </li>


    <!-- Fullscreen -->
    <li class="nav-item">

      <a class="nav-link"
         data-widget="fullscreen"
         href="#"
         role="button">

        <i class="fas fa-expand-arrows-alt"></i>
      </a>

    </li>


    <!-- Logout -->
    <li class="nav-item">

      <a class="nav-link text-danger"
         href="{{ route('logout') }}"
         onclick="event.preventDefault();
         document.getElementById('logout-form').submit();">

        <i class="fas fa-sign-out-alt"></i>
      </a>

      <form id="logout-form"
            action="{{ route('logout') }}"
            method="POST"
            class="d-none">

        @csrf

      </form>

    </li>

  </ul>

</nav>
<!-- /.navbar -->
