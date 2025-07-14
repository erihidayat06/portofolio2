 <!-- ======= Sidebar ======= -->
 <aside id="sidebar" class="sidebar">

     <ul class="sidebar-nav" id="sidebar-nav">

         <li class="nav-item">
             <a class="nav-link {{ Request::is('admin') ? '' : 'collapsed' }}" href="/admin">
                 <i class="bi bi-grid"></i>
                 <span>Dashboard</span>
             </a>
         </li><!-- End Dashboard Nav -->
         <li class="nav-item">
             <a class="nav-link {{ Request::is('admin/bahasa*') ? '' : 'collapsed' }}" href="/admin/bahasa">
                 <i class="bi bi-code-slash"></i>
                 <span>Bahasa pemprograman</span>
             </a>
         </li><!-- End Dashboard Nav -->
         <li class="nav-item">
             <a class="nav-link {{ Request::is('admin/framework*') ? '' : 'collapsed' }}" href="/admin/framework">
                 <i class="bi bi-grid-1x2"></i>
                 <span>Framework</span>
             </a>
         </li><!-- End Dashboard Nav -->
         <li class="nav-item">
             <a class="nav-link {{ Request::is('admin/projek*') ? '' : 'collapsed' }}" href="/admin/projek">
                 <i class="bi bi-list-ul"></i>
                 <span>Projek</span>
             </a>
         </li><!-- End Dashboard Nav -->
         <li class="nav-item">
             <a class="nav-link {{ Request::is('profile*') ? '' : 'collapsed' }}" href="/profile">
                 <i class="bi bi-card-list"></i>
                 <span>Profil</span>
             </a>
         </li><!-- End Dashboard Nav -->



     </ul>

 </aside><!-- End Sidebar-->
