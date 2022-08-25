<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="<?=$data['adminPanelUrl']?>dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">


      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?=base_url()?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Home</p>
                </a>
              </li>
            </ul>
          </li>
      
          <li class="nav-header">STUDENTS</li>
         
          <li class="nav-item">
            <a href="<?=base_url()?>student/list" class="nav-link">
              <i class="nav-icon fa-solid fa-user"></i>
              <p>
                Students
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?=base_url()?>student/list" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=base_url()?>student/addStudent" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-header">TEACHERS</li>
         
         <li class="nav-item">
           <a href="<?=base_url()?>teacher/list" class="nav-link">
             <i class="nav-icon fa-solid fa-user"></i>
             <p>
               Teachers
               <i class="fas fa-angle-left right"></i>
             </p>
           </a>
           <ul class="nav nav-treeview">
             <li class="nav-item">
               <a href="<?=base_url()?>teacher/list" class="nav-link">
                 <i class="far fa-circle nav-icon"></i>
                 <p>List</p>
               </a>
             </li>
             <li class="nav-item">
               <a href="<?=base_url()?>teacher/addTeacher" class="nav-link">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Add</p>
               </a>
             </li>
           </ul>
         </li>

         <li class="nav-header">MASTERS</li>
         
         <li class="nav-item">
           <a href="<?=base_url()?>master/" class="nav-link">
             <i class="nav-icon fa-solid fa-user"></i>
             <p>
               Masters
               <i class="fas fa-angle-left right"></i>
             </p>
           </a>
           <ul class="nav nav-treeview">
             <li class="nav-item">
               <a href="<?=base_url()?>master/cityMaster" class="nav-link">
                 <i class="far fa-circle nav-icon"></i>
                 <p>City Master</p>
               </a>
             </li>
             <li class="nav-item">
               <a href="<?=base_url()?>master/stateMaster" class="nav-link">
                 <i class="far fa-circle nav-icon"></i>
                 <p>State Master</p>
               </a>
             </li>
             <li class="nav-item">
               <a href="<?=base_url()?>master/classMaster" class="nav-link">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Class Master</p>
               </a>
             </li>
             <li class="nav-item">
               <a href="<?=base_url()?>master/sectionMaster" class="nav-link">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Section Master</p>
               </a>
             </li>
             <li class="nav-item">
               <a href="<?=base_url()?>master/subjectMaster" class="nav-link">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Subject Master</p>
               </a>
             </li>
             <li class="nav-item">
               <a href="<?=base_url()?>master/weekMaster" class="nav-link">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Week Master</p>
               </a>
             </li>
             <li class="nav-item">
               <a href="<?=base_url()?>master/hourMaster" class="nav-link">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Hour Master</p>
               </a>
             </li>
             <li class="nav-item">
               <a href="<?=base_url()?>master/teacherSubjectsMaster" class="nav-link">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Teachers Subjects Master</p>
               </a>
             </li>
             <li class="nav-item">
               <a href="<?=base_url()?>master/timeTableSheduleMaster" class="nav-link">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Time Table Master</p>
               </a>
             </li>
             <li class="nav-item">
               <a href="<?=base_url()?>master/panelUserMaster" class="nav-link">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Panel User Master</p>
               </a>
             </li>
           </ul>
         </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>