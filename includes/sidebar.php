<aside class="app-sidebar">
  <div class="app-sidebar__user">
    <img class="app-sidebar__user-avatar" src="image/avatar.png" alt="User Image">
    <div>
      <p class="app-sidebar__user-name"><?= $_SESSION['fullname'] ?></p>
      <p class="app-sidebar__user-designation"><?= $_SESSION['account_type'] ?></p>
    </div>
  </div>
  <ul class="app-menu">

    <li>
      <a class="app-menu__item" href="dashboard.php"><i class="app-menu__icon bi bi-speedometer"></i><span class="app-menu__label">Dashboard</span></a>
    </li>

    <?php if ($_SESSION['account_type'] == 'User' || $_SESSION['account_type'] == 'Administrator'): ?>
      <!-- staff -->
      <li>
        <a class="app-menu__item" href="add-vaccination.php"><i class="app-menu__icon bi bi-person"></i><span class="app-menu__label">Vaccination</span></a>
      </li>
      <!-- staff -->
      <!-- staff -->
      <li>
        <a class="app-menu__item" href="dog-owners.php"><i class="app-menu__icon bi bi-person"></i><span class="app-menu__label">Pet Owner's</span></a>
      </li>
      <!-- staff -->
      <!-- staff -->
      <?php if ($_SESSION['account_type'] == 'Administrator'): ?>
        <li>
          <a class="app-menu__item" href="reports.php"><i class="app-menu__icon bi bi-list"></i><span class="app-menu__label">Reports</span></a>
        </li>
      <?php endif; ?>
      <!-- staff -->
    <?php endif; ?>
    <?php if ($_SESSION['account_type'] == 'Administrator'): ?>
      <li>
        <a class="app-menu__item" href="users.php"><i class="app-menu__icon bi bi-people"></i><span class="app-menu__label">User Management</span></a>
      </li>
    <?php endif; ?>
    <li>
      <a class="app-menu__item" href="concern.php"><i class="app-menu__icon bi bi-list"></i><span class="app-menu__label">Dog Concern</span></a>
    </li>
    <li>
      <a class="app-menu__item" href="<?php echo base_url ?>logout.php"><i class="app-menu__icon bi bi-arrow-left-circle"></i><span class="app-menu__label">Logout</span></a>
    </li>

  </ul>
</aside>