$(document).ready(function() {
  $('.menu-item').click(function() {
    // 切换二级菜单的显示/隐藏状态
    $(this).children('.submenu').slideToggle();

    // 隐藏其他已展开的二级菜单
    $(this)
      .siblings('.menu-item')
      .children('.submenu')
      .slideUp();
  });

  $('.submenu-item').click(function(event) {
    event.stopPropagation(); // 阻止事件冒泡，避免点击二级菜单时触发一级菜单的点击事件

    // 切换三级菜单的显示/隐藏状态
    $(this).children('.sub-submenu').slideToggle();

    // 隐藏其他已展开的三级菜单
    $(this)
      .siblings('.submenu-item')
      .children('.sub-submenu')
      .slideUp();
  });

  // 点击页面任意位置，隐藏所有菜单
  /*$(document).click(function() {
    $('.submenu').slideUp();
    $('.sub-submenu').slideUp();
  });*/
});
