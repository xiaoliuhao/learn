<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta name="generator" content="HTML Tidy for HTML5 (experimental) for Windows https://github.com/w3c/tidy-html5/tree/c63cc39" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <title>蓝山工作室后台管理系统</title>
  <link href="<?php echo $base_url;?>resources/css/mystyle-lanshan.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo $base_url;?>resources/css/bootstrap.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo $base_url;?>resources/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo $base_url;?>resources/css/bootstrap-theme.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo $base_url;?>resources/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
  <script src="<?php echo $base_url;?>resources/js/jquery.js">
</script>
  <script src="<?php echo $base_url;?>resources/datepicker/WdatePicker.js">
</script>
</head>

<body>
  <div id="top">
    <div class="logo"><img src="<?php echo $base_url;?>resources/images/logo.fw.png" /></div>

    <h1><img src="<?php echo $base_url;?>resources/images/name.png" /></h1>

    <p><span><a href="<?php echo $base_url?>bluescms/index.php/user/password_change/">修改密码</a></span><span><a href="<?php echo $base_url;?>bluescms/index.php/user/user_info_show/">用户信息</a></span> <span><a href=
    "<?php echo $base_url;?>bluescms/index.php/login/quit/">退出系统</a></span></p>
  </div><!--end of top-->

  <div id="menu">
    <div class="menu_name">
      <span>管理菜单</span>
    </div>

    <div class="menu_user">
	<a href="<?php echo $base_url;?>bluescms/index.php/home/">系统首页</a>
      当前登录用户：<?php echo $login_info['login_user_name'];?> 用户角色：<?php echo $login_info['login_role_name'];?>
    </div>
  </div><!--end of top-->

  <div id="wrapper">
    <div id="nav">
      <div class="accordion" id="accordion2">
        <?php foreach ($admin_menu as $key => $value): ?>
          <?php if ($key): ?>
            <?php $order = 'Two';?>
          <?php else: ?>
            <?php $order = 'One'?>
          <?php endif;?>
        <div class="accordion-group">
          <div class="accordion-heading nav_list">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="<?php echo '#collapse' . $order;?>"><?php echo $value['menu_name'];?></a>
          </div>

          <div id="<?php echo 'collapse' . $order?>" class="accordion-body collapse">
            <div class="accordion-inner">
              <ul class="subnav_list">
                <?php foreach ($value['menu_second'] as $key2 => $value2): ?>
                <li>
                  <a href="<?php echo $base_url . $value2['menu_url'];?>">
                    <?php echo $value2['menu_name'];?></a>
                </li>
                <?php endforeach;?></ul>
            </div>
          </div>
        </div>
        <?php endforeach;?>
        </div>
    </div><!---end of nav-->
	<div>
		<p>当前位置：添加广告项目</p>
	</div>
  <div>
		<form method="post" action="<?php echo $base_url . 'bluescms/index.php/ad/ad_add_post/';?>">
			名称:<input type="text"name="ad_headline"/>
			<br />
      链接:<input type="text"name="ad_url"/>
      <br />
      广告图片:
      <div id ="ueditor">
        <!-- 加载编辑器的容器 -->
        <script id="container" name="content" type="text/plain">
        </script>
        <!-- 配置文件 -->
        <script type="text/javascript" src="<?php echo $base_url;?>bluescms/ueditor/ueditor.config.js"></script>
        <!-- 编辑器源码文件 -->
        <script type="text/javascript" src="<?php echo $base_url;?>bluescms/ueditor/ueditor.all.js"></script>
        <!-- 实例化编辑器 -->
        <script type="text/javascript">
          var ue = UE.getEditor('container');
        </script>
      </div>
			<input type="submit" name ="submit" value="提交"/>
		</form>
	</div>

  </div><!--end of wrapper-->

  <div id="footer">
    <p>版权所有：@2015重庆邮电大学蓝山工作室</p>

    <p>制作人：AnnsShadoW</p>
  </div><script src="<?php echo $base_url;?>resources/js/bootstrap.min.js">
</script> <script src="<?php echo $base_url;?>resources/js/ajax.js">
</script>
</body>
</html>
